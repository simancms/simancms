<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//Database server type:
	// MySQL (discontinued mysql extension)=0
	// SQLite=2
	// MySQL (mysqli extension)=3
	$serverDB = 3;

	//Database server name (usually localhost)
	$hostNameDB = 'localhost';

	//Database name
	$nameDB = 'siman_1615';

	//Database user name
	$userNameDB = 'root';

	//Database user password
	$userPasswordDB = '';

	//Initial statement after connect to database
	$initialStatementDB = "SET NAMES 'utf8'";
	//$initialStatementDB="SET NAMES 'cp1251'";

	//Table prefix
	$tableprefix = 'sm_';

	//Table `users` prefix.
	// Leave empty for default value.
	$tableusersprefix = '';

	//Session prefix. You need to change it for your site to prevent hacks
	$session_prefix = 'needchange1615_';

	//Salt. You need to change it before installation to prevent hacks
	// Do not change it after installation - all passwords will be lost
	$siman_salt = 'salt';

	//Caching of pages; 0 - disabled; positive integer - min time for caching in seconds
	$siman_cache = 0;

	$siman_useragent_blacklist=Array();

	$siman_block_empty_useragent=false;

	//Don't change code below
	if (empty($tableusersprefix)) $tableusersprefix = $tableprefix;
