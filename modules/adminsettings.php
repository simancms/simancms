<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Settings (Expert Mode)
	Module URI: http://simancms.org/modules/adminsettings/
	Description: Manage default settings in expert mode.
	Version: 2014-01-13
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($userinfo['level'] == 3)
		{
			if (empty($m["mode"])) $m["mode"] = 'admin';
			sm_include_lang('adminsettings');
			$m["module"] = 'adminsettings';
			if (strcmp($m["mode"], 'postadd') == 0)
				{
					$m['title'] = $lang['settings'];
					$name_settings = $_postvars['p_name'];
					$value_settings = dbescape($_postvars['p_value']);
					if (!empty($name_settings))
						{
							$sql = "INSERT INTO ".$tableprefix."settings (name_settings, value_settings) VALUES ('".$name_settings."', '".$value_settings."')";
							$result = database_db_query($nameDB, $sql, $lnkDB);
						}
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($m["mode"], 'addeditor') == 0 || strcmp($m["mode"], 'addhtml') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					$m['title'] = $lang['common']['add'];
				}
			if (strcmp($m["mode"], 'edit') == 0 || strcmp($m["mode"], 'html') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					$m['title'] = $lang['common']['edit'];
					$m['name_settings'] = $_getvars['param'];
					if (function_exists('siman_prepare_to_exteditor'))
						$m['value_settings_ext'] = siman_prepare_to_exteditor($_settings[$_getvars['param']]);
					else
						$m['value_settings_ext'] = $_settings[$_getvars['param']];
					$m['value_settings_html'] = htmlspecialchars($_settings[$_getvars['param']]);
				}
			if (strcmp($m["mode"], 'admin') == 0)
				{
					add_path_modules();
					add_path($lang['module_adminsettings']['module_adminsettings'], 'index.php?m=adminsettings&d=admin');
					require_once('includes/admintable.php');
					$m['title'] = $lang['settings'];
					$t=new TGrid('edit');
					$t->AddCol('title', $lang['common']['title'], '100%');
					$t->AddEdit();
					$t->AddCol('html', '', '16', $lang['common']['edit'].' ('.$lang['common']['html'].')', '', 'edit_html.gif');
					$t->AddDelete();
					$sql = "SELECT * FROM ".$tableprefix."settings WHERE mode='default'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$t->Label('title', $row->name_settings);
							$t->Hint('title', strip_tags($row->value_settings));
							$t->URL('edit', 'index.php?m=adminsettings&d=edit&param='.$row->name_settings);
							$t->URL('html', 'index.php?m=adminsettings&d=edit&param='.$row->name_settings);
							$t->URL('delete', 'index.php?m=adminsettings&d=postdelete&param='.$row->name_settings);
							$t->NewRow();
						}
					$m['table']=$t->Output();
				}
			if (strcmp($m["mode"], 'postedit') == 0)
				{
					$m['title'] = $lang['settings'];
					$name_settings = dbescape($_postvars['p_name']);
					$value_settings = dbescape($_postvars['p_value']);
					$sql = "UPDATE ".$tableprefix."settings SET name_settings='".$name_settings."', value_settings='".$value_settings."' WHERE mode='default' AND name_settings='".$_getvars['param']."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($m["mode"], 'postdelete') == 0)
				{
					$m['title'] = $lang['settings'];
					$name_settings = $_postvars['p_name'];
					$value_settings = dbescape($_postvars['p_value']);
					$sql = "DELETE FROM ".$tableprefix."settings WHERE mode='default' AND name_settings='".$_getvars['param']."'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					$refresh_url = 'index.php?m=adminsettings&d=admin';
				}
			if (strcmp($m["mode"], 'install') == 0)
				{
					$m['title'] = $lang['common']['install'];
					sm_register_module('adminsettings', $lang['module_adminsettings']['module_adminsettings']);
					$refresh_url = 'index.php?m=admin&d=modules';
				}
			if (strcmp($m["mode"], 'uninstall') == 0)
				{
					$m['title'] = $lang['common']['install'];
					sm_unregister_module('adminsettings');
					$refresh_url = 'index.php?m=admin&d=modules';
				}
		}

?>