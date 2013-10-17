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

	if ($userinfo['level'] == 3)
		{
			if (sm_action('delete'))
				{
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['delete'];
					$_msgbox['msg'] = $lang['really_want_delete_user'];
					$_msgbox['yes'] = 'index.php?m=account&d=postdelete&uid='.$_getvars["uid"];
					$_msgbox['no'] = 'index.php?m=account&d=usrlist';
				}
			if (sm_action('postdelete'))
				{
					$id_user = intval($_getvars['uid']);
					$sql = "DELETE FROM ".$tableusersprefix."users WHERE id_user=$id_user AND id_user>1";
					$result = execsql($sql);
					sm_event('deleteuser', array($id_user));
					sm_redirect($_getvars['returnto']);
				}
			if (sm_action('setstatus'))
				{
					$status = $_getvars['status'];
					$id_user = $_getvars['uid'];
					$sql = "UPDATE ".$tableusersprefix."users SET user_status = '$status' WHERE id_user='$id_user' AND id_user>1";
					$result = execsql($sql);
					sm_redirect($_getvars['returnto']);
				}
			if (sm_action('usrlist'))
				{
					add_path_control();
					add_path($lang["user_list"], 'index.php?m=account&d=usrlist');
					$m["module"] = 'account';
					$m["title"] = $lang["user_list"];
					if (empty($_getvars['sellogin']))
						$_getvars['sellogin'] = $_postvars['sellogin'];
					include_once('includes/admininterface.php');
					include_once('includes/admintable.php');
					include_once('includes/adminbuttons.php');
					$ui = new TInterface();
					$b = new TButtons();
					$b->AddButton('add', $lang['register_user'], 'index.php?m=account&d=register');
					$ui->AddButtons($b);
					$t = new TGrid();
					$limit = $_settings['admin_items_by_page'];
					$offset = intval($_getvars['from']);
					$t->AddCol('user', $lang['user'], '60%');
					$t->AddCol('status', $lang['status'], '20%');
					$t->AddCol('action', $lang['action'], '20%');
					$sql = "SELECT * FROM ".$tableusersprefix."users";
					$sql2 = "SELECT count(*) FROM ".$tableusersprefix."users";
					if (!empty($_getvars['sellogin']))
						{
							$sql .= " WHERE login LIKE '".dbescape($_getvars['sellogin'])."%'";
							$sql2 .= " WHERE login LIKE '".dbescape($_getvars['sellogin'])."%'";
						}
					$sql .= " ORDER BY login";
					$sql .= " LIMIT ".$limit;
					$sql .= " OFFSET ".$offset;
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$t->Label('user', $row->login.'<br />'.$row->email);
							if ($row->user_status == 0)
								$t->Label('status', $lang['module_account']['unactivated_user']);
							elseif ($row->user_status == 1)
								$t->Label('status', $lang['normal_user']);
							elseif ($row->user_status == 2)
								$t->Label('status', $lang['privileged_user']);
							elseif ($row->user_status == 3)
								$t->Label('status', $lang['super_user']);
							if ($row->user_status != 0)
								$t->DropDownItem('status', $lang['module_account']['unactivated_user'], 'index.php?m=account&d=setstatus&uid='.$row->id_user.'&status=0&returnto='.urlencode(sm_this_url()));
							if ($row->user_status != 1)
								$t->DropDownItem('status', $lang['normal_user'], 'index.php?m=account&d=setstatus&uid='.$row->id_user.'&status=1&returnto='.urlencode(sm_this_url()));
							if ($row->user_status != 2)
								$t->DropDownItem('status', $lang['privileged_user'], 'index.php?m=account&d=setstatus&uid='.$row->id_user.'&status=2&returnto='.urlencode(sm_this_url()));
							if ($row->user_status != 3)
								$t->DropDownItem('status', $lang['super_user'], 'index.php?m=account&d=setstatus&uid='.$row->id_user.'&status=3&returnto='.urlencode(sm_this_url()));
							$t->Label('action', $lang['details']);
							$t->DropDownItem('action', $lang['module_account']['set_password'], 'index.php?m=account&d=setpwd&uid='.$row->id_user.'&returnto='.urlencode(sm_this_url()));
							$t->DropDownItem('action', $lang['common']['edit'], 'index.php?m=account&d=change&usrid='.$row->id_user.'&returnto='.urlencode(sm_this_url()));
							$t->DropDownItem('action', $lang['delete'], 'index.php?m=account&d=postdelete&uid='.$row->id_user.'&returnto='.urlencode(sm_this_url()), $lang['really_want_delete_user']);
							$t->NewRow();
							$i++;
						}
					$ui->AddGrid($t);
					$ui->div('<form action="index.php"><input type="hidden" name="m" value="account"><input type="hidden" name="d" value="usrlist">'.$lang['search'].': <input type="text" name="sellogin" value="'.htmlspecialchars($sm['g']['sellogin']).'"></form>');
					$ui->AddButtons($b);
					$m['pages']['url'] = sm_this_url('from', '');
					$m['pages']['selected'] = ceil(($offset + 1) / $limit);
					$m['pages']['interval'] = $limit;
					$m['pages']['records'] = intval(getsqlfield($sql2));
					$m['pages']['pages'] = ceil($m['pages']['records'] / $limit);
					$ui->AddPagebar('');
					$ui->Output(true);
				}
			if (sm_action('setpwd'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['module_account']['set_password'];
					$sql = "SELECT * FROM ".$tableusersprefix."users WHERE id_user=".intval($_getvars['uid']);
					$result = execsql($sql);
					while ($row = database_fetch_object($result))
						{
							$m['user']['id'] = $row->id_user;
							$m['user']['login'] = $row->login;
							$m['user']['email'] = $row->email;
							$m['user']['status'] = $row->user_status;
						}
				}
			if (sm_action('postsetpwd'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['module_account']['set_password'];
					$password = md5($_postvars['p_newpwd']);
					$id_user = intval($_postvars['p_iduser']);
					$random_code = md5($id_user.microtime().rand());
					$sql = "UPDATE ".$tableusersprefix."users SET password = '$password', random_code='".$random_code."' WHERE id_user='$id_user' AND id_user>1";
					$result = execsql($sql);
					sm_redirect($_getvars['returnto']);
				}
			if (sm_action('listgroups'))
				{
					add_path_control();
					add_path($lang['module_account']['groups'], 'index.php?m=account&d=listgroups');
					$m['module'] = 'account';
					$m['title'] = $lang['module_account']['groups'];
					require_once('includes/admintable.php');
					include_once('includes/admininterface.php');
					include_once('includes/adminbuttons.php');
					$ui = new TInterface();
					$t=new TGrid();
					$t->AddCol('title', $lang['common']['title'], '100%');
					$t->AddEdit();
					$t->AddDelete();
					$sql = 'SELECT * FROM '.$tableusersprefix.'groups ORDER BY title_group';
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_object($result))
						{
							$t->Label('title', $row->title_group);
							$t->Hint('title', htmlspecialchars($row->description_group));
							$t->URL('edit', 'index.php?m=account&d=editgroup&id='.$row->id_group);
							$t->URL('delete', 'index.php?m=account&d=postdeletegroup&id='.$row->id_group);
							$t->NewRow();
							$i++;
						}
					$b=new TButtons();
					$b->AddButton('add', $lang['module_account']['add_group'], 'index.php?m=account&d=addgroup');
					$ui->AddButtons($b);
					$ui->AddGrid($t);
					$ui->AddButtons($b);
					$ui->Output(true);
				}
			if (sm_action('addgroup'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['module_account']['add_group'];
					require_once('includes/adminform.php');
					add_path_control();
					add_path($lang['module_account']['groups_management'], 'index.php?m=account&d=listgroups');
					$f = new TForm('index.php?m=account&d=postaddgroup');
					$f->AddText('title_group', $lang['module_account']['title_group']);
					$f->AddTextarea('description_group', $lang['module_account']['description_group']);
					$f->AddCheckbox('autoaddtousers_group', $lang['module_account']['add_to_new_users'], 1);
					$m['form'] = $f->Output();
				}
			if (sm_action('editgroup'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['common']['edit'];
					require_once('includes/adminform.php');
					$f = new TForm('index.php?m=account&d=posteditgroup&id='.$_getvars['id']);
					$f->AddText('title_group', $lang['module_account']['title_group']);
					$f->AddTextarea('description_group', $lang['module_account']['description_group']);
					$f->AddCheckbox('autoaddtousers_group', $lang['module_account']['add_to_new_users'], 1);
					$f->LoadValues('SELECT * FROM '.$tableusersprefix.'groups WHERE id_group='.intval($_getvars['id']));
					$m['form'] = $f->Output();
				}
			if (sm_action('postaddgroup'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['module_account']['add_group'];
					$refresh_url = 'index.php?m=account&d=listgroups';
					$q = new TQuery($tableusersprefix.'groups');
					$q->AddPost('title_group');
					$q->AddPost('description_group');
					$q->Add('autoaddtousers_group', intval($_postvars['autoaddtousers_group']));
					$q->Insert();
					sm_redirect('index.php?m=account&d=listgroups');
				}
			if (sm_action('posteditgroup'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['common']['edit'];
					$q = new TQuery($tableusersprefix.'groups');
					$q->AddPost('title_group');
					$q->AddPost('description_group');
					$q->Add('autoaddtousers_group', intval($_postvars['autoaddtousers_group']));
					$q->Update('id_group', intval($_getvars['id']));
					sm_redirect('index.php?m=account&d=listgroups');
				}
			if (sm_action('postdeletegroup'))
				{
					$m['module'] = 'account';
					$m['title'] = $lang['common']['delete'];
					exec_sql_delete($tableusersprefix.'groups', 'id_group', $_getvars['id']);
					$refresh_url = 'index.php?m=account&d=listgroups';
				}
			if (sm_action('postchangegrp'))
				{
					$m["module"] = 'account';
					$m["title"] = $lang['change'];
					$tmp_usrid = $_postvars['p_user_id'];
					$grps = create_groups_str($_postvars['p_groups']);
					$sql = "UPDATE ".$tableusersprefix."users SET groups_user = '$grps' WHERE id_user='".$tmp_usrid.'\'';
					$result = execsql($sql);
					$refresh_url = 'index.php?m=account&d=change&usrid='.$tmp_usrid;
				}
		}

?>