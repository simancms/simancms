<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.15
	//#revision 2018-01-08
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($sm['u']['level'] > 2)
		{
			if (sm_action('admin'))
				{
					sm_title($lang['control_panel'].' - '.$lang['module_content_name']);
					sm_extcore();
					add_path_modules();
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$nav=new TNavigation();
					$nav->AddItem($lang['common']['add'], 'index.php?m=content&d=add');
					$nav->AddItem(sprintf('%s (%s)', $lang['common']['add'], $lang['common']['html']), 'index.php?m=content&d=add&exteditor=off');
					$nav->AddItem($lang['list_content'], 'index.php?m=content&d=list');
					$nav->AddItem($lang['common']['categories'], 'index.php?m=content&d=listctg');
					$nav->AddItem($lang['add_category'], 'index.php?m=content&d=addctg');
					$nav->AddItem(sprintf('%s "%s - %s"', $lang['set_as_block'], $lang['list_content'], $lang['common']['category']), sm_addblockurl($lang['list_content'].' - '.$lang['common']['category'], 'content', 1, 'blockctgview'));
					$ui->Add($nav);
					$ui->Output(true);
				}
			if (sm_action('editctg'))
				{
					$m['title'] = $lang['edit_category'];
					$m["module"] = 'content';
					add_path_modules();
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					$sql = "SELECT * FROM ".$tableprefix."categories WHERE id_category='".intval($_getvars['ctgid'])."'";
					$result = execsql($sql);
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
			if (sm_actionpost('postaddctg'))
				{
					$m['title'] = $lang['add_category'];
					$m["module"] = 'content';
					$title_category = dbescape($_postvars["p_title_category"]);
					$can_view = $_postvars["p_can_view"];
					$id_maincategory = $_postvars["p_mainctg"];
					$id_maincategory = $_postvars["p_mainctg"];
					$preview_category = dbescape($_postvars["p_preview_ctg"]);
					$filename = dbescape($_postvars["p_filename"]);
					$groups_view = create_groups_str($_postvars['p_groups_view']);
					$groups_modify = create_groups_str($_postvars['p_groups_modify']);
					$no_alike_content = intval($_postvars['p_no_alike_content']);
					$sorting_category = intval($_postvars['p_sorting_category']);
					$no_use_path = intval($_postvars['p_no_use_path']);
					$sql = "INSERT INTO ".$tableprefix."categories (title_category, id_maincategory, can_view, preview_category, groups_view, groups_modify, no_alike_content, sorting_category, no_use_path) VALUES ('$title_category', '$id_maincategory', $can_view, '$preview_category', '$groups_view', '$groups_modify', '$no_alike_content', '$sorting_category', '$no_use_path')";
					$ctgid = insertsql($sql);
					if (!empty($filename))
						{
							$urlid = register_filesystem('index.php?m=content&d=viewctg&ctgid='.$ctgid, $filename, $title_category);
							$sql = "UPDATE ".$tableprefix."categories SET filename_category=".intval($urlid)." WHERE id_category=".intval($ctgid);
							$result = execsql($sql);
						}
					sm_notify($lang['add_content_category_successful']);
					if (!empty($_getvars['returnto']))
						sm_redirect($_getvars['returnto']);
					else
						sm_redirect('index.php?m=content&d=listctg');
					sm_event('postaddctgcontent', array($ctgid));
				}
			if (sm_action('addctg'))
				{
					$m['title'] = $lang['add_category'];
					$m["module"] = 'content';
					add_path_modules();
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					$m['ctg'] = siman_load_ctgs_content();
					if (!empty($_settings['ext_editor']) && $_getvars['exteditor'] != 'off')
						{
							$special['ext_editor_on'] = 1;
						}
					$m['groups_list'] = get_groups_list();
				}
			if (sm_actionpost('posteditctg'))
				{
					$title_category = dbescape($_postvars["p_title_category"]);
					$id_maincategory = $_postvars["p_mainctg"];
					$can_view = $_postvars["p_can_view"];
					$id_ctg = intval($_getvars['ctgid']);
					$preview_category = dbescape($_postvars["p_preview_ctg"]);
					$filename = dbescape($_postvars["p_filename"]);
					$groups_view = create_groups_str($_postvars['p_groups_view']);
					$groups_modify = create_groups_str($_postvars['p_groups_modify']);
					$no_alike_content = intval($_postvars['p_no_alike_content']);
					$sorting_category = intval($_postvars['p_sorting_category']);
					$no_use_path = intval($_postvars['p_no_use_path']);
					$sql = "UPDATE ".$tableprefix."categories SET title_category = '$title_category', can_view = $can_view, preview_category='$preview_category', id_maincategory='$id_maincategory', groups_view='$groups_view', groups_modify='$groups_modify', no_alike_content='$no_alike_content', sorting_category = '$sorting_category', no_use_path = '$no_use_path' WHERE id_category='$id_ctg'";
					$result = execsql($sql);
					$sql = "SELECT * FROM ".$tableprefix."categories WHERE id_category='$id_ctg'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_category;
						}
					if ($fname == 0 && !empty($filename))
						{
							$urlid = register_filesystem('index.php?m=content&d=viewctg&ctgid='.$id_ctg, $filename, $title_category);
							$sql = "UPDATE ".$tableprefix."categories SET filename_category='$urlid' WHERE id_category=".$id_ctg;
							$result = execsql($sql);
						}
					else
						{
							if (empty($filename) && $fname != 0)
								{
									$sql = "UPDATE ".$tableprefix."categories SET filename_category='0' WHERE id_category=".$_getvars["cid"];
									$result = execsql($sql);
									delete_filesystem($fname);
								}
							else
								update_filesystem($fname, 'index.php?m=content&d=viewctg&ctgid='.$id_ctg, $filename, $title_category);
						}
					sm_notify($lang['edit_content_category_successful']);
					if (!empty($_getvars['returnto']))
						sm_redirect($_getvars['returnto']);
					else
						sm_redirect('index.php?m=content&d=listctg');
					sm_event('posteditctgcontent', array($id_ctg));
				}
			if (sm_action('postdeletectg') && intval($_getvars['ctgid'])!=1)
				{
					$id_ctg = intval($_getvars['ctgid']);
					if ($id_ctg!=1)
						{
							sm_extcore();
							sm_saferemove('index.php?m=content&d=viewctg&ctgid='.intval($id_ctg));
							TQuery::ForTable($sm['t'].'categories')
								->AddWhere('id_category', intval($id_ctg))
								->Remove();
							TQuery::ForTable($sm['t'].'content')
								->Add('id_category_c', 1)
								->Update('id_category_c', intval($id_ctg));
							sm_notify($lang['delete_content_category_successful']);
							sm_redirect('index.php?m=content&d=listctg');
							sm_event('postdeletectgcontent', array($id_ctg));
						}
				}
			if (sm_action('listctg'))
				{
					sm_extcore();
					sm_title($lang['module_content_name'].' - '.$lang['common']['categories']);
					add_path_modules();
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					add_path_current();
					$m['ctg'] = siman_load_ctgs_content();
					sm_use('admintable');
					sm_use('admininterface');
					sm_use('adminbuttons');
					$ui = new TInterface();
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['common']['title'], '100%');
					$t->AddCol('search', '', '16', $lang['search'], '', 'search.gif');
					$t->AddEdit();
					$t->AddCol('html', '', '16', $lang['common']['edit'].' ('.$lang['common']['html'].')', '', 'edit_html.gif');
					$t->AddDelete();
					$t->AddCol('stick', '', '16', $lang["set_as_block"], '', 'stick.gif');
					$t->AddMenuInsert();
					for ($i = 0; $i < count($m['ctg']); $i++)
						{
							$lev = '';
							for ($j = 1; $j < $m['ctg'][$i]['level']; $j++)
								{
									$lev .= '-';
								}
							$t->Label('title', $lev.$m['ctg'][$i]['title']);
							$t->URL('title', $m['ctg'][$i]['filename']);
							$t->URL('search', 'index.php?m=content&d=list&ctg='.$m['ctg'][$i]['id']);
							if ($m['ctg'][$i]['id'] != 1)
								$t->URL('delete', 'index.php?m=content&d=postdeletectg&ctgid='.$m['ctg'][$i]['id']);
							$t->URL('edit', 'index.php?m=content&d=editctg&ctgid='.$m['ctg'][$i]['id'].'&returnto='.urlencode(sm_this_url()));
							$t->URL('html', 'index.php?m=content&d=editctg&ctgid='.$m['ctg'][$i]['id'].'&exteditor=off'.'&returnto='.urlencode(sm_this_url()));
							$t->URL('tomenu', sm_tomenuurl($m['ctg'][$i]['title'], $m['ctg'][$i]['filename'], sm_this_url()));
							$t->URL('stick', sm_addblockurl($m['ctg'][$i]['title'], 'content', $m['ctg'][$i]['id'], 'rndctgview'));
							$t->NewRow();
						}
					$b=new TButtons();
					$b->AddButton('add', $lang['add_category'], 'index.php?m=content&d=addctg');
					$b->AddButton('addhtml', $lang['common']['add'].' ('.$lang['common']['html'].')', 'index.php?m=content&d=addctg&exteditor=off');
					$ui->AddButtons($b);
					$ui->AddGrid($t);
					$ui->AddButtons($b);
					$ui->Output(true);
				}
			if (sm_action('list'))
				{
					sm_extcore();
					sm_use('ui.interface');
					sm_use('ui.grid');
					sm_use('ui.form');
					sm_use('ui.buttons');
					$ui=new TInterface();
					$f=new TForm('index.php');
					$f->SetMethodGet();
					$f->AddHidden('m')->WithValue('content');
					$f->AddHidden('d')->WithValue('list');
					$f->AddHidden('search')->WithValue('yes');
					$ctgs=siman_load_ctgs_content();
					$v=Array();
					$l=Array();
					for ($i=0; $i<count($ctgs); $i++)
						{
							$v[]=$ctgs[$i]['id'];
							$l[]=($ctgs[$i]['level']>1?str_repeat('- ', $ctgs[$i]['level']-1):'').$ctgs[$i]['title'];
						}
					unset($ctgs);
					$f->AddSelectVL('ctg', $lang['common']['category'], $v, $l);
					$f->SelectAddBeginVL('ctg', '', $lang['all_categories']);
					$f->AddSelectVL('showall', $lang['common']['show_all'], Array('', 'yes'), Array($lang['no'], $lang['yes']));
					$f->LoadValuesArray($_getvars);
					$f->SaveButton($lang['search']);
					$f->SetDOMID('content-search-form');
					if (empty($_getvars['ctg']) && empty($_getvars['showall']) && empty($_getvars['search']))
						$f->SetStyleGlobal('display:none;');
					$ui->Add($f);
					$b=new TButtons();
					$b->Button($lang['common']['add'], 'index.php?m=content&d=add&ctg='.intval($_getvars['ctg']));
					$b->Button($lang['common']['add'].' ('.$lang['common']['html'].')', 'index.php?m=content&d=add&exteditor=off&ctg='.intval($_getvars['ctg']));
					$b->AddToggle('searchswitch', $lang['search'], 'content-search-form');
					if (!empty($_getvars['showall']))
						{
							$limit=abs(intval(sm_settings('admin_items_by_page')));
							$offset=abs(intval($_getvars['from']));
						}
					$ctg_id = intval($_getvars['ctg']);
					sm_title($lang['list_content']);
					add_path_modules();
					add_path($lang['module_content_name'], "index.php?m=content&d=admin");
					add_path($lang['list_content'], "index.php?m=content&d=list");
					$sql = "SELECT ".$tableprefix."content.*, ".$tableprefix."filesystem.* FROM ".$tableprefix."content LEFT JOIN ".$tableprefix."filesystem ON ".$tableprefix."content.filename_content=".$tableprefix."filesystem.id_fs";
					$sort = 0;
					if (!empty($ctg_id))
						{
							$sql .= " WHERE id_category_c = '$ctg_id'";
							for ($i = 0; $i < count($m['ctg']); $i++)
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
					if (!empty($_getvars['showall']))
						$sql .= " LIMIT ".intval($limit)." OFFSET ".intval($offset);
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['common']['title'], '100%');
					$t->AddEdit();
					$t->AddCol('html', '', '16', $lang['common']['edit'].' ('.$lang['common']['html'].')', '', 'edit_html.gif');
					$t->AddDelete();
					$t->AddCol('stick', '', '16', $lang["set_as_block"], '', 'stick.gif');
					$t->AddMenuInsert();
					if ($sort == 2 || $sort == 3)
						{
							$t->AddCol('up', '', '16', $lang['up'], '', 'up.gif');
							$t->AddCol('down', '', '16', $lang['down'], '', 'down.gif');
						}
					$items = getsqlarray($sql);
					for ($i = 0; $i<count($items); $i++)
						{
							$t->Label('title', $items[$i]['title_content']);
							$items[$i]['url']=sm_fs_url('index.php?m=content&d=view&cid='.$items[$i]['id_content']);
							$t->URL('title', $items[$i]['url'], true);
							$t->URL('edit', 'index.php?m=content&d=edit&cid='.$items[$i]['id_content'].'&returnto='.urlencode(sm_this_url()));
							$t->URL('html', 'index.php?m=content&d=edit&cid='.$items[$i]['id_content'].'&exteditor=off'.'&returnto='.urlencode(sm_this_url()));
							if ($items[$i]['id_content'] != 1)
								$t->URL('delete', 'index.php?m=content&d=postdelete&cid='.$items[$i]['id_content'].'&ctg='.$items[$i]['id_category_c'].'&returnto='.urlencode(sm_this_url()));
							$t->URL('tomenu', sm_tomenuurl($items[$i]['title_content'], $items[$i]['url'], sm_this_url()));
							$t->URL('stick', sm_addblockurl($items[$i]['title_content'], 'content', $items[$i]['id_content'], 'view', 'index.php?m=content&d=edit&cid='.$items[$i]['id_content']));
							if ($sort == 2 || $sort == 3)
								{
									if ($i>0)
										$t->URL('up', 'index.php?m=content&d=exchange&id1='.$items[$i]['id_content'].'&id2='.$items[$i-1]['id_content'].'&ctg='.$ctg_id.'&showall='.$showall);
									if ($i+1<count($items))
										$t->URL('down', 'index.php?m=content&d=exchange&id1='.$items[$i]['id_content'].'&id2='.$items[$i+1]['id_content'].'&ctg='.$ctg_id.'&showall='.$showall);
								}
							$t->NewRow();
						}
					$ui->Add($b);
					$ui->Add($t);
					$ui->Add($b);
					if (!empty($_getvars['showall']))
						{
							$sql = "SELECT count(*) FROM ".$tableprefix."content";
							if (!empty($ctg_id)) $sql .= " WHERE id_category_c = ".intval($ctg_id);
							$ui->AddPagebarParams(intval(getsqlfield($sql)), $limit, $offset);
						}
					$ui->Output(true);
				}
			if (sm_action('exchange'))
				{
					$id1 = intval($_getvars['id1']);
					$id2 = intval($_getvars['id2']);
					$pr1 = getsqlfield("SELECT priority_content FROM ".$tableprefix."content WHERE id_content=".intval($id1));
					$pr2 = getsqlfield("SELECT priority_content FROM ".$tableprefix."content WHERE id_content=".intval($id2));
					if (!empty($pr1) || !empty($pr2))
						{
							execsql("UPDATE ".$tableprefix."content SET priority_content=".intval($pr1)." WHERE id_content=".intval($id2));
							execsql("UPDATE ".$tableprefix."content SET priority_content=".intval($pr2)." WHERE id_content=".intval($id1));
						}
					if (!empty($_getvars['returnto']))
						sm_redirect($_getvars['returnto']);
					else
						sm_redirect('index.php?m=content&d=list&ctg='.(intval($_getvars['ctg'])).'&showall='.(intval($_getvars['showall'])));
				}
		}
