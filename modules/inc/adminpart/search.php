<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2018-04-09
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');
	
	if ($userinfo['level'] == 3)
		{
			if (sm_action('enablesearch'))
				{
					sm_update_settings('search_module_disabled', 0);
					sm_redirect('index.php?m=search&d=admin');
				}
			if (sm_action('disablesearch'))
				{
					if (sm_has_settings('search_module_disabled'))
						sm_update_settings('search_module_disabled', 1);
					else
						sm_add_settings('search_module_disabled', 1);
					sm_redirect('index.php?m=search&d=admin');
				}
			if (sm_action('admin'))
				{
					sm_extcore();
					add_path_modules();
					add_path_current();
					sm_title($lang['control_panel'].' - '.$lang['search']);
					sm_use('ui.interface');
					sm_use('ui.buttons');
					$ui = new TInterface();
					$b=new TButtons();
					$b->Button($lang['set_as_block'].' "'.$lang['search'].'"', sm_addblockurl($lang['search'], 'search', 1));
					$b->Button($lang['add_to_menu'].' - '.$lang['search'], sm_tomenuurl($lang['search'], 'index.php?m=search'));
					if (intval(sm_settings('search_module_disabled'))==1)
						$b->Button($lang['common']['enable'].' - '.$lang['search'], 'index.php?m=search&d=enablesearch');
					else
						$b->Button($lang['common']['disable'].' - '.$lang['search'], 'index.php?m=search&d=disablesearch');
					$ui->AddButtons($b);
					$ui->Output(true);
				}
		}
