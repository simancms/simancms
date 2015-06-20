<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: SiMan CMS Demo
	Module URI: http://simancms.org/modules/demo/
	Description: Examples of usage
	Version: 1.6.9
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("demo_FUNCTIONS_DEFINED"))
		{
			define("demo_FUNCTIONS_DEFINED", 1);
		}

	if (sm_is_installed(sm_current_module()) && $userinfo['level'] > 0)
		{
			if ($userinfo['level'] == 3)
				{
					if (sm_action('admin'))
						{
							add_path_modules();
							add_path('Demo', 'index.php?m=demo&d=admin');
							sm_use('ui.interface');
							sm_title('Demo');
							$ui = new TInterface();
							$ui->Output(true);

						}
					if (sm_action('install'))
						{
							sm_register_module('demo', $lang['module_demo']['module_demo']);
							sm_redirect('index.php?m=admin&d=modules');
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('demo');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
		}
	if (!sm_is_installed(sm_current_module()) && $userinfo['level'] == 3)
		{
			if (sm_action('install'))
				{
					sm_register_module('demo', 'Demo');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>