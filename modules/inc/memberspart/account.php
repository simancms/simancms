<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.3	                                                               |
//#revision 2012-07-21                                                         |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if (!defined("ACCOUNT_MEMBERS_FUNCTIONS_DEFINED"))
	{
		function siman_get_userid_by_name($name)
			{
				global $nameDB, $lnkDB, $tableusersprefix, $_settings;
				$sql="SELECT * FROM ".$tableusersprefix."users WHERE lower(login)='$name' LIMIT 1";
				$result=execsql($sql);
				$id=0;
				while ($row=database_fetch_object($result))
					{
						$id=$row->id_user;
					}
				return $id;
		    }
		
		function siman_get_privmsgcount($user)
			{
				global $nameDB, $lnkDB, $tableusersprefix, $tableprefix;
				$result['inbox']['all']=0;
				$result['inbox']['unread']=0;
				$result['outbox']['all']=0;
				$sql="SELECT count(*), sum(unread_privmsg), sum(folder_privmsg) FROM ".$tableusersprefix."privmsg WHERE folder_privmsg=1 AND id_sender_privmsg=$user OR folder_privmsg=0 AND id_recipient_privmsg=$user";
				$rezult=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_row($rezult))
					{
						$result['inbox']['all']=$row[0];
						$result['inbox']['unread']=$row[1];
						$result['outbox']['all']=$row[2];
					}
				return $result;
			}
		
		define("ACCOUNT_MEMBERS_FUNCTIONS_DEFINED", 1);
	}

if (strcmp($m['mode'], "postchange")==0)
	{
		$m["module"]='account';
		$m["title"]=$lang['change'];
		$old_password=dbescape($_postvars["p_old_password"]);
		$password=dbescape($_postvars["p_password"]);
		$password2=dbescape($_postvars["p_password2"]);
		$email=$_postvars["p_email"];
		$question=dbescape($_postvars["p_question"]);
		$get_mail=intval($_postvars["p_get_mail"]);
		$answer=dbescape($_postvars["p_answer"]);
		if (empty($email) || (!empty($question) && empty($answer)))
		  {
			$m['message']=$lang["message_set_all_fields"];
			$m['mode']='change';
			$m['user_email']=$email;
			$m['user_question']=$question;
			$m['user_answer']=$answer;
			$m['user_get_mail']=$get_mail;
		  }
		if ($userinfo['level']==3 && !empty($_postvars['p_user_id']))
			{
				$password=md5($password);
			  	$sqlpasswd=", password = '$password' ";
			}
		else
		  	$sqlpasswd='';
		if (!empty($password) && empty($sqlpasswd))
		  {
			if (strcmp($password,$password2)!=0)
			  {
				$m['message']=$lang["message_passwords_not_equal"];
				$m['mode']='change';
				$m['user_email']=$email;
				$m['user_question']=$question;
				$m['user_answer']=$answer;
				$m['user_get_mail']=$get_mail;
			  }
			else
			  {
				$password=md5($password);
			  	$sqlpasswd=", password = '$password' ";
			  }
		  }
		if (!is_email($email))
		  {
			$m['message']=$lang["message_bad_email"];
			$m['mode']='change';
			$m['user_login']=$login;
			$m['user_email']=$email;
			$m['user_question']=$question;
			$m['user_answer']=$answer;
			$m['user_get_mail']=$get_mail;
		  }
		if (strcmp($m['mode'], 'change')!=0)
		  {
			if ($userinfo['level']==3 && !empty($_postvars['p_user_id']))
				{
					$sql="SELECT * FROM ".$tableusersprefix."users WHERE id_user = '".$_postvars['p_user_id']."'";
				}
			else
				{
					$sql="SELECT * FROM ".$tableusersprefix."users WHERE id_session = '".$userinfo['session']."'";
					if (!empty($old_password))
						{
							$sql.=" AND password = '".md5($old_password)."'";
						}
				}
			$result=execsql($sql);
			$u=0;
			while ($row=database_fetch_object($result))
				{
					$u=1;
				}
			if ($u==0)
			  {
				$m['message']=$lang["error"];
				$m['mode']='change';
				$m['user_email']=$email;
				$m['user_question']=$question;
				$m['user_answer']=$answer;
				$m['user_get_mail']=$get_mail;
			  }
			else
			  {
				$sql="UPDATE ".$tableusersprefix."users SET email = '$email', question = '$question', answer = '$answer', get_mail = '$get_mail' $sqlpasswd ";
				if ($userinfo['level']==3 && !empty($_postvars['p_user_id']))
					{
						$sql.=" WHERE  id_user = '".intval($_postvars['p_user_id'])."'";
						$id_newuser=intval($_postvars['p_user_id']);
					}
				else
					{
						$sql.=" WHERE  id_user = '".intval($userinfo['id'])."' AND id_session = '".$userinfo['session']."'";
						$id_newuser=intval($userinfo['id']);
					}
				$result=execsql($sql);
				sm_event('userdetailschanged', array($id_newuser));
				if ($id_newuser==$userinfo['id'])
					{
						sm_login($id_newuser);
						include('includes/userinfo.php');
					}
				if ($userinfo['level']==3 && !empty($_postvars['p_user_id']))
					{
						if (!empty($_getvars['returnto']))
							sm_redirect($_getvars['returnto']);
						else
							sm_redirect('index.php?m=account&d=usrlist');
					}
				else
					{
						$m['mode']='successchange';
						$refresh_url='index.php?m=account&d=cabinet';
					}
			  }
		  }
	}

if (strcmp($m['mode'], 'change')==0)
	{
		$m["module"]='account';
		$m["title"]=$lang['change'];
		if ($userinfo['level']==3 && !empty($_getvars['usrid']))
			{
				if (strcmp($_getvars['usrid'], $userinfo['id'])!=0)
					$m['change_to_other']=1;
				$m["extended_groups"]=1;
				$sql="SELECT * FROM ".$tableusersprefix."users WHERE id_user = '".$_getvars['usrid']."'";
			}
		else
			{
				$sql="SELECT * FROM ".$tableusersprefix."users WHERE id_user = '".$userinfo['id']."' AND id_session = '".$userinfo['session']."' AND login = '".dbescape($userinfo['login'])."' ";
			}
		$result=execsql($sql);
		$u=0;
		while ($row=database_fetch_object($result))
			{
				$m['user_id']=$row->id_user;
				$m['user_login']=$row->login;
				$m['user_email']=$row->email;
				$m['user_question']=$row->question;
				$m['user_answer']=$row->answer;
				$m['user_get_mail']=$row->get_mail;
				$m['user_groups']=get_array_groups($row->groups_user);
			}
		if ($m["extended_groups"]==1)
			{
				$m['groups_all']=get_groups_list();
			}
		sm_event('onchreginfo', array($m['user_id']));
		sm_page_viewid('account-change');
	}
if (strcmp($m['mode'], 'logout')==0)
	{
		$m["module"]='account';
		$m["title"]=$lang["logout"];
		sm_extcore();
		sm_logout();
		include('includes/userinfo.php');
		setcookie($_settings['cookprefix'].'simanautologin', '');
		if (!empty($_settings['redirect_after_logout']))
			$refresh_url=$_settings['redirect_after_logout'];
		else
			$refresh_url='http://'.$_settings['resource_url'];
	}
if (strcmp($m['mode'], 'cabinet')==0 && !empty($userinfo['id']))
	{
		$m["module"]='account';
		$m["title"]=$lang["my_cabinet"];
		if (!empty($userinfo['id']))
			{
				$sql="SELECT * FROM ".$tableusersprefix."users WHERE id_user='".$userinfo['id']."'";
				$result=execsql($sql);
				while ($row=database_fetch_object($result))
					{
						$m['notebook_text']=$row->notebook;
					}
				if (!empty($_settings['users_menu_id']))
					{
						$sql="SELECT * FROM ".$tableusersprefix."menu_lines WHERE id_menu_ml='".$_settings['users_menu_id']."' ORDER BY position";
						$result=execsql($sql);
						$i=0;
						while ($row=database_fetch_object($result))
							{
								$m['userlinks'][$i]['url']=$row->url;
								$m['userlinks'][$i]['title']=$row->caption_ml;
								$i++;
							}
						$m['userlinkcount']=$i;
					}
				$m['privmsgdata']=siman_get_privmsgcount($userinfo['id']);
			}
		sm_page_viewid('account-cabinet');
	}
if (strcmp($m['mode'], 'savenbook')==0 && !empty($userinfo['id']))
	{
		$m["module"]='account';
		$m["title"]=$lang['module_account']['notebook'];
		if (!empty($userinfo['id']))
			{
				$nb_text=dbescape($_postvars['p_notebook']);
				$nb_text=str_replace("<", '&lt;', $nb_text);
				$nb_text=str_replace(">", '&gt;', $nb_text);
				$nb_text=str_replace("&", '&amp;', $nb_text);
				$sql="UPDATE ".$tableusersprefix."users SET notebook = '$nb_text' WHERE id_user='".$userinfo['id']."'";
				$result=execsql($sql);
			}
		$refresh_url='index.php?m=account&d=cabinet';
	}
if (strcmp($m['mode'], 'viewprivmsg')==0 && $_settings['allow_private_messages']==1 && !empty($userinfo['id']))
	{
		sm_page_viewid('account-viewprivmsg');
		$m["module"]='account';
		if (empty($_getvars['folder']))
			$_getvars['folder']='inbox';
		$m["privmsg_folder"]=$_getvars['folder'];
		$tmp_folder=$_getvars['folder'];
		if (strcmp($tmp_folder, 'outbox')==0)
			{
				$m["title"]=$lang['module_account']['outbox'];
				$tmp_filter=' folder_privmsg=1 AND id_sender_privmsg='.$userinfo['id'];
			}
		if (empty($tmp_filter))
			{
				$m["title"]=$lang['module_account']['inbox'];
				$tmp_filter=' folder_privmsg=0 AND id_recipient_privmsg='.$userinfo['id'];
				$tmp_folder='inbox';
			}
		$sql="SELECT * FROM ".$tableusersprefix."privmsg, ".$tableusersprefix."users ";
		if (strcmp($tmp_folder, 'outbox')==0)
			$sql.=" WHERE ".$tableusersprefix."privmsg.id_sender_privmsg=".$tableusersprefix."users.id_user";
		else
			$sql.=" WHERE ".$tableusersprefix."privmsg.id_recipient_privmsg=".$tableusersprefix."users.id_user";
		$sql.=' AND '.$tmp_filter;
		$sql.=' ORDER BY time_privmsg DESC ';
		require_once('includes/admintable.php');
		$m['table']['columns']['ico']['caption']='';
		$m['table']['columns']['ico']['width']='16';
		$m['table']['columns']['ico']['align']='center';
		$m['table']['columns']['title']['caption']=$lang['common']['title'];
		$m['table']['columns']['title']['width']='100%';
		$m['table']['columns']['time']['caption']=$lang['module_account']['sended'];
		$m['table']['columns']['time']['nobr']=1;
		$m['table']['columns']['user']['caption']=$lang['user'];
		$m['table']['columns']['delete']['caption']='';
		$m['table']['columns']['delete']['hint']=$lang['common']['delete'];
		$m['table']['columns']['delete']['replace_text']=$lang['common']['delete'];
		$m['table']['columns']['delete']['replace_image']='delete.gif';
		$m['table']['columns']['delete']['width']='16';
		$m['table']['columns']['delete']['messagebox']=1;
		$m['table']['columns']['delete']['messagebox_text']=addslashes($lang['module_account']['really_want_delete_message']);
		$m['table']['default_column']='title';
		$result=execsql($sql);
		$i=0;
		while ($row=database_fetch_object($result))
			{
				if ($row->folder_privmsg==0 && $row->unread_privmsg==1)
					$m['table']['rows'][$i]['ico']['image']='newmessage.gif';
				else
					$m['table']['rows'][$i]['ico']['image']='message.gif';
				$m['table']['rows'][$i]['title']['data']=$row->theme_privmsg;
				$m['table']['rows'][$i]['title']['url']='index.php?m=account&d=readprivmsg&id='.$row->id_privmsg.'&folder='.$tmp_folder;
				$m['table']['rows'][$i]['user']['data']=$row->login;
				$m['table']['rows'][$i]['time']['data']=strftime($lang["datetimemask"], $row->time_privmsg);
				$m['table']['rows'][$i]['delete']['url']='index.php?m=account&d=postdeleteprivmsg&id='.$row->id_privmsg.'&folder='.$tmp_folder;
				$i++;
			}
	}
if (strcmp($m['mode'], 'postsendprivmsg')==0 && $_settings['allow_private_messages']==1 && !empty($userinfo['id']))
	{
		$m["module"]='account';
		$m["title"]=$lang['module_account']['send_message'];
		$m['data']['recipient']=htmlspecialchars($_postvars['p_recipient']);
		$m['data']['theme']=htmlspecialchars($_postvars['p_theme']);
		$m['data']['text']=htmlspecialchars($_postvars['p_text']);
		if (empty($_postvars['p_recipient']))
			{
				$m['mode']='sendprivmsg';
				$m['error_message']=$lang['module_account']['error_message_recipient'];
			}
		elseif (empty($_postvars['p_theme']) || empty($_postvars['p_text']))
			{
				$m['mode']='sendprivmsg';
				$m['error_message']=$lang['module_account']['error_message_theme_text'];
			}
		elseif (siman_get_userid_by_name(dbescape($_postvars['p_recipient']))<1)
			{
				$m['mode']='sendprivmsg';
				$m['error_message']=$lang['module_account']['error_message_recipient'];
			}
		else
			{
				$id_sender_privmsg=$userinfo['id'];
				$id_recipient_privmsg=siman_get_userid_by_name(dbescape($_postvars['p_recipient']));
				$folder_privmsg=0;
				$unread_privmsg=1;
				$theme_privmsg=dbescape($_postvars['p_theme']);
				$body_privmsg=dbescape($_postvars['p_text']);
				$time_privmsg=time();
				$sql="INSERT INTO ".$tableusersprefix."privmsg (`id_sender_privmsg`, `id_recipient_privmsg`, `folder_privmsg`, `unread_privmsg`, `theme_privmsg`, `body_privmsg`, `time_privmsg`) VALUES('$id_sender_privmsg', '$id_recipient_privmsg', '$folder_privmsg', '$unread_privmsg', '$theme_privmsg', '$body_privmsg', '$time_privmsg')";
				$result=execsql($sql);
				$folder_privmsg=1;
				$unread_privmsg=0;
				$sql="INSERT INTO ".$tableusersprefix."privmsg (`id_sender_privmsg`, `id_recipient_privmsg`, `folder_privmsg`, `unread_privmsg`, `theme_privmsg`, `body_privmsg`, `time_privmsg`) VALUES('$id_sender_privmsg', '$id_recipient_privmsg', '$folder_privmsg', '$unread_privmsg', '$theme_privmsg', '$body_privmsg', '$time_privmsg')";
				$result=execsql($sql);
				log_write(LOG_USEREVENT, $lang['module_account']['log']['user_send_privmsg']);
				$refresh_url='index.php?m=account&d=viewprivmsg&folder=inbox';
			}
	}
if (strcmp($m['mode'], 'sendprivmsg')==0 && $_settings['allow_private_messages']==1 && !empty($userinfo['id']))
	{
		sm_page_viewid('account-sendprivmsg');
		$m["module"]='account';
		$m["title"]=$lang['module_account']['send_message'];
		$m['data']['recipient']=htmlspecialchars($_postvars['p_recipient']);
		$m['data']['theme']=htmlspecialchars($_postvars['p_theme']);
		$m['data']['text']=htmlspecialchars($_postvars['p_body']);
	}
if (strcmp($m['mode'], 'readprivmsg')==0 && $_settings['allow_private_messages']==1 && !empty($userinfo['id']))
	{
		sm_page_viewid('account-readprivmsg');
		$m["module"]='account';
		$m["title"]=$lang['module_account']['read_message'];
		if (empty($_getvars['folder']))
			$_getvars['folder']='inbox';
		$tmp_folder=$_getvars['folder'];
		$m["folder_privmsg"]=$_getvars['folder'];
		$sql="SELECT * FROM ".$tableusersprefix."privmsg, ".$tableusersprefix."users ";
		if (strcmp($tmp_folder, 'outbox')==0)
			$sql.=" WHERE ".$tableusersprefix."privmsg.id_recipient_privmsg=".$tableusersprefix."users.id_user AND id_sender_privmsg=".intval($userinfo['id']);
		else
			$sql.=" WHERE ".$tableusersprefix."privmsg.id_sender_privmsg=".$tableusersprefix."users.id_user AND id_recipient_privmsg=".intval($userinfo['id']);
		$tmp_filter=' id_privmsg='.intval($_getvars['id']);
		$sql.=' AND '.$tmp_filter;
		$result=execsql($sql);
		$i=0;
		while ($row=database_fetch_object($result))
			{
				$m['message']['id']=$row->id_privmsg;
				$m['message']['theme']=$row->theme_privmsg;
				$m['message']['body']=nl2br($row->body_privmsg);
				$m['message']['time']=strftime($lang["datetimemask"], $row->time_privmsg);
				$m['message']['user']=$row->login;
				$m['message']['rebody']="-------------------\n&gt; ".str_replace("\n", "\n&gt; ", $row->body_privmsg);
				$i++;
			}
		if ($i==1)
			{
				$sql=" UPDATE ".$tableusersprefix."privmsg SET unread_privmsg=0 WHERE id_privmsg=".intval($_getvars['id']);
				$result=execsql($sql);
			}
	}
if (strcmp($m['mode'], 'postdeleteprivmsg')==0 && $_settings['allow_private_messages']==1 && !empty($userinfo['id']))
	{
		$m["module"]='account';
		$m["title"]=$lang['common']['delete'];
		$id=$_getvars['id'];
		$user=intval($userinfo['id']);
		$sql="DELETE FROM ".$tableusersprefix."privmsg WHERE id_privmsg=$id and (folder_privmsg=1 and id_sender_privmsg=$user or folder_privmsg=0 and id_recipient_privmsg=$user)";
		$result=execsql($sql);
		$refresh_url='index.php?m=account&d=viewprivmsg&folder='.$_getvars['folder'];
	}
if (strcmp($m['mode'], 'postlogin')==0)
	{
		$m["module"]='account';
		$m["title"]=$lang["login_caption"];
	}

if ($userinfo['level']==3)
	include('modules/inc/adminpart/account.php');

?>