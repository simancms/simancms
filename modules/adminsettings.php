<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|         Система керування вмістом сайту SiMan CMS                          |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#verCMS 1.6.4
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($userinfo['level'] == 3)
		{
			if (empty($modules[$modules_index]["mode"])) $modules[$modules_index]["mode"] = 'admin';
			sm_include_lang('adminsettings');
			$modules[$modules_index]["module"] = 'adminsettings';
			if (strcmp($modules[$modules_index]["mode"], 'postadd') == 0)
				{
					$modules[$modules_index]['title'] = $lang['settings'];
					$name_settings = $_postvars['p_name'];
					$value_settings = addslashesJ($_postvars['p_value']);
					if (!empty($name_settings))
						{
							$sql = "INSERT INTO ".$tableprefix."settings (name_settings, value_settings) VALUES ('".$name_settings."', '".$value_settings."')";
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($modules[$modules_index]["mode"], 'addeditor') == 0 || strcmp($modules[$modules_index]["mode"], 'addhtml') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					$modules[$modules_index]['title'] = $lang['common']['add'];
				}
			if (strcmp($modules[$modules_index]["mode"], 'edit') == 0 || strcmp($modules[$modules_index]["mode"], 'html') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					$modules[$modules_index]['title'] = $lang['common']['edit'];
					$modules[$modules_index]['name_settings'] = $_getvars['param'];
					if (function_exists('siman_prepare_to_exteditor'))
						$modules[$modules_index]['value_settings_ext'] = siman_prepare_to_exteditor($_settings[$_getvars['param']]);
					else
						$modules[$modules_index]['value_settings_ext'] = $_settings[$_getvars['param']];
					$modules[$modules_index]['value_settings_html'] = htmlspecialchars($_settings[$_getvars['param']]);
				}
			if (strcmp($modules[$modules_index]["mode"], 'admin') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					require_once('includes/admintable.php');
					$modules[$modules_index]['title'] = $lang['settings'];
					$modules[$modules_index]['table']['columns']['title']['caption'] = $lang['common']['title'];
					$modules[$modules_index]['table']['columns']['title']['width'] = '100%';
					$modules[$modules_index]['table']['columns']['edit']['caption'] = '';
					$modules[$modules_index]['table']['columns']['edit']['hint'] = $lang['common']['edit'];
					$modules[$modules_index]['table']['columns']['edit']['replace_text'] = $lang['common']['edit'];
					$modules[$modules_index]['table']['columns']['edit']['replace_image'] = 'edit.gif';
					$modules[$modules_index]['table']['columns']['edit']['width'] = '16';
					$modules[$modules_index]['table']['columns']['html']['caption'] = '';
					$modules[$modules_index]['table']['columns']['html']['hint'] = $lang['common']['edit'].' ('.$lang['common']['html'].')';
					$modules[$modules_index]['table']['columns']['html']['replace_text'] = $lang['common']['html'];
					$modules[$modules_index]['table']['columns']['html']['replace_image'] = 'edit_html.gif';
					$modules[$modules_index]['table']['columns']['html']['width'] = '16';
					$modules[$modules_index]['table']['columns']['delete']['caption'] = '';
					$modules[$modules_index]['table']['columns']['delete']['hint'] = $lang['common']['delete'];
					$modules[$modules_index]['table']['columns']['delete']['replace_text'] = $lang['common']['delete'];
					$modules[$modules_index]['table']['columns']['delete']['replace_image'] = 'delete.gif';
					$modules[$modules_index]['table']['columns']['delete']['width'] = '16';
					$modules[$modules_index]['table']['columns']['delete']['messagebox'] = 1;
					$modules[$modules_index]['table']['columns']['delete']['messagebox_text'] = addslashes($lang['module_adminsettings']['really_want_delete']);
					$modules[$modules_index]['table']['default_column'] = 'edit';
					$sql = "SELECT * FROM ".$tableprefix."settings WHERE mode='default'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$modules[$modules_index]['table']['rows'][$i]['title']['data'] = $row->name_settings;
							$modules[$modules_index]['table']['rows'][$i]['title']['hint'] = strip_tags($row->value_settings);
							$modules[$modules_index]['table']['rows'][$i]['edit']['url'] = 'index.php?m=adminsettings&d=edit&param='.$row->name_settings;
							$modules[$modules_index]['table']['rows'][$i]['html']['url'] = 'index.php?m=adminsettings&d=html&param='.$row->name_settings;
							$modules[$modules_index]['table']['rows'][$i]['delete']['url'] = 'index.php?m=adminsettings&d=postdelete&param='.$row->name_settings;
							$i++;
						}
				}
			if (strcmp($modules[$modules_index]["mode"], 'postedit') == 0)
				{
					$modules[$modules_index]['title'] = $lang['settings'];
					$name_settings = $_postvars['p_name'];
					$value_settings = addslashesJ($_postvars['p_value']);
					$sql = "UPDATE ".$tableprefix."settings SET name_settings='".$name_settings."', value_settings='".$value_settings."' WHERE mode='default' AND name_settings='".$_getvars['param']."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($modules[$modules_index]["mode"], 'postdelete') == 0)
				{
					$modules[$modules_index]['title'] = $lang['settings'];
					$name_settings = $_postvars['p_name'];
					$value_settings = addslashesJ($_postvars['p_value']);
					$sql = "DELETE FROM ".$tableprefix."settings WHERE mode='default' AND name_settings='".$_getvars['param']."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($modules[$modules_index]["mode"], 'install') == 0)
				{
					$modules[$modules_index]['title'] = $lang['common']['install'];
					sm_register_module('adminsettings', $lang['module_adminsettings']['module_adminsettings']);
					$refresh_url = 'index.php?m=admin&d=modules';
				}
			if (strcmp($modules[$modules_index]["mode"], 'uninstall') == 0)
				{
					$modules[$modules_index]['title'] = $lang['common']['install'];
					sm_unregister_module('adminsettings');
					$refresh_url = 'index.php?m=admin&d=modules';
				}
		}

?>