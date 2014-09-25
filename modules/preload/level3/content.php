<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-09-25
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	function siman_block_items_content($blockinfo)
		{
			global $nameDB, $tableprefix, $lnkDB, $lang;
			$sql = "SELECT * FROM ".$tableprefix."categories";
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_object($result))
				{
					$res[$i]['caption'] = ' - '.$lang['show_on_category'].': '.$row->title_category;
					$res[$i]['value'] = 'content|'.$row->id_category;
					if (strcmp($blockinfo["show_on_module_block"], 'content') == 0 && $blockinfo["show_on_ctg_block"] == $row->id_category)
						$res[$i]['selected'] = 1;
					$i++;
				}
			return $res;
		}


?>