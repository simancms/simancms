<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.4
//#revision 2013-04-08
//==============================================================================

if (!defined("admininterface_DEFINED"))
	{
		sm_add_cssfile('common_admininterface.css');
		
		class TInterface
			{
				var $blocks;
				var $currneblock;
				var $items;
				var $item;
				function TInterface($title='', $showborders=1)
					{
						$this->currentblock=-1;
						$this->AddBlock($title, $showborders);
					}
				function SetActiveItem()
					{
						$this->item=&$this->blocks[$this->currentblock]['items'][count($this->blocks[$this->currentblock]['items'])];
					}
				function AddBlock($title, $showborders=1)
					{
						if (!($this->currentblock==0 && $this->blocks[$this->currentblock]['itemscount']==0))
							$this->currentblock++;
						$this->blocks[$this->currentblock]['title']=$title;
						$this->blocks[$this->currentblock]['show_borders']=$showborders;
						$this->blocks[$this->currentblock]['items']=Array();
						$this->items=&$this->blocks[$this->currentblock]['items'];
						$this->blocks[$this->currentblock]['itemscount']=0;
						$this->SetActiveItem();
					}
				function AddOutputObject($type, $object, $tpl='')
					{
						$this->blocks[$this->currentblock]['itemscount']++;
						$this->item['type']=$type;
						$this->item['tpl']=$tpl;
						$this->item[$type]=$object->Output();
						$this->SetActiveItem();
					}
				function AddForm($form)
					{
						$this->AddOutputObject('form', $form);
					}
				function AddGrid($grid)
					{
						$this->AddOutputObject('table', $grid);
					}
				function AddConversation($messages)
					{
						$this->AddOutputObject('board', $messages);
					}
				function AddButtons($buttons)
					{
						$this->AddOutputObject('bar', $buttons);
					}
				function AddPanel($panel)
					{
						$this->AddOutputObject('ui', $panel);
					}
				function AddPagebar($html_not_used='')
					{
						$this->blocks[$this->currentblock]['itemscount']++;
						$this->item['type']='pagebar';
						$this->SetActiveItem();
					}
				function html($html)
					{
						$this->blocks[$this->currentblock]['itemscount']++;
						$this->item['type']='html';
						$this->item['html']=$html;
						$this->SetActiveItem();
					}
				function div($html, $id='', $class='', $style='', $additionaltagattrs='')
					{
						$code='<div'.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').$additionaltagattrs.'>'.$html.'</div>';
						$this->html($code);
					}
				function p($html, $id='', $class='', $style='', $additionaltagattrs='')
					{
						$code='<p'.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').$additionaltagattrs.'>'.$html.'</p>';
						$this->html($code);
					}
				function h($type=1, $html, $id='', $class='', $style='', $additionaltagattrs='')
					{
						$code='<h'.$type.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').$additionaltagattrs.'>'.$html.'</h'.$type.'>';
						$this->html($code);
					}
				function a($href, $html, $id='', $class='', $style='', $onclick='', $additionaltagattrs='')
					{
						$code='<a href="'.$href.'"'.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').''.(empty($onclick)?'':' onclick="'.$onclick.'"').$additionaltagattrs.'>'.$html.'</a>';
						$this->html($code);
					}
				function br()
					{
						$this->html('<br />');
					}
				function hr($id='', $class='', $style='', $additionaltagattrs='')
					{
						$code='<hr '.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').$additionaltagattrs.' />';
						$this->html($code);
					}
				function Output($replace_template=false)
					{
						global $modules, $modules_index;
						if ($replace_template)
							{
								$modules[$modules_index]['module']='common_admininterface';
								$modules[$modules_index]['mode']='common_admininterface_launcher';
								$modules[$modules_index]['common_admininterface_output']=$this->blocks;
								return $this->blocks;
							}
						else
							return $this->blocks;
					}
			}
		
		class TPanel extends TInterface
			{
				function TPanel($width='', $height='')
					{
						$this->TInterface('', 0);
					}
			}
		
		define("admininterface_DEFINED", 1);
	}

?>