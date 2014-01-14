<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#revision 2012-06-17                                                         |
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
				function TForm($action, $prefix='', $method='POST')
					{
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
					}
				function AddTab($title)
					{
						$i=count($this->form['tabs']);
						$this->form['tabs'][$i]['title']=$title;
						$this->currentTab=$i;
					}
				function AddSeparator($name, $title)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['type']='separator';
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddLabel($name, $title, $labeltext)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['labeltext']=$labeltext;
						$this->form['fields'][$name]['type']='label';
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddText($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='text';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddPassword($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='password';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddFile($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='file';
						$this->form['files']=addto_nllist($this->form['files'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddStatictext($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='statictext';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddHidden($name, $value='')
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['type']='hidden';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->SetValue($name, $value);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddSystemHidden($name, $value='')
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['type']='hidden';
						$this->SetValue($name, $value);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddTextarea($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='textarea';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddEditor($name, $title, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='editor';
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
						if (!$this->firsteditor)
							$this->form['fields'][$name]['noinit']=1;
						if ($this->firsteditor)
							$this->firsteditor=false;
					}
				function AddCheckbox($name, $title, $checkedvalue=1, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='checkbox';
						$this->form['fields'][$name]['checkedvalue']=$checkedvalue;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddSelectNLList($name, $title, $nllist_values, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=nllistToArray($nllist_values);
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddSelect($name, $title, $array_values, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=$array_values;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddSelectVL($name, $title, $array_values, $array_labels, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=$array_values;
						$this->form['fields'][$name]['labels']=$array_labels;
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
					}
				function AddSelectNLListVL($name, $title, $nllist_values, $nllist_labels, $required=false)
					{
						$this->form['fields'][$name]['name']=$name;
						$this->form['fields'][$name]['caption']=$title;
						$this->form['fields'][$name]['required']=$required;
						$this->form['fields'][$name]['type']='select';
						$this->form['fields'][$name]['values']=nllistToArray($nllist_values);
						$this->form['fields'][$name]['labels']=nllistToArray($nllist_labels);
						$this->form['updates']=addto_nllist($this->form['updates'], $name);
						$this->form['fields'][$name]['tab']=$this->currentTab;
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
					}
				function AddSelectSQL($name, $title, $sql, $fiedvalue, $fieldlabel='', $required=false)
					{
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
					}
				function LoadValues($sql)
					{
						$data=getsql($sql);
						$this->LoadValuesArray($data);
					}
				function SetValue($name, $value)
					{
						$this->form['data'][$name]=htmlspecialchars($value);
					}
				function Output()
					{
						$this->form['tabscount']=count($this->form['tabs']);
						$this->form['method']=strtolower($this->form['method']);
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
					}
				function SetColumnsWidth($first, $second)
					{
						$this->form['options']['width1']=$first;
						$this->form['options']['width2']=$second;
					}
				//-------------------------------------------------------------
				function SetFieldId($name, $id)
					{
						$this->form['fields'][$name]['id']=$id;
					}
				function SetFieldAttribute($name, $attribute, $value)
					{
						$this->form['fields'][$name]['attrs'][$attribute]=$value;
					}
				function SetFieldTopText($name, $text)
					{
						$this->form['fields'][$name]['toptext']=$text;
					}
				function SetFieldBeginText($name, $text)
					{
						$this->form['fields'][$name]['begintext']=$text;
					}
				function SetFieldEndText($name, $text)
					{
						$this->form['fields'][$name]['endtext']=$text;
					}
				function SetFieldBottomText($name, $text)
					{
						$this->form['fields'][$name]['bottomtext']=$text;
					}
				function MergeColumns($name)
					{
						$this->form['fields'][$name]['mergecolumns']=1;
					}
				function HideDefinition($name)
					{
						$this->form['fields'][$name]['hidedefinition']=1;
					}
				function HideEncloser($name)
					{
						$this->form['fields'][$name]['hideencloser']=1;
					}
				function SetImage($name, $src, $href='')
					{
						$this->form['fields'][$name]['image']['src']=$src;
						$this->form['fields'][$name]['image']['href']=$href;
					}
				function AddProtectCode($name, $title)
					{
						siman_generate_protect_code();
						$this->AddText($name, $title, true);
						$this->SetImage($name, 'ext/antibot/antibot.php?rand='.rand(11111,99999));
					}
				//-------------------------------------------------------------
				function SaveButton($text)
					{
						$this->form['savetitle']=$text;
					}
				//-------------------------------------------------------------
				function Calendar($name)
					{
						global $special;
						$special['document']['headend'].='
							<script type="text/javascript">
							$(function()
								{
									$( "#'.$this->form['prefix'].$name.'" ).datepicker();
									alert("aaa");
								});
							</script>';
					}
				function NoHighlight($turn_off=true)
					{
						if ($turn_off)
							$this->form['no_highlight']=1;
						else
							$this->form['no_highlight']=0;
					}
				function Tooltip($name, $text, $image='help.gif')
					{
						$this->form['fields'][$name]['tooltip']=$text;
						if (!file_exists('themes/'.sm_settings('default_theme').'/images/admintable/'.$image))
							$this->form['fields'][$name]['tooltipimg']='themes/default/images/admintable/'.$image;
						else
							$this->form['fields'][$name]['tooltipimg']='themes/'.sm_settings('default_theme').'/images/admintable/'.$image;
						$this->form['tooltip_present']=true;
					}
				function SendFieldsInfo()
					{
						$this->form['send_fields_info']=true;
					}
			}

		define("adminform_DEFINED", 1);
	}

?>