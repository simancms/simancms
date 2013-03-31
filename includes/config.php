<?php

//------------------------------------------------------------------------------
//|            Content Management System SiMan CMS                             |
//|                http://www.simancms.org                                     |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.4
//#revision 2013-03-31
//==============================================================================

$_settings['version']='1.6.4';

$_settings['default_news_text_style']='0'; // 0 1
$_settings['content_multiview']='on';// on off
$_settings['show_script_info']='off';// on off

$sql="SELECT * FROM ".$tableprefix."settings WHERE mode='default'";
$result=database_db_query($nameDB, $sql, $lnkDB);
while ($row=database_fetch_object($result))
	{
		$_settings[$row->name_settings]=$row->value_settings;
	}

$special['deviceinfo']=sm_detect_device();

if ($special['deviceinfo']['is_mobile'])
	{
		$sql="SELECT * FROM ".$tableprefix."settings WHERE mode='mobile'";
		$result=database_db_query($nameDB, $sql, $lnkDB);
		while ($row=database_fetch_object($result))
			{
				$_settings[$row->name_settings]=$row->value_settings;
			}
	}

if ($special['deviceinfo']['is_tablet'])
	{
		$sql="SELECT * FROM ".$tableprefix."settings WHERE mode='tablet'";
		$result=database_db_query($nameDB, $sql, $lnkDB);
		while ($row=database_fetch_object($result))
			{
				$_settings[$row->name_settings]=$row->value_settings;
			}
	}

$_settings['show_help']='on'; //on off

?>