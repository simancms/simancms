<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Media
	Module URI: http://simancms.org/modules/content/
	Description: Media files management. Base CMS module
	Version: 1.6.7
	Revision: 2014-06-07
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("MEDIA_FUNCTIONS_DEFINED"))
		{
			
			define("MEDIA_FUNCTIONS_DEFINED", 1);
		}
	
	sm_default_action('galleries');
	
	if ($userinfo['level']>2)
		include('modules/inc/adminpart/media.php');

?>