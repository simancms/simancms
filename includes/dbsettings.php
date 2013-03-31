<?php

//------------------------------------------------------------------------------
//|            Content Management System SiMan CMS                             |
//|                http://www.simancms.org                                     |
//------------------------------------------------------------------------------

//Database server type:
// MySQL=0
$serverDB=0;

//Database server name (usually localhost)
$hostNameDB='localhost';

//Database name
$nameDB='siman_164';

//Database user name
$userNameDB='root';

//Database user password
$userPasswordDB='';

//Initial statement after connect to database
$initialStatementDB="SET NAMES 'utf8'";
//$initialStatementDB="SET NAMES 'cp1251'";

//Table prefix
$tableprefix='sm_';

//Table `users` prefix.
// Leave ampte for default value.
$tableusersprefix='';

//Session prefix. You need to change it for your site to prevent hacks
$session_prefix='needchange164_';

//Use protect code (captcha). 1 - on, 0 - off
$_settings['use_protect_code']=1;

//Don't change code below
if (empty($tableusersprefix)) $tableusersprefix=$tableprefix;


?>