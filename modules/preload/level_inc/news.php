<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-09-25
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}
	
	function siman_news_nicename_generation($id)
		{
			global $sm;
			$type=intval(sm_get_settings('autogenerate_news_filesystem', 'news'));
			if ($type==0)
				return;
			sm_extcore();
			$url=sm_fs_url('index.php?m=news&d=view&nid='.intval($id), true);
			if ($url===false)
				{
					$info=TQuery::ForTable($sm['t'].'news')->Add('id_news', intval($id))->Get();
					if ($type==2)
						$prefix='news/';
					elseif ($type==3)
						$prefix='blog/';
					elseif ($type==4)
						$prefix='news/'.strftime('%Y/%m/%d/', $info['date_news']);
					elseif ($type==5)
						$prefix='blog/'.strftime('%Y/%m/%d/', $info['date_news']);
					else
						$prefix='';
					sm_fs_update($info['title_news'], 'index.php?m=news&d=view&nid='.intval($id), sm_fs_autogenerate($info['title_news'], '.html', $prefix));
				}
		}

	sm_event_handler('postaddnews', 'siman_news_nicename_generation');

?>