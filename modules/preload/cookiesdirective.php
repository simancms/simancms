<?php

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (intval(sm_settings('cookiesdirective_on'))==1)
		{
			sm_include_lang('cookiesdirective');
			sm_html_bodyend('<script type="text/javascript" src="ext/cookiesdirective/jquery.cookiesdirective.js"></script>');
			sm_html_bodyend('<script type="text/javascript">
				$(document).ready(function(){
					$.cookiesDirective({
						privacyPolicyUri: \'\',
						duration: '.jsescape(sm_settings('cookiesdirective_duration')).',
						explicitConsent: false,
						position : \''.jsescape(sm_settings('cookiesdirective_position')).'\',
						message:\''.jsescape(sm_settings('cookiesdirective_message')).'\',
						scriptWrapper: function(){},
						backgroundColor: \''.jsescape(sm_settings('cookiesdirective_background_color')).'\',
						backgroundOpacity: \''.jsescape(sm_settings('cookiesdirective_background_opacity')).'\',
						fontColor: \''.jsescape(sm_settings('cookiesdirective_font_color')).'\',
						fontSize: \''.jsescape(sm_settings('cookiesdirective_font_size')).'\',
						fontFamily: \''.jsescape(sm_settings('cookiesdirective_font_family')).'\',
						extra_button_style: \''.jsescape(sm_settings('cookiesdirective_extra_button_style')).'\',
						extra_container_style: \''.jsescape(sm_settings('cookiesdirective_extra_container_style')).'\',
						zindex: \''.jsescape(sm_settings('cookiesdirective_zindex')).'\',
						button_message: \''.jsescape($lang['module_cookiesdirective']['hide_message']).'\'
					});
				});
				</script>');
		}