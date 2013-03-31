<?php

//-------------[Налаштування]-[Settings]-----------------------

$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('install_not_erased','1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('cookprefix','_siman');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('resource_title','".$inst['settings']['title']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('resource_url','".$inst['settings']['addr']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('logo_text','".$inst['settings']['logo']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_language','".$inst['settings']['lang']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_theme','".$inst['settings']['theme']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_module','".$inst['settings']['default_module']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('copyright_text','".$inst['settings']['copyright']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('allow_register','1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_by_page','10');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('short_news_count','3');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('short_news_cut','50');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_anounce_cut','300');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('meta_description','Siman CMS Powered Site');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('meta_keywords','siman, apserver, CMS');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('allow_forgot_password','1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('admin_items_by_page', '10');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('search_items_by_page', '10');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('ext_editor', 'tinymce3_2');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('max_upload_filesize', '1048576');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('upper_menu_id', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('banned_ip', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('header_static_text', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('footer_static_text', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('administrators_email', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('email_signature', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('noflood_time', '600')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('help_resource', 'simancms.org')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
//version 1.4
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('content_use_preview', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('content_per_page_multiview', '10')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('bottom_menu_id', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_preview', '1')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_title', '1')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_image', '1')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('menuitems_use_image', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('menus_use_image', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('blocks_use_image', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('user_activating_by_admin', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('censored_words', '')
";
$result=database_db_query($nameDB, $sql, $lnkDB);
//version 1.5 beta 1
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings (`name_settings`, `value_settings`) VALUES ('main_block_position', '0');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
//version 1.5
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('return_after_login', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_private_messages', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_alike_content', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('alike_content_count', '5');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_alike_news', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('alike_news_count', '5');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('log_type', '1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('log_store_days', '30');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//version 1.6 alpha 1
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('meta_header_text', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_use_time', '".$inst['settings']['news_use_time']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('installed_packages', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('autoload_modules', 'content
news
menu');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('rewrite_index_title', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_preview_width', '150');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_preview_height', '100');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_fulltext_width', '250');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_fulltext_height', '200');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('sidepanel_count', '2');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//version 1.6 alpha 2
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('postload_modules', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('content_use_path', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('content_attachments_count', '0');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_attachments_count', '0');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//version 1.6
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('image_generation_type', 'dynamic');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('use_email_as_login', '0');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('title_delimiter', ' :: ');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('meta_resource_title_position', '1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_use_image', '0');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_preview_width', '150');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_preview_height', '100');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_fulltext_width', '250');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_fulltext_height', '200');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//version 1.6.1
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_logout', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_1', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_2', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_3', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_register', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_rewrite', '1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_mobile', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_tablet', '');
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//version 1.6.2
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_time', '3600', 'general');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_attempts', '5', 'general');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_ips', '', 'default');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('clean_temptable_interval', '600', 'general');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('next_clean_temptable', '0', 'default');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('next_system_cleanup', '0', 'default');
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//---------[DATABASE DATE]------------------------------------
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('database_date', '20120814');
";
$result=database_db_query($nameDB, $sql, $lnkDB);


?>