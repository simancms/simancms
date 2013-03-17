<?php

//------------------------------------------------------------------------------
//|            Content Management System SiMan CMS                             |
//------------------------------------------------------------------------------

//==============================================================================
//#verCMS 1.6.2                                                                |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if (!defined("simpleshortcodes_FUNCTIONS_DEFINED"))
	{
		define("simpleshortcodes_FUNCTIONS_DEFINED", 1);
	}

$m=&$modules[$modules_index];

if ($userinfo['level']==3)
	{
		sm_include_lang('simpleshortcodes');
		if (strcmp($m["mode"], 'admin')==0)
			{
				$m["module"]='simpleshortcodes';
				add_path_modules();
				add_path($lang['module_simpleshortcodes']['module_simpleshortcodes'], 'index.php?m=simpleshortcodes&d=admin');
				$m['title']=$lang['settings'];
			}
		if (strcmp($m["mode"], 'install')==0)
			{
				$m['title']=$lang['common']['install'];
				$m["module"]='simpleshortcodes';
				sm_register_module('simpleshortcodes', $lang['module_simpleshortcodes']['module_simpleshortcodes']);
				sm_register_autoload('simpleshortcodes');
				sm_register_postload('simpleshortcodes');
				sm_redirect('index.php?m=admin&d=modules');
			}
		if (strcmp($m["mode"], 'uninstall')==0)
			{
				$m['title']=$lang['common']['install'];
				$m["module"]='simpleshortcodes';
				sm_unregister_module('simpleshortcodes');
				sm_unregister_autoload('simpleshortcodes');
				sm_unregister_postload('simpleshortcodes');
				sm_redirect('index.php?m=admin&d=modules');
			}
	}

?>