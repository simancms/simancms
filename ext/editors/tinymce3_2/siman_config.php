<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	if (!defined("EXTEDITOR_FUNCTIONS_DEFINED"))
		{
			function siman_prepare_to_exteditor($str)
				{
					return $str;
				}

			function siman_exteditor_insert_image($image)
				{
					return "tinyMCE.execCommand('mceInsertContent',false,'<img src=\\'".jsescape($image)."\\'>')";
				}

			$_settings['ext_editor_toolbar'] = 'Siman'; //Basic,Default,Siman
			define("EXTEDITOR_FUNCTIONS_DEFINED", 1);
		}

?>