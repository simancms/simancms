<?php

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	function yourubereplacer_embed_generation($str)
		{
			global $sm;
			$pattern = '/https*:\/\/www\.youtube\.com\/watch\?(.*)v=([a-zA-Z0-9_\-]+)(\S*)/i';
			$replace = sm_settings('youtubereplacerstarthtml').'<div class="ytreplacer-container"><iframe class="ytreplacer-iframe" width="'.$sm['_s']['youtubereplacerwidth'].'" height="'.$sm['_s']['youtubereplacerheight'].'" src="//www.youtube.com/embed/$2" frameborder="0" allowfullscreen></iframe></div>';
			if (intval(sm_settings('youtubereplacershowyturl'))==1)
				$replace .= '<div class="ytreplacer-url">http://youtu.be/$2</div>';
			$replace .= sm_settings('youtubereplacerendhtml');
			return preg_replace($pattern, $replace, $str);
		}

	if (intval(sm_settings('youtubereplaceron'))==1)
		{
			for ($i = 0; $i < count($special['contentmodifier']); $i++)
				{
					if (strpos($special['contentmodifier'][$i], 'youtube'))
						{
							$special['contentmodifier'][$i] = yourubereplacer_embed_generation($special['contentmodifier'][$i]);
						}
				}
		}

?>