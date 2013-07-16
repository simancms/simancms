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
			$lnkDB = mysql_connect($host, $user, $password);
			if (!mysql_select_db($database, $lnkDB))
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
			$r = mysql_query($sql);
			if (!$r)
				{
					//print('<hr />'.mysql_error($lnkDB).'<br /> ====&gt;<br />'.$sql.'<hr />');
				}
			return $r;
		}

	function database_fetch_object($result)
		{
			return mysql_fetch_object($result);
		}

	function database_fetch_row($result)
		{
			return mysql_fetch_row($result);
		}


	function database_fetch_array($result)
		{
			return mysql_fetch_array($result);
		}

	function database_fetch_assoc($result)
		{
			return mysql_fetch_assoc($result);
		}

	function database_insert_id($tbl, $nameDB, $lnkDB)
		{
			return mysql_insert_id($lnkDB);
		}

	function database_real_escape_string($unescaped_string, $lnkDB)
		{
			return mysql_real_escape_string($unescaped_string, $lnkDB);
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