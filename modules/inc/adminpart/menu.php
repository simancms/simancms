<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.3	                                                               |
//#revision 2012-07-20                                                         |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if (!defined("MENU_ADMINPART_FUNCTIONS_DEFINED"))
	{
		function siman_delete_menu_line($line_id)
			{
				global $nameDB, $lnkDB, $tableprefix, $_settings;
				$sql="SELECT id_ml FROM ".$tableprefix."menu_lines WHERE submenu_from='$line_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_object($result))
					{
						siman_delete_menu_line($row->id_ml);
					}
				$sql="DELETE FROM ".$tableprefix."menu_lines WHERE id_ml='$line_id'";
				if ($_settings['menuitems_use_image']==1)
					{
						if (file_exists('./files/img/menuitem'.$line_id.'.jpg'))
							unlink('./files/img/menuitem'.$line_id.'.jpg');
					}
				$result=database_db_query($nameDB, $sql, $lnkDB);
			}
		define("MENU_ADMINPART_FUNCTIONS_DEFINED", 1);
	}


if ($userinfo['level']==3)
	{
		if (strcmp($modules[$modules_index]["mode"], 'admin')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["settings"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");			
			}
		if (strcmp($modules[$modules_index]["mode"], 'addouter')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$modules[$modules_index]['url_line']=$_postvars["p_url"];
				$modules[$modules_index]['caption_line']=$_postvars["p_caption"];
				$sql="SELECT * FROM ".$tableprefix."menus";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$modules[$modules_index]["menu"][$i]['lines']=siman_load_menu($row->id_menu_m);
						$modules[$modules_index]["menu"][$i]["id"]=$row->id_menu_m;
						$modules[$modules_index]["menu"][$i]["caption"]=$row->caption_m;
						$i++;
					}
			}
		if (strcmp($modules[$modules_index]["mode"], 'postadd')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["add_menu"];
				$mcaption=$_postvars["p_caption"];
				$sql="INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('$mcaption')";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['menus_use_image']==1)
					{
						$id_menu=database_insert_id('menus', $nameDB, $lnkDB);
						siman_upload_image($id_menu, 'menu');
					}
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['module_menu']['add_menu_line'];
				$_msgbox['msg']=$lang['you_want_add_line'];
				$_msgbox['yes']='index.php?m=menu&d=addline&mid='.database_insert_id('menus', $nameDB, $lnkDB);
				$_msgbox['no']='index.php?m=menu&d=listmenu';
			}
		if (strcmp($modules[$modules_index]["mode"], 'deleteline')==0)
			{
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['delete_menu_line'];
				$_msgbox['msg']=$lang['really_want_delete_line'];
				$_msgbox['yes']='index.php?m=menu&d=postdeleteline&mid='.$_getvars['mid'].'&lid='.$_getvars['lid'];
				$_msgbox['no']='index.php?m=menu&d=listlines&mid='.$_getvars['mid'];
			}
		if (strcmp($modules[$modules_index]["mode"], 'postdeleteline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["delete_menu_line"];
				$menu_id=$_getvars["mid"];
				$menuline_id=$_getvars["lid"];
				siman_delete_menu_line($menuline_id);
				$refresh_url='index.php?m=menu&d=listlines&mid='.$menu_id;
			}
		if (strcmp($modules[$modules_index]["mode"], 'postaddouter')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$_getvars["mid"]=$_postvars["p_mid"];
				$lposition=0;
				$modules[$modules_index]["mode"]='postaddline';
			}
		if (strcmp($modules[$modules_index]["mode"], 'postaddline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$lcaption=$_postvars["p_caption"];
				$menu_id=$_getvars["mid"];
				$lurl=$_postvars["p_url"];
				$submenu_from=$_postvars["p_sub"];
				$lposition=$_postvars["p_position"];
				$alt_ml = addslashesJ($_postvars["p_alt"]);
				$newpage_ml = intval($_postvars["p_newpage"]);
				if ($lposition==0)
					{
						$sql="SELECT max(position) FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id' AND submenu_from='$submenu_from'";
						$lposition=1;
						$result=database_db_query($nameDB, $sql, $lnkDB);
						while ($row=database_fetch_row($result))
							{
								$lposition=$row[0]+1;
							}
					}
				else
					{
						$sql="UPDATE ".$tableprefix."menu_lines SET position=position+1 WHERE position >= '$lposition' AND id_menu_ml='$menu_id' AND submenu_from='$submenu_from'";
						$result=database_db_query($nameDB, $sql, $lnkDB);
					}
				$sql="INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, submenu_from, url, caption_ml, position, alt_ml, newpage_ml) VALUES ('$menu_id', '$submenu_from', '$lurl', '$lcaption', '$lposition', '$alt_ml', '$newpage_ml')";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['menuitems_use_image']==1)
					{
						$id_ml=database_insert_id('menu_lines', $nameDB, $lnkDB);
						siman_upload_image($id_ml, 'menuitem');
					}
				$refresh_url='index.php?m=menu&d=listlines&mid='.$menu_id;
			}
		if (strcmp($modules[$modules_index]["mode"], 'posteditline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$menu_id=$_getvars["mid"];
				$menuline_id=$_getvars["lid"];
				$lcaption=$_postvars["p_caption"];
				$lurl=$_postvars["p_url"];
				$lposition=$_postvars["p_position"];
				$partial_select=intval($_postvars["p_partial_select"]);
				$alt_ml = addslashesJ($_postvars["p_alt"]);
				$attr_ml = addslashesJ($_postvars["attr_ml"]);
				$newpage_ml = intval($_postvars["p_newpage"]);
				if (empty($lposition))
					{
						//Нічого не робимо
					}
				elseif ($lposition==-1)
					{
						$sql="SELECT max(position) FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id'";
						$lposition=1;
						$result=database_db_query($nameDB, $sql, $lnkDB);
						while ($row=database_fetch_row($result))
							{
								$lposition=$row[0]+1;
							}
					}
				else
					{
						$sql="UPDATE ".$tableprefix."menu_lines SET position=position+1 WHERE position>='$lposition'";
						$result=database_db_query($nameDB, $sql, $lnkDB);
					}
				$sql="UPDATE ".$tableprefix."menu_lines SET url = '$lurl', caption_ml = '$lcaption', partial_select='$partial_select', alt_ml = '$alt_ml', attr_ml = '$attr_ml', newpage_ml = '$newpage_ml' ";
				if (!empty($lposition))
					{
						$sql.=", position = '$lposition'";
					}
				$sql.=" WHERE id_ml = '$menuline_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['menuitems_use_image']==1)
					{
						siman_upload_image($menuline_id, 'menuitem');
					}
				$refresh_url='index.php?m=menu&d=listlines&mid='.$menu_id;
			}
		if (strcmp($modules[$modules_index]["mode"], 'addline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$menu_id=$_getvars["mid"];
				$modules[$modules_index]['idmenu']=$menu_id;
				$modules[$modules_index]['menu']=siman_load_menu($menu_id);
			}
		if (strcmp($modules[$modules_index]["mode"], 'prepareaddline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['module_menu']['add_menu_line'];
				$modules[$modules_index]['menuline']['menu_id']=substr($_postvars['p_mainmenu'], 0, strpos($_postvars['p_mainmenu'], '|'));
				$modules[$modules_index]['menuline']['sub_id']=substr($_postvars['p_mainmenu'], strpos($_postvars['p_mainmenu'], '|')+1, strlen($_postvars['p_mainmenu'])-strpos($_postvars['p_mainmenu'], '|')-1);
				$modules[$modules_index]['menuline']['caption']=$_postvars['p_caption'];
				$modules[$modules_index]['menuline']['url']=$_postvars['p_url'];
				$sql="SELECT * FROM ".$tableprefix."menu_lines WHERE id_menu_ml='".$modules[$modules_index]['menuline']['menu_id']."' AND submenu_from='".$modules[$modules_index]['menuline']['sub_id']."' ORDER BY position";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$modules[$modules_index]['menu'][$i]['id']=$row->id_ml;
						$modules[$modules_index]['menu'][$i]['mid']=$modules[$modules_index]['addmenu']['id'];
						$modules[$modules_index]['menu'][$i]['caption']=$row->caption_ml;
						$modules[$modules_index]['menu'][$i]['pos']=$row->position;
						$i++;
					}
			}
		if (strcmp($modules[$modules_index]["mode"], 'editline')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["menu"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
				add_path($lang['list_menus'], "index.php?m=menu&d=listmenu");
				$menu_id=intval($_getvars["mid"]);
				$menuline_id=intval($_getvars["lid"]);
				$submenu_from=intval($_getvars["sid"]);
				if (empty($submenu_from)) $submenu_from=0;
				$modules[$modules_index]['idmenu']=$menu_id;
				$modules[$modules_index]['idline']=$menuline_id;
				$sql="SELECT * FROM ".$tableprefix."menu_lines WHERE id_menu_ml='$menu_id' AND submenu_from='$submenu_from' ORDER BY position";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				$u=0;
				while ($row=database_fetch_object($result))
					{
						if ($row->id_ml==$menuline_id)
							{
								$modules[$modules_index]['captionline']=$row->caption_ml;
								$modules[$modules_index]['urlline']=$row->url;
								$modules[$modules_index]['posline']=$row->position;
								$modules[$modules_index]['partial_select']=$row->partial_select;
								$modules[$modules_index]['alt_ml']=$row->alt_ml;
								$modules[$modules_index]['attr_ml']=$row->attr_ml;
								$modules[$modules_index]['newpage_ml']=$row->newpage_ml;
								$u=1;
							}
						else
							{
								if ($u==1)
									{
										$u=0;
									}
								else
									{
										$modules[$modules_index]['menu'][$i]['id']=$row->id_ml;
										$modules[$modules_index]['menu'][$i]['mid']=$menu_id;
										$modules[$modules_index]['menu'][$i]['caption']=$row->caption_ml;
										$modules[$modules_index]['menu'][$i]['pos']=$row->position;
										$i++;
									}
							}
					}
			}
		if (strcmp($modules[$modules_index]["mode"], 'listlines')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["menu"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");
				add_path($lang['list_menus'], "index.php?m=menu&d=listmenu");
				$menu_id=$_getvars["mid"];
				$modules[$modules_index]['idmenu']=$menu_id;
				$modules[$modules_index]['menu']=siman_load_menu($menu_id);
					require_once('includes/admintable.php');
					$modules[$modules_index]['table']['columns']['title']['caption']=$lang['common']['title'];
					$modules[$modules_index]['table']['columns']['title']['width']='100%';
					$modules[$modules_index]['table']['columns']['edit']['caption']='';
					$modules[$modules_index]['table']['columns']['edit']['hint']=$lang['common']['edit'];
					$modules[$modules_index]['table']['columns']['edit']['replace_text']=$lang['common']['edit'];
					$modules[$modules_index]['table']['columns']['edit']['replace_image']='edit.gif';
					$modules[$modules_index]['table']['columns']['edit']['width']='16';
					$modules[$modules_index]['table']['columns']['delete']['caption']='';
					$modules[$modules_index]['table']['columns']['delete']['hint']=$lang['common']['delete'];
					$modules[$modules_index]['table']['columns']['delete']['replace_text']=$lang['common']['delete'];
					$modules[$modules_index]['table']['columns']['delete']['replace_image']='delete.gif';
					$modules[$modules_index]['table']['columns']['delete']['width']='16';
					$modules[$modules_index]['table']['columns']['delete']['messagebox']=1;
					$modules[$modules_index]['table']['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_line']);
					$modules[$modules_index]['table']['default_column']='edit';
					for ($i=0; $i<count($modules[$modules_index]['menu']); $i++)
						{
							$lev='';
							for ($j=1; $j<$modules[$modules_index]['menu'][$i]['level']; $j++)
								$lev.='-';
							$modules[$modules_index]['table']['rows'][$i]['title']['data']=$lev.$modules[$modules_index]['menu'][$i]['caption'];
							$modules[$modules_index]['table']['rows'][$i]['title']['hint']=$modules[$modules_index]['menu'][$i]['caption'];
							$modules[$modules_index]['table']['rows'][$i]['title']['url']=$modules[$modules_index]['menu'][$i]['url'];
							$modules[$modules_index]['table']['rows'][$i]['edit']['url']='index.php?m=menu&d=editline&mid='.$modules[$modules_index]['menu'][$i]['mid'].'&lid='.$modules[$modules_index]['menu'][$i]['id'].'&sid='.$modules[$modules_index]['menu'][$i]['submenu_from'];
							$modules[$modules_index]['table']['rows'][$i]['delete']['url']='index.php?m=menu&d=postdeleteline&mid='.$modules[$modules_index]['menu'][$i]['mid'].'&lid='.$modules[$modules_index]['menu'][$i]['id'];
						}
			}
		if (strcmp($modules[$modules_index]["mode"], 'editmenu')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["edit_menu"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");			
				$menu_id=$_getvars["mid"];
				$sql="SELECT * FROM ".$tableprefix."menus WHERE id_menu_m='$menu_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_object($result))
					{
						$modules[$modules_index]["id"]=$menu_id;
						$modules[$modules_index]["caption"]=$row->caption_m;
					}
			}
		if (strcmp($modules[$modules_index]["mode"], 'postedit')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang['edit_menu'];
				$menu_id=$_getvars["mid"];
				$mcaption=$_postvars["p_caption"];
				$sql="UPDATE ".$tableprefix."menus SET caption_m = '$mcaption' WHERE id_menu_m='$menu_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['menus_use_image']==1)
					{
						siman_upload_image($menu_id, 'menu');
					}
				$refresh_url='index.php?m=menu&d=listmenu';
			}
		if (strcmp($modules[$modules_index]["mode"], 'deletemenu')==0)
			{
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['delete_menu'];
				$_msgbox['msg']=$lang['really_want_delete_menu'];
				$_msgbox['yes']='index.php?m=menu&d=postdeletemenu&mid='.$_getvars['mid'];
				$_msgbox['no']='index.php?m=menu&d=listmenu';
			}
		if (strcmp($modules[$modules_index]["mode"], 'postdeletemenu')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$menu_id=$_getvars["mid"];
				$modules[$modules_index]["title"]=$lang["delete_menu"];
				$sql="DELETE FROM ".$tableprefix."menus WHERE id_menu_m='$menu_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['menuitems_use_image']==1)
					{
						if (file_exists('./files/img/menu'.$menu_id.'.jpg'))
							unlink('./files/img/menu'.$menu_id.'.jpg');
					}
				$refresh_url='index.php?m=menu&d=listmenu';
			}
		if (strcmp($modules[$modules_index]["mode"], 'add')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["add_menu"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");	
			}
		if (strcmp($modules[$modules_index]["mode"], 'listmenu')==0)
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$lang["list_menus"];
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_menu']['module_menu_name'], "index.php?m=menu&d=admin");	
				$sql="SELECT * FROM ".$tableprefix."menus";
				require_once('includes/admintable.php');
				$modules[$modules_index]['table']['columns']['title']['caption']=$lang['common']['title'];
				$modules[$modules_index]['table']['columns']['title']['width']='100%';
				$modules[$modules_index]['table']['columns']['edit']['caption']='';
				$modules[$modules_index]['table']['columns']['edit']['hint']=$lang['common']['edit'];
				$modules[$modules_index]['table']['columns']['edit']['replace_text']=$lang['common']['edit'];
				$modules[$modules_index]['table']['columns']['edit']['replace_image']='edit.gif';
				$modules[$modules_index]['table']['columns']['edit']['width']='16';
				$modules[$modules_index]['table']['columns']['delete']['caption']='';
				$modules[$modules_index]['table']['columns']['delete']['hint']=$lang['common']['delete'];
				$modules[$modules_index]['table']['columns']['delete']['replace_text']=$lang['common']['delete'];
				$modules[$modules_index]['table']['columns']['delete']['replace_image']='delete.gif';
				$modules[$modules_index]['table']['columns']['delete']['width']='16';
				$modules[$modules_index]['table']['columns']['delete']['messagebox']=1;
				$modules[$modules_index]['table']['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_menu']);
				$modules[$modules_index]['table']['columns']['stick']['caption']='';
				$modules[$modules_index]['table']['columns']['stick']['hint']=$lang['set_as_block_random_text'];
				$modules[$modules_index]['table']['columns']['stick']['replace_text']=$lang['common']['stick'];
				$modules[$modules_index]['table']['columns']['stick']['replace_image']='stick.gif';
				$modules[$modules_index]['table']['default_column']='edit';
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$modules[$modules_index]['table']['rows'][$i]['title']['data']=$row->caption_m;
						$modules[$modules_index]['table']['rows'][$i]['title']['hint']=$row->caption_m;
						$modules[$modules_index]['table']['rows'][$i]['title']['url']='index.php?m=menu&d=listlines&mid='.$row->id_menu_m;
						$modules[$modules_index]['table']['rows'][$i]['edit']['url']='index.php?m=menu&d=editmenu&mid='.$row->id_menu_m;
						$modules[$modules_index]['table']['rows'][$i]['delete']['url']='index.php?m=menu&d=postdeletemenu&mid='.$row->id_menu_m;
						$modules[$modules_index]['table']['rows'][$i]['stick']['url']='index.php?m=blocks&d=add&b=menu&id='.$row->id_menu_m.'&c='.$row->caption_m;
						$i++;
					}
			}
	}

?>