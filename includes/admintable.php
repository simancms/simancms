<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2018-03-10
	//==============================================================================

	if (!defined("admintable_DEFINED"))
		{
			sm_add_cssfile('common_admintable.css');

			class TGrid
				{
					var $table;
					var $rownumber;

					static $grids_used;

					private $sort_statement = '';

					function __construct($default_column = '', $postfix = '')
						{
							global $sm;
							$this->rownumber = 0;
							$this->table['columns']=Array();
							$this->table['default_column'] = '';
							$this->SetWidth('100%');
							if (strlen($postfix) == 0)
								$this->table['postfix'] = TGrid::$grids_used;
							else
								$this->table['postfix'] = $postfix;
							$this->AddClassnameGlobal('admintable');
							if (!empty($sm['admintable']['globalclass']))
								$this->AddClassnameGlobal($sm['admintable']['globalclass']);
							$this->SetDOMID('admintable'.$this->table['postfix']);
							TGrid::$grids_used++;
						}

					function SetWidth($width)
						{
							$this->table['attrs']['width'] = $width;
							return $this;
						}

					function SetInlineImagesStyleGlobal($style)
						{
							$this->table['inlineimages']['style'] = $style;
							return $this;
						}

					function SetInlineImagesClassGlobal($class)
						{
							$this->table['inlineimages']['class'] = $class;
							return $this;
						}

					function AddCol($name, $title, $width = '', $hint = '', $default_text = '', $default_image = '', $messagebox = 0, $messagebox_text = '')
						{
							global $sm;
							$this->table['columns'][$name]['caption'] = $title;
							$this->table['columns'][$name]['width'] = $width;
							if (empty($hint))
								$hint=$title;
							$this->table['columns'][$name]['hint'] = htmlescape(strip_tags($hint));
							$this->table['columns'][$name]['default_text'] = $default_text;
							$this->table['columns'][$name]['imagepath'] = true;//DEPRECATED: Left for compatibility with 1.6.9 and less
							if (!empty($default_image) && strpos($default_image, '/') === false)
								$this->table['columns'][$name]['default_image'] = TGrid::ImageURL($default_image);
							$this->table['columns'][$name]['messagebox'] = $messagebox;
							$this->table['columns'][$name]['messagebox_text'] = $messagebox_text;
							return $this;
						}

					function HasColumn($colname)
						{
							if (is_array($this->table['columns']) && array_key_exists($colname, $this->table['columns']))
								return true;
							else
								return false;
						}

					function GetColumnNames()
						{
							$cols=Array();
							if (is_array($this->table['columns']))
								{
									foreach ($this->table['columns'] as $name => $columnval)
										$cols[]=$name;
								}
							return $cols;
						}

					function GetColumnNameByIndex($column_index)
						{
							$cols=$this->GetColumnNames();
							return $cols[$column_index];
						}

					function GetColumnTitle($colname)
						{
							return $this->table['columns'][$colname]['caption'];
						}

					function SetColumnHeaderAttr($colname, $attrname, $attrval)
						{
							$this->table['columns'][$colname]['header_attr'][$attrname] = $attrval;
							return $this;
						}

					function GetColumnHeaderAttr($colname, $attrname)
						{
							return $this->table['columns'][$colname]['header_attr'][$attrname];
						}

					function AppendCoumnHeaderAttr($colname, $attrname, $attrval, $append_prefix = ' ')
						{
							$this->table['columns'][$colname]['header_attr'][$attrname] .= (strlen($this->table['columns'][$colname]['attr'][$attrname])>0 ? $append_prefix : '').$attrval;
							return $this;
						}

					function SetHeaderImage($name, $image)
						{
							$this->table['columns'][$name]['html'] .= '<img src="'.TGrid::ImageURL($image).'" class="adminform_header_image" />';
							return $this;
						}

					function AddIcon($name, $image, $hint = '')
						{
							if (strpos($image, '.') === false && strpos($image, '://') === false)
								$image .= '.gif';
							$this->AddCol($name, '', '16', $hint, $hint, $image);
							return $this;
						}

					function AddEdit($name = 'edit')
						{
							global $lang;
							$this->AddCol($name, '', '16', $lang['common']['edit'], $lang['common']['edit'], 'edit');
							return $this;
						}

					function AddActions($name = 'actions')
						{
							global $lang;
							$this->AddCol($name, '', '16', $lang['common']['actions'], $lang['common']['actions'], 'actions');
							return $this;
						}

					function AddDelete($msg = '', $name = 'delete')
						{
							global $lang;
							if (empty($msg))
								$msg = $lang['common']['really_want_delete'];
							$this->AddCol($name, '', '16', $lang['common']['delete'], $lang['common']['delete'], 'delete', 1, addslashes($msg));
							return $this;
						}

					function SetAsMessageBox($name, $msg)
						{
							$this->table['columns'][$name]['messagebox'] = 1;
							$this->table['columns'][$name]['messagebox_text'] = $msg;
							return $this;
						}

					function ColumnAddClass($name, $classname)
						{
							$this->table['columns'][$name]['column_class'] .= ' '.$classname;
							return $this;
						}

					function HeaderUrl($name, $url)
						{
							$this->table['columns'][$name]['headerurl'] = $url;
							return $this;
						}

					function AddMenuInsert($name = 'tomenu')
						{
							global $lang;
							$this->AddCol($name, '', '', $lang['module_menu']['add_to_menu'], $lang['module_menu']['add_to_menu']);
							$this->table['columns'][$name]['nobr'] = 1;
							return $this;
						}

					function SingleLineLabel($label)
						{
							$first=true;
							if (!empty($this->table['columns']))
								foreach ($this->table['columns'] as $key=>$val)
									{
										if ($first)
											{
												$this->Label($key, $label);
												$first=false;
											}
										else
											$this->Label($key, '');
									}
							$this->AttachEmptyCellsToLeft();
							return $this;
						}

					function AttachEmptyCellsToLeft()
						{
							if (!empty($this->table['columns']))
								{
									$i = 0;
									$notempty = '';
									$colspan = 1;
									reset($this->table['columns']);
									while (list($key, $val) = each($this->table['columns']))
										{
											if (strlen($this->table['rows'][$this->rownumber][$key]['data']) == 0 && strlen($this->table['rows'][$this->rownumber][$key]['image']) == 0 && strlen($this->table['rows'][$this->rownumber][$key]['url']) == 0 && $i>0)
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
													$notempty = $key;
													$colspan = 1;
												}
											$i++;
										}
									if ($colspan>1)
										$this->Colspan($notempty, $colspan);
								}
							return $this;
						}

					function AutoColspanFor($column_name)
						{
							if (!empty($this->table['columns']))
								{
									$colspan = 1;
									reset($this->table['columns']);
									$found=false;
									while (list($key, $val) = each($this->table['columns']))
										{
											if (!$found)
												{
													if ($key==$column_name)
														$found=true;
													continue;
												}
											if (strlen($this->table['rows'][$this->rownumber][$key]['data']) == 0 && strlen($this->table['rows'][$this->rownumber][$key]['image']) == 0 && strlen($this->table['rows'][$this->rownumber][$key]['headerurl']) == 0)
												{
													$this->Hide($key);
													$colspan++;
												}
											else
												break;
										}
									if ($colspan>1)
										$this->Colspan($column_name, $colspan);
								}
							return $this;
						}

					function AutoRowspanFor($column_name)
						{
							$row_index = 0;
							while ($row_index < $this->RowCount()-1)
								{
									$rowspan = 1;
									$rowspan_index=$row_index;
									for ($i = $row_index + 1; $i < $this->RowCount(); $i++)
										{
											if (
												strlen($this->table['rows'][$i][$column_name]['data']) == 0
												&& strlen($this->table['rows'][$i][$column_name]['image']) == 0
												&& strlen($this->table['rows'][$i][$column_name]['headerurl']) == 0
												&& intval($this->table['rows'][$i][$column_name]['hide']) != 1
											)
												{
													$this->Hide($column_name, $i);
													$rowspan++;
													$row_index++;
												}
											else
												{
													$row_index++;
													break;
												}
										}
									if ($rowspan > 1)
										$this->Rowspan($column_name, $rowspan, $rowspan_index);
								}
							return $this;
						}

					function HeaderAutoColspanFor($fieldname)
						{
							if (!empty($this->table['columns']))
								{
									$colspan = 1;
									reset($this->table['columns']);
									$found=false;
									while (list($key, $val) = each($this->table['columns']))
										{
											if (!$found)
												{
													if ($key==$fieldname)
														$found=true;
													continue;
												}
											if (strlen($this->table['columns'][$key]['caption']) == 0 && strlen($this->table['columns'][$key]['html']) == 0 && strlen($this->table['columns'][$key]['url']) == 0)
												{
													$this->HeaderHideCol($key);
													$colspan++;
												}
											else
												break;
										}
									if ($colspan>1)
										$this->HeaderColspan($fieldname, $colspan);
								}
							return $this;
						}

					function Colspan($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['colspan'] = $value;
							return $this;
						}

					function Rowspan($name, $value, $row_index=NULL)
						{
							if ($row_index===NULL)
								$row_index=$this->rownumber;
							$this->table['rows'][$row_index][$name]['attrs']['rowspan'] = $value;
							return $this;
						}

					function RowCount()
						{
							return count($this->table['rows']);
						}

					function ColCount()
						{
							return count($this->table['columns']);
						}

					function NewRow()
						{
							if (count($this->table['rows'])==0)
								$this->SetActiveRow(1);
							else
								{
									if ($this->rownumber<count($this->table['rows']))
										$this->rownumber=count($this->table['rows']);
									else
										$this->rownumber++;
									$this->SetActiveRow($this->rownumber);
								}
							return $this;
						}

					function SetActiveRow($index)
						{
							$this->rownumber=$index;
							while(count($this->table['rows'])<$this->rownumber)
								$this->table['rows'][]=Array();
						}

					function RemoveRow($row_index)
						{
							if ($row_index<=$this->RowCount())
								{
									array_splice($this->table['rows'], $row_index, 1);
									//Todo: $this->table['rowparams']
									if ($row_index+1<=$this->rownumber)
										$this->rownumber--;
								}
						}

					function NextRow()
						{
							$this->SetActiveRow($this->rownumber+1);
						}

					function Label($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] = $value;
							return $this;
						}

					function LabelAppend($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] .= $value;
							return $this;
						}

					//Data type should be used in export
					function SetDataTypeInteger($name)
						{
							$this->table['rows'][$this->rownumber][$name]['datatype'] = 'integer';
							return $this;
						}

					//Data type should be used in export
					function SetDataTypeMoney($name)
						{
							$this->table['rows'][$this->rownumber][$name]['datatype'] = 'money';
							return $this;
						}

					//Data type should be used in export
					function SetDataTypeFloat($name)
						{
							$this->table['rows'][$this->rownumber][$name]['datatype'] = 'float';
							return $this;
						}

					//Data type should be used in export
					function SetDataTypeText($name)
						{
							$this->table['rows'][$this->rownumber][$name]['datatype'] = '';
							return $this;
						}

					function GetLabelText($name, $row_index=NULL)
						{
							if ($row_index===NULL)
								$row_index=$this->rownumber;
							return $this->table['rows'][$row_index][$name]['data'];
						}

					function AddClassnameGlobal($classname)
						{
							$this->table['class'] .= ' '.$classname;
							return $this;
						}

					function CellAddClass($name, $classname, $rownumber=NULL)
						{
							if ($rownumber === NULL)
								$rownumber = $this->rownumber;
							$this->table['rows'][$rownumber][$name]['class'] .= ' '.$classname;
							return $this;
						}

					function CellHasClass($name, $classname, $rownumber=NULL)
						{
							if ($rownumber === NULL)
								$rownumber = $this->rownumber;
							$classes=explode(' ', $this->table['rows'][$rownumber][$name]['class']);
							if (in_array($classname, $classes))
								return true;
							else
								return false;
						}

					function CellAddStyle($name, $style)
						{
							$this->table['rows'][$this->rownumber][$name]['style'] .= $style;
							return $this;
						}

					function Hint($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['hint'] = htmlescape(strip_tags($value));
							return $this;
						}

					function SetCellHeaderHTML($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['html_start'] = $value;
							return $this;
						}

					function SetCellFooterHTML($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['html_end'] = $value;
							return $this;
						}

					function AppendCellHeaderHTML($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['html_start'] .= $value;
							return $this;
						}

					function AppendCellFooterHTML($name, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['html_end'] .= $value;
							return $this;
						}

					function CellHeaderHTML($name, $value)
						{
							return $this->table['rows'][$this->rownumber][$name]['html_start'];
						}

					function CellFooterHTML($name, $value)
						{
							return $this->table['rows'][$this->rownumber][$name]['html_end'];
						}

					function CellDOMID($name)
						{
							return 'at'.$this->table['postfix'].'-cell-'.$name.'-'.$this->rownumber;
						}

					public static function ImageURL($image_name)
						{
							if (strpos($image_name, '.') === false && strpos($image_name, '://') === false)
								$image_name .= '.gif';
							if (strpos($image_name, '/') === false)
								{
									if (file_exists('themes/'.sm_current_theme().'/images/admintable/'.$image_name))
										$image_name = 'themes/'.sm_current_theme().'/images/admintable/'.$image_name;
									else
										$image_name = 'themes/default/images/admintable/'.$image_name;
								}
							return $image_name;
						}

					function Image($name, $image_url)
						{
							if (!empty($image_url))
								{
									$this->table['rows'][$this->rownumber][$name]['imagepath']=true;//DEPRECATED: Left for compatibility with 1.6.9 and less
									$this->table['rows'][$this->rownumber][$name]['image']=TGrid::ImageURL($image_url);
								}
							return $this;
						}

					function InlineImage($name, $image, $url='', $onclick_javascript='')
						{
							$i=count($this->table['rows'][$this->rownumber][$name]['inlineimages']);
							$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['image'] = TGrid::ImageURL($image);
							if (!empty($onclick_javascript) && empty($url))
								$url='javascript:;';
							$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['url'] = $url;
							$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['onclick'] = $onclick_javascript;
							return $this;
						}

					function CustomMessageBox($name, $message)
						{
							$this->table['rows'][$this->rownumber][$name]['messagebox_text'] = $message;
							return $this;
						}

					function URL($name, $value, $open_in_new_window = false)
						{
							if (is_array($name))
								{
									for ($i = 0; $i<count($name); $i++)
										$this->URL($name[$i], $value, $open_in_new_window);
								}
							else
								{
									$this->table['rows'][$this->rownumber][$name]['url'] = $value;
									$this->table['rows'][$this->rownumber][$name]['new_window'] = $open_in_new_window;
								}
							return $this;
						}

					function Menu($menu_caption, $menu_url, $name = 'tomenu')
						{
							global $lang;
							sm_extcore();
							$this->URL($name, sm_tomenuurl($menu_caption, $menu_url, sm_this_url()));
							return $this;
						}

					function Hide($name, $row_index=NULL)
						{
							if ($row_index===NULL)
								$row_index=$this->rownumber;
							$this->table['rows'][$row_index][$name]['hide'] = 1;
							return $this;
						}

					function ExpanderHTML($html)
						{
							$this->table['expanders'][$this->rownumber]['html'] = $html;
							$this->table['expanders'][$this->rownumber]['enabled'] = true;
							return $this;
						}

					function Expand($name)
						{
							$this->table['rows'][$this->rownumber][$name]['url'] = 'javascript:;';
							$this->table['rows'][$this->rownumber][$name]['onclick'] .= "document.getElementById('admintable-expander-".$this->rownumber."-".$this->table['postfix']."').style.display=(document.getElementById('admintable-expander-".$this->rownumber."-".$this->table['postfix']."').style.display)?'':'none';";
							return $this;
						}

					function ExpandAJAX($name, $url)
						{
							$this->Expand($name);
							$this->table['rows'][$this->rownumber][$name]['onclick'] .= "admintable_ajax_load".$this->table['postfix']."('".$url."', 'admintable-expanderarea-".$this->rownumber."-".$this->table['postfix']."');";
							return $this;
						}

				//--------------------------------------------------------------------------------------------------------
					function HeaderColspan($name, $value = 2)
						{
							$this->table['columns'][$name]['headercolspan'] = $value;
							return $this;
						}

					function HeaderHideCol($name)
						{
							$this->table['columns'][$name]['hideheader'] = 1;
							return $this;
						}

					function HideHeader()
						{
							$this->table['hideheader'] = 1;
							return $this;
						}

				//--------------------------------------------------------------------------------------------------------
					function OnClick($name, $code)
						{
							$this->table['rows'][$this->rownumber][$name]['onclick'] .= $code;
							return $this;
						}

					function HeaderOnClick($name, $code)
						{
							$this->table['columns'][$name]['onclick'] .= $code;
							return $this;
						}

					function DropDownItemsCount($name)
						{
							return count($this->table['rows'][$this->rownumber][$name]['dropdownitems']);
						}

					function DropDownItem($name, $title, $url, $confirm_message = '', $tomenutitle = '')
						{
							$this->table['rows'][$this->rownumber][$name]['dropdown'] = 1;
							$this->URL($name, 'javascript:;');
							$this->OnClick($name, "atdropdownopen".$this->table['postfix']."('atdropdown-".$name."-".$this->rownumber."-".$this->table['postfix']."');");
							$i = count($this->table['rows'][$this->rownumber][$name]['dropdownitems']);
							$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['title'] = $title;
							$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['url'] = $url;
							$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['confirm_message'] = htmlescape($confirm_message);
							$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['tomenutitle'] = $tomenutitle;
							return $this;
						}

					function DropDownItemSelect($name, $index = -1)
						{
							if ($index == -1)
								$i = count($this->table['rows'][$this->rownumber][$name]['dropdownitems'])-1;
							else
								$i = $index;
							$this->table['rows'][$this->rownumber][$name]['dropdownitems'][$i]['selected'] = 1;
							return $this;
						}

					function HeaderDropDownItem($name, $title, $url, $confirm_message = '')
						{
							$this->table['columns'][$name]['dropdown'] = 1;
							$this->HeaderUrl($name, 'javascript:;');
							$this->HeaderOnClick($name, "atdropdownopen".$this->table['postfix']."('atdropdown-".$name."-".$this->table['postfix']."');");
							$i = count($this->table['columns'][$name]['dropdownitems']);
							$this->table['columns'][$name]['dropdownitems'][$i]['title'] = $title;
							$this->table['columns'][$name]['dropdownitems'][$i]['url'] = $url;
							$this->table['columns'][$name]['dropdownitems'][$i]['confirm_message'] = $confirm_message;
							return $this;
						}

					function HeaderDropDownItemSelect($name, $index = -1)
						{
							if ($index == -1)
								$i = count($this->table['columns'][$name]['dropdownitems'])-1;
							else
								$i = $index;
							$this->table['columns'][$name]['dropdownitems'][$i]['selected'] = 1;
							return $this;
						}

					function HeaderDropDownItemAutoSelect($name, $url=NULL)
						{
							if ($url===NULL)
								$url=sm_this_url();
							for ($i = 0; $i<count($this->table['columns'][$name]['dropdownitems']); $i++)
								{
									if (strcmp(sm_relative_url($this->table['columns'][$name]['dropdownitems'][$i]['url']), sm_relative_url($url))==0)
										$this->HeaderDropDownItemSelect($name, $i);
								}
							return $this;
						}

					private function USortRowsByColumnData($a, $b)
						{
							if ($a == $b)
								return 0;
							$cols = explode(',', $this->sort_statement);
							for ($j = 0; $j<count($cols); $j++)
								{
									$col = explode(' ', trim($cols[$j]));
									if (strtoupper($col[2]) == 'NUM' || strtoupper($col[1]) == 'NUM')
										{
											if ($a[$col[0]]['data'] == $b[$col[0]]['data'])
												$result = 0;
											else
												$result = $a[$col[0]]['data']>$b[$col[0]]['data'] ? 1 : -1;
										}
									else
										$result = strcmp($a[$col[0]]['data'], $b[$col[0]]['data']);
									if ($result != 0)
										return (strtoupper($col[1]) == 'DESC' ? -1 : 1)*($result<0 ? -1 : 1);
								}
						}

					function SortRowsByColumnData($comma_separaded_columns)
						{
							if ($this->RowCount()==0)
								return;
							$this->sort_statement = $comma_separaded_columns;
							usort($this->table['rows'], array(
															 $this,
															 "USortRowsByColumnData"
														));
							return $this;
						}

				//-------- FORM FUNCTIONS ------------------------------------------------------------------------------------------------
					function Textbox($name, $varname, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] = $value;
							$this->table['rows'][$this->rownumber][$name]['element'] = 'text';
							$this->table['rows'][$this->rownumber][$name]['varname'] = $varname;
							return $this;
						}

					function Selectbox($name, $varname, $value, $valuesarrayornllist, $labelsarrayornllist)
						{
							if (!is_array($valuesarrayornllist))
								$valuesarrayornllist = nllistToArray($valuesarrayornllist);
							if (!is_array($labelsarrayornllist))
								$labelsarrayornllist = nllistToArray($labelsarrayornllist);
							$this->table['rows'][$this->rownumber][$name]['data'] = $value;
							$this->table['rows'][$this->rownumber][$name]['element'] = 'select';
							$this->table['rows'][$this->rownumber][$name]['values'] = $valuesarrayornllist;
							$this->table['rows'][$this->rownumber][$name]['labels'] = $labelsarrayornllist;
							$this->table['rows'][$this->rownumber][$name]['varname'] = $varname;
							return $this;
						}

					function Checkbox($name, $varname, $checkedvalue, $checked = false)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] = $checkedvalue;
							$this->table['rows'][$this->rownumber][$name]['element'] = 'checkbox';
							$this->table['rows'][$this->rownumber][$name]['varname'] = $varname;
							$this->table['rows'][$this->rownumber][$name]['checked'] = $checked;
							return $this;
						}

					function RadioItem($name, $varname, $checkedvalue, $checked = false)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] = $checkedvalue;
							$this->table['rows'][$this->rownumber][$name]['element'] = 'radioitem';
							$this->table['rows'][$this->rownumber][$name]['varname'] = $varname;
							$this->table['rows'][$this->rownumber][$name]['checked'] = $checked;
							return $this;
						}

					function SetControlAttr($name, $attrname, $attrval)
						{
							$this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname] = $attrval;
							return $this;
						}

					function GetControlAttr($name, $attrname)
						{
							return $this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname];
							return $this;
						}

					function AppendControlAttr($name, $attrname, $attrval, $append_prefix = ' ')
						{
							$this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname] .= (strlen($this->table['rows'][$this->rownumber][$name]['control_attr'][$attrname])>0 ? $append_prefix : '').$attrval;
							return $this;
						}

					function GetControlDOMID($name, $rownumber = NULL)
						{
							if ($rownumber === NULL)
								$rownumber = $this->rownumber;
							return 'control-'.$this->table['postfix'].'-'.$name.'-row'.$rownumber;
						}

				//Input type=hidden + Label
					function StoredLabel($name, $varname, $value)
						{
							$this->table['rows'][$this->rownumber][$name]['data'] = $value;
							$this->table['rows'][$this->rownumber][$name]['element'] = 'storedlabel';
							$this->table['rows'][$this->rownumber][$name]['varname'] = $varname;
							return $this;
						}

				//-------- /FORM FUNCTIONS ------------------------------------------------------------------------------------------------
					function NoHighlight()
						{
							$this->table['no_highlight'] = 1;
							return $this;
						}

					function HeaderBulkCheckbox($name)
						{
							$this->table['columns'][$name]['html'] = '<input type="checkbox" id="'.$name.'-'.($this->table['postfix']).'-bulkcheckbox" class="at-bulk-checkbox" onchange="'.
								"\$('.admintable-".($this->table['postfix'])."-control-".$name."').prop('checked', \$('#".$name.'-'.($this->table['postfix'])."-bulkcheckbox').prop('checked')?true:false);$('.admintable-".($this->table['postfix'])."-control-".$name."').trigger('change');".
								'" />';
							return $this;
						}

				//-----------------------------
					function LabelsFromArray($array)
						{
							if (!is_array($this->table['columns']) || !is_array($array))
								return;
							foreach ($this->table['columns'] as $key => $val)
								{
									if (array_key_exists($key, $array))
										$this->Label($key, $array[$key]);
								}
							return $this;
						}
					function SetLabels()
						{
							if (func_num_args()==0)
								return $this;
							$columns=$this->GetColumnNames();
							for ($i = 0; $i < count($columns) && $i<func_num_args(); $i++)
								{
									$this->Label($columns[$i], func_get_arg($i));
								}
							return $this;
						}

				//-----------------------------
					function RowAddClass($classname, $rownumber = NULL)
						{
							if ($rownumber === NULL)
								$rownumber = $this->rownumber;
							$this->table['rowparams'][$rownumber]['class'] .= ' '.$classname;
							return $this;
						}

					function RowAddStyle($rule, $rownumber = NULL)
						{
							if ($rownumber === NULL)
								$rownumber = $this->rownumber;
							$this->table['rowparams'][$rownumber]['style'] .= $rule;
							return $this;
						}
					function RowHighlightError($rownumber = NULL)
						{
							$this->RowAddClass('at-highlight-error', $rownumber);
						}
					function RowHighlightWarning($rownumber = NULL)
						{
							$this->RowAddClass('at-highlight-warning', $rownumber);
						}
					function RowHighlightInfo($rownumber = NULL)
						{
							$this->RowAddClass('at-highlight-info', $rownumber);
						}
					function RowHighlightSuccess($rownumber = NULL)
						{
							$this->RowAddClass('at-highlight-success', $rownumber);
						}
					function RowHighlightAttention($rownumber = NULL)
						{
							$this->RowAddClass('at-highlight-attention', $rownumber);
						}
					function CellHighlightError($name)
						{
							$this->CellAddClass($name, 'at-highlight-error');
						}
					function CellHighlightWarning($name)
						{
							$this->CellAddClass($name, 'at-highlight-warning');
						}
					function CellHighlightInfo($name)
						{
							$this->CellAddClass($name, 'at-highlight-info');
						}
					function CellHighlightSuccess($name)
						{
							$this->CellAddClass($name, 'at-highlight-success');
						}
					function CellHighlightAttention($name)
						{
							$this->CellAddClass($name, 'at-highlight-attention');
						}
					function CellAlignLeft($name)
						{
							$this->CellAddStyle($name, 'text-align:left;');
						}
					function CellAlignRight($name)
						{
							$this->CellAddStyle($name, 'text-align:right;');
						}
					function CellAlignCenter($name)
						{
							$this->CellAddStyle($name, 'text-align:center;');
						}
					function NoBR($name=NULL)
						{
							if ($name===NULL)
								{
									foreach ($this->table['columns'] as $name => $columnval)
										$this->NoBR($name);
								}
							elseif (is_array($name))
								{
									foreach ($name as $colname)
										$this->NoBR($colname);
								}
							else
								$this->CellAddClass($name, 'at-nobr');
						}

					//====================================================
					function SetGlobalAttribute($attribute, $value)
						{
							if ($value===NULL)
								unset($this->table['attrs'][$attribute]);
							else
								$this->table['attrs'][$attribute] = $value;
							return $this;
						}

					function HasGlobalAttribute($attribute)
						{
							return is_array($this->table['attrs']) && array_key_exists($attribute, $this->table['attrs']);
						}

					function GetGlobalAttribute($attribute)
						{
							return $this->table['attrs'][$attribute];
						}

					function AppendGlobalAttribute($attribute, $value, $delimiter=' ')
						{
							$attrval=$this->GetGlobalAttribute($attribute);
							if (!empty($attrval))
								$attrval.=$delimiter;
							$attrval.=$value;
							$this->SetGlobalAttribute($attribute, $attrval);
							return $this;
						}

					function UnsetGlobalAttribute($attribute)
						{
							$this->SetGlobalAttribute($attribute, NULL);
							return $this;
						}

					function SetDOMID($id)
						{
							$this->SetGlobalAttribute('id', $id);
						}

					function GetDOMID($id)
						{
							return $this->GetGlobalAttribute('id');
						}

					//====================================================
					function Output()
						{
							$this->table['colcount'] = count($this->table['columns']);
							$this->table['rowcount'] = count($this->table['rows']);
							if (!empty($this->table['class']))
								$this->SetGlobalAttribute('class', $this->table['class']);
							for ($this->rownumber = 0; $this->rownumber<$this->RowCount(); $this->rownumber++)
								{
									$this->RowAddClass('at-row-'.$this->rownumber, $this->rownumber);
									if (intval($this->table['no_highlight']) != 1)
										if ($this->rownumber%2 == 0)
											$this->RowAddClass('at-row-pair', $this->rownumber);
										else
											$this->RowAddClass('at-row-odd', $this->rownumber);
									foreach ($this->table['columns'] as $name => $columnval)
										{
											if (in_array($this->table['rows'][$this->rownumber][$name]['element'], Array(
																														'text',
																														'select',
																														'checkbox',
																														'radioitem',
																														'storedlabel'
																												   ))
											)
												{
													if ($this->table['rows'][$this->rownumber][$name]['element'] == 'text')
														$this->SetControlAttr($name, 'type', 'text');
													if ($this->table['rows'][$this->rownumber][$name]['element'] == 'checkbox')
														{
															$this->SetControlAttr($name, 'type', 'checkbox');
															if ($this->table['rows'][$this->rownumber][$name]['checked'])
																$this->SetControlAttr($name, 'checked', 'checked');
														}
													if ($this->table['rows'][$this->rownumber][$name]['element'] == 'radioitem')
														$this->SetControlAttr($name, 'type', 'radio');
													if ($this->table['rows'][$this->rownumber][$name]['element'] == 'storedlabel')
														$this->SetControlAttr($name, 'type', 'hidden');
													if ($this->table['rows'][$this->rownumber][$name]['element'] == 'select')
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
															$this->AppendControlAttr($name, 'class', 'admintable-'.$this->table['postfix'].'-control-'.$name);
														}
													$this->SetControlAttr($name, 'name', $this->table['rows'][$this->rownumber][$name]['varname']);
													$this->SetControlAttr($name, 'id', $this->GetControlDOMID($name, $this->rownumber));
													if (!empty($this->table['rows'][$this->rownumber][$name]['onclick']))
														$this->SetControlAttr($name, 'onclick', $this->table['rows'][$this->rownumber][$name]['onclick']);
												}
											if (count($this->table['rows'][$this->rownumber][$name]['inlineimages'])>0)
												{
													$inlineimages='';
													for ($i = 0; $i<count($this->table['rows'][$this->rownumber][$name]['inlineimages']); $i++)
														{
															if (!empty($this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['url']))
																{
																	$html='<a href="'.$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['url'].'"';
																	if (!empty($this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['onclick']))
																		$html.=' onclick="'.$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['onclick'].'"';
																	$html.='>'.'<img src="'.$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['image'].'" />'.'</a>';
																}
															else
																$html='<img src="'.$this->table['rows'][$this->rownumber][$name]['inlineimages'][$i]['image'].'" />';
															$inlineimages.=$html;
														}
													$this->table['rows'][$this->rownumber][$name]['data'].='<span class="at-inlineimages'.(empty($this->table['inlineimages']['class'])?'':' '.$this->table['inlineimages']['class']).'"'.(empty($this->table['inlineimages']['style'])?'':' style="'.$this->table['inlineimages']['style']).'">'.$inlineimages.'</span>';
												}
											if (!empty($this->table['columns'][$name]['column_class']))
												{
													$this->CellAddClass($name, $this->table['columns'][$name]['column_class'], $this->rownumber);
												}
											if (!empty($this->table['rows'][$this->rownumber][$name]['colspan']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['colspan']=$this->table['rows'][$this->rownumber][$name]['colspan'];
											if (!empty($this->table['rows'][$this->rownumber][$name]['hint']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['title']=$this->table['rows'][$this->rownumber][$name]['hint'];
											elseif (!empty($this->table['columns'][$name]['hint']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['title']=$this->table['columns'][$name]['hint'];
											if (!empty($this->table['rows'][$this->rownumber][$name]['align']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['align']=$this->table['rows'][$this->rownumber][$name]['align'];
											if ($this->table['hideheader']==1 && !empty($this->table['columns'][$name]['width']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['width']=$this->table['columns'][$name]['width'];
											$this->table['rows'][$this->rownumber][$name]['attrs']['id']='at'.$this->table['postfix'].'-cell-'.$name.'-'.$this->rownumber;
											$this->table['rows'][$this->rownumber][$name]['attrs']['class']='at-cell-'.$name.$this->table['rows'][$this->rownumber][$name]['class'];
											if (!empty($this->table['rows'][$this->rownumber][$name]['style']))
												$this->table['rows'][$this->rownumber][$name]['attrs']['style']=$this->table['rows'][$this->rownumber][$name]['style'];
										}
								}
							return $this->table;
						}
				}

			TGrid::$grids_used = 0;

			define("admintable_DEFINED", 1);
		}