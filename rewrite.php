<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.5
	//#revision 2013-10-27
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
			$_GET['rewrittenquery'] = dbescape($_GET['rewrittenquery']);
			$sql = "SELECT * FROM ".$tableprefix."filesystem WHERE filename_fs='".$_GET['rewrittenquery']."' OR  filename_fs='".$_GET['rewrittenquery']."/'";
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_object($result))
				{
					$url = $row->url_fs;
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