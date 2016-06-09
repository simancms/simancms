<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2016-06-09
	//==============================================================================

	if (!defined("SMFormatter_DEFINED"))
		{
			class SMFormatter
				{
					public static function Money($float)
						{
							return number_format($float, 2, '.', '');
						}

					public static function Weight($float)
						{
							return number_format($float, 3, '.', '');
						}

					public static function FloatFine($float, $precision=2)
						{
							return number_format($float, $precision, '.', ' ');
						}

					public static function IntFine($float)
						{
							return number_format($float, 0, '.', ' ');
						}

					public static function Date($unixtimestamp)
						{
							global $lang;
							return strftime($lang['datemask'], $unixtimestamp);
						}

					public static function DateTime($unixtimestamp)
						{
							global $lang;
							return strftime($lang['datetimemask'], $unixtimestamp);
						}

					public static function Time($unixtimestamp)
						{
							global $lang;
							return strftime($lang['timemask'], $unixtimestamp);
						}

					public static function FileSize($size_in_bytes, $divider=' ')
						{
							$base=1024;
							$lim=1024;
							if ($size_in_bytes<$lim)
								return intval($size_in_bytes).$divider.'B';
							$lim*=$base;
							if ($size_in_bytes<$lim)
								return (floor($size_in_bytes/($lim/$base)*10)/10).$divider.'KiB';
							$lim*=$base;
							if ($size_in_bytes<$lim)
								return (floor($size_in_bytes/($lim/$base)*10)/10).$divider.'MiB';
							$lim*=$base;
							return (floor($size_in_bytes/($lim/$base)*10)/10).$divider.'GiB';
						}
				}

			define("SMFormatter_DEFINED", 1);
		}

?>