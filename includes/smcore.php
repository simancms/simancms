<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.7
	//#revision 2014-07-22
	//==============================================================================


	function sm_delete_settings($settings_name, $mode = 'default')
		{
			global $tableprefix;
			execsql("DELETE FROM ".$tableprefix."settings WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode)."'");
		}

	function sm_get_settings($settings_name, $mode = 'default')
		{
			global $tableprefix;
			return getsqlfield("SELECT value_settings FROM ".$tableprefix."settings WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode)."' LIMIT 1");
		}

	//Alias for sm_new_settings
	function sm_add_settings($settings_name, $settings_value, $mode = 'default')
		{
			sm_new_settings($settings_name, $settings_value, $mode);
		}

	function sm_new_settings($settings_name, $settings_value, $mode = 'default')
		{
			global $tableprefix;
			execsql("INSERT INTO ".$tableprefix."settings (name_settings, value_settings, mode) VALUES  ('".dbescape($settings_name)."', '".dbescape($settings_value)."', '".dbescape($mode)."')");
		}

	function sm_update_settings($settings_name, $new_value, $mode = 'default')
		{
			global $tableprefix;
			execsql("UPDATE ".$tableprefix."settings SET value_settings = '".dbescape($new_value)."' WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode)."'");
		}

	function sm_register_module($module_name, $module_title, $search_fields = '', $search_doing = '', $search_var = '', $search_table = '', $search_title = '', $search_idfield = '', $search_text = '')
		{
			global $tableprefix, $_settings;
			execsql("INSERT INTO ".$tableprefix."modules (module_name, module_title, search_fields, search_doing, search_var, search_table, search_title, search_idfield, search_text) VALUES ('".dbescape($module_name)."', '".dbescape($module_title)."', '".dbescape($search_fields)."', '".dbescape($search_doing)."', '".dbescape($search_var)."', '".dbescape($search_table)."', '".dbescape($search_title)."', '".dbescape($search_idfield)."', '".dbescape($search_text)."');");
			$_settings['installed_packages'] = addto_nllist($_settings['installed_packages'], $module_name);
			sm_update_settings('installed_packages', $_settings['installed_packages']);
		}

	function sm_unregister_module($module_name)
		{
			global $tableprefix, $_settings;
			execsql("DELETE FROM ".$tableprefix."modules WHERE module_name = '".dbescape($module_name)."'");
			$_settings['installed_packages'] = removefrom_nllist($_settings['installed_packages'], $module_name);
			sm_update_settings('installed_packages', $_settings['installed_packages']);
		}

	function sm_register_autoload($module_name)
		{
			global $_settings;
			$_settings['autoload_modules'] = addto_nllist($_settings['autoload_modules'], $module_name);
			sm_update_settings('autoload_modules', $_settings['autoload_modules']);
		}

	function sm_unregister_autoload($module_name)
		{
			global $_settings;
			$_settings['autoload_modules'] = removefrom_nllist($_settings['autoload_modules'], $module_name);
			sm_update_settings('autoload_modules', $_settings['autoload_modules']);
		}

	function sm_register_postload($module_name)
		{
			global $_settings;
			$_settings['postload_modules'] = addto_nllist($_settings['postload_modules'], $module_name);
			sm_update_settings('postload_modules', $_settings['postload_modules']);
		}

	function sm_unregister_postload($module_name)
		{
			global $_settings;
			$_settings['postload_modules'] = removefrom_nllist($_settings['postload_modules'], $module_name);
			sm_update_settings('postload_modules', $_settings['postload_modules']);
		}

	function sm_add_cssfile($fname, $includeAsIs = 0)
		{
			global $special, $sm;
			if (empty($fname)) return false;
			if ($includeAsIs == 1)
				$special['customcss'][count($special['customcss'])] = $fname;
			elseif (file_exists('themes/'.sm_current_theme().'/'.$fname))
				$special['customcss'][count($special['customcss'])] = 'themes/'.sm_current_theme().'/'.$fname;
			else
				$special['customcss'][count($special['customcss'])] = 'themes/default/'.$fname;
			return $special['customcss'][count($special['customcss'])-1];
		}

	function sm_add_jsfile($fname, $includeAsIs = 0)
		{
			global $special, $sm;
			if (empty($fname)) return false;
			if ($includeAsIs == 1)
				$special['customjs'][count($special['customjs'])] = $fname;
			elseif (file_exists('themes/'.sm_current_theme().'/'.$fname))
				$special['customjs'][count($special['customjs'])] = 'themes/'.sm_current_theme().'/'.$fname;
			else
				$special['customjs'][count($special['customjs'])] = 'themes/default/'.$fname;
			return $special['customjs'][count($special['customjs'])-1];
		}

	function sm_userinfo($id, $srchfield = 'id_user')
		{
			global $tableusersprefix;
			if ($srchfield != 'email' && $srchfield != 'login')
				$srchfield = 'id_user';
			$sql = "SELECT * FROM ".$tableusersprefix."users WHERE `".dbescape($srchfield)."`='".dbescape($id)."'";
			$result = execsql($sql);
			while ($row = database_fetch_assoc($result))
				{
					$userinfo['id'] = $row['id_user'];
					$userinfo['login'] = $row['login'];
					$userinfo['email'] = $row['email'];
					$userinfo['level'] = $row['user_status'];
					$userinfo['groups'] = $row['groups_user'];
					unset($row['password']);
					unset($row['question']);
					unset($row['answer']);
					$userinfo['info'] = $row;
				}
			if (empty($userinfo['id']))
				{
					$userinfo['id'] = '';
					$userinfo['login'] = '';
					$userinfo['email'] = '';
					$userinfo['session'] = '';
					$userinfo['level'] = 0;
					$userinfo['groups'] = '';
					$userinfo['info'] = Array();
				}
			return $userinfo;
		}

	function sm_include_lang($modulename, $langname = '')
		{
			global $lang, $sm;
			if (empty($langname))
				$langname = $sm['s']['lang'];
			if (file_exists("./lang/modules/".$langname."_".$modulename.".php"))
				require("lang/modules/".$langname."_".$modulename.".php");
			elseif (file_exists("./lang/modules/en_".$modulename.".php"))
				require("lang/modules/en_".$modulename.".php");
			elseif (file_exists("./lang/modules/ukr_".$modulename.".php"))
				require("lang/modules/ukr_".$modulename.".php");
			if (!is_array($sm['other']['includedlanguages']) || !in_array(Array('module'=>$modulename, 'language'=>$langname), $sm['other']['includedlanguages']))
				$sm['other']['includedlanguages'][]=Array('module'=>$modulename, 'language'=>$langname);
		}

	function sm_load_tree($tablename, $field_id, $field_root, $load_only_branches_of_this = -1, $extsqlwhere = '', $sortfield = '')
		{
			global $tableprefix;
			$addsql = '';
			if (!empty($extsqlwhere))
				$addsql .= ' WHERE '.$extsqlwhere;
			if ($load_only_branches_of_this>=0)
				{
					if (empty($addsql))
						$addsql .= " WHERE ";
					else
						$addsql .= " AND ";
					$addsql .= " `".dbescape($field_root)."`='".dbescape($load_only_branches_of_this)."'";
				}
			$sql = "SELECT * FROM ".$tableprefix.$tablename."";
			$sql .= $addsql;
			$sql .= " ORDER BY `".dbescape($field_root)."`";
			if (!empty($sortfield))
				$sql .= ', `'.dbescape($sortfield).'`';
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_array($result))
				{
					$ctg[$i] = $row;
					$i++;
				}

			for ($i = 0; $i<count($ctg); $i++)
				{
					$pos[$i] = 0;
				}
			$fistlevelposition = 0;
			$fistlevellastposition = 0;
			for ($i = 0; $i<count($ctg); $i++)
				{
					if ($ctg[$i][$field_root] == 0)
						{
							$maxpos = 0;
							for ($j = 0; $j<count($ctg); $j++)
								{
									if ($maxpos<$pos[$j])
										$maxpos = $pos[$j];
								}
							$pos[$i] = $maxpos+1;
							$fistlevelposition++;
							$ctg[$i]['sub_position'] = $fistlevelposition;
							$fistlevellastposition = $i;
						}
					else
						{
							$rootpos = 0;
							$childpos = -1;
							for ($j = 0; $j<count($ctg); $j++)
								{
									if ($ctg[$j][$field_id] == $ctg[$i][$field_root])
										{
											$rootpos = $pos[$j];
											$ctg[$i]['level'] = $ctg[$j]['level']+1;
											$ctg[$j]['is_main'] = 1;
											$ctg[$j]['count_sub']++;
											$ctg[$j]['have_sub'] = 1;
											$ctg[$i]['sub_position'] = $ctg[$j]['count_sub'];
										}
									if ($ctg[$j][$field_root] == $ctg[$i][$field_root] && $j != $i && $childpos<$pos[$j])
										$childpos = $pos[$j];
								}
							$pos[$i] = ($rootpos>$childpos) ? ($rootpos+1) : ($childpos+1);
							for ($j = 0; $j<count($ctg); $j++)
								{
									if ($pos[$j]>=$pos[$i] && $j != $i)
										$pos[$j]++;
								}
						}
				}
			if (count($ctg)>0)
				{
					$ctg[0]['first'] = 1;
					$menu[$fistlevellastposition]['last'] = 1;
				}
			for ($i = 0; $i<count($ctg); $i++)
				{
					$rctg[$pos[$i]-1] = $ctg[$i];
				}

			return $rctg;
		}

	//Функція для виведення шляху в дереві з таблиці
	function sm_get_path_tree($tablename, $field_id, $field_root, $start_id, $stop_id = 0)
		{
			if ($start_id == $stop_id) return Array();
			$sql = "SELECT * FROM $tablename ORDER BY IF ($field_id=$start_id, 0 ,1), $field_id";
			$r = getsqlarray($sql);
			if (count($r)<=0) return Array();
			$pos[0] = 0;
			$curpos = 0;
			$iteration = 0;
			while ($r[$pos[$curpos]][$field_root] != $stop_id && $iteration<=count($r))
				{
					$u = 0;
					for ($i = 1; $i<count($r); $i++)
						{
							if ($r[$i][$field_id] == $r[$pos[$curpos]][$field_root])
								{
									$curpos++;
									$pos[$curpos] = $i;
									$u = 1;
									break;
								}
						}
					if ($u == 0) return Array(); //broken tree
					$iteration++;
				}
			$res = Array();
			for ($i = count($pos)-1; $i>=0; $i--)
				{
					$res[count($res)] = $r[$pos[$i]];
				}
			return $res;
		}

	function sm_add_title_modifier(&$title)
		{
			global $special;
			$special['titlemodifier'][count($special['titlemodifier'])] =& $title;
		}

	function sm_add_content_modifier(&$content)
		{
			global $special;
			if (is_array($content))
				{
					foreach ($content as &$item)
						{
							sm_add_content_modifier($item);
						}
				}
			else
				$special['contentmodifier'][count($special['contentmodifier'])] =& $content;
		}

	function sm_getnicename($str)
		{
			$replacers['а'] = 'a';
			$replacers['б'] = 'b';
			$replacers['в'] = 'v';
			$replacers['г'] = 'g';
			$replacers['д'] = 'd';
			$replacers['е'] = 'e';
			$replacers['є'] = 'ye';
			$replacers['ж'] = 'zh';
			$replacers['з'] = 'z';
			$replacers['и'] = 'y';
			$replacers['і'] = 'i';
			$replacers['ї'] = 'yi';
			$replacers['й'] = 'y';
			$replacers['к'] = 'k';
			$replacers['л'] = 'l';
			$replacers['м'] = 'm';
			$replacers['н'] = 'n';
			$replacers['о'] = 'o';
			$replacers['п'] = 'p';
			$replacers['р'] = 'r';
			$replacers['с'] = 's';
			$replacers['т'] = 't';
			$replacers['у'] = 'u';
			$replacers['ф'] = 'f';
			$replacers['х'] = 'h';
			$replacers['ц'] = 'c';
			$replacers['ч'] = 'ch';
			$replacers['ш'] = 'sh';
			$replacers['щ'] = 'shch';
			$replacers['ю'] = 'yu';
			$replacers['я'] = 'ya';
			$replacers['ґ'] = 'g';
			$replacers['А'] = 'a';
			$replacers['Б'] = 'b';
			$replacers['В'] = 'v';
			$replacers['Г'] = 'g';
			$replacers['Д'] = 'd';
			$replacers['Е'] = 'e';
			$replacers['Є'] = 'ye';
			$replacers['Ж'] = 'zh';
			$replacers['З'] = 'z';
			$replacers['И'] = 'y';
			$replacers['І'] = 'i';
			$replacers['Ї'] = 'yi';
			$replacers['Й'] = 'y';
			$replacers['К'] = 'k';
			$replacers['Л'] = 'l';
			$replacers['М'] = 'm';
			$replacers['Н'] = 'n';
			$replacers['О'] = 'o';
			$replacers['П'] = 'p';
			$replacers['Р'] = 'r';
			$replacers['С'] = 's';
			$replacers['Т'] = 't';
			$replacers['У'] = 'u';
			$replacers['Ф'] = 'f';
			$replacers['Х'] = 'h';
			$replacers['Ц'] = 'c';
			$replacers['Ч'] = 'ch';
			$replacers['Ш'] = 'sh';
			$replacers['Щ'] = 'shch';
			$replacers['Ю'] = 'yu';
			$replacers['Я'] = 'ya';
			$replacers['Ґ'] = 'g';
			$str = strtolower($str);
			$nice = '';
			for ($i = 0; $i<strlen($str); $i++)
				{
					if ($str[$i]>='a' && $str[$i]<='z' || $str[$i]>='0' && $str[$i]<='9' || $str[$i] == '.' || $str[$i] == '_' || $str[$i] == '-')
						$nice .= $str[$i];
					elseif (!empty($replacers[$str[$i]]))
						$nice .= $replacers[$str[$i]];
					else
						$nice .= '-';
				}
			return $nice;
		}

	function sm_event($eventname, $paramsarray = Array())
		{
			global $sm;
			$listeners = nllistToArray($sm['_s']['autoload_modules']);
			for ($i = 0; $i<count($listeners); $i++)
				{
					$eventfn = 'event_'.$eventname.'_'.$listeners[$i];
					if (function_exists($eventfn))
						{
							if (!is_array($paramsarray))
								$paramsarray = array($paramsarray);
							call_user_func_array($eventfn, $paramsarray);
						}
				}
			for ($i = 0; $i<count($sm['eventlisteners'][$eventname]); $i++)
				{
					$eventfn = $sm['eventlisteners'][$eventname][$i];
					if (function_exists($eventfn))
						{
							if (!is_array($paramsarray))
								$paramsarray = array($paramsarray);
							call_user_func_array($eventfn, $paramsarray);
						}
				}
		}

	function sm_event_handler($eventname, $functionname)
		{
			global $sm;
			if (empty($sm['eventlisteners']) || !in_array($functionname, $sm['eventlisteners']))
				$sm['eventlisteners'][$eventname][] = $functionname;
		}

	function sm_get_attachments($fromModule, $fromId)
		{
			global $tableprefix, $userinfo, $_settings;
			$sql = "SELECT * FROM ".$tableprefix."downloads WHERE userlevel_download<=".intval($userinfo['id'])." AND attachment_from='".dbescape($fromModule)."' AND attachment_id=".intval($fromId);
			$result = execsql($sql);
			$i = 0;
			$r = Array();
			while ($row = database_fetch_object($result))
				{
					$r[$i]['id'] = $row->id_download;
					$r[$i]['filename'] = sm_getnicename($row->file_download);
					$r[$i]['leveldownload'] = $row->userlevel_download;
					$r[$i]['attachment_from'] = $row->attachment_from;
					$r[$i]['attachment_id'] = $row->attachment_id;
					$r[$i]['type'] = $row->attachment_type;
					$r[$i]['is_image'] = ($row->attachment_type == 'image/jpeg' || $row->attachment_type == 'image/jpg' || $row->attachment_type == 'image/gif' || $row->attachment_type == 'image/png');
					$r[$i]['deleteurl'] = 'index.php?m=download&d=deleteattachment&id='.$r[$i]['id'];
					$r[$i]['realfilename'] = 'files/download/attachment'.$r[$i]['id'];
					if (file_exists($r[$i]['realfilename']))
						$r[$i]['filesize'] = filesize($r[$i]['realfilename']);
					else
						$r[$i]['filesize'] = 0;
					if ($r[$i]['filesize']>1048576)
						$r[$i]['filesize'] = round($r[$i]['filesize']/1048576, 2).' MB';
					elseif ($r[$i]['filesize']>1024)
						$r[$i]['filesize'] = round($r[$i]['filesize']/1024, 2).' KB';
					else
						$r[$i]['filesize'] = $r[$i]['filesize'].' B';
					$r[$i]['downloadurl'] = 'downloads/attachments/'.$r[$i]['id'].'-'.$r[$i]['filename'];
					$r[$i]['viewurl'] = 'downloads/viewattachment/'.$r[$i]['id'].'-'.$r[$i]['filename'];
					$i++;
				}
			return $r;
		}

	function sm_upload_attachment($fromModule, $fromId, &$filesPointer, $userlevel = 0)
		{
			global $tableprefix;
			if ($filesPointer['error'] <> UPLOAD_ERR_OK)
				return false;
			$fs = $filesPointer['tmp_name'];
			if (!empty($fs))
				{
					$q=new TQuery($tableprefix."downloads");
					$q->Add('file_download', dbescape(sm_getnicename($filesPointer['name'])));
					$q->Add('userlevel_download', intval($userlevel));
					$q->Add('attachment_from', dbescape($fromModule));
					$q->Add('attachment_id', intval($fromId));
					$q->Add('attachment_type', dbescape($filesPointer['type']));
					$newid = $q->Insert();
					if (empty($newid))
						return false;
					$fd = 'files/download/attachment'.$newid;
					if (file_exists($fd))
						unlink($fd);
					$result = move_uploaded_file($fs, $fd);
					if ($result)
						sm_event('successuploadattachment', array($newid, $fd));
					return $result;
				}
			else
				return false;
		}

	function sm_delete_attachments($fromModule, $fromId)
		{
			global $tableprefix;
			$r = sm_get_attachments($fromModule, $fromId);
			for ($i = 0; $i<count($r); $i++)
				{
					if (file_exists($r[$i]['realfilename']))
						unlink($r[$i]['realfilename']);
					deletesql($tableprefix.'downloads', 'id_download', $r[$i]['id']);
				}
		}

	function sm_delete_attachment($id)
		{
			global $tableprefix;
			if (file_exists('files/download/attachment'.intval($id)))
				unlink('files/download/attachment'.intval($id));
			deletesql($tableprefix.'downloads', 'id_download', intval($id));
		}

	function sm_upload_file($upload_var = 'userfile', $upload_path = '', $secondary_index=NULL)
		{
			global $_uplfilevars;
			if ($secondary_index===NULL)
				$fs = $_uplfilevars[$upload_var]['tmp_name'];
			else
				$fs = $_uplfilevars[$upload_var]['tmp_name'][$secondary_index];
			if (empty($upload_path))
				$upload_path = 'files/temp/'.md5(microtime(true));
			if (!empty($fs))
				{
					$fd = $upload_path;
					if (file_exists($fd))
						unlink($fd);
					$res = move_uploaded_file($fs, $fd);
					if ($res !== false)
						{
							sm_event('afteruploadedfile', array($fd));
							return $upload_path;
						}
					else
						return false;
				}
			else
				return false;
		}

	function sm_detect_device($useragent = '')
		{
			global $_servervars, $_settings, $special;
			if (!empty($_settings['resource_url_mobile']) && strpos($special['page']['url'], $_settings['resource_url_mobile']) !== false)
				{
					$result['is_desktop'] = false;
					$result['is_mobile'] = true;
					$result['is_tablet'] = false;
					$result['devicename'] = 'unknown';
					return $result;
				}
			if (!empty($_settings['resource_url_tablet']) && strpos($special['page']['url'], $_settings['resource_url_tablet']) !== false)
				{
					$result['is_desktop'] = false;
					$result['is_mobile'] = false;
					$result['is_tablet'] = true;
					$result['devicename'] = 'unknown';
					return $result;
				}
			if (empty($useragent))
				{
					$useragent = $_servervars['HTTP_USER_AGENT'];
					$wapprofile = $_servervars['HTTP_X_WAP_PROFILE'];
					$httpprofile = $_servervars['HTTP_PROFILE'];
					$httpaccept = $_servervars['HTTP_ACCEPT'];
				}
			else
				{
					$wapprofile = '';
					$httpprofile = '';
					$httpaccept = '';
				}
			$result['is_desktop'] = false;
			$result['is_mobile'] = false;
			$result['is_tablet'] = false;
			$result['devicename'] = 'unknown';
			$mobileDevices = array(
				"android" => "android",
				"blackberry" => "blackberry",
				"iphone" => "(iphone|ipod)",
				"opera" => "opera mini",
				"palm" => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
				"windows" => "windows ce; (iemobile|ppc|smartphone)",
				"generic" => "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)"
			);
			$tabletDevices = array(
				"ipad" => "ipad"
			);
			if (isset($wapprofile) || isset($httpprofile))
				{
					$result['is_mobile'] = true;
				}
			elseif (strpos($useragent, 'text/vnd.wap.wml')>0 || strpos($httpaccept, 'application/vnd.wap.xhtml+xml')>0)
				{
					$result['is_mobile'] = true;
				}
			foreach ($mobileDevices as $device => $regexp)
				{
					if (preg_match("/".$regexp."/i", $useragent))
						{
							$result['is_mobile'] = true;
							$result['devicename'] = $device;
							break;
						}
				}
			foreach ($tabletDevices as $device => $regexp)
				{
					if (preg_match("/".$regexp."/i", $useragent))
						{
							$result['is_mobile'] = false;
							$result['is_tablet'] = true;
							$result['devicename'] = $device;
							break;
						}
				}
			if ($result['is_mobile'] === false && $result['is_tablet'] === false)
				$result['is_desktop'] = true;
			return $result;
		}

	function sm_redirect($url, $message = '', $dontsendredirectheaders = false)
		{
			global $modules, $modules_index, $refresh_url, $lang, $special;
			if (empty($modules[$modules_index]['title']))
				$modules[$modules_index]['title'] = $lang['common']['redirect'];
			$modules[$modules_index]['module'] = 'refresh';
			if ($message === true)
				{
					$message = '';
					$dontsendredirectheaders = true;
				}
			$modules[$modules_index]['message'] = $message;
			$special['dontsendredirectheaders'] = $dontsendredirectheaders;
			$refresh_url = $url;
		}

	function sm_redirect_now($url, $header_http_code='')
		{
			global $sm;
			if (is_numeric($header_http_code))
				{
					if (intval($header_http_code)==301)
						$header_http_code='301 Moved Permanently';
				}
			@header('Location: '.$url);
			if (!empty($header_http_code))
				@header($sm['server']['SERVER_PROTOCOL']." ".$header_http_code);
			exit;
		}

	function sm_page_viewid($id, $rewriteanyway = false)
		{
			global $sm, $modules_index;
			if ($modules_index == 0 || $rewriteanyway)
				$sm['s']['page']['viewid'] = $id;
		}

	function sm_extcore()
		{
			include_once('includes/smcoreext.php');
		}

	function sm_set_userfield($userid, $fieldname, $value)
		{
			global $tableusersprefix, $userinfo, $_sessionvars;
			$q = new TQuery($tableusersprefix."users");
			$q->Add(dbescape($fieldname), dbescape($value));
			$q->Update('id_user', intval($userid));
			if (!in_array($fieldname, Array('info', 'groups', 'id', 'login', 'level')) && $userid == $userinfo['id'])
				{
					if (strcmp($fieldname, 'email') == 0)
						$_sessionvars['userinfo_email'] = $value;
					$userinfo['info'][$fieldname] = $value;
					$_sessionvars['userinfo_allinfo'] = serialize($userinfo['info']);
				}
		}

	function sm_login($userid, $usrinfo = Array())
		{
			global $tableusersprefix, $_sessionvars;
			if (empty($usrinfo))
				$usrinfo = getsql("SELECT * FROM ".$tableusersprefix."users WHERE id_user=".intval($userid)." AND user_status>0");
			if (!empty($usrinfo['id_user']))
				{
					if (empty($usrinfo['random_code']))
						{
							$usrinfo['random_code'] = md5(time().rand());
							$q = new TQuery($tableusersprefix.'users');
							$q->Add('random_code', dbescape($usrinfo['random_code']));
							$q->Update('id_user', intval($usrinfo['id_user']));
							unset($q);
						}
					$_sessionvars['userinfo_id'] = $usrinfo['id_user'];
					$_sessionvars['userinfo_login'] = $usrinfo['login'];
					$_sessionvars['userinfo_email'] = $usrinfo['email'];
					$_sessionvars['userinfo_level'] = $usrinfo['user_status'];
					$_sessionvars['userinfo_groups'] = $usrinfo['groups_user'];
					unset($usrinfo['password']);
					unset($usrinfo['question']);
					unset($usrinfo['answer']);
					$_sessionvars['userinfo_allinfo'] = serialize($usrinfo);
					execsql("UPDATE ".$tableusersprefix."users SET id_session='".session_id()."', last_login='".time()."' WHERE id_user='".intval($usrinfo['id_user'])."'");
					return true;
				}
			else
				return false;
		}

	/*
	* Return preloaded settings value for key $name without checking DB.
	* This is lightweight replacement of sm_get_settings function.
	*/
	function sm_settings($name)
		{
			global $sm;
			return $sm['_s'][$name];
		}

	function sm_change_theme($themename)
		{
			global $smarty, $sm;
			if (!$sm['s']['nosmarty'])
				{
					$smarty->template_dir = 'themes/'.$themename.'/';
					$smarty->compile_dir = 'files/themes/'.$themename.'/';
					$smarty->config_dir = 'themes/'.$themename.'/';
					$smarty->cache_dir = 'files/temp/';
					$smarty->template_dir_default = 'themes/default/';
				}
			$sm['s']['theme'] = $themename;
			if ($sm['_s']['sm_changetheme_default_theme'] == 1)
				$sm['_s']['default_theme'] = $themename;
			if (file_exists('themes/'.$themename.'/themeinit.php'))
				include('themes/'.$themename.'/themeinit.php');
		}

	//Return true  if current action is in set $action1, $action2... or false otherwice
	function sm_action()
		{
			global $m;
			for ($i = 0; $i<func_num_args(); $i++)
				{
					$param = func_get_arg($i);
					if (strcmp($m["mode"], $param) == 0)
						return true;
				}
			return false;
		}

	//Return true  if not empty $_POST and current action is in set $action1, $action2... or false otherwice
	function sm_actionpost()
		{
			global $m, $sm;
			if (count($sm['p']) == 0)
				return false;
			for ($i = 0; $i<func_num_args(); $i++)
				{
					$param = func_get_arg($i);
					if (strcmp($m["mode"], $param) == 0)
						return true;
				}
			return false;
		}

	//Change or format the parameters of the $url
	//sm_url($url, $get_param_name, $get_param_value)
	//sm_url($url, $param_replacers_array)
	// If $url is empty - using index.php
	function sm_url($url, $param_name = NULL, $param_value = NULL)
		{
			if ($param_name === NULL && $param_value === NULL)
				return $url;
			if (empty($url))
				$url = 'index.php';
			if (is_array($param_name) && $param_value === NULL)
				{
					foreach ($param_name as $key => $val)
						{
							$url = sm_url($url, $key, $val);
						}
					return $url;
				}
			$param_value = urlencode($param_value);
			if (strpos($url, '?'.$param_name.'=') !== false || strpos($url, '&'.$param_name.'=') !== false)
				{
					if (strcmp($param_value, '') != 0)
						{
							$param_value = str_replace('$', '\\$', $param_value);
							$url = preg_replace('|(.*)([&\\?])'.$param_name.'=(.*?)&(.*)|is', '$1$2'.$param_name.'='.$param_value.'&$4', $url);
							$url = preg_replace('|(.*)([&\\?])'.$param_name.'=([^&#]*)$|is', '$1$2'.$param_name.'='.$param_value, $url);
						}
					else
						{
							$url = preg_replace('|(.*)([&\\?])'.$param_name.'=(.*?)&(.*)|is', '$1$2$4', $url);
							$url = preg_replace('|(.*)([&\\?])'.$param_name.'=([^&#]*)$|is', '$1', $url);
						}
				}
			elseif (strcmp($param_value, '') != 0)
				{
					if (strpos($url, '?') !== false)
						$url .= '&'.$param_name.'='.$param_value;
					else
						$url .= '?'.$param_name.'='.$param_value;
				}
			return $url;
		}

	//Change or format the parameters of the current $url
	//sm_this_url($get_param_name, $get_param_value)
	//sm_this_url($param_replacers_array)
	//sm_this_url() - current url
	function sm_this_url($param_name = NULL, $param_value = NULL)
		{
			global $sm;
			return sm_url($sm['s']['page']['url'], $param_name, $param_value);
		}

	function sm_set_action($action)
		{
			global $m;
			$m['mode'] = $action;
		}

	function sm_default_action($action)
		{
			global $m;
			if (empty($m['mode']))
				$m['mode'] = $action;
		}

	function sm_title($title)
		{
			global $sm;
			$sm['m']['title'] = $title;
			if ($sm['index']==0)
				for ($i = 0; $i < count($sm['s']['path']); $i++)
					{
						if ($sm['s']['path'][$i]['tag']=='currentpage')
							$sm['s']['path'][$i]['title']=$title;
					}
			sm_add_title_modifier($sm['m']['title']);
		}

	function sm_meta_title($title, $hide_site_title = true)
		{
			global $special, $_settings;
			$special['dont_take_a_title'] = 1;
			$special['pagetitle'] = $title;
			if ($hide_site_title)
				$_settings['meta_resource_title_position'] = 0;
		}

	function sm_meta_keywords($keywodrs, $append = false)
		{
			global $special;
			if ($append)
				$special['meta']['keywords'] .= $keywodrs;
			else
				$special['meta']['keywords'] = $keywodrs;
		}

	function sm_meta_description($description, $append = false)
		{
			global $special;
			if ($append)
				$special['meta']['description'] .= $description;
			else
				$special['meta']['description'] = $description;
		}

	function sm_meta_tag($name, $content, $property='')
		{
			sm_html_headend('<meta'.(strlen($property)==0?'':' property="'.htmlescape($property).'"').''.(strlen($name)==0?'':' name="'.htmlescape($name).'"').' content="'.htmlescape($content).'"/>');
		}

	function sm_homepage()
		{
			global $special, $_settings;
			return $special['page']['parsed_url']['scheme'].'://'.$_settings['resource_url'];
		}

	function sm_use($libname)
		{
			global $sm;
			if (file_exists('includes/'.$libname.'.php'))
				include_once('includes/'.$libname.'.php');
		}

	function sm_setfocus($dom_element, $noservicesymbol_as_id=true)
		{
			global $sm;
			if (!$noservicesymbol_as_id)
				$sm['s']['autofocus'] = $dom_element;
			else
				{
					if (!in_array(substr($dom_element, 0, 1), Array('.', '#')))
						$sm['s']['autofocus'] = '#'.$dom_element;
					else
						$sm['s']['autofocus'] = $dom_element;
				}
		}

	function sm_thumburl($filename, $maxwidth = 0, $maxheight = 0, $format = '', $quality = '', $path = 'files/img/')
		{
			$info = pathinfo($filename);
			$url = 'ext/showimage.php?img='.$info['filename'];
			if ($info['extension'] == 'png')
				$url .= '&png=1';
			if ($info['extension'] == 'gif')
				$url .= '&gif=1';
			if (strpos($path, 'files/img/') == 0 && strlen($path)>10)
				$url .= '&ext='.substr($path, 10);
			if (!empty($quality))
				$url .= '&quality='.$quality;
			if (!empty($format))
				$url .= '&format='.$format;
			if (!empty($maxwidth))
				$url .= '&width='.$maxwidth;
			if (!empty($maxheight))
				$url .= '&height='.$maxheight;
			return $url;
		}

	function sm_isuseringroup($userid_or_userinfo, $groupid)
		{
			if (!is_array($userid_or_userinfo))
				$userid_or_userinfo = sm_userinfo(intval($userid_or_userinfo));
			$groups = get_array_groups($userid_or_userinfo['groups']);
			return in_array($groupid, $groups);
		}

	function sm_fs_update($title, $system_url, $register_url = '', $default_extension = '.html')
		{
			global $sm;
			if (empty($register_url))
				$register_url = sm_getnicename($title).$default_extension;
			$q = new TQuery($sm['t'].'filesystem');
			$q->Add('url_fs', dbescape($system_url));
			$info = $q->Get();
			$q->Add('comment_fs', dbescape($title));
			$q->Add('filename_fs', dbescape($register_url));
			if (empty($info['id_fs']))
				$q->Insert();
			else
				$q->Update('id_fs', intval($info['id_fs']));
		}

	function sm_fs_delete($system_url)
		{
			global $sm;
			$q = new TQuery($sm['t'].'filesystem');
			$q->Add('url_fs', dbescape($system_url));
			$q->Remove();
		}

	function sm_fs_url($system_url, $return_false_on_nonexists=false)
		{
			global $sm;
			$q = new TQuery($sm['t'].'filesystem');
			$q->Add('url_fs', dbescape($system_url));
			$info = $q->Get();
			if (empty($info['filename_fs']))
				{
					if ($return_false_on_nonexists)
						return false;
					else
						return $system_url;
				}
			else
				return $info['filename_fs'];
		}
	
	function sm_html_headstart($html)
		{
			global $sm;
			$sm['s']['document']['headstart'].=$html;
		}

	function sm_html_headend($html)
		{
			global $sm;
			$sm['s']['document']['headend'].=$html;
		}

	function sm_html_bodystart($html)
		{
			global $sm;
			$sm['s']['document']['bodystart'].=$html;
		}

	function sm_html_bodyend($html)
		{
			global $sm;
			$sm['s']['document']['bodyend'].=$html;
		}

	function sm_html_beforepanel($html, $panelindex)
		{
			global $sm;
			$sm['s']['document']['panel'][$panelindex]['beforepanel'].=$html;
		}

	function sm_html_afterpanel($html, $panelindex)
		{
			global $sm;
			$sm['s']['document']['panel'][$panelindex]['afterpanel'].=$html;
		}

	function sm_html_beforeblock($html, $blockindex)
		{
			global $sm;
			$sm['s']['document']['block'][$blockindex]['beforeblock'].=$html;
		}

	function sm_html_afterblock($html, $blockindex)
		{
			global $sm;
			$sm['s']['document']['block'][$blockindex]['afterblock'].=$html;
		}

	//Dummy function for further implementation
	function sm_notify($message, $title='')
		{
			global $sm;
			$sm['session']['notifications'][]=Array('message'=>$message, 'title'=>$title, 'time'=>time());
		}
	
	function sm_change_language($langname)
		{
			global $sm, $lang;
			require("lang/".$langname.".php");
			if (file_exists("./lang/user/".$langname.".php"))
				require("lang/user/".$langname.".php");
			$sm['s']['lang']=$langname;
			if (is_array($sm['other']['includedlanguages']))
				for ($i = 0; $i<count($sm['other']['includedlanguages']); $i++)
					{
						sm_include_lang($sm['other']['includedlanguages'][$i]['module'], $sm['other']['includedlanguages'][$i]['language']);
					}
			$sm['s']['charset']=$lang['charset'];
		}
	
	function sm_current_theme()
		{
			global $sm;
			return $sm['s']['theme'];
		}

	function sm_set_metadata($object_name, $object_id, $key_name, $val)
		{
			global $sm;
			$q=new TQuery($sm['tu'].'metadata');
			$q->Add('object_name', dbescape($object_name));
			$q->Add('object_id', dbescape($object_id));
			$q->Add('key_name', dbescape($key_name));
			$info=$q->Get();
			if ($val===NULL)
				{
					$q->Remove();
					unset($sm['cache']['metadata'][$object_name][$object_id][$key_name]);
				}
			else
				{
					$q->Add('val', dbescape($val));
					if (empty($info['id']))
						{
							$q->Insert();
						}
					else
						{
							$q->Update('id', intval($info['id']));
						}
					$sm['cache']['metadata'][$object_name][$object_id][$key_name]=$val;
				}
		}

	function sm_metadata($object_name, $object_id, $key_name, $dont_use_cache=false)
		{
			global $sm;
			if (isset($sm['cache']['metadata'][$object_name][$object_id][$key_name]) && !$dont_use_cache)
				return $sm['cache']['metadata'][$object_name][$object_id][$key_name];
			$q=new TQuery($sm['tu'].'metadata');
			$q->Add('object_name', dbescape($object_name));
			$q->Add('object_id', dbescape($object_id));
			$q->Add('key_name', dbescape($key_name));
			$sm['cache']['metadata'][$object_name][$object_id][$key_name]=$q->GetField('val');
			return $sm['cache']['metadata'][$object_name][$object_id][$key_name];
		}

	function sm_load_metadata($object_name, $object_id)
		{
			global $sm;
			$q=new TQuery($sm['tu'].'metadata');
			$q->Add('object_name', dbescape($object_name));
			$q->Add('object_id', dbescape($object_id));
			$q->Open();
			while ($row=$q->Fetch())
				$sm['cache']['metadata'][$object_name][$object_id][$row['key_name']]=$row['val'];
			return $sm['cache']['metadata'][$object_name][$object_id];
		}
	function sm_relative_url($url=NULL)
		{
			if ($url==NULL)
				$url=sm_this_url();
			$parsed=@parse_url($url);
			$parsed_src=@parse_url('http://'.sm_settings('resource_url'));
			if (empty($parsed['path']))
				$parsed['path']='/';
			if (empty($parsed_src['path']))
				$parsed_src['path']='/';
			if (strpos($parsed['path'], $parsed_src['path'])===false)
				return false;
			if (strpos($parsed['path'], $parsed_src['path'])!=0)
				return false;
			$r=substr($parsed['path'], strlen($parsed_src['path']));
			if (!empty($parsed['query']))
				$r.='?'.$parsed['query'];
			return $r;
		}
	
	function sm_use_template($tpl_filename)
		{
			$special['main_tpl'] = $tpl_filename;
		}

?>