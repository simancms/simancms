<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.3	                                                               |
	//#revision 2012-08-14                                                         |
	//==============================================================================

	function database_get_fn_name($fn)
		{
			if (strcmp($fn, 'rand') == 0)
				return 'rand';
			if (strcmp($fn, 'curdate') == 0)
				return 'curdate';
		}

	function database_connect($host, $user, $password, $database = 'siman')
		{
			global $serverDB, $lnkDB;
			if (empty($host))
				$host='.';
			$lnkDB = new  SQLite3($host.'/'.$database, SQLITE3_OPEN_READWRITE);
			if (!empty($error))
				{
					return false;
				}
			return $lnkDB;
		}

	function database_query($sql, $lnkDB)
		{
			return database_db_query('', $sql, $lnkDB);
		}

	function database_db_query($nameDB /*DEPRECATED*/, $sql, $lnkDB)
		{
			global $special, $_settings;
			if ($_settings['show_script_info'] == 'on')
				{
					$special['sql']['count']++;
					//$special['sql']['last_query']=$sql;
					//$special['sql']['queries'][count($special['sql']['queries'])]=$sql;
				}
			$r = $lnkDB->query($sql);
			if (!$r)
				{
					//print('<hr />'.$lnkDB->lastErrorMsg().'<br /> ====&gt;<br />'.$sql.'<hr />');
				}
			return $r;
		}

	function database_fetch_object($result)
		{
			if (!class_exists('DATABASEFieldObject'))
				{
					class DATABASEFieldObject
						{
							function SetField($f, $v)
								{
									$this->$f=$v;
								}
						}
				}
			if (!$row=database_fetch_assoc($result))
				return false;
			$data=new DATABASEFieldObject();
			if (is_array($row))
				{
					foreach ($row as $key=>$val)
						{
							$data->SetField($key, $val);
						}
				}
			return $data;
		}

	function database_fetch_row($result)
		{
			return $result->fetchArray(SQLITE3_NUM);
		}


	function database_fetch_array($result)
		{
			return $result->fetchArray(SQLITE3_BOTH);
		}

	function database_fetch_assoc($result)
		{
			return $result->fetchArray(SQLITE3_ASSOC);
		}

	function database_insert_id($tbl, $nameDB, $lnkDB)
		{
			return $lnkDB->lastInsertRowID();
		}

	function database_real_escape_string($unescaped_string, $lnkDB)
		{
			return $lnkDB->escapeString($unescaped_string);
		}

	function execsql($sql)
		{
			global $nameDB, $lnkDB;
			return database_db_query($nameDB, $sql, $lnkDB);
		}

	function insertsql($sql)
		{
			global $nameDB, $lnkDB;
			database_db_query($nameDB, $sql, $lnkDB);
			return database_insert_id('', $nameDB, $lnkDB);
		}

	function deletesql($tablename, $idname, $idval)
		{
			execsql("DELETE FROM $tablename WHERE $idname = '".dbescape($idval)."'");
		}

	function getsql($sql, $type = 'a')
		{
			$result = execsql($sql);
			if ($type == 'r')
				return database_fetch_row($result);
			elseif ($type == 'o')
				return database_fetch_object($result);
			else
				return database_fetch_array($result);
		}

	function getsqlarray($sql, $type = 'a')
		{
			$result = execsql($sql);
			$i = 0;
			$r = Array();
			if ($type == 'r')
				{
					while ($row = database_fetch_row($result))
						{
							$r[$i] = $row;
							$i++;
						}
				}
			elseif ($type == 'o')
				{
					while ($row = database_fetch_object($result))
						{
							$r[$i] = $row;
							$i++;
						}
				}
			else
				{
					while ($row = database_fetch_array($result))
						{
							$r[$i] = $row;
							$i++;
						}
				}
			return $r;
		}

	function getsqlfield($sql)
		{
			$result = getsql($sql, 'r');
			return $result[0];
		}

	function dbescape($unescaped_string)
		{
			global $lnkDB;
			return database_real_escape_string($unescaped_string, $lnkDB);
		}

?>