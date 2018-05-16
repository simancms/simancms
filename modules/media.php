<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Media
	Module URI: http://simancms.org/modules/media/
	Description: Media files management. Base CMS module
	Version: 1.6.15
	Revision: 2018-05-16
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("MEDIA_FUNCTIONS_DEFINED"))
		{
			function siman_medium_for_media($filepath)
				{
					$info=pathinfo($filepath);
					$filename=$info['dirname'].'/'.$info['filename'].'-medium.'.$info['extension'];
					if (file_exists($filename))
						return $filename;
					else
						return $filepath;
				}

			define("MEDIA_FUNCTIONS_DEFINED", 1);
		}
	
	sm_include_lang('media');
	sm_default_action('galleries');
	
	if (sm_action('galleries'))
		{
			sm_title($lang['module_galleies']['galleries']);
			sm_add_cssfile('media.css');
			$m['module']='media';
			$offset=abs(intval($_getvars['from']));
			$limit=30;
			$q=new TQuery(sm_table_prefix().'categories_media');
			$q->Add('public', 1);
			$q->OrderBy('lastupdate DESC');
			$q->Limit($limit);
			$q->Offset($offset);
			$q->Select();
			for ($i = 0; $i<count($q->items); $i++)
				{
					$m['galleries'][$i]['id']=$q->items[$i]['id_ctg'];
					$m['galleries'][$i]['title']=$q->items[$i]['title'];
					$m['galleries'][$i]['image']='files/img/mediagallery'.$q->items[$i]['id_ctg'].'.jpg';
					if (!file_exists($m['galleries'][$i]['image']))
						{
							if (file_exists('files/img/noimage.jpg'))
								$m['galleries'][$i]['image'] = 'files/img/noimage.jpg';
							else
								$m['galleries'][$i]['image'] = 'ext/showimage.php?img=&width='.sm_settings('gallery_thumb_width').'&height='.sm_settings('gallery_thumb_height');
						}
					$m['galleries'][$i]['url']=sm_fs_url('index.php?m=media&d=gallery&ctg='.$q->items[$i]['id_ctg']);
					if (intval(sm_settings('galleries_view_items_per_row'))>0)
						if (($i+1) % intval(sm_settings('galleries_view_items_per_row'))==0)
							$m['galleries'][$i]['newrow']=true;
				}
			sm_pagination_init($q->TotalCount(), $limit, $offset);
		}
	
	if (sm_action('gallery') && sm_settings('gallery_default_view')=='all')
		{
			$q=new TQuery(sm_table_prefix().'categories_media');
			$q->Add('id_ctg', intval($_getvars['ctg']));
			if ($userinfo['level']==0)
				$q->Add('public', 1);
			$ctg=$q->Get();
			if (!empty($ctg['id_ctg']))
				{
					sm_title($ctg['title']);
					sm_add_cssfile('media.css');
					sm_use('admininterface');
					$ui = new TInterface();
					$q=new TQuery(sm_table_prefix().'media');
					$q->Add('id_ctg', intval($ctg['id_ctg']));
					$q->OrderBy('id');
					$q->Select();
					for ($i = 0; $i<count($q->items); $i++)
						{
							$ui->div('<img src="'.siman_medium_for_media($q->items[$i]['filepath']).'" />', '', 'gallery-view-all-item');
						}
					$ui->Output(true);
				}
		}
	
	if ($userinfo['level']>1)
		include('modules/inc/memberspart/media.php');
	if ($userinfo['level']>2)
		include('modules/inc/adminpart/media.php');

