<?php

//---------------------------------------------------------------
//--------[Заповнення таблиць]-[Posting data to tables]----------
//---------------------------------------------------------------

//-----------[Статичні блоки]-[Static blocks]--------------------

$sql="
INSERT INTO ".$tableprefix."blocks (panel_block, position_block, name_block, caption_block, showed_id, level, editsource_block) VALUES ('1', 1, 'menu','".$lang['p_blocks']['admin_menu']."',1,3,'index.php?m=menu&d=listlines&mid=1');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."blocks (panel_block, position_block, name_block, caption_block, showed_id, level, editsource_block) VALUES ('1', 2, 'menu','".$lang['p_blocks']['main_menu']."',2,0,'index.php?m=menu&d=listlines&mid=2');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."blocks (panel_block, position_block, name_block, caption_block, showed_id, level) VALUES ('2', 1, 'account','".$lang['p_blocks']['login']."',1,0);
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//------------------[Категорії]-[Categories]--------------------

$sql="
INSERT INTO ".$tableprefix."categories (title_category, public_menu) VALUES ('[------------]',0);
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//----------[Сторінка привітання]-[Wellcome page]---------------

$sql="
INSERT INTO ".$tableprefix."content (id_category_c, title_content, text_content, type_content) VALUES (1,'".$lang['p_welcome_page']['title']."','".$lang['p_welcome_page']['text']."',1);
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//---------------[Меню адміністратора]-[Admin menu]---------------------

$sql="
INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('".$lang['p_menu']['admin_menu']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//---------------[Головне меню]-[Main menu]---------------------

$sql="
INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('".$lang['p_menu']['main_menu']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//---------------[Top and bottom nav]---------------------

$sql="
INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('".$lang['p_menu']['top_menu']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menus (caption_m) VALUES ('".$lang['p_menu']['bottom_menu']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//-----------------[Рядки меню]-[Menu lines]--------------------

$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (2,'".(strlen(str_replace('/install/install.php', '', $_SERVER['SCRIPT_NAME']))==0?'/':'index.php')."','".$lang['p_mlines']['main']."',1);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (2,'news/','".$lang['p_mlines']['news']."',2);
";
$result=database_db_query($nameDB, $sql, $lnkDB);

$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=content&d=add','".$lang['p_mlines']['adm_new_content']."',1);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=news&d=add','".$lang['p_mlines']['adm_new_news']."',2);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=content&d=list','".$lang['p_mlines']['adm_content']."',3);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=news&d=list','".$lang['p_mlines']['adm_news']."',4);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=admin&d=modules','".$lang['p_mlines']['modules_management']."',5);
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (1,'index.php?m=admin','".$lang['p_mlines']['control_panel']."',6);
";
$result=database_db_query($nameDB, $sql, $lnkDB);

$sql="
INSERT INTO ".$tableprefix."menu_lines (id_menu_ml, url, caption_ml, position) VALUES (3,'".(strlen(str_replace('/install/install.php', '', $_SERVER['SCRIPT_NAME']))==0?'/':'index.php')."','".$lang['p_mlines']['main']."',1);
";
$result=database_db_query($nameDB, $sql, $lnkDB);


	//---------------------[Модулі]-[Modules]-----------------------

$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title, search_fields, search_doing, search_var, search_table, search_title, search_idfield, search_text) VALUES ('content', '".$lang['p_modules']['content']."', 'title_content text_content', 'view', 'cid', 'content', 'title_content', 'id_content', 'text_content');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title, search_fields, search_doing, search_var, search_table, search_title, search_idfield, search_text) VALUES ('news', '".$lang['p_modules']['news']."', 'text_news', 'view', 'nid', 'news', 'title_news', 'id_news', 'text_news');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title) VALUES ('menu','".$lang['p_modules']['menu']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title) VALUES ('download', '".$lang['p_modules']['download']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title) VALUES ('search', '".$lang['p_modules']['search']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."modules (module_name, module_title) VALUES ('media', '".$lang['p_modules']['search']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);


//----------[Категорії новин]-[Categories news]----------------

$sql="
INSERT INTO ".$tableprefix."categories_news (title_category) VALUES ('[------------]');
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//-------------------[Новини]-[News]--------------------------

$sql="
INSERT INTO ".$tableprefix."news (id_category_n, date_news, title_news, text_news, type_news) VALUES ('1', '".time()."', '".$inst['p_news']['firs_news']."', '".$inst['p_news']['firs_news_text']."', '1')
";
$result=database_db_query($nameDB, $sql, $lnkDB);

//-------------[virtual filesystem]------------

$sql="
INSERT INTO ".$tableprefix."filesystem (filename_fs, url_fs, comment_fs) VALUES ('login/', 'index.php?m=account', '".$lang['p_blocks']['login']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);
$sql="
INSERT INTO ".$tableprefix."filesystem (filename_fs, url_fs, comment_fs) VALUES ('news/', 'index.php?m=news', '".$lang['p_mlines']['news']."');
";
$result=database_db_query($nameDB, $sql, $lnkDB);



?>