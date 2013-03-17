<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|         Система керування вмістом сайту SiMan CMS                          |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//|               (c) Aged Programmer's Group                                  |
//|                http://www.apserver.org.ua                                  |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.4.0.1                                                                 |
//==============================================================================


if (!defined("EXTEDITOR_FUNCTIONS_DEFINED"))
	{
		function siman_prepare_to_exteditor($str)
			{
				return $str;
			}
		
		$_settings['ext_editor_toolbar']='Siman';	//Basic,Default,Siman
		define("EXTEDITOR_FUNCTIONS_DEFINED", 1);
	}

?>