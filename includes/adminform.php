<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.7
//#revision 2014-05-20
//==============================================================================

if (!defined("adminform_DEFINED"))
	{
		sm_add_cssfile('common_adminform.css');
		
		class TForm
			{
				var $form;
				var $currentTab;
				var $tabs;
				var $firsteditor=true;
				private $currentname;
				function TForm($action, $prefix='', $method='POST')
					{
						global $sm;
						$this->form['action']=$action;
						if ($action===false)
							$this->form['dont_use_form_tag']=1;
						$this->form['method']=$method;
						$this->form['prefix']=$prefix;
						$this->form['updates']='';
						$this->form['send_fields_info']=false;
						$this->currentTab=0;
						$this->form['tabs'][0]['title']='';
						$this->form['postfix']=mt_rand(1000, 9999);
						$this->form['tooltip_present']=false;
						if ($sm['adminform']['nohighlight']===true)
							$this->NoHighlight();
					}
				public static function withAction($action, $method='POST')
					{
						$form=new TForm($action, '', $method);
						return $form;
					}
				function AddTab($title)
					{
						$i=count($this->form['tabs']);
						$this->form['tabs'][$i]['title']=$title;
						$this->currentTab=$i;
						return $this;
					}
				function SetMethodGet()
					{
						$this->form['method']='GET';
						return $this;
					}
				function SetMethodPost()
					{
						$this->form['method']='POST';
						return $this;
					}
				function AddSeparator($name, $title)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['type']='separator';
						$this->form['fields'][$name]['tab']=$this->currentTab;
						$this->SetRowClass('adminform-separator');
						return $this;
					}
				function Separator($title)
					{
						$name='separator'.count($this->form['fields']).'r'.rand(1111, 9999);
						$this->AddSeparator($name, $title);
						return $this;
					}
				function SetRowClass($class, $name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['rowclassname']=$class;
						return $this;
					}
				function AppendRowClass($class, $name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['rowclassname'].=(strlen($this->form['fields'][$name]['rowclassname'])==0?'':' ').$class;
						return $this;
					}
				function AddLabel($name, $title, $labeltext)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['labeltext']=$labeltext;
						$this->form['fields'][$name]['type']='label';
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddText($name, $title, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='text';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddPassword($name, $title, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='password';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddFile($name, $title, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='file';
						$this->form['files']=addto_nllist($this->form['files'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddStatictext($name, $title, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='statictext';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddHidden($name, $value='')
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['type']='hidden';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->SetValue($name, $value);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddSystemHidden($name, $value='')
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['type']='hidden';
						$this->SetValue($name, $value);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddTextarea($name, $title, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='textarea';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddOutputObject($type, $object, $tpl='')
					{
						$this->form['fields'][$this->currentname]['type']=$type;
						$this->form['fields'][$this->currentname]['tpl']=$tpl;
						$this->form['fields'][$this->currentname]['data']=$object->Output();
						return $this;
					}
				function InsertButtons($buttons, $title=NULL, $name=NULL)
					{
						if ($name==NULL)
							$name='buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
						$this->currentname=$name;
						$buttons->AddClassnameGlobal('adminformbuttons');
						$this->AddOutputObject('bar', $buttons);
						if ($title==NULL)
							$this->MergeColumns();
						return $this;
					}
				function InsertGrid($grid, $title=NULL, $name=NULL)
					{
						if ($name==NULL)
							$name='table_'.count($this->form['fields']).'_'.rand(1, 999999);
						$this->currentname=$name;
						$this->AddOutputObject('table', $grid);
						if ($title==NULL)
							$this->MergeColumns();
						return $this;
					}
				function InsertHTML($html, $title=NULL, $name=NULL)
					{
						if ($name==NULL)
							$name='buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
						$this->currentname=$name;
						$this->form['fields'][$this->currentname]['type']='html';
						$this->form['fields'][$this->currentname]['html']=$html;
						if ($title==NULL)
							$this->MergeColumns();
						return $this;
					}
				function InsertTPL($tpl, $data=Array(), $action='', $title=NULL, $name=NULL)
					{
						if ($name==NULL)
							$name='buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
						$this->currentname=$name;
						$this->form['fields'][$this->currentname]['type']='tpl';
						$this->form['fields'][$this->currentname]['tpl']=$tpl;
						$this->form['fields'][$this->currentname]['data']=$data;
						$this->form['fields'][$this->currentname]['action']=$action;
						if ($title==NULL)
							$this->MergeColumns();
						return $this;
					}
				function AddEditor($name, $title, $required=false)
					{
						global $sm;
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='editor';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						if (!$this->firsteditor || intval($sm['s']['tinymce_instances_in_tform'])>0)
							$this->form['fields'][$name]['noinit']=1;
						if ($this->firsteditor)
							$this->firsteditor=false;
						$sm['s']['tinymce_instances_in_tform']++;
						return $this;
					}
				function AddCheckbox($name, $title, $checkedvalue=1, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='checkbox';
						$this->form['fields'][$name]['checkedvalue']=$checkedvalue;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddSelectNLList($name, $title, $nllist_values, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=nllistToArray($nllist_values);
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddSelect($name, $title, $array_values, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=$array_values;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddSelectVL($name, $title, $array_values, $array_labels, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=$array_values;
						$this->form['fields'][$name]['labels']=$array_labels;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function AddSelectNLListVL($name, $title, $nllist_values, $nllist_labels, $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=nllistToArray($nllist_values);
						$this->form['fields'][$name]['labels']=nllistToArray($nllist_labels);
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						return $this;
					}
				function SelectAddBeginVL($name, $value, $label)
					{
						if (is_array($this->form['fields'][$name]['values']))
							{
								array_unshift($this->form['fields'][$name]['values'], $value);
								array_unshift($this->form['fields'][$name]['labels'], $label);
							}
						else
							{
								$this->form['fields'][$name]['values'][]=$value;
								$this->form['fields'][$name]['labels'][]=$label;
							}
						return $this;
					}
				function SelectAddEndVL($name, $value, $label)
					{
						if (is_array($this->form['fields'][$name]['values']))
							{
								array_push($this->form['fields'][$name]['values'], $value);
								array_push($this->form['fields'][$name]['labels'], $label);
							}
						else
							{
								$this->form['fields'][$name]['values'][]=$value;
								$this->form['fields'][$name]['labels'][]=$label;
							}
						return $this;
					}
				function GetTitle($name)
					{
						return $this->form['fields'][$name]['caption'];
					}
				function AddSelectSQL($name, $title, $sql, $fiedvalue, $fieldlabel='', $required=false)
					{
						$this->currentname=$name;
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['tab']=$this->currentTab;
						$list=getsqlarray($sql);
						for ($i=0; $i<count($list); $i++)
							{
								$this->form['fields'][$name]['values'][$i]=$list[$i][$fiedvalue];
								$this->form['fields'][$name]['labels'][$i]=$list[$i][empty($fieldlabel)?$fiedvalue:$fieldlabel];
							}
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						return $this;
					}
				function LoadValues($sql)
					{
						$data=getsql($sql);
						$this->LoadValuesArray($data);
						return $this;
					}
				function SetValue($name, $value)
					{
						if (!is_array($value))
							$this->form['data'][$name]=htmlescape($value);
						return $this;
					}
				function Output()
					{
						$this->form['tabscount']=count($this->form['tabs']);
						$this->form['method']=strtolower($this->form['method']);
						if ($this->form['no_highlight']!=1)
							{
								$class='';
								foreach ($this->form['fields'] as $name=>$value)
									{
										if ($this->form['fields'][$name]['hidedefinition']==1 || $this->form['fields'][$name]['type']=='separator')
											continue;
										if ($class!='adminform-row-odd')
											$class='adminform-row-odd';
										else
											$class='adminform-row-pair';
										$this->AppendRowClass($class, $name);
									}
							}
						return $this->form;
					}
				//-------------------------------------------------------------
				function LoadValuesArray($array)
					{
						if (!is_array($array))
							return;
						while ( list($name, $value) = each($array) )
							{
								$this->SetValue($name, $value);
							}
						return $this;
					}
				function SetColumnsWidth($first, $second)
					{
						$this->form['options']['width1']=$first;
						$this->form['options']['width2']=$second;
						return $this;
					}
				//-------------------------------------------------------------
				function SetFieldId($name, $id)
					{
						$this->form['fields'][$name]['id']=$id;
						return $this;
					}
				function SetFieldAttribute($name, $attribute, $value)
					{
						$this->form['fields'][$name]['attrs'][$attribute]=$value;
						return $this;
					}
				function SetTitleText($name, $title)
					{
						$this->form['fields'][$name]['caption']=$title;
						return $this;
					}
				function SetFieldTopText($name, $text)
					{
						$this->form['fields'][$name]['toptext']=$text;
						return $this;
					}
				function SetFieldBeginText($name, $text)
					{
						$this->form['fields'][$name]['begintext']=$text;
						return $this;
					}
				function SetFieldEndText($name, $text)
					{
						$this->form['fields'][$name]['endtext']=$text;
						return $this;
					}
				function SetFieldBottomText($name, $text)
					{
						$this->form['fields'][$name]['bottomtext']=$text;
						return $this;
					}
				function MergeColumns($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['mergecolumns']=1;
						return $this;
					}
				function HideDefinition($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['hidedefinition']=1;
						return $this;
					}
				function HideEncloser($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['hideencloser']=1;
						return $this;
					}
				function SetImage($name, $src, $href='')
					{
						$this->form['fields'][$name]['image']['src']=$src;
						$this->form['fields'][$name]['image']['href']=$href;
						return $this;
					}
				function AddProtectCode($name, $title)
					{
						siman_generate_protect_code();
						$this->AddText($name, $title, true);
						$this->SetImage($name, 'ext/antibot/antibot.php?rand='.rand(11111,99999));
						return $this;
					}
				function LabelAfterControl($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->SetFieldEndText($name, $this->GetTitle($name));
						$this->SetTitleText($name, '');
						$this->MergeColumns($name);
						return $this;
					}
				function Disable($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['attrs']['disabled']='disabled';
						return $this;
					}
				function SetFieldClass($name=NULL, $classname)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['attrs']['class']=$classname;
						return $this;
					}
				function AppendFieldClass($name=NULL, $classname)
					{
						if ($name===NULL)
							$name=$this->currentname;
						$this->form['fields'][$name]['attrs']['class'].=' '.$classname;
						return $this;
					}
				function WithFieldClass($classname)
					{
						$this->SetFieldClass(NULL, $classname);
						return $this;
					}
				function WithFieldClassAppended($classname)
					{
						$this->AppendFieldClass(NULL, $classname);
						return $this;
					}
				//-------------------------------------------------------------
				function SaveButton($text)
					{
						$this->form['savetitle']=$text;
						return $this;
					}
				function SetSaveButtonHelperText($text)
					{
						$this->form['savebutton_helper']['text']=$text;
						return $this;
					}
				function SetSaveButtonHelperClassname($class)
					{
						$this->form['savebutton_helper']['class']=$class;
						return $this;
					}
				//-------------------------------------------------------------
				function Calendar($name=NULL)
					{
						global $sm;
						if ($name===NULL)
							$name=$this->currentname;
						$sm['s']['document']['headend'].='
							<script type="text/javascript">
							$(function()
								{
									$( "#'.$this->form['prefix'].$name.'" ).datepicker();
								});
							</script>';
						return $this;
					}
				function NoHighlight($turn_off=true)
					{
						if ($turn_off)
							$this->form['no_highlight']=1;
						else
							$this->form['no_highlight']=0;
						return $this;
					}
				function Tooltip($name, $text, $image='help.gif')
					{
						$this->form['fields'][$name]['tooltip']=$text;
						if (!file_exists('themes/'.sm_settings('default_theme').'/images/admintable/'.$image))
							$this->form['fields'][$name]['tooltipimg']='themes/default/images/admintable/'.$image;
						else
							$this->form['fields'][$name]['tooltipimg']='themes/'.sm_settings('default_theme').'/images/admintable/'.$image;
						$this->form['tooltip_present']=true;
						return $this;
					}
				function SendFieldsInfo()
					{
						$this->form['send_fields_info']=true;
						return $this;
					}
				function Autocomplete($ajax_url, $name=NULL)
					{
						global $sm;
						sm_use('autocomplete');
						sm_autocomplete_init_controls();
						if ($name===NULL)
							$name=$this->currentname;
						sm_autocomplete_for('#'.$this->form['prefix'].$name, $ajax_url);
						return $this;
					}
				function SetFocus($name=NULL)
					{
						if ($name===NULL)
							$name=$this->currentname;
						sm_setfocus($name);
						return $this;
					}
				function WithValue($value)
					{
						$this->SetValue($this->currentname, $value);
						return $this;
					}
			}

		define("adminform_DEFINED", 1);
	}

?>