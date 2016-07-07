<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.12
	//#revision 2016-07-07
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] == 3)
		{
			if (sm_action('admin'))
				{
					sm_title($lang['control_panel'].' - '.$lang['module_news']['module_news_name']);
					sm_extcore();
					add_path_modules();
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$nav=new TNavigation();
					$nav->AddItem($lang['common']['add'], 'index.php?m=news&d=add');
					$nav->AddItem(sprintf('%s (%s)', $lang['common']['add'], $lang['common']['html']), 'index.php?m=news&d=add&exteditor=off');
					$nav->AddItem($lang['list_news'], 'index.php?m=news&d=list');
					$nav->AddItem($lang['common']['categories'], 'index.php?m=news&d=listctg');
					$nav->AddItem($lang['add_category'], 'index.php?m=news&d=addctg');
					$nav->AddItem(sprintf('%s "%s"', $lang['set_as_block'], $lang['short_news_block']), 'index.php?m=blocks&d=add&b=news&id=1&c='.urlencode($lang['short_news_block']));
					$nav->AddItem(sprintf('%s - %s', $lang['add_to_menu'], $lang['news']), sm_tomenuurl($lang['news'], sm_fs_url('index.php?m=news&d=listnews')));
					$ui->Add($nav);
					$ui->Output(true);
				}
			if (sm_actionpost('postaddctg', 'posteditctg'))
				{
					sm_extcore();
					if (empty($sm['p']['title_category']))
						{
							$error_message=$lang['messages']['fill_required_fields'];
						}
					elseif (!empty($sm['p']['url']))
						{
							if (sm_action('postaddctg'))
								{
									if (sm_fs_exists($sm['p']['url']))
										$error_message=$lang['messages']['seo_url_exists'];
								}
						}
					if (empty($error_message))
						{
							$groups=get_groups_list();
							$groupsenabled=Array();
							for ($i = 0; $i < count($groups); $i++)
								{
									if (!empty($sm['p']['group_'.$groups[$i]['id']]))
										$groupsenabled[]=$groups[$i]['id'];
								}
							$q = new TQuery($sm['t'].'categories_news');
							$q->Add('title_category', dbescape($sm['p']['title_category']));
							$q->Add('groups_modify', dbescape(create_groups_str($groupsenabled)));
							$q->Add('no_alike_news', empty($sm['p']['no_alike_news'])?0:1);
							if (sm_actionpost('postaddctg'))
								$ctgid=$q->Insert();
							else
								{
									$ctgid = intval($sm['g']['ctgid']);
									$q->Update('id_category', intval($sm['g']['ctgid']));
								}
							if (!empty($sm['p']['url']))
								sm_fs_update($sm['p']['title_category'], 'index.php?m=news&d=listnews&ctg='.$ctgid, $sm['p']['url']);
							if (sm_action('postadd'))
								sm_notify($lang['add_content_category_successful']);
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								sm_redirect('index.php?m=news&d=listctg');
							sm_event('postaddctgnews', Array($ctgid));
						}
					if (!empty($error_message))
						sm_set_action(Array('postaddctg'=>'addctg', 'posteditctg'=>'editctg'));
				}
			if (sm_action('addctg', 'editctg'))
				{
					$m['groups_list'] = get_groups_list();
					if (sm_action('editctg'))
						sm_title($lang['edit_category']);
					else
						sm_title($lang['add_category']);
					add_path_modules();
					add_path($lang['module_news']['module_news_name'], 'index.php?m=news&d=admin');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.form');
					$ui = new TInterface();
					if (!empty($error_message))
						$ui->NotificationError($error_message);
					if (sm_action('editctg'))
						$f = new TForm('index.php?m='.sm_current_module().'&d=posteditctg&ctgid='.intval($sm['g']['ctgid']));
					else
						$f = new TForm('index.php?m='.sm_current_module().'&d=postaddctg');
					$f->Separator($lang['common']['general']);
					$f->AddText('title_category', $lang['caption_category'], true)
						->SetFocus();
					$f->AddText('url', $lang['common']['url'])
						->WithTooltip($lang['common']['leave_empty_for_default']);
					if (intval(sm_settings('allow_alike_news'))==1)
						{
							$f->Separator($lang['common']['extended_parameters']);
							$f->AddCheckbox('no_alike_news', $lang['module_news']['dont_show_alike_news']);
						}
					$groups=get_groups_list();
					if (count($groups)>0)
						{
							$f->Separator($lang['common']['groups_can_modify']);
							for ($i = 0; $i < count($groups); $i++)
								{
									$f->AddCheckbox('group_'.$groups[$i]['id'], $groups[$i]['title']);
								}
						}
					if (sm_action('editctg'))
						{
							$info=TQuery::ForTable($sm['t'].'categories_news')
								->AddWhere('id_category', intval($sm['g']['ctgid']))
								->Get();
							$f->LoadValuesArray($info);
							if ($url=sm_fs_url('index.php?m=news&d=listnews&ctg='.$info['id_category'], true))
								$f->SetValue('url', $url);
							$selected_groups=get_array_groups($info['groups_modify']);
							for ($i = 0; $i < count($selected_groups); $i++)
								{
									$f->SetValue('group_'.$selected_groups[$i], 1);
								}
						}
					if (!empty($sm['p']))
						$f->LoadAllValues($sm['p']);
					$ui->Output(true);
					$ui->Add($f);
				}
			if (sm_action('postdeletectg'))
				{
					$id_ctg = intval($_getvars['ctgid']);
					$sql = "SELECT * FROM ".$tableprefix."categories_news WHERE id_category='".$id_ctg."'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$fname = $row->filename_category;
						}
					sm_extcore();
					sm_saferemove('index.php?m=news&d=listnews&ctg='.$id_ctg);
					if ($fname != 0)
						{
							delete_filesystem($fname);
						}
					$sql = "DELETE FROM ".$tableprefix."categories_news WHERE id_category='$id_ctg' AND id_category<>1";
					$result = execsql($sql);
					$q=new TQuery($sm['t'].'news');
					$q->Add('id_category_n', 1);
					$q->Update('id_category_n', intval($id_ctg));
					sm_notify($lang['delete_content_category_successful']);
					sm_redirect('index.php?m=news&d=listctg');
					sm_event('postdeletectgnews', array($id_ctg));
				}
			if (sm_action('listctg'))
				{
					sm_extcore();
					sm_title($lang['news'].' - '.$lang['common']['categories']);
					add_path_modules();
					add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
					add_path_current();
					$sql = "SELECT * FROM ".$tableprefix."categories_news ORDER BY title_category";
					$result = execsql($sql);
					sm_use('admintable');
					sm_use('admininterface');
					sm_use('adminbuttons');
					$ui = new TInterface();
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['common']['title'], '100%');
					$t->AddCol('search', '', '16', $lang['search'], '', 'search.gif');
					$t->AddEdit();
					$t->AddDelete();
					$t->AddCol('stick', '', '16', $lang["set_as_block"], '', 'stick.gif');
					$t->AddMenuInsert();
					$i = 0;
					while ($row = database_fetch_assoc($result))
						{
							$t->Label('title', $row['title_category']);
							$url = sm_fs_url('index.php?m=news&d=listnews&ctg='.$row['id_category']);
							$t->URL('title', $url);
							$t->URL('search', 'index.php?m=news&d=list&ctg='.$row['id_category']);
							if ($row['id_category'] != 1)
								$t->URL('delete', 'index.php?m=news&d=postdeletectg&ctgid='.$row['id_category']);
							$t->URL('edit', 'index.php?m=news&d=editctg&ctgid='.$row['id_category']);
							$t->URL('tomenu', sm_tomenuurl($row['title_category'], $url, sm_this_url()));
							$t->URL('stick', 'index.php?m=blocks&d=add&b=news&id='.$row['id_category'].'&db=shortnews&c='.$row['title_category']);
							$t->NewRow();
							$i++;
						}
					$b=new TButtons();
					$b->AddButton('add', $lang['add_category'], 'index.php?m=news&d=addctg');
					$ui->AddButtons($b);
					$ui->AddGrid($t);
					$ui->AddButtons($b);
					$ui->Output(true);
				}
			if (sm_action('list'))
				{
					sm_use('ui.interface');
					sm_use('ui.buttons');
					sm_use('ui.grid');
					sm_use('ui.form');
					$ui=new TInterface();
					sm_extcore();
					add_path_modules();
					add_path($lang['module_news']['module_news_name'], 'index.php?m=news&d=admin');
					add_path_current();
					sm_title($lang['list_news']);
					$b=new TButtons();
					$b->Button($lang['common']['add'], 'index.php?m=news&d=add&ctg='.$sm['g']['ctg']);
					$b->Button(sprintf('%s (%s)', $lang['common']['add'], $lang['common']['html']), 'index.php?m=news&d=add&ctg='.$sm['g']['ctg'].'&exteditor=off');
					$b->AddToggle('btnsearch', $lang['search'], 'admin-search-form');
					$limit = abs(intval($_settings['admin_items_by_page']));
					$offset = abs(intval($_getvars['from']));
					$f=new TForm('index.php');
					$f->SetMethodGet();
					$f->AddHidden('m', 'news');
					$f->AddHidden('d', 'list');
					$q=new TQuery($sm['t'].'categories_news');
					$q->OrderBy('title_category');
					$q->Select();
					$f->AddSelectVL('ctg', $lang['common']['category'], $q->ColumnValues('id_category'), $q->ColumnValues('title_category'));
					$f->SelectAddBeginVL('ctg', '', $lang['all_categories']);
					$f->SetValue('ctg', $sm['g']['ctg']);
					$f->SaveButton($lang['search']);
					$ui->div_open('admin-search-form', '', empty($sm['g']['ctg'])?'display:none;':'');
					$ui->Add($f);
					$ui->div_close();
					unset($q);
					$q=new TQuery($sm['t']."news");
					if (!empty($sm['g']['ctg']))
						$q->Add('id_category_n', intval($sm['g']['ctg']));
					$q->OrderBy('date_news DESC');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					$t=new TGrid('edit');
					$t->AddCol('date', $lang['common']['date'], '10%');
					$t->AddCol('title', $lang['common']['title'], '90%');
					$t->AddEdit();
					$t->AddCol('html', '', '16', $lang['common']['edit'].' ('.$lang['common']['html'].')', '', 'edit_html.gif');
					$t->AddDelete();
					$t->AddCol('stick', '', '16', $lang["set_as_block"], '', 'stick.gif');
					$t->AddMenuInsert();
					$result = execsql($sql);
					for ($i = 0; $i<count($q->items); $i++)
						{
							if (intval(sm_settings('news_use_time'))==1)
								{
									if (strcmp(date('Y-m-d', $q->items[$i]['date_news']), date('Y-m-d', time()))==0)
										{
											$t->Label('date', strftime($lang['timemask'], $q->items[$i]['date_news']));
											$t->Hint('date', strftime($lang['datemask'], $q->items[$i]['date_news']));
										}
									else
										{
											$t->Label('date', strftime($lang['datemask'], $q->items[$i]['date_news']));
											$t->Hint('date', strftime($lang['timemask'], $q->items[$i]['date_news']));
										}
								}
							else
								$t->Label('date', strftime($lang['datemask'], $q->items[$i]['date_news']));
							$t->Label('title', $q->items[$i]['title_news']);
							$t->Hint('title', $q->items[$i]['title_news']);
							if (empty($q->items[$i]['preview_news']))
								$t->Hint('title', htmlescape(cut_str_by_word(nl2br(strip_tags($q->items[$i]['text_news'])), 100, '...')));
							else
								$t->Hint('title', htmlescape(cut_str_by_word(nl2br(strip_tags($q->items[$i]['preview_news'])), 100, '...')));
							$url=sm_fs_url('index.php?m=news&d=view&nid='.$q->items[$i]['id_news']);
							$t->URL('title', $url, true);
							$t->URL('date', $url, true);
							$t->URL('edit', 'index.php?m=news&d=edit&nid='.$q->items[$i]['id_news']);
							$t->URL('html', 'index.php?m=news&d=edit&nid='.$q->items[$i]['id_news'].'&exteditor=off');
							$t->URL('delete', 'index.php?m=news&d=postdelete&nid='.$q->items[$i]['id_news'].'&ctg='.$q->items[$i]['id_category_n']);
							$t->URL('tomenu', sm_tomenuurl(!empty($q->items[$i]['title_news'])?$q->items[$i]['title_news']:strftime($lang["datemask"], $q->items[$i]['date_news']), $url, sm_this_url()));
							$t->URL('stick', 'index.php?m=blocks&d=add&b=news&id='.$q->items[$i]['id_news'].'&db=view&c='.(!empty($q->items[$i]['title_news'])?$q->items[$i]['title_news']:strftime($lang["datemask"], $q->items[$i]['date_news'])));
							$t->NewRow();
						}
					if ($b->Count()>0)
						$ui->Add($b);
					$ui->Add($t);
					if ($b->Count()>0)
						$ui->Add($b);
					$ui->AddPagebarParams($q->TotalCount(), $limit, $offset);
					$ui->Output(true);
				}
		}

?>