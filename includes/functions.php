<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.3	                                                               |
//#revision 2012-07-20                                                         |
//==============================================================================

function is_email($string) 
	{
		$s = trim(strtolower($string));
		return preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $s);
	}

function addslashesJ($string) 
	{
		global $lnkDB;
		if (get_magic_quotes_gpc()==1)
			{
				$s=database_real_escape_string(stripslashes($string), $lnkDB);
			}
		else
			{
				$s=database_real_escape_string($string, $lnkDB);
			}
		return $s;
	}

function siman_upload_image($id, $prefix, $postfix='', $extention='.jpg')
	{
		global $_uplfilevars;
		$fs=$_uplfilevars["userfile".$postfix]['tmp_name'];
		if (!empty($fs))
			{
				$fd='files/img/'.$prefix.$id.$extention;
				if (file_exists($fd))
					unlink($fd);
				$res=move_uploaded_file($fs, $fd);
				if ($res!==FALSE)
					sm_event('afteruploadedimagesave', array($fd));
				return $res;
			}
		else
			return false;
	}

function siman_generate_protect_code()
	{
		global $_sessionvars, $session_prefix;
		$code=rand(0,9999);
		while (strlen($code)<4)
			$code='0'.$code;
		$_sessionvars['protect_code']=$code;
	}

//#descr ua Повертає 1 якщо в $str знаходиться одне з слів, що зберігаються в списку цензурованих $codedlist['items']. В іншому випадку повертає 0
//#descr en Returns 1 if $str have one of censored words $codedlist['items']. Anotherway it returnhs false
function is_in_censored_list($str, $codedlist)
	{
		$list=extract_siman_vars($codedlist);
		for ($i=0; $i<count($list['items']); $i++)
			if (strpos($str, $list['items'][$i])!==false)
				return 1;
		return 0;
	}

function send_mail($from,$to,$subject,$message,$attachment='',$filename='')
{
 global $lang;
 $eol="\r\n";
 $boundary='----=_Part_'.md5(uniqid(time()));
 if ($from and $a=strpos($from,'<') and strpos($from,'>',$a))
  $from="=?".$lang["charset"]."?B?".base64_encode(trim(substr($from,0,$a)))."?= ".trim(substr($from,$a));
 $headers=
 ($from ? "From: $from$eol" : '').
 "Content-Type: multipart/mixed; boundary=\"$boundary\"$eol".
 "Content-Transfer-Encoding: 8bit$eol".
 "Content-Disposition: inline$eol".
 "MIME-Version: 1.0$eol";
 $body=
 "$eol--$boundary$eol".
 "Content-Type: text/html; charset=\"".$lang["charset"]."\"; format=\"flowed\"$eol".
 "Content-Disposition: inline$eol".
 "Content-Transfer-Encoding: 8bit$eol$eol".
 $message.$eol;
 if ($attachment and is_readable($attachment) and $data=@file_get_contents($attachment))
  $body.=
   "--$boundary$eol".
   "Content-Type: application/octet-stream; name=\"$filename\"$eol".
   "Content-Disposition: attachment; filename=\"$filename\"$eol".
   "Content-Transfer-Encoding: base64$eol$eol".
   chunk_split(base64_encode($data)).$eol;
 $body.="--$boundary--$eol";
 return mail($to,"=?".$lang["charset"]."?B?".base64_encode($subject)."?=",$body,$headers);
}

//Завантажує список з файлами вказаного розширення
// load_file_list('./files/img/', 'jpg|gif|bmp')
function load_file_list($path, $ext='')
{
	$extall=explode('|', $ext);
	$dir=dir($path);
	$i=0;
	while($entry=$dir->read()) 
	  {
		if (empty($ext))
			$u=1;
		else
			{
			  	$u=0;
				for ($j=0; $j<count($extall); $j++)
					if (strpos(strtolower($entry), '.'.strtolower($extall[$j])))
						{
							$u=1;
							break;
						}
			}
	    if (strcmp($entry, '.')!=0 && strcmp($entry, '..')!=0 && $u==1)
			{
				$result[$i]=$entry;
				$i++;
			}
	  }
	$dir->close();
	if (is_array($result))
		sort($result);
	return $result;
}

//Функції роботи з віртуальною файловою системою
function register_filesystem($url, $filename, $comment)
{
	global $lnkDB, $nameDB, $tableprefix;
	$sql="INSERT INTO ".$tableprefix."filesystem (`filename_fs`, `url_fs`, `comment_fs`) VALUES ('".dbescape($filename)."', '".dbescape($url)."', '".dbescape($comment)."')";
	$result=database_db_query($nameDB, $sql, $lnkDB);
	return database_insert_id('filesystem', $nameDB, $lnkDB);
}

function update_filesystem($id, $url, $filename, $comment)
{
	global $lnkDB, $nameDB, $tableprefix;
	$sql="UPDATE ".$tableprefix."filesystem SET filename_fs='".dbescape($filename)."', url_fs='".dbescape($url)."', comment_fs='".dbescape($comment)."' WHERE id_fs=".intval($id)." ";
	$result=database_db_query($nameDB, $sql, $lnkDB);
	return database_insert_id('filesystem', $nameDB, $lnkDB);
}

function delete_filesystem($id)
{
	global $lnkDB, $nameDB, $tableprefix;
	$sql="DELETE FROM ".$tableprefix."filesystem WHERE id_fs=".intval($id);
	$result=database_db_query($nameDB, $sql, $lnkDB);
}

function get_filesystem($id)
{
	global $lnkDB, $nameDB, $tableprefix;
	$sql="SELECT * FROM ".$tableprefix."filesystem WHERE id_fs=".intval($id);
	$result=database_db_query($nameDB, $sql, $lnkDB);
	while ($row=database_fetch_object($result))
		{
			$res['id']=$row->id_fs;
			$res['url']=$row->url_fs;
			$res['filename']=$row->filename_fs;
			$res['comment']=$row->comment_fs;
		}
	return $res;
}

function get_filename($id)
{
	$r=get_filesystem($id);
	return $r['filename'];
}

function cut_str_by_word($str, $count, $end_str)
{
	$str=strip_tags($str);
	if (strlen($str)>=$count)
		{
			while (substr($str, $count, 1)!=' ' && substr($str, $count, 1)!='.' && substr($str, $count, 1)!=',' && substr($str, $count, 1)!='!' && substr($str, $count, 1)!=':' && substr($str, $count, 1)!=';' && $count>20)
				$count--;
			$res=substr($str, 0, $count).$end_str;
		}
	else
		$res=$str;
	return $res;
}


function post_genetare_insert($table, $prefix, $add_fields='', $add_values='')
	{
		global $_postvars;
		$i=0;
		while ( list( $key, $val ) = each($_postvars) )
			{
				if (strpos($key, $prefix)==0)
					{
						$fields[$i]=substr($key, strlen($prefix));
						$values[$i]=dbescape($val);
						$i++;
					}
			}					 
		$sql="INSERT INTO $table (";
		for ($i=0; $i<count($fields); $i++)	
			{
				if ($i!=0) $sql.=', ';
				$sql.=$fields[$i];
			}
		if (!empty($add_fields)) $sql.=", $add_fields";
		$sql.=') VALUES (';
		for ($i=0; $i<count($values); $i++)	
			{
				if ($i!=0) $sql.=', ';
				$sql.="'".$values[$i]."'";
			}
		if (!empty($add_values)) $sql.=", $add_values";
		$sql.=')';
		return $sql;
	}

function post_genetare_update($table, $prefix, $add_update='')
	{
		global $_postvars;
		$i=0;
		while ( list( $key, $val ) = each($_postvars) )
			{
				if (strpos($key, $prefix)==0)
					{
						$fields[$i]=substr($key, strlen($prefix));
						$values[$i]=dbescape($val);
						$i++;
					}
			}					 
		$sql="UPDATE $table SET ";
		for ($i=0; $i<count($fields); $i++)	
			{
				if ($i!=0) $sql.=', ';
				$sql.=$fields[$i].' = \''.$values[$i].'\'';
			}
		if (!empty($add_update)) $sql.=", $add_update";
		return $sql;
	}

function post_generate_filters($prefixEq='filter_', $prefixLike='like_', $prefixGt='greater_', $prefixLt='less_', $prefixMl='multu_')
	{
		global $_postvars, $modules_index, $modules;
		$i=0;
		$j=0;
		$k=0;
		$l=0;
		$m=0;
		while ( list( $key, $val ) = each($_postvars) )
			{
				if (strpos($key, $prefixEq)===0 && !empty($val))
					{
						$fields[$i]=substr($key, strlen());
						$values[$i]=dbescape($val);
						$i++;
					}
				if (strpos($key, $prefixLike)===0 && !empty($val))
					{
						$fieldsL[$j]=substr($key, strlen($prefixLike));
						$valuesL[$j]=dbescape($val);
						$j++;
					}
				if (strpos($key, $prefixGt)===0 && !empty($val))
					{
						$fieldsG[$k]=substr($key, strlen($prefixGt));
						$valuesG[$k]=dbescape($val);
						$k++;
					}
				if (strpos($key, $prefixLt)===0 && !empty($val))
					{
						$fieldsM[$l]=substr($key, strlen($prefixLt));
						$valuesM[$l]=dbescape($val);
						$l++;
					}
				if (strpos($key, $prefixMl)===0 && !empty($val))
					{
						for ($qqq=0; $qqq<count($val); $qqq++)
							{
								$fieldsB[$m]=substr($key, strlen($prefixMl));
								$valuesB[$m]=dbescape($val[$qqq]);
								$m++;
							}
					}
			}
		$sql='';
		for ($i=0; $i<count($fields); $i++)	
			{
				if ($i!=0) $sql.=' AND ';
				$sql.=$fields[$i].' = \''.$values[$i].'\'';
			}
		for ($i=0; $i<count($fieldsL); $i++)	
			{
				if ($i!=0 || !empty($sql)) $sql.=' AND ';
				$sql.=$fieldsL[$i].' LIKE \'%'.$valuesL[$i].'%\'';
			}
		for ($i=0; $i<count($fieldsG); $i++)	
			{
				if ($i!=0 || !empty($sql)) $sql.=' AND ';
				$sql.=$fieldsG[$i].' > \''.$valuesG[$i].'\'';
			}
		for ($i=0; $i<count($fieldsM); $i++)	
			{
				if ($i!=0 || !empty($sql)) $sql.=' AND ';
				$sql.=$fieldsM[$i].' < \''.$valuesM[$i].'\'';
			}
		if (count($fieldsB)>0)
			{
				if (!empty($sql)) $sql.=' AND ';
				$sql.="(";
				for ($i=0; $i<count($fieldsB); $i++)	
					{
						if ($i!=0) $sql.=' OR ';
						$sql.=$fieldsB[$i].' = \''.$valuesB[$i].'\'';
					}
				$sql.=")";
			}
		return $sql;
	}


function exec_sql_delete($table, $idname, $id)
	{
		global $nameDB, $lnkDB;
		$sql="DELETE FROM $table WHERE $idname=".intval($id);
		$result=database_db_query($nameDB, $sql, $lnkDB);
	}

function get_sql_data($table, $idname, $id)
	{
		global $nameDB, $lnkDB;
		$sql="SELECT * FROM $table WHERE $idname=".intval($id);
		$result=database_db_query($nameDB, $sql, $lnkDB);
		return database_fetch_array($result);
	}

//Список усіх наявних груп у вигляді масиву
function get_groups_list()
	{
		global $lnkDB, $nameDB, $tableusersprefix;
		$sql="SELECT * FROM ".$tableusersprefix."groups ORDER BY title_group ASC";
		$result=database_db_query($nameDB, $sql, $lnkDB);
		$i=0;
		while ($row=database_fetch_object($result))
			{
				$res[$i]['id']=$row->id_group;
				$res[$i]['title']=$row->title_group;
				$res[$i]['description']=$row->description_group;
				$res[$i]['auto']=$row->autoaddtousers_group;
				$i++;
			}
		return $res;
	}

//Переводить список ідентифікаторів груп з рядкового формату ;X;Y;Z; в масив {X,Y,Z}
function get_array_groups($gr)
	{
		$res=explode(';', $gr);
		$j=0;
		for ($i=0; $i<count($res); $i++)
			if (!empty($res[$i]))
				{
					$res2[$j]=$res[$i];
					$j++;
				}
		return $res2;
	}

//Переводить масив {X,Y,Z} в ;X;Y;Z;
function create_groups_str($array)
	{
		$str=';';
		for ($i=0; $i<count($array); $i++)
			if (!empty($array[$i]))
				$str.=$array[$i].';';
		return $str;
	}

//Повертає 1 якщо ;X;Y;Z; ;X;R;T; мають спільний ідентифікатор
function compare_groups($grp1, $grp2)
	{
		$gr1=get_array_groups($grp1);
		$gr2=get_array_groups($grp2);
		for ($i=0; $i<count($gr1); $i++)
			for ($j=0; $j<count($gr2); $j++)
				if ($gr1[$i]==$gr2[$j])
					return 1;
		return 0;
	}

//Конвертує рядок ідентифікаторів ;X;Y;Z; в sql-представлення
function convert_groups_to_sql($str, $fieldname)
	{
		$sql='';
		$gr=get_array_groups($str);
		for ($i=0; $i<count($gr); $i++)
			{
				if (!empty($sql))
					$sql.=' OR ';
				$sql.=' '.$fieldname.' LIKE \'%;'.$gr[$i].';%\'';
			}
		return $sql;
	}

//Ведення логу
define("LOG_NOLOG", 0);
define("LOG_DANGER", 1);
define("LOG_LOGIN", 10);
define("LOG_UPLOAD", 20);
define("LOG_MODIFY", 30);
define("LOG_USEREVENT", 100);
define("LOG_ALL", 120);
function log_write($type, $description)
	{
		global $lnkDB, $nameDB, $tableusersprefix, $_servervars, $_settings, $userinfo;
		if ($_settings['log_type']>=$type)
			{
				$ip=$_servervars['REMOTE_ADDR'];
				$time=time();
				$user=dbescape($userinfo['login']);
				$sql="INSERT INTO ".$tableusersprefix."log (type, description, ip, time, user) VALUES ('$type', '".dbescape($description)."', INET_ATON('$ip'), '$time', '$user')";
				$result=database_db_query($nameDB, $sql, $lnkDB);
			}
	}

//Видалення файлу/каталогу
function delete_file_dir( $_target ) 
	{
	    //file?
	    if( is_file($_target) ) {
	        if( is_writable($_target) ) {
	            if( @unlink($_target) ) {
	                return true;
	            }
	        }
	        return false;
	    }
	    //dir?
	    if( is_dir($_target) ) {
	        if( is_writeable($_target) ) {
	            foreach( new DirectoryIterator($_target) as $_res ) {
	                if( $_res->isDot() ) {
	                    unset($_res);
	                    continue;
	                }
	                if( $_res->isFile() ) {
	                    removeRessource( $_res->getPathName() );
	                } elseif( $_res->isDir() ) {
	                    removeRessource( $_res->getRealPath() );
	                }
	                unset($_res);
	            }
	            if( @rmdir($_target) ) {
	                return true;
	            }
	        }
	        return false;
	    }
	}

//Функція для введення позиції в кінець шляху
function add_path($title, $url)
	{
		global $special;
		$i=count($special['path']);
		$special['path'][$i]['title']=$title;
		$special['path'][$i]['url']=$url;
	}

//Функція для введення позиції на початок шляху
function push_path($title, $url)
	{
		global $special;
		$max=count($special['path']);
		if ($max>0)
			for ($i=max-1; $i>=0; $i++)
				{
					$special['path'][$i]['title']=$special['path'][$i-1]['title'];
					$special['path'][$i]['url']=$special['path'][$i-1]['url'];
				}
		$special['path'][0]['title']=$title;
		$special['path'][0]['url']=$url;
	}

//Функція для введення в шлях панелі керування
function add_path_home()
	{
		global $lang, $_settings;
		add_path($lang['common']['home'], 'http://'.$_settings['resource_url']);
	}

//Функція для введення в шлях панелі керування
function add_path_control()
	{
		global $lang;
		add_path($lang['control_panel'], 'index.php?m=admin');
	}

//Функція для введення в шлях панелі керування та керування модулями
function add_path_modules()
	{
		global $lang;
		add_path($lang['control_panel'], 'index.php?m=admin');
		add_path($lang['modules_mamagement'], 'index.php?m=admin&d=modules');
	}


//nllist - список розділений символами #13#10 або #10 - тобто переводом каретки
function nllistToArray($nllist, $clean_empty_values=false)
	{
		if ($clean_empty_values)
			{
				while (strpos($nllist, "\r\n\r\n"))
					$nllist=str_replace("\r\n\r\n", "\r\n", $nllist);
				while (strpos($nllist, "\n\n"))
					$nllist=str_replace("\n\n", "\n", $nllist);
			}
		$r=explode("\n", str_replace("\r", '', $nllist));
		if (count($r)==1 && $r[0]=='') return Array();
		return $r;
	}
function arrayToNllist($array)
	{
		return implode("\r\n", $array);
	}
function addto_nllist($nllist, $item)
	{
		//$nllist.=(strlen($nllist)==0?'':"\r\n").$item;
		$nllist=nllistToArray($nllist, false);
		$nllist[]=$item;
		return arrayToNllist($nllist);
	}
function removefrom_nllist($nllist, $item)
	{
		$a=nllistToArray($nllist, false);
		$b=Array();
		for ($i=0; $i<count($a); $i++)
			if ($a[$i]!=$item)
				$b[]=$a[$i];
		return arrayToNllist($b);
	}
function removefrom_nllist_index($nllist, $index)
	{
		$list='';
		$a=nllistToArray($nllist, false);
		$b=Array();
		for ($i=0; $i<count($a); $i++)
			if ($i!=$index)
				$b[]=$a[$i];
		return arrayToNllist($b);
	}

function present_nllist($nllist, $item)
	{
		$a=nllistToArray($nllist, false);
		for ($i=0; $i<count($a); $i++)
			if ($a[$i]==$item)
				return true;
		return false;
	}

function out($txt)
	{
		global $special;
		$special['textout'].=$txt;
	}


?>