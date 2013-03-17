<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.1	                                                               |
//#revision 2011-08-18                                                         |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if (empty($modules[$modules_index]["mode"])) $modules[$modules_index]["mode"]='view';
if (!empty($modules[$modules_index]["bid"])) $modules[$modules_index]["mid"]=$modules[$modules_index]["bid"];
$menu_id=$modules[$modules_index]["mid"];

if (strcmp($modules[$modules_index]["mode"], 'view')==0)
	{
		$sql="SELECT * FROM ".$tableprefix."menus WHERE id_menu_m='$menu_id'";
		$result=database_db_query($nameDB, $sql, $lnkDB);
		while ($row=database_fetch_object($result))
			{
				$modules[$modules_index]["module"]='menu';
				$modules[$modules_index]["title"]=$row->caption_m;
			}
		$modules[$modules_index]['menu']=siman_load_menu($menu_id);
		siman_add_modifier_menu($modules[$modules_index]['menu']);
		sm_add_title_modifier($modules[$modules_index]['title']);
		sm_page_viewid('menu-view-'.$menu_id);
	}

if ($userinfo['level']==3)
	include('modules/inc/adminpart/menu.php');
 
?>