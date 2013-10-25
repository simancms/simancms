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

	if (!defined("MENU_ADMINPART_FUNCTIONS_DEFINED"))
		{
			function siman_delete_menu_line($line_id)
				{
					global $tableprefix, $_settings;
					$sql = "SELECT id_ml FROM ".$tableprefix."menu_lines WHERE submenu_from=".intval($line_id);
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							siman_delete_menu_line($row->id_ml);
						}
					$sql = "DELETE FROM ".$tableprefix."menu_lines WHERE id_ml=".intval($line_id);
					if ($_settings['menuitems_use_image'] == 1)
						{
							if (file_exists('./files/img/menuitem'.intval($line_id).'.jpg'))
								unlink('./files/img/menuitem'.intval($line_id).'.jpg');
						}
					execsql($sql);
				}

			define("MENU_ADMINPART_FUNCTIONS_DEFINED", 1);
		}


	if ($userinfo['level'] == 3)
		{
			if (sm_action('admin'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["settings"];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				}
			if (sm_action('addouter'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					$f = new TForm('index.php?m=menu&d=prepareaddline&returnto='.urlencode($_getvars['returnto']));
					$f->AddText('p_caption', $lang['caption']);
					$f->AddText('p_url', $lang['url']);
					unset($values);
					unset($labels);
					$q=new TQuery($tableprefix."menus");
					$q->OrderBy('if (id_menu_m=1, 1, 0), caption_m');
					$q->Select();
					for ($i = 0; $i < $q->Count(); $i++)
						{
							$values[]=$q->items[$i]['id_menu_m'].'|0';
							$labels[]='['.$q->items[$i]['caption_m'].']';
							$lines = siman_load_menu($q->items[$i]['id_menu_m']);
							for ($j = 0; $j < count($lines); $j++)
								{
									$prefix=' - ';
									for ($k = 1; $k < $lines[$j]['level']; $k++)
										$prefix.=' - ';
									$values[]=$q->items[$i]['id_menu_m'].'|'.$lines[$j]['add_param'];
									$labels[]=$prefix.$lines[$j]['caption'];
								}
						}
					$f->AddSelectVL('p_mainmenu', $lang['module_menu']['add_to_menu'], $values, $labels);
					$f->LoadValuesArray($_postvars);
					$f->LoadValuesArray($_getvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('p_caption');
				}
			if (sm_action('postadd'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["add_menu"];
					$mcaption = $_postvars["p_caption"];
					$sql = "INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('".dbescape($mcaption)."')";
					$result = execsql($sql);
					if ($_settings['menus_use_image'] == 1)
						{
							$id_menu = database_insert_id('menus', $nameDB, $lnkDB);
							siman_upload_image($id_menu, 'menu');
						}
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['module_menu']['add_menu_line'];
					$_msgbox['msg'] = $lang['you_want_add_line'];
					$_msgbox['yes'] = 'index.php?m=menu&d=addline&mid='.database_insert_id('menus', $nameDB, $lnkDB);
					$_msgbox['no'] = 'index.php?m=menu&d=listmenu';
				}
			if (sm_action('deleteline'))
				{
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['delete_menu_line'];
					$_msgbox['msg'] = $lang['really_want_delete_line'];
					$_msgbox['yes'] = 'index.php?m=menu&d=postdeleteline&mid='.$_getvars['mid'].'&lid='.$_getvars['lid'];
					$_msgbox['no'] = 'index.php?m=menu&d=listlines&mid='.$_getvars['mid'];
				}
			if (sm_action('postdeleteline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["delete_menu_line"];
					$menu_id = $_getvars["mid"];
					$menuline_id = $_getvars["lid"];
					siman_delete_menu_line($menuline_id);
					sm_redirect('index.php?m=menu&d=listlines&mid='.$menu_id);
				}
			if (sm_action('postaddouter'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					$_getvars["mid"] = $_postvars["p_mid"];
					$lposition = 0;
					$m["mode"] = 'postaddline';
				}
			if (sm_action('postaddline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					$lcaption = $_postvars["p_caption"];
					$menu_id = $_getvars["mid"];
					$lurl = $_postvars["p_url"];
					$submenu_from = $_postvars["p_sub"];
					$lposition = $_postvars["p_position"];
					$alt_ml = dbescape($_postvars["p_alt"]);
					$newpage_ml = intval($_postvars["p_newpage"]);
					if ($lposition == 0)
						{
							$sql = "SELECT max(position) FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id' AND submenu_from='$submenu_from'";
							$lposition = 1;
							$result = execsql($sql);
							while ($row = database_fetch_row($result))
								{
									$lposition = $row[0] + 1;
								}
						}
					else
						{
							$sql = "UPDATE ".$tableprefix."menu_lines SET position=position+1 WHERE position >= '$lposition' AND id_menu_ml='$menu_id' AND submenu_from='$submenu_from'";
							$result = execsql($sql);
						}
					$sql = "INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, submenu_from, url, caption_ml, position, alt_ml, newpage_ml) VALUES ('".dbescape($menu_id)."', '".dbescape($submenu_from)."', '".dbescape($lurl)."', '".dbescape($lcaption)."', '".dbescape($lposition)."', '".dbescape($alt_ml)."', '".dbescape($newpage_ml)."')";
					$result = execsql($sql);
					if ($_settings['menuitems_use_image'] == 1)
						{
							$id_ml = database_insert_id('menu_lines', $nameDB, $lnkDB);
							siman_upload_image($id_ml, 'menuitem');
						}
					if (!empty($_getvars['returnto']))
						sm_redirect($_getvars['returnto']);
					else
						sm_redirect('index.php?m=menu&d=listlines&mid='.$menu_id);
				}
			if (sm_action('posteditline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					$menu_id = $_getvars["mid"];
					$menuline_id = $_getvars["lid"];
					$lcaption = $_postvars["p_caption"];
					$lurl = $_postvars["p_url"];
					$lposition = $_postvars["p_position"];
					$partial_select = intval($_postvars["p_partial_select"]);
					$alt_ml = dbescape($_postvars["p_alt"]);
					$attr_ml = dbescape($_postvars["attr_ml"]);
					$newpage_ml = intval($_postvars["p_newpage"]);
					if (empty($lposition))
						{
							//ͳ���� �� ������
						}
					elseif ($lposition == -1)
						{
							$sql = "SELECT max(position) FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id'";
							$lposition = 1;
							$result = execsql($sql);
							while ($row = database_fetch_row($result))
								{
									$lposition = $row[0] + 1;
								}
						}
					else
						{
							$sql = "UPDATE ".$tableprefix."menu_lines SET position=position+1 WHERE position>='$lposition'";
							$result = execsql($sql);
						}
					$sql = "UPDATE ".$tableprefix."menu_lines SET url = '".dbescape($lurl)."', caption_ml = '".dbescape($lcaption)."', partial_select='".dbescape($partial_select)."', alt_ml = '".dbescape($alt_ml)."', attr_ml = '".dbescape($attr_ml)."', newpage_ml = '".dbescape($newpage_ml)."' ";
					if (!empty($lposition))
						{
							$sql .= ", position = '$lposition'";
						}
					$sql .= " WHERE id_ml = '$menuline_id'";
					$result = execsql($sql);
					if ($_settings['menuitems_use_image'] == 1)
						{
							siman_upload_image($menuline_id, 'menuitem');
						}
					sm_redirect('index.php?m=menu&d=listlines&mid='.$menu_id);
				}
			if (sm_action('addline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					$menu_id = $_getvars["mid"];
					$m['idmenu'] = $menu_id;
					$m['menu'] = siman_load_menu($menu_id);
				}
			if (sm_action('prepareaddline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['module_menu']['add_menu_line'];
					$m['menuline']['menu_id'] = substr($_postvars['p_mainmenu'], 0, strpos($_postvars['p_mainmenu'], '|'));
					$m['menuline']['sub_id'] = substr($_postvars['p_mainmenu'], strpos($_postvars['p_mainmenu'], '|') + 1, strlen($_postvars['p_mainmenu']) - strpos($_postvars['p_mainmenu'], '|') - 1);
					$m['menuline']['caption'] = $_postvars['p_caption'];
					$m['menuline']['url'] = $_postvars['p_url'];
					$sql = "SELECT * FROM ".$tableprefix."menu_lines WHERE id_menu_ml='".$m['menuline']['menu_id']."' AND submenu_from='".$m['menuline']['sub_id']."' ORDER BY position";
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['menu'][$i]['id'] = $row->id_ml;
							$m['menu'][$i]['mid'] = $m['addmenu']['id'];
							$m['menu'][$i]['caption'] = $row->caption_ml;
							$m['menu'][$i]['pos'] = $row->position;
							$i++;
						}
				}
			if (sm_action('editline'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["menu"];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
					add_path($lang['list_menus'], "index.php?m=menu&d=listmenu");
					$menu_id = intval($_getvars["mid"]);
					$menuline_id = intval($_getvars["lid"]);
					$submenu_from = intval($_getvars["sid"]);
					if (empty($submenu_from)) $submenu_from = 0;
					$m['idmenu'] = $menu_id;
					$m['idline'] = $menuline_id;
					$sql = "SELECT * FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id' AND submenu_from='$submenu_from' ORDER BY position";
					$result = execsql($sql);
					$i = 0;
					$u = 0;
					while ($row = database_fetch_object($result))
						{
							if ($row->id_ml == $menuline_id)
								{
									$m['captionline'] = $row->caption_ml;
									$m['urlline'] = $row->url;
									$m['posline'] = $row->position;
									$m['partial_select'] = $row->partial_select;
									$m['alt_ml'] = $row->alt_ml;
									$m['attr_ml'] = $row->attr_ml;
									$m['newpage_ml'] = $row->newpage_ml;
									$u = 1;
								}
							else
								{
									if ($u == 1)
										{
											$u = 0;
										}
									else
										{
											$m['menu'][$i]['id'] = $row->id_ml;
											$m['menu'][$i]['mid'] = $menu_id;
											$m['menu'][$i]['caption'] = $row->caption_ml;
											$m['menu'][$i]['pos'] = $row->position;
											$i++;
										}
								}
						}
				}
			if (sm_action('listlines'))
				{
					$m["module"] = 'menu';
					$menu_id = intval($_getvars["mid"]);
					$q = new TQuery($sm['t'].'menus');
					$q->Add('id_menu_m', $menu_id);
					$menuinfo = $q->Get();
					$m["title"] = $lang["menu"].': '.$menuinfo['caption_m'];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
					add_path($lang['list_menus'], "index.php?m=menu&d=listmenu");
					add_path($menuinfo['caption_m'], "index.php?m=menu&d=listlines&mid=".$menu_id);
					$m['idmenu'] = $menu_id;
					$m['menu'] = siman_load_menu($menu_id);
					require_once('includes/admintable.php');
					$m['table']['columns']['title']['caption'] = $lang['common']['title'];
					$m['table']['columns']['title']['width'] = '100%';
					$m['table']['columns']['edit']['caption'] = '';
					$m['table']['columns']['edit']['hint'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_text'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_image'] = 'edit.gif';
					$m['table']['columns']['edit']['width'] = '16';
					$m['table']['columns']['delete']['caption'] = '';
					$m['table']['columns']['delete']['hint'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_text'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_image'] = 'delete.gif';
					$m['table']['columns']['delete']['width'] = '16';
					$m['table']['columns']['delete']['messagebox'] = 1;
					$m['table']['columns']['delete']['messagebox_text'] = addslashes($lang['really_want_delete_line']);
					$m['table']['default_column'] = 'edit';
					for ($i = 0; $i < count($m['menu']); $i++)
						{
							$lev = '';
							for ($j = 1; $j < $m['menu'][$i]['level']; $j++)
								{
									$lev .= '-';
								}
							$m['table']['rows'][$i]['title']['data'] = $lev.$m['menu'][$i]['caption'];
							$m['table']['rows'][$i]['title']['hint'] = $m['menu'][$i]['caption'];
							$m['table']['rows'][$i]['title']['url'] = $m['menu'][$i]['url'];
							$m['table']['rows'][$i]['edit']['url'] = 'index.php?m=menu&d=editline&mid='.$m['menu'][$i]['mid'].'&lid='.$m['menu'][$i]['id'].'&sid='.$m['menu'][$i]['submenu_from'];
							$m['table']['rows'][$i]['delete']['url'] = 'index.php?m=menu&d=postdeleteline&mid='.$m['menu'][$i]['mid'].'&lid='.$m['menu'][$i]['id'];
						}
				}
			if (sm_action('editmenu'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["edit_menu"];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
					$menu_id = $_getvars["mid"];
					$sql = "SELECT * FROM ".$tableprefix."menus WHERE id_menu_m='$menu_id'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$m["id"] = $menu_id;
							$m["caption"] = $row->caption_m;
						}
				}
			if (sm_action('postedit'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang['edit_menu'];
					$menu_id = $_getvars["mid"];
					$mcaption = $_postvars["p_caption"];
					$sql = "UPDATE ".$tableprefix."menus SET caption_m = '$mcaption' WHERE id_menu_m='$menu_id'";
					$result = execsql($sql);
					if ($_settings['menus_use_image'] == 1)
						{
							siman_upload_image($menu_id, 'menu');
						}
					sm_redirect('index.php?m=menu&d=listmenu');
				}
			if (sm_action('deletemenu'))
				{
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['delete_menu'];
					$_msgbox['msg'] = $lang['really_want_delete_menu'];
					$_msgbox['yes'] = 'index.php?m=menu&d=postdeletemenu&mid='.$_getvars['mid'];
					$_msgbox['no'] = 'index.php?m=menu&d=listmenu';
				}
			if (sm_action('postdeletemenu'))
				{
					$m["module"] = 'menu';
					$menu_id = $_getvars["mid"];
					$m["title"] = $lang["delete_menu"];
					$sql = "DELETE FROM ".$tableprefix."menus WHERE id_menu_m='$menu_id'";
					$result = execsql($sql);
					if ($_settings['menuitems_use_image'] == 1)
						{
							if (file_exists('./files/img/menu'.$menu_id.'.jpg'))
								unlink('./files/img/menu'.$menu_id.'.jpg');
						}
					sm_redirect('index.php?m=menu&d=listmenu');
				}
			if (sm_action('add'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["add_menu"];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
				}
			if (sm_action('listmenu'))
				{
					$m["module"] = 'menu';
					$m["title"] = $lang["list_menus"];
					add_path($lang['control_panel'], "index.php?m=admin");
					add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
					add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
					$sql = "SELECT * FROM ".$tableprefix."menus";
					require_once('includes/admintable.php');
					$m['table']['columns']['title']['caption'] = $lang['common']['title'];
					$m['table']['columns']['title']['width'] = '100%';
					$m['table']['columns']['edit']['caption'] = '';
					$m['table']['columns']['edit']['hint'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_text'] = $lang['common']['edit'];
					$m['table']['columns']['edit']['replace_image'] = 'edit.gif';
					$m['table']['columns']['edit']['width'] = '16';
					$m['table']['columns']['delete']['caption'] = '';
					$m['table']['columns']['delete']['hint'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_text'] = $lang['common']['delete'];
					$m['table']['columns']['delete']['replace_image'] = 'delete.gif';
					$m['table']['columns']['delete']['width'] = '16';
					$m['table']['columns']['delete']['messagebox'] = 1;
					$m['table']['columns']['delete']['messagebox_text'] = addslashes($lang['really_want_delete_menu']);
					$m['table']['columns']['stick']['caption'] = '';
					$m['table']['columns']['stick']['hint'] = $lang['set_as_block_random_text'];
					$m['table']['columns']['stick']['replace_text'] = $lang['common']['stick'];
					$m['table']['columns']['stick']['replace_image'] = 'stick.gif';
					$m['table']['default_column'] = 'edit';
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$m['table']['rows'][$i]['title']['data'] = $row->caption_m;
							$m['table']['rows'][$i]['title']['hint'] = $row->caption_m;
							$m['table']['rows'][$i]['title']['url'] = 'index.php?m=menu&d=listlines&mid='.$row->id_menu_m;
							$m['table']['rows'][$i]['edit']['url'] = 'index.php?m=menu&d=editmenu&mid='.$row->id_menu_m;
							$m['table']['rows'][$i]['delete']['url'] = 'index.php?m=menu&d=postdeletemenu&mid='.$row->id_menu_m;
							$m['table']['rows'][$i]['stick']['url'] = 'index.php?m=blocks&d=add&b=menu&id='.$row->id_menu_m.'&c='.$row->caption_m;
							$i++;
						}
				}
		}

?>