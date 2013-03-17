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

if ($userinfo['level']==3)
	{
		if (strcmp($m["mode"], 'admin')==0)
			{
				$m['title']=$lang['settings'];
				$m["module"]='news';
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
			}
		if (strcmp($m["mode"], 'editctg')==0)
			{
				$m['title']=$lang['edit_category'];
				$m["module"]='news';
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
				$sql="SELECT * FROM ".$tableprefix."categories_news WHERE id_category='".$_getvars['ctgid']."'";;
				$result=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_object($result))
					{
						$m["id_ctg"]=$row->id_category;
						$m["title_category"]=$row->title_category;
						$m['modify_groups_category']=get_array_groups($row->groups_modify);
						$m['category_no_alike_news']=$row->no_alike_news;
						if (!empty($row->filename_category) && $_settings['humanURL']==1)
							{
								$m['filesystem']=get_filesystem($row->filename_category);
								$m["filename_category"]=$m['filesystem']['filename'];
							}
					}
				$m['groups_list']=get_groups_list();
			}
		if (strcmp($m["mode"], 'postaddctg')==0)
			{
				$m['title']=$lang['add_category'];
				$m["module"]='news';
				$title_category=addslashesJ($_postvars["p_title_category"]);
				$filename=addslashesJ($_postvars["p_filename"]);
				$groups_modify=create_groups_str($_postvars['p_groups_modify']);
				$no_alike_news=intval($_postvars['p_no_alike_news']);
				$sql="INSERT INTO ".$tableprefix."categories_news (title_category, groups_modify, no_alike_news) VALUES ('$title_category', '$groups_modify', '$no_alike_news')";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$ctgid=database_insert_id('categories_news', $nameDB, $lnkDB);
				if (!empty($filename) && $_settings['humanURL']==1)
					{
						$urlid=register_filesystem('index.php?m=news&d=listnews&ctg='.$ctgid, $filename, $title_category);
						$sql="UPDATE ".$tableprefix."categories_news SET filename_category='$urlid' WHERE id_category=".$ctgid;
						$result=database_db_query($nameDB, $sql, $lnkDB);
					}
				$refresh_url='index.php?m=news&d=listctg';
				sm_event('postaddctgnews', array($ctgid));
			}
		if (strcmp($m["mode"], 'addctg')==0)
			{
				$m['title']=$lang['add_category'];
				$m["module"]='news';
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
				$m['groups_list']=get_groups_list();
			}
		if (strcmp($m["mode"], 'posteditctg')==0)
			{
				$m['title']=$lang['edit_category'];
				$m["module"]='news';
				$title_category=addslashesJ($_postvars["p_title_category"]);
				$filename=addslashesJ($_postvars["p_filename"]);
				$groups_modify=create_groups_str($_postvars['p_groups_modify']);
				$id_ctg=$_getvars['ctgid'];
				$no_alike_news=intval($_postvars['p_no_alike_news']);
				$sql="UPDATE ".$tableprefix."categories_news SET title_category = '$title_category', groups_modify='$groups_modify', no_alike_news='$no_alike_news' WHERE id_category='$id_ctg'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				if ($_settings['humanURL']==1)
					{
						$sql="SELECT * FROM ".$tableprefix."categories_news WHERE id_category='$id_ctg'";
						$result=database_db_query($nameDB, $sql, $lnkDB);
						while ($row=database_fetch_object($result))
							{
								$fname=$row->filename_category;
							}
						if ($fname==0 && !empty($filename))
							{
								$urlid=register_filesystem('index.php?m=news&d=listnews&ctg='.$id_ctg, $filename, $title_category);
								$sql="UPDATE ".$tableprefix."categories_news SET filename_category='$urlid' WHERE id_category=".$id_ctg;
								$result=database_db_query($nameDB, $sql, $lnkDB);
							}
						else
							{
								if (empty($filename))
									{
										$sql="UPDATE ".$tableprefix."categories_news SET filename_category='0' WHERE id_category=".$id_ctg;
										$result=database_db_query($nameDB, $sql, $lnkDB);
										delete_filesystem($fname);
									}
								else
									update_filesystem($fname, 'index.php?m=news&d=listnews&ctg='.$id_ctg, $filename, $title_category);
							}
					}
				$refresh_url='index.php?m=news&d=listctg';
				sm_event('posteditctgnews', array($id_ctg));
			}
		if (strcmp($m["mode"], 'deletectg')==0)
			{
				$m["module"]='news';
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['delete_category'];
				$_msgbox['msg']=$lang['really_want_delete_category_news'];
				$_msgbox['yes']='index.php?m=news&d=postdeletectg&ctgid='.$_getvars["ctgid"];
				$_msgbox['no']='index.php?m=news&d=listctg';
			}
		if (strcmp($m["mode"], 'postdeletectg')==0)
			{
				$m['title']=$lang['delete_category'];
				$m["module"]='news';
				$id_ctg=$_getvars['ctgid'];
				if ($_settings['humanURL']==1)
					{
						$sql="SELECT * FROM ".$tableprefix."categories_news WHERE id_category='".$id_ctg."'";
						$result=database_db_query($nameDB, $sql, $lnkDB);
						while ($row=database_fetch_object($result))
							{
								$fname=$row->filename_category;
							}
						if ($fname!=0)
							{
								delete_filesystem($fname);
							}
					}
				$sql="DELETE FROM ".$tableprefix."categories_news WHERE id_category='$id_ctg'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$refresh_url='index.php?m=news&d=listctg';
				sm_event('postdeletectgnews', array($id_ctg));
			}
		if (strcmp($m["mode"], 'listctg')==0)
			{
				$m['title']=$lang['list_news_categories'];
				$m["module"]='news';
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
				$sql="SELECT ".$tableprefix."categories_news.*, ".$tableprefix."filesystem.* FROM ".$tableprefix."categories_news LEFT JOIN ".$tableprefix."filesystem ON ".$tableprefix."categories_news.filename_category=".$tableprefix."filesystem.id_fs";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				
				require_once('includes/admintable.php');
				$m['table']['columns']['title']['caption']=$lang['common']['title'];
				$m['table']['columns']['title']['width']='100%';
				$m['table']['columns']['search']['caption']='';
				$m['table']['columns']['search']['hint']=$lang['search'];
				$m['table']['columns']['search']['replace_text']=$lang['search'];
				$m['table']['columns']['search']['replace_image']='search.gif';
				$m['table']['columns']['search']['width']='16';
				$m['table']['columns']['edit']['caption']='';
				$m['table']['columns']['edit']['hint']=$lang['common']['edit'];
				$m['table']['columns']['edit']['replace_text']=$lang['common']['edit'];
				$m['table']['columns']['edit']['replace_image']='edit.gif';
				$m['table']['columns']['edit']['width']='16';
				$m['table']['columns']['delete']['caption']='';
				$m['table']['columns']['delete']['hint']=$lang['common']['delete'];
				$m['table']['columns']['delete']['replace_text']=$lang['common']['delete'];
				$m['table']['columns']['delete']['replace_image']='delete.gif';
				$m['table']['columns']['delete']['width']='16';
				$m['table']['columns']['delete']['messagebox']=1;
				$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_category_news']);
				$m['table']['columns']['stick']['caption']='';
				$m['table']['columns']['stick']['hint']=$lang["set_as_block"];
				$m['table']['columns']['stick']['replace_text']=$lang['common']['stick'];
				$m['table']['columns']['stick']['replace_image']='stick.gif';
				$m['table']['columns']['tomenu']['caption']='';
				$m['table']['columns']['tomenu']['hint']=$lang['module_menu']['add_to_menu'];
				$m['table']['columns']['tomenu']['replace_text']=$lang['module_menu']['add_to_menu'];
				$m['table']['columns']['tomenu']['to_menu']=1;
				$m['table']['default_column']='edit';
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$m['table']['rows'][$i]['title']['data']=$row->title_category;
						$m['table']['rows'][$i]['title']['hint']=$row->title_category;
						if ($_settings['humanURL']==1 && $row->filename_category!=0)
							$m['table']['rows'][$i]['title']['url']=get_filename($row->filename_category);
						else
							$m['table']['rows'][$i]['title']['url']='index.php?m=news&d=listnews&ctg='.$row->id_category;
						$m['table']['rows'][$i]['edit']['url']='index.php?m=news&d=editctg&ctgid='.$row->id_category;
						$m['table']['rows'][$i]['search']['url']='index.php?m=news&d=list&ctg='.$row->id_category;
						if ($row->id_category!=1)
							$m['table']['rows'][$i]['delete']['url']='index.php?m=news&d=postdeletectg&ctgid='.$row->id_category;
						//$m['table']['rows'][$i]['html']['url']='index.php?m=news&d=editctg&ctgid='.$row->id_category.'&exteditor=off';
						$m['table']['rows'][$i]['stick']['url']='index.php?m=blocks&d=add&b=news&id='.$row->id_category.'&db=shortnews&c='.$row->title_category;
						$m['table']['rows'][$i]['tomenu']['menu_url']=addslashes($m['table']['rows'][$i]['title']['url']);
						$m['table']['rows'][$i]['tomenu']['menu_caption']=addslashes($row->title_category);
						$i++;
					}
				
				
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$m["ctg"][$i]['id']=$row->id_category;
						$m["ctg"][$i]['title']=$row->title_category;
						if ($_settings['humanURL']==1 && $row->filename_category!=0)
							$m["ctg"][$i]['filename']=get_filename($row->filename_category);
						else
							$m["ctg"][$i]['filename']='index.php?m=news&d=listnews&ctg='.$row->id_category;
						$i++;
					}
			}
		if (strcmp($m["mode"], 'list')==0)
			{
				$m["module"]='news';
				add_path($lang['control_panel'], "index.php?m=admin");
				add_path($lang['modules_mamagement'], "index.php?m=admin&d=modules");
				add_path($lang['module_news']['module_news_name'], "index.php?m=news&d=admin");
				$from_record=$_getvars['from'];
				if (empty($from_record)) $from_record=0;
				$from_page=ceil( ($from_record+1) / $_settings['admin_items_by_page'] );
				$m['pages']['url']='index.php?m=news&d=list';
				$m['pages']['selected']=$from_page;
				$m['pages']['interval']=$_settings['admin_items_by_page'];
				$ctg_id=$_getvars['ctg'];
				$m['ctg_id']=$ctg_id;
				$sql="SELECT * FROM ".$tableprefix."categories_news";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$m['ctg'][$i]['title']=$row->title_category;
						$m['ctg'][$i]['id']=$row->id_category;
						$i++;
					}
				$m['title']=$lang['list_news'];
				//$sql2='';
				//if (!empty($ctg_id)) $sql2=" WHERE id_category_n = '$ctg_id' ";
				$sql="SELECT ".$tableprefix."news.*, ".$tableprefix."filesystem.* FROM ".$tableprefix."news LEFT JOIN ".$tableprefix."filesystem ON ".$tableprefix."news.filename_news=".$tableprefix."filesystem.id_fs";
				if (!empty($ctg_id)) $sql.=" WHERE id_category_n = '$ctg_id'";
				$sql.=" ORDER BY date_news DESC";
				//$sql="SELECT * FROM ".$tableprefix."news $sql2 ORDER BY date_news DESC";
				$sql.=" LIMIT ".$_settings['admin_items_by_page']." OFFSET $from_record";
				require_once('includes/admintable.php');
				$m['table']['columns']['date']['caption']=$lang['date_news'];
				$m['table']['columns']['date']['width']='30';
				$m['table']['columns']['title']['caption']=$lang['common']['title'];
				$m['table']['columns']['title']['width']='100%';
				$m['table']['columns']['edit']['caption']='';
				$m['table']['columns']['edit']['hint']=$lang['common']['edit'];
				$m['table']['columns']['edit']['replace_text']=$lang['common']['edit'];
				$m['table']['columns']['edit']['replace_image']='edit.gif';
				$m['table']['columns']['edit']['width']='16';
				$m['table']['columns']['html']['caption']='';
				$m['table']['columns']['html']['hint']=$lang['common']['edit'].' ('.$lang['common']['html'].')';
				$m['table']['columns']['html']['replace_text']=$lang['common']['html'];
				$m['table']['columns']['html']['replace_image']='edit_html.gif';
				$m['table']['columns']['html']['width']='16';
				$m['table']['columns']['delete']['caption']='';
				$m['table']['columns']['delete']['hint']=$lang['common']['delete'];
				$m['table']['columns']['delete']['replace_text']=$lang['common']['delete'];
				$m['table']['columns']['delete']['replace_image']='delete.gif';
				$m['table']['columns']['delete']['width']='16';
				$m['table']['columns']['delete']['messagebox']=1;
				$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['really_want_delete_news']);
				$m['table']['columns']['stick']['caption']='';
				$m['table']['columns']['stick']['hint']=$lang["set_as_block"];
				$m['table']['columns']['stick']['replace_text']=$lang['common']['stick'];
				$m['table']['columns']['stick']['replace_image']='stick.gif';
				$m['table']['columns']['tomenu']['caption']='';
				$m['table']['columns']['tomenu']['hint']=$lang['module_menu']['add_to_menu'];
				$m['table']['columns']['tomenu']['replace_text']=$lang['module_menu']['add_to_menu'];
				$m['table']['columns']['tomenu']['to_menu']=1;
				$m['table']['default_column']='edit';
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				$have_title=0;
				while ($row=database_fetch_object($result))
					{
						$m['table']['rows'][$i]['date']['data']=strftime($lang["datemask"], $row->date_news);
						$m['table']['rows'][$i]['title']['data']=$row->title_news;
						if (empty($row->preview_news))
							$m['table']['rows'][$i]['title']['hint']=htmlspecialchars(cut_str_by_word(nl2br($row->text_news), 100, '...'));
						else
							$m['table']['rows'][$i]['title']['hint']=htmlspecialchars(cut_str_by_word(nl2br($row->preview_news), 100, '...'));
						if ($_settings['humanURL']==1 && $row->filename_news!=0)
							{
								$m['table']['rows'][$i]['title']['url']=$row->filename_fs;
							}
						else
							{
								$m['table']['rows'][$i]['title']['url']='index.php?m=news&d=view&nid='.$row->id_news;
							}
						$m['table']['rows'][$i]['date']['url']=$m['table']['rows'][$i]['title']['url'];
						$m['table']['rows'][$i]['date']['hint']=$m['table']['rows'][$i]['title']['hint'];
						$m['table']['rows'][$i]['edit']['url']='index.php?m=news&d=edit&nid='.$row->id_news;
						$m['table']['rows'][$i]['delete']['url']='index.php?m=news&d=postdelete&nid='.$row->id_news.'&ctg='.$row->id_category_n;
						$m['table']['rows'][$i]['html']['url']='index.php?m=news&d=edit&nid='.$row->id_news.'&exteditor=off';
						$m['table']['rows'][$i]['tomenu']['menu_url']=addslashes($m['table']['rows'][$i]['title']['url']);
						$m['table']['rows'][$i]['tomenu']['menu_caption']=addslashes($row->title_news);
						if (empty($row->title_news))
							$m['table']['rows'][$i]['tomenu']['menu_caption']=addslashes($m['table']['rows'][$i]['date']['data']);
						$m['table']['rows'][$i]['stick']['url']='index.php?m=blocks&d=add&b=news&id='.$row->id_news.'&db=view&c='.$row->title_news;
						if (empty($row->title_news))
							$m['table']['rows'][$i]['stick']['url'].=addslashes($m['table']['rows'][$i]['date']['data']);
						if (!empty($row->title_news))
							$have_title=1;
						$i++;
					}
				if ($have_title!=1)
					{
						$m['table']['columns']['title']['hide']='1';
						$m['table']['columns']['date']['width']='100%';
					}
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$i=0;
				while ($row=database_fetch_object($result))
					{
						$m["newsid"][$i][0]=$row->id_news;
						$m["newsid"][$i][1]=$row->date_news;
						$m["newsid"][$i][1]=strftime($lang["datemask"], $m["newsid"][$i][1]);
						$m["newsid"][$i][2]=$row->text_news;
						$m["newsid"][$i][3]=$row->preview_news;
						$m["newsid"][$i][4]=$row->title_news;
						if ($_settings['humanURL']==1 && $row->filename_news!=0)
							{
								$m["newsid"][$i][5]=$row->filename_fs;
							}
						else
							{
								$m["newsid"][$i][5]='index.php?m=news&d=view&nid='.$row->id_news;
							}
						$m["newsid"][$i][6]=$row->id_category_n;
						$i++;
					}
				$sql="SELECT count(*) FROM ".$tableprefix."news";
				if (!empty($ctg_id)) $sql.=" WHERE id_category_n = '$ctg_id'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				$m['pages']['records']=0;
				while ($row=database_fetch_row($result))
					{
						$m['pages']['records']=$row[0];
					}
				$m['pages']['pages']=ceil($m['pages']['records'] / $_settings['admin_items_by_page']);
				$m['short_news']=0;
			}
	}

?>