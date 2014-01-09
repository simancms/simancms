<?php

	if (intval(sm_settings('database_date'))<20130420)//1.6.4
		{
			sm_add_settings('redirect_on_success_change_usrdata', '');
			sm_add_settings('signinwithloginandemail', '0');
			sm_update_settings('database_date', '20130420');
		}
	if (intval(sm_settings('database_date'))<20140115)//1.6.5
		{
			execsql("ALTER TABLE `".$sm['tu']."log` CHANGE `ip` `ip` VARBINARY(255) NULL DEFAULT NULL;");
			sm_add_settings('news_editor_level', '3');
			sm_add_settings('content_editor_level', '3');
			sm_update_settings('database_date', '20140115');
		}

?>