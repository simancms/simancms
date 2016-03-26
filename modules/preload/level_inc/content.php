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

	function siman_contnet_nicename_generation($id)
		{
			global $sm;
			if (intval(sm_get_settings('autogenerate_content_filesystem', 'content'))==0)
				return;
			sm_extcore();
			$url=sm_fs_url('index.php?m=content&d=view&cid='.intval($id), true);
			if ($url===false)
				{
					$info=TQuery::ForTable($sm['t'].'content')->Add('id_content', intval($id))->Get();
					sm_fs_update($info['title_content'], 'index.php?m=content&d=view&cid='.intval($id), sm_fs_autogenerate($info['title_content'], '.html'));
				}
		}

	sm_event_handler('postaddcontent', 'siman_contnet_nicename_generation');

?>