<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-08-17
	//==============================================================================

	//Preload modules begin
	sm_event('beforepreload', Array());
	$autoloadmodules = nllistToArray($_settings['autoload_modules']);
	for ($autoloadmodulesindex = 0; $autoloadmodulesindex < count($autoloadmodules); $autoloadmodulesindex++)
		{
			if (strpos($autoloadmodules[$autoloadmodulesindex], ':')!==false || strpos($autoloadmodules[$autoloadmodulesindex], '.')!==false || strpos($autoloadmodules[$autoloadmodulesindex], '/')!==false || strpos($autoloadmodules[$autoloadmodulesindex], '\\')!==false || empty($autoloadmodules[$autoloadmodulesindex]))
				continue;
			if (file_exists('modules/preload/'.$autoloadmodules[$autoloadmodulesindex].'.php'))
				include_once('modules/preload/'.$autoloadmodules[$autoloadmodulesindex].'.php');
			if (file_exists('modules/preload/level'.$userinfo['level'].'/'.$autoloadmodules[$autoloadmodulesindex].'.php'))
				include_once('modules/preload/level'.$userinfo['level'].'/'.$autoloadmodules[$autoloadmodulesindex].'.php');
		}
	sm_event('afterpreload', Array());
	//Preload modules end

?>