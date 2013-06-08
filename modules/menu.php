<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Navigation
	Module URI: http://simancms.org/modules/menu/
	Description: Navigation management module. Base CMS module
	Version: 1.6.4
	Revision: 2013-06-07
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if (empty($m["mode"]))
		$m["mode"] = 'view';
	if (!empty($m["bid"]))
		$m["mid"] = $m["bid"];
	$menu_id = $m["mid"];

	if (strcmp($m["mode"], 'view') == 0)
		{
			$sql = "SELECT * FROM ".$tableprefix."menus WHERE id_menu_m=".intval($menu_id);
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_object($result))
				{
					$m["module"] = 'menu';
					$m["title"] = $row->caption_m;
				}
			$m['menu'] = siman_load_menu($menu_id);
			siman_add_modifier_menu($m['menu']);
			sm_add_title_modifier($m['title']);
			sm_page_viewid('menu-view-'.$menu_id);
		}

	if ($userinfo['level'] == 3)
		include('modules/inc/adminpart/menu.php');

?>