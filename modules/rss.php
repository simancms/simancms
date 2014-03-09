<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: RSS-Feed
	Module URI: http://simancms.org/modules/content/
	Description: RSS-Export module
	Version: 1.6.5
	Revision: 2014-01-03
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if (!defined("rss_FUNCTIONS_DEFINED"))
		{

			define("rss_FUNCTIONS_DEFINED", 1);
		}

	sm_default_action('news');

	if (sm_action('news'))
		{
			$m["module"] = 'rss';
			$rss['title'] = sm_settings('resource_title');
			$rss['link'] = 'http://'.sm_settings('resource_url');
			$rss['description'] = sm_settings('logo_text');
			$ctg = intval($_getvars['ctg']);
			$sql = "SELECT * FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE id_category_n=id_category ";
			if (!empty($ctg))
				{
					$sql .= " AND id_category_n=".intval($ctg);
				}
			$sql .= " AND (date_news<=".time().") ";
			$sql .= " ORDER BY date_news DESC LIMIT ".intval(sm_settings('rss_itemscount'));
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_object($result))
				{
					$rss['items'][$i]['title'] = $row->title_news;
					$rss['items'][$i]['pubDate'] = date('D, d M Y H:i:s O', $row->date_news);
					//$rss['items'][$i]['pubDate']=date('D, d M Y H:i:s +0200', $row->date_news);
					if (empty($row->prewiew_news))
						{
							$rss['items'][$i]['description'] = cut_str_by_word(strip_tags($row->text_news), sm_settings('news_anounce_cut'), '...');
						}
					else
						$rss['items'][$i]['description'] = strip_tags($row->prewiew_news);
					$rss['items'][$i]['description'] = preg_replace("/&#?[a-z0-9]+;/i", "", $rss['items'][$i]['description']);
					$rss['items'][$i]['description'] = str_replace('&', '&amp;', $rss['items'][$i]['description']);
					$rss['items'][$i]['description'] = str_replace('&nbsp;', ' ', $rss['items'][$i]['description']);
					$rss['items'][$i]['description'] = str_replace('nbsp;', ' ', $rss['items'][$i]['description']);
					$rss['items'][$i]['fulltext'] = strip_tags($row->text_news);
					$rss['items'][$i]['fulltext'] = preg_replace("/&#?[a-z0-9]+;/i", "", $rss['items'][$i]['fulltext']);
					$rss['items'][$i]['fulltext'] = str_replace('&', '&amp;', $rss['items'][$i]['fulltext']);
					$rss['items'][$i]['title'] = $row->title_news;
					$rss['items'][$i]['link'] = 'http://'.$_settings['resource_url'].'index.php?m=news&amp;d=view&amp;nid='.$row->id_news;
					$rss['items'][$i]['category'] = $row->title_category;
					$rss['items'][$i]['description'] = eregi_replace('&[a-zA-Z]+;', ' ', $rss['items'][$i]['description']);
					if (empty($rss['items'][$i]['title']))
						$rss['items'][$i]['title'] = $rss['items'][$i]['description'];
					$i++;
				}
			sm_event('generaterss', Array($rss));
		}

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
					sm_redirect('index.php?m=rss&d=admin');
				}
			if (sm_action('admin'))
				{
					add_path_modules();
					add_path($lang['module_rss']['module_rss'], 'index.php?m=rss&d=admin');
					sm_title($lang['settings']);
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					$f = new TForm('index.php?m=rss&d=postsettings');
					$f->AddText('rss_itemscount', $lang['module_rss']['settings']['rss_itemscount']);
					$f->AddCheckbox('rss_showfulltext', $lang['module_rss']['settings']['rss_showfulltext']);
					$f->AddCheckbox('rss_shownewsctgs', $lang['module_rss']['settings']['rss_shownewsctgs']);
					$f->AddCheckbox('rss_shownimagetag', $lang['module_rss']['settings']['rss_shownimagetag']);
					$f->LoadValuesArray($_settings);
					$ui->AddForm($f);
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