<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

if (!defined("admintable_DEFINED"))
	{
		sm_add_cssfile('common_admintable.css');
		
		class TGrid
			{
				var $table;
				var $rownumber;
				
				static $grids_used;

				private $sort_statement='';

				function TGrid($default_column='', $postfix='')
					{
						$this->rownumber=0;
						$this->table['default_column']='';
						if (strlen($postfix)==0)
							$this->table['postfix']=TGrid::$grids_used;
						else
							$this->table['postfix']=$postfix;
						TGrid::$grids_used++;
					}
				function AddCol($name, $title, $width='', $hint='', $replace_text='', $replace_image='', $messagebox=0, $messagebox_text='', $to_menu=0)
					{
						global $sm;
						if (strlen($replace_image)>0 && strpos($replace_image, '://')===false && strpos($replace_image, '.')===false)
							$replace_image.='.gif';
						$this->table['columns'][$name]['caption']=$title;
						$this->table['columns'][$name]['width']=$width;
						$this->table['columns'][$name]['hint']=$hint;
						$this->table['columns'][$name]['replace_text']=$replace_text;
						$this->table['columns'][$name]['imagepath']=false;
						if (!empty($replace_image) && strpos($replace_image, '/')===false)
							{
								if (!file_exists('themes/'.sm_current_theme().'/images/admintable/'.$replace_image))
									{
										$replace_image='themes/default/images/admintable/'.$replace_image;
										$this->table['columns'][$name]['imagepath']=true;
									}
							}
						elseif (!empty($replace_image))
							$this->table['columns'][$name]['imagepath']=true;
						$this->table['columns'][$name]['replace_image']=$replace_image;
						$this->table['columns'][$name]['messagebox']=$messagebox;
						$this->table['columns'][$name]['messagebox_text']=$messagebox_text;
						$this->table['columns'][$name]['to_menu']=$to_menu;//Deprecated
					}
				function SetHeaderImage($name, $image)
					{
						if (strpos($image, '.')===false && strpos($image, '://')===false)
							$image.='.gif';
						if (strpos($image, '://')!==false || file_exists($image))
							$img=$image;
						elseif (file_exists('themes/'.sm_current_theme().'/images/admintable/'.$image))
							$img='themes/'.sm_current_theme().'/images/admintable/'.$image;
						else
							$img='themes/default/images/admintable/'.$image;
						$this->table['columns'][$name]['html'].='<img src="'.$img.'" class="adminform_header_image" />';
					}
				function AddIcon($name, $image, $hint='')
					{
						if (strpos($image, '.')===false && strpos($image, '://')===false)
							$image.='.gif';
						$this->AddCol($name, '', '16', $hint, $hint, $image);
					}
				function AddEdit($name='edit')
					{
						global $lang;
						$this->AddCol($name, '', '16', $lang['common']['edit'], $lang['common']['edit'], 'edit');
					}
				function AddDelete($msg='', $name='delete')
					{
						global $lang;
						if (empty($msg))
							$msg=$lang['common']['really_want_delete'];
						$this->AddCol($name, '', '16', $lang['common']['delete'], $lang['common']['delete'], 'delete', 1, addslashes($msg));
					}
				function SetAsMessageBox($name, $msg)
					{
						$this->table['columns'][$name]['messagebox']=1;
						$this->table['columns'][$name]['messagebox_text']=$msg;
					}
				function HeaderUrl($name, $url)
					{
						$this->table['columns'][$name]['headerurl']=$url;
					}
				function AddMenuInsert($name='tomenu')
					{
						global $lang;
						$this->AddCol($name, '', '', $lang['module_menu']['add_to_menu'], $lang['module_menu']['add_to_menu']);
						$this->table['columns'][$name]['nobr']=1;
					}
				function OneLine($name)
					{
						$this->table['rows'][$this->rownumber][$name]['colspan']=count($this->table['columns']);
						if (!empty($this->table['columns']))
							while ( list( $key, $val ) = each($this->table['columns']) )
								{
									if ($key!=$name)
										$this->table['rows'][$this->rownumber][$key]['hide']=1;
								}
					}
				function AttachEmptyCellsToLeft()
					{
						if (!empty($this->table['columns']))
							{
								$i=0;
								$notempty='';
								$colspan=1;
								reset($this->table['columns']);
								while ( list( $key, $val ) = each($this->table['columns']) )
									{
										if (strlen($this->table['rows'][$this->rownumber][$key]['data'])==0 && strlen($this->table['rows'][$this->rownumber][$key]['image'])==0  && strlen($this->table['rows'][$this->rownumber][$key]['url'])==0 && $i>0)
											{
												$this->Hide($key);
												$colspan++;
											}
										else
											{
												if (!empty($notempty))
													{
														$this->Colspan($notempty, $colspan);
													}
												$notempty=$key;
												$colspan=1;
											}
										$i++;
									}
								if ($colspan>1)
									$this->Colspan($notempty, $colspan);
							}
					}
				function Colspan($name, $value)
					{
						$this->table['rows'][$this->rownumber][$name]['colspan']=$value;
					}
				function RowCount()
					{
						return count($this->table['rows']);
					}
				function NewRow()
					{
						$this->rownumber++;
					}
				function Label($name, $value)
					{
						$this->table['rows'][$this->rownumber][$name]['data']=$value;
					}
				function CellAddClass($name, $classname)
					{
						$this->table['rows'][$this->rownumber][$name]['class'].=' '.$classname;
					}
				function CellAddStyle($name, $style)
					{
						$this->table['rows'][$this->rownumber][$name]['style'].=$style;
					}
				function Hint($name, $value)
					{
						$this->table['rows'][$this->rownumber][$name]['hint']=$value;
					}
				function Image($name, $replace_image)
					{
						if (strpos($replace_image, '.')===false && strpos($replace_image, '://')===false)
							$replace_image.='.gif';
						$this->table['rows'][$this->rownumber][$name]['imagepath']=false;
						if (!empty($replace_image) && strpos($replace_image, '/')===false)
							{
								if (!file_exists('themes/'.sm_current_theme().'/images/admintable/'.$replace_image))
									{
										$replace_image='themes/default/images/admintable/'.$replace_image;
										$this->table['rows'][$this->rownumber][$name]['imagepath']=true;
									}
							}
						elseif (!empty($replace_image))
							$this->table['rows'][$this->rownumber][$name]['imagepath']=true;
						$this->table['rows'][$this->rownumber][$name]['image']=$replace_image;
					}
				function CustomMessageBox($name, $message)
					{
						$this->table['rows'][$this->rownumber][$name]['messagebox_text']=$message;
					}
				function URL($name, $value, $open_in_new_window=false)
					{
						$this->table['rows'][$this->rownumber][$name]['url']=$value;
						$this->table['rows'][$this->rownumber][$name]['new_window']=$open_in_new_window;
					}
				function Menu($menu_caption, $menu_url, $name='tomenu')
					{
						global $lang;
						sm_extcore();
						$this->URL($name, sm_tomenuurl($menu_caption, $menu_url, sm_this_url()));
					}
				function Hide($name)
					{
						$this->table['rows'][$this->rownumber][$name]['hide']=1;
					}
				function ExpanderHTML($html)
					{
						$this->table['expanders'][$this->rownumber]['html']=$html;
					}
				function Expand($name)
					{
						$this->table['rows'][$this->rownumber][$name]['url']='javascript:;';
						$this->table['rows'][$this->rownumber][$name]['onclick'].="document.getElementById('admintable-expander-".$this->rownumber."-".$this->table['postfix']."').style.display=(document.getElementById('admintable-expander-".$this->rownumber."-".$this->table['postfix']."').style.display)?'':'none';";
					}
				function ExpandAJAX($name, $url)
					{
						$this->Expand($name);
						$this->table['rows'][$this->rownumber][$name]['onclick'].="admintable_ajax_load".$this->table['postfix']."('".$url."', 'admintable-expanderarea-".$this->rownumber."-".$this->table['postfix']."');";
					}
				//--------------------------------------------------------------------------------------------------------
				function HeaderColspan($name, $value=2)
					{
						$this->table['columns'][$name]['headercolspan']=$value;
					}
				function HeaderHideCol($name)
					{
						$this->table['columns'][$name]['hideheader']=1;
					}
				function HideHeader()
					{
						$this->table['hideheader']=1;
					}
				//--------------------------------------------------------------------------------------------------------
				function OnClick($name, $code)
					{
						$this->table['rows'][$this->rownumber][$name]['onclick'].=$code;
					}
				function HeaderOnClick($name, $code)
					{
						$this->table['columns'][$name]['onclick'].=$code;
					}
				function DropDownItemsCount($name)
					{
						return count($this->table['rows'][$this->rownumber][$name]['dropdownitems']);
					}
				function DropDownItem($name, $title, $url, $confirm_message='', $tomenutitle='')
					{
						$this->table['rows'][$this->rownumber][$name]['dropdown']=1;
						$this->URL($name, 'javascript:;');
						$this->OnClick($name, "atdropdownopen".$this->table['postfix']."('atdropdown-".$name."-".$this->rownumber."-".$this->table['postfix']."');");
						$i=count($this->table['rows'][$this->rownumber][$name]['dropdownitems']);
						$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['title']=$title;
						$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['url']=$url;
						$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['confirm_message']=htmlescape($confirm_message);
						$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['tomenutitle']=$tomenutitle;
					}
				function DropDownItemSelect($name, $index=-1)
					{
						if ($index==-1)
							$i=count($this->table['rows'][$this->rownumber][$name]['dropdownitems'])-1;
						else
							$i=$index;
						$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['selected']=1;
					}
				function HeaderDropDownItem($name, $title, $url, $confirm_message='')
					{
						$this->table['columns'][$name]['dropdown']=1;
						$this->HeaderUrl($name, 'javascript:;');
						$this->HeaderOnClick($name, "atdropdownopen".$this->table['postfix']."('atdropdown-".$name."-".$this->table['postfix']."');");
						$i=count($this->table['columns'][$name]['dropdownitems']);
						$this->table['columns'][$name]['dropdownitems'][$i]['title']=$title;
						$this->table['columns'][$name]['dropdownitems'][$i]['url']=$url;
						$this->table['columns'][$name]['dropdownitems'][$i]['confirm_message']=$confirm_message;
					}
				function HeaderDropDownItemSelect($name, $index=-1)
					{
						if ($index==-1)
							$i=count($this->table['columns'][$name]['dropdownitems'])-1;
						else
							$i=$index;
						$this->table['columns'][$name]['dropdownitems'][$i]['selected']=1;
					}
				private function USortRowsByColumnData($a, $b)
					{
						if ($a==$b)
							return 0;
						$cols=explode(',', $this->sort_statement);
						for ($j = 0; $j < count($cols); $j++)
							{
								$col=explode(' ', trim($cols[$j]));
								if (strtoupper($col[2])=='NUM' || strtoupper($col[1])=='NUM')
									{
										if ($a[$col[0]]['data']==$b[$col[0]]['data'])
											$result=0;
										else
											$result=$a[$col[0]]['data']>$b[$col[0]]['data']?1:-1;
									}
								else
									$result=strcmp($a[$col[0]]['data'], $b[$col[0]]['data']);
								if ($result!=0)
									return (strtoupper($col[1])=='DESC'?-1:1)*($result<0?-1:1);
							}
					}
				function SortRowsByColumnData($comma_separaded_columns)
					{
						$this->sort_statement=$comma_separaded_columns;
						usort($this->table['rows'], array($this, "USortRowsByColumnData"));
					}
				//-------- FORM FUNCTIONS ------------------------------------------------------------------------------------------------
				function Textbox($name, $varname, $value)
					{
						$this->table['rows'][$this->rownumber][$name]['data']=$value;
						$this->table['rows'][$this->rownumber][$name]['element']='text';
						$this->table['rows'][$this->rownumber][$name]['varname']=$varname;
					}
				function Selectbox($name, $varname, $value, $valuesarrayornllist, $labelsarrayornllist)
					{
						if (!is_array($valuesarrayornllist))
							$valuesarrayornllist=nllistToArray($valuesarrayornllist);
						if (!is_array($labelsarrayornllist))
							$labelsarrayornllist=nllistToArray($labelsarrayornllist);
						$this->table['rows'][$this->rownumber][$name]['data']=$value;
						$this->table['rows'][$this->rownumber][$name]['element']='select';
						$this->table['rows'][$this->rownumber][$name]['values']=$valuesarrayornllist;
						$this->table['rows'][$this->rownumber][$name]['labels']=$labelsarrayornllist;
						$this->table['rows'][$this->rownumber][$name]['varname']=$varname;
					}
				function Checkbox($name, $varname, $checkedvalue, $checked=false)
					{
						$this->table['rows'][$this->rownumber][$name]['data']=$checkedvalue;
						$this->table['rows'][$this->rownumber][$name]['element']='checkbox';
						$this->table['rows'][$this->rownumber][$name]['varname']=$varname;
						$this->table['rows'][$this->rownumber][$name]['checked']=$checked;
					}
				function RadioItem($name, $varname, $checkedvalue, $checked=false)
					{
						$this->table['rows'][$this->rownumber][$name]['data']=$checkedvalue;
						$this->table['rows'][$this->rownumber][$name]['element']='radioitem';
						$this->table['rows'][$this->rownumber][$name]['varname']=$varname;
						$this->table['rows'][$this->rownumber][$name]['checked']=$checked;
					}
				function SetControlAttr($name, $attrname, $attrval)
					{
						$this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname]=$attrval;
					}
				function GetControlAttr($name, $attrname)
					{
						return $this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname];
					}
				function AppendControlAttr($name, $attrname, $attrval, $append_prefix=' ')
					{
						$this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname].=(strlen($this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname])>0?$append_prefix:'').$attrval;
					}
				//Input type=hidden + Label
				function StoredLabel($name, $varname, $value)
					{
						$this->table['rows'][$this->rownumber][$name]['data']=$value;
						$this->table['rows'][$this->rownumber][$name]['element']='storedlabel';
						$this->table['rows'][$this->rownumber][$name]['varname']=$varname;
					}
				//-------- /FORM FUNCTIONS ------------------------------------------------------------------------------------------------
				function NoHighlight()
					{
						$this->table['no_highlight']=1;
					}
				function HeaderBulkCheckbox($name)
					{
						$this->table['columns'][$name]['html']='<input type="checkbox" id="'.$name.'-'.($this->table['postfix']).'-bulkcheckbox" class="at-bulk-checkbox" onchange="'.
							"\$('.admintable-".($this->table['postfix'])."-control-checkbox').prop('checked', \$('#".$name.'-'.($this->table['postfix'])."-bulkcheckbox').prop('checked')?true:false);".
							'" />';
					}
				//-----------------------------
				function LabelsFromArray($array)
					{
						if (!is_array($this->table['columns']) || !is_array($array))
							return;
						foreach ($this->table['columns'] as $key=>$val)
							{
								if (array_key_exists($key, $array))
									$this->Label($key, $array[$key]);
							}
					}
				//-----------------------------
				function RowAddClass($classname, $rownumber=NULL)
					{
						if ($rownumber===NULL)
							$rownumber=$this->rownumber;
						$this->table['rowparams'][$rownumber]['class'].=' '.$classname;
					}
				function RowAddStyle($rule, $rownumber=NULL)
					{
						if ($rownumber===NULL)
							$rownumber=$this->rownumber;
						$this->table['rowparams'][$rownumber]['style'].=$rule;
					}
				//====================================================
				function Output()
					{
						$this->table['colcount']=count($this->table['columns']);
						$this->table['rowcount']=count($this->table['rows']);
						for ($this->rownumber = 0; $this->rownumber<$this->RowCount(); $this->rownumber++)
							{
								$this->RowAddClass('at-row-'.$i, $i);
								if (intval($this->table['no_highlight'])!=1)
									if ($i % 2 == 0)
										$this->RowAddClass('at-row-pair', $i);
									else
										$this->RowAddClass('at-row-odd', $i);
								foreach ($this->table['columns'] as $name=>$columnval)
									{
										if (in_array($this->table['rows'][$this->rownumber][$name]['element'], Array('text', 'select', 'checkbox', 'radioitem', 'storedlabel')))
											{
												if ($this->table['rows'][$this->rownumber][$name]['element']=='text')
													$this->SetControlAttr($name, 'type', 'text');
												if ($this->table['rows'][$this->rownumber][$name]['element']=='checkbox')
													{
														$this->SetControlAttr($name, 'type', 'checkbox');
														if ($this->table['rows'][$this->rownumber][$name]['checked'])
															$this->SetControlAttr($name, 'checked', 'checked');
													}
												if ($this->table['rows'][$this->rownumber][$name]['element']=='radioitem')
													$this->SetControlAttr($name, 'type', 'radio');
												if ($this->table['rows'][$this->rownumber][$name]['element']=='storedlabel')
													$this->SetControlAttr($name, 'type', 'hidden');
												if ($this->table['rows'][$this->rownumber][$name]['element']=='select')
													{
														$this->SetControlAttr($name, 'size', '1');
														$this->AppendControlAttr($name, 'class', 'admintable-control-select');
														$this->AppendControlAttr($name, 'class', 'admintable-'.$this->table['postfix'].'-control-select');
													}
												else
													{
														$this->SetControlAttr($name, 'value', $this->table['rows'][$this->rownumber][$name]['data']);
														$this->AppendControlAttr($name, 'class', 'admintable-control-'.$this->GetControlAttr($name, 'type'));
														$this->AppendControlAttr($name, 'class', 'admintable-'.$this->table['postfix'].'-control-'.$this->GetControlAttr($name, 'type'));
													}
												$this->SetControlAttr($name, 'name', $this->table['rows'][$this->rownumber][$name]['varname']);
												$this->SetControlAttr($name, 'id', 'control-'.$this->table['postfix'].'-'.$name.'-row'.$this->rownumber);
												if (!empty($this->table['rows'][$this->rownumber][$name]['onclick']))
													$this->SetControlAttr($name, 'onclick', $this->table['rows'][$this->rownumber][$name]['onclick']);
											}
									}
							}
						return $this->table;
					}
			}
		
		TGrid::$grids_used=0;

		define("admintable_DEFINED", 1);
	}

?>