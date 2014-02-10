<?php

//-------------[Налаштування]-[Settings]-----------------------

$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('install_not_erased','1');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('cookprefix','_siman".rand(11111111, 99999999)."_');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('resource_title','".$inst['settings']['title']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('resource_url','".$inst['settings']['addr']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('logo_text','".$inst['settings']['logo']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_language','".$inst['settings']['lang']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_theme','".$inst['settings']['theme']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('default_module','".$inst['settings']['default_module']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('copyright_text','".$inst['settings']['copyright']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('allow_register','1');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_by_page','10');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('short_news_count','3');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('short_news_cut','50');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_anounce_cut','300');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('meta_description','Siman CMS Powered Site');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('meta_keywords','siman, apserver, CMS');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('allow_forgot_password','1');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('admin_items_by_page', '10');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('search_items_by_page', '10');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('ext_editor', 'tinymce3_2');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('max_upload_filesize', '1048576');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('upper_menu_id', '3');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('banned_ip', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('header_static_text', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('footer_static_text', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('administrators_email', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('email_signature', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('noflood_time', '600')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('help_resource', 'simancms.org')
";
execsql($sql);
//version 1.4
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('content_use_preview', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('content_per_page_multiview', '10')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('bottom_menu_id', '4')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_preview', '1')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_title', '1')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('news_use_image', '1')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('menuitems_use_image', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('menus_use_image', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('blocks_use_image', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('user_activating_by_admin', '')
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings(name_settings, value_settings) VALUES ('censored_words', '')
";
execsql($sql);
//version 1.5 beta 1
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings (`name_settings`, `value_settings`) VALUES ('main_block_position', '0');
";
execsql($sql);
//version 1.5
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('return_after_login', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_private_messages', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_alike_content', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('alike_content_count', '5');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('allow_alike_news', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('alike_news_count', '5');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('log_type', '1');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('log_store_days', '30');
";
execsql($sql);

//version 1.6 alpha 1
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('meta_header_text', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_use_time', '".$inst['settings']['news_use_time']."');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('installed_packages', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('autoload_modules', 'content
news
menu');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('rewrite_index_title', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_preview_width', '150');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_preview_height', '100');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_fulltext_width', '250');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_image_fulltext_height', '200');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('sidepanel_count', '2');
";
execsql($sql);

//version 1.6 alpha 2
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('postload_modules', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('content_use_path', '');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('content_attachments_count', '0');
";
execsql($sql);
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('news_attachments_count', '0');
";
execsql($sql);

//version 1.6
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('image_generation_type', 'dynamic');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('use_email_as_login', '0');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('title_delimiter', ' :: ');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('meta_resource_title_position', '1');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_use_image', '0');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_preview_width', '150');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_preview_height', '100');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_fulltext_width', '250');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('content_image_fulltext_height', '200');
";
execsql($sql);

//version 1.6.1
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_logout', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_1', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_2', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_login_3', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('redirect_after_register', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_rewrite', '1');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_mobile', '');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`) VALUES ('resource_url_tablet', '');
";
execsql($sql);


//version 1.6.2
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_time', '3600', 'general');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_attempts', '5', 'general');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('autoban_ips', '', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('clean_temptable_interval', '600', 'general');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('next_clean_temptable', '0', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('next_system_cleanup', '0', 'default');
";
execsql($sql);

//version 1.6.4
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('redirect_on_success_change_usrdata', '', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('signinwithloginandemail', '0', 'default');
";
execsql($sql);

//version 1.6.5
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('news_editor_level', '2', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('content_editor_level', '2', 'default');
";
execsql($sql);

//version 1.6.6
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('content_multiview', 'on', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('default_news_text_style', '0', 'default');
";
execsql($sql);
$sql="
INSERT INTO `".$tableprefix."settings` (`name_settings`, `value_settings`, `mode`) VALUES ('show_help', 'on', 'default');
";
execsql($sql);


//---------[DATABASE DATE]------------------------------------
$sql="
INSERT INTO ".$tableprefix."settings  (`name_settings`, `value_settings`) VALUES  ('database_date', '20140315');
";
execsql($sql);


?>