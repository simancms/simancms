<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.4
	//#revision 2013-07-16
	//==============================================================================

	function is_email($string)
		{
			$s = trim(strtolower($string));
			return preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $s);
		}

	//Deprecated
	function addslashesJ($string)
		{
			if (get_magic_quotes_gpc() == 1)
				{
					$s = dbescape(stripslashes($string));
				}
			else
				{
					$s = dbescape($string);
				}
			return $s;
		}

	function siman_upload_image($id, $prefix, $postfix = '', $extention = '.jpg')
		{
			global $_uplfilevars;
			$fs = $_uplfilevars["userfile".$postfix]['tmp_name'];
			if (!empty($fs))
				{
					$fd = 'files/img/'.$prefix.$id.$extention;
					if (file_exists($fd))
						unlink($fd);
					$res = move_uploaded_file($fs, $fd);
					if ($res !== FALSE)
						sm_event('afteruploadedimagesave', array($fd));
					return $res;
				}
			else
				return false;
		}

	function siman_generate_protect_code()
		{
			global $_sessionvars, $session_prefix;
			$code = rand(0, 9999);
			while (strlen($code)<4)
				$code = '0'.$code;
			$_sessionvars['protect_code'] = $code;
		}

	function send_mail($from, $to, $subject, $message, $attachment = '', $filename = '')
		{
			global $lang;
			$eol = "\r\n";
			$boundary = '----=_Part_'.md5(uniqid(time()));
			if ($from and $a = strpos($from, '<') and strpos($from, '>', $a))
				$from = "=?".$lang["charset"]."?B?".base64_encode(trim(substr($from, 0, $a)))."?= ".trim(substr($from, $a));
			$headers =
				($from ? "From: $from$eol" : '').
					"Content-Type: multipart/mixed; boundary=\"$boundary\"$eol".
					"Content-Transfer-Encoding: 8bit$eol".
					"Content-Disposition: inline$eol".
					"MIME-Version: 1.0$eol";
			$body =
				"$eol--$boundary$eol".
					"Content-Type: text/html; charset=\"".$lang["charset"]."\"; format=\"flowed\"$eol".
					"Content-Disposition: inline$eol".
					"Content-Transfer-Encoding: 8bit$eol$eol".
					$message.$eol;
			if ($attachment and is_readable($attachment) and $data = @file_get_contents($attachment))
				$body .=
					"--$boundary$eol".
						"Content-Type: application/octet-stream; name=\"$filename\"$eol".
						"Content-Disposition: attachment; filename=\"$filename\"$eol".
						"Content-Transfer-Encoding: base64$eol$eol".
						chunk_split(base64_encode($data)).$eol;
			$body .= "--$boundary--$eol";
			return mail($to, "=?".$lang["charset"]."?B?".base64_encode($subject)."?=", $body, $headers);
		}

	// load_file_list('./files/img/', 'jpg|gif|bmp')
	function load_file_list($path, $ext = '')
		{
			$extall = explode('|', $ext);
			$dir = dir($path);
			$i = 0;
			while ($entry = $dir->read())
				{
					if (empty($ext))
						$u = 1;
					else
						{
							$u = 0;
							for ($j = 0; $j<count($extall); $j++)
								{
									if (strpos(strtolower($entry), '.'.strtolower($extall[$j])))
										{
											$u = 1;
											break;
										}
								}
						}
					if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && $u == 1)
						{
							$result[$i] = $entry;
							$i++;
						}
				}
			$dir->close();
			if (is_array($result))
				sort($result);
			return $result;
		}

	function register_filesystem($url, $filename, $comment)
		{
			global $tableprefix;
			$sql = "INSERT INTO ".$tableprefix."filesystem (`filename_fs`, `url_fs`, `comment_fs`) VALUES ('".dbescape($filename)."', '".dbescape($url)."', '".dbescape($comment)."')";
			return insertsql($sql);
		}

	function update_filesystem($id, $url, $filename, $comment)
		{
			global $tableprefix;
			$sql = "UPDATE ".$tableprefix."filesystem SET filename_fs='".dbescape($filename)."', url_fs='".dbescape($url)."', comment_fs='".dbescape($comment)."' WHERE id_fs=".intval($id)." ";
			execsql($sql);
		}

	function delete_filesystem($id)
		{
			global $tableprefix;
			$sql = "DELETE FROM ".$tableprefix."filesystem WHERE id_fs=".intval($id);
			execsql($sql);
		}

	function get_filesystem($id)
		{
			global $tableprefix;
			$sql = "SELECT * FROM ".$tableprefix."filesystem WHERE id_fs=".intval($id);
			$result = execsql($sql);
			while ($row = database_fetch_object($result))
				{
					$res['id'] = $row->id_fs;
					$res['url'] = $row->url_fs;
					$res['filename'] = $row->filename_fs;
					$res['comment'] = $row->comment_fs;
				}
			return $res;
		}

	function get_filename($id)
		{
			$r = get_filesystem($id);
			return $r['filename'];
		}

	function cut_str_by_word($str, $count, $end_str)
		{
			$str = strip_tags($str);
			if (strlen($str)>=$count)
				{
					while (substr($str, $count, 1) != ' ' && substr($str, $count, 1) != '.' && substr($str, $count, 1) != ',' && substr($str, $count, 1) != '!' && substr($str, $count, 1) != ':' && substr($str, $count, 1) != ';' && $count>20)
						$count--;
					$res = substr($str, 0, $count).$end_str;
				}
			else
				$res = $str;
			return $res;
		}


	function exec_sql_delete($table, $idname, $id)
		{
			$sql = "DELETE FROM $table WHERE $idname=".intval($id);
			execsql($sql);
		}

	function get_sql_data($table, $idname, $id)
		{
			$sql = "SELECT * FROM $table WHERE $idname=".intval($id);
			$result = execsql($sql);
			return database_fetch_array($result);
		}

	function get_groups_list()
		{
			global $tableusersprefix;
			$sql = "SELECT * FROM ".$tableusersprefix."groups ORDER BY title_group ASC";
			$result = execsql($sql);
			$i = 0;
			while ($row = database_fetch_object($result))
				{
					$res[$i]['id'] = $row->id_group;
					$res[$i]['title'] = $row->title_group;
					$res[$i]['description'] = $row->description_group;
					$res[$i]['auto'] = $row->autoaddtousers_group;
					$i++;
				}
			return $res;
		}

	//str ;X;Y;Z; to array {X,Y,Z}
	function get_array_groups($gr)
		{
			$res = explode(';', $gr);
			$j = 0;
			for ($i = 0; $i<count($res); $i++)
				{
					if (!empty($res[$i]))
						{
							$res2[$j] = $res[$i];
							$j++;
						}
				}
			return $res2;
		}

	//array {X,Y,Z} to str ;X;Y;Z;
	function create_groups_str($array)
		{
			$str = ';';
			for ($i = 0; $i<count($array); $i++)
				{
					if (!empty($array[$i]))
						$str .= $array[$i].';';
				}
			return $str;
		}

	//return 1 if both groups ;X;Y;Z; ;X;R;T; has the same group in list 
	function compare_groups($grp1, $grp2)
		{
			$gr1 = get_array_groups($grp1);
			$gr2 = get_array_groups($grp2);
			for ($i = 0; $i<count($gr1); $i++)
				{
					for ($j = 0; $j<count($gr2); $j++)
						{
							if ($gr1[$i] == $gr2[$j])
								return 1;
						}
				}
			return 0;
		}

	//Convert group string ;X;Y;Z; to SQL
	function convert_groups_to_sql($str, $fieldname)
		{
			$sql = '';
			$gr = get_array_groups($str);
			for ($i = 0; $i<count($gr); $i++)
				{
					if (!empty($sql))
						$sql .= ' OR ';
					$sql .= ' '.$fieldname.' LIKE \'%;'.$gr[$i].';%\'';
				}
			return $sql;
		}

	define("LOG_NOLOG", 0);
	define("LOG_DANGER", 1);
	define("LOG_LOGIN", 10);
	define("LOG_UPLOAD", 20);
	define("LOG_MODIFY", 30);
	define("LOG_USEREVENT", 100);
	define("LOG_ALL", 120);
	function log_write($type, $description)
		{
			global $tableusersprefix, $_servervars, $_settings, $userinfo;
			if ($_settings['log_type']>=$type)
				{
					$ip = $_servervars['REMOTE_ADDR'];
					$sql = "INSERT INTO ".$tableusersprefix."log (type, description, ip, time, user) VALUES (".intval($type).", '".dbescape($description)."', INET_ATON('$ip'), ".time().", '".dbescape($userinfo['login'])."')";
					execsql($sql);
				}
		}

	function delete_file_dir($_target)
		{
			//file?
			if (is_file($_target))
				{
					if (is_writable($_target))
						{
							if (@unlink($_target))
								{
									return true;
								}
						}
					return false;
				}
			//dir?
			if (is_dir($_target))
				{
					if (is_writeable($_target))
						{
							foreach (new DirectoryIterator($_target) as $_res)
								{
									if ($_res->isDot())
										{
											unset($_res);
											continue;
										}
									if ($_res->isFile())
										{
											removeRessource($_res->getPathName());
										}
									elseif ($_res->isDir())
										{
											removeRessource($_res->getRealPath());
										}
									unset($_res);
								}
							if (@rmdir($_target))
								{
									return true;
								}
						}
					return false;
				}
		}

	function add_path($title, $url)
		{
			global $special;
			$i = count($special['path']);
			$special['path'][$i]['title'] = $title;
			$special['path'][$i]['url'] = $url;
		}

	function push_path($title, $url)
		{
			global $special;
			$max = count($special['path']);
			if ($max>0)
				for ($i = max-1; $i>=0; $i++)
					{
						$special['path'][$i]['title'] = $special['path'][$i-1]['title'];
						$special['path'][$i]['url'] = $special['path'][$i-1]['url'];
					}
			$special['path'][0]['title'] = $title;
			$special['path'][0]['url'] = $url;
		}

	function add_path_home()
		{
			global $lang, $_settings;
			add_path($lang['common']['home'], 'http://'.$_settings['resource_url']);
		}

	function add_path_control()
		{
			global $lang;
			add_path($lang['control_panel'], 'index.php?m=admin');
		}

	function add_path_modules()
		{
			global $lang;
			add_path($lang['control_panel'], 'index.php?m=admin');
			add_path($lang['modules_mamagement'], 'index.php?m=admin&d=modules');
		}


	//nllist - sting with items separated by new line character (s)
	function nllistToArray($nllist, $clean_empty_values = false)
		{
			if ($clean_empty_values)
				{
					while (strpos($nllist, "\r\n\r\n"))
						$nllist = str_replace("\r\n\r\n", "\r\n", $nllist);
					while (strpos($nllist, "\n\n"))
						$nllist = str_replace("\n\n", "\n", $nllist);
				}
			$r = explode("\n", str_replace("\r", '', $nllist));
			if (count($r) == 1 && $r[0] == '') return Array();
			return $r;
		}

	function arrayToNllist($array)
		{
			return implode("\r\n", $array);
		}

	function addto_nllist($nllist, $item)
		{
			//$nllist.=(strlen($nllist)==0?'':"\r\n").$item;
			$nllist = nllistToArray($nllist, false);
			$nllist[] = $item;
			return arrayToNllist($nllist);
		}

	function removefrom_nllist($nllist, $item)
		{
			$a = nllistToArray($nllist, false);
			$b = Array();
			for ($i = 0; $i<count($a); $i++)
				{
					if ($a[$i] != $item)
						$b[] = $a[$i];
				}
			return arrayToNllist($b);
		}

	function removefrom_nllist_index($nllist, $index)
		{
			$list = '';
			$a = nllistToArray($nllist, false);
			$b = Array();
			for ($i = 0; $i<count($a); $i++)
				{
					if ($i != $index)
						$b[] = $a[$i];
				}
			return arrayToNllist($b);
		}

	function present_nllist($nllist, $item)
		{
			$a = nllistToArray($nllist, false);
			for ($i = 0; $i<count($a); $i++)
				{
					if ($a[$i] == $item)
						return true;
				}
			return false;
		}

	function out($txt)
		{
			global $special;
			$special['textout'] .= $txt;
		}


?>