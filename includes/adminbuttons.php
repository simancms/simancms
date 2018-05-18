<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2018-05-18
	//==============================================================================

	if (!defined("adminbuttons_DEFINED"))
		{
			sm_add_cssfile('common_adminbuttons.css');
			sm_add_jsfile('common_adminbuttons.js');

			class TButtons
				{
					var $bar;
					private $currentbuttonname;

					function __construct($buttonbar_title = '')
						{
							global $sm;
							$this->bar['buttonbar_title'] = $buttonbar_title;
							$this->SetButtonsSeparatorHTML('');
							$this->AddClassnameGlobal('adminbuttons');
							if (!empty($sm['adminbuttons']['globalclass']))
								$this->AddClassnameGlobal($sm['adminbuttons']['globalclass']);
							if (!empty($sm['adminbuttons']['buttonseparator']))
								$this->SetButtonsSeparatorHTML($sm['adminbuttons']['buttonseparator']);
							if (!empty($sm['adminbuttons']['htmlbegin']))
								$this->SetBeginHTML($sm['adminbuttons']['htmlbegin']);
							if (!empty($sm['adminbuttons']['htmlend']))
								$this->SetEndHTML($sm['adminbuttons']['htmlend']);
						}

					function SetTitleForBar($buttonbar_title)
						{
							$this->bar['buttonbar_title'] = $buttonbar_title;
						}

					function SetBeginHTML($html)
						{
							$this->bar['htmlbegin'] = $html;
						}

					function GetBeginHTML()
						{
							return $this->bar['htmlbegin'];
						}

					function SetEndHTML($html)
						{
							$this->bar['htmlend'] = $html;
						}

					function GetEndHTML()
						{
							return $this->bar['htmlend'];
						}

					function SetButtonsSeparatorHTML($html)
						{
							$this->bar['buttons_separator'] = $html;
						}

					function GetButtonsSeparatorHTML()
						{
							return $this->bar['buttons_separator'];
						}

					function Button($title, $url = '')
						{
							$this->AddButton('', $title, $url, 'button');
							return $this;
						}

					function HasButton($name)
						{
							if (!is_array($this->bar['buttons']))
								return false;
							return array_key_exists($name, $this->bar['buttons']);
						}

					function AddButton($name, $title, $url = '', $type = 'button', $style = '', $messagebox_message = '', $javascript = '')
						{
							global $sm;
							if (empty($name))
								$name = md5(rand(1, 999999));
							$this->currentbuttonname = $name;
							$this->bar['buttons'][$name]['name'] = $name;
							$this->bar['buttons'][$name]['caption'] = $title;
							$this->bar['buttons'][$name]['url'] = $url;
							$this->bar['buttons'][$name]['type'] = $type;
							$this->bar['buttons'][$name]['javascript'] = $javascript;
							$this->bar['buttons'][$name]['style'] = $style;
							$this->bar['buttons'][$name]['message'] = addslashes($messagebox_message);
							$this->SetAttr('type', 'button');
							if (!empty($sm['adminbuttons']['buttonclass']))
								$this->AddClassname($sm['adminbuttons']['buttonclass']);
							if (!empty($sm['adminbuttons']['buttonbegin']))
								$this->WithBeginHTML($sm['adminbuttons']['buttonbegin']);
							if (!empty($sm['adminbuttons']['buttonend']))
								$this->WithEndHTML($sm['adminbuttons']['buttonend']);
							return $this;
						}

					function AddSeparator($name, $title = ' | ', $style = '')
						{
							if (empty($name))
								$name = md5(rand(1, 999999));
							$this->currentbuttonname = $name;
							$this->bar['buttons'][$name]['name'] = $name;
							$this->bar['buttons'][$name]['caption'] = $title;
							$this->bar['buttons'][$name]['style'] = $style;
							$this->bar['buttons'][$name]['type'] = 'separator';
							return $this;
						}

					function Separator($title = ' | ', $style = '')
						{
							$this->AddSeparator('', $title, $style);
							return $this;
						}

					function Toggle($title, $toggle_id_focus, $style = '')
						{
							$this->AddToggle('', $title, $toggle_id_focus, $style);
							return $this;
						}

					function AddToggle($name, $title, $toggle_id_focus, $style = '')
						{
							if (!is_array($toggle_id_focus))
								$toggle_id_focus = Array($toggle_id_focus);
							$javascript = "tmp=document.getElementById('".$toggle_id_focus[0]."');tmp.style.display=(tmp.style.display=='none')?'':'none';";
							if (!empty($toggle_id_focus[1]))
								$javascript .= "tmp1=document.getElementById('".$toggle_id_focus[1]."');if(tmp.style.display=='')tmp1.focus();";
							$this->AddButton($name, $title, '', 'button', $style, '', $javascript);
							return $this;
						}

					function MessageBox($title, $url, $messagebox_message = NULL)
						{
							$this->AddMessageBox('', $title, $url, $messagebox_message);
							return $this;
						}

					function AddMessageBox($name, $title, $url, $messagebox_message = NULL, $style = '')
						{
							global $lang;
							if ($messagebox_message === NULL)
								$messagebox_message = $lang['common']['are_you_sure'].(substr($lang['common']['are_you_sure'], -1) == '?' ? '' : '?');
							$this->AddButton($name, $title, $url, 'messagebox', $style, $messagebox_message);
							return $this;
						}

					function Bold($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['bold'] = true;
							return $this;
						}

					function Width($buttonname, $width)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['width'] = $width;
							return $this;
						}

					function SetWidth($width)
						{
							$this->Width(NULL, $width);
							return $this;
						}

					function Height($buttonname, $height)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['height'] = $height;
							return $this;
						}

					function SetHeight($height)
						{
							$this->Height(NULL, $height);
							return $this;
						}

					function Style($name, $style)
						{
							$this->bar['buttons'][$name]['style'] = $style;
							return $this;
						}

					function AssignImage($name, $imagename)
						{
							$this->bar['buttons'][$name]['image'] = $imagename;
							return $this;
						}

					function AddClassnameGlobal($classname)
						{
							$this->bar['class'] = (empty($this->bar['class']) ? '' : ' ').$classname;
							return $this;
						}

					function AddClassname($classname, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['class'] .= (empty($this->bar['buttons'][$buttonname]['class']) ? '' : ' ').$classname;
							return $this;
						}

					function HighlightSuccess($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-success', $buttonname);
						}

					function HighlightError($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-error', $buttonname);
						}

					function HighlightWarning($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-warning', $buttonname);
						}

					function HighlightPrimary($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-primary', $buttonname);
						}

					function HighlightInfo($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-info', $buttonname);
						}

					function HighlightAttention($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->AddClassname('ab-highlight-attention', $buttonname);
						}

					function SetAttr($attrname, $attrvalue, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['attrs'][$attrname] = $attrvalue;
							return $this;
						}

					function ApplyClassnameForAll($classname)
						{
							if (is_array($this->bar['buttons']))
								{
									foreach ($this->bar['buttons'] as $buttonname => &$buttonparams)
										{
											$this->AddClassname($classname, $buttonname);
										}
								}
							return $this;
						}

					function Count()
						{
							return count($this->bar['buttons']);
						}

					function OnClick($javascript, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['javascript'] = $javascript;
							return $this;
						}

					function SetStyleGlobal($style)
						{
							$this->bar['style'] = $style;
							return $this;
						}

					function WithBeginHTML($html, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['htmlbegin'] = $html;
							return $this;
						}

					function WithEndHTML($html, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['htmlend'] = $html;
							return $this;
						}

					function DropDownItem($title, $url='', $open_in_new_window=false, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['is_dropdown']=true;
							$this->bar['buttons'][$buttonname]['dropdown_items'][] = Array(
								'caption'=>$title,
								'href'=>$url,
								'target'=>$open_in_new_window?'_blank':'',
								'class'=>'btn-dropdown-item'
							);
							return $this;
						}

					function DropDownOnClick($title, $javascript, $buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['is_dropdown']=true;
							$this->bar['buttons'][$buttonname]['dropdown_items'][] = Array(
								'caption'=>$title,
								'onclick'=>$javascript,
								'class'=>'btn-dropdown-item'
							);
							return $this;
						}

					function DropDownMessageBox($title, $url='', $messagebox_message = NULL, $buttonname = NULL)
						{
							global $lang;
							if ($messagebox_message === NULL)
								$messagebox_message = $lang['common']['are_you_sure'];
							$this->DropDownOnClick($title, "button_msgbox('".$url."', '".jsescape($messagebox_message)."');", $buttonname);
							return $this;
						}

					function DropDownSeparator($buttonname = NULL)
						{
							if ($buttonname == NULL)
								$buttonname = $this->currentbuttonname;
							$this->bar['buttons'][$buttonname]['is_dropdown']=true;
							$this->bar['buttons'][$buttonname]['dropdown_items'][] = Array(
								'separator'=>true
							);
							return $this;
						}

					function Output()
						{
							if (is_array($this->bar['buttons']))
								{
									foreach ($this->bar['buttons'] as $buttonname => &$buttonparams)
										{
											if ($buttonparams['type'] == 'separator')
												{
													$buttonparams['htmlelement'] = 'span';
													$buttonparams['class'] .= (empty($buttonparams['class']) ? '' : ' ').'ab-separator';
												}
											else
												{
													$buttonparams['htmlelement'] = 'button';
													$buttonparams['class'] .= (empty($buttonparams['class']) ? '' : ' ').'ab-button';
												}
											if ($buttonparams['bold'])
												$buttonparams['style'] .= (empty($buttonparams['style']) ? '' : ';').'font-weight:bold;';
											if (!empty($buttonparams['width']))
												$buttonparams['style'] .= (empty($buttonparams['style']) ? '' : ';').'width:'.$buttonparams['width'].';';
											if (!empty($buttonparams['height']))
												$buttonparams['style'] .= (empty($buttonparams['style']) ? '' : ';').'height:'.$buttonparams['height'].';';
											if (!empty($buttonparams['url']) || !empty($buttonparams['javascript']))
												{
													if ($buttonparams['type'] == 'messagebox')
														$buttonparams['onclick'] = "button_msgbox('".$buttonparams['url']."', '".jsescape($buttonparams['message'])."');";
													elseif (!empty($buttonparams['javascript']))
														$buttonparams['onclick'] = $buttonparams['javascript'];
													else
														$buttonparams['onclick'] = "location.href='".$buttonparams['url']."'";
												}
											$buttonparams['html'] = $buttonparams['caption'];
											if (!empty($buttonparams['image']))
												$buttonparams['html']='<img class="ab-btn-image" src="'.$buttonparams['image'].'" />'.$buttonparams['html'];
											$buttonparams['attrs']['style'] = $buttonparams['style'];
											$buttonparams['attrs']['onclick'] = $buttonparams['onclick'];
											$buttonparams['attrs']['class'] = $buttonparams['class'];
											if ($this->bar['buttons'][$buttonname]['is_dropdown'])
												{
													$buttonparams['htmlbegin'].='<div class="btn-group">';
													$buttonparams['attrs']['class'] .= ' dropdown-toggle';
													$buttonparams['attrs']['data-toggle'] = 'dropdown';
													$buttonparams['attrs']['aria-haspopup'] = 'true';
													$buttonparams['attrs']['aria-expanded'] = 'false';
													$dropdownhtml='<ul class="dropdown-menu">';
													for ($i = 0; $i < count($buttonparams['dropdown_items']); $i++)
														{
															if ($buttonparams['dropdown_items'][$i]['separator'])
																$dropdownhtml.='<li role="separator" class="divider"></li>';
															else
																{
																	$dropdownhtml .= '<li><a';
																	foreach ($buttonparams['dropdown_items'][$i] as $dropdown_attr => $dropdown_attr_value)
																		{
																			if ($dropdown_attr == 'caption' || strlen($dropdown_attr_value) == 0)
																				continue;
																			elseif ($dropdown_attr == 'title')
																				$dropdown_attr_value = strip_tags($dropdown_attr_value);
																			$dropdown_attr_value = str_replace('"', '&quot;', $dropdown_attr_value);
																			$dropdownhtml .= ' '.$dropdown_attr.'="'.$dropdown_attr_value.'"';
																		}
																	$dropdownhtml .= '>'.$buttonparams['dropdown_items'][$i]['caption'].'</a></li>';
																}
														}
													$dropdownhtml.='</ul>';
													$dropdownhtml.='</div>';
													$buttonparams['htmlend']=$dropdownhtml.$buttonparams['htmlend'];
												}
										}
								}
							$this->bar['count'] = count($this->bar['buttons']);
							return $this->bar;
						}
				}

			define("adminbuttons_DEFINED", 1);
		}
