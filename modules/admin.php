<?php

//------------------------------------------------------------------------------
//|            Content Management System SiMan CMS                             |
//|                http://www.simancms.org                                     |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.4
//#revision 2013-03-31
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Hacking attempt!');
		exit();
	}

if (!defined("ADMIN_FUNCTIONS_DEFINED"))
	{
		function sm_get_module_info($filename)
			{
				$fh=fopen($filename, 'r');
				$info=fread($fh, 2048);
				fclose($fh);
				$start=strpos($info, 'Module Name:');
				if ($start!==false && strpos($info, '*/', $start)!==false)
					{
						$info=substr($info, $start, strpos($info, '*/', $start)-$start);
						return trim($info);
					}
				else
					return false;
			}
		define("ADMIN_FUNCTIONS_DEFINED", 1);
	}

sm_default_action('view');

if ($userinfo['level']==3)
{
	$m["module"]='admin';
	if (strcmp($m['mode'], 'postsettings')==0 && $_postvars['marker']==1)
		{
			if (!empty($_getvars['viewmode']))
				$m['mode_settings']=$_getvars['viewmode'];
			else
				$m['mode_settings']='default';
			$m["title"]=$lang['settings'];
			$i=0;
			$set[$i]['name']='cookprefix';
			$set[$i]['value']=dbescape($_postvars['p_cook']);
			$i++;
			$set[$i]['name']='resource_title';
			$set[$i]['value']=dbescape($_postvars['p_title']);
			$i++;
			$_postvars['p_url']=trim($_postvars['p_url']);
			if (substr($_postvars['p_url'], -1)!='/')
				$_postvars['p_url'].='/';
			$set[$i]['name']='resource_url';
			$set[$i]['value']=dbescape($_postvars['p_url']);
			$i++;
			$set[$i]['name']='logo_text';
			$set[$i]['value']=dbescape($_postvars['p_logo']);
			$i++;
			$set[$i]['name']='meta_description';
			$set[$i]['value']=dbescape($_postvars['p_description']);
			$i++;
			$set[$i]['name']='meta_keywords';
			$set[$i]['value']=dbescape($_postvars['p_keywords']);
			$i++;
			$set[$i]['name']='default_language';
			$set[$i]['value']=dbescape($_postvars['p_lang']);
			$i++;
			$set[$i]['name']='default_theme';
			$set[$i]['value']=dbescape($_postvars['p_theme']);
			$i++;
			$set[$i]['name']='default_module';
			$set[$i]['value']=dbescape($_postvars['p_module']);
			$i++;
			$set[$i]['name']='copyright_text';
			$set[$i]['value']=dbescape($_postvars['p_copyright']);
			$i++;
			$set[$i]['name']='max_upload_filesize';
			$set[$i]['value']=dbescape($_postvars['p_maxfsize']);
			$i++;
			$set[$i]['name']='banned_ip';
			$set[$i]['value']=dbescape($_postvars['p_banned_ip']);
			$i++;
			$set[$i]['name']='meta_header_text';
			$set[$i]['value']=dbescape($_postvars['p_meta_header_text']);
			$i++;
			$set[$i]['name']='header_static_text';
			$set[$i]['value']=dbescape($_postvars['p_htext']);
			$i++;
			$set[$i]['name']='footer_static_text';
			$set[$i]['value']=dbescape($_postvars['p_ftext']);
			$i++;
			$set[$i]['name']='admin_items_by_page';
			if (empty($_postvars['p_adminitems_per_page']))
				$set[$i]['value']=dbescape('10');
			else
				$set[$i]['value']=dbescape($_postvars['p_adminitems_per_page']);
			$i++;
			$set[$i]['name']='search_items_by_page';
			if (empty($_postvars['p_searchitems_per_page']))
				$set[$i]['value']=dbescape('10');
			else
				$set[$i]['value']=dbescape($_postvars['p_searchitems_per_page']);
			$i++;
			$set[$i]['name']='ext_editor';
			$set[$i]['value']=dbescape($_postvars['p_exteditor']);
			$i++;
			$set[$i]['name']='noflood_time';
			$set[$i]['value']=dbescape($_postvars['p_floodtime']);
			$i++;
			$set[$i]['name']='blocks_use_image';
			$set[$i]['value']=dbescape($_postvars['p_blocks_use_image']);
			$i++;
			$set[$i]['name']='log_type';
			$set[$i]['value']=intval($_postvars['p_log_type']);
			$i++;
			$set[$i]['name']='log_store_days';
			$set[$i]['value']=intval($_postvars['p_log_store_days']);
			$i++;
			$set[$i]['name']='upper_menu_id';
			$set[$i]['value']=dbescape($_postvars['p_uppermenu']);
			$i++;
			$set[$i]['name']='bottom_menu_id';
			$set[$i]['value']=dbescape($_postvars['p_bottommenu']);
			$i++;
			$set[$i]['name']='menus_use_image';
			$set[$i]['value']=dbescape($_postvars['p_menus_use_image']);
			$i++;
			$set[$i]['name']='menuitems_use_image';
			$set[$i]['value']=dbescape($_postvars['p_menuitems_use_image']);
			$i++;
			$set[$i]['name']='users_menu_id';
			$set[$i]['value']=dbescape($_postvars['p_usersmenu']);
			$i++;
			$set[$i]['name']='content_use_preview';
			$set[$i]['value']=dbescape($_postvars['p_content_use_preview']);
			$i++;
			$set[$i]['name']='allow_alike_content';
			if ($_postvars['p_allow_alike_content']!=1) $_postvars['p_allow_alike_content']=0;
			$set[$i]['value']=$_postvars['p_allow_alike_content'];
			$i++;
			$set[$i]['name']='alike_content_count';
			$set[$i]['value']=dbescape(intval($_postvars['p_alike_content_count']));
			$i++;
			$set[$i]['name']='news_use_title';
			$set[$i]['value']=dbescape($_postvars['p_news_use_title']);
			$i++;
			$set[$i]['name']='news_use_image';
			$set[$i]['value']=dbescape($_postvars['p_news_use_image']);
			$i++;
			$set[$i]['name']='news_use_preview';
			$set[$i]['value']=dbescape($_postvars['p_news_use_preview']);
			$i++;
			$set[$i]['name']='content_per_page_multiview';
			if (empty($_postvars['p_multiviewperpage']))
				$set[$i]['value']=dbescape('10');
			else
				$set[$i]['value']=dbescape($_postvars['p_multiviewperpage']);
			$i++;
			$set[$i]['name']='news_by_page';
			if (empty($_postvars['p_news_per_page']))
				$set[$i]['value']=dbescape('10');
			else
				$set[$i]['value']=dbescape($_postvars['p_news_per_page']);
			$i++;
			$set[$i]['name']='news_anounce_cut';
			if (empty($_postvars['p_news_cut']))
				$set[$i]['value']=dbescape('300');
			else
				$set[$i]['value']=dbescape($_postvars['p_news_cut']);
			$i++;
			$set[$i]['name']='short_news_count';
			if (empty($_postvars['p_news_short']))
				$set[$i]['value']=dbescape('3');
			else
				$set[$i]['value']=dbescape($_postvars['p_news_short']);
			$i++;
			$set[$i]['name']='short_news_cut';
			if (empty($_postvars['p_short_news_cut']))
				$set[$i]['value']=dbescape('100');
			else
				$set[$i]['value']=dbescape($_postvars['p_short_news_cut']);
			$i++;
			$set[$i]['name']='news_use_time';
			$set[$i]['value']=dbescape($_postvars['p_news_use_time']);
			$i++;
			$set[$i]['name']='allow_alike_news';
			if ($_postvars['p_allow_alike_news']!=1) $_postvars['p_allow_alike_news']=0;
			$set[$i]['value']=$_postvars['p_allow_alike_news'];
			$i++;
			$set[$i]['name']='alike_news_count';
			$set[$i]['value']=dbescape(intval($_postvars['p_alike_news_count']));
			$i++;
			$set[$i]['name']='allow_register';
			if ($_postvars['p_allowregister']!=1) $_postvars['p_allowregister']=0;
			$set[$i]['value']=$_postvars['p_allowregister'];
			$i++;
			$set[$i]['name']='allow_forgot_password';
			if ($_postvars['p_allowforgotpass']!=1) $_postvars['p_allowforgotpass']=0;
			$set[$i]['value']=$_postvars['p_allowforgotpass'];
			$i++;
			$set[$i]['name']='user_activating_by_admin';
			if ($_postvars['p_adminactivating']!=1) $_postvars['p_adminactivating']=0;
			$set[$i]['value']=$_postvars['p_adminactivating'];
			$i++;
			$set[$i]['name']='return_after_login';
			if ($_postvars['p_return_after_login']!=1) $_postvars['p_return_after_login']=0;
			$set[$i]['value']=$_postvars['p_return_after_login'];
			$i++;
			$set[$i]['name']='allow_private_messages';
			if ($_postvars['p_allow_private_messages']!=1) $_postvars['p_allow_private_messages']=0;
			$set[$i]['value']=$_postvars['p_allow_private_messages'];
			$i++;
			$set[$i]['name']='administrators_email';
			$set[$i]['value']=dbescape($_postvars['p_admemail']);
			$i++;
			$set[$i]['name']='email_signature';
			$set[$i]['value']=dbescape($_postvars['p_esignature']);
			for ($i=0; $i<count($set); $i++)
				{
					$sql="UPDATE ".$tableprefix."settings SET value_settings = '".$set[$i]['value']."' WHERE name_settings = '".$set[$i]['name']."' AND mode='".dbescape($m['mode_settings'])."'";
					$result=execsql($sql);
				}
			sm_update_settings('rewrite_index_title', $_postvars['p_rewrite_index_title'], $m['mode_settings']);
			sm_update_settings('news_image_preview_width', $_postvars['p_news_image_preview_width'], $m['mode_settings']);
			sm_update_settings('news_image_preview_height', $_postvars['p_news_image_preview_height'], $m['mode_settings']);
			sm_update_settings('news_image_fulltext_width', $_postvars['p_news_image_fulltext_width'], $m['mode_settings']);
			sm_update_settings('news_image_fulltext_height', $_postvars['p_news_image_fulltext_height'], $m['mode_settings']);
			sm_update_settings('sidepanel_count', (intval($_postvars['p_sidepanel_count'])<=0)?1:intval($_postvars['p_sidepanel_count']), $m['mode_settings']);
			sm_update_settings('content_use_path', ($_postvars['p_content_use_path']=='1')?'1':'0', $m['mode_settings']);
			sm_update_settings('content_attachments_count', abs(intval($_postvars['p_content_attachments_count'])), $m['mode_settings']);
			sm_update_settings('news_attachments_count', abs(intval($_postvars['p_news_attachments_count'])), $m['mode_settings']);
			sm_update_settings('image_generation_type', ($_postvars['p_image_generation_type']=='static')?'static':'dynamic', $m['mode_settings']);
			sm_update_settings('use_email_as_login', ($_postvars['p_use_email_as_login']=='1')?'1':'0', $m['mode_settings']);
			sm_update_settings('title_delimiter', $_postvars['p_title_delimiter'], $m['mode_settings']);
			sm_update_settings('meta_resource_title_position', intval($_postvars['p_meta_resource_title_position']), $m['mode_settings']);
			sm_update_settings('content_use_image', ($_postvars['p_content_use_image']=='1')?'1':'0', $m['mode_settings']);
			sm_update_settings('content_image_preview_width', $_postvars['p_content_image_preview_width'], $m['mode_settings']);
			sm_update_settings('content_image_preview_height', $_postvars['p_content_image_preview_height'], $m['mode_settings']);
			sm_update_settings('content_image_fulltext_width', $_postvars['p_content_image_fulltext_width'], $m['mode_settings']);
			sm_update_settings('content_image_fulltext_height', $_postvars['p_content_image_fulltext_height'], $m['mode_settings']);
			sm_update_settings('redirect_after_login_1', $_postvars['p_redirect_after_login_1'], $m['mode_settings']);
			sm_update_settings('redirect_after_login_2', $_postvars['p_redirect_after_login_2'], $m['mode_settings']);
			sm_update_settings('redirect_after_login_3', $_postvars['p_redirect_after_login_3'], $m['mode_settings']);
			sm_update_settings('redirect_after_register', $_postvars['p_redirect_after_register'], $m['mode_settings']);
			sm_update_settings('redirect_after_logout', $_postvars['p_redirect_after_logout'], $m['mode_settings']);
			sm_update_settings('resource_url_rewrite', ($_postvars['resource_url_rewrite']=='1')?'1':'0', $m['mode_settings']);
			sm_update_settings('resource_url_mobile', $_postvars['resource_url_mobile'], $m['mode_settings']);
			sm_update_settings('resource_url_tablet', $_postvars['resource_url_tablet'], $m['mode_settings']);

			include('includes/config.php');
			$refresh_url='index.php?m=admin&d=settings&viewmode='.$m['mode_settings'];
		}
	if (strcmp($m['mode'], 'postchgttl')==0)
		{
			$module_title=dbescape($_postvars['p_title']);
			$sql="UPDATE ".$tableprefix."modules SET module_title = '$module_title' WHERE id_module='".$_getvars['mid']."'";;
			$result=execsql($sql);
			$m['mode']='view';
		}
	if (strcmp($m['mode'], 'postuplimg')==0)
		{
			$m['title']=$lang['upload_image'];
			$fs=$_uplfilevars['userfile']['tmp_name'];
			if (empty($_postvars['p_optional']))
				{
					$fd=basename($_uplfilevars['userfile']['name']);
				}
			else
				{
					$fd=$_postvars['p_optional'];
				}
			$fd='./files/img/'.$fd;
			$m['fs']=$fs;
			$m['fd']=$fd;
			if (!move_uploaded_file($fs, $fd)) 
				{
					$m['mode']='errorupload';
				}
			else
				{
					sm_event('afteruploadedimagesaveadmin', array($fd));
					$refresh_url='index.php?m=admin&d=listimg';
				}
		}
	if (strcmp($m['mode'], 'view')==0)
		{
			if (file_exists('includes/update.php'))
				{
					sm_update_settings('install_not_erased', 1);
				}
			$m["title"]=$lang['control_panel'];
			if (is_writeable('./'))
				$m['can_use_package']=1;
		}
	if (strcmp($m['mode'], 'uplimg')==0)
		{
			$m["title"]=$lang['upload_image'];
			add_path_control();
			add_path($lang['module_admin']['images_list'], 'index.php?m=admin&d=listimg');
		}
	if (strcmp($m['mode'], 'modules')==0)
		{
			add_path_modules();
			$m["title"]=$lang['modules_mamagement'];
			include_once('includes/admininterface.php');
			include_once('includes/admintable.php');
			include_once('includes/adminbuttons.php');
			$ui=new TInterface();
			$b=new TButtons();
			$b->AddButton('add', $lang['module_admin']['add_module'], 'index.php?m=admin&d=addmodule');
			$ui->AddButtons($b);
			$t=new TGrid();
			$t->AddCol('title', $lang['module']);
			$t->AddEdit();
			$t->AddDelete();
			$sql="SELECT * FROM ".$tableprefix."modules";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['modules'][$i]['title']=$row->module_title;
					$m['modules'][$i]['name']=$row->module_name;
					$m['modules'][$i]['id']=$row->id_module;
					$t->Label('title', $row->module_title);
					$t->Url('title', 'index.php?m='.$row->module_name.'&d=admin');
					$t->Url('edit', 'index.php?m=admin&d=chgttl&mid='.$row->id_module);
					$t->Url('delete', 'index.php?m='.$row->module_name.'&d=uninstall');
					$t->NewRow();
					$i++;
				}
			$ui->AddGrid($t);
			$ui->AddButtons($b);
			$ui->Output(true);
		}
	if (strcmp($m['mode'], 'chgttl')==0)
		{
			$m["title"]=$lang['change_title'];
			$sql="SELECT * FROM ".$tableprefix."modules WHERE id_module='".$_getvars['mid']."'";;
			$result=execsql($sql);
			while ($row=database_fetch_object($result))
				{
					$m['mod']['title']=$row->module_title;
					$m['mod']['name']=$row->module_name;
					$m['mod']['id']=$row->id_module;
				}
		}
	if (strcmp($m['mode'], 'copysettings')==0)
		{
			$m["title"]=$lang['settings'];
			if (!empty($_getvars['destmode']) && !empty($_getvars['name']))
				{
					$q=new TQuery($tableprefix."settings");
					$q->Add('name_settings', dbescape($_getvars['name']));
					$q->Add('value_settings', addslashes($_settings[$_getvars['name']]));
					$q->Add('mode', dbescape($_getvars['destmode']));
					$q->Insert();
				}
			sm_redirect('index.php?m=admin&d=settings');
		}
	if (strcmp($m['mode'], 'remsettings')==0)
		{
			$m["title"]=$lang['settings'];
			if (!empty($_getvars['destmode']) && !empty($_getvars['name']))
				{
					$q=new TQuery($tableprefix."settings");
					$q->Add('name_settings', dbescape($_getvars['name']));
					$q->Add('mode', dbescape($_getvars['destmode']));
					$q->Remove();
				}
			sm_redirect('index.php?m=admin&d=settings');
		}
	if (strcmp($m['mode'], 'settings')==0)
		{
			$m["title"]=$lang['settings'];
			if (!empty($_getvars['viewmode']))
				$m['mode_settings']=$_getvars['viewmode'];
			else
				$m['mode_settings']='default';
			//if ($m['mode_settings']=='default')
				{
					$m['list_modes'][0]['mode']='mobile';
					$m['list_modes'][0]['shortcut']='M';
					$m['list_modes'][0]['hint']=$lang['common']['device'].': '.$lang['common']['mobile_device'];
					$m['list_modes'][0]['profile']=$lang['common']['mobile_device'];
					$m['list_modes'][1]['mode']='tablet';
					$m['list_modes'][1]['shortcut']='T';
					$m['list_modes'][1]['hint']=$lang['common']['device'].': '.$lang['common']['tablet_device'];
					$m['list_modes'][1]['profile']=$lang['common']['tablet_device'];
				}
			if ($m['mode_settings']=='default')
				$m['available_modes']=$m['list_modes'];
			add_path_control();
			$sql="SELECT * FROM ".$tableprefix."settings WHERE mode='".$m['mode_settings']."'";
			$result=execsql($sql);
			while ($row=database_fetch_object($result))
				{
					$m['edit_settings'][$row->name_settings]=$row->value_settings;
					$m['show_settings'][$row->name_settings]=1;
				}
			for ($i=0; $i<count($m['available_modes']); $i++)
				{
					$sql="SELECT * FROM ".$tableprefix."settings WHERE mode='".$m['available_modes'][$i]['mode']."'";
					$result=execsql($sql);
					while ($row=database_fetch_object($result))
						{
							$m['extmodes'][$m['available_modes'][$i]['mode']]['show_settings'][$row->name_settings]=1;
						}
				}
			$dir=dir('./lang/');
			$i=0;
			while($entry=$dir->read()) 
			  {
			    if (strcmp($entry, '.')!=0 && strcmp($entry, '..')!=0 && strcmp($entry, 'index.html')!=0 && strpos($entry, '.php'))
					{
						$m['lang'][$i]=substr($entry, 0, strpos($entry, '.'));
						$i++;
					}
			  }
			$dir->close();
			$dir=dir('./themes/');
			$i=0;
			while($entry=$dir->read()) 
			  {
			    if (strcmp($entry, '.')!=0 && strcmp($entry, '..')!=0 && strcmp($entry, 'default')!=0 && strcmp($entry, 'index.html')!=0)
					{
						if (!file_exists('./files/themes/'.$entry)) continue;
						$m['themes'][$i]=$entry;
						$i++;
					}
			  }
			$dir->close();
			$dir=dir('./ext/editors/');
			$i=0;
			while($entry=$dir->read()) 
			  {
			    if (strcmp($entry, '.')!=0 && strcmp($entry, '..')!=0 && strcmp($entry, 'index.html')!=0)
					{
						$m['exteditors'][$i]=$entry;
						$i++;
					}
			  }
			$dir->close();
			$sql="SELECT * FROM ".$tableprefix."modules";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['modules'][$i]['title']=$row->module_title;
					$m['modules'][$i]['name']=$row->module_name;
					$m['modules'][$i]['id']=$row->id_module;
					$i++;
				}
			$sql="SELECT * FROM ".$tableprefix."menus";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['menus'][$i]['title']=$row->caption_m;
					$m['menus'][$i]['id']=$row->id_menu_m;
					$i++;
				}
		}
	if (strcmp($m['mode'], 'tstatus')==0)
		{
			$m["title"]=$lang['module_admin']['optimize_database'];
			$m["table_count"]=0;
			if ($serverDB==0)
				{
					$sql="SHOW TABLE STATUS FROM ".$nameDB;
					$result=execsql($sql);
					$i=0;
					while ($row=database_fetch_object($result))
						{
							$m['tables'][$i]['name']=$row->Name;
							$m['tables'][$i]['rows']=$row->Rows;
							$m['tables'][$i]['need_opt']=$row->Data_free;
							$m['tables'][$i]['data_length']=$row->Data_length+$row->Index_length;
							$i++;
						}
					$m["table_count"]=$i;
				}
		}
	if (strcmp($m['mode'], 'optimize')==0)
		{
			$m["title"]=$lang['module_admin']['optimize_database'];
			$tc=$_postvars['p_table_count'];
			if ($serverDB==0)
				{
					for ($i=0; $i<$tc; $i++)
						{
							if (isset($_postvars['p_opt_'.$i]))
								{
									$sql="OPTIMIZE TABLE ".$_postvars['p_opt_'.$i];
									$result=execsql($sql);
								}
						}
					$refresh_url='index.php?m=admin&d=tstatus';
				}
		}
	if (strcmp($m['mode'], 'viewimg')==0)
		{
			$m["title"]=$lang['common']['image'];
			$m["viewed_img_name"]=$_getvars['path'];
		}
	if (strcmp($m['mode'], 'listimg')==0)
		{
			$m["title"]=$lang['module_admin']['images_list'];
			require_once('includes/admintable.php');
			add_path_control();
			$m['table']['columns']['title']['caption']=$lang['module_admin']['image_file_name'];
			$m['table']['columns']['title']['width']='100%';
			//$m['table']['columns']['title']['url_target']='_blank';
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
			$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['module_admin']['really_want_delete_image']);
			$i=0;
			$j=-1;
			$files=load_file_list('./files/img/');
			while ($j+1<count($files)) 
			  {
			  	$j++;
				$entry=$files[$j];
			    if (!empty($_getvars['filter']))
					if (strpos($entry, $_getvars['filter'])!==0)
						continue;
				if (strcmp($entry, '.')!=0 && strcmp($entry, '..')!=0 && strcmp($entry, 'index.html')!=0)
					{
						$m['table']['rows'][$i]['title']['data']=$entry;
						$m['table']['rows'][$i]['title']['url']='index.php?m=admin&d=viewimg&path='.$entry;
						$m['table']['rows'][$i]['edit']['url']='index.php?m=admin&d=renimg&imgn='.$entry;
						$m['table']['rows'][$i]['delete']['url']='index.php?m=admin&d=postdelimg&imgn='.$entry;
						$i++;
					}
			  }
		}
	if (strcmp($m['mode'], 'delimg')==0)
		{
			$_msgbox['mode']='yesno';
			$_msgbox['title']=$lang['module_admin']['delete_image'];
			$_msgbox['msg']=$lang['module_admin']['really_want_delete_image'].' "'.$_getvars["imgn"].'"';
			$_msgbox['yes']='index.php?m=admin&d=postdelimg&imgn='.$_getvars["imgn"];
			$_msgbox['no']='index.php?m=admin&d=listimg';
		}
	if (strcmp($m['mode'], 'postdelimg')==0)
		{
			$m["title"]=$lang['module_admin']['delete_image'];
			$img=$_getvars["imgn"];
			if (!strpos($img, '..') && !strpos($img, '/') && !strpos($img, '\\'))
				unlink('./files/img/'.$img);
			$refresh_url='index.php?m=admin&d=listimg';
		}
	if (strcmp($m['mode'], 'postrenimg')==0)
		{
			$m["title"]=$lang['module_admin']['rename_image'];
			$img1=$_getvars["on"];
			$img2=$_getvars["nn"];
			if (!(!strpos($img1, '..') && !strpos($img1, '/') && !strpos($img1, '\\') && !strpos($img2, '..') && !strpos($img2, '/') && !strpos($img2, '\\')) || empty($img1) || empty($img2))
				{
					$m["error_message"]=$lang['module_admin']['message_wrong_file_name'];
				}
			else
				{
					if (!rename('files/img/'.$img1, 'files/img/'.$img2))
						$m["error_message"]=$lang['module_admin']['message_cant_reaname'];
				}
			if (empty($m["error_message"]))
				$refresh_url='index.php?m=admin&d=listimg';
			else
				{
					$m['mode']='renimg';
					$_getvars["imgn"]=$img1;
				}
		}
	if (strcmp($m['mode'], 'renimg')==0)
		{
			$m["title"]=$lang['module_admin']['rename_image'];
			$m['image']['old_name']=$_getvars["imgn"];
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['module_admin']['images_list'], "index.php?m=admin&d=listimg");
		}
	if (strcmp($m['mode'], 'massemail')==0)
		{
			$m['title']=$lang['module_admin']['mass_email'];
			if (!empty($_settings['ext_editor']))
				{
					$special['ext_editor_on']=1;
					$m['email']['text']=siman_prepare_to_exteditor(nl2br($_settings['email_signature']));
				}
			else
				$m['email']['text']=$_settings['email_signature'];
		}
	if (strcmp($m['mode'], 'postmassemail')==0)
		{
			$m['title']=$lang['module_admin']['mass_email'];
			$sql="SELECT * FROM ".$tableusersprefix."users WHERE get_mail=1";
			$result=execsql($sql);
			$i=0;
			if (empty($_settings['ext_editor']))
				$msg=nl2br($_postvars['p_body']);
			else
				$msg=$_postvars['p_body'];
			while ($row=database_fetch_object($result))
				{
					send_mail($_settings['resource_title']." <".$_settings['administrators_email'].">", $row->email, $_postvars['p_theme'], $_postvars['p_body']);
				}
			$refresh_url='index.php?m=admin';
		}
	if (strcmp($m['mode'], 'remmodule')==0)
		{
			$_msgbox['mode']='yesno';
			$_msgbox['title']=$lang['module_admin']['remove_module'];
			$_msgbox['msg']=$lang['module_admin']['really_want_remove_module'];
			$_msgbox['yes']='index.php?m=admin&d=postremmodule&mid='.$_getvars["mid"];
			$_msgbox['no']='index.php?m=admin&d=modules';
		}
	if (strcmp($m['mode'], 'postremmodule')==0)
		{
			$m["title"]=$lang['module_admin']['remove_module'];
			$sql="DELETE FROM ".$tableprefix."modules WHERE id_module = '".$_getvars['mid']."'";
			$result=execsql($sql);
			$refresh_url='index.php?m=admin&d=modules';
		}
	if (strcmp($m['mode'], 'addmodule')==0)
		{
			add_path_modules();
			$modename=$_getvars['mn'];
			$m['title']=$lang['module_admin']['add_module'];
			$dir=dir('./modules/');
			$i=0;
			while($entry=$dir->read()) 
			  {
				  if (strpos($entry, '.php')>0)
					{
						if ($entry!='admin.php')
							$info=sm_get_module_info('./modules/'.$entry);
						if (
								!file_exists('./themes/'.$_settings['default_theme'].'/'.substr($entry, 0, -4).'.tpl')
									&&
								!file_exists('./themes/default/'.substr($entry, 0, -4).'.tpl')
									&&
								$info==false
							)
							continue;
						$m['modules'][substr($entry, 0, -4)]['present']=1;
						$m['modules'][substr($entry, 0, -4)]['info']=$info;
						$i++;
					}
			  }
			$dir->close();
			$m['modules']['account']['disabled']=1;
			$m['modules']['admin']['disabled']=1;
			$m['modules']['blocks']['disabled']=1;
			$m['modules']['geturl']['disabled']=1;
			$m['modules']['index']['disabled']=1;
			$m['modules']['msgbox']['disabled']=1;
			$m['modules']['refresh']['disabled']=1;
			$sql="SELECT * FROM ".$tableprefix."modules";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['modules'][$row->module_name]['disabled']=1;
					$i++;
				}
			$i=0;
			while ( list( $key, $val ) = each($m['modules']) )
			  {
			  	if ($m['modules'][$key]['disabled']!=1)
					{
						$m['addmodules'][$i]=$key;
						$i++;
					}
			  }
		}
	if (strcmp($m['mode'], 'postaddmodule')==0)
		{
			$m["title"]=$lang['module_admin']['remove_module'];
			$mname=$_getvars['modname'];
			$sql="INSERT INTO ".$tableprefix."modules (module_name, module_title) VALUES ('$mname', '$mname')";
			$result=execsql($sql);
			$refresh_url='index.php?m=admin&d=modules';
		}
	if (strcmp($m['mode'], 'filesystem')==0)
		{
			$m["title"]=$lang['module_admin']['virtual_filesystem'];
			require_once('includes/admintable.php');
			$m['table']['columns']['ico']['caption']='';
			$m['table']['columns']['ico']['width']='16';
			$m['table']['columns']['title']['caption']=$lang['common']['title'];
			$m['table']['columns']['title']['width']='100%';
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
			$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['module_admin']['really_want_delete_filesystem_item']);
			$m['table']['columns']['tomenu']['caption']='';
			$m['table']['columns']['tomenu']['hint']=$lang['module_menu']['add_to_menu'];
			$m['table']['columns']['tomenu']['replace_text']=$lang['module_menu']['add_to_menu'];
			$m['table']['columns']['tomenu']['to_menu']=1;
			$m['table']['default_column']='edit';
			$sql="SELECT * FROM ".$tableprefix."filesystem ORDER BY filename_fs ASC";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					if ($row->filename_fs[strlen($row->filename_fs)-1]=='/')
						$m['table']['rows'][$i]['ico']['image']='folder.gif';
					else
						$m['table']['rows'][$i]['ico']['image']='file.gif';
					$m['table']['rows'][$i]['ico']['hint']=$row->id_fs;
					$m['table']['rows'][$i]['title']['data']=$row->filename_fs;
					$m['table']['rows'][$i]['title']['hint']=$row->comment_fs;
					$m['table']['rows'][$i]['title']['url']=$row->url_fs;
					$m['table']['rows'][$i]['edit']['url']='index.php?m=admin&d=editfilesystem&id='.$row->id_fs;
					$m['table']['rows'][$i]['delete']['url']='index.php?m=admin&d=postdeletefilesystem&id='.$row->id_fs;
					$m['table']['rows'][$i]['tomenu']['menu_url']=addslashes($row->filename_fs);
					$m['table']['rows'][$i]['tomenu']['menu_caption']=addslashes($row->comment_fs);
					$i++;
				}
		}
	if (strcmp($m["mode"], 'postdeletefilesystem')==0)
		{
			$m['title']=$lang['common']['delete'];
			$sql="DELETE FROM ".$tableprefix."filesystem WHERE id_fs='".intval($_getvars["id"])."'";
			$result=execsql($sql);
			$refresh_url='index.php?m=admin&d=filesystem';
		}
	if (strcmp($m["mode"], 'addfilesystem')==0)
		{
			$m['title']=$lang['common']['add'];
			$m['row']=get_filesystem(intval($_getvars["id"]));
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['module_admin']['virtual_filesystem'], "index.php?m=admin&d=filesystem");
		}
	if (strcmp($m["mode"], 'editfilesystem')==0)
		{
			$m['title']=$lang['common']['edit'];
			$m['row']=get_filesystem(intval($_getvars["id"]));
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['module_admin']['virtual_filesystem'], "index.php?m=admin&d=filesystem");
		}
	if (strcmp($m["mode"], 'posteditfilesystem')==0)
		{
			$m['title']=$lang['common']['edit'];
			update_filesystem(intval($_getvars['id']), $_postvars['p_url'], $_postvars['p_filename'], $_postvars['p_comment']);
			$refresh_url='index.php?m=admin&d=filesystem';
		}
	if (strcmp($m["mode"], 'postaddfilesystem')==0)
		{
			$m['title']=$lang['common']['add'];
			register_filesystem($_postvars['p_url'], $_postvars['p_filename'], $_postvars['p_comment']);
			$refresh_url='index.php?m=admin&d=filesystem';
		}
	if (strcmp($m['mode'], 'filesystemexp')==0)
		{
			$m["title"]=$lang['module_admin']['virtual_filesystem_regexp'];
			require_once('includes/admintable.php');
			$m['table']['columns']['regexp']['caption']=$lang['module_admin']['regexp'];
			$m['table']['columns']['regexp']['width']='50%';
			$m['table']['columns']['replace']['caption']=$lang['module_admin']['regexp_replace'];
			$m['table']['columns']['replace']['width']='50%';
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
			$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['module_admin']['really_want_delete_filesystem_item']);
			$m['table']['default_column']='edit';
			$sql="SELECT * FROM ".$tableprefix."filesystem_regexp ORDER BY regexpr ASC";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['table']['rows'][$i]['regexp']['data']=$row->regexpr;
					$m['table']['rows'][$i]['replace']['data']=$row->url;
					$m['table']['rows'][$i]['edit']['url']='index.php?m=admin&d=editfilesystemexp&id='.$row->id_fsr;
					$m['table']['rows'][$i]['delete']['url']='index.php?m=admin&d=postdeletefilesystemexp&id='.$row->id_fsr;
					$i++;
				}
		}
	if (strcmp($m["mode"], 'postdeletefilesystemexp')==0)
		{
			$m['title']=$lang['common']['delete'];
			$sql="DELETE FROM ".$tableprefix."filesystem_regexp WHERE id_fsr='".intval($_getvars["id"])."'";
			$result=execsql($sql);
			$refresh_url='index.php?m=admin&d=filesystemexp';
		}
	if (strcmp($m["mode"], 'addfilesystemexp')==0)
		{
			$m['title']=$lang['common']['add'];
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['module_admin']['virtual_filesystem_regexp'], "index.php?m=admin&d=filesystemexp");
			require_once('includes/adminform.php');
			$f=new TForm('index.php?m=admin&d=postaddfilesystemexp');
			$f->AddText('regexpr', $lang['module_admin']['regexp']);
			$f->AddText('url', $lang['module_admin']['regexp_replace']);
			$m['form']=$f->Output();
		}
	if (strcmp($m["mode"], 'editfilesystemexp')==0)
		{
			$m['title']=$lang['common']['edit'];
			add_path($lang['control_panel'], "index.php?m=admin");
			add_path($lang['module_admin']['virtual_filesystem_regexp'], "index.php?m=admin&d=filesystemexp");
			require_once('includes/adminform.php');
			$f=new TForm('index.php?m=admin&d=posteditfilesystemexp&id='.$_getvars['id']);
			$f->AddText('regexpr', $lang['module_admin']['regexp']);
			$f->AddText('url', $lang['module_admin']['regexp_replace']);
			$f->LoadValues("SELECT * FROM ".$tableprefix.'filesystem_regexp WHERE id_fsr='.intval($_getvars['id']));
			$m['form']=$f->Output();
		}
	if (strcmp($m["mode"], 'posteditfilesystemexp')==0)
		{
			$m['title']=$lang['common']['edit'];
			$q=new TQuery($tableprefix.'filesystem_regexp');
			$q->AddPost('regexpr');
			$q->AddPost('url');
			$q->Update('id_fsr', intval($_getvars['id']));
			sm_redirect('index.php?m=admin&d=filesystemexp');
		}
	if (strcmp($m["mode"], 'postaddfilesystemexp')==0)
		{
			$m['title']=$lang['common']['add'];
			$q=new TQuery($tableprefix.'filesystem_regexp');
			$q->AddPost('regexpr');
			$q->AddPost('url');
			$q->Insert();
			sm_redirect('index.php?m=admin&d=filesystemexp');
		}
	if (strcmp($m['mode'], 'viewlog')==0)
		{
			add_path_control();
			$sql="DELETE FROM ".$tableusersprefix."log WHERE time<".(time()-$_settings['log_store_days']*3600*24);
			$result=execsql($sql);
			$m["title"]=$lang['module_admin']['view_log'];
			require_once('includes/admintable.php');
			$m['table']['columns']['time']['caption']=$lang['common']['time'];
			$m['table']['columns']['time']['width']='20%';
			$m['table']['columns']['description']['caption']=$lang['common']['description'];
			$m['table']['columns']['description']['width']='60%';
			$m['table']['columns']['ip']['caption']='IP';
			$m['table']['columns']['ip']['width']='10%';
			$m['table']['columns']['user']['caption']=$lang['user'];
			$m['table']['columns']['user']['width']='10%';
			$sql="SELECT *, INET_NTOA(ip) AS stringip FROM ".$tableusersprefix."log ORDER BY id_log DESC";
			$result=execsql($sql);
			$i=0;
			while ($row=database_fetch_object($result))
				{
					$m['table']['rows'][$i]['time']['data']=strftime($lang["datetimemask"], $row->time);;
					$m['table']['rows'][$i]['description']['data']=htmlspecialchars($row->description);
					$m['table']['rows'][$i]['ip']['data']=$row->stringip;
					$m['table']['rows'][$i]['user']['data']=$row->user;
					$i++;
				}
		}
	if (strcmp($m['mode'], 'package')==0)
		{
			$m["title"]=$lang['module_admin']['upload_package'];
			add_path($lang['control_panel'], 'index.php?m=admin');
			if (function_exists('curl_init'))
				$m["can_upload_from_server"]=1;
		}
	if (strcmp($m['mode'], 'postpackage')==0)
		{
			$m['title']=$lang['module_admin']['upload_package'];
			$message='';
			if (empty($_getvars['typeupload']))
				{
					$fs=$_uplfilevars['userfile']['tmp_name'];
					$fd=basename($_uplfilevars['userfile']['name']);
					$fd='./'.$fd;
					$m['fs']=$fs;
					$m['fd']=$fd;
					if (!move_uploaded_file($fs, $fd))
						{
							$m['mode']='errorupload';
						}
				}
			elseif (function_exists('curl_init'))
				{
					$ch = curl_init($_postvars['urlupload']);
					if (file_exists('urlupload.zip'))
						unlink('urlupload.zip');
					$fp = fopen("urlupload.zip", "w");
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_FAILONERROR, 1);
					curl_exec($ch);
					$tmperr=curl_error($ch);
					curl_close($ch);
					fclose($fp);
					if (!empty($tmperr))
						{
							$m['mode']='errorupload';
							$m['error_message']=$tmperr;
							unlink('urlupload.zip');
						}
					else
						$fd='urlupload.zip';
				}
			if ($m['mode']!='errorupload')
				{
					require_once('ext/package/unarchiver.php');
					$zip = new PclZip($fd);
					$ext = $zip->extract(PCLZIP_OPT_SET_CHMOD, 0777);
					unlink($fd);
					if (file_exists('includes/update.php'))
						{
							include('includes/update.php');
							@unlink('includes/update.php');
							if (file_exists('includes/update.php') && empty($refresh_url))
								sm_update_settings('install_not_erased', 1);
						}
					if (empty($refresh_url))
						sm_redirect('index.php?m=admin&d=view', $message);
				}
		}
	if (strcmp($m['mode'], 'errorupload')==0)
		{
			$m["title"]=$lang['upload'];
		}
}


?>