<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//------------------------------------------------------------------------------

	/*
	Module Name: YouTube Replacer
	Module URI: http://simancms.org/
	Description: YouTube links replacement to embed code
	Version: 2016-07-02
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] == 3)
		{
			if (sm_action('admin'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/admintable.php');
					add_path_modules();
					add_path('YouTube replacer', 'index.php?m=youtubereplacer&d=admin');
					sm_title($lang['settings']);
					$ui = new TInterface();
					$ui->Output(true);
				}
			if (strcmp($m["mode"], 'install') == 0)
				{
					sm_register_module('youtubereplacer', 'YouTube replacer');
					sm_add_settings('youtubereplaceron', 1);
					sm_add_settings('youtubereplacerwidth', 480);
					sm_add_settings('youtubereplacerheight', 385);
					sm_add_settings('youtubereplacershowyturl', 1);
					sm_add_settings('youtubereplacerstarthtml', '<br />');
					sm_add_settings('youtubereplacerendhtml', '<br />');
					sm_register_postload('youtubereplacer');
					sm_notify($lang['operation_complete']);
					sm_redirect('index.php?m=admin&d=modules');
				}
			if (strcmp($m["mode"], 'uninstall') == 0)
				{
					sm_unregister_module('youtubereplacer');
					sm_delete_settings('youtubereplaceron');
					sm_delete_settings('youtubereplacerwidth');
					sm_delete_settings('youtubereplacerheight');
					sm_delete_settings('youtubereplacershowyturl');
					sm_delete_settings('youtubereplacerstarthtml');
					sm_delete_settings('youtubereplacerendhtml');
					sm_unregister_postload('youtubereplacer');
					sm_notify($lang['operation_complete']);
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>