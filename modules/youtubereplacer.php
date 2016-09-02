<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//------------------------------------------------------------------------------

	/*
	Module Name: YouTube Replacer
	Module URI: http://simancms.org/
	Description: YouTube links replacement to embed code
	Version: 2016-09-02
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] == 3)
		{
			sm_include_lang('youtubereplacer');
			if (sm_action('savesettings'))
				{
					sm_add_settings('youtubereplaceron', intval($_postvars['youtubereplaceron']));
					sm_notify($lang['messages']['settings_updated']);
					sm_redirect('index.php?m='.sm_current_module().'&d=admin');
				}
			if (sm_action('admin'))
				{
					sm_use('ui.interface');
					sm_use('ui.form');
					add_path_modules();
					add_path($lang['module_youtubereplacer']['module_youtubereplacer'], 'index.php?m=youtubereplacer&d=admin');
					sm_title($lang['module_youtubereplacer']['module_youtubereplacer'].' - '.$lang['settings']);
					$ui = new TInterface();
					if (!empty($error_message))
						$ui->NotificationError($error_message);
					$f=new TForm('index.php?m='.sm_current_module().'&d=savesettings');
					$f->AddSelectVL('youtubereplaceron', $lang['status'], Array(1, 0), Array($lang['common']['enabled'], $lang['common']['disabled']))->WithValue(sm_settings('youtubereplaceron'));
					$ui->AddForm($f);
					$ui->Output(true);
				}
			if (strcmp($m["mode"], 'install') == 0)
				{
					sm_register_module('youtubereplacer', $lang['module_youtubereplacer']['module_youtubereplacer']);
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
