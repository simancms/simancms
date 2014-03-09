<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.6
	//#revision 2014-03-08
	//==============================================================================
	
	require_once("includes/dbsettings.php");
	require_once("includes/dbengine".$serverDB.".php");
	require_once("includes/dbelite.php");

	$url = '';

	$lnkDB = database_connect($hostNameDB, $userNameDB, $userPasswordDB, $nameDB);
	if ($lnkDB != false)
		{
			if (!empty($initialStatementDB))
				$result = database_db_query($nameDB, $initialStatementDB, $lnkDB);
			$replaced = 0;
			if (substr($_GET['rewrittenquery'], -1) == '/')
				$_GET['rewrittenquery'] = substr($_GET['rewrittenquery'], 0, -1);
			$tmp=dbescape($_GET['rewrittenquery']);
			$sql = "SELECT * FROM `".$tableprefix."filesystem` WHERE `filename_fs`='".$tmp."' OR `filename_fs`='".$tmp."/'";
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_assoc($result))
				{
					$url = $row['url_fs'];
				}
		}

	if (empty($url))
		$url = 'index.php?m=404';

	$query = substr($url, 10);

	$options = explode('&', $query);

	for ($i = 0; $i < count($options); $i++)
		{
			$tmp = explode('=', $options[$i]);
			$_GET[$tmp[0]] = $tmp[1];
		}

	require(dirname(__FILE__).'/index.php');

?>