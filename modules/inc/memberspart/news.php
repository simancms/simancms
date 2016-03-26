<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.10
	//#revision 2016-03-26
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($sm['u']['level'] > 0)
		{
			if (!defined("NEWS_MEMBERSPART_FUNCTIONS_DEFINED"))
				{
					function siman_get_available_categories_news()
						{
							global $sm;
							$q = new TQuery($sm['t'].'categories_news');
							if ($sm['u']['level'] < intval(sm_settings('news_editor_level')))
								$q->Add('('.convert_groups_to_sql($sm['u']['groups'], 'groups_modify').')');
							$q->OrderBy('title_category');
							$q->Select();
							$categories=Array();
							for ($i = 0; $i < $q->Count(); $i++)
								{
									$categories[$i]['id']=$q->items[$i]['id_category'];
									$categories[$i]['title']=$q->items[$i]['title_category'];
								}
							return $categories;
						}

					function siman_is_allowed_to_add_news()
						{
							global $sm;
							if ($sm['u']['level'] >= intval(sm_settings('news_editor_level')))
								return true;
							elseif (!empty($sm['u']['groups']))
								{
									$categories = siman_get_available_categories_news();
									if (count($categories) > 0)
										return true;
								}
							return false;
						}

					function siman_is_allowed_to_edit_news($id)
						{
							global $sm;
							if ($sm['u']['level'] >= intval(sm_settings('news_editor_level')))
								return true;
							elseif (!empty($sm['u']['groups']))
								{
									$categories = siman_get_available_categories_news();
									if (count($categories) > 0)
										{
											$content = TQuery::ForTable($sm['t'].'news')
												->AddWhere('id_news', intval($id))
												->Get();
											if (empty($content['id_news']))
												return false;
											for ($i = 0; $i < count($categories); $i++)
												{
													if (intval($categories[$i]['id']) == intval($content['id_category_n']))
														return true;
												}
										}
								}
							return false;
						}

					define("NEWS_MEMBERSPART_FUNCTIONS_DEFINED", 1);
				}
			/*
			if (sm_actionpost('postadd') && ($userinfo['level'] >= intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
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
			*/

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

			if (sm_action('delete') && ($userinfo['level'] >= intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
				{
					$candelete = 0;
					if ($userinfo['level'] >= intval(sm_settings('news_editor_level')))
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

			if (sm_action('postdelete') && ($userinfo['level'] >= intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
				{
					$candelete = 0;
					if ($userinfo['level'] >= intval(sm_settings('news_editor_level')))
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

			/*
			if (sm_actionpost('postedit') && ($userinfo['level'] >= intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
				{
					$canedit = 0;
					if ($userinfo['level'] >= intval(sm_settings('news_editor_level')))
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
			*/

			/*
			if (sm_action('edit') && ($userinfo['level'] >= intval(sm_settings('news_editor_level')) || !empty($userinfo['groups'])))
				{
					$canedit = 0;
					if ($userinfo['level'] >= intval(sm_settings('news_editor_level')))
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
									if (count($sm['themeinfo']['alttpl']['news']) > 0)
										{
											$m['alttpl']['news'] = Array(Array('tpl' => '', 'name' => $lang['common']['default']));
											for ($i = 0; $i < count($sm['themeinfo']['alttpl']['news']); $i++)
												{
													$m['alttpl']['news'][] = $sm['themeinfo']['alttpl']['news'][$i];
												}
										}
									$tmp = sm_load_metadata('news', $m["id_news"]);
									if (!isset($sm['p']['tplnews']))
										$sm['p']['tplnews'] = $tmp['news_template'];
									sm_event('oneditnews', array($m["id_news"]));
								}
						}
					$m['images'] = load_file_list('./files/img/', 'jpg|gif|jpeg|png');
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
					add_path($lang['module_news']['list_news'], "index.php?m=news&d=list&ctg=".$m['ctgidselected']."");
				}   
			*/

			if (sm_action('postadd') && siman_is_allowed_to_add_news() || sm_action('postedit') && siman_is_allowed_to_edit_news(intval($_getvars['id'])))
				{
					sm_extcore();
					if (sm_action('postadd'))
						sm_event('beforepostaddnews');
					else
						{
							$id_news=intval($_getvars['id']);
							sm_event('beforeposteditnews', array($id_news));
						}
					$timestamp=@mktime($sm['p']['time_hours'], $sm['p']['time_minutes'], 0, $sm['p']['date_month'], $sm['p']['date_day'], $sm['p']['date_year']);
					if (empty($sm['p']['title_news']) || empty($sm['p']['id_category']))
						$error=$lang['messages']['fill_required_fields'];
					elseif (sm_action('postadd') && !empty($sm['p']['url']) && sm_fs_exists($sm['p']['url']))
						$error=$lang['messages']['seo_url_exists'];
					elseif (sm_action('postedit') && !empty($sm['p']['url']) && sm_fs_exists($sm['p']['url']) && strcmp($sm['p']['url'], sm_fs_url('index.php?m=news&d=view&nid='.intval($_getvars['id'])))!=0)
						$error=$lang['messages']['seo_url_exists'];
					elseif ($timestamp===false || $timestamp==-1)
						$error=$lang['messages']['wrong_date'];
					if (empty($error))
						{
							if (sm_action('postadd'))
								sm_event('startpostaddnews');
							else
								sm_event('startposteditnews', array($id_news));
							$q=new TQuery($sm['t'].'news');
							$q->Add('id_category_n', intval($sm['p']['id_category']));
							if (sm_action('postadd'))
								$q->Add('id_author_news', intval($sm['u']['id']));
							$q->Add('img_copyright_news', dbescape($sm['p']['img_copyright_news']));
							$q->Add('date_news', intval($timestamp));
							$q->Add('title_news', dbescape($sm['p']['title_news']));
							if (intval(sm_settings('news_use_preview'))==1)
								$q->Add('preview_news', dbescape($sm['p']['preview_news']));
							$q->Add('text_news', dbescape($sm['p']['text_news']));
							$q->Add('type_news', intval($sm['p']['type_news']));
							$q->Add('keywords_news', dbescape($sm['p']['keywords_news']));
							$q->Add('description_news', dbescape($sm['p']['description_news']));
							if (sm_action('postadd'))
								{
									$id_news=$q->Insert();
									sm_set_metadata('news', $id_news, 'author_id', $sm['u']['id']);
									sm_set_metadata('news', $id_news, 'time_created', time());
								}
							else
								{
									$q->Update('id_news', intval($id_news));
								}
							$item = TQuery::ForTable($sm['t'].'news')
								->AddWhere('id_news', intval($id_news))
								->Get();
							sm_set_metadata('news', $id_news, 'last_updated_time', time());
							sm_set_metadata('news', $id_news, 'news_template', $_postvars['tplnews']);
							sm_set_metadata('news', $id_news, 'seo_title', $_postvars['seo_title']);
							if (sm_action('postedit'))
								{
									$attachments=sm_get_attachments('news', $id_news);
									for ($i = 0; $i<count($attachments); $i++)
										{
											if (!empty($sm['p']['delete_attachment_'.$attachments[$i]['id']]))
												{
													sm_delete_attachment(intval($attachments[$i]['id']));
													sm_event('postdeleteattachment', array(intval($attachments[$i]['id'])));
												}
										}
								}
							for ($i = 0; $i < intval(sm_settings('news_attachments_count')); $i++)
								{
									sm_upload_attachment('news', $id_news, $_uplfilevars['attachment'.$i]);
								}
							if (!empty($sm['p']['url']))
								sm_fs_update($sm['p']['title_news'], 'index.php?m=news&d=view&nid='.intval($id_news), $sm['p']['url']);
							//TODO remove url if empty
							if (sm_action('postadd'))
								sm_notify($lang['messages']['add_successful']);
							else
								sm_notify($lang['messages']['edit_successful']);
							if (sm_action('postadd'))
								sm_event('postaddnews', array($id_news));
							else
								sm_event('posteditnews', array($id_news));
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								{
									if ($sm['u']['level'] < 3)
										sm_redirect('index.php?m=news&d=listnews&ctg='.intval($sm['p']['id_category_c']));
									else
										sm_redirect('index.php?m=news&d=list&ctg='.intval($sm['p']['id_category_c']));
								}
						}
					else
						sm_set_action(Array('postadd'=>'add', 'postedit'=>'edit'));
				}
			if (sm_action('add') && siman_is_allowed_to_add_news() || sm_action('edit') && siman_is_allowed_to_edit_news(intval($_getvars['id'])))
				{
					if (sm_action('add'))
						{
							sm_event('onaddnews');
							sm_title($lang['news'].' - '.$lang['common']['add']);
						}
					else
						{
							$item = TQuery::ForTable($sm['t'].'news')
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
					if ($sm['u']['level'] == 3)
						{
							add_path_modules();
							add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
							add_path($lang['module_news']['list_news'], "index.php?m=news&d=list");
						}
					else
						add_path_home();
					add_path_current();
					$ui = new TInterface();
					$b = new TButtons();
					if ($_getvars['exteditor'] != 'off')
						{
							$b->AddMessageBox('exteditoroff', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor' => 'off')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
							$modal = new TModalHelper();
							$modal->SetAJAXSource('index.php?m=media&d=editorinsert&theonepage=1');
							$b->AddButton('insertimgmodal', $lang['add_image'])
								->OnClick($modal->GetJSCode());
							$use_ext_editor=true;
						}
					else
						{
							$b->AddMessageBox('exteditoron', $lang['ext']['editors']['switch_to_standard_editor'], sm_this_url(Array('exteditor' => '')), $lang['common']['are_you_sure']."? ".$lang['messages']['changes_will_be_lost']);
							$use_ext_editor=false;
						}
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
								$item['url'] = get_filename($item['filename_news']);
							else
								$item['url'] = sm_fs_url('index.php?m=news&d=view&nid='.$item['id_news']);
							$f = new TForm('index.php?m='.sm_current_module().'&d=postedit&id='.$item['id_news'].'&returnto='.urlencode($_getvars['returnto']));
							sm_event('startnewseditform', Array($cid));
						}
					$f->AddText('title_news', $lang['common']['title'])
						->SetFocus();
					$categories=siman_get_available_categories_news();
					$categories_v=Array();
					$categories_l=Array();
					for ($i = 0; $i < count($categories); $i++)
						{
							$categories_v[]=$categories[$i]['id'];
							$categories_l[]=$categories[$i]['title'];
						}
					$f->AddSelectVL('id_category', $lang['common']['category'], $categories_v, $categories_l);
					if (intval(sm_settings('news_use_image')) == 1)
						{
							$f->AddFile('userfile', $lang['common']['image']);
							$f->AddText('img_copyright_news', $lang['common']['copyright'].' ('.$lang['common']['image'].')');
						}
					//-- Date and time begin -------
					$years = Array();
					for ($i = 2006; $i <= intval(date('Y') + 10); $i++)
						{
							$years[] = $i;
						}
					$days = Array();
					for ($i = 1; $i <= 31; $i++)
						{
							$days[] = $i;
						}
					$months_v = Array();
					$months_l = Array();
					for ($i = 1; $i <= 12; $i++)
						{
							$months_v[] = $i;
							$months_l[] = $lang['month_'.$i];
						}
					$f->AddSelect('date_day', $lang['common']['date'], $days);
					$f->HideEncloser();
					$f->AddSelectVL('date_month', $lang['common']['date'], $months_v, $months_l);
					$f->HideDefinition();
					$f->HideEncloser();
					$f->AddSelect('date_year', $lang['common']['date'], $years);
					$f->HideDefinition();
					if (intval(sm_settings('news_use_time')) == 1)
						{
							$hrs = Array();
							for ($i = 0; $i < 24; $i++)
								{
									$hrs[] = ($i < 10 ? '0' : '').$i;
								}
							$min = Array();
							for ($i = 0; $i < 60; $i++)
								{
									$min[] = ($i < 10 ? '0' : '').$i;
								}
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
					if (intval(sm_settings('news_use_preview')) == 1)
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
					if (count($sm['themeinfo']['alttpl']['news']) > 0)
						$f->Separator($lang['common']['additional_options']);
					if (count($sm['themeinfo']['alttpl']['news']) > 0)
						{
							$v = Array('');
							$l = Array($lang['common']['default']);
							for ($i = 0; $i < count($sm['themeinfo']['alttpl']['news']); $i++)
								{
									$v[] = $sm['themeinfo']['alttpl']['news'][$i]['tpl'];
									$l[] = $sm['themeinfo']['alttpl']['news'][$i]['name'];
								}
							$f->AddSelectVL('tplnews', $lang['common']['template'], $v, $l);
						}
					if (intval(sm_settings('news_attachments_count')) > 0)
						{
							$f->Separator($lang['common']['attachments']);
							if (sm_action('edit'))
								$attachments = sm_get_attachments('mews', $item['id_news']);
							else
								$attachments = Array();
							for ($i = 0; $i < intval(sm_settings('news_attachments_count')); $i++)
								{
									if ($i < count($attachments))
										$f->AddCheckbox('delete_attachment_'.$attachments[$i]['id'], $lang['number_short'].($i + 1).'. '.$lang['delete'].' - '.$attachments[$i]['filename'])
											->LabelAfterControl();
									else
										$f->AddFile('attachment'.$i, $lang['number_short'].($i + 1));
								}
						}
					//-------------------------------
					if (sm_action('add'))
						{
							$m['type_news'] = $_settings['default_news_text_style'];
							$f->SetValue('id_category', intval($_getvars['ctg']));
							$f->SetValue('date_day', date('d'));
							$f->SetValue('date_month', date('m'));
							$f->SetValue('date_year', date('Y'));
							if (intval(sm_settings('news_use_time')) == 1)
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
							$tmp = sm_load_metadata('news', intval($item['id_news']));
							$f->SetValue('seo_title', $tmp['seo_title']);
							$f->SetValue('tplnews', $tmp['news_template']);
							$f->SetValue('url', $item['url']);
							$f->SetValue('date_day', date('d', $item['date_news']));
							$f->SetValue('date_month', date('m', $item['date_news']));
							$f->SetValue('date_year', date('Y', $item['date_news']));
							if (intval(sm_settings('news_use_time')) == 1)
								{
									$f->SetValue('time_hours', date('H', $item['date_news']));
									$f->SetValue('time_minutes', date('i', $item['date_news']));
								}
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