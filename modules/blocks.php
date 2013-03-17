<?php

//------------------------------------------------------------------------------
//|            Content Management System SiMan CMS                             |
//|                http://www.simancms.org                                     |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.3	                                                               |
//#revision 2012-08-14                                                         |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if ($userinfo['level']>=3)
{
	if (empty($m["mode"])) $m["mode"]='view';
	
	if (strcmp($m["mode"], 'add')==0)
		{
			$m["module"]='blocks';
			$m["title"]=$lang['static_blocks'];
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['static_blocks'], "index.php?m=blocks");
			$m["id"]=$_getvars['id'];
			$m["block"]=$_getvars['b'];
			$m["doing"]=$_getvars['db'];
			$m["caption_block"]=$_getvars['c'];
			$sql="SELECT * FROM ".$tableprefix."modules ORDER BY module_name='content' ASC";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['show_on'][$i]['caption']=$lang['module'].': '.$row->module_title;
					$m['show_on'][$i]['value']=$row->module_name.'|0';
					$i++;
				}
			$listeners=nllistToArray($_settings['autoload_modules']);
			for ($i=0; $i<count($listeners); $i++)
				{
					$blockfn='siman_block_items_'.$listeners[$i];
					if (function_exists($blockfn))
						{
							$tmparr=call_user_func($blockfn, $m);
							if (is_array($tmparr))
								$m['show_on']=array_merge($m['show_on'], $tmparr);
						}
				}
			$m['groups_all']=get_groups_list();
		}
	if (strcmp($m["mode"], 'edit')==0)
		{
			$m["module"]='blocks';
			$m["title"]=$lang['static_blocks'];
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['static_blocks'], "index.php?m=blocks");
			$id_block=$_getvars["id"];
			$sql="SELECT * FROM ".$tableprefix."blocks WHERE id_block='$id_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			while ($row=database_fetch_object($result))
				{
					$m["id"]=$row->id_block;
					$m["panel_block"]=$row->panel_block;
					$m["pos_block"]=$row->position_block;
					$m["caption_block"]=$row->caption_block;
					$m["level_block"]=$row->level;
					$m["show_on_module_block"]=$row->show_on_module;
					$m["show_on_ctg_block"]=$row->show_on_ctg;
					$m["dont_show_modif"]=$row->dont_show_modif;
					$m["no_borders"]=$row->no_borders;
					$m["rewrite_title"]=$row->rewrite_title;
					$m["block_groups_sel"]=get_array_groups($row->groups_view);
					$m["thislevelonly"]=$row->thislevelonly;
					$m["show_on_device"]=$row->show_on_device;
					$m["show_on_viewids"]=$row->show_on_viewids;
				}
			$m["show_on_all"]=1;
			$sql="SELECT * FROM ".$tableprefix."modules ORDER BY module_name='content' ASC";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['show_on'][$i]['caption']=$lang['module'].': '.$row->module_title;
					$m['show_on'][$i]['value']=$row->module_name.'|0';
					if (strcmp($m["show_on_module_block"], $row->module_name)==0 && $m["show_on_ctg_block"]==0)
						$m['show_on'][$i]['selected']=1;
					$i++;
				}
			$listeners=nllistToArray($_settings['autoload_modules']);
			for ($i=0; $i<count($listeners); $i++)
				{
					$blockfn='siman_block_items_'.$listeners[$i];
					if (function_exists($blockfn))
						{
							$tmparr=call_user_func($blockfn, $m);
							if (is_array($tmparr))
								$m['show_on']=array_merge($m['show_on'], $tmparr);
						}
				}
			$m['groups_all']=get_groups_list();
		}
	if (strcmp($m["mode"], 'postedit')==0)
		{
			$id_block=intval($_postvars["p_id"]);
			$old_panel=$_postvars["p_old_pnl"];
			$old_position=$_postvars["p_old_pos"];
			$caption_block=addslashesJ($_postvars["p_caption"]);
			$panel_block=$_postvars["p_panel"];
			$level=$_postvars["p_level"];
			$arr_show_on=explode('|', $_postvars['p_show_on']);
			$module_block=$arr_show_on[0];
			$show_doing_block=$arr_show_on[2];
			$ctg_block=$arr_show_on[1];
			$no_borders=$_postvars['p_no_borders'];
			$dont_show_modif=$_postvars['p_dont_show'];
			$rewrite_title=$_postvars["p_rewrite_title"];
			$groups_view=create_groups_str($_postvars['p_groups']);
			$thislevelonly=intval($_postvars['p_thislevelonly']);
			$show_on_device=$_postvars['show_on_device'];
			$show_on_viewids=$_postvars['show_on_viewids'];
			if ($panel_block!=$old_panel)
				{
					$sql="SELECT max(position_block) FROM ".$tableprefix."blocks WHERE panel_block='$panel_block'";
					$result=database_db_query($nameDB, $sql, $lnkDB);
					$pos_block=0;
					while ($row=database_fetch_row($result))
						{
							$pos_block=$row[0];
						}
					$pos_block++;
					$sql="UPDATE ".$tableprefix."blocks SET level = '$level', panel_block='$panel_block', position_block='$pos_block', caption_block='$caption_block', show_on_module='$module_block', show_on_doing='$show_doing_block', show_on_ctg='$ctg_block', no_borders='$no_borders', dont_show_modif ='$dont_show_modif', rewrite_title = '$rewrite_title', groups_view = '$groups_view', thislevelonly='$thislevelonly', show_on_device='$show_on_device', show_on_viewids='$show_on_viewids' WHERE id_block = '$id_block'";
					$result=database_db_query($nameDB, $sql, $lnkDB);
					$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>='".($old_position)."' AND panel_block='$old_panel'";
					$result=database_db_query($nameDB, $sql, $lnkDB);
				}
			else
				{
					$sql="UPDATE ".$tableprefix."blocks SET level = '$level', caption_block = '$caption_block', show_on_module='$module_block', show_on_doing='$show_doing_block', show_on_ctg='$ctg_block', no_borders='$no_borders', dont_show_modif ='$dont_show_modif', rewrite_title = '$rewrite_title', groups_view = '$groups_view', thislevelonly='$thislevelonly', show_on_device='$show_on_device', show_on_viewids='$show_on_viewids'  WHERE id_block = '$id_block'";
					$result=database_db_query($nameDB, $sql, $lnkDB);
				}
			if ($_settings['blocks_use_image']==1)
				{
					siman_upload_image($id_block, 'block');
				}
			sm_redirect('index.php?m=blocks&d=view');
		}
	if (strcmp($m["mode"], 'postadd')==0)
		{
			$id_block=$_postvars["p_id"];
			$name_block=$_postvars["p_block"];
			$caption_block=addslashesJ($_postvars["p_caption"]);
			$panel_block=$_postvars["p_panel"];
			$level=$_postvars["p_level"];
			$arr_show_on=explode('|', $_postvars['p_show_on']);
			$dont_show_modif=$_postvars["p_dont_show"];
			$doing_block=$_postvars["p_doing"];
			$rewrite_title=$_postvars["p_rewrite_title"];
			$module_block=$arr_show_on[0];
			$show_doing_block=$arr_show_on[2];
			$ctg_block=$arr_show_on[1];
			$no_borders=$_postvars['p_no_borders'];
			$show_on_device=$_postvars['show_on_device'];
			$groups_view=create_groups_str($_postvars['p_groups']);
			$thislevelonly=intval($_postvars['p_thislevelonly']);
			$show_on_viewids=$_postvars['show_on_viewids'];
			$sql="SELECT max(position_block) FROM ".$tableprefix."blocks WHERE panel_block='$panel_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$pos_block=0;
			while ($row=database_fetch_row($result))
				{
					$pos_block=$row[0];
				}
			$pos_block++;
			$sql="INSERT INTO ".$tableprefix."blocks (level, panel_block, position_block, name_block, caption_block, showed_id, show_on_module, show_on_doing, show_on_ctg, dont_show_modif, doing_block, no_borders, rewrite_title, groups_view, thislevelonly, show_on_device, show_on_viewids) VALUES ('$level', '$panel_block', '$pos_block', '$name_block', '$caption_block', '$id_block', '$module_block', '$show_doing_block', '$ctg_block', '$dont_show_modif', '$doing_block', '$no_borders', '$rewrite_title', '$groups_view', '$thislevelonly', '$show_on_device', '$show_on_viewids')";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			if ($_settings['blocks_use_image']==1)
				{
					$id_block=database_insert_id('blocks', $nameDB, $lnkDB);
					siman_upload_image($id_block, 'block');
				}
			sm_redirect('index.php?m=blocks&d=view');
		}
	if (strcmp($m["mode"], 'delete')==0)
		{
			$m["module"]='blocks';
			$_msgbox['mode']='yesno';
			$_msgbox['title']=$lang['delete'];
			$_msgbox['msg']=$lang['really_want_delete_block'];
			$_msgbox['yes']='index.php?m=blocks&d=postdelete&id='.$_getvars['id'].'&pos='.$_getvars['pos'].'&pnl='.$_getvars['pnl'];
			$_msgbox['no']='index.php?m=blocks';
		}
	if (strcmp($m["mode"], 'postdelete')==0)
		{
			$m["title"]=$lang['static_blocks'];
			$m["module"]='blocks';
			$refresh_url='index.php?m=blocks';
			$id_block=$_getvars["id"];
			$pos_block=$_getvars["pos"];
			$panel_block=$_getvars["pnl"];
			$sql="DELETE FROM ".$tableprefix."blocks  WHERE id_block='$id_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>='".($pos_block)."' AND panel_block='$panel_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
		}
	if (strcmp($m["mode"], 'up')==0)
		{
			$m["title"]=$lang['static_blocks'];
			$m["module"]='blocks';
			$refresh_url='index.php?m=blocks';
			$id_block=$_getvars["id"];
			$pos_block=$_getvars["pos"];
			$panel_block=$_getvars["pnl"];
			$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block+1 WHERE position_block='".($pos_block-1)."' AND panel_block='$panel_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block>'1' AND id_block='$id_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
		}
	if (strcmp($m["mode"], 'down')==0)
		{
			$m["title"]=$lang['static_blocks'];
			$m["module"]='blocks';
			$refresh_url='index.php?m=blocks';
			$id_block=$_getvars["id"];
			$pos_block=$_getvars["pos"];
			$panel_block=$_getvars["pnl"];
			$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block-1 WHERE position_block='".($pos_block+1)."' AND panel_block='$panel_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$sql="UPDATE ".$tableprefix."blocks SET position_block=position_block+1 WHERE id_block='$id_block'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
		}
	if (strcmp($m["mode"], 'view')==0)
		{
			$m["module"]='blocks';
			$m["title"]=$lang['static_blocks'];
			add_path_control();
			require_once('includes/admintable.php');
			for ($panel=1; $panel<intval($_settings['sidepanel_count'])+1; $panel++)
				{
					$m['tablepanels'][$panel]['columns']['title']['caption']=$lang['panel'].' '.$panel;
					$m['tablepanels'][$panel]['columns']['title']['width']='100%';
					$m['tablepanels'][$panel]['columns']['up']['caption']='';
					$m['tablepanels'][$panel]['columns']['up']['hint']=$lang['up'];
					$m['tablepanels'][$panel]['columns']['up']['replace_text']=$lang['up'];
					$m['tablepanels'][$panel]['columns']['up']['replace_image']='up.gif';
					$m['tablepanels'][$panel]['columns']['up']['width']='16';
					$m['tablepanels'][$panel]['columns']['down']['caption']='';
					$m['tablepanels'][$panel]['columns']['down']['hint']=$lang['down'];
					$m['tablepanels'][$panel]['columns']['down']['replace_text']=$lang['down'];
					$m['tablepanels'][$panel]['columns']['down']['replace_image']='down.gif';
					$m['tablepanels'][$panel]['columns']['down']['width']='16';
					$m['tablepanels'][$panel]['columns']['edit']['caption']='';
					$m['tablepanels'][$panel]['columns']['edit']['hint']=$lang['common']['edit'];
					$m['tablepanels'][$panel]['columns']['edit']['replace_text']=$lang['common']['edit'];
					$m['tablepanels'][$panel]['columns']['edit']['replace_image']='edit.gif';
					$m['tablepanels'][$panel]['columns']['edit']['width']='16';
					$m['tablepanels'][$panel]['columns']['delete']['caption']='';
					$m['tablepanels'][$panel]['columns']['delete']['hint']=$lang['common']['delete'];
					$m['tablepanels'][$panel]['columns']['delete']['replace_text']=$lang['common']['delete'];
					$m['tablepanels'][$panel]['columns']['delete']['replace_image']='delete.gif';
					$m['tablepanels'][$panel]['columns']['delete']['width']='16';
					$m['tablepanels'][$panel]['columns']['delete']['messagebox']=1;
					$m['tablepanels'][$panel]['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_block']);
					$m['tablepanels'][$panel]['default_column']='edit';
					$panelstr="panel_block='".$panel."'";
					if ($panel==1)
						$panelstr.=" OR panel_block='l'";
					if ($panel==2)
						$panelstr.=" OR panel_block='r'";
					$sql="SELECT * FROM ".$tableprefix."blocks WHERE $panelstr ORDER BY panel_block, position_block";
					$result=database_db_query($nameDB, $sql, $lnkDB);
					$i=0;
					while ($row=database_fetch_object($result))
						{
							$m['tablepanels'][$panel]['rows'][$i]['title']['data']=$row->caption_block;
							$m['tablepanels'][$panel]['rows'][$i]['title']['hint']=$row->caption_block;
							$m['tablepanels'][$panel]['rows'][$i]['edit']['url']='index.php?m=blocks&d=edit&id='.$row->id_block;
							$m['tablepanels'][$panel]['rows'][$i]['up']['url']='index.php?m=blocks&d=up&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
							$m['tablepanels'][$panel]['rows'][$i]['down']['url']='index.php?m=blocks&d=down&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
							$m['tablepanels'][$panel]['rows'][$i]['delete']['url']='index.php?m=blocks&d=postdelete&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
							$i++;
						}
					if (count($m['tablepanels'][$panel]['rows'])>0)
						{
							$m['tablepanels'][$panel]['rows'][0]['up']['url']='';
							$m['tablepanels'][$panel]['rows'][count($m['tablepanels'][$panel]['rows'])-1]['down']['url']='';
						}
				}
			$m['table3']['columns']['id']['hide']=1;
			$m['table3']['columns']['title']['caption']=$lang['center_panel'];
			$m['table3']['columns']['title']['width']='100%';
			$m['table3']['columns']['up']['caption']='';
			$m['table3']['columns']['up']['hint']=$lang['up'];
			$m['table3']['columns']['up']['replace_text']=$lang['up'];
			$m['table3']['columns']['up']['replace_image']='up.gif';
			$m['table3']['columns']['up']['width']='16';
			$m['table3']['columns']['down']['caption']='';
			$m['table3']['columns']['down']['hint']=$lang['down'];
			$m['table3']['columns']['down']['replace_text']=$lang['down'];
			$m['table3']['columns']['down']['replace_image']='down.gif';
			$m['table3']['columns']['down']['width']='16';
			$m['table3']['columns']['edit']['caption']='';
			$m['table3']['columns']['edit']['hint']=$lang['common']['edit'];
			$m['table3']['columns']['edit']['replace_text']=$lang['common']['edit'];
			$m['table3']['columns']['edit']['replace_image']='edit.gif';
			$m['table3']['columns']['edit']['width']='16';
			$m['table3']['columns']['delete']['caption']='';
			$m['table3']['columns']['delete']['hint']=$lang['common']['delete'];
			$m['table3']['columns']['delete']['replace_text']=$lang['common']['delete'];
			$m['table3']['columns']['delete']['replace_image']='delete.gif';
			$m['table3']['columns']['delete']['width']='16';
			$m['table3']['columns']['delete']['messagebox']=1;
			$m['table3']['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_block']);
			$m['table3']['default_column']='edit';
			$sql="SELECT * FROM ".$tableprefix."blocks WHERE panel_block='c' ORDER BY panel_block, position_block";
			$result=database_db_query($nameDB, $sql, $lnkDB);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['table3']['rows'][$i]['id']['data']=$row->id_block;
					$m['table3']['rows'][$i]['title']['data']=$row->caption_block;
					$m['table3']['rows'][$i]['title']['hint']=$row->caption_block;
					$m['table3']['rows'][$i]['edit']['url']='index.php?m=blocks&d=edit&id='.$row->id_block;
					$m['table3']['rows'][$i]['up']['url']='index.php?m=blocks&d=up&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
					$m['table3']['rows'][$i]['down']['url']='index.php?m=blocks&d=down&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
					$m['table3']['rows'][$i]['delete']['url']='index.php?m=blocks&d=postdelete&id='.$row->id_block.'&pos='.$row->position_block.'&pnl='.$row->panel_block;
					$i++;
				}
			if (count($m['table3']['rows'])>0)
				{
					$m['table3']['rows'][0]['up']['url']='';
					$m['table3']['rows'][count($m['table3']['rows'])-1]['down']['url']='';
				}
			
			if (intval($_settings['main_block_position'])>count($m['table3']['rows']))
				{
					$_settings['main_block_position']=count($m['table3']['rows']);
				}
		}
	if (strcmp($m["mode"], 'setmain')==0)
		{
			$m["title"]=$lang['static_blocks'];
			$m["module"]='blocks';
			$refresh_url='index.php?m=blocks';
			$sql="UPDATE ".$tableprefix."settings SET value_settings='".intval($_postvars['p_mainpos'])."' WHERE name_settings='main_block_position' AND mode='default'";
			$result=database_db_query($nameDB, $sql, $lnkDB);
		}
}

?>