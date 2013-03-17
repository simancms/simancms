<?php

/*
print("URL=");
print($_SERVER['REQUEST_URI']);
print("<br>q=");
print($_GET['q']);
print("<br>GET=");
print_r($_GET);
print('<hr>');
*/

require_once("includes/dbsettings.php");
require_once("includes/dbengine".$serverDB.".php");

$url='';

$lnkDB1 = database_connect($hostNameDB, $userNameDB, $userPasswordDB, $nameDB);
if ($lnkDB1!=false)
	{
		if (!empty($initialStatementDB))
			$result=database_db_query($nameDB, $initialStatementDB, $lnkDB1);
		$replaced=0;
		$sql="SELECT * FROM ".$tableprefix."filesystem WHERE filename_fs='".dbescape($_GET['rewrittenquery'])."'";
		$result=database_db_query($nameDB, $sql, $lnkDB1);
		while ($row=database_fetch_object($result))
			{
				$url=$row->url_fs;
				$replaced=1;
			}
		if ($replaced!=1)
			{
				$sql="SELECT * FROM ".$tableprefix."filesystem_regexp";
				$result=database_db_query($nameDB, $sql, $lnkDB1);
				while (($row=database_fetch_object($result)) && $replaced!=1)
					{
						if (ereg($row->regexpr, $_GET['rewrittenquery']))
							{
								$url=eregi_replace($row->regexpr, $row->url, $_GET['q']);
								$replaced=1;
							}
					}
			}
	}

if (empty($url))
	$url='index.php?m=404';

//$_GET=Array();

$query=substr($url,10);
//print($query);

$options=explode('&', $query);

//print_r($options);

for ($i=0; $i<count($options); $i++)
	{
		$tmp=explode('=', $options[$i]);
		$_GET[$tmp[0]]=$tmp[1];
	}

//print('<hr>');

//print_r($_GET);

require('index.php');

?>