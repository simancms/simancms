<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.5
	//#revision 2013-10-17
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($userinfo['level'] >= 3)
		{
			if (empty($m["mode"])) $m["mode"] = 'view';

			if (sm_action('add'))
				{
					$m["module"] = 'blocks';
					$m["title"] = $lang['static_blocks'];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['static_blocks'], "index.php?m=blocks");
					$m["id"] = $_getvars['id'];
					$m["block"] = $_getvars['b'];
					$m["doing"] = $_getvars['db'];
					$m["caption_block"] = $_getvars['c'];
					$sql = "SELECT * FROM ".$tableprefix."modules ORDER BY module_name='content' ASC";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['show_on'][$i]['caption'] = $lang['module'].': '.$row->module_title;
							$m['show_on'][$i]['value'] = $row->module_name.'|0';
							$i++;
						}
					$listeners = nllistToArray($_settings['autoload_modules']);
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
					$m["title"] = $lang['static_blocks'];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['static_blocks'], "index.php?m=blocks");
					$id_block = $_getvars["id"];
					$sql = "SELECT * FROM ".$tableprefix."blocks WHERE id_block='$id_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
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
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['show_on'][$i]['caption'] = $lang['module'].': '.$row->module_title;
							$m['show_on'][$i]['value'] = $row->module_name.'|0';
							if (strcmp($m["show_on_module_block"], $row->module_name) == 0 && $m["show_on_ctg_block"] == 0)
								$m['show_on'][$i]['selected'] = 1;
							$i++;
						}
					$listeners = nllistToArray($_settings['autoload_modules']);
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
					$id_block = intval($_postvars["p_id"]);
					$old_panel = $_postvars["p_old_pnl"];
					$old_position = $_postvars["p_old_pos"];
					$caption_block = dbescape($_postvars["p_caption"]);
					$panel_block = $_postvars["p_panel"];
					$level = $_postvars["p_level"];
					$arr_show_on = explode('|', $_postvars['p_show_on']);
					$module_block = $arr_show_on[0];
					$show_doing_block = $arr_show_on[2];
					$ctg_block = $arr_show_on[1];
					$no_borders = $_postvars['p_no_borders'];
					$dont_show_modif = $_postvars['p_dont_show'];
					$rewrite_title = $_postvars["p_rewrite_title"];
					$groups_view = create_groups_str($_postvars['p_groups']);
					$thislevelonly = intval($_postvars['p_thislevelonly']);
					$show_on_device = $_postvars['show_on_device'];
					$show_on_viewids = $_postvars['show_on_viewids'];
					if ($panel_block != $old_panel)
						{
							$sql = "SELECT max(position_block) FROM ".$tableprefix."blocks WHERE panel_block='$panel_block'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
							$pos_block = 0;
							while ($row = database_fetch_row($result))
								{
									$pos_block = $row[0];
								}
							$pos_block++;
							$sql = "UPDATE ".$tableprefix."blocks SET level = '$level', panel_block='$panel_block', position_block='$pos_block', caption_block='$caption_block', show_on_module='$module_block', show_on_doing='$show_doing_block', show_on_ctg='$ctg_block', no_borders='$no_borders', dont_show_modif ='$dont_show_modif', rewrite_title = '$rewrite_title', groups_view = '$groups_view', thislevelonly='$thislevelonly', show_on_device='$show_on_device', show_on_viewids='$show_on_viewids' WHERE id_block = '$id_block'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
							$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>='".($old_position)."' AND panel_block='$old_panel'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					else
						{
							$sql = "UPDATE ".$tableprefix."blocks SET level = '$level', caption_block = '$caption_block', show_on_module='$module_block', show_on_doing='$show_doing_block', show_on_ctg='$ctg_block', no_borders='$no_borders', dont_show_modif ='$dont_show_modif', rewrite_title = '$rewrite_title', groups_view = '$groups_view', thislevelonly='$thislevelonly', show_on_device='$show_on_device', show_on_viewids='$show_on_viewids'  WHERE id_block = '$id_block'";
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					if ($_settings['blocks_use_image'] == 1)
						{
							siman_upload_image($id_block, 'block');
						}
					sm_redirect('index.php?m=blocks&d=view');
				}
			if (sm_action('postadd'))
				{
					$id_block = $_postvars["p_id"];
					$name_block = $_postvars["p_block"];
					$caption_block = dbescape($_postvars["p_caption"]);
					$panel_block = $_postvars["p_panel"];
					$level = $_postvars["p_level"];
					$arr_show_on = explode('|', $_postvars['p_show_on']);
					$dont_show_modif = $_postvars["p_dont_show"];
					$doing_block = $_postvars["p_doing"];
					$rewrite_title = $_postvars["p_rewrite_title"];
					$module_block = $arr_show_on[0];
					$show_doing_block = $arr_show_on[2];
					$ctg_block = $arr_show_on[1];
					$no_borders = $_postvars['p_no_borders'];
					$show_on_device = $_postvars['show_on_device'];
					$groups_view = create_groups_str($_postvars['p_groups']);
					$thislevelonly = intval($_postvars['p_thislevelonly']);
					$show_on_viewids = $_postvars['show_on_viewids'];
					$sql = "SELECT max(position_block) FROM ".$tableprefix."blocks WHERE panel_block='$panel_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$pos_block = 0;
					while ($row = database_fetch_row($result))
						{
							$pos_block = $row[0];
						}
					$pos_block++;
					$sql = "INSERT INTO ".$tableprefix."blocks (level, panel_block, position_block, name_block, caption_block, showed_id, show_on_module, show_on_doing, show_on_ctg, dont_show_modif, doing_block, no_borders, rewrite_title, groups_view, thislevelonly, show_on_device, show_on_viewids) VALUES ('$level', '$panel_block', '$pos_block', '$name_block', '$caption_block', '$id_block', '$module_block', '$show_doing_block', '$ctg_block', '$dont_show_modif', '$doing_block', '$no_borders', '$rewrite_title', '$groups_view', '$thislevelonly', '$show_on_device', '$show_on_viewids')";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					if ($_settings['blocks_use_image'] == 1)
						{
							$id_block = database_insert_id('blocks', $nameDB, $lnkDB);
							siman_upload_image($id_block, 'block');
						}
					sm_redirect('index.php?m=blocks&d=view');
				}
			if (sm_action('postdelete'))
				{
					$m["title"] = $lang['static_blocks'];
					$m["module"] = 'blocks';
					$refresh_url = 'index.php?m=blocks';
					$id_block = $_getvars["id"];
					$pos_block = $_getvars["pos"];
					$panel_block = $_getvars["pnl"];
					$sql = "DELETE FROM ".$tableprefix."blocks  WHERE id_block='$id_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>='".($pos_block)."' AND panel_block='$panel_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
				}
			if (sm_action('up'))
				{
					$m["title"] = $lang['static_blocks'];
					$m["module"] = 'blocks';
					$refresh_url = 'index.php?m=blocks';
					$id_block = $_getvars["id"];
					$pos_block = $_getvars["pos"];
					$panel_block = $_getvars["pnl"];
					$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block+1 WHERE position_block='".($pos_block - 1)."' AND panel_block='$panel_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>'1' AND id_block='$id_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
				}
			if (sm_action('down'))
				{
					$m["title"] = $lang['static_blocks'];
					$m["module"] = 'blocks';
					$refresh_url = 'index.php?m=blocks';
					$id_block = $_getvars["id"];
					$pos_block = $_getvars["pos"];
					$panel_block = $_getvars["pnl"];
					$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block='".($pos_block + 1)."' AND panel_block='$panel_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$sql = "UPDATE ".$tableprefix."blocks SET position_block=position_block+1 WHERE id_block='$id_block'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
				}
			if (sm_action('view'))
				{
					$m["module"] = 'blocks';
					sm_title($lang['static_blocks']);
					add_path_control();
					add_path($lang['static_blocks'], "index.php?m=blocks");
					require_once('includes/admintable.php');
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					$q=new TQuery($sm['t']."blocks");
					$q->Add('panel_block', 'c');
					$q->OrderBy('panel_block, position_block');
					$q->Select();
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['center_panel'], '100%');
					$t->AddCol('up', '', '16', $lang['up'], '', 'up.gif');
					$t->AddCol('down', '', '16', $lang['down'], '', 'down.gif');
					$t->AddEdit();
					$t->AddDelete();
					$v=Array(0);
					$l=Array($lang['first']);
					for ($i = 0; $i<$q->Count(); $i++)
						{
							$v[]=$i+1;
							$l[]=$lang['after'].': '.$q->items[$i]['caption_block'];
							$t->Label('title', $q->items[$i]['caption_block']);
							$t->URL('edit', 'index.php?m=blocks&d=edit&id='.$q->items[$i]['id_block']);
							if ($i>0)
								$t->URL('up', 'index.php?m=blocks&d=up&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
							if ($i+1<$q->Count())
								$t->URL('down', 'index.php?m=blocks&d=down&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
							$t->URL('delete', 'index.php?m=blocks&d=postdelete&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
							$t->NewRow();
						}
					if (intval($_settings['main_block_position']) > $q->Count())
						{
							$_settings['main_block_position'] = $q->Count();
						}
					$f = new TForm('index.php?m=blocks&d=setmain');
					$f->AddSelectVL('p_mainpos', $lang['module_blocks']['main_block_position'], $v, $l);
					$f->SetValue('p_mainpos', $_settings['main_block_position']);
					$ui->AddForm($f);
					unset($f);
					$ui->br();
					$ui->AddGrid($t);
					unset($t);
					for ($panel = 1; $panel < intval($_settings['sidepanel_count']) + 1; $panel++)
						{
							$t=new TGrid('edit');
							$t->AddCol('title', $lang['panel'].' '.$panel, '100%');
							$t->AddCol('up', '', '16', $lang['up'], '', 'up.gif');
							$t->AddCol('down', '', '16', $lang['down'], '', 'down.gif');
							$t->AddEdit();
							$t->AddDelete();
							$q=new TQuery($sm['t']."blocks");
							$q->Add('panel_block', intval($panel));
							$q->OrderBy('panel_block, position_block');
							$q->Select();
							for ($i = 0; $i<$q->Count(); $i++)
								{
									$t->Label('title', $q->items[$i]['caption_block']);
									$t->URL('edit', 'index.php?m=blocks&d=edit&id='.$q->items[$i]['id_block']);
									if ($i>0)
										$t->URL('up', 'index.php?m=blocks&d=up&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
									if ($i+1<$q->Count())
										$t->URL('down', 'index.php?m=blocks&d=down&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
									$t->URL('delete', 'index.php?m=blocks&d=postdelete&id='.$q->items[$i]['id_block'].'&pos='.$q->items[$i]['position_block'].'&pnl='.$q->items[$i]['panel_block']);
									$t->NewRow();
								}
							$ui->br();
							$ui->AddGrid($t);
							unset($t);
						}
					$ui->Output(true);
				}
			if (sm_action('setmain'))
				{
					$m["title"] = $lang['static_blocks'];
					$m["module"] = 'blocks';
					$refresh_url = 'index.php?m=blocks';
					$sql = "UPDATE ".$tableprefix."settings SET value_settings='".intval($_postvars['p_mainpos'])."' WHERE name_settings='main_block_position' AND mode='default'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
				}
		}

?>