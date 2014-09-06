<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-04-26
	//==============================================================================

	if (!in_array(php_sapi_name(), Array('cli', 'cgi-fcgi')) && @get_magic_quotes_gpc() == 1)
		exit('Configuration error! magic_quotes_gpc is on.');
	if (!file_exists('files/temp'))
		exit('SiMan CMS is not instaled!');

	define("SIMAN_DEFINED", 1);

	$special['rand'] = rand();
	$special['time']['generation_begin'] = microtime();
	$special['time']['generation_begin'] = explode(' ', $special['time']['generation_begin']);
	$special['time']['generation_begin'] = $special['time']['generation_begin'][0] + $special['time']['generation_begin'][1];
	require_once("includes/dbsettings.php");
	require_once("includes/dbengine".$serverDB.".php");
	require_once("includes/dbelite.php");
	require_once("includes/simplyquery.php");
	if (file_exists("includes/core/init_usr.php"))
		require_once("includes/core/init_usr.php");
	require_once("includes/core/init.php");
	require_once("includes/functions.php");
	require_once("includes/smcore.php");
	require_once('Smarty/libs/Smarty.class.php');

	if (!isset($lnkDB))
		$lnkDB = @database_connect($hostNameDB, $userNameDB, $userPasswordDB, $nameDB);
	if ($lnkDB != false)
		{
			$special['page']['url'] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$special['page']['parsed_url'] = @parse_url($special['page']['url']);
			$special['page']['scheme'] = $special['page']['parsed_url']['scheme'];
			if (!empty($initialStatementDB))
				$result = database_db_query($nameDB, $initialStatementDB, $lnkDB);
			require("includes/config.php");
			$sm['_s'] =& $_settings;
			if ($_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == substr($_settings['resource_url'], strpos($_settings['resource_url'], '/')).'index.php')
				sm_redirect_now('http://'.$_settings['resource_url'], 301);
			if (!empty($_settings['default_timezone']))
				date_default_timezone_set($_settings['default_timezone']);
			if (empty($_settings['database_date']))
				$special['dberror'] = true;
		}
	else
		$special['dberror'] = true;
	
	if (!$sm['s']['nosmarty'])
		$smarty = new Smarty;

	if (!$special['dberror'])
		{
			if ($_settings['resource_url_rewrite'] == 1)
				{
					$special['resource_url'] = $special['page']['parsed_url']['host'].substr($_settings['resource_url'], strpos($_settings['resource_url'], '/'));
				}
			else
				{
					$special['resource_url'] = $_settings['resource_url'];
				}
			if ($special['deviceinfo']['is_mobile'])
				{
					if (!empty($_settings['resource_url_mobile']) && $special['resource_url'] == $_settings['resource_url'])
						{
							$_getvars["m"] = 'refresh';
							$_getvars["d"] = 'view';
							$refresh_url = $special['page']['scheme'].'://'.$_settings['resource_url_mobile'];
						}
				}
			if ($special['deviceinfo']['is_tablet'])
				{
					if (!empty($_settings['resource_url_tablet']) && $special['resource_url'] == $_settings['resource_url'])
						{
							$_getvars["m"] = 'refresh';
							$_getvars["d"] = 'view';
							$refresh_url = $special['page']['scheme'].'://'.$_settings['resource_url_tablet'];
						}
				}

			sm_change_language($_settings['default_language']);

			sm_change_theme($_settings['default_theme']);

			$module = $_getvars["m"];
			$mode = $_getvars["d"];

			$special['sql']['count'] = 0;

			if (count($_getvars) == 0)
				$special['is_index_page'] = 1;

			if (empty($module) || strpos($module, ':') || strpos($module, '.') || strpos($module, '/') || strpos($module, '\\'))
				{
					$module = $_settings['default_module'];
					$mode = '';
					$_getvars["d"] = '';
					if (empty($_getvars["cid"]))
						{
							$_getvars["cid"] = 1;
						}
				}
			if (!file_exists('modules/'.$module.'.php'))
				{
					$module = '404';
				}

			if (!empty($_settings['banned_ip']))
				{
					$banip = explode(' ', $_settings['banned_ip']);
					for ($i = 0; $i < count($banip); $i++)
						{
							if (strcmp($banip[$i], $_servervars['REMOTE_ADDR']) == 0)
								{
									if (!$sm['s']['nosmarty'])
										{
											$smarty->assign('errorname', 'banerror');
											$smarty->display('error.tpl');
										}
									exit;
								}
						}
				}
			if (!empty($_settings['autoban_ips']))
				{
					$banip = nllistToArray($_settings['autoban_ips']);
					for ($i = 0; $i < count($banip); $i++)
						{
							if (strcmp($banip[$i], $_servervars['REMOTE_ADDR']) == 0)
								{
									sm_extcore();
									if (intval(sm_tempdata_aggregate('bannedip', $_servervars['REMOTE_ADDR'], SM_AGGREGATE_COUNT)) > 0)
										{
											if (!$sm['s']['nosmarty'])
												{
													$smarty->assign('errorname', 'banerror');
													$smarty->display('error.tpl');
												}
											exit;
										}
									else
										{
											//unblock this person
											sm_update_settings('autoban_ips', removefrom_nllist(sm_get_settings('autoban_ips'), $_servervars['REMOTE_ADDR']));
											sm_tempdata_clean('bannedip', $_servervars['REMOTE_ADDR']);
										}
								}
						}
				}
			if (!empty($_settings['install_not_erased']))
				{
					if (file_exists('./install') || file_exists('./upgrade') || file_exists('./includes/update.php'))
						{
							if (!$sm['s']['nosmarty'])
								{
									$smarty->assign('errorname', 'noterasedinstall');
									$smarty->assign('lang', $_settings['default_language']);
									$smarty->display('error.tpl');
								}
							exit;
						}
					else
						{
							sm_update_settings('install_not_erased', '');
						}
				}

			require("includes/userinfo.php");
			$sm['u'] =& $userinfo;
			//Autologin feature
			if ($userinfo['level'] < 1 && !empty($_cookievars[$_settings['cookprefix'].'simanautologin']))
				{
					$tmpusrinfo = getsql("SELECT * FROM ".$tableusersprefix."users WHERE md5(concat('".$session_prefix."', random_code, id_user))='".dbescape($_cookievars[$_settings['cookprefix'].'simanautologin'])."' AND user_status>0 LIMIT 1");
					if (!empty($tmpusrinfo['id_user']))
						{
							sm_login($tmpusrinfo['id_user'], $tmpusrinfo);
							require("includes/userinfo.php");
							log_write(LOG_LOGIN, $lang['module_account']['log']['user_logged'].' - '.$lang['common']['auto_login']);
							$sm['s']['autologin'] = 1;
						}
					else
						{
							setcookie($_settings['cookprefix'].'simanautologin', '');
						}
					unset($tmpusrinfo);
				}

			if ($userinfo['level'] == 3 && !empty($_settings['ext_editor']))
				require('ext/editors/'.$_settings['ext_editor'].'/siman_config.php');

			$special['meta']['keywords'] = $_settings['meta_keywords'];
			$special['meta']['description'] = $_settings['meta_description'];

			include('includes/core/preload.php');
			if ($singleWindow == 1)
				{
					$modules_index = 0;
					$modules[$modules_index]["mode"] = $_getvars["d"];
					$sm['modules'] =& $modules;
					$sm['index'] =& $modules_index;
					$m =& $modules[$modules_index];
					include('modules/'.$module.'.php');
					if (!$sm['s']['nosmarty'])
						{
							$smarty->assign('_settings', $_settings);
							$smarty->assign('lang', $lang);
							$smarty->assign('special', $special);
							$smarty->display($special['main_tpl'].'.tpl');
						}
				}
			else
				{
					if ($sm['s']['autologin'] == 1)
						sm_event('successlogin', array($userinfo['id']));

					$special['categories']['id'] = 0;

					//Main module loading begin
					$modules_index = 0;
					$special['categories']['getctg'] = 1;
					$modules[$modules_index]["panel"] = "center";
					$modules[$modules_index]["mode"] = $_getvars["d"];
					$sm['modules'] =& $modules;
					$sm['index'] =& $modules_index;
					$m =& $modules[$modules_index];
					$sm['m'] =& $modules[$modules_index];
					if ($special['no_borders_main_block'])
						$modules[$modules_index]['borders_off'] = 1;
					if ($module <> '404')
						include('modules/'.$module.'.php');
					if (empty($modules[$modules_index]['module']))
						{
							$modules[$modules_index]['module'] = '404';
							$special['is_index_page'] = 0;
						}
					if ($special['dont_take_a_title'] != 1)
						$special['pagetitle'] = $modules[$modules_index]['title'];
					if ($special['is_index_page'] == 1 && !empty($_settings['rewrite_index_title']))
						$special['pagetitle'] = $_settings['rewrite_index_title'];
					if (!empty($_msgbox["mode"]))
						{
							$module = 'msgbox';
							include($module.".php");
						}
					$special['categories']['getctg'] = 0;
					//Main module loading end

					if (!$special['no_blocks'])
						{
							//Static blocks out
							$sql = "SELECT * FROM ".$tableprefix."blocks ORDER BY position_block ASC";
							$pnlresult = database_db_query($nameDB, $sql, $lnkDB);
							while ($pnlrow = database_fetch_object($pnlresult))
								{
									$dont_show_high_priority = 0;
									if ((intval($userinfo['level']) < intval($pnlrow->level) && $pnlrow->thislevelonly == 0) && compare_groups($userinfo['groups'], $pnlrow->groups_view) != 1)
										$dont_show_high_priority = 1;
									elseif ((intval($userinfo['level']) > intval($pnlrow->level) && $pnlrow->thislevelonly == -1) && compare_groups($userinfo['groups'], $pnlrow->groups_view) != 1)
										$dont_show_high_priority = 1;
									elseif (intval($userinfo['level']) != intval($pnlrow->level) && $pnlrow->thislevelonly == 1)
										$dont_show_high_priority = 1;
									elseif ($special['is_index_page'] != 1 && strcmp('#index#', $pnlrow->show_on_module) == 0 && $pnlrow->dont_show_modif != 1)
										$dont_show_high_priority = 1;
									if (!empty($pnlrow->showontheme) && $pnlrow->showontheme == sm_current_theme())
										$dont_show_high_priority = 1;
									if (!empty($pnlrow->showonlang) && $pnlrow->showontheme == $sm['s']['lang'])
										$dont_show_high_priority = 1;
									$show_panel = 1;
									if (!empty($pnlrow->show_on_module))
										{
											$show_panel = 0;
											if ((strcmp($pnlrow->show_on_module, $module) == 0 || (empty($pnlrow->show_on_doing) && empty($modules[0]["mode"]) || strcmp($pnlrow->show_on_doing, $modules[0]["mode"]) == 0)) || ($special['is_index_page'] == 1 && strcmp('#index#', $pnlrow->show_on_module) == 0))
												{
													if ($pnlrow->show_on_ctg != 0)
														{
															if ($special['categories']['id'] == $pnlrow->show_on_ctg)
																$show_panel = 1;
														}
													else
														$show_panel = 1;
												}
										}
									if ($pnlrow->dont_show_modif == 1)
										$show_panel = abs($show_panel - 1);
									if ($show_panel == 0 && !empty($pnlrow->show_on_viewids) && !empty($special['page']['viewid']))
										{
											$tmpviewidslist = nllistToArray($pnlrow->show_on_viewids);
											for ($i = 0; $i < count($tmpviewidslist); $i++)
												{
													if ($tmpviewidslist[$i] == $special['page']['viewid'])
														{
															$show_panel = 1;
															break;
														}
												}
											unset($tmpviewidslist);
										}
									if ($show_panel == 1 && !empty($pnlrow->show_on_device))
										{
											if ($pnlrow->show_on_device == 'desktop' && !$special['deviceinfo']['is_desktop'])
												$show_panel = 0;
											elseif ($pnlrow->show_on_device == 'mobile' && !$special['deviceinfo']['is_mobile'])
												$show_panel = 0;
											elseif ($pnlrow->show_on_device == 'tablet' && !$special['deviceinfo']['is_tablet'])
												$show_panel = 0;
										}
									if ($dont_show_high_priority == 1)
										$show_panel = 0;
									if ($show_panel == 1)
										{
											$modules_index++;
											if ($pnlrow->panel_block == 'l')
												{
													$modules[$modules_index]["panel"] = "1";
												}
											elseif ($pnlrow->panel_block == 'r')
												{
													$modules[$modules_index]["panel"] = "2";
												}
											elseif (is_numeric($pnlrow->panel_block))
												{
													$modules[$modules_index]["panel"] = $pnlrow->panel_block;
												}
											else
												{
													$modules[$modules_index]["panel"] = "center";
												}
											$modules[$modules_index]["borders_off"] = $pnlrow->no_borders;
											$modules[$modules_index]["bid"] = $pnlrow->showed_id;
											$modules[$modules_index]["mode"] = $pnlrow->doing_block;
											if ($_settings['blocks_use_image'] == 1)
												{
													if (file_exists('./files/img/block'.$pnlrow->id_block.'.jpg'))
														{
															$modules[$modules_index]['block_image'] = 'files/img/block'.$pnlrow->id_block.'.jpg';
														}
												}
											$modules[$modules_index]['rewrite_title_to'] = $pnlrow->rewrite_title;
											$m =& $modules[$modules_index];
											$sm['m'] =& $modules[$modules_index];
											if (empty($pnlrow->name_block) && !empty($pnlrow->text_block))
												{
													$m['title'] = $pnlrow->rewrite_title;
													$m['mode'] = 'view';
													$m['module'] = 'content';
													$m['content'][0]['can_view'] = 1;
													$m['content'][0]["text"] = $pnlrow->text_block;
													sm_add_title_modifier($m['title']);
													sm_add_content_modifier($m['content'][0]["text"]);
												}
											else
												include('modules/'.$pnlrow->name_block.'.php');
										}
									else
										{
											$modules_index++;
											if ($pnlrow->panel_block == 'l')
												{
													$modules[$modules_index]["panel"] = "1";
												}
											elseif ($pnlrow->panel_block == 'r')
												{
													$modules[$modules_index]["panel"] = "2";
												}
											elseif (is_numeric($pnlrow->panel_block))
												{
													$modules[$modules_index]["panel"] = $pnlrow->panel_block;
												}
											else
												{
													$modules[$modules_index]["panel"] = "center";
												}
											$modules[$modules_index]["module"] = 'system_empty_block';
										}
								}
						}

					include('includes/core/postload.php');

					//Final initialization
					sm_event('beforetplgenerate');
					$special['pathcount'] = count($special['path']);
					if (!$sm['s']['nosmarty'])
						{
							$smarty->assign_by_ref('userinfo', $userinfo);
							$smarty->assign_by_ref('modules', $modules);
							$smarty->assign_by_ref('refresh_url', $refresh_url);
							$smarty->assign_by_ref('lang', $lang);
							$smarty->assign_by_ref('_settings', $_settings);
							$smarty->assign_by_ref('sm', $sm);
						}
					$special['time']['generation_end'] = microtime();
					$special['time']['generation_end'] = explode(' ', $special['time']['generation_end']);
					$special['time']['generation_end'] = $special['time']['generation_end'][0] + $special['time']['generation_end'][1];
					$special['time']['generation_time'] = round($special['time']['generation_end'] - $special['time']['generation_begin'], 4);
					if (!$sm['s']['nosmarty'])
						$smarty->assign_by_ref('special', $special);

					if (!empty($_sessionvars))
						while (list($key, $val) = each($_sessionvars))
							{
								$_SESSION[$session_prefix.$key] = $val;
							}
					session_write_close();

					//Send headers before output
					if (!headers_sent())
						{
							if (!empty($refresh_url) && $special['dontsendredirectheaders'] != true)
								@header('Location: '.$refresh_url);
							if ((empty($modules[0]['module']) || $modules[0]['module'] == '404') && !empty($special['header_error_code']))
								@header($_servervars['SERVER_PROTOCOL']." ".$special['header_error_code']);
							elseif (empty($modules[0]['module']) || $modules[0]['module'] == '404')
								@header("HTTP/1.0 404 Not Found");
							@header('Content-type: text/html; charset='.$sm['s']['charset']);
						}

					//Output page
					if (!empty($special['main_tpl']))
						if (!$sm['s']['nosmarty'])
							{
								if (!empty($siman_cache) && $sm['cacheit'] && $sm['u']['level']==0)
									{
										$output = $smarty->fetch($special['main_tpl'].'.tpl');
										$fname='files/temp/cache_'.md5($_SERVER['REQUEST_URI']);
										$fh=fopen($fname, 'w');
										fwrite($fh, $output);
										fclose($fh);
										if (intval($sm['cacheittime'])>0)
											touch($fname, time()+intval($sm['cacheittime']));
										print($output);
									}
								else
									$smarty->display($special['main_tpl'].'.tpl');
							}
					sm_event('aftertplgenerate');
				}
		}
	elseif (!$sm['s']['nosmarty'])
		{
			$smarty->template_dir = 'themes/default/';
			$smarty->compile_dir = 'files/temp/';
			$smarty->config_dir = 'themes/default/';
			$smarty->cache_dir = 'files/temp/';
			$smarty->template_dir_default = 'themes/default/';
			$smarty->assign('errorname', 'dberror');
			$smarty->display('error.tpl');
		}

	//print(memory_get_peak_usage(true));

?>