<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2017-07-14
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');
	
	if ($userinfo['level'] == 3)
		{
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
					$ui->AddButtons($b);
					$ui->Output(true);
				}
		}
