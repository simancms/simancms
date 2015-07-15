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
			sm_default_action('demos');
			if (sm_action('htmlshortcuts'))
				{
					sm_title('UI HTML-shortcuts');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					$ui = new TInterface();
					$ui->p('Paragraph simple');
					$ui->p_open();
					$ui->html('Paragraph open');
					$ui->br();
					$ui->html('BR tag');
					$ui->hr();
					$ui->html('HR tag');
					$ui->br();
					$ui->html('Paragraph close');
					$ui->p_close();
					$ui->div('Div with classname demo-red', '', 'demo-red');
					$ui->div('Div with style', '', '', 'background:#ccccff;');
					$ui->h(1, 'H1');
					$ui->h(2, 'H2');
					$ui->h(3, 'H3');
					$ui->h(4, 'H4');
					$ui->h(5, 'H5');
					$ui->h(6, 'H6');
					$ui->a(sm_homepage(), 'Clickable URL');
					$ui->style('.demo-red{background:#ffcccc;}');
					$ui->Output(true);
				}
			if (sm_action('regular'))
				{
					sm_title('Smarty Template');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					$m['module']='demo';
				}
			if (sm_action('demos'))
				{
					sm_title('Available Demos');
					add_path_home();
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$nav=new TNavigation();
					$nav->AddItem('UI HTML-shortcuts', 'index.php?m=demo&d=htmlshortcuts');
					$nav->AddItem('Smarty Template', 'index.php?m=demo&d=regular');
					$ui->Add($nav);
					$ui->Output(true);
				}
			if ($userinfo['level'] == 3)
				{
					if (sm_action('admin'))
						{
							add_path_modules();
							add_path('Demo', 'index.php?m=demo&d=admin');
							sm_use('ui.interface');
							sm_title('Demo');
							$ui = new TInterface();
							$ui->a('index.php?m=demo', 'View Demos');
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