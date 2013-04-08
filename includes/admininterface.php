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
				function AddOutputObject($type, $object)
					{
						$this->blocks[$this->currentblock]['itemscount']++;
						$this->item['type']=$type;
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
				function div($html, $id='', $class='', $style='')
					{
						$code='<div'.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').'>'.$html.'</div>';
						$this->html($code);
					}
				function p($html, $id='', $class='', $style='')
					{
						$code='<p'.(empty($id)?'':' id="'.$id.'"').''.(empty($class)?'':' class="'.$class.'"').''.(empty($style)?'':' style="'.$style.'"').'>'.$html.'</p>';
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
		define("admininterface_DEFINED", 1);
	}

?>