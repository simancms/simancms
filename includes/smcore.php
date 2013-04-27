<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.4
	//#revision 2013-04-16
	//==============================================================================


	function sm_delete_settings($settings_name, $mode_not_used = 'default')
		{
			global $nameDB, $lnkDB, $tableprefix;
			$sql = "DELETE FROM ".$tableprefix."settings WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode_not_used)."'";
			$result = execsql($sql);
		}

	function sm_get_settings($settings_name, $mode_not_used = 'default')
		{
			global $nameDB, $lnkDB, $tableprefix;
			$sql = "SELECT value_settings FROM ".$tableprefix."settings WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode_not_used)."' LIMIT 1";
			$result = execsql($sql);
			$r = database_fetch_row($result);
			return $r[0];
		}

	//Pseudo for sm_new_settings
	function sm_add_settings($settings_name, $settings_value, $mode_not_used = 'default')
		{
			sm_new_settings($settings_name, $settings_value, $mode_not_used);
		}

	function sm_new_settings($settings_name, $settings_value, $mode_not_used = 'default')
		{
			global $nameDB, $lnkDB, $tableprefix;
			$sql = "INSERT INTO ".$tableprefix."settings (name_settings, value_settings, mode) VALUES  ('".dbescape($settings_name)."', '".dbescape($settings_value)."', '".dbescape($mode_not_used)."')";
			$result = execsql($sql);
		}

	function sm_update_settings($settings_name, $new_value, $mode_not_used = 'default')
		{
			global $nameDB, $lnkDB, $tableprefix;
			$sql = "UPDATE ".$tableprefix."settings SET value_settings = '".dbescape($new_value)."' WHERE name_settings = '".dbescape($settings_name)."' AND mode='".dbescape($mode_not_used)."'";
			$result = execsql($sql);
		}

	function sm_register_module($module_name, $module_title, $search_fields = '', $search_doing = '', $search_var = '', $search_table = '', $search_title = '', $search_idfield = '', $search_text = '')
		{
			global $nameDB, $lnkDB, $tableprefix, $_settings;
			$sql = "INSERT INTO ".$tableprefix."modules (module_name, module_title, search_fields, search_doing, search_var, search_table, search_title, search_idfield, search_text) VALUES ('".dbescape($module_name)."', '".dbescape($module_title)."', '".dbescape($search_fields)."', '".dbescape($search_doing)."', '".dbescape($search_var)."', '".dbescape($search_table)."', '".dbescape($search_title)."', '".dbescape($search_idfield)."', '".dbescape($search_text)."');";
			$result = execsql($sql);
			$_settings['installed_packages'] = addto_nllist($_settings['installed_packages'], $module_name);
			sm_update_settings('installed_packages', $_settings['installed_packages']);
		}

	function sm_unregister_module($module_name)
		{
			global $nameDB, $lnkDB, $tableprefix, $_settings;
			$sql = "DELETE FROM ".$tableprefix."modules WHERE module_name = '$module_name'";
			$result = execsql($sql);
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
			global $_settings, $special, $sm;
			if (empty($fname)) return false;
			if ($includeAsIs == 1)
				$special['customcss'][count($special['customcss'])] = $fname;
			elseif (file_exists('themes/'.$sm['s']['theme'].'/'.$fname))
				$special['customcss'][count($special['customcss'])] = 'themes/'.$sm['s']['theme'].'/'.$fname;
			else
				$special['customcss'][count($special['customcss'])] = 'themes/default/'.$fname;
			return $special['customcss'][count($special['customcss']) - 1];
		}

	function sm_add_jsfile($fname, $includeAsIs = 0)
		{
			global $_settings, $special, $sm;
			if (empty($fname)) return false;
			if ($includeAsIs == 1)
				$special['customjs'][count($special['customjs'])] = $fname;
			elseif (file_exists('themes/'.$sm['s']['theme'].'/'.$fname))
				$special['customjs'][count($special['customjs'])] = 'themes/'.$sm['s']['theme'].'/'.$fname;
			else
				$special['customjs'][count($special['customjs'])] = 'themes/default/'.$fname;
			return $special['customjs'][count($special['customjs']) - 1];
		}

	function sm_userinfo($id, $srchfield = 'id_user')
		{
			global $nameDB, $lnkDB, $tableusersprefix;
			if ($srchfield != 'email' && $srchfield != 'login')
				$srchfield = 'id_user';
			$sql = "SELECT * FROM ".$tableusersprefix."users WHERE $srchfield='".dbescape($id)."'";
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
			global $lang, $_settings;
			if (empty($langname))
				$langname = $_settings['default_language'];
			if (file_exists("./lang/modules/".$langname."_".$modulename.".php"))
				require("lang/modules/".$langname."_".$modulename.".php");
			elseif (file_exists("./lang/modules/en_".$modulename.".php"))
				require("lang/modules/en_".$modulename.".php");
			elseif (file_exists("./lang/modules/ukr_".$modulename.".php"))
				require("lang/modules/ukr_".$modulename.".php");
		}

	function sm_load_tree($tablename, $field_id, $field_root, $load_only_branches_of_this = -1, $extsqlwhere = '', $sortfield = '')
		{
			global $nameDB, $lnkDB, $tableprefix, $_settings;
			if (!empty($extsqlwhere))
				$addsql .= ' WHERE '.$extsqlwhere;
			if ($load_only_branches_of_this >= 0)
				{
					if (empty($addsql))
						$addsql .= " WHERE ";
					else
						$addsql .= " AND ";
					$addsql = " AND $field_root='$load_only_branches_of_this'";
				}
			$sql = "SELECT * FROM ".$tableprefix.$tablename;
			$sql .= $addsql;
			$sql .= " ORDER BY $field_root";
			if (!empty($sortfield))
				$sql .= ', '.$sortfield;
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_array($result))
				{
					$ctg[$i] = $row;
					$i++;
				}

			for ($i = 0; $i < count($ctg); $i++)
				{
					$pos[$i] = 0;
				}
			$fistlevelposition = 0;
			$fistlevellastposition = 0;
			for ($i = 0; $i < count($ctg); $i++)
				{
					if ($ctg[$i][$field_root] == 0)
						{
							$maxpos = 0;
							for ($j = 0; $j < count($ctg); $j++)
								{
									if ($maxpos < $pos[$j])
										$maxpos = $pos[$j];
								}
							$pos[$i] = $maxpos + 1;
							$fistlevelposition++;
							$ctg[$i]['sub_position'] = $fistlevelposition;
							$fistlevellastposition = $i;
						}
					else
						{
							$rootpos = 0;
							$childpos = -1;
							for ($j = 0; $j < count($ctg); $j++)
								{
									if ($ctg[$j][$field_id] == $ctg[$i][$field_root])
										{
											$rootpos = $pos[$j];
											$ctg[$i]['level'] = $ctg[$j]['level'] + 1;
											$ctg[$j]['is_main'] = 1;
											$ctg[$j]['count_sub']++;
											$ctg[$j]['have_sub'] = 1;
											$ctg[$i]['sub_position'] = $ctg[$j]['count_sub'];
										}
									if ($ctg[$j][$field_root] == $ctg[$i][$field_root] && $j != $i && $childpos < $pos[$j])
										$childpos = $pos[$j];
								}
							$pos[$i] = ($rootpos > $childpos) ? ($rootpos + 1) : ($childpos + 1);
							for ($j = 0; $j < count($ctg); $j++)
								{
									if ($pos[$j] >= $pos[$i] && $j != $i)
										$pos[$j]++;
								}
						}
				}
			if (count($ctg) > 0)
				{
					$ctg[0]['first'] = 1;
					$menu[$fistlevellastposition]['last'] = 1;
				}
			for ($i = 0; $i < count($ctg); $i++)
				{
					$rctg[$pos[$i] - 1] = $ctg[$i];
				}

			return $rctg;
		}

	//������� ��� ��������� ����� � ����� � �������
	function sm_get_path_tree($tablename, $field_id, $field_root, $start_id, $stop_id = 0)
		{
			if ($start_id == $stop_id) return Array();
			$sql = "SELECT * FROM $tablename ORDER BY IF ($field_id=$start_id, 0 ,1), $field_id";
			$r = getsqlarray($sql);
			if (count($r) <= 0) return Array();
			$pos[0] = 0;
			$curpos = 0;
			$iteration = 0;
			while ($r[$pos[$curpos]][$field_root] != $stop_id && $iteration <= count($r))
				{
					$u = 0;
					for ($i = 1; $i < count($r); $i++)
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
			for ($i = count($pos) - 1; $i >= 0; $i--)
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
			$special['contentmodifier'][count($special['contentmodifier'])] =& $content;
		}

	function sm_getnicename($str)
		{
			$replacers['�'] = 'a';
			$replacers['�'] = 'b';
			$replacers['�'] = 'v';
			$replacers['�'] = 'g';
			$replacers['�'] = 'd';
			$replacers['�'] = 'e';
			$replacers['�'] = 'ye';
			$replacers['�'] = 'zh';
			$replacers['�'] = 'z';
			$replacers['�'] = 'y';
			$replacers['�'] = 'i';
			$replacers['�'] = 'yi';
			$replacers['�'] = 'y';
			$replacers['�'] = 'k';
			$replacers['�'] = 'l';
			$replacers['�'] = 'm';
			$replacers['�'] = 'n';
			$replacers['�'] = 'o';
			$replacers['�'] = 'p';
			$replacers['�'] = 'r';
			$replacers['�'] = 's';
			$replacers['�'] = 't';
			$replacers['�'] = 'u';
			$replacers['�'] = 'f';
			$replacers['�'] = 'h';
			$replacers['�'] = 'c';
			$replacers['�'] = 'ch';
			$replacers['�'] = 'sh';
			$replacers['�'] = 'shch';
			$replacers['�'] = 'yu';
			$replacers['�'] = 'ya';
			$replacers['�'] = 'g';
			$replacers['�']='a';
			$replacers['�']='b';
			$replacers['�']='v';
			$replacers['�']='g';
			$replacers['�']='d';
			$replacers['�']='e';
			$replacers['�']='ye';
			$replacers['�']='zh';
			$replacers['�']='z';
			$replacers['�']='y';
			$replacers['�']='i';
			$replacers['�']='yi';
			$replacers['�']='y';
			$replacers['�']='k';
			$replacers['�']='l';
			$replacers['�']='m';
			$replacers['�']='n';
			$replacers['�']='o';
			$replacers['�']='p';
			$replacers['�']='r';
			$replacers['�']='s';
			$replacers['�']='t';
			$replacers['�']='u';
			$replacers['�']='f';
			$replacers['�']='h';
			$replacers['�']='c';
			$replacers['�']='ch';
			$replacers['�']='sh';
			$replacers['�']='shch';
			$replacers['�']='yu';
			$replacers['�']='ya';
			$replacers['�']='g';
			$str = strtolower($str);
			$nice = '';
			for ($i = 0; $i < strlen($str); $i++)
				{
					if ($str[$i] >= 'a' && $str[$i] <= 'z' || $str[$i] >= '0' && $str[$i] <= '9' || $str[$i] == '.' || $str[$i] == '_' || $str[$i] == '-')
						$nice .= $str[$i];
					elseif (!empty($replacers[$str[$i]]))
						$nice .= $replacers[$str[$i]];
					else
						$nice .= '-';
				}
			return $nice;
		}

	function sm_event($eventname, $paramsarray)
		{
			global $_settings;
			$listeners = nllistToArray($_settings['autoload_modules']);
			for ($i = 0; $i < count($listeners); $i++)
				{
					$eventfn = 'event_'.$eventname.'_'.$listeners[$i];
					if (function_exists($eventfn))
						{
							if (!is_array($paramsarray))
								$paramsarray = array($paramsarray);
							call_user_func_array($eventfn, $paramsarray);
						}
				}
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
					if ($r[$i]['filesize'] > 1048576)
						$r[$i]['filesize'] = round($r[$i]['filesize'] / 1048576, 2).' MB';
					elseif ($r[$i]['filesize'] > 1024)
						$r[$i]['filesize'] = round($r[$i]['filesize'] / 1024, 2).' KB';
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
			global $tableprefix, $userinfo, $_settings;
			if ($filesPointer['error'] <> UPLOAD_ERR_OK)
				return false;
			$fs = $filesPointer['tmp_name'];
			if (!empty($fs))
				{
					$file_download = "'".dbescape(sm_getnicename($filesPointer['name']))."'";
					$userlevel_download = $userlevel;
					$attachment_from = "'".dbescape($fromModule)."'";
					$attachment_id = intval($fromId);
					$attachment_type = "'".$filesPointer['type']."'";
					$sql = "INSERT INTO ".$tableprefix."downloads (file_download, userlevel_download, attachment_from, attachment_id, attachment_type) VALUES ($file_download, $userlevel_download, $attachment_from, $attachment_id, $attachment_type)";
					$newid = insertsql($sql);
					if (empty($newid)) return false;
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
			global $tableprefix, $userinfo, $_settings;
			$r = sm_get_attachments($fromModule, $fromId);
			for ($i = 0; $i < count($r); $i++)
				{
					if (file_exists($r[$i]['realfilename']))
						unlink($r[$i]['realfilename']);
					deletesql($tableprefix.'downloads', 'id_download', $r[$i]['id']);
				}
		}

	function sm_delete_attachment($id)
		{
			global $tableprefix, $userinfo, $_settings;
			if (file_exists('files/download/attachment'.intval($id)))
				unlink('files/download/attachment'.intval($id));
			deletesql($tableprefix.'downloads', 'id_download', intval($id));
		}

	function sm_sqlarrayf($sql)
		{
			global $tableprefix, $_settings;
			$a = getsqlarray($sql, 'a');
			$r = Array();
			for ($i = 0; $i < count($a); $i++)
				{
					if (is_array($a[$i]))
						while (list($key, $val) = each($a[$i]))
							{
								if (strpos($key, '_'))
									{
										$key2 = substr($key, 0, strpos($key, '_'));
										$r[$i][$key2] = $val;
									}
								else
									$r[$i][$key] = $val;
							}
				}
			return $r;
		}

	function sm_upload_file($upload_var = 'userfile', $upload_path = '')
		{
			global $_uplfilevars;
			$fs = $_uplfilevars[$upload_var]['tmp_name'];
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
			elseif (strpos($useragent, 'text/vnd.wap.wml') > 0 || strpos($httpaccept, 'application/vnd.wap.xhtml+xml') > 0)
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

	function sm_page_viewid($id, $rewriteanyway = false)
		{
			global $special, $modules_index;
			if ($modules_index == 0 || $rewriteanyway)
				$special['page']['viewid'] = $id;
		}

	function sm_extcore()
		{
			include_once('includes/smcoreext.php');
		}

	function sm_set_userfield($userid, $fieldname, $value)
		{
			global $tableusersprefix;
			$q = new TQuery($tableusersprefix."users");
			$q->Add($fieldname, dbescape($value));
			$q->Update('id_user', intval($userid));
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
					$sql = "UPDATE ".$tableusersprefix."users SET id_session='".session_id()."', last_login='".time()."' WHERE id_user='".intval($usrinfo['id_user'])."'";
					$result = execsql($sql);
				}
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
			$smarty->template_dir = 'themes/'.$themename.'/';
			$smarty->compile_dir = 'files/themes/'.$themename.'/';
			$smarty->config_dir = 'themes/'.$themename.'/';
			$smarty->cache_dir = 'files/temp/';
			$smarty->template_dir_default = 'themes/default/';
			$sm['s']['theme'] = $themename;
			if ($sm['_s']['sm_changetheme_default_theme'] == 1)
				$sm['_s']['default_theme'] = $themename;
		}

	//Return true  if current action is in set $action1, $action2... or false otherwice
	function sm_action()
		{
			global $m;
			for ($i = 0; $i < func_num_args(); $i++)
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
			global $m;
			$m['title'] = $title;
		}
	
	function sm_meta_title($title, $hide_site_title=true)
		{
			global $special, $_settings;
			$special['dont_take_a_title']=1;
			$special['pagetitle']=$title;
			if ($hide_site_title)
				$_settings['meta_resource_title_position']=0;
		}

	function sm_meta_keywords($keywodrs, $append=false)
		{
			global $special;
			if ($append)
				$special['meta']['keywords'].=$keywodrs;
			else
				$special['meta']['keywords']=$keywodrs;
		}

	function sm_meta_description($description, $append=false)
		{
			global $special;
			if ($append)
				$special['meta']['description'].=$description;
			else
				$special['meta']['description']=$description;
		}
	
	function sm_homepage()
		{
			global $special, $_settings;
			return $special['page']['parsed_url']['scheme'].'://'.$_settings['resource_url']; 
		}
	
	function sm_use($libname)
		{
			if (file_exists('includes/'.$libname.'.php'))
				include_once('includes/'.$libname.'.php');
		}
	
	function sm_setfocus($dom_id)
		{
			global $sm;
			$sm['s']['autofocus']=$dom_id;
		}
	
	function sm_thumburl($filename, $maxwidth=0, $maxheight=0, $format='', $quality='', $path='files/img/')
		{
			$info=pathinfo($filename);
			$url='ext/showimg.php?img='.$info['filename'];
			if ($info['extension']=='png')
				$url.='&png=1';
			if ($info['extension']=='gif')
				$url.='&gif=1';
			if (strpos($path, 'files/img/')==0 && strlen($path)>10)
				$url.='&ext='.substr($path, 10);
			if (!empty($quality))
				$url.='&quality='.$quality;
			if (!empty($format))
				$url.='&format='.$format;
			if (!empty($maxwidth))
				$url.='&width='.$maxwidth;
			if (!empty($maxheight))
				$url.='&height='.$maxheight;
			return $url;
		}

?>