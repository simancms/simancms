<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//------------------------------------------------------------------------------

	/*
	Module Name: YouTube Replacer
	Module URI: http://simancms.org/
	Description: YouTube links replacement to embed code
	Version: 2016-09-06
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
					if (intval($_postvars['youtubereplacerwidth'])<=0 || intval($_postvars['youtubereplacerheight'])<=0)
						$error_message=$lang['messages']['wrong_value'];
					if (empty($error_message))
						{
							sm_add_settings('youtubereplaceron', intval($_postvars['youtubereplaceron']));
							sm_add_settings('youtubereplacershowyturl', intval($_postvars['youtubereplacershowyturl']));
							sm_add_settings('youtubereplacerwidth', intval($_postvars['youtubereplacerwidth']));
							sm_add_settings('youtubereplacerheight', intval($_postvars['youtubereplacerheight']));
							sm_add_settings('youtubereplacerstarthtml', $_postvars['youtubereplacerstarthtml']);
							sm_add_settings('youtubereplacerendhtml', $_postvars['youtubereplacerendhtml']);
							sm_notify($lang['messages']['settings_updated']);
							sm_redirect('index.php?m='.sm_current_module().'&d=admin');
						}
					else
						sm_set_action('admin');
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
					$f->AddSelectVL('youtubereplacershowyturl', $lang['module_youtubereplacer']['append_youtube_url'], Array(1, 0), Array($lang['yes'], $lang['no']))->WithValue(sm_settings('youtubereplacershowyturl'));
					$f->AddText('youtubereplacerwidth', $lang['common']['width'])->WithValue(sm_settings('youtubereplacerwidth'));
					$f->AddText('youtubereplacerheight', $lang['common']['height'])->WithValue(sm_settings('youtubereplacerheight'));
					$f->AddTextarea('youtubereplacerstarthtml', $lang['module_youtubereplacer']['start_with_html'])->WithValue(sm_settings('youtubereplacerstarthtml'));
					$f->AddTextarea('youtubereplacerendhtml', $lang['module_youtubereplacer']['end_with_html'])->WithValue(sm_settings('youtubereplacerendhtml'));
					$f->LoadValuesArray($_postvars);
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
					sm_redirect('index.php?m=youtubereplacer&d=admin');
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
