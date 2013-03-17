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
//                                                               |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

function siman_block_items_news($blockinfo)
	{
		global $nameDB, $tableprefix, $lnkDB, $lang;
		$sql="SELECT * FROM ".$tableprefix."categories_news";
		$result=database_db_query($nameDB, $sql, $lnkDB);
		$i=0;
		while ($row=database_fetch_object($result))
			{
				$res[$i]['caption']=' - '.$lang['show_on_category'].': '.$row->title_category;
				$res[$i]['value']='news|'.$row->id_category;
				if (strcmp($blockinfo["show_on_module_block"], 'news')==0 && $blockinfo["show_on_ctg_block"]==$row->id_category)
					$res[$i]['selected']=1;
				$i++;
			}
		return $res;
	}

?>