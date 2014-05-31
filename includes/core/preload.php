<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-05-31
	//==============================================================================

	//Preload modules begin
	sm_event('beforepreload', Array());
	$autoloadmodules = nllistToArray($_settings['autoload_modules']);
	for ($autoloadmodulesindex = 0; $autoloadmodulesindex < count($autoloadmodules); $autoloadmodulesindex++)
		{
			if (!strpos($autoloadmodules[$autoloadmodulesindex], ':') && !strpos($autoloadmodules[$autoloadmodulesindex], '.') && !strpos($autoloadmodules[$autoloadmodulesindex], '/') && !strpos($autoloadmodules[$autoloadmodulesindex], '\\'))
				include_once('modules/preload/'.$autoloadmodules[$autoloadmodulesindex].'.php');
		}
	sm_event('afterpreload', Array());
	//Preload modules end

?>