<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                http://simancms.org                                  |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.4
//==============================================================================

$userinfo['sid']=session_id();

if (empty($_sessionvars['userinfo_id']))
	{
		$userinfo['id']='';
		$userinfo['login']='';
		$userinfo['email']='';
		$userinfo['session']='';
		$userinfo['level']=0;
		$userinfo['groups']='';
		$userinfo['info']=Array();
	}
else
	{
		$userinfo['id']=$_sessionvars['userinfo_id'];
		$userinfo['login']=$_sessionvars['userinfo_login'];
		$userinfo['email']=$_sessionvars['userinfo_email'];
		$userinfo['level']=$_sessionvars['userinfo_level'];
		$userinfo['session']=$userinfo['sid'];
		$userinfo['groups']=$_sessionvars['userinfo_groups'];
		$userinfo['info']=unserialize($_sessionvars['userinfo_allinfo']);
	}

if ($userinfo['id']==1)
	{
		$userinfo['level']=3;
		$userinfo['can_upload_image']=1;
	}

$smarty->assign('userinfo', $userinfo);

?>