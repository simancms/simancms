<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.15
	//#revision 2018-03-19
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("ACCOUNT_FUNCTIONS_DEFINED"))
		{

			define("ACCOUNT_FUNCTIONS_DEFINED", 1);
		}

	sm_default_action('show');

	if (sm_actionpost("postregister") && !sm_empty_settings('allow_register'))
		{
			$m['module'] = 'account';
			sm_title($lang['register']);
			sm_extcore();
			$login = $_postvars["p_login"];
			$password = $_postvars["p_password"];
			$password2 = $_postvars["p_password2"];
			if (intval(sm_settings('use_email_as_login')) == 1)
				$email = $login;
			else
				$email = $_postvars["p_email"];
			$question = $_postvars["p_question"];
			$answer = $_postvars["p_answer"];
			sm_event('postregistercheckdata', array(0));
			if (empty($login) || empty($password) || empty($password2) || empty($email) || (intval(sm_settings('account_disable_secret_question')!=1) && (empty($question) || empty($answer))) || !empty($special['postregistercheckdataerror']))
				{
					$m['message'] = $lang["message_set_all_fields"].(empty($special['postregistercheckdataerror']) ? '' : '. '.$special['postregistercheckdataerror']);
					sm_set_action('register');
				}
			elseif (!is_email($email))
				{
					$m['message'] = $lang["message_bad_email"];
					sm_set_action('register');
				}
			elseif (strcmp($password, $password2) != 0)
				{
					$m['message'] = $lang["message_passwords_not_equal"];
					sm_set_action('register');
				}
			elseif (intval(sm_settings('use_protect_code')) == 1 && (strcmp($_sessionvars['protect_code'], $_postvars['p_protect_code']) != 0 || empty($_postvars['p_protect_code'])))
				{
					$m['message'] = $lang['module_account']['wrong_protect_code'];
					sm_set_action('register');
				}
			elseif (intval(TQuery::ForTable($sm['tu'].'users')->Add('login', dbescape($login))->GetField('id_user'))>0)
				{
					$m['message'] = $lang["message_this_login_present_try_another"];
					sm_set_action('register');
				}
			elseif (intval(TQuery::ForTable($sm['tu'].'users')->Add('email', dbescape($email))->GetField('id_user'))>0)
				{
					$m['message'] = $lang["message_bad_email"];
					sm_set_action('register');
				}
			else
				{
					sm_extcore();
					if (intval(sm_settings('user_activating_by_admin')) == 1)
						$user_status = '0';
					else
						$user_status = '1';
					$id_newuser = sm_add_user($login, $password, $email, $question, $answer, $user_status);
					sm_event('successregister', array($id_newuser));
					if (!sm_empty_settings('redirect_after_register'))
						{
							sm_redirect(sm_settings('redirect_after_register'));
						}
					elseif ($userinfo['level']>0)
						{
							sm_redirect('index.php?m=account&d=usrlist');
						}
					sm_set_action('successregister');
					log_write(LOG_LOGIN, $lang['module_account']['log']['user_registered'].': '.$login.'. '.$lang['email'].': '.$email);
				}
		}
	if (sm_action('successregister'))
		{
			sm_title($lang['register']);
			sm_use('admininterface');
			$ui = new TInterface();
			$ui->p($lang['success_registration'].'.');
			$ui->a('index.php?m=account&d=show', $lang['you_can_enter']);
			$ui->Output(true);
		}

	if (intval(sm_settings('allow_forgot_password')) == 1)
		{
			if (sm_action('getpasswd'))
				{
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_title($lang['get_password']);
					$ui=new TInterface();
					$f=new TForm('index.php');
					$f->SetMethodGet();
					$f->AddHidden('m', sm_current_module());
					$f->AddHidden('d', 'getpasswd2');
					$f->AddText('login', $lang['login_str'])
						->SetFocus();
					$f->SaveButton($lang['get_password']);
					$ui->Add($f);
					$ui->Output(true);
				}
			if (sm_action('getpasswd3'))
				{
					$m['module'] = 'account';
					sm_title($lang['get_password']);
					sm_extcore();
					$usr_name = dbescape(strtolower($_getvars["login"]));
					$usr_answer = dbescape($_postvars["p_answ"]);
					$usr_newpwd = dbescape(sm_password_hash($_postvars["p_newpwd"], $_getvars["login"]));
					$info = getsql("SELECT id_user FROM ".$tableusersprefix."users WHERE lower(login)='$usr_name' AND answer='$usr_answer' AND answer<>''");
					if (!empty($info['id_user']))
						{
							execsql("UPDATE ".$tableusersprefix."users SET password='$usr_newpwd', random_code='".dbescape(md5($usr_name.microtime(true).rand()))."' WHERE lower(login)='$usr_name' AND answer='$usr_answer' AND answer<>''");
							log_write(LOG_LOGIN, $lang['get_password'].' - '.$lang['common']['ok']);
							sm_event('onchangepassword', Array('login' => $_getvars["login"], 'newpassword' => $_postvars["p_newpwd"]));
							sm_notify($lang['message_forgot_password_finish']);
							sm_redirect('index.php?m=account');
						}
					else
						{
							log_write(LOG_LOGIN, $lang['get_password'].' - '.$lang["error"]);
							sm_set_action('getpasswd2');
						}
				}
			if (sm_action('getpasswd2'))
				{
					$m['module'] = 'account';
					sm_title($lang["get_password"]);
					$usr_name = $_getvars["login"];
					$sql = "SELECT * FROM ".$tableusersprefix."users WHERE login='".dbescape(strtolower($usr_name))."'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$m['secret_question'] = $row->question;
							$m['userdata_login'] = $usr_name;
						}
					if (empty($m['secret_question']))
						sm_set_action('wronglogin');
				}
		}

	if (sm_action('register'))
		{
			if (!sm_empty_settings('allow_register') || $userinfo['level']==3)
				{
					$m['module'] = 'account';
					sm_title($lang['register']);
					if (intval(sm_settings('use_protect_code')) == 1)
						siman_generate_protect_code();
					sm_event('onregister', array(''));
					sm_page_viewid('account-register');
				}
			else
				{
					sm_extcore();
					sm_error_page($lang['error'], $lang['you_cant_register']);
				}
		}
	if (sm_action('login'))
		{
			$m['module'] = 'account';
			sm_title($lang['login_caption']);
			if (!empty($_postvars['login_d']))
				{
					sm_extcore();
					sm_event('beforelogincheck');
					if ($uid=sm_check_user($_postvars['login_d'], $_postvars['passwd_d']))
						{
							sm_event('beforelogin');
							sm_process_login($uid);
							sm_notify($lang['message_success_login']);
							//$sql="UPDATE ".$tableusersprefix."users SET id_session='".$userinfo['session']."', last_login='".time()."' WHERE id_user='".$userinfo['id']."'";
							//$result=execsql($sql);
							if ($_postvars['autologin_d'] == 1 || intval(sm_settings('alwaysautologin')) == 1)
								{
									setcookie(sm_settings('cookprefix').'simanautologin', md5($session_prefix.$userinfo['info']['random_code'].$userinfo['id']), time() + (intval(sm_settings('autologinlifetime')) > 0 ? intval(sm_settings('autologinlifetime')) : 30758400));
								}
							log_write(LOG_LOGIN, $lang['module_account']['log']['user_logged']);
							if (intval(sm_settings('return_after_login')) == 1 && !empty($_postvars['p_goto_url']))
								{
									sm_redirect($_postvars['p_goto_url']);
								}
							elseif (!sm_empty_settings('redirect_after_login_3') && $userinfo['level'] == 3)
								{
									sm_redirect(sm_settings('redirect_after_login_3'));
								}
							elseif (!sm_empty_settings('redirect_after_login_2') && $userinfo['level'] >= 2)
								{
									sm_redirect(sm_settings('redirect_after_login_2'));
								}
							elseif (!sm_empty_settings('redirect_after_login_1') && $userinfo['level'] >= 1)
								{
									sm_redirect(sm_settings('redirect_after_login_1'));
								}
							else
								{
									if (!sm_empty_settings('cabinet_module'))
										sm_redirect('index.php?m='.sm_settings('cabinet_module'));
									else
										sm_redirect('index.php?m=account&d=cabinet');
								}
						}
					else
						{
							sm_set_action('wronglogin');
							log_write(LOG_DANGER, $lang['module_account']['log']['user_not_logged'].': '.$usr_name);
							sm_setfocus('login_d');
							sm_extcore();
							$autoban_time = sm_get_settings('autoban_time', 'general');
							sm_tempdata_addint('wronglogin', $_servervars['REMOTE_ADDR'], time(), $autoban_time);
							//Autoban checking
							if (intval(sm_tempdata_aggregate('wronglogin', $_servervars['REMOTE_ADDR'], SM_AGGREGATE_COUNT)) > intval(sm_get_settings('autoban_attempts', 'general')))
								{
									sm_ban_ip($autoban_time);
									sm_tempdata_remove('wronglogin', $_servervars['REMOTE_ADDR']);
									sm_access_denied();
								}
						}
				}
			else
				sm_set_action('show');
		}
	if (sm_action('show'))
		{
			if ($modules_index == 0 && !empty($userinfo['id']))
				sm_set_action('cabinet');
			else
				{
					sm_title($lang['login_caption']);
					$m['module'] = 'account';
					$m['goto_url'] = $_servervars['REQUEST_URI'];
					if ($modules_index == 0)
						sm_setfocus('login_d');
					if (!empty($userinfo['id']))
						{
							$m['cabinet_home_url'] = 'index.php?m=account&d=cabinet';
							if (!sm_empty_settings('cabinet_module'))
								$m['cabinet_home_url'] = 'index.php?m='.sm_settings('cabinet_module');
						}
					sm_event('onshowloginpage', array(''));
					sm_page_viewid('account-show');
				}
		}

	if ($userinfo['level'] > 0)
		include('modules/inc/memberspart/account.php');
	else
		if (sm_action('logout'))
			{
				if (!sm_empty_settings('redirect_after_logout'))
					sm_redirect(sm_settings('redirect_after_logout'));
				else
					sm_redirect(sm_homepage());
			}
