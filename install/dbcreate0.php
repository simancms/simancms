<?php

if ($serverDB==0)
{

$sql="
CREATE TABLE ".$tableprefix."blocks (
  `id_block` int(11) unsigned NOT NULL auto_increment,
  `panel_block` char(1) default NULL,
  `position_block` int(11) default NULL,
  `name_block` varchar(255) default NULL,
  `caption_block` varchar(50) default NULL,
  `showed_id` int(11) unsigned default NULL,
  `level` int(11) default '0',
  `show_on_module` varchar(100) default NULL,
  `show_on_doing` VARCHAR( 255 ) NULL,
  `show_on_ctg` int(11) default '0',
  `dont_show_modif` TINYINT NOT NULL DEFAULT '0',
  `doing_block` varchar(150) default '',
  `no_borders` tinyint(4) NULL DEFAULT '0',
  `rewrite_title` varchar(255) NULL,
  `groups_view` text NULL,
  `thislevelonly` TINYINT NOT NULL DEFAULT '0',
  `show_on_device` VARCHAR( 255 ) NULL,
  `show_on_viewids` TEXT NOT NULL,
  `classname_block` VARCHAR( 255 ) NULL DEFAULT NULL,
  `text_block` TEXT NULL DEFAULT NULL,
  `editsource_block` VARCHAR( 255 ) NULL DEFAULT NULL,
  `showontheme` VARCHAR( 255 ) NULL DEFAULT NULL,
  `showonlang` VARCHAR( 255 ) NULL DEFAULT NULL,
  PRIMARY KEY  (`id_block`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][0]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."categories (
  `id_category` int(11) unsigned NOT NULL auto_increment,
  `id_maincategory` int(11) unsigned NOT NULL default '0',
  `title_category` varchar(255) default NULL,
  `can_view` tinyint(4) NULL default 0,
  `public_menu` tinyint(4) default '1',
  `preview_category` TEXT NULL,
  `filename_category` int(11) NULL DEFAULT 0,
  `groups_view` text NULL,
  `groups_modify` text NULL,
  `no_alike_content` tinyint(4) NULL DEFAULT 0,
  `sorting_category` SMALLINT NULL DEFAULT '0',
  `no_use_path` TINYINT( 4 ) NULL DEFAULT '0',
  PRIMARY KEY  (`id_category`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][1]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."content (
  `id_content` int(11) unsigned NOT NULL auto_increment,
  `id_category_c` int(11) unsigned default '1',
  `title_content` varchar(255) default NULL,
  `preview_content` TEXT NULL,
  `text_content` text,
  `type_content` tinyint(4) default '0',
  `keywords_content` varchar(255) default NULL,
  `description_content` TEXT NULL,
  `filename_content` int(11) NULL DEFAULT 0,
  `priority_content` int(11) NULL DEFAULT 0,
  `refuse_direct_show` TINYINT NULL DEFAULT '0',
  PRIMARY KEY  (`id_content`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][2]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."menu_lines (
  `id_ml` int(11) unsigned NOT NULL auto_increment,
  `id_menu_ml` int(11) unsigned default NULL,
  `submenu_from` INT( 11 ) UNSIGNED NULL DEFAULT '0',
  `url` varchar(255) default NULL,
  `caption_ml` varchar(255) default NULL,
  `position` int(11) default NULL,
  `partial_select` tinyint(4) NULL DEFAULT 0,
  `alt_ml` VARCHAR( 255 ) NULL,
  `newpage_ml` TINYINT NULL DEFAULT '0',
  `attr_ml` TEXT NULL,
  `tag_ml` VARCHAR( 255 ) NULL DEFAULT NULL,
  PRIMARY KEY  (`id_ml`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][3]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."menus (
  `id_menu_m` int(10) unsigned NOT NULL auto_increment,
  `caption_m` varchar(255) default NULL,
  PRIMARY KEY  (`id_menu_m`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][4]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."modules (
  `id_module` int(1) unsigned NOT NULL auto_increment,
  `module_name` varchar(100) default NULL,
  `module_title` varchar(100) default NULL,
  `search_fields` text NULL,
  `search_doing` varchar(100) NULL,
  `search_var` varchar(10) NULL,
  `search_table` varchar(100) NULL,
  `search_title` varchar(100) NULL,
  `search_idfield` varchar(100) NULL,
  `search_text` varchar(100) NULL,
  PRIMARY KEY  (`id_module`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][5]['result']=$result;

$sql="
CREATE TABLE ".$tableusersprefix."users (
  `id_user` int(11) NOT NULL auto_increment,
  `login` varchar(150) NOT NULL default '',
  `password` varchar(150) NOT NULL default '',
  `email` varchar(150) default NULL,
  `question` varchar(150) default NULL,
  `answer` varchar(150) default NULL,
  `id_session` varchar(150) default NULL,
  `user_status` tinyint(4) default '1',
  `last_login` bigint(14) default NULL,
  `notebook` text,
  `get_mail` tinyint(4) NULL DEFAULT 1,
  `groups_user` text NULL,
  `random_code` VARCHAR( 255 ) NULL DEFAULT NULL,
  PRIMARY KEY  (`id_user`),
  INDEX ( `random_code` )
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][6]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."categories_news (
  `id_category` int(1) unsigned NOT NULL auto_increment,
  `title_category` varchar(255) default NULL,
  `filename_category` int(11) NULL DEFAULT 0,
  `groups_modify` text NULL,
  `no_alike_news` tinyint(4) NULL DEFAULT 0,
  PRIMARY KEY  (`id_category`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][7]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."settings (
  `name_settings` varchar(150) NOT NULL default '',
  `value_settings` text,
  `mode` VARCHAR( 50 ) NULL DEFAULT 'default'
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][8]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."news (
  `id_news` int(1) unsigned NOT NULL auto_increment,
  `id_category_n` int(11) unsigned default NULL,
  `id_author_news` INT UNSIGNED NULL DEFAULT 1,
  `img_copyright_news` VARCHAR( 255 ) NULL,
  `date_news` int(11) unsigned default NULL,
  `title_news` varchar(255) default NULL,
  `preview_news` TEXT,
  `text_news` text,
  `type_news` tinyint(4) default NULL,
  `keywords_news` VARCHAR( 255 ) NULL,
  `description_news` TEXT NULL,
  `filename_news` int(11) NULL DEFAULT 0,
  PRIMARY KEY  (`id_news`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][9]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."downloads (
  `id_download` int(11) unsigned NOT NULL auto_increment,
  `file_download` varchar(255) default NULL,
  `description_download` text,
  `userlevel_download` tinyint(4) default '0',
  `attachment_from` VARCHAR( 255 ) NULL DEFAULT '-',
  `attachment_id` INT NULL DEFAULT '0',
  `attachment_type` VARCHAR( 255 ) NULL DEFAULT 'text/plain',
  PRIMARY KEY  (`id_download`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][10]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."filesystem ( `id_fs` int(11) unsigned NOT NULL auto_increment,   `filename_fs` varchar(255) NULL,   `url_fs` varchar(255) NULL,   `comment_fs` varchar(255) NULL,   PRIMARY KEY (`id_fs`))
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][11]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."groups (`id_group` int(10) unsigned NOT NULL auto_increment, `title_group` varchar(255) NULL, `description_group` text NULL, `autoaddtousers_group` TINYINT NOT NULL DEFAULT '0',  PRIMARY KEY (`id_group`));
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][12]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."filesystem_regexp (
  `id_fsr` int(10) unsigned NOT NULL auto_increment,
  `regexpr` text NULL,
  `url` text NULL,
  PRIMARY KEY (`id_fsr`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][13]['result']=$result;

$sql="
CREATE TABLE ".$tableusersprefix."privmsg (
  `id_privmsg` int(11) unsigned NOT NULL auto_increment,
  `id_sender_privmsg` int(11) NULL DEFAULT 0,
  `id_recipient_privmsg` int(11) NULL DEFAULT 0,
  `folder_privmsg` tinyint(4) NULL DEFAULT 0,
  `unread_privmsg` tinyint(4) NULL DEFAULT 1,
  `theme_privmsg` varchar(255) NULL,
  `body_privmsg` text NULL,
  `time_privmsg` int(11) NULL,
  PRIMARY KEY (`id_privmsg`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][14]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."log (
  `id_log` int(10) unsigned NOT NULL auto_increment,
  `object_name` VARCHAR(255)  NOT NULL  DEFAULT 'system',
  `object_id` VARCHAR(255)  NOT NULL  DEFAULT '0',
  `type` tinyint(4) NULL DEFAULT 0,
  `description` text NULL,
  `ip` VARBINARY(255) NULL DEFAULT NULL,
  `time` int(11) NULL,
  `user` varchar(255) NULL,
  PRIMARY KEY (`id_log`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][15]['result']=$result;

$sql="
CREATE TABLE ".$tableprefix."tempdata (
 `type_td` varchar(100) NOT NULL,
 `identifier_td` varchar(200) NOT NULL,
 `data_td_text` text NULL,
 `data_td_int` INT UNSIGNED NULL DEFAULT NULL,
 `deleteafter_td` int(10) unsigned NOT NULL DEFAULT '0',
 KEY `type_td` (`type_td`,`identifier_td`)
);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][16]['result']=$result;
$sql="ALTER  TABLE ".$tableprefix."tempdata ADD INDEX ( `deleteafter_td` ) ;";
$result=database_db_query($nameDB, $sql, $lnkDB);

$sql="CREATE TABLE ".$tableprefix."metadata (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`object_name` varchar(50) NOT NULL DEFAULT '',
	`object_id` varchar(50) NOT NULL DEFAULT '',
	`key_name` varchar(100) NOT NULL DEFAULT '',
	`val` text,
	PRIMARY KEY (`id`),
	KEY `object_name` (`object_name`,`object_id`,`key_name`,`val`(50))
)";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][17]['result']=$result;

$sql="CREATE TABLE ".$tableprefix."taxonomy (
					`object_name` varchar(50) NOT NULL DEFAULT '',
					`object_id` int(11) unsigned NOT NULL DEFAULT '0',
					`rel_id` int(11) unsigned NOT NULL DEFAULT '0',
					PRIMARY KEY (`object_name`,`object_id`,`rel_id`)
				)";
$result=database_db_query($nameDB, $sql, $lnkDB);
$inst['tables'][18]['result']=$result;

	$sql=("CREATE TABLE ".$tableprefix."media (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`id_ctg` int(11) unsigned NOT NULL DEFAULT '0',
					`type` varchar(255) NOT NULL DEFAULT 'jpg',
					`title` varchar(255) DEFAULT NULL,
					`originalname` varchar(255) DEFAULT '',
					`filepath` varchar(255) DEFAULT NULL,
					`alt_text` varchar(255) DEFAULT NULL,
					`description` text,
					PRIMARY KEY (`id`)
				)");
	$result=database_db_query($nameDB, $sql, $lnkDB);
	$inst['tables'][19]['result']=$result;

	$sql=("CREATE TABLE ".$tableprefix."categories_media (
					`id_ctg` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`title` varchar(255) DEFAULT NULL,
					`public` tinyint(4) unsigned NOT NULL DEFAULT '1',
					`keywords` varchar(255) DEFAULT NULL,
					`description` varchar(255) DEFAULT NULL,
					`items_count` int(11) unsigned NOT NULL DEFAULT '0',
					`lastupdate` int(11) unsigned NOT NULL DEFAULT '0',
					PRIMARY KEY (`id_ctg`)
				)");
	$result=database_db_query($nameDB, $sql, $lnkDB);
	$inst['tables'][20]['result']=$result;

}

?>