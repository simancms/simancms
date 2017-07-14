<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.14
	//#revision 2017-07-14
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] >= 3)
		{
			sm_default_action('view');

			if (sm_action('add'))
				{
					$m["module"] = 'blocks';
					sm_title($lang['static_blocks'].': '.$lang['common']['add']);
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['static_blocks'], "index.php?m=blocks");
					add_path_current();
					$m["id"] = $_getvars['id'];
					$m["block"] = $_getvars['b'];
					$m["doing"] = $_getvars['db'];
					$m["caption_block"] = $_getvars['c'];
					$sql = "SELECT * FROM ".$tableprefix."modules ORDER BY module_name='content' ASC";
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['show_on'][$i]['caption'] = $lang['module'].': '.$row->module_title;
							$m['show_on'][$i]['value'] = $row->module_name.'|0';
							$i++;
						}
					$listeners = nllistToArray(sm_settings('autoload_modules'));
					for ($i = 0; $i < count($listeners); $i++)
						{
							$blockfn = 'siman_block_items_'.$listeners[$i];
							if (function_exists($blockfn))
								{
									$tmparr = call_user_func($blockfn, $m);
									if (is_array($tmparr))
										$m['show_on'] = array_merge($m['show_on'], $tmparr);
								}
						}
					$m['groups_all'] = get_groups_list();
				}
			if (sm_action('edit'))
				{
					$m["module"] = 'blocks';
					sm_title($lang['static_blocks'].': '.$lang['common']['edit']);
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['static_blocks'], "index.php?m=blocks");
					add_path_current();
					$id_block = intval($_getvars["id"]);
					$sql = "SELECT * FROM ".$tableprefix."blocks WHERE id_block='$id_block'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$m["id"] = $row->id_block;
							$m["panel_block"] = $row->panel_block;
							$m["pos_block"] = $row->position_block;
							$m["caption_block"] = $row->caption_block;
							$m["level_block"] = $row->level;
							$m["show_on_module_block"] = $row->show_on_module;
							$m["show_on_ctg_block"] = $row->show_on_ctg;
							$m["dont_show_modif"] = $row->dont_show_modif;
							$m["no_borders"] = $row->no_borders;
							$m["rewrite_title"] = $row->rewrite_title;
							$m["block_groups_sel"] = get_array_groups($row->groups_view);
							$m["thislevelonly"] = $row->thislevelonly;
							$m["show_on_device"] = $row->show_on_device;
							$m["show_on_viewids"] = $row->show_on_viewids;
						}
					$m["show_on_all"] = 1;
					$sql = "SELECT * FROM ".$tableprefix."modules ORDER BY module_name='content' ASC";
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['show_on'][$i]['caption'] = $lang['module'].': '.$row->module_title;
							$m['show_on'][$i]['value'] = $row->module_name.'|0';
							if (strcmp($m["show_on_module_block"], $row->module_name) == 0 && $m["show_on_ctg_block"] == 0)
								$m['show_on'][$i]['selected'] = 1;
							$i++;
						}
					$listeners = nllistToArray(sm_settings('autoload_modules'));
					for ($i = 0; $i < count($listeners); $i++)
						{
							$blockfn = 'siman_block_items_'.$listeners[$i];
							if (function_exists($blockfn))
								{
									$tmparr = call_user_func($blockfn, $m);
									if (is_array($tmparr))
										$m['show_on'] = array_merge($m['show_on'], $tmparr);
								}
						}
					$m['groups_all'] = get_groups_list();
				}
			if (sm_action('postedit'))
				{
					sm_use('smblock');
					$block=new SMBlock(intval($_postvars['p_id']));
					if ($block->Exists())
						{
							$old_panel = $block->Panel();
							$block->SetCaption($_postvars['p_caption']);
							$block->SetLevel($_postvars['p_level']);
							$block->SetDontShowModifier(!empty($_postvars['p_dont_show']));
							$block->SetNoBorders(!empty($_postvars['p_no_borders']));
							$block->SetRewriteTitleTo($_postvars['p_rewrite_title']);
							$block->SetShowOnDevice($_postvars['show_on_device']);
							$block->SetThisLevelOnlyValue(intval($_postvars['p_thislevelonly']));
							$block->SetGroupsView(create_groups_str($_postvars['p_groups']));
							$block->SetShowOnViewIDs($_postvars['show_on_viewids']);
							$arr_show_on = explode('|', $_postvars['p_show_on']);
							$block->SetShowOnModule($arr_show_on[0]);
							$block->SetShowOnAction($arr_show_on[2]);
							$block->SetShowOnCtgID($arr_show_on[1]);
							if ($block->Panel()!=$_postvars['p_panel'])
								{
									$block->MoveToPanel($_postvars['p_panel']);
								}
							if (sm_settings('blocks_use_image') == 1)
								{
									siman_upload_image($block->ID(), 'block');
								}
							sm_notify($lang['messages']['edit_successful']);
							sm_redirect('index.php?m=blocks&d=view');
						}
				}
			if (sm_action('postadd'))
				{
					sm_use('smblock');
					$block=SMBlock::CreateNonVisibleBlock($_postvars['p_panel']);
					$block->SetModuleName($_postvars['p_block']);
					$block->SetActionIDValue($_postvars['p_id']);
					$block->SetCaption($_postvars['p_caption']);
					$block->SetLevel($_postvars['p_level']);
					$block->SetDontShowModifier(!empty($_postvars['p_dont_show']));
					$block->SetActionValue($_postvars['p_doing']);
					$block->SetNoBorders(!empty($_postvars['p_no_borders']));
					$block->SetRewriteTitleTo($_postvars['p_rewrite_title']);
					$block->SetShowOnDevice($_postvars['show_on_device']);
					$block->SetGroupsView(create_groups_str($_postvars['p_groups']));
					$block->SetThisLevelOnlyValue(intval($_postvars['p_thislevelonly']));
					$block->SetShowOnViewIDs($_postvars['show_on_viewids']);
					$arr_show_on = explode('|', $_postvars['p_show_on']);
					$block->SetShowOnModule($arr_show_on[0]);
					$block->SetShowOnAction($arr_show_on[2]);
					$block->SetShowOnCtgID($arr_show_on[1]);
					if (sm_settings('blocks_use_image') == 1)
						{
							siman_upload_image($block->ID(), 'block');
						}
					sm_notify($lang['messages']['add_successful']);
					sm_redirect('index.php?m=blocks&d=view');
				}
			if (sm_action('postdelete'))
				{
					execsql("DELETE FROM ".$tableprefix."blocks  WHERE id_block=".intval($_getvars['id']));
					execsql("UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>=".intval($_getvars['pos'])." AND panel_block='".dbescape($_getvars['pnl'])."'");
					sm_redirect('index.php?m=blocks');
				}
			if (sm_action('up'))
				{
					$block=TQuery::ForTable($tableprefix."blocks")->Add('id_block', intval($_getvars['id']))->Get();
					$block2=TQuery::ForTable($tableprefix."blocks")->Add('panel_block', dbescape($block['panel_block']))->Add('position_block<'.intval($block['position_block']))->OrderBy('position_block DESC')->Get();
					if (!empty($block['position_block']) && !empty($block2['position_block']))
						{
							$q=new TQuery($tableprefix."blocks");
							$q->Add('position_block', intval($block2['position_block']));
							$q->Update('id_block', intval($block['id_block']));
							unset($q);
							$q=new TQuery($tableprefix."blocks");
							$q->Add('position_block', intval($block['position_block']));
							$q->Update('id_block', intval($block2['id_block']));
							unset($q);
						}
					sm_redirect('index.php?m=blocks');
				}
			if (sm_action('down'))
				{
					$block=TQuery::ForTable($tableprefix."blocks")->Add('id_block', intval($_getvars['id']))->Get();
					$block2=TQuery::ForTable($tableprefix."blocks")->Add('panel_block', dbescape($block['panel_block']))->Add('position_block>'.intval($block['position_block']))->OrderBy('position_block ASC')->Get();
					if (!empty($block['position_block']) && !empty($block2['position_block']))
						{
							$q=new TQuery($tableprefix."blocks");
							$q->Add('position_block', intval($block2['position_block']));
							$q->Update('id_block', intval($block['id_block']));
							unset($q);
							$q=new TQuery($tableprefix."blocks");
							$q->Add('position_block', intval($block['position_block']));
							$q->Update('id_block', intval($block2['id_block']));
							unset($q);
						}
					sm_redirect('index.php?m=blocks');
				}
			if (sm_action('view'))
				{
					$m["module"] = 'blocks';
					sm_use('smblock');
					sm_use('admintable');
					sm_use('admininterface');
					sm_use('adminform');
					sm_title($lang['static_blocks']);
					add_path_control();
					add_path($lang['static_blocks'], "index.php?m=blocks");
					$ui = new TInterface();
					$q=new TQuery($sm['t']."blocks");
					$q->Add('panel_block', 'c');
					$q->OrderBy('panel_block, position_block');
					$q->Select();
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['center_panel'], '95%');
					$t->AddCol('open', $lang['common']['open'], '5%');
					$t->AddCol('up', '', '16', $lang['up']);
					$t->AddCol('down', '', '16', $lang['down']);
					$t->AddEdit();
					$t->AddDelete();
					$v=Array(0);
					$l=Array($lang['first']);
					for ($i = 0; $i<$q->Count(); $i++)
						{
							$block=new SMBlock($q->items[$i]);
							if (intval(sm_settings('main_block_position'))==$i)
								{
									$t->SingleLineLabel($lang['module_blocks']['main_block_position']);
									$t->NewRow();
								}
							$v[]=$i+1;
							$l[]=$lang['after'].': '.$block->Caption();
							$t->Label('title', $block->Caption());
							if ($block->HasEditSourceURL())
								{
									$t->Label('open', $lang['common']['open']);
									$t->URL('open', $block->EditSourceURL());
								}
							else
								$t->Label('open', '-');
							$t->URL('edit', 'index.php?m=blocks&d=edit&id='.$block->ID());
							if ($i>0)
								{
									$t->URL('up', 'index.php?m=blocks&d=up&id='.$block->ID());
									$t->Image('up', 'up.gif');
								}
							if ($i+1<$q->Count())
								{
									$t->URL('down', 'index.php?m=blocks&d=down&id='.$block->ID());
									$t->Image('down', 'down.gif');
								}
							$t->URL('delete', 'index.php?m=blocks&d=postdelete&id='.$block->ID().'&pos='.$block->Position().'&pnl='.$block->Panel());
							$t->NewRow();
							unset($block);
						}
					if (intval(sm_settings('main_block_position')) >= $q->Count())
						{
							$t->SingleLineLabel($lang['module_blocks']['main_block_position']);
							$t->NewRow();
							$_settings['main_block_position'] = $q->Count();
						}
					$f = new TForm('index.php?m=blocks&d=setmain');
					$f->AddSelectVL('p_mainpos', $lang['module_blocks']['main_block_position'], $v, $l);
					$f->SetValue('p_mainpos', sm_settings('main_block_position'));
					$ui->AddForm($f);
					unset($f);
					$ui->br();
					$ui->AddGrid($t);
					unset($t);
					for ($panel = 1; $panel < intval(sm_settings('sidepanel_count')) + 1; $panel++)
						{
							$t=new TGrid('edit');
							$t->AddCol('title', $lang['panel'].' '.$panel, '95%');
							$t->AddCol('open', $lang['common']['open'], '5%');
							$t->AddCol('up', '', '16', $lang['up']);
							$t->AddCol('down', '', '16', $lang['down']);
							$t->AddEdit();
							$t->AddDelete();
							$q=new TQuery($sm['t']."blocks");
							$q->Add('panel_block', intval($panel));
							$q->OrderBy('panel_block, position_block');
							$q->Select();
							for ($i = 0; $i<$q->Count(); $i++)
								{
									$block=new SMBlock($q->items[$i]);
									$t->Label('title', $block->Caption());
									if ($block->HasEditSourceURL())
										{
											$t->Label('open', $lang['common']['open']);
											$t->URL('open', $block->EditSourceURL());
										}
									else
										$t->Label('open', '-');
									$t->URL('edit', 'index.php?m=blocks&d=edit&id='.$block->ID());
									if ($i>0)
										{
											$t->URL('up', 'index.php?m=blocks&d=up&id='.$block->ID().'&pos='.$block->Position().'&pnl='.$block->Panel());
											$t->Image('up', 'up.gif');
										}
									if ($i+1<$q->Count())
										{
											$t->URL('down', 'index.php?m=blocks&d=down&id='.$block->ID().'&pos='.$block->Position().'&pnl='.$block->Panel());
											$t->Image('down', 'down.gif');
										}
									$t->URL('delete', 'index.php?m=blocks&d=postdelete&id='.$block->ID().'&pos='.$block->Position().'&pnl='.$block->Panel());
									$t->NewRow();
									unset($block);
								}
							if ($t->RowCount()==0)
								$t->SingleLineLabel($lang['messages']['nothing_found']);
							$ui->br();
							$ui->AddGrid($t);
							unset($t);
						}
					$ui->Output(true);
				}
			if (sm_action('setmain'))
				{
					sm_update_settings('main_block_position', intval($_postvars['p_mainpos']));
					sm_redirect('index.php?m=blocks');
				}
		}
