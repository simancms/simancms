<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#verCMS 1.6.2                                                                |
	//==============================================================================

	/*
	Module Name: Replacers
	Module URI: http://simancms.org/
	Description: Template replacers for custom themes
	Version: 2012-07-20
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if (!defined("replacers_FUNCTIONS_DEFINED"))
		{
			define("replacers_FUNCTIONS_DEFINED", 1);
		}

	if ($userinfo['level'] == 3)
		{
			sm_include_lang('replacers');
			if (sm_action('postdelete'))
				{
					$q = new TQuery($sm['t']."replacers");
					$q->Add('id_r', intval($_getvars['id']));
					$q->Remove();
					sm_redirect('index.php?m=replacers&d=admin');
				}
			if (sm_action('postadd', 'postedit'))
				{
					$q = new TQuery($sm['t']."replacers");
					$q->AddPost('key_r');
					$q->AddPost('value_r');
					if (sm_action('postedit'))
						$q->Update('id_r='.intval($_getvars['id']));
					else
						$q->Insert();
					sm_redirect('index.php?m=replacers&d=admin');
				}
			if (sm_action('add', 'edit'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					$f = new TForm('index.php?m=replacers&d=post'.$m['mode'].'&id='.intval($_getvars['id']));
					$f->AddText('key_r', 'Key');
					$f->AddTextarea('value_r', 'Content');
					if (sm_action('edit'))
						{
							$q = new TQuery($sm['t']."replacers");
							$q->Add('id_r', intval($_getvars['id']));
							$f->LoadValuesArray($q->Get());
						}
					$f->LoadValuesArray($sm['p']);
					$ui->AddForm($f);
					$ui->Output(true);
				}
			if (sm_action('admin'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/admintable.php');
					add_path_modules();
					add_path('Replacers', 'index.php?m=replacers&d=admin');
					$ui = new TInterface();
					$ui->div('<a href="index.php?m=replacers&d=add">'.$lang['common']['add'].'</a>');
					$t = new TGrid();
					$t->AddCol('key_r', 'Key', '100%');
					$t->AddEdit();
					$t->AddDelete();
					$q = new TQuery($sm['t']."replacers");
					$q->Select();
					for ($i = 0; $i < count($q->items); $i++)
						{
							$t->Label('key_r', $q->items[$i]['key_r']);
							$t->Url('edit', 'index.php?m=replacers&d=edit&id='.$q->items[$i]['id_r']);
							$t->Url('delete', 'index.php?m=replacers&d=postdelete&id='.$q->items[$i]['id_r']);
							$t->NewRow();
						}
					if (count($q->items) == 0)
						{
							$t->Label('key_r', 'Nothing found');
							$t->OneLine('key_r');
						}
					$ui->AddGrid($t);
					$ui->Output(true);
					$m['title'] = $lang['settings'];
				}
			if (strcmp($m["mode"], 'install') == 0)
				{
					$m['title'] = $lang['common']['install'];
					sm_register_module('replacers', 'Replacers');
					sm_register_autoload('replacers');
					execsql("CREATE TABLE `".$sm['t']."replacers` (
								`id_r` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
								`key_r` VARCHAR( 255 ) NOT NULL ,
								`value_r` TEXT NOT NULL
							);");
					sm_redirect('index.php?m=admin&d=modules');
				}
			if (strcmp($m["mode"], 'uninstall') == 0)
				{
					$m['title'] = $lang['common']['install'];
					sm_unregister_module('replacers');
					sm_unregister_autoload('replacers');
					execsql("DROP TABLE `".$sm['t']."replacers`");
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>