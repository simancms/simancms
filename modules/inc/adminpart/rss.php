<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2014-05-26
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level'] == 3)
		{
			sm_include_lang('rss');
			if (sm_action('postsettings'))
				{
					$cnt=intval($_postvars['rss_itemscount']);
					if ($cnt<=0)
						$cnt=15;
					sm_update_settings('rss_itemscount', $cnt);
					sm_update_settings('rss_showfulltext', intval($_postvars['rss_showfulltext']));
					sm_update_settings('rss_shownewsctgs', intval($_postvars['rss_shownewsctgs']));
					sm_update_settings('rss_shownimagetag', intval($_postvars['rss_shownimagetag']));
					if ($filename=sm_upload_file())
						{
							if (file_exists($filename))
								{
									$dst='files/img/rss_logo.png';
									if (file_exists($dst))
										unlink($dst);
									rename($filename, $dst);
								}
						}
					sm_notify($lang['settings_saved_successful']);
					sm_redirect('index.php?m='.sm_current_module().'&d=admin');
				}
			if (sm_action('removelogo'))
				{
					if (file_exists('files/img/rss_logo.png'))
						unlink('files/img/rss_logo.png');
					sm_notify($lang['messages']['delete_successful']);
					sm_redirect('index.php?m='.sm_current_module().'&d=admin');
				}
			if (sm_action('admin'))
				{
					add_path_modules();
					add_path($lang['module_rss']['module_rss'], 'index.php?m=rss&d=admin');
					sm_title($lang['settings']);
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_extcore();
					$ui = new TInterface();
					$f = new TForm('index.php?m=rss&d=postsettings');
					$f->AddText('rss_itemscount', $lang['module_rss']['settings']['rss_itemscount']);
					$f->AddCheckbox('rss_showfulltext', $lang['module_rss']['settings']['rss_showfulltext']);
					$f->AddCheckbox('rss_shownewsctgs', $lang['module_rss']['settings']['rss_shownewsctgs']);
					$f->AddCheckbox('rss_shownimagetag', $lang['module_rss']['settings']['rss_shownimagetag']);
					$f->AddFile('userfile', $lang['module_rss']['rss_feed_logo']);
					$f->LoadValuesArray($_settings);
					$ui->AddForm($f);
					if (file_exists('files/img/rss_logo.png'))
						{
							$ui->AddBlock($lang['module_rss']['rss_feed_logo']);
							$ui->img('files/img/rss_logo.png?rand='.rand());
							sm_use('ui.buttons');
							$b=new TButtons();
							$b->MessageBox($lang['module_admin']['delete_image'], 'index.php?m='.sm_current_module().'&d=removelogo');
							$ui->Add($b);
						}
					$ui->AddBlock($lang['module_rss']['rss_feeds']);
					sm_use('ui.grid');
					$t = new TGrid();
					$t->AddCol('title', $lang['title']);
					$t->AddCol('url', $lang['url']);
					$t->AddCol('add_to_menu', $lang['add_to_menu']);
					$t->Label('title', $lang['news']);
					$t->Label('url', sm_homepage().sm_fs_url('index.php?m=rss'));
					$t->URL('url', sm_homepage().sm_fs_url('index.php?m=rss'), true);
					$t->Label('add_to_menu', $lang['add_to_menu']);
					$t->URL('add_to_menu', sm_tomenuurl('RSS - '.$lang['news'], sm_fs_url('index.php?m=rss')));
					$t->NewRow();
					$newsctgs = getsqlarray("SELECT * FROM ".$sm['t']."categories_news ORDER BY title_category");
					for ($i = 0; $i < count($newsctgs); $i++)
						{
							$t->Label('title', $newsctgs[$i]['title_category']);
							$t->Label('url', sm_homepage().sm_fs_url('index.php?m=rss&ctg='.$newsctgs[$i]['id_category']));
							$t->URL('url', sm_homepage().sm_fs_url('index.php?m=rss&ctg='.$newsctgs[$i]['id_category']), true);
							$t->Label('add_to_menu', $lang['add_to_menu']);
							$t->URL('add_to_menu', sm_tomenuurl('RSS - '.$newsctgs[$i]['title_category'], sm_fs_url('index.php?m=rss&ctg='.$newsctgs[$i]['id_category'])));
							$t->NewRow();
						}
					$ui->AddGrid($t);
					$ui->Output(true);
				}
			if (sm_action('install'))
				{
					sm_register_module('rss', $lang['module_rss']['module_rss']);
					sm_register_autoload('rss');
					sm_new_settings('rss_itemscount', 15);
					sm_new_settings('rss_showfulltext', 0);
					sm_new_settings('rss_shownewsctgs', 0);
					sm_new_settings('rss_shownimagetag', 0);
					sm_redirect('index.php?m=admin&d=modules');
				}
			if (sm_action('uninstall'))
				{
					sm_unregister_module('rss');
					sm_unregister_autoload('rss');
					sm_delete_settings('rss_itemscount');
					sm_delete_settings('rss_showfulltext');
					sm_delete_settings('rss_shownewsctgs');
					sm_delete_settings('rss_shownimagetag');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>