<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.4
//#revision 2013-04-09
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Hacking attempt!');
		exit();
	}

if (!defined("ACCOUNT_FUNCTIONS_DEFINED"))
	{
		
		define("ACCOUNT_FUNCTIONS_DEFINED", 1);
	}

if (empty($m['mode']))
	$m['mode']='show';

if (strcmp($m['mode'], "postregister")==0 && ($_settings['allow_register'] || !empty($userinfo['id'])))
	{
		$m["module"]='account';
		$m["title"]=$lang["register"];
		$login=dbescape($_postvars["p_login"]);
		$password=$_postvars["p_password"];
		$password2=$_postvars["p_password2"];
		if ($_settings['use_email_as_login']!=1)
			$email=dbescape($_postvars["p_email"]);
		else
			$email=$login;
		$question=dbescape($_postvars["p_question"]);
		$answer=dbescape($_postvars["p_answer"]);
		if (empty($login) || empty($password) || empty($password2) || empty($email) || empty($question) || empty($answer))
		  {
			$m['message']=$lang["message_set_all_fields"];
			$m['mode']='register';
			$m['user_login']=$login;
			$m['user_email']=$email;
			$m['user_question']=$question;
			$m['user_answer']=$answer;
		  }
		elseif (!is_email($email))
		  {
			$m['message']=$lang["message_bad_email"];
			$m['mode']='register';
			$m['user_login']=$login;
			$m['user_email']=$email;
			$m['user_question']=$question;
			$m['user_answer']=$answer;
		  }
		elseif (strcmp($password,$password2)!=0)
		  {
			$m['message']=$lang["message_passwords_not_equal"];
			$m['mode']='register';
			$m['user_login']=$login;
			$m['user_email']=$email;
			$m['user_question']=$question;
			$m['user_answer']=$answer;
		  }
		elseif ($_settings['use_protect_code']==1 && (strcmp($_sessionvars['protect_code'], $_postvars['p_protect_code'])!=0 || empty($_postvars['p_protect_code'])))
			{
				$m['message']=$lang['module_account']['wrong_protect_code'];
				$m['mode']='register';
				$m['user_login']=$login;
				$m['user_email']=$email;
				$m['user_question']=$question;
				$m['user_answer']=$answer;
			}
		else
		  {
			$sql="SELECT * FROM ".$tableusersprefix."users WHERE login = '$login'";
			$result=execsql($sql);
			$u=0;
			while ($row=database_fetch_object($result))
				{
					if (strcmp($row->login, $login)==0)
						{
							$u=1;
						}
				}
			$sql="SELECT * FROM ".$tableusersprefix."users WHERE email = '$email'";
			$result=execsql($sql);
			$u=0;
			while ($row=database_fetch_object($result))
				{
					if (strcmp($row->email, $email)==0)
						{
							$u=2;
						}
				}
			if ($u==1)
			  {
				$m['message']=$lang["message_this_login_present_try_another"];
				$m['mode']='register';
				$m['user_login']='';
				$m['user_email']=$email;
				$m['user_question']=$question;
				$m['user_answer']=$answer;
			  }
			elseif ($u==2)
			  {
				$m['message']=$lang["message_bad_email"];
				$m['mode']='register';
				$m['user_login']=$login;
				$m['user_email']=$email;
				$m['user_question']=$question;
				$m['user_answer']=$answer;
			  }
			else
			  {
			  	//$password=md5($password);
				include('includes/smcoreext.php');
				if($_settings['user_activating_by_admin']==1)
					$user_status='0';
				else
					$user_status='1';
				//$sql="INSERT INTO ".$tableusersprefix."users (login, password, email, question, answer, user_status) VALUES  ('$login', '$password', '$email', '$question', '$answer', '$user_status')";
				//$result=execsql($sql);
				$id_newuser=sm_add_user($login, $password, $email, $question, $answer, $user_status);
				sm_event('successregister', array($id_newuser));
				if (!empty($_settings['redirect_after_register']))
					{
						$refresh_url=$_settings['redirect_after_register'];
						$m['module']='refresh';
					}
				$m['mode']='successregister';
				log_write(LOG_LOGIN, $lang['module_account']['log']['user_registered'].': '.$login.'. '.$lang['email'].': '.$email);
			  }
		  }
	}



if ($_settings['allow_forgot_password']==1)
	{
		if (strcmp($m['mode'], 'getpasswd')==0)
			{
				$m["module"]='account';
				$m["title"]=$lang["get_password"];
			}
		  if (strcmp($m['mode'], 'getpasswd3')==0)
			{
				$m["module"]='account';
				$m["title"]=$lang["get_password"];
				$usr_name=dbescape($_getvars["login"]);
				$usr_answer=dbescape($_postvars["p_answ"]);
				$usr_newpwd=md5(dbescape($_postvars["p_newpwd"]));
				$sql="SELECT id_user FROM ".$tableusersprefix."users WHERE login='$usr_name' AND answer='$usr_answer' AND answer<>''";
				$info=getsql($sql);
				if (!empty($info['id_user']))
					{
						$sql="UPDATE ".$tableusersprefix."users SET password='$usr_newpwd' WHERE login='$usr_name' AND answer='$usr_answer' AND answer<>''";
						$result=execsql($sql);
						log_write(LOG_LOGIN, $lang['get_password'].' - '.$lang['common']['ok']);
						sm_event('onchangepassword', Array('login' => $_getvars["login"], 'newpassword' => $_postvars["p_newpwd"]));
						$refresh_url='index.php?m=account';
					}
				else
					{
						log_write(LOG_LOGIN, $lang['get_password'].' - '.$lang["error"]);
						$m['mode']='getpasswd2';
					}
			}
		  if (strcmp($m['mode'], 'getpasswd2')==0)
			{
				$m["module"]='account';
				$m["title"]=$lang["get_password"];
				$usr_name=$_getvars["login"];
				$sql="SELECT * FROM ".$tableusersprefix."users WHERE login='".dbescape($usr_name)."'";
				$result=execsql($sql);
				while ($row=database_fetch_object($result))
					{
						$m['secret_question']=$row->question;
						$m['userdata_login']=$usr_name;
					}
				if (empty($m['secret_question']))
					$m['mode']="wronglogin";
			}
	}

if (strcmp($m['mode'], 'register')==0)
	{
		$m["module"]='account';
		$m["title"]=$lang["register"];
		if ($_settings['use_protect_code']==1)
			siman_generate_protect_code();
		sm_event('onregister', array(''));
		sm_page_viewid('account-register');
	}
if (strcmp($m['mode'], 'login')==0)
	{
		$m["module"]='account';
		$m["title"]=$lang["login_caption"];
		if (!empty($_postvars["login_d"]))
			{
				$usr_name=dbescape(strtolower($_postvars["login_d"]));
				$usr_passwd=md5($_postvars["passwd_d"]);
				if ($_settings['signinwithloginandemail']==1)
					$usrlogin=getsql("SELECT * FROM ".$tableusersprefix."users WHERE (lower(login)='$usr_name' OR lower(email)='$usr_name') AND password='$usr_passwd' AND user_status>0 LIMIT 1");
				else
					$usrlogin=getsql("SELECT * FROM ".$tableusersprefix."users WHERE lower(login)='$usr_name' AND password='$usr_passwd' AND user_status>0 LIMIT 1");
			}
		else
			$m['mode']='show';
		if (empty($usrlogin['id_user']) && $m['mode']!='show')
			{
				$m['mode']="wronglogin";
				log_write(LOG_DANGER, $lang['module_account']['log']['user_not_logged'].': '.$usr_name);
				$special['autofocus']='login_d';
				sm_extcore();
				$autoban_time=sm_get_settings('autoban_time', 'general');
				sm_tempdata_addint('wronglogin', $_servervars['REMOTE_ADDR'], time(), $autoban_time);
				//Autoban checking
				if (intval(sm_tempdata_aggregate('wronglogin', $_servervars['REMOTE_ADDR'], SM_AGGREGATE_COUNT))>intval(sm_get_settings('autoban_attempts', 'general')))
					{
						sm_update_settings('autoban_ips', addto_nllist(sm_get_settings('autoban_ips'), $_servervars['REMOTE_ADDR']));
						sm_tempdata_addint('bannedip', $_servervars['REMOTE_ADDR'], time(), $autoban_time);
						sm_tempdata_remove('wronglogin', $_servervars['REMOTE_ADDR']);
						sm_access_denied();
					}
			}
		elseif ($m['mode']!='show')
			{
				sm_login($usrlogin['id_user'], $usrlogin);
				$m['mode']="successlogin";
				include('includes/userinfo.php');
				//$sql="UPDATE ".$tableusersprefix."users SET id_session='".$userinfo['session']."', last_login='".time()."' WHERE id_user='".$userinfo['id']."'";
				//$result=execsql($sql);
				sm_event('successlogin', array($userinfo['id']));
				if ($_postvars['autologin_d']==1 || $_settings['alwaysautologin']==1)
					{
						setcookie($_settings['cookprefix'].'simanautologin', md5($session_prefix.$userinfo['info']['random_code'].$userinfo['id']), time()+(intval($_settings['autologinlifetime'])>0?intval($_settings['autologinlifetime']):30758400));
					}
				log_write(LOG_LOGIN, $lang['module_account']['log']['user_logged']);
				if ($_settings['return_after_login']==1 && !empty($_postvars['p_goto_url']))
					{
						sm_redirect($_postvars['p_goto_url']);
					}
				elseif (!empty($_settings['redirect_after_login_3']) && $userinfo['level']==3)
					{
						sm_redirect($_settings['redirect_after_login_3']);
					}
				elseif (!empty($_settings['redirect_after_login_2']) && $userinfo['level']>=2)
					{
						sm_redirect($_settings['redirect_after_login_2']);
					}
				elseif (!empty($_settings['redirect_after_login_1']) && $userinfo['level']>=1)
					{
						sm_redirect($_settings['redirect_after_login_1']);
					}
				else
					{
						sm_redirect('index.php?m=account&d=cabinet');
					}
			}
	}
if (strcmp($m['mode'], 'show')==0)
	{
		if ($modules_index==0 && !empty($userinfo['id']))
			$m['mode']='cabinet';
		else
			{
				$m["title"]=$lang["login_caption"];
				$m["module"]='account';
				$m["goto_url"]=$_servervars['REQUEST_URI'];
				if ($modules_index==0)
					$special['autofocus']='login_d';
				sm_event('onshowloginpage', array(''));
				sm_page_viewid('account-show');
			}
	}

if ($userinfo['level']>0)
	include('modules/inc/memberspart/account.php');

?>