<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.4
	//#revision 2013-03-31
	//==============================================================================

	session_start();

	function is_email($string)
		{
			$s = trim(strtolower($string));
			return preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $s);
		}

	function addslashesJ($string)
		{
			global $lnkDB;
			if (get_magic_quotes_gpc() == 1)
				{
					$s = database_real_escape_string(stripslashes($string), $lnkDB);
				}
			else
				{
					$s = database_real_escape_string($string, $lnkDB);
				}
			return $s;
		}

	if (!is_writeable('../files'))
		{
			print('<html><head><title>Siman CMS Installation</title></head><body>');
			print('The directory <strong>files</strong> is not writeable. Installation aborted.');
			print('</body></html>');
			exit;
		}
	else
		{
			if (!file_exists('../files/temp'))
				{
					mkdir('../files/temp', 0777);
				}
		}


	require_once('../Smarty/libs/Smarty.class.php');

	require("../includes/dbsettings.php");
	require("../includes/dbengine".$serverDB.".php");

	$smarty = new Smarty;

	$smarty->template_dir = 'templates/';
	$smarty->compile_dir = '../files/temp/';
	$smarty->config_dir = 'templates/';
	$smarty->cache_dir = './../files/temp/';

	$phpver = phpversion();
	$_getvars = $_GET;
	$_postvars = $_POST;
	$_cookievars = $_COOKIE;
	$_servervars = $_SERVER;
	$_uplfilevars = $_FILES;
	$_sessionvars = $_SESSION;

	$inst['error'] = 0;
	$inst['step'] = $_getvars['s'];

	if (!empty($_sessionvars['language']))
		{
			require("lang/".$_sessionvars['language'].".php");
		}

	if (empty($inst['step']))
		{
			$dir = dir('./lang/');
			$i = 0;
			while ($entry = $dir->read())
				{
					if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strpos($entry, '.php'))
						{
							$inst['lang'][$i] = substr($entry, 0, strpos($entry, '.'));
							$i++;
						}
				}
			$dir->close();
		}
	else
		{
			$inst['lang'] = $_sessionvars['language'];
			$lnkDB = database_connect($hostNameDB, $userNameDB, $userPasswordDB, $nameDB);
			if ($lnkDB == false)
				{
					if (empty($_sessionvars['language']))
						require("lang/en.php");
					$inst['error'] = 1;
					$inst['errors'][0]['text'] = $lang['cant_connect_to_database'];
				}
			else
				{
					if (!empty($initialStatementDB))
						$result = database_db_query($nameDB, $initialStatementDB, $lnkDB);
					if ($inst['step'] == 1)
						{
							$inst['title'] = $lang['step1_title'];
							$_sessionvars['language'] = $_postvars['p_lang'];
							require("lang/".$_sessionvars['language'].".php");
							$inst['messages'][0]['text'] = $lang['language_set'].': '.$lang['lang_name'].'. <font color="#00FF00">'.$lang['OK'].'</font>';
							$inst['created'][0] = '../files/download';
							$inst['created'][1] = '../files/img';
							$inst['created'][2] = '../files/themes';
							$inst['created'][3] = '../files/thumb';
							$inst['created'][4] = '../files/fullimg';
							//$inst['created'][3]='../files/themes/classic';
							$dir = dir('../themes/');
							$i = 5;
							while ($entry = $dir->read())
								{
									if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strcmp(strtolower($entry), '.ds_store') != 0)
										{
											$inst['created'][$i] = '../files/themes/'.$entry;
											$i++;
										}
								}
							$dir->close();
							for ($i = 0; $i < count($inst['created']); $i++)
								{
									if (!file_exists($inst['created'][$i]))
										{
											if (!mkdir($inst['created'][$i], 0777))
												{
													$inst['error'] = 1;
													$inst['errors'][count($inst['errors'])]['text'] = $lang['fatal_error'].': '.$lang['cant_create_directory'].' <b>'.$inst['created'][$i].'</b>';
												}
											else
												{
													$inst['messages'][count($inst['messages'])]['text'] = $lang['directory_creation'].' <b>'.$inst['created'][$i].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
												}
										}
									else
										{
											$inst['messages'][count($inst['messages'])]['text'] = $lang['warning'].': '.$lang['directory_creation'].' <b>'.$inst['created'][$i].'. </b> <i>'.$lang['directory_exist'].'</i>';
										}
								}
						}
					elseif ($inst['step'] == 2)
						{
							$inst['title'] = $lang['step2_title'];
							$inst['tables'][0]['name'] = 'blocks';
							$inst['tables'][1]['name'] = 'categories';
							$inst['tables'][2]['name'] = 'content';
							$inst['tables'][3]['name'] = 'menu_lines';
							$inst['tables'][4]['name'] = 'menus';
							$inst['tables'][5]['name'] = 'modules';
							$inst['tables'][6]['name'] = 'users';
							$inst['tables'][7]['name'] = 'categories_news';
							$inst['tables'][8]['name'] = 'settings';
							$inst['tables'][9]['name'] = 'news';
							$inst['tables'][10]['name'] = 'downloads';
							$inst['tables'][11]['name'] = 'filesystem';
							$inst['tables'][12]['name'] = 'groups';
							$inst['tables'][13]['name'] = 'filesystem_regexp';
							$inst['tables'][14]['name'] = 'privmsg';
							$inst['tables'][15]['name'] = 'log';
							$inst['tables'][16]['name'] = 'tempdata';
							include('dbcreate.php');
							for ($i = 0; $i < count($inst['tables']); $i++)
								{
									if ($inst['tables'][$i]['result'])
										{
											if ($inst['tables'][$i]['name'] == 'users')
												$inst['messages'][count($inst['messages'])]['text'] = $lang['table_creation'].' <b>'.$tableusersprefix.$inst['tables'][$i]['name'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
											else
												$inst['messages'][count($inst['messages'])]['text'] = $lang['table_creation'].' <b>'.$tableprefix.$inst['tables'][$i]['name'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
										}
									else
										{
											$inst['error'] = 1;
											if ($inst['tables'][$i]['name'] == 'users')
												$inst['errors'][count($inst['errors'])]['text'] = $lang['fatal_error'].': '.$lang['cant_create_table'].' <b>'.$tableusersprefix.$inst['tables'][$i]['name'].'</b>';
											else
												$inst['errors'][count($inst['errors'])]['text'] = $lang['fatal_error'].': '.$lang['cant_create_table'].' <b>'.$tableprefix.$inst['tables'][$i]['name'].'</b>';
										}
								}
						}
					elseif ($inst['step'] == 3)
						{
							$inst['title'] = $lang['step3_title'];
							$inst['tables'][0]['name'] = 'blocks';
							$inst['tables'][1]['name'] = 'categories';
							$inst['tables'][2]['name'] = 'content';
							$inst['tables'][3]['name'] = 'menu_lines';
							$inst['tables'][4]['name'] = 'menus';
							$inst['tables'][5]['name'] = 'modules';
							$inst['tables'][6]['name'] = 'categories_news';
							$inst['tables'][7]['name'] = 'news';
							include('dbpost.php');
							for ($i = 0; $i < count($inst['tables']); $i++)
								{
									$inst['messages'][count($inst['messages'])]['text'] = $lang['data_post_into_table'].' <b>'.$tableprefix.$inst['tables'][$i]['name'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
								}
						}
					elseif ($inst['step'] == 4)
						{
							$inst['title'] = $lang['step4_title'];
							$inst['resource_url'] = $_servervars['SERVER_NAME'].substr($_servervars['SCRIPT_NAME'], 0, strpos($_servervars['SCRIPT_NAME'], 'install/install.php'));
							$dir = dir('./../lang/');
							$i = 0;
							while ($entry = $dir->read())
								{
									if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strpos($entry, '.php'))
										{
											$inst['langs'][$i] = substr($entry, 0, strpos($entry, '.'));
											$i++;
										}
								}
							$dir->close();
							$dir = dir('./../themes/');
							$i = 0;
							while ($entry = $dir->read())
								{
									if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strcmp($entry, 'default') != 0)
										{
											$inst['themes'][$i] = $entry;
											$i++;
										}
								}
							$dir->close();
						}
					elseif ($inst['step'] == 5)
						{
							$inst['title'] = $lang['step5_title'];
							$inst['settings']['title'] = addslashesJ($_postvars['p_title']);
							$inst['settings']['addr'] = addslashesJ($_postvars['p_url']);
							$inst['settings']['logo'] = addslashesJ($_postvars['p_logo']);
							$inst['settings']['copyright'] = addslashesJ($_postvars['p_copyright']);
							$inst['settings']['lang'] = $_postvars['p_lang'];
							$inst['settings']['theme'] = $_postvars['p_theme'];
							$inst['settings']['default_module'] = $_postvars['default_module'];
							if ($inst['settings']['default_module'] == 'news')
								$inst['settings']['news_use_time'] = 1;
							include('dbpset.php');
							if ($inst['settings']['theme'] == 'bootstrap')
								{
									execsql("UPDATE ".$tableprefix."blocks SET panel_block=1, position_block=3 WHERE name_block='account'");
								}
							$inst['messages'][0]['text'] = $lang['settings_save'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
						}
					elseif ($inst['step'] == 6)
						{
							$inst['title'] = $lang['step6_title'];
						}
					elseif ($inst['step'] == 7)
						{
							$inst['title'] = $lang['step7_title'];
							$login = addslashesJ($_postvars["p_login"]);
							$password = $_postvars["p_password"];
							$email = addslashesJ($_postvars["p_email"]);
							$question = addslashesJ($_postvars["p_question"]);
							$answer = addslashesJ($_postvars["p_answer"]);
							if (empty($login) || empty($password) || empty($email))
								{
									$inst['step'] = 6;
									$inst['error'] = 1;
									$inst['errors'][count($inst['errors'])]['text'] = $lang['addadm']["message_set_all_fields"];
									$inst['addadmin']['user_login'] = $login;
									$inst['addadmin']['user_email'] = $email;
									$inst['addadmin']['user_question'] = $question;
									$inst['addadmin']['user_answer'] = $answer;
								}
							elseif (!is_email($email))
								{
									$inst['step'] = 6;
									$inst['error'] = 1;
									$inst['errors'][count($inst['errors'])]['text'] = $lang['addadm']["message_bad_email"];
									$inst['addadmin']['user_login'] = $login;
									$inst['addadmin']['user_email'] = $email;
									$inst['addadmin']['user_question'] = $question;
									$inst['addadmin']['user_answer'] = $answer;
								}
							else
								{
									$password = md5($password);
									$sql = "INSERT INTO ".$tableusersprefix."users (login, password, email, question, answer, user_status) VALUES  ('$login', '$password', '$email', '$question', '$answer', 3)";
									$result = database_db_query($nameDB, $sql, $lnkDB);
									$inst['messages'][0]['text'] = $lang['create_administrator'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
									$sql = "UPDATE ".$tableprefix."settings SET value_settings='".$email."' WHERE name_settings='administrators_email'";
									$result = database_db_query($nameDB, $sql, $lnkDB);
									$inst['messages'][1]['text'] = $lang['addadm']['add_settings_admin_email'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
								}
						}
					elseif ($inst['step'] == 'finish')
						{
							$inst['title'] = $lang['finish_title'];
						}
				}
		}

	while (list($key, $val) = each($_sessionvars))
		{
			$_SESSION[$key] = $val;
		}

	//  print_r($inst);

	$smarty->assign('inst', $inst);
	$smarty->assign('lang', $lang);
	$smarty->display('index.tpl');

?>