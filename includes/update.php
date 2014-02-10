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
	if (intval(sm_settings('database_date'))<20140315)//1.6.6
		{
			sm_add_settings('content_multiview', 'on');
			sm_add_settings('default_news_text_style', '0');
			sm_add_settings('show_help', 'on');
			sm_update_settings('database_date', '20140315');
		}

?>