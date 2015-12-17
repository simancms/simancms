<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.10
	//#revision 2015-10-29
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
					if (sm_action('postadd'))
						sm_event('beforepostaddcontent');
					else
						{
							$cid=intval($_getvars['id']);
							sm_event('beforeposteditcontent', array($cid));
						}
					if (empty($sm['p']['title_content']) || empty($sm['p']['id_category_c']))
						$error=$lang['messages']['fill_required_fields'];
					elseif (sm_action('postadd') && !empty($sm['p']['url']) && sm_fs_exists($sm['p']['url']))
						$error=$lang['messages']['seo_url_exists'];
					elseif (sm_action('postadd') && !empty($sm['p']['url']) && sm_fs_exists($sm['p']['url']) && strcmp($sm['p']['url'], sm_fs_url('index.php?m=content&d=view&cid='.intval($_getvars['id'])))!=0)
						$error=$lang['messages']['seo_url_exists'];
					if (empty($error))
						{
							if (sm_action('postadd'))
								sm_event('startpostaddcontent');
							else
								sm_event('startposteditcontent', array($cid));
							$q=new TQuery($sm['t'].'content');
							$q->Add('id_category_c', intval($sm['p']['id_category_c']));
							$q->Add('title_content', dbescape($sm['p']['title_content']));
							if (intval(sm_settings('content_use_preview'))==1)
								$q->Add('preview_content', dbescape($sm['p']['preview_content']));
							$q->Add('text_content', dbescape($sm['p']['text_content']));
							$q->Add('type_content', intval($sm['p']['type_content']));
							$q->Add('keywords_content', dbescape($sm['p']['keywords_content']));
							$q->Add('description_content', dbescape($sm['p']['description_content']));
							$q->Add('refuse_direct_show', intval($sm['p']['refuse_direct_show']));
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
									$q->Update('id_content', intval($cid));
								}
							sm_set_metadata('content', $cid, 'main_template', $sm['p']['tplmain']);
							sm_set_metadata('content', $cid, 'content_template', $sm['p']['tplcontent']);
							sm_set_metadata('content', $cid, 'seo_title', $sm['p']['seo_title']);
							sm_set_metadata('content', $cid, 'main_template', $sm['p']['tplmain']);
							sm_set_metadata('content', $cid, 'content_template', $sm['p']['tplcontent']);
							if (sm_action('postedit'))
								{
									$attachments=sm_get_attachments('content', $cid);
									for ($i = 0; $i<count($attachments); $i++)
										{
											if (!empty($sm['p']['delete_attachment_'.$attachments[$i]['id']]))
												{
													sm_delete_attachment(intval($attachments[$i]['id']));
													sm_event('postdeleteattachment', array(intval($attachments[$i]['id'])));
												}
										}
								}
							for ($i = 0; $i < intval(sm_settings('content_attachments_count')); $i++)
								{
									sm_upload_attachment('content', $cid, $_uplfilevars['attachment'.$i]);
								}
							if (!empty($sm['p']['url']))
								sm_fs_update($sm['p']['title_content'], 'index.php?m=content&d=view&cid='.intval($cid), $sm['p']['url']);
								//TODO remove url if empty
							if (sm_action('postadd'))
								sm_notify($lang['messages']['add_successful']);
							else
								sm_notify($lang['messages']['edit_successful']);
							if (sm_action('postadd'))
								sm_event('postaddcontent', array($cid));
							else
								sm_event('posteditcontent', array($cid));
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								{
									if ($sm['u']['level'] < 3)
										sm_redirect('index.php?m=content&d=viewctg&ctgid='.intval($sm['p']['id_category_c']));
									else
										sm_redirect('index.php?m=content&d=list&ctg='.intval($sm['p']['id_category_c']));
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
						{
							sm_event('onaddcontent');
							sm_title($lang['common']['add']);
						}
					else
						{
							$content=TQuery::ForTable($sm['t'].'content')
								->AddWhere('id_content', intval($sm['g']['cid']))
								->Get();
							sm_event('oneditcontent', array($content['id_content']));
							sm_title($lang['common']['edit']);
						}
					sm_add_cssfile('contentaddedit.css');
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_use('ui.buttons');
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
					$b=new TButtons();
					if ($_getvars['exteditor']!='off')
						$b->AddMessageBox('exteditoroff', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor'=>'off')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
					else
						$b->AddMessageBox('exteditoron', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor'=>'')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
					if (!empty($error))
						$ui->NotificationError($error);
					if (sm_action('add'))
						sm_event('beforecontentaddform');
					else
						sm_event('beforecontenteditform', Array($cid));
					if (sm_action('add'))
						{
							$f = new TForm('index.php?m='.sm_current_module().'&d=postadd');
							sm_event('startcontentaddform');
						}
					else
						{
							if (!empty($content['filename_content']))
								$content['url']=get_filename($content['filename_content']);
							$f = new TForm('index.php?m='.sm_current_module().'&d=postedit&id='.$content['id_content']);
							sm_event('startcontenteditform', Array($cid));
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
					if (!empty($sm['contenteditor']['controlbuttonsclass']))
						$b->ApplyClassnameForAll($sm['contenteditor']['controlbuttonsclass']);
					$f->InsertButtons($b);
					if ($use_ext_editor)
						$f->AddEditor('text_content', $lang['common']['text'], true);
					else
						$f->AddTextarea('text_content', $lang['common']['text'], true);
					$f->MergeColumns('text_content');
					if (intval(sm_settings('content_use_preview'))==1)
						{
							if ($use_ext_editor)
								{
									$f->AddEditor('preview_content', $lang['common']['preview']);
									$f->SetFieldAttribute('preview_content', 'style', ';');//TinyMCE temporary fix
								}
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
						->WithTooltip($lang['common']['leave_empty_for_default']);
					$f->AddText('keywords_content', $lang['common']['seo_keywords']);
					$f->AddTextarea('description_content', $lang['common']['seo_description']);
					$f->Separator($lang['common']['additional_options']);
					$f->AddCheckbox('refuse_direct_show', $lang['module_content']['refuse_direct_show'])
						->LabelAfterControl();
					if (count($sm['themeinfo']['alttpl']['main'])>0)
						{
							$v=Array('');
							$l=Array($lang['common']['default']);
							for ($i = 0; $i < count($sm['themeinfo']['alttpl']['main']); $i++)
								{
									$v[]=$sm['themeinfo']['alttpl']['main'][$i]['tpl'];
									$l[]=$sm['themeinfo']['alttpl']['main'][$i]['name'];
								}
							$f->AddSelectVL('tplmain', $lang['common']['template'].' ('.$lang['common']['site'].')', $v, $l);
						}
					if (count($sm['themeinfo']['alttpl']['content'])>0)
						{
							$v=Array('');
							$l=Array($lang['common']['default']);
							for ($i = 0; $i < count($sm['themeinfo']['alttpl']['content']); $i++)
								{
									$v[]=$sm['themeinfo']['alttpl']['content'][$i]['tpl'];
									$l[]=$sm['themeinfo']['alttpl']['content'][$i]['name'];
								}
							$f->AddSelectVL('tplcontent', $lang['common']['template'].' ('.$lang['common']['page'].')', $v, $l);
						}
					if (intval(sm_settings('content_attachments_count')))
						{
							$f->Separator($lang['common']['attachments']);
							if (sm_action('edit'))
								$attachments=sm_get_attachments('content', $content['id_content']);
							else
								$attachments=Array();
							for ($i = 0; $i<intval(sm_settings('content_attachments_count')); $i++)
								{
									if ($i<count($attachments))
										$f->AddCheckbox('delete_attachment_'.$attachments[$i]['id'], $lang['number_short'].($i+1).'. '.$lang['delete'].' - '.$attachments[$i]['filename'])
											->LabelAfterControl();
									else
										$f->AddFile('attachment'.$i, $lang['number_short'].($i+1));
								}
						}
					if (sm_action('add'))
						sm_event('endcontentaddform');
					else
						sm_event('endcontenteditform', Array($cid));
					if (sm_action('edit'))
						{
							$f->LoadValuesArray($content);
							$tmp=sm_load_metadata('content', intval($content['id_content']));
							$f->SetValue('seo_title', $tmp['seo_title']);
							$f->SetValue('tplmain', $tmp['main_template']);
							$f->SetValue('tplcontent', $tmp['content_template']);
						}
					$f->LoadValuesArray($_postvars);
					$ui->Add($f);
					if (sm_action('add'))
						sm_event('aftercontentaddform');
					else
						sm_event('aftercontenteditform', Array($cid));
					$ui->Output(true);
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