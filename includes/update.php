<?php

	if (intval(sm_settings('database_date'))<20130420)//1.6.4
		{
			sm_add_settings('redirect_on_success_change_usrdata', '');
			sm_add_settings('signinwithloginandemail', '0');
			sm_update_settings('database_date', '20130420');
		}
	if (intval(sm_settings('database_date'))<20140115)//1.6.5
		{
			execsql("ALTER TABLE ".$sm['t']."log CHANGE `ip` `ip` VARBINARY(255) NULL DEFAULT NULL;");
			sm_add_settings('news_editor_level', '3');
			sm_add_settings('content_editor_level', '3');
			sm_update_settings('database_date', '20140115');
		}
	if (intval(sm_settings('database_date'))<20140315)//1.6.6
		{
			sm_add_settings('content_multiview', 'on');
			sm_add_settings('default_news_text_style', '0');
			sm_add_settings('show_help', 'on');
			sm_update_settings('database_date', '20140315');
		}
	if (intval(sm_settings('database_date'))<20140701)//1.6.7
		{
			sm_add_settings('gallery_thumb_width', '150');
			sm_add_settings('gallery_thumb_height', '150');
			sm_add_settings('gallery_default_view', 'all');
			sm_add_settings('gallery_view_items_per_row', '0');
			sm_add_settings('galleries_view_items_per_row', '0');
			sm_add_settings('galleries_sort', 'lastupdate_desc');
			sm_add_settings('media_thumb_width', '150');
			sm_add_settings('media_thumb_height', '150');
			sm_add_settings('media_medium_width', '600');
			sm_add_settings('media_meduim_height', '600');
			sm_add_settings('media_allowed_extensions', "jpg\njpeg\ngif\npng\nmp4\nmp3\nwav");
			sm_add_settings('media_edit_after_upload', '1', 'media');
			execsql("CREATE TABLE `".$sm['t	']."metadata` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`object_name` varchar(50) NOT NULL DEFAULT '',
					`object_id` varchar(50) NOT NULL DEFAULT '',
					`key_name` varchar(100) NOT NULL DEFAULT '',
					`val` text,
					PRIMARY KEY (`id`),
					KEY `object_name` (`object_name`,`object_id`,`key_name`,`val`(50))
				)");
			if (!array_key_exists('news_full_list_longformat', $_settings))
				sm_add_settings('news_full_list_longformat', '0');
			sm_add_settings('robots_txt', '', 'seo');
			sm_add_settings('notifications_time', '5');
			sm_add_settings('notifierlib', 'alertify');
			execsql("CREATE TABLE `".$sm['t']."taxonomy` (
					`object_name` varchar(50) NOT NULL DEFAULT '',
					`object_id` int(11) unsigned NOT NULL DEFAULT '0',
					`rel_id` int(11) unsigned NOT NULL DEFAULT '0',
					PRIMARY KEY (`object_name`,`object_id`,`rel_id`)
				)");
			execsql("ALTER TABLE `".$sm['t']."log` ADD `object_name` VARCHAR(255)  NOT NULL  DEFAULT 'system'  AFTER `id_log`;");
			execsql("ALTER TABLE `".$sm['t']."log` ADD `object_id` VARCHAR(255)  NOT NULL  DEFAULT '0'  AFTER `object_name`;");
			sm_add_settings('autogenerate_content_filesystem', '1', 'content');
			sm_add_settings('autogenerate_news_filesystem', '1', 'news');

			sm_update_settings('database_date', '20140701');
		}

?>