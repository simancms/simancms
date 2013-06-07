<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.4
	//#revision 2013-05-09
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($userinfo['level'] > 2)
		{
			if (strcmp($m["mode"], 'admin') == 0)
				{
					$m['title'] = $lang['settings'];
					$m["module"] = 'content';
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
				}
			if (strcmp($m["mode"], 'editctg') == 0)
				{
					$m['title'] = $lang['edit_category'];
					$m["module"] = 'content';
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					$sql = "SELECT * FROM ".$tableprefix."categories WHERE id_category='".intval($_getvars['ctgid'])."'";
					;
					$result = database_db_query($nameDB, $sql, $lnkDB);
					if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
						{
							$special['ext_editor_on'] = 1;
						}
					while ($row = database_fetch_object($result))
						{
							$m['id_ctg'] = $row->id_category;
							$m['title_category'] = $row->title_category;
							$m['category_can_view'] = $row->can_view;
							$m['category_no_alike_content'] = $row->no_alike_content;
							$m['main_ctg'] = $row->id_maincategory;
							$m['view_groups_category'] = get_array_groups($row->groups_view);
							$m['modify_groups_category'] = get_array_groups($row->groups_modify);
							$m['sorting_category'] = $row->sorting_category;
							$m['category_no_use_path'] = $row->no_use_path;
							if ($special['ext_editor_on'] == 1)
								$m['preview_ctg'] = siman_prepare_to_exteditor($row->preview_category);
							else
								$m['preview_ctg'] = $row->preview_category;
							if (!empty($row->filename_category))
								{
									$m['filesystem'] = get_filesystem($row->filename_category);
									$m["filename_category"] = $m['filesystem']['filename'];
								}
						}
					$m['ctg'] = siman_load_ctgs_content();
					$m['groups_list'] = get_groups_list();
				}
			if (strcmp($m["mode"], 'postaddctg') == 0)
				{
					$m['title'] = $lang['add_category'];
					$m["module"] = 'content';
					$title_category = addslashesJ($_postvars["p_title_category"]);
					$can_view = $_postvars["p_can_view"];
					$id_maincategory = $_postvars["p_mainctg"];
					$id_maincategory = $_postvars["p_mainctg"];
					$preview_category = addslashesJ($_postvars["p_preview_ctg"]);
					$filename = addslashesJ($_postvars["p_filename"]);
					$groups_view = create_groups_str($_postvars['p_groups_view']);
					$groups_modify = create_groups_str($_postvars['p_groups_modify']);
					$no_alike_content = intval($_postvars['p_no_alike_content']);
					$sorting_category = intval($_postvars['p_sorting_category']);
					$no_use_path = intval($_postvars['p_no_use_path']);
					$sql = "INSERT INTO ".$tableprefix."categories (title_category, id_maincategory, can_view, preview_category, groups_view, groups_modify, no_alike_content, sorting_category, no_use_path) VALUES ('$title_category', '$id_maincategory', $can_view, '$preview_category', '$groups_view', '$groups_modify', '$no_alike_content', '$sorting_category', '$no_use_path')";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$ctgid = database_insert_id('categories', $nameDB, $lnkDB);
					if (!empty($filename))
						{
							$urlid = register_filesystem('index.php?m=content&d=viewctg&ctgid='.$ctgid, $filename, $title_category);
							$sql = "UPDATE ".$tableprefix."categories SET filename_category='$urlid' WHERE id_category=".$ctgid;
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					$refresh_url = 'index.php?m=content&d=listctg';
					sm_event('postaddctgcontent', array($ctgid));
				}
			if (strcmp($m["mode"], 'addctg') == 0)
				{
					$m['title'] = $lang['add_category'];
					$m["module"] = 'content';
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					$m['ctg'] = siman_load_ctgs_content();
					if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
						{
							$special['ext_editor_on'] = 1;
						}
					$m['groups_list'] = get_groups_list();
				}
			if (strcmp($m["mode"], 'posteditctg') == 0)
				{
					$m['title'] = $lang['edit_category'];
					$m["module"] = 'content';
					$title_category = addslashesJ($_postvars["p_title_category"]);
					$id_maincategory = $_postvars["p_mainctg"];
					$can_view = $_postvars["p_can_view"];
					$id_ctg = intval($_getvars['ctgid']);
					$preview_category = addslashesJ($_postvars["p_preview_ctg"]);
					$filename = addslashesJ($_postvars["p_filename"]);
					$groups_view = create_groups_str($_postvars['p_groups_view']);
					$groups_modify = create_groups_str($_postvars['p_groups_modify']);
					$no_alike_content = intval($_postvars['p_no_alike_content']);
					$sorting_category = intval($_postvars['p_sorting_category']);
					$no_use_path = intval($_postvars['p_no_use_path']);
					$sql = "UPDATE ".$tableprefix."categories SET title_category = '$title_category', can_view = $can_view, preview_category='$preview_category', id_maincategory='$id_maincategory', groups_view='$groups_view', groups_modify='$groups_modify', no_alike_content='$no_alike_content', sorting_category = '$sorting_category', no_use_path = '$no_use_path' WHERE id_category='$id_ctg'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$sql = "SELECT * FROM ".$tableprefix."categories WHERE id_category='$id_ctg'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_category;
						}
					if ($fname == 0 && !empty($filename))
						{
							$urlid = register_filesystem('index.php?m=content&d=viewctg&ctgid='.$id_ctg, $filename, $title_category);
							$sql = "UPDATE ".$tableprefix."categories SET filename_category='$urlid' WHERE id_category=".$id_ctg;
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					else
						{
							if (empty($filename) && $fname != 0)
								{
									$sql = "UPDATE ".$tableprefix."categories SET filename_category='0' WHERE id_category=".$_getvars["cid"];
									$result = database_db_query($nameDB, $sql, $lnkDB);
									delete_filesystem($fname);
								}
							else
								update_filesystem($fname, 'index.php?m=content&d=viewctg&ctgid='.$id_ctg, $filename, $title_category);
						}
					$refresh_url = 'index.php?m=content&d=listctg';
					sm_event('posteditctgcontent', array($id_ctg));
				}
			if (strcmp($m["mode"], 'deletectg') == 0)
				{
					$m["module"] = 'content';
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['delete_category'];
					$_msgbox['msg'] = $lang['really_want_delete_category'];
					$_msgbox['yes'] = 'index.php?m=content&d=postdeletectg&ctgid='.$_getvars["ctgid"];
					$_msgbox['no'] = 'index.php?m=content&d=listctg';
				}
			if (strcmp($m["mode"], 'postdeletectg') == 0)
				{
					$m['title'] = $lang['delete_category'];
					$m["module"] = 'content';
					$id_ctg = intval($_getvars['ctgid']);
					$sql = "SELECT * FROM ".$tableprefix."categories WHERE id_category='".$id_ctg."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_category;
						}
					if ($fname != 0)
						{
							delete_filesystem($fname);
						}
					$sql = "DELETE FROM ".$tableprefix."categories WHERE id_category='$id_ctg'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=content&d=listctg';
					sm_event('postdeletectgcontent', array($id_ctg));
				}
			if (strcmp($m["mode"], 'listctg') == 0)
				{
					$m['title'] = $lang['list_content_categories'];
					$m["module"] = 'content';
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					$m['ctg'] = siman_load_ctgs_content();
					require_once('includes/admintable.php');
					$m['table']['columns']['title']['caption'] = $lang['common']['title'];
					$m['table']['columns']['title']['width'] = '100%';
					$m['table']['columns']['search']['caption'] = '';
					$m['table']['columns']['search']['hint'] = $lang['search'];
					$m['table']['columns']['search']['replace_text'] = $lang['search'];
					$m['table']['columns']['search']['replace_image'] = 'search.gif';
					$m['table']['columns']['search']['width'] = '16';
					$m['table']['columns']['edit']['caption'] = '';
					$m['table']['columns']['edit']['hint'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_text'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_image'] = 'edit.gif';
					$m['table']['columns']['edit']['width'] = '16';
					$m['table']['columns']['html']['caption'] = '';
					$m['table']['columns']['html']['hint'] = $lang['common']['edit'].' ('.$lang['common']['html'].')';
					$m['table']['columns']['html']['replace_text'] = $lang['common']['html'];
					$m['table']['columns']['html']['replace_image'] = 'edit_html.gif';
					$m['table']['columns']['html']['width'] = '16';
					$m['table']['columns']['delete']['caption'] = '';
					$m['table']['columns']['delete']['hint'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_text'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_image'] = 'delete.gif';
					$m['table']['columns']['delete']['width'] = '16';
					$m['table']['columns']['delete']['messagebox'] = 1;
					$m['table']['columns']['delete']['messagebox_text'] = addslashes($lang['really_want_delete_category']);
					$m['table']['columns']['stick']['caption'] = '';
					$m['table']['columns']['stick']['hint'] = $lang['set_as_block_random_text'];
					$m['table']['columns']['stick']['replace_text'] = $lang['common']['stick'];
					$m['table']['columns']['stick']['replace_image'] = 'stick.gif';
					$m['table']['columns']['tomenu']['caption'] = '';
					$m['table']['columns']['tomenu']['hint'] = $lang['module_menu']['add_to_menu'];
					$m['table']['columns']['tomenu']['replace_text'] = $lang['module_menu']['add_to_menu'];
					$m['table']['columns']['tomenu']['to_menu'] = 1;
					$m['table']['default_column'] = 'edit';
					for ($i = 0; $i < count($m['ctg']); $i++)
						{
							$lev = '';
							for ($j = 1; $j < $m['ctg'][$i]['level']; $j++)
								{
									$lev .= '-';
								}
							$m['table']['rows'][$i]['title']['data'] = $lev.$m['ctg'][$i]['title'];
							$m['table']['rows'][$i]['title']['hint'] = $m['ctg'][$i]['title'];
							$m['table']['rows'][$i]['title']['url'] = $m['ctg'][$i]['filename'];
							$m['table']['rows'][$i]['edit']['url'] = 'index.php?m=content&d=editctg&ctgid='.$m['ctg'][$i]['id'];
							$m['table']['rows'][$i]['search']['url'] = 'index.php?m=content&d=list&ctg='.$m['ctg'][$i]['id'];
							if ($m['ctg'][$i]['id'] != 1)
								$m['table']['rows'][$i]['delete']['url'] = 'index.php?m=content&d=postdeletectg&ctgid='.$m['ctg'][$i]['id'];
							$m['table']['rows'][$i]['html']['url'] = 'index.php?m=content&d=editctg&ctgid='.$m['ctg'][$i]['id'].'&exteditor=off';
							$m['table']['rows'][$i]['tomenu']['menu_url'] = addslashes($m['table']['rows'][$i]['title']['url']);
							$m['table']['rows'][$i]['tomenu']['menu_caption'] = addslashes($m['ctg'][$i]['title']);
							$m['table']['rows'][$i]['stick']['url'] = 'index.php?m=blocks&d=add&b=content&id='.$m['ctg'][$i]['id'].'&db=rndctgview&c='.$m['ctg'][$i]['title'];
						}
				}
			if (strcmp($m["mode"], 'postedit') == 0)
				{
					$m['title'] = $lang['edit_content'];
					$m["module"] = 'content';
					$id_category_c = $_postvars["p_id_category_c"];
					$title_content = addslashesJ($_postvars["p_title_content"]);
					$preview_content = addslashesJ($_postvars["p_preview_content"]);
					$text_content = addslashesJ($_postvars["p_text_content"]);
					$type_content = $_postvars["p_type_content"];
					$keywords_content = addslashesJ($_postvars["p_keywords_content"]);
					$filename = addslashesJ($_postvars["p_filename"]);
					if ($_settings['content_use_preview'] == 1)
						$tmp_preview_sql = "preview_content='$preview_content',";
					$sql = "UPDATE ".$tableprefix."content SET id_category_c='$id_category_c', title_content='$title_content', $tmp_preview_sql text_content='$text_content', type_content='$type_content', keywords_content = '$keywords_content' WHERE id_content='".$_getvars["cid"]."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$sql = "SELECT * FROM ".$tableprefix."content WHERE id_content='".intval($_getvars["cid"])."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_content;
						}
					if ($fname == 0 && !empty($filename))
						{
							$urlid = register_filesystem('index.php?m=content&d=view&cid='.$_getvars["cid"], $filename, $title_content);
							$sql = "UPDATE ".$tableprefix."content SET filename_content='$urlid' WHERE id_content=".$_getvars["cid"];
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					else
						{
							if (empty($filename))
								{
									$sql = "UPDATE ".$tableprefix."content SET filename_content='0' WHERE id_content=".$_getvars["cid"];
									$result = database_db_query($nameDB, $sql, $lnkDB);
									delete_filesystem($fname);
								}
							else
								update_filesystem($fname, 'index.php?m=content&d=view&cid='.$_getvars["cid"], $filename, $title_content);
						}
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=content&d=list&ctg='.$id_category_c;
				}
			if (strcmp($m["mode"], 'list') == 0)
				{
					sm_extcore();
					if (intval($_getvars['showall']) == 1)
						$showall = 1;
					$m['showall'] = $showall;
					$from_record = intval($_getvars['from']);
					$ctg_id = intval($_getvars['ctg']);
					if ($showall != 1)
						{
							if (empty($from_record)) $from_record = 0;
							$from_page = ceil(($from_record + 1) / $_settings['admin_items_by_page']);
							$m['pages']['url'] = 'index.php?m=content&d=list&ctg='.$ctg_id;
							$m['pages']['selected'] = $from_page;
							$m['pages']['interval'] = $_settings['admin_items_by_page'];
						}
					$m['ctg_id'] = $ctg_id;
					$m['ctg'] = siman_load_ctgs_content();
					$m['title'] = $lang['list_content'];
					$m["module"] = 'content';
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					add_path($lang['list_content'], "index.php?m=content&d=list");
					$sql = "SELECT ".$tableprefix."content.*, ".$tableprefix."filesystem.* FROM ".$tableprefix."content LEFT JOIN ".$tableprefix."filesystem ON ".$tableprefix."content.filename_content=".$tableprefix."filesystem.id_fs";
					$sort = 0;
					if (!empty($ctg_id))
						{
							$sql .= " WHERE id_category_c = '$ctg_id'";
							for ($i = 0; $i < 10; $i++)
								{
									if ($m['ctg'][$i]['id'] == $ctg_id)
										{
											$sort = $m['ctg'][$i]['sorting_category'];
											add_path($m['ctg'][$i]['title'], 'index.php?m=content&d=list&ctg='.$m['ctg'][$i]['id']);
											$m['title'] .= ' - '.$m['ctg'][$i]['title'];
										}
								}
						}
					if ($sort == 1)
						$sql .= " ORDER BY title_content DESC";
					elseif ($sort == 2)
						$sql .= " ORDER BY priority_content ASC";
					elseif ($sort == 3)
						$sql .= " ORDER BY priority_content DESC";
					else
						$sql .= " ORDER BY title_content ASC";
					if ($showall != 1)
						$sql .= " LIMIT ".$_settings['admin_items_by_page']." OFFSET $from_record";
					require_once('includes/admintable.php');
					$m['table']['columns']['title']['caption'] = $lang['common']['title'];
					$m['table']['columns']['title']['width'] = '100%';
					$m['table']['columns']['edit']['caption'] = '';
					$m['table']['columns']['edit']['hint'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_text'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_image'] = 'edit.gif';
					$m['table']['columns']['edit']['width'] = '16';
					$m['table']['columns']['html']['caption'] = '';
					$m['table']['columns']['html']['hint'] = $lang['common']['edit'].' ('.$lang['common']['html'].')';
					$m['table']['columns']['html']['replace_text'] = $lang['common']['html'];
					$m['table']['columns']['html']['replace_image'] = 'edit_html.gif';
					$m['table']['columns']['html']['width'] = '16';
					$m['table']['columns']['delete']['caption'] = '';
					$m['table']['columns']['delete']['hint'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_text'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_image'] = 'delete.gif';
					$m['table']['columns']['delete']['width'] = '16';
					$m['table']['columns']['delete']['messagebox'] = 1;
					$m['table']['columns']['delete']['messagebox_text'] = addslashes($lang['module_content']['really_want_delete_text']);
					$m['table']['columns']['stick']['caption'] = '';
					$m['table']['columns']['stick']['hint'] = $lang["set_as_block"];
					$m['table']['columns']['stick']['replace_text'] = $lang['common']['stick'];
					$m['table']['columns']['stick']['replace_image'] = 'stick.gif';
					$m['table']['columns']['tomenu']['caption'] = '';
					$m['table']['columns']['tomenu']['hint'] = $lang['module_menu']['add_to_menu'];
					$m['table']['columns']['tomenu']['replace_text'] = '<nobr>'.$lang['module_menu']['add_to_menu'].'</nobr>';
					//$m['table']['columns']['tomenu']['to_menu'] = 1;
					if ($sort == 2 || $sort == 3)
						{
							$m['table']['columns']['up']['caption'] = '';
							$m['table']['columns']['up']['hint'] = $lang['up'];
							$m['table']['columns']['up']['replace_text'] = $lang['up'];
							$m['table']['columns']['up']['replace_image'] = 'up.gif';
							$m['table']['columns']['up']['width'] = '16';
							$m['table']['columns']['down']['caption'] = '';
							$m['table']['columns']['down']['hint'] = $lang['down'];
							$m['table']['columns']['down']['replace_text'] = $lang['down'];
							$m['table']['columns']['down']['replace_image'] = 'down.gif';
							$m['table']['columns']['down']['width'] = '16';
						}
					$m['table']['default_column'] = 'edit';
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['table']['rows'][$i]['title']['data'] = $row->title_content;
							$m['table']['rows'][$i]['title']['hint'] = $row->title_content;
							if ($row->filename_content != 0)
								{
									$m['table']['rows'][$i]['title']['url'] = $row->filename_fs;
								}
							else
								{
									$m['table']['rows'][$i]['title']['url'] = 'index.php?m=content&d=view&cid='.$row->id_content;
								}
							$m['table']['rows'][$i]['edit']['url'] = 'index.php?m=content&d=edit&cid='.$row->id_content;
							if ($row->id_content != 1)
								$m['table']['rows'][$i]['delete']['url'] = 'index.php?m=content&d=postdelete&cid='.$row->id_content.'&ctg='.$row->id_category_c;
							$m['table']['rows'][$i]['html']['url'] = 'index.php?m=content&d=edit&cid='.$row->id_content.'&exteditor=off';
							//$m['table']['rows'][$i]['tomenu']['menu_url'] = addslashes($m['table']['rows'][$i]['title']['url']);
							//$m['table']['rows'][$i]['tomenu']['menu_caption'] = addslashes($row->title_content);
							$m['table']['rows'][$i]['tomenu']['url'] = sm_tomenuurl($row->title_content, $m['table']['rows'][$i]['title']['url'], sm_this_url());
							$m['table']['rows'][$i]['stick']['url'] = 'index.php?m=blocks&d=add&b=content&id='.$row->id_content.'&c='.$row->title_content;
							if ($i > 0 && ($sort == 2 || $sort == 3))
								{
									$m['table']['rows'][$i - 1]['down']['url'] = 'index.php?m=content&d=exchange&id1='.$row->id_content.'&id2='.$id_prewious.'&ctg='.$ctg_id.'&showall='.$showall;
									$m['table']['rows'][$i]['up']['url'] = $m['table']['rows'][$i - 1]['down']['url'];
								}
							$id_prewious = $row->id_content;
							$i++;
						}

					if ($showall != 1)
						{
							$sql = "SELECT count(*) FROM ".$tableprefix."content";
							if (!empty($ctg_id)) $sql .= " WHERE id_category_c = '$ctg_id'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
							$m['pages']['records'] = 0;
							while ($row = database_fetch_row($result))
								{
									$m['pages']['records'] = $row[0];
								}
							$m['pages']['pages'] = ceil($m['pages']['records'] / $_settings['admin_items_by_page']);
						}
				}
			if (strcmp($m["mode"], 'exchange') == 0)
				{
					$m['title'] = $lang['operation_complete'];
					$m["module"] = 'content';
					$id1 = intval($_getvars['id1']);
					$id2 = intval($_getvars['id2']);
					$sql = "SELECT * FROM ".$tableprefix."content WHERE id_content='$id1'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$pr1 = $row->priority_content;
						}
					$sql = "SELECT * FROM ".$tableprefix."content WHERE id_content='$id2'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$pr2 = $row->priority_content;
						}
					if (!empty($pr1) && !empty($pr2))
						{
							$sql = "UPDATE ".$tableprefix."content SET priority_content='$pr1' WHERE id_content='$id2'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
							$sql = "UPDATE ".$tableprefix."content SET priority_content='$pr2' WHERE id_content='$id1'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					$refresh_url = 'index.php?m=content&d=list&ctg='.(intval($_getvars['ctg'])).'&showall='.(intval($_getvars['showall']));
				}
		}

?>