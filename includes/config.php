<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	$_settings['version'] = '1.6.6';

	$sql = "SELECT * FROM ".$tableprefix."settings WHERE mode='default'";
	$result = database_db_query($nameDB, $sql, $lnkDB);
	while ($row = database_fetch_assoc($result))
		{
			$_settings[$row['name_settings']] = $row['value_settings'];
		}

	$special['deviceinfo'] = sm_detect_device();

	if ($special['deviceinfo']['is_mobile'])
		{
			$sql = "SELECT * FROM ".$tableprefix."settings WHERE mode='mobile'";
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_assoc($result))
				{
					$_settings[$row['name_settings']] = $row['value_settings'];
				}
		}

	if ($special['deviceinfo']['is_tablet'])
		{
			$sql = "SELECT * FROM ".$tableprefix."settings WHERE mode='tablet'";
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_assoc($result))
				{
					$_settings[$row['name_settings']] = $row['value_settings'];
				}
		}
	
	include(dirname(__FILE__).'/config_def.php');
	if (file_exists(dirname(__FILE__).'/config_usr.php'))
		include(dirname(__FILE__).'/config_usr.php');

?>