<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Download
	Module URI: http://simancms.org/modules/download/
	Description: Downloads management module. Base CMS module
	Version: 1.6.5
	Revision: 2013-10-10
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if (empty($m["mode"]))
		$m["mode"] = 'view';

	if (sm_action('attachment', 'showattachedfile'))
		{
			$att = getsql("SELECT * FROM ".$tableprefix."downloads WHERE userlevel_download<=".intval($userinfo['id'])." AND id_download=".intval($_getvars['id']));
			if (!empty($att['id_download']) && file_exists('files/download/attachment'.intval($_getvars['id'])))
				{
					$m["module"] = 'download';
					$special['main_tpl'] = '';
					$special['no_blocks'] = 1;
					header("Content-type: ".$att['attachment_type']);
					if (strcmp($m["mode"], 'showattachedfile') != 0)
						header("Content-Disposition: attachment; filename=".basename($att['file_download']));
					$fp = fopen('files/download/attachment'.intval($_getvars['id']), 'rb');
					fpassthru($fp);
					fclose($fp);
				}
		}

	if ($userinfo['level'] == 3)
		{
			$m["module"] = 'download';
			if (strcmp($m["mode"], 'deleteattachment') == 0)
				{
					$m["module"] = 'download';
					$m['title'] = $lang['common']['delete'];
					$_msgbox['mode'] = 'yesno';
					$_msgbox['title'] = $lang['common']['delete'];
					$_msgbox['msg'] = $lang['module_download']['really_want_delete_file'];
					$_msgbox['yes'] = 'index.php?m=download&d=postdeleteattachment&id='.$_getvars["id"];
					$_msgbox['no'] = 'index.php?m=download';
				}
			if (strcmp($m["mode"], 'postdeleteattachment') == 0)
				{
					$m["module"] = 'download';
					$m['title'] = $lang['common']['delete'];
					sm_delete_attachment(intval($_getvars['id']));
					sm_event('postdeleteattachment', array(intval($_getvars['id'])));
					if (!empty($_getvars['returnto']))
						sm_redirect($_getvars['returnto']);
					else
						sm_redirect('index.php?m=download&d=view');
				}
			if (sm_action('postdelete'))
				{
					$q=new TQuery('sm_downloads');
					$q->Add('id_download', intval($_getvars['id']));
					$info=$q->Get();
					$filename='files/download/'.basename($info['file_download']);
					$q->Remove();
					sm_extcore();
					if (file_exists($filename))
						unlink($filename);
					sm_saferemove($filename);
					//sm_notify($lang['module_download']['delete_file_successful']);
					sm_redirect($_getvars['returnto']);
				}
			if (strcmp($m["mode"], 'postadd') == 0)
				{
					$descr = dbescape($_postvars['p_shortdesc']);
					$fs = $_uplfilevars['userfile']['tmp_name'];
					if (empty($_postvars['p_optional']))
						{
							$fd = basename($_uplfilevars['userfile']['name']);
						}
					else
						{
							$fd = basename($_postvars['optional_name']);
						}
					$fd = './files/download/'.$fd;
					if (file_exists($fd))
						{
							$error = $lang['module_download']['file_already_exists'];
							$m['mode'] = 'add';
						}
					elseif (!move_uploaded_file($fs, $fd))
						{
							$error=$lang['error_file_upload_message'];
							$m['mode'] = 'add';
						}
					else
						{
							$q=new TQuery($sm['t'].'downloads');
							$q->Add('file_download', dbescape(basename($fd)));
							$q->Add('description_download', dbescape($_postvars['description_download']));
							$q->Add('userlevel_download', intval($_postvars['userlevel_download']));
							$q->Insert();
							//sm_notify($lang['operation_complete']);
							if (!empty($_getvars['returnto']))
								sm_redirect($_getvars['returnto']);
							else
								sm_redirect('index.php?m=download&d=view');
						}
				}
			if (sm_action('postupload'))
				{
					$fs = $_uplfilevars['userfile']['tmp_name'];
					$fd = './files/download/'.$fd;
					if (!file_exists($fs))
						{
							$error=$lang['error_file_upload_message'];
							$m['mode'] = 'upload';
						}
					else
						{
							//need to finish here
							if (!move_uploaded_file($fs, $fd))
								{
									$error=$lang['error_file_upload_message'];
									$m['mode'] = 'upload';
								}
							else
								{
									$q=new TQuery($sm['t'].'downloads');
									$q->Add('file_download', dbescape(basename($fd)));
									$q->Add('description_download', dbescape($_postvars['description_download']));
									$q->Add('userlevel_download', intval($_postvars['userlevel_download']));
									$q->Insert();
									//sm_notify($lang['operation_complete']);
									if (!empty($_getvars['returnto']))
										sm_redirect($_getvars['returnto']);
									else
										sm_redirect('index.php?m=download&d=view');
								}
						}
				}
			if (sm_action('upload'))
				{
					add_path_modules();
					add_path($lang['module_download']['downloads'], 'index.php?m=download&d=admin');
					add_path($lang['module_download']['downloads'], 'index.php?m=download&d=list');
					add_path_current();
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					if (!empty($error))
						$ui->div($error, '', 'error alert-error errormessage error-message');
					sm_title($lang['module_download']['upload_file']);
					$f=new TForm('index.php?m=download&d=postupload&returnto='.urlencode($_getvars['returnto']));
					$f->AddFile('userfile', $lang['file_name']);
					$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('userfile');
				}
			if (sm_action('add'))
				{
					add_path_modules();
					add_path($lang['module_download']['downloads'], 'index.php?m=download&d=admin');
					add_path($lang['module_download']['downloads'], 'index.php?m=download&d=list');
					add_path_current();
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					if (!empty($error))
						$ui->div($error, '', 'error alert-error errormessage error-message');
					sm_title($lang['module_download']['upload_file']);
					$f=new TForm('index.php?m=download&d=postadd&returnto='.urlencode($_getvars['returnto']));
					$f->AddFile('userfile', $lang['file_name']);
					$f->AddText('optional_name', $lang['optional_file_name']);
					$f->AddTextarea('description_download', $lang['module_download']['short_description_download']);
					$f->AddSelectVL('userlevel_download', $lang['can_view'], Array(0, 1, 2, 3), Array($lang['all_users'], $lang['logged_users'], $lang['power_users'], $lang['administrators']));
					$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('file_download');
				}
			if (sm_action('list'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					include_once('includes/admintable.php');
					include_once('includes/adminbuttons.php');
					add_path_modules();
					add_path($lang['module_download']['downloads'], 'index.php?m=download&d=admin');
					add_path_current();
					sm_title($lang['module_download']['downloads']);
					$offset=abs(intval($_getvars['from']));
					$limit=30;
					$ui = new TInterface();
					$b=new TButtons();
					$b->AddButton('add', $lang['common']['add'], 'index.php?m=download&d=add&returnto='.urlencode(sm_this_url()));
					$ui->AddButtons($b);
					$t=new TGrid();
					$t->AddCol('file_download', $lang['file_name']);
					$t->AddCol('description_download', $lang['common']['description']);
					$t->AddCol('userlevel_download', $lang['can_view']);
					$t->AddCol('upload', $lang['module_download']['upload_file']);
					$t->AddEdit();
					$t->AddDelete();
					$q=new TQuery($sm['t'].'downloads');
					$q->AddWhere('attachment_from', '-');
					$q->OrderBy('file_download');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					for ($i = 0; $i<count($q->items); $i++)
						{
							$t->Label('file_download', $q->items[$i]['file_download']);
							$t->URL('file_download', 'files/download/'.$q->items[$i]['file_download'], true);
							$t->Label('description_download', $q->items[$i]['description_download']);
							if ($q->items[$i]['userlevel_download']==0)
								$t->Label('userlevel_download', $lang['all_users']);
							elseif ($q->items[$i]['userlevel_download']==1)
								$t->Label('userlevel_download', $lang['logged_users']);
							elseif ($q->items[$i]['userlevel_download']==2)
								$t->Label('userlevel_download', $lang['power_users']);
							else
								$t->Label('userlevel_download', $lang['administrators']);
							$t->Label('upload', $lang['module_download']['upload_file']);
							$t->Url('upload', 'index.php?m=download&d=upload&id='.$q->items[$i]['id_download'].'&returnto='.urlencode(sm_this_url()));
							$t->Url('edit', 'index.php?m=download&d=edit&id='.$q->items[$i]['id_download'].'&returnto='.urlencode(sm_this_url()));
							$t->Url('delete', 'index.php?m=download&d=postdelete&id='.$q->items[$i]['id_download'].'&returnto='.urlencode(sm_this_url()));
							$t->NewRow();
						}
					$ui->AddGrid($t);
					$ui->AddPagebarParams($q->Find(), $limit, $offset);
					$ui->AddButtons($b);
					$ui->Output(true);
				}
			if (sm_action('admin'))
				{
					add_path_modules();
					sm_extcore();
					$m['title'] = $lang['control_panel'].' :: '.$lang['module_download']['downloads'];
					include_once('includes/admininterface.php');
					$ui = new TInterface();
					$ui->a('index.php?m=download&d=list', $lang['module_download']['downloads']);
					$ui->br();
					$ui->a('index.php?m=download&d=add', $lang['module_download']['upload_file']);
					$ui->br();
					$ui->a(sm_tomenuurl($lang['module_download']['downloads'], 'index.php?m=download&d=add'), $lang['add_to_menu'].' - '.$lang['module_download']['downloads']);
					$ui->br();
					$ui->Output(true);
				}
			if (strcmp($m["mode"], 'edit') == 0)
				{
					$m['title'] = $lang['edit'];
					$iddownl = $_getvars['did'];
					$m['iddownl'] = $iddownl;
					$sql = "SELECT * FROM ".$tableprefix."downloads WHERE id_download = '$iddownl'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
					while ($row = database_fetch_object($result))
						{
							$m['short_desc'] = $row->description_download;
						}
				}
			if (strcmp($m["mode"], 'postedit') == 0)
				{
					$iddownl = $_getvars['did'];
					$m['mode'] = 'view';
					$descr = dbescape($_postvars['p_shortdesc']);
					$sql = "UPDATE ".$tableprefix."downloads SET description_download = '$descr' WHERE id_download = '$iddownl'";
					$result = database_db_query($nameDB, $sql, $lnkDB);
				}
		}

	if (strcmp($m["mode"], 'errorupload') == 0)
		{
			$m['title'] = $lang['error'];
			$m["module"] = 'download';
		}

	if (strcmp($m["mode"], 'view') == 0)
		{
			sm_page_viewid('download-view');
			$m["module"] = 'download';
			$m['title'] = $lang['module_download']['downloads'];
			$sql = "SELECT * FROM ".$tableprefix."downloads WHERE attachment_from='-' AND userlevel_download <= ".intval($userinfo["level"]);
			$i = 0;
			$result = database_db_query($nameDB, $sql, $lnkDB);
			while ($row = database_fetch_assoc($result))
				{
					$m['files'][$i]['id'] = $row['id_download'];
					$m['files'][$i]['file'] = $row['file_download'];
					$m['files'][$i]['description'] = $row['description_download'];
					$m['files'][$i]['sizeK'] = round(filesize('./files/download/'.$row['file_download'])/1024, 2);
					$m['files'][$i]['sizeM'] = round($m['files'][$i]['sizeK']/1024, 2);
					sm_add_content_modifier($m['files'][$i]['description']);
					$i++;
				}
			sm_add_title_modifier($m['title']);
		}

?>