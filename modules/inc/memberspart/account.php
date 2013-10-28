<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.5
	//#revision 2013-10-17
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($_settings['allow_private_messages'] == 1 && !empty($userinfo['id']))
		include('modules/inc/memberspart/account_privatemessages.php');
	if (sm_action('postchange'))
		{
			$m["module"] = 'account';
			$m["title"] = $lang['change'];
			$old_password = dbescape($_postvars["p_old_password"]);
			$password = dbescape($_postvars["p_password"]);
			$password2 = dbescape($_postvars["p_password2"]);
			$email = $_postvars["p_email"];
			$question = dbescape($_postvars["p_question"]);
			$get_mail = intval($_postvars["p_get_mail"]);
			$answer = dbescape($_postvars["p_answer"]);
			sm_event('userdetailschangedcheckdata', array(0));
			if (empty($email) || (!empty($question) && empty($answer)) || !empty($special['userdetailschangedcheckdataerror']))
				{
					$m['message'] = $lang["message_set_all_fields"].(empty($special['userdetailschangedcheckdataerror'])?'':'. '.$special['userdetailschangedcheckdataerror']);
					$m['mode'] = 'change';
					$m['user_email'] = $email;
					$m['user_question'] = $question;
					$m['user_answer'] = $answer;
					$m['user_get_mail'] = $get_mail;
				}
			if ($userinfo['level'] == 3 && !empty($_postvars['p_user_id']))
				{
					$password = md5($password);
					$sqlpasswd = ", password = '$password' ";
				}
			else
				$sqlpasswd = '';
			if (!empty($password) && empty($sqlpasswd))
				{
					if (strcmp($password, $password2) != 0)
						{
							$m['message'] = $lang["message_passwords_not_equal"];
							$m['mode'] = 'change';
							$m['user_email'] = $email;
							$m['user_question'] = $question;
							$m['user_answer'] = $answer;
							$m['user_get_mail'] = $get_mail;
						}
					else
						{
							$password = md5($password);
							$sqlpasswd = ", password = '$password' ";
						}
				}
			if (!is_email($email))
				{
					$m['message'] = $lang["message_bad_email"];
					$m['mode'] = 'change';
					$m['user_login'] = $login;
					$m['user_email'] = $email;
					$m['user_question'] = $question;
					$m['user_answer'] = $answer;
					$m['user_get_mail'] = $get_mail;
				}
			if (strcmp($m['mode'], 'change') != 0)
				{
					if ($userinfo['level'] == 3 && !empty($_postvars['p_user_id']))
						{
							$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user = '".intval($_postvars['p_user_id'])."'";
							if ($userinfo['id']!=1)
								 $sql.=' AND id_user<>1';
						}
					else
						{
							$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user = '".intval($userinfo['id'])."'";
							if (!empty($old_password))
								{
									$sql .= " AND password = '".md5($old_password)."'";
								}
						}
					$result = execsql($sql);
					$u = 0;
					while ($row = database_fetch_object($result))
						{
							$u = 1;
						}
					if ($u == 0)
						{
							$m['message'] = $lang["error"];
							$m['mode'] = 'change';
							$m['user_email'] = $email;
							$m['user_question'] = $question;
							$m['user_answer'] = $answer;
							$m['user_get_mail'] = $get_mail;
						}
					else
						{
							$sql = "UPDATE ".$tableusersprefix."users SET email = '$email', question = '$question', answer = '$answer', get_mail = '$get_mail' $sqlpasswd ";
							if ($userinfo['level'] == 3 && !empty($_postvars['p_user_id']))
								{
									$sql .= " WHERE  id_user = '".intval($_postvars['p_user_id'])."'";
									$id_newuser = intval($_postvars['p_user_id']);
								}
							else
								{
									$sql .= " WHERE  id_user = '".intval($userinfo['id'])."'";
									$id_newuser = intval($userinfo['id']);
								}
							$result = execsql($sql);
							sm_event('userdetailschanged', array($id_newuser));
							if ($id_newuser == $userinfo['id'])
								{
									sm_login($id_newuser);
									include('includes/userinfo.php');
								}
							if ($userinfo['level'] == 3 && !empty($_postvars['p_user_id']))
								{
									if (!empty($_getvars['returnto']))
										sm_redirect($_getvars['returnto']);
									else
										sm_redirect('index.php?m=account&d=usrlist');
								}
							else
								{
									$m['mode'] = 'successchange';
									if (!empty($special['redirect_on_success_change_usrdata']))
										sm_redirect($special['redirect_on_success_change_usrdata']);
									elseif (!empty($_settings['redirect_on_success_change_usrdata']))
										sm_redirect($_settings['redirect_on_success_change_usrdata']);
									else
										sm_redirect('index.php?m=account&d=cabinet');
								}
						}
				}
		}

	if (sm_action('change'))
		{
			$m["module"] = 'account';
			$m["title"] = $lang['change'];
			if ($userinfo['level'] == 3 && !empty($_getvars['usrid']))
				{
					if (strcmp($_getvars['usrid'], $userinfo['id']) != 0)
						$m['change_to_other'] = 1;
					$m["extended_groups"] = 1;
					$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user = ".intval($_getvars['usrid']);
				}
			else
				{
					$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user = ".intval($userinfo['id']);
				}
			$result = execsql($sql);
			$u = 0;
			while ($row = database_fetch_object($result))
				{
					$m['user_id'] = $row->id_user;
					$m['user_login'] = $row->login;
					$m['user_email'] = $row->email;
					$m['user_question'] = $row->question;
					$m['user_answer'] = $row->answer;
					$m['user_get_mail'] = $row->get_mail;
					$m['user_groups'] = get_array_groups($row->groups_user);
				}
			if ($m["extended_groups"] == 1)
				{
					$m['groups_all'] = get_groups_list();
				}
			sm_event('onchreginfo', array($m['user_id']));
			sm_page_viewid('account-change');
		}
	if (sm_action('logout'))
		{
			$m["module"] = 'account';
			$m["title"] = $lang["logout"];
			sm_extcore();
			sm_logout();
			include('includes/userinfo.php');
			setcookie($_settings['cookprefix'].'simanautologin', '');
			if (!empty($_settings['redirect_after_logout']))
				$refresh_url = $_settings['redirect_after_logout'];
			else
				$refresh_url = 'http://'.$_settings['resource_url'];
		}
	if (sm_action('cabinet') && !empty($userinfo['id']))
		{
			$m["module"] = 'account';
			$m["title"] = $lang["my_cabinet"];
			if (!empty($userinfo['id']))
				{
					$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user='".$userinfo['id']."'";
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$m['notebook_text'] = $row->notebook;
						}
					if (!empty($_settings['users_menu_id']))
						{
							$sql = "SELECT * FROM ".$tableusersprefix."menu_lines WHERE id_menu_ml='".$_settings['users_menu_id']."' ORDER BY position";
							$result = execsql($sql);
							$i = 0;
							while ($row = database_fetch_object($result))
								{
									$m['userlinks'][$i]['url'] = $row->url;
									$m['userlinks'][$i]['title'] = $row->caption_ml;
									$i++;
								}
							$m['userlinkcount'] = $i;
						}
					if ($_settings['allow_private_messages'] == 1)
						$m['privmsgdata'] = siman_get_privmsgcount($userinfo['id']);
				}
			sm_page_viewid('account-cabinet');
		}
	if (sm_action('savenbook') && !empty($userinfo['id']))
		{
			$m["module"] = 'account';
			$m["title"] = $lang['module_account']['notebook'];
			if (!empty($userinfo['id']))
				{
					$nb_text = dbescape($_postvars['p_notebook']);
					$nb_text = str_replace("<", '&lt;', $nb_text);
					$nb_text = str_replace(">", '&gt;', $nb_text);
					$nb_text = str_replace("&", '&amp;', $nb_text);
					$sql = "UPDATE ".$tableusersprefix."users SET notebook = '$nb_text' WHERE id_user='".$userinfo['id']."'";
					$result = execsql($sql);
				}
			$refresh_url = 'index.php?m=account&d=cabinet';
		}
	if (sm_action('postlogin'))
		{
			$m["module"] = 'account';
			$m["title"] = $lang["login_caption"];
		}
	if ($userinfo['level'] == 3)
		include('modules/inc/adminpart/account.php');

?>