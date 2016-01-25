<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2016-01-24
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("MEDIAMEMBERS_FUNCTIONS_DEFINED"))
		{
			function siman_thumb_for_media($filepath)
				{
					$info=pathinfo($filepath);
					$filename=$info['dirname'].'/'.$info['filename'].'-small.'.$info['extension'];
					if (file_exists($filename))
						return $filename;
					else
						return $filepath;
				}

			define("MEDIAMEMBERS_FUNCTIONS_DEFINED", 1);
		}
	
	if ($userinfo['level']>1)
		{
			if (sm_action('editorinsert'))
				{
					sm_use('ui.modal');
					$m['module'] = 'media';
					$offset = abs(intval($_getvars['from']));
					$limit = 30;
					$q = new TQuery($sm['t'].'categories_media');
					$q->OrderBy('lastupdate DESC');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					for ($i = 0; $i<count($q->items); $i++)
						{
							$m['galleries'][$i]['id'] = $q->items[$i]['id_ctg'];
							$m['galleries'][$i]['title'] = $q->items[$i]['title'];
							$m['galleries'][$i]['image'] = 'files/img/mediagallery'.$q->items[$i]['id_ctg'].'.jpg';
							if (!file_exists($m['galleries'][$i]['image']))
								$m['galleries'][$i]['image'] = 'files/img/noimage.jpg';
							$m['galleries'][$i]['url'] = 'javascript:;';
							$m['galleries'][$i]['onclick'] = sm_ajax_load('index.php?m=media&d=editorinsertlist&ctg='.$q->items[$i]['id_ctg'].'&theonepage=1', TModalHelper::ModalDOMSelector());
							if (intval(sm_settings('galleries_editorinsert_items_per_row'))>0)
								if (($i+1)%intval(sm_settings('galleries_editorinsert_items_per_row')) == 0)
									$m['galleries'][$i]['newrow'] = true;
						}
				}
			if (sm_action('editorinsertlist'))
				{
					$ctg=TQuery::ForTable($sm['t'].'categories_media')->Add('id_ctg', intval($_getvars['ctg']))->Get();
					if (!empty($ctg['id_ctg']))
						{
							sm_title($ctg['title']);
							sm_add_cssfile('media.css');
							sm_use('ui.interface');
							sm_use('ui.modal');
							$ui = new TInterface();
							$ui->a('javascript:;', $lang['common']['back'], '', '', '', sm_ajax_load(sm_fs_url('index.php?m=media&d=editorinsert&theonepage=1'), TModalHelper::ModalDOMSelector()));
							$ui->div('', '', '', 'clear:both;');
							$q=new TQuery($sm['t'].'media');
							$q->Add('id_ctg', intval($ctg['id_ctg']));
							$q->OrderBy('id');
							$q->Select();
							for ($i = 0; $i<count($q->items); $i++)
								{
									$ui->div_open('', 'gallery-insert-list-item');
									$ui->div('<a href="javascript:;" onclick="'.siman_exteditor_insert_image(siman_medium_for_media($q->items[$i]['filepath'])).'"><img src="'.siman_thumb_for_media($q->items[$i]['filepath']).'" /></a>', '', 'gallery-insert-list-item-container');
									$ui->div_close();
								}
							$ui->Output(true);
						}
				}
		}

?>