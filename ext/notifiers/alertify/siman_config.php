<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	if (!defined("NOTIFIER_FUNCTIONS_DEFINED"))
		{
			if (is_array($sm['s']['notifications']))
				{
					sm_add_cssfile('ext/notifiers/alertify/themes/alertify.core.css', 1);
					sm_add_cssfile('ext/notifiers/alertify/themes/alertify.default.css', 1);
					sm_add_jsfile('ext/notifiers/alertify/lib/alertify.min.js', 1);
					sm_html_bodyend('<script>(function() {alertify.set({ delay: 7000 });');
					foreach ($sm['s']['notifications'] as $key=>$val)
						sm_html_bodyend('alertify.success("'.(empty($val['title'])?'':'<b>'.jsescape($val['title']).'<b><br />').jsescape($val['message']).'");');
					sm_html_bodyend('})();</script>');
				}
		}

?>