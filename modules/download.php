<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.2	                                                               |
//#revision 2012-03-22                                                         |
//==============================================================================

if (!defined("SIMAN_DEFINED"))
	{
		print('Спроба несанкціонованого доступу!<br><br>Hacking attempt!');
		exit();
	}

if (empty($modules[$modules_index]["mode"])) $modules[$modules_index]["mode"]='view';

if (strcmp($modules[$modules_index]["mode"], 'attachment')==0 || strcmp($modules[$modules_index]["mode"], 'showattachedfile')==0)
	{
		$att=getsql("SELECT * FROM ".$tableprefix."downloads WHERE userlevel_download<=".intval($userinfo['id'])." AND id_download=".intval($_getvars['id']));
		if (!empty($att['id_download']) && file_exists('files/download/attachment'.intval($_getvars['id'])))
			{
				$modules[$modules_index]["module"]='download';
				$special['main_tpl']='';
				$special['no_blocks']=1;
				header("Content-type: ".$att['attachment_type']);
				if (strcmp($modules[$modules_index]["mode"], 'showattachedfile')!=0)
					header("Content-Disposition: attachment; filename=".$att['file_download']);
				$fp = fopen('files/download/attachment'.intval($_getvars['id']), 'rb');
				fpassthru($fp);
				fclose($fp);
			}
	}

if (strcmp($userinfo['status'],'admin')==0)
	{
		$modules[$modules_index]["module"]='download';
		if (strcmp($modules[$modules_index]["mode"], 'deleteattachment')==0)
			{
				$modules[$modules_index]["module"]='download';
				$modules[$modules_index]['title']=$lang['common']['delete'];
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['common']['delete'];
				$_msgbox['msg']=$lang['module_download']['really_want_delete_file'];
				$_msgbox['yes']='index.php?m=download&d=postdeleteattachment&id='.$_getvars["id"];
				$_msgbox['no']='index.php?m=download';
			}
		if (strcmp($modules[$modules_index]["mode"], 'postdeleteattachment')==0)
			{
				$modules[$modules_index]["module"]='download';
				$modules[$modules_index]['title']=$lang['common']['delete'];
				sm_delete_attachment(intval($_getvars['id']));
				$refresh_url='index.php?m=download';
				sm_event('postdeleteattachment', array(intval($_getvars['id'])));
			}
		if (strcmp($modules[$modules_index]["mode"], 'admin')==0)
			{
				$modules[$modules_index]['title']=$lang['settings'].' :: '.$lang['module_download']['downloads'];
				if ($_settings['humanURL']==1)
					$modules[$modules_index]['downloads_url']='downloads/';
				else
					$modules[$modules_index]['downloads_url']='index.php?m=download';
			}
		if (strcmp($modules[$modules_index]["mode"], 'edit')==0)
			{
				$modules[$modules_index]['title']=$lang['edit'];
				$iddownl=$_getvars['did'];
				$modules[$modules_index]['iddownl']=$iddownl;
				$sql="SELECT * FROM ".$tableprefix."downloads WHERE id_download = '$iddownl'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_object($result))
					{
						$modules[$modules_index]['short_desc']=$row->description_download;
					}
			}
		if (strcmp($modules[$modules_index]["mode"], 'postedit')==0)
			{
				$iddownl=$_getvars['did'];
				$modules[$modules_index]['mode']='view';
				$descr=addslashesJ($_postvars['p_shortdesc']);
				$sql="UPDATE ".$tableprefix."downloads SET description_download = '$descr' WHERE id_download = '$iddownl'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
			}
		if (strcmp($modules[$modules_index]["mode"], 'upload')==0)
			{
				$modules[$modules_index]['title']=$lang['module_download']['upload_file'];
			}
		if (strcmp($modules[$modules_index]["mode"], 'postdelete')==0)
			{
				$modules[$modules_index]['title']=$lang['delete'];
				$iddownl=$_getvars['did'];
				$fname="";
				$sql="SELECT * FROM ".$tableprefix."downloads WHERE id_download = '$iddownl'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				while ($row=database_fetch_object($result))
					{
						$fname=$row->file_download;
					}
				$sql="DELETE FROM ".$tableprefix."downloads WHERE id_download = '$iddownl'";
				$result=database_db_query($nameDB, $sql, $lnkDB);
				unlink('./files/download/'.$fname);
				$refresh_url="index.php?m=download&d=view";
			}
		if (strcmp($modules[$modules_index]["mode"], 'delete')==0)
			{
				$_msgbox['mode']='yesno';
				$_msgbox['title']=$lang['delete'];
				$_msgbox['msg']=$lang['module_download']['really_want_delete_file'];
				$_msgbox['yes']='index.php?m=download&d=postdelete&did='.$_getvars["did"];
				$_msgbox['no']='index.php?m=download';
			}
		if (strcmp($modules[$modules_index]["mode"], 'postupload')==0)
			{
				$modules[$modules_index]['title']=$lang['module_download']['upload_file'];
				$descr=addslashesJ($_postvars['p_shortdesc']);
				$fs=$_uplfilevars['userfile']['tmp_name'];
				if (empty($_postvars['p_optional']))
					{
						$fd=basename($_uplfilevars['userfile']['name']);
					}
				else
					{
						$fd=$_postvars['p_optional'];
					}
				$fd2=$fd;
				$fd='./files/download/'.$fd;
				$modules[$modules_index]['fs']=$fs;
				$modules[$modules_index]['fd']=$fd;
				if (file_exists($fd))
					unlink($fd);
				if (!move_uploaded_file($fs, $fd)) 
					{
						$modules[$modules_index]['mode']='errorupload';
					}
				else
					{
						$fd2=addslashesJ($fd2);
						$sql="INSERT INTO ".$tableprefix."downloads  (file_download, description_download, userlevel_download) VALUES ('$fd2', '$descr', 0)";
						$result=database_db_query($nameDB, $sql, $lnkDB);
						$refresh_url="index.php?m=download&d=view";
					}
			}
	}

if (strcmp($modules[$modules_index]["mode"], 'errorupload')==0)
	{
		$modules[$modules_index]['title']=$lang['error'];
		$modules[$modules_index]["module"]='download';
	}

if (strcmp($modules[$modules_index]["mode"], 'view')==0)
	{
		sm_page_viewid('download-view');
		$modules[$modules_index]["module"]='download';
		$modules[$modules_index]['title']=$lang['module_download']['downloads'];
		$sql="SELECT * FROM ".$tableprefix."downloads WHERE attachment_from='-' AND userlevel_download <= '".$userinfo["level"]."'";
		$i=0;
		$result=database_db_query($nameDB, $sql, $lnkDB);
		while ($row=database_fetch_object($result))
			{
				$modules[$modules_index]['files'][$i]['id']=$row->id_download;
				$modules[$modules_index]['files'][$i]['file']=$row->file_download;
				$modules[$modules_index]['files'][$i]['description']=$row->description_download;
				$modules[$modules_index]['files'][$i]['sizeK']=round(filesize('./files/download/'.$row->file_download)/1024, 2);
				$modules[$modules_index]['files'][$i]['sizeM']=round($modules[$modules_index]['files'][$i]['sizeK']/1024, 2);
				sm_add_content_modifier($modules[$modules_index]['files'][$i]['description']);
				$i++;
			}
		sm_add_title_modifier($modules[$modules_index]['title']);
	}

?>