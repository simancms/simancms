<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Download
	Module URI: http://simancms.org/modules/download/
	Description: Downloads management module. Base CMS module
	Version: 1.6.16
	Revision: 2018-01-30
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	sm_default_action('view');

	if (sm_action('attachment', 'showattachedfile'))
		{
			$att = getsql("SELECT * FROM ".sm_table_prefix()."downloads WHERE userlevel_download<=".intval($userinfo['id'])." AND id_download=".intval($_getvars['id']).' LIMIT 1');
			if (!empty($att['id_download']) && file_exists('files/download/attachment'.intval($_getvars['id'])))
				{
					sm_session_close();
					header("Content-type: ".$att['attachment_type']);
					if (!sm_action('showattachedfile'))
						header("Content-Disposition: attachment; filename=".basename($att['file_download']));
					$fp = fopen('files/download/attachment'.intval($_getvars['id']), 'rb');
					fpassthru($fp);
					fclose($fp);
					exit;
				}
		}

	if (sm_action('view'))
		{
			sm_page_viewid('download-view');
			sm_template('download');
			sm_title($lang['module_download']['downloads']);
			$i = 0;
			$result = execsql("SELECT * FROM ".sm_table_prefix()."downloads WHERE attachment_from='-' AND userlevel_download <= ".intval($userinfo["level"]));
			while ($row = database_fetch_assoc($result))
				{
					$m['files'][$i]['id'] = $row['id_download'];
					$m['files'][$i]['file'] = $row['file_download'];
					$m['files'][$i]['download_url'] = 'files/download/'.$row['file_download'];
					$m['files'][$i]['description'] = $row['description_download'];
					$m['files'][$i]['sizeK'] = round(filesize('./files/download/'.$row['file_download'])/1024, 2);
					$m['files'][$i]['sizeM'] = round($m['files'][$i]['sizeK']/1024, 2);
					if ($m['files'][$i]['sizeM']>=1)
						$m['files'][$i]['size_label']=$m['files'][$i]['sizeM'].' M';
					else
						$m['files'][$i]['size_label']=$m['files'][$i]['sizeK'].' K';
					sm_add_content_modifier($m['files'][$i]['description']);
					$i++;
				}
		}
	if ($userinfo['level'] == 3 || (intval(sm_settings('perm_downloads_management_level'))>0 && sm_settings('perm_downloads_management_level')<=intval($userinfo['level'])))
		include('modules/inc/adminpart/download.php');