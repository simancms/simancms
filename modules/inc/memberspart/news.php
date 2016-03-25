<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.10
	//#revision 2016-02-04
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (sm_actionpost('postadd') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$m['post'] = $_postvars;
			if ($userinfo['level'] < intval(sm_settings('news_editor_level')))
				$extsql = '('.convert_groups_to_sql($userinfo['groups'], 'groups_modify').') AND id_category='.intval($_postvars['p_id_category_n']);
			else
				$extsql = '';
			$sql = "SELECT * FROM ".$tableprefix."categories_news";
			if (!empty($extsql))
				$sql .= ' WHERE '.$extsql;
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_assoc($result))
				{
					$m['ctgid'][$i][0] = $row['id_category'];
					$m['ctgid'][$i][1] = $row['title_category'];
					$i++;
				}
			if ($i > 0)
				{
					if (!checkdate($_postvars['p_date_month'], $_postvars['p_date_day'], $_postvars['p_date_year']))
						{
							$m['error_message'] = $lang['news_wrong_date_message'];
							$m['mode'] = 'add';
							$m['ctgidselected'] = $_postvars['p_id_category_n'];
							$m['date_news'] = time();
							$m['date'] = getdate($m['date_news']);
							$m['title_news'] = $_postvars['p_title_news'];
							$m['preview_news'] = $_postvars['p_preview_news'];
							$m['text_news'] = $_postvars['p_text_news'];
							$m['type_news'] = $_postvars['p_type_news'];
							$m['filename_news'] = $_postvars['p_filename'];
							$m['keywords_news'] = $_postvars['keywords_news'];
							$m['description_news'] = $_postvars['description_news'];
						}
					else
						{
							sm_event('startpostaddnews', array(0));
							$m["module"] = 'news';
							sm_title($lang['add_news']);
							$id_category_n = $_postvars["p_id_category_n"];
							$preview_news = dbescape($_postvars['p_preview_news']);
							$text_news = dbescape($_postvars["p_text_news"]);
							$title_news = dbescape($_postvars['p_title_news']);
							$m['date'] = getdate(time());
							$type_news = $_postvars["p_type_news"];
							$filename = dbescape($_postvars["p_filename"]);
							$keywords_news = dbescape($_postvars["keywords_news"]);
							$description_news = dbescape($_postvars["description_news"]);
							$img_copyright_news = dbescape($_postvars["img_copyright_news"]);
							if (!sm_empty_settings('news_use_time'))
								{
									$m['date']['hours'] = $_postvars['p_time_hours'];
									$m['date']['minutes'] = $_postvars['p_time_minutes'];
								}
							$date_news = mktime($m['date']['hours'], $m['date']['minutes'], $m['date']['seconds'], $_postvars['p_date_month'], $_postvars['p_date_day'], $_postvars['p_date_year']);
							$sql = "INSERT INTO ".$tableprefix."news (id_category_n, date_news, title_news, preview_news, text_news, type_news, keywords_news, description_news, id_author_news, img_copyright_news) VALUES ('$id_category_n', '$date_news', '$title_news', '$preview_news', '$text_news', '$type_news', '$keywords_news', '$description_news', '".intval($userinfo['id'])."', '$img_copyright_news')";
							$id_news = insertsql($sql);
							sm_set_metadata('news', $id_news, 'author_id', $sm['u']['id']);
							sm_set_metadata('news', $id_news, 'time_created', time());
							sm_set_metadata('news', $id_news, 'last_updated_time', time());
							sm_set_metadata('news', $id_news, 'news_template', $_postvars['tplnews']);
							sm_set_metadata('news', $id_news, 'seo_title', $_postvars['seo_title']);
							if (intval(sm_settings('news_use_image')) == 1)
								{
									if ($_settings['image_generation_type'] == 'static' && file_exists($_uplfilevars['userfile']['tmp_name']))
										{
											include_once('includes/smcoreext.php');
											move_uploaded_file($_uplfilevars['userfile']['tmp_name'], 'files/temp/news'.$id_news.'.jpg');
											sm_resizeimage(
												'files/temp/news'.$id_news.'.jpg',
												'files/thumb/news'.$id_news.'.jpg',
												$_settings['news_image_preview_width'],
												$_settings['news_image_preview_height'],
												0, 100, 1);
											sm_resizeimage(
												'files/temp/news'.$id_news.'.jpg',
												'files/fullimg/news'.$id_news.'.jpg',
												$_settings['news_image_fulltext_width'],
												$_settings['news_image_fulltext_height'],
												0, 100, 1);
											unlink('files/temp/news'.$id_news.'.jpg');
										}
									else
										{
											siman_upload_image($id_news, 'news');
										}
								}
							if (!empty($filename))
								{
									$nid = $id_news;
									$title_news2 = (empty($title_news)) ? $_postvars['p_date_day'].'.'.$_postvars['p_date_month'].'.'.$_postvars['p_date_year'] : $title_news;
									$urlid = register_filesystem('index.php?m=news&d=view&nid='.$nid, $filename, $title_news2);
									$sql = "UPDATE ".$tableprefix."news SET filename_news='$urlid' WHERE id_news=".$nid;
									$result = execsql($sql);
								}
							for ($i = 0; $i < sm_settings('news_attachments_count'); $i++)
								{
									sm_upload_attachment('news', $id_news, $_uplfilevars['attachment'.$i]);
								}
							if ($userinfo['level'] == 3)
								sm_redirect('index.php?m=news&d=list&ctg='.$id_category_n);
							else
								sm_redirect('index.php?m=news&d=listnews&ctg='.$id_category_n);
							sm_notify($lang['add_news_successful']);
							sm_event('postaddnews', array($id_news));
						}
				}
		}

	/*
	if (sm_action('add') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			if ($userinfo['level'] < intval(sm_settings('news_editor_level')))
				$extsql = convert_groups_to_sql($userinfo['groups'], 'groups_modify');
			else
				$extsql = '';
			$sql = "SELECT * FROM ".$tableprefix."categories_news";
			if (!empty($extsql))
				$sql .= ' WHERE '.$extsql;
			$m["ctgidselected"] = intval($_getvars['ctg']);
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_object($result))
				{
					$m["ctgid"][$i][0] = $row->id_category;
					$m["ctgid"][$i][1] = $row->title_category;
					$i++;
				}
			if ($i > 0)
				{
					$m["module"] = 'news';
					if (!sm_empty_settings('ext_editor') && $_getvars['exteditor'] != 'off')
						{
							$special['ext_editor_on'] = 1;
						}
					else
						{
							$m['type_news'] = $_settings['default_news_text_style'];
						}
					sm_title($lang['add_news']);
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
					add_path($lang['module_news']['list_news'], "index.php?m=news&d=list");
					$m['date'] = getdate(time());
					$m['images'] = load_file_list('./files/img/', 'jpg|gif|jpeg|png');
					if (count($sm['themeinfo']['alttpl']['news'])>0)
						{
							$m['alttpl']['news']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
							for ($i = 0; $i < count($sm['themeinfo']['alttpl']['news']); $i++)
								$m['alttpl']['news'][]=$sm['themeinfo']['alttpl']['news'][$i];
						}
					sm_setfocus('title_news');
					sm_event('onaddnews', array($m['date']));
				}
		}
    */

	if (sm_action('delete') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$candelete = 0;
			if ($userinfo['level']>=intval(sm_settings('news_editor_level')))
				{
					$candelete = 1;
				}
			elseif (!empty($userinfo['groups']))
				{
					$extsql = convert_groups_to_sql($userinfo['groups'], 'groups_modify');
					$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."categories_news.* FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE ".$tableprefix."news.id_category_n=".$tableprefix."categories_news.id_category AND id_news='".intval($_getvars["nid"])."'";
					$sql .= ' AND '.$extsql;
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$candelete = 1;
						}
				}
			if ($candelete == 1)
				{
					$m["module"] = 'news';
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['delete_news'];
					$_msgbox['msg'] = $lang['really_want_delete_news'];
					$_msgbox['yes'] = 'index.php?m=news&d=postdelete&nid='.$_getvars["nid"].'&ctg='.$_getvars['ctg'];
					if ($userinfo['level'] == 3)
						$_msgbox['no'] = 'index.php?m=news&d=list';
					else
						$_msgbox['no'] = 'index.php?m=news&d=view&nid='.$_getvars["nid"];
				}
		}

	if (sm_action('postdelete') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$candelete = 0;
			if ($userinfo['level']>=intval(sm_settings('news_editor_level')))
				{
					$candelete = 1;
				}
			elseif (!empty($userinfo['groups']))
				{
					$extsql = convert_groups_to_sql($userinfo['groups'], 'groups_modify');
					$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."categories_news.* FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE ".$tableprefix."news.id_category_n=".$tableprefix."categories_news.id_category AND id_news='".intval($_getvars["nid"])."'";
					$sql .= ' AND '.$extsql;
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$candelete = 1;
						}
				}
			if ($candelete == 1)
				{
					sm_title($lang['delete_news']);
					$m["module"] = 'news';
					$id_news = intval($_getvars["nid"]);
					$sql = "SELECT * FROM ".$tableprefix."news WHERE id_news=".intval($id_news);
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_news;
						}
					$sql = "DELETE FROM ".$tableprefix."news WHERE id_news=".intval($id_news);
					$result = execsql($sql);
					sm_extcore();
					sm_saferemove('index.php?m=news&d=view&nid='.$id_news);
					if ($fname != 0)
						{
							delete_filesystem($fname);
						}
					sm_delete_attachments('news', intval($id_news));
					if (file_exists('files/thumb/news'.$id_news.'.jpg'))
						unlink('files/thumb/news'.$id_news.'.jpg');
					if (file_exists('files/fullimg/news'.$id_news.'.jpg'))
						unlink('files/fullimg/news'.$id_news.'.jpg');
					if (file_exists('files/img/news'.$id_news.'.jpg'))
						unlink('files/img/news'.$id_news.'.jpg');
					sm_notify($lang['delete_news_successful']);
					sm_event('onnewsdeleted', array($id_news));
					if ($userinfo['level'] == 3)
						sm_redirect('index.php?m=news&d=list&ctg='.intval($_getvars['ctg']));
					else
						sm_redirect('index.php?m=news&d=listnews&ctg='.intval($_getvars['ctg']));
				}
		}


	if (sm_actionpost('postedit') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$canedit = 0;
			if ($userinfo['level']>=intval(sm_settings('news_editor_level')))
				{
					$canedit = 1;
				}
			elseif (!empty($userinfo['groups']))
				{
					$extsql = convert_groups_to_sql($userinfo['groups'], 'groups_modify');
					$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."categories_news.* FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE ".$tableprefix."news.id_category_n=".$tableprefix."categories_news.id_category AND id_news='".intval($_postvars["p_id_category_n"])."'";
					$sql .= ' AND '.$extsql;
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$canedit = 1;
						}
				}
			if ($canedit == 1)
				{
					if (!checkdate($_postvars['p_date_month'], $_postvars['p_date_day'], $_postvars['p_date_year']))
						{
							$m['error_message'] = $lang['news_wrong_date_message'];
							$m['mode'] = 'edit';
							$m['ctgidselected'] = $_postvars['p_id_category_n'];
							$m['date_news'] = $_postvars['p_date_prev'];
							$m['date'] = getdate($m['date_news']);
							$m['title_news'] = $_postvars['p_title_news'];
							$m['preview_news'] = $_postvars['p_preview_news'];
							$m['text_news'] = $_postvars['p_text_news'];
							$m['type_news'] = $_postvars['p_type_news'];
							$m["id_news"] = $_getvars["nid"];
							$m['filename_news'] = $_postvars['p_filename'];
							$m['keywords_news'] = $_postvars['keywords_news'];
							$m['description_news'] = $_postvars['description_news'];
							$m['seo_title'] = $_postvars['seo_title'];
						}
					else
						{
							$id_news = intval($_getvars["nid"]);
							sm_event('startposteditnews', array($id_news));
							sm_title($lang['edit_news']);
							$m["module"] = 'news';
							$id_category_n = $_postvars["p_id_category_n"];
							$m['date'] = getdate($_postvars["p_date_prev"]);
							if ($_settings['news_use_time'])
								{
									$m['date']['hours'] = $_postvars['p_time_hours'];
									$m['date']['minutes'] = $_postvars['p_time_minutes'];
								}
							$date_news = mktime($m['date']['hours'], $m['date']['minutes'], $m['date']['seconds'], $_postvars['p_date_month'], $_postvars['p_date_day'], $_postvars['p_date_year']);
							$title_news = dbescape($_postvars['p_title_news']);
							$preview_news = dbescape($_postvars['p_preview_news']);
							$text_news = dbescape($_postvars["p_text_news"]);
							$type_news = dbescape($_postvars["p_type_news"]);
							$filename = dbescape($_postvars["p_filename"]);
							$keywords_news = dbescape($_postvars["keywords_news"]);
							$description_news = dbescape($_postvars["description_news"]);
							$sql = "UPDATE ".$tableprefix."news SET id_category_n='$id_category_n', title_news='$title_news', preview_news='$preview_news', text_news='$text_news', type_news='$type_news', date_news='$date_news', keywords_news='$keywords_news', description_news='$description_news' WHERE id_news='".$id_news."'";
							$result = execsql($sql);
							sm_set_metadata('news', $id_news, 'seo_title', $_postvars['seo_title']);
							sm_set_metadata('news', $id_news, 'last_updated_time', time());
							if ($_settings['news_use_image'] == 1)
								{
									if ($_settings['image_generation_type'] == 'static' && file_exists($_uplfilevars['userfile']['tmp_name']))
										{
											include_once('includes/smcoreext.php');
											move_uploaded_file($_uplfilevars['userfile']['tmp_name'], 'files/temp/news'.$id_news.'.jpg');
											sm_resizeimage(
												'files/temp/news'.$id_news.'.jpg',
												'files/thumb/news'.$id_news.'.jpg',
												$_settings['news_image_preview_width'],
												$_settings['news_image_preview_height'],
												0, 100, 1);
											sm_resizeimage(
												'files/temp/news'.$id_news.'.jpg',
												'files/fullimg/news'.$id_news.'.jpg',
												$_settings['news_image_fulltext_width'],
												$_settings['news_image_fulltext_height'],
												0, 100, 1);
											unlink('files/temp/news'.$id_news.'.jpg');
										}
									else
										{
											siman_upload_image($id_news, 'news');
										}
								}
							sm_set_metadata('news', $id_news, 'news_template', $_postvars['tplnews']);
							$title_news2 = (empty($title_news)) ? $_postvars['p_date_day'].'.'.$_postvars['p_date_month'].'.'.$_postvars['p_date_year'] : $title_news;
							$sql = "SELECT * FROM ".$tableprefix."news WHERE id_news='".$id_news."'";
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$fname = $row->filename_news;
								}
							if ($fname == 0 && !empty($filename))
								{
									$urlid = register_filesystem('index.php?m=news&d=view&nid='.$id_news, $filename, $title_news2);
									$sql = "UPDATE ".$tableprefix."news SET filename_news='$urlid' WHERE id_news=".$id_news;
									$result = execsql($sql);
								}
							else
								{
									if (empty($filename))
										{
											$sql = "UPDATE ".$tableprefix."news SET filename_news='0' WHERE id_news=".$id_news;
											$result = execsql($sql);
											delete_filesystem($fname);
										}
									else
										update_filesystem($fname, 'index.php?m=news&d=view&nid='.$id_news, $filename, $title_news2);
								}
							for ($i = 0; $i < $_settings['news_attachments_count']; $i++)
								{
									sm_upload_attachment('news', $id_news, $_uplfilevars['attachment'.$i]);
								}
							if ($userinfo['level'] == 3)
								sm_redirect('index.php?m=news&d=list&ctg='.$id_category_n);
							else
								sm_redirect('index.php?m=news&d=listnews&ctg='.$id_category_n);
							sm_notify($lang['edit_news_successful']);
							sm_event('posteditnews', array($id_news));
						}
				}
		}


	if (sm_action('edit') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$canedit = 0;
			if ($userinfo['level']>=intval(sm_settings('news_editor_level')))
				{
					$canedit = 1;
				}
			elseif (!empty($userinfo['groups']))
				{
					$extsql = convert_groups_to_sql($userinfo['groups'], 'groups_modify');
					$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."categories_news.* FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE ".$tableprefix."news.id_category_n=".$tableprefix."categories_news.id_category AND id_news='".intval($_getvars["nid"])."'";
					$sql .= ' AND '.$extsql;
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$canedit = 1;
						}
				}
			if ($canedit == 1)
				{
					if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
						{
							$special['ext_editor_on'] = 1;
							require_once('ext/editors/'.$_settings['ext_editor'].'/siman_config.php');
						}
					sm_title($lang['edit_news']);
					$m["module"] = 'news';
					$sql = "SELECT * FROM ".$tableprefix."categories_news";
					if (!empty($extsql))
						$sql .= ' WHERE '.$extsql;
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m["ctgid"][$i][0] = $row->id_category;
							$m["ctgid"][$i][1] = $row->title_category;
							$i++;
						}
					$sql = "SELECT * FROM ".$tableprefix."news WHERE id_news='".intval($_getvars["nid"])."' LIMIT 1";
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_array($result))
						{
							$m["post"] = $row;
							$m["type_news"] = $row['type_news'];
							if ($m["type_news"] != 1)
								$special['ext_editor_on'] = 0;
							$m["ctgidselected"] = $row['id_category_n'];
							$m["date_news"] = $row['date_news'];
							$m['date'] = getdate($m['date_news']);
							$m["title_news"] = htmlescape($row['title_news']);
							$m["preview_news"] = $row['preview_news'];
							$m["text_news"] = $row['text_news'];
							$m["filename_news"] = get_filename($row['filename_news']);
							$m["seo_title"] = sm_metadata('news', intval($_getvars["nid"]), 'seo_title');
							$m["keywords_news"] = $row['keywords_news'];
							$m["description_news"] = $row['description_news'];
							if ($special['ext_editor_on'] == 1)
								{
									$m["text_news"] = siman_prepare_to_exteditor($m["text_news"]);
									$m["preview_news"] = siman_prepare_to_exteditor($m["preview_news"]);
								}
							$m["id_news"] = $row['id_news'];
							$m['attachments'] = sm_get_attachments('news', $row['id_news']);
							$i++;
						}
					if (!empty($m["id_news"]))
						{
							if (count($sm['themeinfo']['alttpl']['news'])>0)
								{
									$m['alttpl']['news']=Array(Array('tpl'=>'', 'name'=>$lang['common']['default']));
									for ($i = 0; $i < count($sm['themeinfo']['alttpl']['news']); $i++)
										$m['alttpl']['news'][]=$sm['themeinfo']['alttpl']['news'][$i];
								}
							$tmp=sm_load_metadata('news', $m["id_news"]);
							if (!isset($sm['p']['tplnews']))
								$sm['p']['tplnews']=$tmp['news_template'];
							sm_event('oneditnews', array($m["id_news"]));
						}
				}
			$m['images'] = load_file_list('./files/img/', 'jpg|gif|jpeg|png');
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
			add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
			add_path($lang['module_news']['list_news'], "index.php?m=news&d=list&ctg=".$m['ctgidselected']."");
		}
	
	if (sm_action('add') && ($userinfo['level']>=intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
		{
			$use_ext_editor=strcmp($_getvars['exteditor'], 'off')!=0;
			$qctgs=new TQuery($sm['t'].'categories_news');
			if ($userinfo['level'] < intval(sm_settings('news_editor_level')))
				$qctgs->Add('('.convert_groups_to_sql($userinfo['groups'], 'groups_modify').')');
			$qctgs->OrderBy('title_category');
			$qctgs->Select();
			if ($qctgs > 0)
				{
					if (sm_action('add'))
						{
							sm_event('onaddnews');
							sm_title($lang['news'].' - '.$lang['common']['add']);
						}
					else
						{
							$item=TQuery::ForTable($sm['t'].'news')
								->AddWhere('id_news', intval($sm['g']['nid']))
								->Get();
							sm_event('oneditnews', array($item['id_news']));
							sm_title($lang['news'].' - '.$lang['common']['edit']);
						}
					sm_add_cssfile('mediainsert.css');
					sm_add_cssfile('newsaddedit.css');
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_use('ui.buttons');
					sm_use('ui.modal');
					if ($sm['u']['level']==3)
						{
							add_path_modules();
							add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
							add_path($lang['module_news']['list_news'], "index.php?m=news&d=list");
						}
					else
						add_path_home();
					add_path_current();
					$ui = new TInterface();
					$b=new TButtons();
					if ($_getvars['exteditor']!='off')
						{
							$b->AddMessageBox('exteditoroff', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor'=>'off')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
							$modal=new TModalHelper();
							$modal->SetAJAXSource('index.php?m=media&d=editorinsert&theonepage=1');
							$b->AddButton('insertimgmodal', $lang['add_image'])
								->OnClick($modal->GetJSCode());
						}
					else
						$b->AddMessageBox('exteditoron', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor'=>'')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
					if (!empty($error))
						$ui->NotificationError($error);
					if (sm_action('add'))
						sm_event('beforenewsaddform');
					else
						sm_event('beforenewseditform', Array($cid));
					if (sm_action('add'))
						{
							$f = new TForm('index.php?m='.sm_current_module().'&d=postadd&returnto='.urlencode($_getvars['returnto']));
							sm_event('startnewsaddform');
						}
					else
						{
							if (!empty($item['filename_news']))
								$item['url']=get_filename($item['filename_news']);
							$f = new TForm('index.php?m='.sm_current_module().'&d=postedit&id='.$item['id_news'].'&returnto='.urlencode($_getvars['returnto']));
							sm_event('startnewseditform', Array($cid));
						}
					$f->AddText('title_news', $lang['common']['title'])
						->SetFocus();
					$f->AddSelectVL('id_category', $lang['common']['category'], $qctgs->ColumnValues('id_category'), $qctgs->ColumnValues('title_category'));
					if (intval(sm_settings('news_use_image'))==1)
						{
							$f->AddFile('userfile', $lang['common']['image']);
							$f->AddText('img_copyright_news', $lang['common']['copyright'].' ('.$lang['common']['image'].')');
						}
					//-- Date and time begin -------
					$years=Array();
					for ($i = 2006; $i<=intval(date('Y')+10); $i++)
						$years[]=$i;
					$days=Array();
					for ($i = 1; $i<=31; $i++)
						$days[]=$i;
					$months_v=Array();
					$months_l=Array();
					for ($i = 1; $i<=12; $i++)
						{
							$months_v[]=$i;
							$months_l[]=$lang['month_'.$i];
						}
					$f->AddSelect('date_day', $lang['common']['date'], $days);
					$f->HideEncloser();
					$f->AddSelectVL('date_month', $lang['common']['date'], $months_v, $months_l);
					$f->HideDefinition();
					$f->HideEncloser();
					$f->AddSelect('date_year', $lang['common']['date'], $years);
					$f->HideDefinition();
					if (intval(sm_settings('news_use_time'))==1)
						{
							$hrs=Array();
							for ($i = 0; $i<24; $i++)
								$hrs[]=($i<10?'0':'').$i;
							$min=Array();
							for ($i = 0; $i<60; $i++)
								$min[]=($i<10?'0':'').$i;
							$f->HideEncloser();
							$f->AddSelect('time_hours', $lang['common']['date'], $hrs);
							$f->SetFieldBeginText('time_hours', $lang['common']['time']);
							$f->HideDefinition();
							$f->HideEncloser();
							$f->AddSelect('time_minutes', $lang['common']['date'], $min);
							$f->HideDefinition();
						}
					//-- Date and time end -------
					//-- Editors begin -------
					if (!empty($sm['contenteditor']['controlbuttonsclass']))
						$b->ApplyClassnameForAll($sm['contenteditor']['controlbuttonsclass']);
					$f->InsertButtons($b);
					if ($use_ext_editor)
						$f->AddEditor('text_news', $lang['common']['text'], true);
					else
						$f->AddTextarea('text_news', $lang['common']['text'], true);
					$f->MergeColumns('text_news');
					if (intval(sm_settings('news_use_preview'))==1)
						{
							if ($use_ext_editor)
								{
									$f->AddEditor('preview_news', $lang['common']['preview']);
									$f->SetFieldAttribute('preview_news', 'style', ';');//TinyMCE temporary fix
								}
							else
								$f->AddTextarea('preview_news', $lang['common']['preview']);
							$f->MergeColumns('preview_news');
						}
					if ($use_ext_editor)
						$f->AddHidden('type_news', 1);
					else
						$f->AddSelectVL('type_news', $lang['type_content'], Array(0, 1), Array($lang['type_content_simple_text'], $lang['type_content_HTML']));
					//-- Editors end -------
					$f->Separator($lang['common']['seo']);
					$f->AddText('url', $lang['url'])
						->WithTooltip($lang['common']['leave_empty_for_default']);
					if (sm_action('edit'))
						$f->WithValue(sm_fs_url('index.php?m=news&d=view&nid='.intval($item['id_content']), true));
					$f->AddText('seo_title', $lang['common']['seo_title'])
						->WithTooltip($lang['common']['leave_empty_for_default']);
					$f->AddText('keywords_news', $lang['common']['seo_keywords']);
					$f->AddTextarea('description_news', $lang['common']['seo_description']);
					if (count($sm['themeinfo']['alttpl']['news'])>0)
						$f->Separator($lang['common']['additional_options']);
					if (count($sm['themeinfo']['alttpl']['news'])>0)
						{
							$v=Array('');
							$l=Array($lang['common']['default']);
							for ($i = 0; $i < count($sm['themeinfo']['alttpl']['news']); $i++)
								{
									$v[]=$sm['themeinfo']['alttpl']['news'][$i]['tpl'];
									$l[]=$sm['themeinfo']['alttpl']['news'][$i]['name'];
								}
							$f->AddSelectVL('tplnews', $lang['common']['template'], $v, $l);
						}
					if (intval(sm_settings('news_attachments_count'))>0)
						{
							$f->Separator($lang['common']['attachments']);
							if (sm_action('edit'))
								$attachments=sm_get_attachments('mews', $item['id_news']);
							else
								$attachments=Array();
							for ($i = 0; $i<intval(sm_settings('news_attachments_count')); $i++)
								{
									if ($i<count($attachments))
										$f->AddCheckbox('delete_attachment_'.$attachments[$i]['id'], $lang['number_short'].($i+1).'. '.$lang['delete'].' - '.$attachments[$i]['filename'])
											->LabelAfterControl();
									else
										$f->AddFile('attachment'.$i, $lang['number_short'].($i+1));
								}
						}
					
					if (sm_action('add'))
						{
							$m['type_news'] = $_settings['default_news_text_style'];
							$f->SetValue('id_category', intval($_getvars['ctg']));
							$f->SetValue('date_day', date('d'));
							$f->SetValue('date_month', date('m'));
							$f->SetValue('date_year', date('Y'));
							if (intval(sm_settings('news_use_time'))==1)
								{
									$f->SetValue('time_hours', date('H'));
									$f->SetValue('time_minutes', date('i'));
								}
							if (!$use_ext_editor)
								$f->SetValue('type_news', intval(sm_settings('default_news_text_style')));
						}
					else
						{
							$f->LoadValuesArray($item);
							$tmp=sm_load_metadata('news', intval($item['id_news']));
							$f->SetValue('seo_title', $tmp['seo_title']);
							$f->SetValue('tplnews', $tmp['news_template']);
						}
					$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					if (sm_action('add'))
						sm_event('afternewsaddform');
					else
						sm_event('afternewseditform', Array($item['id_news']));
					$ui->Output(true);
				}
		}

	if ($userinfo['level'] == 3)
		include('modules/inc/adminpart/news.php');

?>