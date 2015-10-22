<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.10
	//#revision 2015-10-20
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($sm['u']['level'] > 0)
		{
			if (!defined("CONTENT_MEMBERSPART_FUNCTIONS_DEFINED"))
				{
					function siman_get_available_categories()
						{
							global $sm;
							$categories = siman_load_ctgs_content(
								-1,
								convert_groups_to_sql($sm['u']['groups'], 'groups_modify')
							);
							return $categories;
						}
					function siman_is_allowed_to_add_content()
						{
							global $sm;
							if ($sm['u']['level']>=intval(sm_settings('content_editor_level')))
								return true;
							elseif (!empty($sm['u']['groups']))
								{
									$categories = siman_get_available_categories();
									if (count($categories)>0)
										return true;
								}
							return false;
						}
					function siman_is_allowed_to_edit_content($id)
						{
							global $sm;
							if ($sm['u']['level']>=intval(sm_settings('content_editor_level')))
								return true;
							elseif (!empty($sm['u']['groups']))
								{
									$categories = siman_get_available_categories();
									if (count($categories)>0)
										{
											$content=TQuery::ForTable($sm['t']['content'].'content')
												->AddWhere('id_content', intval($id))
												->Get();
											if (empty($content['id_content']))
												return false;
											for ($i = 0; $i < count($categories); $i++)
												{
													if (intval($categories[$i]['id'])==intval($content['id_category_c']))
														return true;
												}
										}
								}
							return false;
						}
					define("CONTENT_MEMBERSPART_FUNCTIONS_DEFINED", 1);
				}


			if (sm_action('postadd') && siman_is_allowed_to_add_content() || sm_action('postedit') && siman_is_allowed_to_edit_content(intval($_getvars['id'])))
				{
					sm_extcore();
					if (empty($_postvars['title_content']) || empty($_postvars['id_category_c']))
						$error=$lang['messages']['fill_requied_fields'];
					elseif (sm_action('postadd') && !empty($_postvars['url']) && sm_fs_exists($_postvars['url']))
						$error=$lang['messages']['seo_url_exists'];
					elseif (sm_action('postadd') && !empty($_postvars['url']) && sm_fs_exists($_postvars['url']) && strcmp($_postvars['url'], sm_fs_url('index.php?m=content&d=view&cid='.intval($_getvars['id'])))!=0)
						$error=$lang['messages']['seo_url_exists'];
					if (empty($error))
						{
							$q=new TQuery($sm['t'].'content');
							$q->Add('id_category_c', intval($_postvars['id_category_c']));
							$q->Add('title_content', dbescape($_postvars['title_content']));
							if (intval(sm_settings('content_use_preview'))==1)
								$q->Add('preview_content', dbescape($_postvars['preview_content']));
							$q->Add('text_content', dbescape($_postvars['text_content']));
							$q->Add('type_content', intval($_postvars['type_content']));
							$q->Add('keywords_content', dbescape($_postvars['type_content']));
							$q->Add('description_content', dbescape($_postvars['description_content']));
							$q->Add('refuse_direct_show', intval($_postvars['refuse_direct_show']));
							if (sm_action('postadd'))
								{
									$cid=$q->Insert();
									sm_set_metadata('content', $cid, 'author_id', $sm['u']['id']);
									TQuery::ForTable($sm['t'].'content')
										->Add('priority_content', intval($cid))
										->Update('id_content', intval($cid));
								}
							else
								{
									$cid=intval($_getvars['id']);
									$q->Update('id_content', intval($cid));
								}
							sm_set_metadata('content', $cid, 'main_template', $_postvars['tplmain']);
							sm_set_metadata('content', $cid, 'content_template', $_postvars['tplcontent']);
							sm_set_metadata('content', $cid, 'seo_title', $_postvars['seo_title']);
							if (!empty($_postvars['url']))
								sm_fs_update($_postvars['title_content'], 'index.php?m=content&d=view&cid='.intval($cid), $_postvars['url']);
								//TODO remove url if empty
							if (sm_action('postadd'))
								sm_notify($lang['messages']['add_successful']);
							else
								sm_notify($lang['messages']['edit_successful']);
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								{
									if ($sm['u']['level'] < 3)
										sm_redirect('index.php?m=content&d=viewctg&ctgid='.intval($_postvars['id_category_c']));
									else
										sm_redirect('index.php?m=content&d=list&ctg='.intval($_postvars['id_category_c']));
								}
						}
					else
						sm_set_action(Array('postadd'=>'add', 'postedit'=>'edit'));
				}
			if (sm_action('add') && siman_is_allowed_to_add_content() || sm_action('edit') && siman_is_allowed_to_edit_content(intval($_getvars['id'])))
				{
					if ($sm['u']['level']>=intval(sm_settings('content_editor_level')))
						$categories = siman_load_ctgs_content(-1);
					elseif (!empty($sm['u']['groups']))
						$categories = siman_get_available_categories();
					$use_ext_editor=strcmp($_getvars['exteditor'], 'off')!=0;
					if (sm_action('add'))
						sm_title($lang['common']['add']);
					else
						sm_title($lang['common']['edit']);
					sm_use('ui.interface');
					sm_use('ui.form');
					if ($sm['u']['level']==3)
						{
							add_path_modules();
							add_path($lang['module_content_name'], "index.php?m=content&d=admin");
							add_path($lang['list_content'], "index.php?m=content&d=list");
						}
					else
						add_path_home();
					add_path_current();
					$ui = new TInterface();
					if (!empty($error))
						$ui->NotificationError($error);
					if (sm_action('add'))
						$f = new TForm('index.php?m='.sm_current_module().'&d=postadd');
					else
						{
							$content=TQuery::ForTable($sm['t'].'content')
								->AddWhere('id_content', intval($sm['g']['cid']))
								->Get();
							if (!empty($content['filename_content']))
								$content['url']=get_filename($content['filename_content']);
							$f = new TForm('index.php?m='.sm_current_module().'&d=postedit&id='.$content['id_content']);
						}
					$v=Array();
					$l=Array();
					for ($i = 0; $i < count($categories); $i++)
						{
							$v[]=$categories[$i]['id'];
							$l[]=$categories[$i]['title'];
						}
					$f->AddText('title_content', $lang['title'], true)
						->SetFocus();
					$f->AddSelectVL('id_category_c', $lang['common']['category'], $v, $l, true);
					if (intval(sm_settings('content_use_image'))==1)
						{
							$f->AddFile('userfile', $lang['common']['image']);
						}
					if ($use_ext_editor)
						$f->AddEditor('text_content', $lang['common']['text'], true);
					else
						$f->AddTextarea('text_content', $lang['common']['text'], true);
					$f->MergeColumns('text_content');
					if (intval(sm_settings('content_use_preview'))==1)
						{
							if ($use_ext_editor)
								$f->AddEditor('preview_content', $lang['common']['preview']);
							else
								$f->AddTextarea('preview_content', $lang['common']['preview']);
							$f->MergeColumns('preview_content');
						}
					if ($use_ext_editor)
						$f->AddHidden('type_content', 1);
					else
						$f->AddSelectVL('type_content', $lang['type_content'], Array(0, 1, 2), Array($lang['type_content_simple_text'], $lang['type_content_HTML'], $lang['type_content_simple_text'].' / Header: plain/text'));
					$f->Separator($lang['common']['seo']);
					$f->AddText('url', $lang['url'])
						->WithTooltip($lang['common']['leave_empty_for_default']);
					if (sm_action('edit'))
						$f->WithValue(sm_fs_url('index.php?m=content&d=view&cid='.intval($content['id_content']), true));
					$f->AddText('seo_title', $lang['common']['seo_title'])
						->WithTooltip($lang['common']['leave_empty_for_default'])
						->WithValue(sm_metadata('content', intval($_getvars["cid"]), 'seo_title'));
					$f->AddText('keywords_content', $lang['common']['seo_keywords']);
					$f->AddTextarea('description_content', $lang['common']['seo_description']);
					$f->Separator($lang['common']['additional_options']);
					$f->AddCheckbox('refuse_direct_show', $lang['module_content']['refuse_direct_show']);
					if (sm_action('edit'))
						$f->LoadValuesArray($content);
					$f->LoadValuesArray($_postvars);
					//TODO: Attachments
					//TODO: Select template
					$ui->Add($f);
					$ui->Output(true);
				}
			/*
			if (sm_action('add') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level'] < intval(sm_settings('content_editor_level')))
						$extsql = convert_groups_to_sql($sm['u']['groups'], 'groups_modify');
					else
						$extsql = '';
					$categories = siman_load_ctgs_content(-1, $extsql);
					if (count($categories) > 0)
						{
							sm_title($lang['add_content']);
							$m["module"] = 'content';
							add_path($lang['control_panel'], "index.php?m=admin");
							add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
							add_path($lang['module_content_name'], "index.php?m=content&d=admin");
							add_path($lang['list_content'], "index.php?m=content&d=list");
							if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
								{
									$special['ext_editor_on'] = 1;
								}
							$m['ctgidselected'] = intval($_getvars['ctg']);
							$m['images'] = load_file_list('./files/img/', 'jpg|gif|jpeg|png');
							if (count($sm['themeinfo']['alttpl']['main'])>0)
								{
									$m['alttpl']['main']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
									for ($i = 0; $i < count($sm['themeinfo']['alttpl']['main']); $i++)
										$m['alttpl']['main'][]=$sm['themeinfo']['alttpl']['main'][$i];
								}
							if (count($sm['themeinfo']['alttpl']['content'])>0)
								{
									$m['alttpl']['content']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
									for ($i = 0; $i < count($sm['themeinfo']['alttpl']['content']); $i++)
										$m['alttpl']['content'][]=$sm['themeinfo']['alttpl']['content'][$i];
								}
							sm_event('onaddcontent', array($m['selected_ctg']));
						}
				}

			if (sm_action('postadd') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level']<intval(sm_settings('content_editor_level')))
						$extsql = '('.convert_groups_to_sql($sm['u']['groups'], 'groups_modify').') AND id_category='.intval($_postvars["p_id_category_c"]);
					else
						$extsql = '';
					$m['ctgid'] = siman_load_ctgs_content(-1, $extsql);
					if (count($m['ctgid']) > 0)
						{
							sm_event('startpostaddcontent', array(0));
							$id_category_c = $_postvars["p_id_category_c"];
							$title_content = dbescape($_postvars["p_title_content"]);
							$preview_content = dbescape($_postvars["p_preview_content"]);
							$text_content = dbescape($_postvars["p_text_content"]);
							$type_content = $_postvars["p_type_content"];
							$keywords_content = dbescape($_postvars["p_keywords_content"]);
							$description_content = dbescape($_postvars["p_description_content"]);
							$filename = dbescape($_postvars["p_filename"]);
							$refuse_direct_show = intval($_postvars["p_refuse_direct_show"]);
							$sql = "INSERT INTO ".$tableprefix."content (id_category_c, title_content, preview_content, text_content, type_content, keywords_content, refuse_direct_show, description_content) VALUES ('$id_category_c', '$title_content', '$preview_content', '$text_content', '$type_content', '$keywords_content', '$refuse_direct_show', '$description_content')";
							$cid = insertsql($sql);
							sm_set_metadata('content', $cid, 'author_id', $sm['u']['id']);
							sm_set_metadata('content', $cid, 'main_template', $_postvars['tplmain']);
							sm_set_metadata('content', $cid, 'content_template', $_postvars['tplcontent']);
							sm_set_metadata('content', $cid, 'seo_title', $_postvars['seo_title']);
							if (!empty($filename))
								{
									$urlid = register_filesystem('index.php?m=content&d=view&cid='.$cid, $filename, $title_content);
									$sql = "UPDATE ".$tableprefix."content SET filename_content='$urlid' WHERE id_content=".$cid;
									$result = execsql($sql);
								}
							$sql = "UPDATE ".$tableprefix."content SET priority_content='$cid' WHERE id_content=".$cid;
							$result = execsql($sql);
							for ($i = 0; $i < $_settings['content_attachments_count']; $i++)
								{
									sm_upload_attachment('content', $cid, $_uplfilevars['attachment'.$i]);
								}
							if ($_settings['content_use_image'] == 1)
								{
									if ($_settings['image_generation_type'] == 'static' && file_exists($_uplfilevars['userfile']['tmp_name']))
										{
											include_once('includes/smcoreext.php');
											move_uploaded_file($_uplfilevars['userfile']['tmp_name'], 'files/temp/content'.$cid.'.jpg');
											sm_resizeimage(
												'files/temp/content'.$cid.'.jpg',
												'files/thumb/content'.$cid.'.jpg',
												$_settings['content_image_preview_width'],
												$_settings['content_image_preview_height'],
												0, 100, 1);
											sm_resizeimage(
												'files/temp/content'.$cid.'.jpg',
												'files/fullimg/content'.$cid.'.jpg',
												$_settings['content_image_fulltext_width'],
												$_settings['content_image_fulltext_height'],
												0, 100, 1);
											unlink('files/temp/content'.$cid.'.jpg');
										}
									else
										{
											siman_upload_image($cid, 'content');
										}
								}
							sm_notify($lang['add_content_successful']);
							if ($sm['u']['level'] < 3)
								sm_redirect('index.php?m=content&d=viewctg&ctgid='.$id_category_c);
							else
								sm_redirect('index.php?m=content&d=list&ctg='.$id_category_c);
							sm_event('postaddcontent', array($cid));
						}
				}
			*/
			/*
			if (sm_actionpost('postedit') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level']<intval(sm_settings('content_editor_level')))
						$extsql = '('.convert_groups_to_sql($sm['u']['groups'], 'groups_modify').') AND id_category='.intval($_postvars["p_id_category_c"]);
					else
						{
							$extsql = '';
							$canedit = 1;
						}
					$m['ctgid'] = siman_load_ctgs_content(-1, $extsql);
					if (count($m['ctgid']) > 0 && $canedit != 1)
						{
							$sql = "SELECT * FROM ".$tableprefix."content  LEFT JOIN ".$tableprefix."categories ON ".$tableprefix."content.id_category_c=".$tableprefix."categories.id_category  WHERE id_content='".intval($_getvars["cid"])."'";
							$sql .= " AND (".$extsql.')';
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$canedit = 1;
								}
						}
					if ($canedit == 1)
						{
							sm_event('startposteditcontent', array(intval($_getvars["cid"])));
							$id_category_c = $_postvars["p_id_category_c"];
							$title_content = dbescape($_postvars["p_title_content"]);
							$preview_content = dbescape($_postvars["p_preview_content"]);
							$text_content = dbescape($_postvars["p_text_content"]);
							$type_content = dbescape($_postvars["p_type_content"]);
							$keywords_content = dbescape($_postvars["p_keywords_content"]);
							$description_content = dbescape($_postvars["p_description_content"]);
							$filename = dbescape($_postvars["p_filename"]);
							$refuse_direct_show = intval($_postvars["p_refuse_direct_show"]);
							if ($_settings['content_use_preview'] == 1)
								$tmp_preview_sql = "preview_content='$preview_content',";
							$sql = "UPDATE ".$tableprefix."content SET id_category_c='".intval($id_category_c)."', title_content='$title_content', $tmp_preview_sql text_content='$text_content', type_content='$type_content', keywords_content = '$keywords_content', refuse_direct_show = '$refuse_direct_show', description_content='$description_content' WHERE id_content='".intval($_getvars["cid"])."'";
							$result = execsql($sql);
							if ($_settings['content_use_image'] == 1)
								{
									$id_content = intval($_getvars["cid"]);
									if ($_settings['image_generation_type'] == 'static' && file_exists($_uplfilevars['userfile']['tmp_name']))
										{
											include_once('includes/smcoreext.php');
											move_uploaded_file($_uplfilevars['userfile']['tmp_name'], 'files/temp/content'.$id_content.'.jpg');
											sm_resizeimage(
												'files/temp/content'.$id_content.'.jpg',
												'files/thumb/content'.$id_content.'.jpg',
												$_settings['content_image_preview_width'],
												$_settings['content_image_preview_height'],
												0, 100, 1);
											sm_resizeimage(
												'files/temp/content'.$id_content.'.jpg',
												'files/fullimg/content'.$id_content.'.jpg',
												$_settings['content_image_fulltext_width'],
												$_settings['content_image_fulltext_height'],
												0, 100, 1);
											unlink('files/temp/content'.$id_content.'.jpg');
										}
									else
										{
											siman_upload_image($id_content, 'content');
										}
								}
							$sql = "SELECT * FROM ".$tableprefix."content WHERE id_content='".intval($_getvars["cid"])."'";
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$fname = $row->filename_content;
								}
							if ($fname == 0 && !empty($filename))
								{
									$urlid = register_filesystem('index.php?m=content&d=view&cid='.$_getvars["cid"], $filename, $title_content);
									$sql = "UPDATE ".$tableprefix."content SET filename_content='$urlid' WHERE id_content=".intval($_getvars["cid"]);
									$result = execsql($sql);
								}
							else
								{
									if (empty($filename))
										{
											$sql = "UPDATE ".$tableprefix."content SET filename_content='0' WHERE id_content=".intval($_getvars["cid"]);
											$result = execsql($sql);
											delete_filesystem($fname);
										}
									else
										update_filesystem($fname, 'index.php?m=content&d=view&cid='.intval($_getvars["cid"]), $filename, $title_content);
								}
							$result = execsql($sql);
							sm_set_metadata('content', intval($_getvars["cid"]), 'main_template', $_postvars['tplmain']);
							sm_set_metadata('content', intval($_getvars["cid"]), 'content_template', $_postvars['tplcontent']);
							sm_set_metadata('content', intval($_getvars["cid"]), 'seo_title', $_postvars['seo_title']);
							for ($i = 0; $i < $_settings['content_attachments_count']; $i++)
								{
									sm_upload_attachment('content', intval($_getvars["cid"]), $_uplfilevars['attachment'.$i]);
								}
							sm_notify($lang['edit_content_successful']);
							if ($sm['u']['level'] < 3)
								sm_redirect('index.php?m=content&d=viewctg&ctgid='.$id_category_c);
							else
								sm_redirect('index.php?m=content&d=list&ctg='.$id_category_c);
							sm_event('posteditcontent', array(intval($_getvars["cid"])));
						}
				}    
			*/

			if (sm_action('edit') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level']<intval(sm_settings('content_editor_level')))
						$extsql = convert_groups_to_sql($sm['u']['groups'], 'groups_modify');
					else
						$extsql = '';
					$m['ctgid'] = siman_load_ctgs_content(-1, $extsql);
					if (count($m['ctgid']) > 0)
						{
							if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
								{
									$special['ext_editor_on'] = 1;
									require_once('ext/editors/'.$_settings['ext_editor'].'/siman_config.php');
								}
							$sql = "SELECT * FROM ".$tableprefix."content  LEFT JOIN ".$tableprefix."categories ON ".$tableprefix."content.id_category_c=".$tableprefix."categories.id_category  WHERE id_content='".intval($_getvars["cid"])."'";
							if (!empty($extsql))
								$sql .= " AND (".$extsql.')';
							$result = execsql($sql);
							$i = 0;
							while ($row = database_fetch_object($result))
								{
									$m["type_content"] = $row->type_content;
									if ($m["type_content"] != 1)
										$special['ext_editor_on'] = 0;
									$m["title_content"] = htmlescape($row->title_content);
									$m["keywords_content"] = htmlescape($row->keywords_content);
									$m["seo_title"] = sm_metadata('content', intval($_getvars["cid"]), 'seo_title');
									$m["description_content"] = htmlescape($row->description_content);
									$m["ctgidselected"] = $row->id_category_c;
									if ($special['ext_editor_on'] != 1)
										{
											$m["preview_content"] = htmlescape($row->preview_content);
											$m["text_content"] = htmlescape($row->text_content);
										}
									else
										{
											$m["preview_content"] = $row->preview_content;
											$m["text_content"] = $row->text_content;
										}
									if ($special['ext_editor_on'] == 1)
										{
											$m["text_content"] = siman_prepare_to_exteditor($m["text_content"]);
											$m["preview_content"] = siman_prepare_to_exteditor($m["preview_content"]);
										}
									$m["id_content"] = $row->id_content;
									if (!empty($row->filename_content))
										{
											$m['filesystem'] = get_filesystem($row->filename_content);
											$m["filename_content"] = $m['filesystem']['filename'];
										}
									$m['refuse_direct_show'] = $row->refuse_direct_show;
									$m['attachments'] = sm_get_attachments('content', $row->id_content);
									$i++;
								}
							sm_title($lang['edit_content']);
							add_path($lang['control_panel'], "index.php?m=admin");
							add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
							add_path($lang['module_content_name'], "index.php?m=content&d=admin");
							add_path($lang['list_content'], "index.php?m=content&d=list&ctg=".$modules[$modules_index]['ctgidselected']."");
							if ($i > 0)
								$m["module"] = 'content';
							$m['images'] = load_file_list('./files/img/', 'jpg|gif|jpeg|png');
							//$m["ctgid"]=siman_load_ctgs_content();
							if (count($sm['themeinfo']['alttpl']['main'])>0)
								{
									$m['alttpl']['main']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
									for ($i = 0; $i < count($sm['themeinfo']['alttpl']['main']); $i++)
										$m['alttpl']['main'][]=$sm['themeinfo']['alttpl']['main'][$i];
								}
							if (count($sm['themeinfo']['alttpl']['content'])>0)
								{
									$m['alttpl']['content']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
									for ($i = 0; $i < count($sm['themeinfo']['alttpl']['content']); $i++)
										$m['alttpl']['content'][]=$sm['themeinfo']['alttpl']['content'][$i];
								}
							$tmp=sm_load_metadata('content', intval($_getvars["cid"]));
							if (!isset($sm['p']['tplmain']))
								$sm['p']['tplmain']=$tmp['main_template'];
							if (!isset($sm['p']['tplcontent']))
								$sm['p']['tplcontent']=$tmp['content_template'];
							if (!empty($m["id_content"]))
								sm_event('oneditcontent', array($m["id_content"]));
						}
				}

			if (sm_action('delete') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level']<intval(sm_settings('content_editor_level')))
						$extsql = convert_groups_to_sql($sm['u']['groups'], 'groups_modify');
					else
						{
							$extsql = '';
							$candelete = 1;
						}
					$m['ctgid'] = siman_load_ctgs_content(-1, $extsql);
					if (count($m['ctgid']) > 0 && $candelete != 1)
						{
							$sql = "SELECT * FROM ".$tableprefix."content  LEFT JOIN ".$tableprefix."categories ON ".$tableprefix."content.id_category_c=".$tableprefix."categories.id_category  WHERE id_content='".intval($_getvars["cid"])."'";
							$sql .= " AND (".$extsql.')';
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$candelete = 1;
								}
						}
					if ($candelete == 1)
						{
							$m["module"] = 'content';
							$_msgbox['mode'] = 'yesno';
							$_msgbox['title'] = $lang['delete_content'];
							$_msgbox['msg'] = $lang['module_content']['really_want_delete_text'];
							$_msgbox['yes'] = 'index.php?m=content&d=postdelete&cid='.$_getvars["cid"].'&ctg='.$_getvars['ctg'];
							if ($sm['u']['level'] < 3)
								$_msgbox['no'] = 'index.php?m=content&d=viewctg&ctgid='.$_getvars['ctg'];
							else
								$_msgbox['no'] = 'index.php?m=content&d=list&ctg='.$_getvars['ctg'];
						}
				}
			if (sm_action('postdelete') && ($sm['u']['level']>=intval(sm_settings('content_editor_level')) || !empty($sm['u']['groups'])))
				{
					if ($sm['u']['level']<intval(sm_settings('content_editor_level')))
						$extsql = convert_groups_to_sql($sm['u']['groups'], 'groups_modify');
					else
						{
							$extsql = '';
							$candelete = 1;
						}
					$m['ctgid'] = siman_load_ctgs_content(-1, $extsql);
					if (count($m['ctgid']) > 0 && $candelete != 1)
						{
							$sql = "SELECT * FROM ".$tableprefix."content  LEFT JOIN ".$tableprefix."categories ON ".$tableprefix."content.id_category_c=".$tableprefix."categories.id_category  WHERE id_content='".intval($_getvars["cid"])."'";
							$sql .= " AND (".$extsql.')';
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$candelete = 1;
								}
						}
					if ($candelete == 1)
						{
							sm_title($lang['delete_content']);
							$m["module"] = 'content';
							$fname=0;
							$sql = "SELECT * FROM ".$tableprefix."content WHERE id_content=".intval($_getvars["cid"]);
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$fname = $row->filename_content;
								}
							$sql = "DELETE FROM ".$tableprefix."content WHERE id_content=".intval($_getvars["cid"])." AND id_content<>1";
							$result = execsql($sql);
							sm_extcore();
							sm_saferemove('index.php?m=content&d=view&cid='.intval($_getvars["cid"]));
							if ($fname != 0)
								{
									delete_filesystem($fname);
								}
							sm_delete_attachments('content', intval($_getvars["cid"]));
							if (file_exists('files/thumb/content'.intval($_getvars["cid"]).'.jpg'))
								unlink('files/thumb/content'.intval($_getvars["cid"]).'.jpg');
							if (file_exists('files/fullimg/content'.intval($_getvars["cid"]).'.jpg'))
								unlink('files/fullimg/content'.intval($_getvars["cid"]).'.jpg');
							if (file_exists('files/img/content'.intval($_getvars["cid"]).'.jpg'))
								unlink('files/img/content'.intval($_getvars["cid"]).'.jpg');
							sm_notify($lang['messages']['delete_successful']);
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								{
									if ($sm['u']['level'] < 3)
										sm_redirect('index.php?m=content&d=viewctg&ctgid='.intval($_getvars['ctg']));
									else
										sm_redirect('index.php?m=content&d=list&ctg='.intval($_getvars['ctg']));
								}
							sm_event('postdeletecontent', array(intval($_getvars["cid"])));
						}
				}

			if ($sm['u']['level'] > 2)
				include('modules/inc/adminpart/content.php');
		}

?>