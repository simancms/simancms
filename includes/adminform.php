<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2018-05-22
	//==============================================================================

	if (!defined("adminform_DEFINED"))
		{
			sm_add_cssfile('common_adminform.css');

			class TForm
				{
					var $form;
					var $firsteditor = true;
					private $currentname;

					static $forms_used=0;

					function __construct($action='', $prefix = '', $method = 'POST')
						{
							global $sm;
							$this->form['action'] = $action;
							if ($action === false)
								$this->form['dont_use_form_tag'] = 1;
							$this->form['method'] = $method;
							$this->form['prefix'] = $prefix;
							$this->form['updates'] = '';
							$this->form['postfix'] = TForm::$forms_used;
							$this->form['tooltip_present'] = false;
							if ($sm['adminform']['nohighlight'] === true)
								$this->NoHighlight();
							$this->AddClassnameGlobal('adminform_form');
							if (!empty($sm['adminform']['globalclass']))
								$this->AddClassnameGlobal($sm['adminform']['globalclass']);
							$this->SetFormAttribute('enctype', 'multipart/form-data');
							$this->SetDOMID('uiform-'.TForm::$forms_used);
							TForm::$forms_used++;
						}

					public static function withAction($action, $method = 'POST')
						{
							$form = new TForm($action, '', $method);
							return $form;
						}

					function SetDOMID($id)
						{
							$this->SetFormAttribute('id', $id);
						}

					function GetDOMID()
						{
							return $this->GetFormAttribute('id');
						}

					function SetMethodGet()
						{
							$this->form['method'] = 'GET';
							return $this;
						}

					function SetMethodPost()
						{
							$this->form['method'] = 'POST';
							return $this;
						}

					function SetAction($action)
						{
							$this->form['action'] = $action;
							return $this;
						}

					function AddSeparator($name, $title)
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['type'] = 'separator';
							$this->SetRowClass('adminform-separator');
							return $this;
						}

					function Separator($title)
						{
							$name = 'separator'.count($this->form['fields']).'r'.rand(1111, 9999);
							$this->AddSeparator($name, $title);
							return $this;
						}

					function SetRowClass($class, $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['rowclassname'] = $class;
							return $this;
						}

					function AppendRowClass($class, $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['rowclassname'] .= (strlen($this->form['fields'][$name]['rowclassname']) == 0 ? '' : ' ').$class;
							return $this;
						}

					function AddLabel($name, $title='', $labeltext='')
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['labeltext'] = $labeltext;
							$this->form['fields'][$name]['type'] = 'label';
							return $this;
						}

					function Label($title, $labeltext)
						{
							$name='tmpfrmlbl'.count($this->form['fields']).'-'.md5(microtime());
							$this->AddLabel($name, $title, $labeltext);
							return $this;
						}

					function AddText($name, $title='', $required = false)
						{
							global $sm;
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'text';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							if (!empty($sm['adminform']['textclass']))
								$this->SetFieldClass($name, $sm['adminform']['textclass']);
							return $this;
						}

					function AddPassword($name, $title='', $required = false)
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'password';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							return $this;
						}

					function AddFile($name, $title='', $required = false)
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'file';
							$this->form['files'] = addto_nllist($this->form['files'], $name);
							return $this;
						}

					function AddStatictext($name, $title='', $required = false)
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'statictext';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							return $this;
						}

					function AddHidden($name, $value = '')
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['type'] = 'hidden';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							$this->SetValue($name, $value);
							return $this;
						}

					function AddSystemHidden($name, $value = '')
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['type'] = 'hidden';
							$this->SetValue($name, $value);
							return $this;
						}

					function AddTextarea($name, $title='', $required = false)
						{
							global $sm;
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'textarea';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							if (!empty($sm['adminform']['textareaclass']))
								$this->SetFieldClass($name, $sm['adminform']['textareaclass']);
							return $this;
						}

					function AddOutputObject($type, $object, $tpl = '')
						{
							$this->form['fields'][$this->currentname]['type'] = $type;
							$this->form['fields'][$this->currentname]['tpl'] = $tpl;
							$this->form['fields'][$this->currentname]['data'] = $object->Output();
							return $this;
						}

				/**
				 * @param TButtons $buttons
				 * @param string|NULL $title
				 * @param string|NULL $name
				 * @return $this
				 */
					function InsertButtons($buttons, $title = NULL, $name = NULL)
						{
							if ($name == NULL)
								$name = 'buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
							$this->currentname = $name;
							$buttons->AddClassnameGlobal('adminformbuttons');
							$this->AddOutputObject('bar', $buttons);
							if ($title == NULL)
								$this->MergeColumns();
							return $this;
						}

					function InsertGrid($grid, $title = NULL, $name = NULL)
						{
							if ($name == NULL)
								$name = 'table_'.count($this->form['fields']).'_'.rand(1, 999999);
							$this->currentname = $name;
							$this->AddOutputObject('table', $grid);
							if ($title == NULL)
								$this->MergeColumns();
							return $this;
						}

					function InsertHTML($html, $title = NULL, $name = NULL)
						{
							if ($name == NULL)
								$name = 'buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
							$this->currentname = $name;
							$this->form['fields'][$this->currentname]['type'] = 'html';
							$this->form['fields'][$this->currentname]['html'] = $html;
							if ($title == NULL)
								$this->MergeColumns();
							return $this;
						}

					function InsertTPL($tpl, $data = Array(), $action = '', $title = NULL, $name = NULL)
						{
							if ($name == NULL)
								$name = 'buttons_'.count($this->form['fields']).'_'.rand(1, 999999);
							$this->currentname = $name;
							$this->form['fields'][$this->currentname]['type'] = 'tpl';
							$this->form['fields'][$this->currentname]['tpl'] = $tpl;
							$this->form['fields'][$this->currentname]['data'] = $data;
							$this->form['fields'][$this->currentname]['action'] = $action;
							if ($title == NULL)
								$this->MergeColumns();
							return $this;
						}

					function AddEditor($name, $title='', $required = false)
						{
							global $sm;
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'editor';
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							if (!$this->firsteditor || intval($sm['s']['tinymce_instances_in_tform'])>0)
								$this->form['fields'][$name]['noinit'] = 1;
							if ($this->firsteditor)
								$this->firsteditor = false;
							$sm['s']['tinymce_instances_in_tform']++;
							return $this;
						}

					function AddCheckbox($name, $title='', $checkedvalue = 1, $required = false)
						{
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = TForm::FieldTypeTagCheckbox();
							$this->form['fields'][$name]['checkedvalue'] = $checkedvalue;
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							return $this;
						}

					/*
						AddSelect($name, $title='', $array_values=Array(), $required = false)
						AddSelect($name, $title='', $array_values=Array(), $array_labels=Array(), $required = false)
					*/
					function AddSelect($name, $title='', $array_values=Array(), $array_labels=Array(), $required = false)
						{
							global $sm;
							//backward compatibility parameters
							if (is_array($array_values) && !is_array($array_labels) && is_bool($array_labels))
								{
									$required = $array_labels;
									$array_labels=$array_values;
								}
							elseif (is_array($array_values) && is_array($array_labels) && count($array_labels)==0)
								{
									$array_labels=$array_values;
								}
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'select';
							$this->form['fields'][$name]['values'] = $array_values;
							$this->form['fields'][$name]['labels'] = $array_labels;
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							if (!empty($sm['adminform']['selectclass']))
								$this->SetFieldClass($name, $sm['adminform']['selectclass']);
							return $this;
						}

					//Deprecated
					function AddSelectVL($name, $title='', $array_values=Array(), $array_labels=Array(), $required = false)
						{
							$this->AddSelect($name, $title, $array_values, $array_labels, $required);
							return $this;
						}

					//Deprecated
					function AddSelectNLListVL($name, $title='', $nllist_values='', $nllist_labels='', $required = false)
						{
							$this->AddSelect($name, $title, nllistToArray($nllist_values), nllistToArray($nllist_labels), $required);
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
									$this->form['fields'][$name]['values'][] = $value;
									$this->form['fields'][$name]['labels'][] = $label;
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
									$this->form['fields'][$name]['values'][] = $value;
									$this->form['fields'][$name]['labels'][] = $label;
								}
							return $this;
						}

					function GetTitle($name)
						{
							return $this->form['fields'][$name]['caption'];
						}

					function GetType($name)
						{
							return $this->form['fields'][$name]['type'];
						}

					function GetFieldNames()
						{
							$list=Array();
							if (is_array($this->form['fields']))
								foreach ($this->form['fields'] as $name => &$value)
									$list[]=$name;
							return $list;
						}

					//Deprecated
					function AddSelectSQL($name, $title, $sql, $fiedvalue, $fieldlabel = '', $required = false)
						{
							global $sm;
							$this->currentname = $name;
							$this->form['fields'][$name]['name'] = $name;
							$this->form['fields'][$name]['caption'] = $title;
							$this->form['fields'][$name]['required'] = $required;
							$this->form['fields'][$name]['type'] = 'select';
							$list = getsqlarray($sql);
							for ($i = 0; $i<count($list); $i++)
								{
									$this->form['fields'][$name]['values'][$i] = $list[$i][$fiedvalue];
									$this->form['fields'][$name]['labels'][$i] = $list[$i][empty($fieldlabel) ? $fiedvalue : $fieldlabel];
								}
							$this->form['updates'] = addto_nllist($this->form['updates'], $name);
							if (!empty($sm['adminform']['selectclass']))
								$this->SetFieldClass($name, $sm['adminform']['selectclass']);
							return $this;
						}

					//Deprecated
					function LoadValues($sql)
						{
							$data = getsql($sql);
							$this->LoadValuesArray($data);
							return $this;
						}

					function SetValue($name, $value)
						{
							if ($this->GetType($name)==TForm::FieldTypeTagLabel())
								{
									if (!is_array($value))
										$this->form['fields'][$name]['labeltext']=htmlescape($value);
									else
										$this->form['fields'][$name]['labeltext']=$value;
								}
							elseif ($this->GetType($name)==TForm::FieldTypeTagCheckbox() && is_bool($value))
								{
									if ($value)
										$this->SetValue($name, $this->form['fields'][$name]['checkedvalue']);
									else
										$this->SetValue($name, '');
								}
							else
								{
									if (!is_array($value))
										$this->form['data'][$name]=htmlescape($value);
									else
										$this->form['data'][$name]=$value;
								}
							return $this;
						}

					function GetValue($name)
						{
							return $this->form['data'][$name];
						}

					function SetNotEscapedValue($name, $value)
						{
							$this->form['data'][$name] = $value;
						}

					function ToggleFor($element_name_or_array, $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							if (is_array($element_name_or_array))
								{
									foreach ($element_name_or_array as $key=>$val)
										$this->ToggleFor($val, $name);
								}
							else
								$this->form['fields'][$name]['checkbox_toggle'][] = $element_name_or_array;
							return $this;
						}

					function ValueToggleFor($element_name_or_array, $value_on, $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							if (is_array($element_name_or_array))
								{
									foreach ($element_name_or_array as $key=>$val)
										$this->ValueToggleFor($val, $value_on, $name);
								}
							else
								$this->form['fields'][$name]['value_toggle'][] = Array('id'=>$element_name_or_array, 'val'=>$value_on);
							return $this;
						}

					function Output()
						{
							global $sm;
							$this->form['method'] = strtolower($this->form['method']);
							if (is_array($this->form['fields']))
								{
									if ($this->form['no_highlight'] != 1)
										{
											$class = '';
											foreach ($this->form['fields'] as $name => $value)
												{
													if ($this->form['fields'][$name]['hidedefinition'] == 1 || $this->form['fields'][$name]['type'] == 'separator')
														continue;
													if ($class != 'adminform-row-odd')
														$class = 'adminform-row-odd';
													else
														$class = 'adminform-row-pair';
													$this->AppendRowClass($class, $name);
												}
										}
									foreach ($this->form['fields'] as $name => $value)
										{
											if (!empty($sm['adminform']['rowclass']))
												$this->AppendRowClass($sm['adminform']['rowclass'], $name);
											$this->SetFieldId($name, $this->GetFieldId($name));
											if (!empty($this->form['fields'][$name]['rowclassname']))
												$this->AppendFieldRowAttribute($name, 'class', $this->form['fields'][$name]['rowclassname']);
											$this->SetFieldRowAttribute($name, 'id', $this->GetFieldRowId($name));
											if (!empty($this->form['fields'][$name]['toptext']))
												$this->form['fields'][$name]['toptext'] = '<span class="adminform-filed-top-txt'.(!empty($this->form['fields'][$name]['toptext_classname']) ? ' '.$this->form['fields'][$name]['toptext_classname'] : '').'"'.(!empty($this->form['fields'][$name]['toptext_style']) ? ' style="'.$this->form['fields'][$name]['toptext_style'].'"' : '').'>'.$this->form['fields'][$name]['toptext'].'</span>';
											if (!empty($this->form['fields'][$name]['bottomtext']))
												$this->form['fields'][$name]['bottomtext'] = '<span class="adminform-filed-btm-txt'.(!empty($this->form['fields'][$name]['bottomtext_classname']) ? ' '.$this->form['fields'][$name]['bottomtext_classname'] : '').'"'.(!empty($this->form['fields'][$name]['bottomtext_style']) ? ' style="'.$this->form['fields'][$name]['bottomtext_style'].'"' : '').'>'.$this->form['fields'][$name]['bottomtext'].'</span>';
											if (!empty($this->form['fields'][$name]['begintext']))
												$this->form['fields'][$name]['begintext'] = '<span class="adminform-filed-bgn-txt'.(!empty($this->form['fields'][$name]['begintext_classname']) ? ' '.$this->form['fields'][$name]['begintext_classname'] : '').'"'.(!empty($this->form['fields'][$name]['begintext_style']) ? ' style="'.$this->form['fields'][$name]['begintext_style'].'"' : '').'>'.$this->form['fields'][$name]['begintext'].'</span>';
											if (!empty($this->form['fields'][$name]['endtext']))
												$this->form['fields'][$name]['endtext'] = '<span class="adminform-filed-end-txt'.(!empty($this->form['fields'][$name]['endtext_classname']) ? ' '.$this->form['fields'][$name]['endtext_classname'] : '').'"'.(!empty($this->form['fields'][$name]['endtext_style']) ? ' style="'.$this->form['fields'][$name]['endtext_style'].'"' : '').'>'.$this->form['fields'][$name]['endtext'].'</span>';
											if (!empty($this->form['fields'][$name]['tooltip']) || !empty($this->form['fields'][$name]['tooltip_url']))
												{
													$this->form['fields'][$name]['column'][2] = '<div class="adminform-tooltip" title="'.$this->form['fields'][$name]['tooltip'].'">';
													if (!empty($this->form['fields'][$name]['tooltip_url']))
														$this->form['fields'][$name]['column'][2] .= '<a href="'.$this->form['fields'][$name]['tooltip_url'].'"'.(!empty($this->form['fields'][$name]['tooltip_url_target'])?' target="'.$this->form['fields'][$name]['tooltip_url_target'].'"':'').' class="tooltip-url">';
													$this->form['fields'][$name]['column'][2] .= '<img src="'.$this->form['fields'][$name]['tooltipimg'].'" />';
													if (!empty($this->form['fields'][$name]['tooltip_url']))
														$this->form['fields'][$name]['column'][2] .= '</a>';
													$this->form['fields'][$name]['column'][2] .= '</div>';
												}
											if ($this->form['fields'][$name]['type'] == 'select')
												{
													for ($i = 0; $i < count($this->form['fields'][$name]['values']); $i++)
														{
															if (strlen($this->form['fields'][$name]['labels'][$i])==0)
																$this->form['fields'][$name]['options'][$i]['label']=htmlescape($this->form['fields'][$name]['values'][$i]);
															else
																$this->form['fields'][$name]['options'][$i]['label']=htmlescape($this->form['fields'][$name]['labels'][$i]);
															$this->form['fields'][$name]['options'][$i]['attrs']['value']=htmlescape($this->form['fields'][$name]['values'][$i]);
															if (is_array($this->form['data'][$name]))
																{
																	if (in_array($this->form['fields'][$name]['values'][$i], $this->form['data'][$name]))
																		$this->form['fields'][$name]['options'][$i]['attrs']['selected']='selected';
																}
															else
																{
																	if (strcmp($this->form['data'][$name], $this->form['fields'][$name]['values'][$i])==0)
																		$this->form['fields'][$name]['options'][$i]['attrs']['selected']='selected';
																}
														}
												}
											if (is_array($this->form['fields'][$name]['checkbox_toggle']))
												for ($i = 0; $i<count($this->form['fields'][$name]['checkbox_toggle']); $i++)
													{
														$this->javascriptCode('$("#'.$this->GetFieldId($name).'").change(function(){if($("#'.$this->GetFieldId($name).'").prop("checked"))$("#'.$this->GetFieldRowId($this->form['fields'][$name]['checkbox_toggle'][$i]).'").show();else $("#'.$this->GetFieldRowId($this->form['fields'][$name]['checkbox_toggle'][$i]).'").hide();});$("#'.$this->GetFieldId($name).'").change();');
													}
											if (is_array($this->form['fields'][$name]['value_toggle']))
												for ($i = 0; $i<count($this->form['fields'][$name]['value_toggle']); $i++)
													{
														if (!is_array($this->form['fields'][$name]['value_toggle'][$i]['val']))
															$tmp='"'.jsescape($this->form['fields'][$name]['value_toggle'][$i]['val']).'"';
														else
															{
																$tmp='';
																for ($j = 0; $j<count($this->form['fields'][$name]['value_toggle'][$i]['val']); $j++)
																	{
																		if (!empty($tmp))
																			$tmp.=',';
																		$tmp.='"'.jsescape($this->form['fields'][$name]['value_toggle'][$i]['val'][$j]).'"';
																	}
															}
														$this->javascriptCode('$("#'.$this->GetFieldId($name).'").change(function(){if($.inArray($("#'.$this->GetFieldId($name).'").val(), ['.$tmp.'])!=-1)$("#'.$this->GetFieldRowId($this->form['fields'][$name]['value_toggle'][$i]['id']).'").show();else $("#'.$this->GetFieldRowId($this->form['fields'][$name]['value_toggle'][$i]['id']).'").hide();});$("#'.$this->GetFieldId($name).'").change();');
													}
										}
								}
							if (!empty($this->form['class']))
								$this->SetFormAttribute('class', $this->form['class']);
							if (!empty($this->form['action']))
								$this->SetFormAttribute('action', $this->form['action']);
							if (!empty($this->form['method']))
								$this->SetFormAttribute('method', $this->form['method']);
							else
								$this->SetFormAttribute('method', 'post');
							return $this->form;
						}

				//-------------------------------------------------------------
					function LoadValuesArray($array)
						{
							if (!is_array($array))
								return $this;
							while (list($name, $value) = each($array))
								{
									$this->SetValue($name, $value);
								}
							return $this;
						}

					function LoadAllValues($array)
						{
							if (!is_array($array) || count($array)==0)
								return $this;
							if (!is_array($this->form['fields']))
								return $this;
							foreach ($this->form['fields'] as $name => $value)
								{
									$this->SetValue($name, $array[$name]);
								}
							return $this;
						}

					function SetColumnsWidth($first, $second)
						{
							$this->form['options']['width1'] = $first;
							$this->form['options']['width2'] = $second;
							return $this;
						}

				//-------------------------------------------------------------
					function SetFormAttribute($attribute, $value)
						{
							if ($value===NULL)
								unset($this->form['attrs'][$attribute]);
							else
								$this->form['attrs'][$attribute] = $value;
							return $this;
						}

					function AppendFormAttribute($attribute, $value, $delimiter=' ')
						{
							$attrval=$this->GetFormAttribute($attribute);
							if (!empty($attrval))
								$attrval.=$delimiter;
							$attrval.=$value;
							$this->SetFormAttribute($attribute, $attrval);
							return $this;
						}

					function UnsetFormAttribute($attribute)
						{
							$this->SetFormAttribute($attribute, NULL);
							return $this;
						}

					function GetFormAttribute($attribute)
						{
							return $this->form['attrs'][$attribute];
						}

					function HasFormAttribute($attribute)
						{
							return is_array($this->form['attrs']) && array_key_exists($attribute, $this->form['attrs']);
						}

				//-------------------------------------------------------------
					function SetFieldId($name, $id)
						{
							$this->form['fields'][$name]['id'] = $id;
							return $this;
						}

					function GetFieldId($name)
						{
							if (!empty($this->form['fields'][$name]['id']))
								return $this->form['fields'][$name]['id'];
							else
								return $this->form['prefix'].$name;
						}

					function GetFieldRowId($name)
						{
							return 'admintablerow-'.$this->GetFieldId($name);
						}

					function SetFieldAttribute($name, $attribute, $value)
						{
							if ($name === NULL)
								$name = $this->currentname;
							if ($value===NULL)
								unset($this->form['fields'][$name]['attrs'][$attribute]);
							else
								$this->form['fields'][$name]['attrs'][$attribute] = $value;
							return $this;
						}

					function WithFieldAttribute($attribute, $value, $name=NULL)
						{
							$this->SetFieldAttribute($name, $attribute, $value);
							return $this;
						}

					function UnsetFieldAttribute($attribute, $name=NULL)
						{
							$this->SetFieldAttribute($name, $attribute, NULL);
							return $this;
						}

					function GetFieldAttribute($name, $attribute)
						{
							return $this->form['fields'][$name]['attrs'][$attribute];
						}

					function HasFieldAttribute($name, $attribute)
						{
							return is_array($this->form['fields'][$name]['attrs']) && array_key_exists($attribute, $this->form['fields'][$name]['attrs']);
						}

					function SetFieldRowAttribute($name, $attribute, $value)
						{
							if ($name === NULL)
								$name = $this->currentname;
							if ($value===NULL)
								unset($this->form['fields'][$name]['rowattrs'][$attribute]);
							else
								$this->form['fields'][$name]['rowattrs'][$attribute] = $value;
							return $this;
						}

					function WithFieldRowAttribute($attribute, $value, $name=NULL)
						{
							$this->SetFieldRowAttribute($name, $attribute, $value);
							return $this;
						}

					function GetFieldRowAttribute($name, $attribute)
						{
							return $this->form['fields'][$name]['rowattrs'][$attribute];
						}

					function AppendFieldRowAttribute($name, $attribute, $value, $delimiter=' ')
						{
							$attr=$this->GetFieldRowAttribute($name, $attribute);
							if (!empty($attr))
								$attr.=$delimiter;
							$attr.=$value;
							$this->SetFieldRowAttribute($name, $attribute, $attr);
						}

					function SetTitleText($name, $title)
						{
							$this->form['fields'][$name]['caption'] = $title;
							return $this;
						}

					function WithTitle($title)
						{
							$this->SetTitleText($this->currentname, $title);
							return $this;
						}

					function SetFieldTopText($name, $text, $classname = '', $style = '')
						{
							$this->form['fields'][$name]['toptext'] = $text;
							$this->form['fields'][$name]['toptext_classname'] = $classname;
							$this->form['fields'][$name]['toptext_style'] = $style;
							return $this;
						}

					function WithFieldTopText($text, $classname = '', $style = '')
						{
							$this->SetFieldTopText($this->currentname, $text, $classname, $style);
							return $this;
						}

					function SetFieldBeginText($name, $text, $classname = '', $style = '')
						{
							$this->form['fields'][$name]['begintext'] = $text;
							$this->form['fields'][$name]['begintext_classname'] = $classname;
							$this->form['fields'][$name]['begintext_style'] = $style;
							return $this;
						}

					function WithFieldBeginText($text, $classname = '', $style = '')
						{
							$this->SetFieldBeginText($this->currentname, $text, $classname, $style);
							return $this;
						}

					function SetFieldEndText($name, $text, $classname = '', $style = '')
						{
							$this->form['fields'][$name]['endtext'] = $text;
							$this->form['fields'][$name]['endtext_classname'] = $classname;
							$this->form['fields'][$name]['endtext_style'] = $style;
							return $this;
						}

					function WithFieldEndText($text, $classname = '', $style = '')
						{
							$this->SetFieldEndText($this->currentname, $text, $classname, $style);
							return $this;
						}

					function SetFieldBottomText($name, $text, $classname = '', $style = '')
						{
							$this->form['fields'][$name]['bottomtext'] = $text;
							$this->form['fields'][$name]['bottomtext_classname'] = $classname;
							$this->form['fields'][$name]['bottomtext_style'] = $style;
							return $this;
						}

					function WithFieldBottomText($text, $classname = '', $style = '')
						{
							$this->SetFieldBottomText($this->currentname, $text, $classname, $style);
							return $this;
						}

					function MergeColumns($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['mergecolumns'] = 1;
							return $this;
						}

					function HideDefinition($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['hidedefinition'] = 1;
							return $this;
						}

					function HideEncloser($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['hideencloser'] = 1;
							return $this;
						}

					function SetImage($name, $src, $href = '')
						{
							$this->form['fields'][$name]['image']['src'] = $src;
							$this->form['fields'][$name]['image']['href'] = $href;
							return $this;
						}

					function AddProtectCode($name, $title='')
						{
							siman_generate_protect_code();
							$this->AddText($name, $title, true);
							$this->SetImage($name, 'ext/antibot/antibot.php?rand='.rand(11111, 99999));
							return $this;
						}

					function LabelAfterControl($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->SetFieldEndText($name, $this->GetTitle($name));
							$this->SetTitleText($name, '');
							$this->MergeColumns($name);
							return $this;
						}

					function Disable($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['attrs']['disabled'] = 'disabled';
							return $this;
						}

					function SetFieldClass($name = NULL, $classname)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['attrs']['class'] = $classname;
							return $this;
						}

					function AppendFieldClass($name = NULL, $classname)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['attrs']['class'] .= ' '.$classname;
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

					function Readonly($name = NULL)
						{
							$this->SetFieldAttribute($name, 'readonly', 'readonly');
							return $this;
						}

					function Required($name = NULL, $value = true)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->form['fields'][$name]['required'] = $value;
							return $this;
						}

				//-------------------------------------------------------------
					function SaveButton($text)
						{
							$this->form['savetitle'] = $text;
							return $this;
						}

					function SetSaveButtonHelperText($text)
						{
							$this->form['savebutton_helper']['text'] = $text;
							return $this;
						}

					function SetSaveButtonHelperClassname($class)
						{
							$this->form['savebutton_helper']['class'] = $class;
							return $this;
						}

					function DisableSubmitButton()
						{
							$this->form['nosubmitbutton'] = true;
						}

				//-------------------------------------------------------------
					function Calendar($name = NULL, $format='', $weekStart=NULL, $language = NULL)
						{
							global $sm, $lang;
							sm_use('datepicker');
							if ($language === NULL)
								{
									if (sm_current_language() == 'ukr' || sm_current_language() == 'ua')
										$language = 'uk';
									else
										$language = 'en';
								}
							if ($language=='ukr' || $language=='ua')
								$language = 'uk';
							if (empty($format))
								{
									if ($language == 'uk')
										$format = 'dd.mm.yyyy';
									else
										$format = 'mm/dd/yyyy';
								}
							if ($weekStart === NULL)
								{
									if ($language == 'uk')
										$weekStart = 1;
									else
										$weekStart = 0;
								}
							if ($name === NULL)
								$name = $this->currentname;
							$sm['s']['document']['headend'] .= '
							<script type="text/javascript">
							$(function()
								{
									$( "#'.$this->form['prefix'].$name.'" ).datepicker({format:"'.jsescape($format).'", autoclose: true, todayHighlight:true, weekStart:'.$weekStart.', language:"'.$language.'"});
								});
							</script>';
							$this->form['fields'][$name]['is_calendar'] = true;
							$this->form['fields'][$name]['calendar_format']=$format;
							return $this;
						}

					function isSubtypeCalendar($name)
						{
							return $this->form['fields'][$name]['is_calendar']===true;
						}

					function WithMask($mask='', $placeholder='', $name = NULL)
						{
							global $sm, $lang;
							sm_use('maskedinput');
							if ($name === NULL)
								$name = $this->currentname;
							$sm['s']['document']['headend'] .= '
							<script type="text/javascript">
							$(function()
								{
									$( "#'.$this->form['prefix'].$name.'" ).mask("'.$mask.'"'.(strlen($placeholder)>0?', {placeholder:"'.$placeholder.'"}':'').');
								});
							</script>';
							$this->form['fields'][$name]['is_maskedinput']=true;
							$this->form['fields'][$name]['maskedinput_mask']=$mask;
							return $this;
						}

					function isMaskedInput($name)
						{
							return $this->form['fields'][$name]['maskedinput_mask']===true;
						}

					function NoHighlight($turn_off = true)
						{
							if ($turn_off)
								$this->form['no_highlight'] = 1;
							else
								$this->form['no_highlight'] = 0;
							return $this;
						}

					function SetTooltipImage($name, $image = 'help.gif')
						{
							if (!file_exists('themes/'.sm_settings('default_theme').'/images/admintable/'.$image))
								$this->form['fields'][$name]['tooltipimg'] = 'themes/default/images/admintable/'.$image;
							else
								$this->form['fields'][$name]['tooltipimg'] = 'themes/'.sm_settings('default_theme').'/images/admintable/'.$image;
							$this->form['tooltip_present'] = true;
							return $this;
						}

					function Tooltip($name, $text, $image = 'help.gif')
						{
							$this->form['fields'][$name]['tooltip'] = $text;
							$this->SetTooltipImage($name, $image);
							$this->form['tooltip_present'] = true;
							return $this;
						}

					function WithTooltip($text, $image = 'help.gif', $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->Tooltip($name, $text, $image);
							return $this;
						}

					function TooltipURL($name, $url, $open_in_new_page=true, $image = 'help.gif')
						{
							$this->form['fields'][$name]['tooltip_url'] = $url;
							if ($open_in_new_page)
								$this->form['fields'][$name]['tooltip_url_target'] = '_blank';
							$this->SetTooltipImage($name, $image);
							$this->form['tooltip_present'] = true;
							return $this;
						}

					function WithTooltipURL($url, $open_in_new_page=true, $image = 'help.gif', $name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							$this->TooltipURL($name, $url, $open_in_new_page, $image);
							return $this;
						}

					function SendFieldsInfo()
						{
							//Deprecated
							return $this;
						}

					function Autocomplete($ajax_url, $name = NULL)
						{
							global $sm;
							sm_use('autocomplete');
							sm_autocomplete_init_controls();
							if ($name === NULL)
								$name = $this->currentname;
							sm_autocomplete_for('#'.$this->form['prefix'].$name, $ajax_url);
							return $this;
						}

					function SetFocus($name = NULL)
						{
							if ($name === NULL)
								$name = $this->currentname;
							sm_setfocus($name);
							return $this;
						}

					function WithValue($value)
						{
							$this->SetValue($this->currentname, $value);
							return $this;
						}

					function WithNotEscapedValue($value)
						{
							$this->SetNotEscapedValue($this->currentname, $value);
							return $this;
						}

					function AddClassnameGlobal($classname)
						{
							$this->form['class'] .= ' '.$classname;
							return $this;
						}

					function SetStyleGlobal($style)
						{
							$this->SetFormAttribute('style', $style);
							return $this;
						}

					function javascriptCode($jscode)
						{
							$this->form['html_end'].='<script type="text/javascript">'.$jscode.'</script>';
						}

					public static function FieldTypeTagText()
						{
							return 'text';
						}

					public static function FieldTypeTagSeparator()
						{
							return 'separator';
						}

					public static function FieldTypeTagLabel()
						{
							return 'label';
						}

					public static function FieldTypeTagPassword()
						{
							return 'password';
						}

					public static function FieldTypeTagFile()
						{
							return 'file';
						}

					public static function FieldTypeTagStaticText()
						{
							return 'statictext';
						}

					public static function FieldTypeTagStaticHidden()
						{
							return 'hidden';
						}

					public static function FieldTypeTagStaticTextarea()
						{
							return 'textarea';
						}

					public static function FieldTypeTagSelect()
						{
							return 'select';
						}

					public static function FieldTypeTagCheckbox()
						{
							return 'checkbox';
						}

					function FieldsCount()
						{
							if (is_array($this->form['fields']))
								return count($this->form['fields']);
							else
								return 0;
						}
				}

			define("adminform_DEFINED", 1);
		}
