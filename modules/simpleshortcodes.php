<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Simple Shortcodes
	Module URI: http://simancms.org/modules/simpleshortcodes/
	Description: Simple shortcodes to use in your texts
	Version: 2018-08-01
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] == 3)
		{
			sm_include_lang('simpleshortcodes');
			if (sm_action('admin'))
				{
					sm_template('simpleshortcodes');
					add_path_modules();
					add_path($lang['module_simpleshortcodes']['module_simpleshortcodes'], 'index.php?m=simpleshortcodes&d=admin');
					sm_title($lang['settings']);
				}
			if (sm_action('install'))
				{
					sm_register_module('simpleshortcodes', $lang['module_simpleshortcodes']['module_simpleshortcodes']);
					sm_register_autoload('simpleshortcodes');
					sm_register_postload('simpleshortcodes');
					sm_redirect('index.php?m=admin&d=modules');
				}
			if (sm_action('uninstall'))
				{
					sm_unregister_module('simpleshortcodes');
					sm_unregister_autoload('simpleshortcodes');
					sm_unregister_postload('simpleshortcodes');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

