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
	require("../includes/dbelite.php");
	require("../includes/functions.php");
	require("../includes/smcoreext.php");

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
			$inst['db']['host']=$hostNameDB;
			$inst['db']['db']=$nameDB;
			$inst['db']['user']=$userNameDB;
			$inst['db']['password']=$userPasswordDB;
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
									if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strcmp(strtolower($entry), '.ds_store') != 0 && strcmp(strtolower($entry), '.htaccess') != 0)
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
							if (!file_exists(dirname(dirname(__FILE__)).'/files/.htaccess'))
								copy(dirname(dirname(__FILE__)).'/ext/.htaccess', dirname(dirname(__FILE__)).'/files/.htaccess');
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
							$inst['tables'][17]['name'] = 'metadata';
							$inst['tables'][18]['name'] = 'taxonomy';
							$inst['tables'][19]['name'] = 'media';
							$inst['tables'][20]['name'] = 'categories_media';
							include('dbcreate'.$serverDB.'.php');
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
									if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0 && strcmp($entry, 'index.html') != 0 && strcmp($entry, 'default') != 0 && strcmp(strtolower($entry), '.ds_store') != 0 && strcmp(strtolower($entry), '.htaccess') != 0)
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
							$inst['settings']['title'] = dbescape($_postvars['p_title']);
							$inst['settings']['addr'] = dbescape($_postvars['p_url']);
							$inst['settings']['logo'] = dbescape($_postvars['p_logo']);
							$inst['settings']['copyright'] = dbescape($_postvars['p_copyright']);
							$inst['settings']['lang'] = $_postvars['p_lang'];
							$inst['settings']['theme'] = $_postvars['p_theme'];
							$inst['settings']['default_module'] = $_postvars['default_module'];
							if ($inst['settings']['default_module'] == 'news')
								$inst['settings']['news_use_time'] = 1;
							include('dbpset.php');
							if ($inst['settings']['theme'] == 'bootstrap' || $inst['settings']['theme'] == 'bootstrap3')
								{
									execsql("UPDATE ".$tableprefix."blocks SET panel_block=1, position_block=3 WHERE name_block='account'");
								}
							$inst['messages'][0]['text'] = $lang['settings_save'].'. </b> <font color="#00FF00">'.$lang['OK'].'</font>';
						}
					elseif ($inst['step'] == 6)
						{
							$inst['title'] = $lang['step6_title'];
							$inst['addadmin']['user_login'] = 'admin';
							$inst['addadmin']['user_email'] = 'webmaster@'.$_servervars['SERVER_NAME'];
						}
					elseif ($inst['step'] == 7)
						{
							$inst['title'] = $lang['step7_title'];
							$login = dbescape($_postvars["p_login"]);
							$password = $_postvars["p_password"];
							$email = dbescape($_postvars["p_email"]);
							if (empty($login) || empty($password) || empty($email))
								{
									$inst['step'] = 6;
									$inst['error'] = 1;
									$inst['errors'][count($inst['errors'])]['text'] = $lang['addadm']["message_set_all_fields"];
									$inst['addadmin']['user_login'] = $login;
									$inst['addadmin']['user_email'] = $email;
								}
							elseif (!is_email($email))
								{
									$inst['step'] = 6;
									$inst['error'] = 1;
									$inst['errors'][count($inst['errors'])]['text'] = $lang['addadm']["message_bad_email"];
									$inst['addadmin']['user_login'] = $login;
									$inst['addadmin']['user_email'] = $email;
								}
							else
								{
									$password = sm_password_hash($password, $_postvars["p_login"]);
									$sql = "INSERT INTO ".$tableusersprefix."users (login, password, email, question, answer, user_status) VALUES  ('$login', '$password', '$email', '', '', 3)";
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
							$clean_temp=true;
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
	if ($clean_temp)
		{
			$dir = dir('../files/temp/');
			while ($entry = $dir->read())
				{
					if (strcmp($entry, '.') != 0 && strcmp($entry, '..') != 0)
						unlink('../files/temp/'.$entry);
				}
			$dir->close();
		}

?>