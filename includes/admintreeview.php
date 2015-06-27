<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	if (!defined("admintreeview_DEFINED"))
		{
			sm_add_cssfile('common_admintreeview.css');
			sm_add_jsfile('common_admintreeview.js');

			class TTreeView
				{
					var $items;
					var $prefix;
					private $currentitem;

					function TTreeView($prefix=NULL)
						{
							global $sm;
							$sm['admintreeview_count']++;
							if ($prefix===NULL)
								$this->prefix=$sm['admintreeview_count'];
							else
								$this->prefix=$prefix;
							if (!empty($sm['admintreeview']['globalclass']))
								$this->AddClassnameGlobal($sm['admintreeview']['globalclass']);
							$this->items[0]=Array('id'=>'0', 'subitems_count'=>0, 'is_root'=>true, 'root_id'=>NULL, 'level'=>0,'expanded'=>true, 'globalindex'=>0, 'parentlist'=>'');
							$this->SetCutrrentItem();
						}
					
					function SetRootIDOfTheTree($index='0')
						{
							$this->items[0]['id']=$index;
						}
					
					function AddItem($id, $title, $root_item_id=0)
						{
							$rootitem=&$this->GetItemByID($root_item_id);
							$this->items[]=Array(
								'id'=>$id, 
								'title'=>$title, 
								'subitems_count'=>0, 
								'is_root'=>false, 
								'level'=>$rootitem['level']+1,
								'root_id'=>$rootitem['id'],
								'expanded'=>false
							);
							$this->SetCutrrentItem();
							$this->currentitem['globalindex']=count($this->items)-1;
							$this->currentitem['parentlist']=(strlen($rootitem['parentlist'])>0?$rootitem['parentlist'].',':'').$this->prefix.'-'.$this->currentitem['globalindex'];
							$rootitem['subitems_count']++;
							return $this;
						}
					
					function AddItemToCurrent($id, $title)
						{
							$this->AddItem($id, $title, $this->currentitem['id']);
							return $this;
						}
					
					function SetCutrrentItem($id=NULL)
						{
							if ($id===NULL)
								$this->currentitem=&$this->items[count($this->items)-1];
							else
								$this->currentitem=&$this->GetItemByID($id);
						}

					function &GetCutrrentItem()
						{
							return $this->currentitem;
						}

					function Checkbox($varname, $value, $checked=false, $parentcheck=false)
						{
							$this->currentitem['checkbox']=true;
							$this->currentitem['varname']=$varname;
							$this->currentitem['checkbox_value']=$value;
							$this->currentitem['checkbox_checked']=$checked;
							$this->currentitem['checkbox_parentcheck']=$parentcheck;
						}

					function Radio($varname, $value, $checked=false)
						{
							$this->currentitem['radio']=true;
							$this->currentitem['radio_varname']=$varname;
							$this->currentitem['radio_value']=$value;
							$this->currentitem['radio_checked']=$checked;
						}

					function SetCheckboxChecked($checked=true)
						{
							$this->currentitem['checkbox_checked']=$checked;
						}

					function Expand()
						{
							$item=$this->GetCutrrentItem();
							$item['expanded']=true;
							return $this;
						}

					function ExpandParents()
						{
							$item=&$this->GetItemByID($this->currentitem['root_id']);
							while ($item['level']!=0)
								{
									$item['expanded']=true;
									$item=&$this->GetItemByID($item['root_id']);
								}
						}

					function &GetItemByID($id)
						{
							foreach ($this->items as &$item)
								if (strcmp($item['id'], $id)==0)
									return $item;
							return $this->items[0];
						}
					
					private function BuildTree(&$tree)
						{
							foreach ($this->items as &$item)
								{
									if (strcmp($tree['id'], $item['root_id'])==0)
										{
											$tree['items'][]=$item;
											$this->BuildTree($tree['items'][count($tree['items'])-1]);
										}
								}
						}

					private function BuildHTML(&$tree, $root_globalindex=0, $parent_expanded=true)
						{
							$html='<div class="atw-treeview-item atw-treeview-level-'.$tree['level'].' treeview-childof-atw-'.$this->prefix.'-'.$root_globalindex;
							if ($tree['subitems_count']>0 && $tree['level']>0)
								$html.=' atw-collapasable';
							if ($tree['expanded'])
								$html.=' atw-expanded';
							if ($parent_expanded)
								$html.=' atw-visible';
							else
								$html.=' atw-invisible';
							$html.='" id="atw-'.$this->prefix.'-'.$tree['globalindex'].'"><div class="atw-title-container">';
							if ($tree['subitems_count']>0 && $tree['level']>0)
								$html.='<a href="javascript:;" onclick="atw_collapser(\''.$this->prefix.'-'.$tree['globalindex'].'\')"><span class="atw-collapser">+</span></a>';
							if ($tree['checkbox'])
								{
									$html.=' <input type="checkbox" name="'.$tree['varname'].'" value="'.$tree['checkbox_value'].'" id="atw-checkbox-'.$this->prefix.'-'.$tree['globalindex'].'" class="atw-checkbox"';
									if ($tree['checkbox_checked'])
										$html.=' checked';
									$html.=' />';
									if ($tree['checkbox_parentcheck'])
										$html.='<a href="javascript:;" onclick="atw_mass_checkboxes(\''.$tree['parentlist'].'\')" class="atw-mass-chk">^</a>';
								}
							if ($tree['radio'])
								{
									$html.=' <input type="radio" name="'.$tree['radio_varname'].'" value="'.$tree['radio_value'].'" id="atw-radio-'.$this->prefix.'-'.$tree['globalindex'].'" class="atw-radio"';
									if ($tree['radio_checked'])
										$html.=' checked';
									$html.=' />';
								}
							if ($tree['subitems_count']>0 && $tree['level']>0)
								$html.='<a href="javascript:;" onclick="atw_collapser(\''.$this->prefix.'-'.$tree['globalindex'].'\')">';
							$html.='<span class="atw-item-title">'.$tree['title'].'</span>';
							if ($tree['subitems_count']>0 && $tree['level']>0)
								{
									$html.='</a>';
								}
							$html.='</div>';
							if (count($tree['items'])>0 && $tree['level']>0)
								$html.='<div class="atw-treeview-subitems-container treeview-childof-atw-'.$this->prefix.'-'.$root_globalindex.'">';
							for ($i=0; $i<count($tree['items']); $i++)
								{
									$html.="\n".$this->BuildHTML($tree['items'][$i], $tree['globalindex'], $tree['expanded'])."\n";
								}
							if (count($tree['items'])>0 && $tree['level']>0)
								$html.='</div>';
							$html.='</div>'."\n";
							return $html;
						}

					function Output()
						{
							$tree=$this->items[0];
							$this->BuildTree($tree);
							$html=$this->BuildHTML($tree);
							return $html;
						}
				}

			define("admintreeview_DEFINED", 1);
		}

?>