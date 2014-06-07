<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2014-06-07
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if ($userinfo['level']==3)
		{
			sm_include_lang('media');

			if (sm_action('postdeletectg'))
				{
					$q=new TQuery('sm_categories_media');
					$q->Add('id_ctg', intval($_getvars['id']));
					$q->Remove();
					sm_extcore();
					sm_saferemove('index.php?m=media&d=list&id='.intval($_getvars['id']));
					if (file_exists('files/img/mediagallery'.intval($_getvars['id']).'.jpg'))
						unlink('files/img/mediagallery'.intval($_getvars['id']).'.jpg');
					sm_redirect($_getvars['returnto']);
				}

			if (sm_action('postaddctg', 'posteditctg'))
				{
					$q=new TQuery('sm_categories_media');
					$q->Add('title', dbescape($_postvars['title']));
					$q->Add('public', intval($_postvars['public']));
					$q->Add('keywords', dbescape($_postvars['keywords']));
					$q->Add('description', dbescape($_postvars['description']));
					$q->Add('items_count', dbescape($_postvars['items_count']));
					$q->Add('lastupdate', time());
					if (sm_action('postaddctg'))
						$id=$q->Insert();
					else
						{
							$id=intval($_getvars['id']);
							$q->Update('id_ctg', $id);
						}
					print_r($_FILES);
					if ($file=sm_upload_file('userfile'))
						{
							sm_extcore();
							sm_resizeimage($file, 'files/img/mediagallery'.$id.'.jpg', sm_settings('gallery_thumb_width'), sm_settings('gallery_thumb_height'), 0, 100, 1);
							unlink($file);
							var_dump($file);
						}
					sm_redirect($_getvars['returnto']);
				}

			if (sm_action('addctg', 'editctg'))
				{
					add_path_modules();
					add_path('Media', 'index.php?m=media&d=list');
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					if (!empty($error))
						$ui->div($error, '', 'error alert-error');
					if (sm_action('editctg'))
						{
							sm_title($lang['common']['edit']);
							$f=new TForm('index.php?m=media&d=posteditctg&id='.intval($_getvars['id']).'&returnto='.urlencode($_getvars['returnto']));
						}
					else
						{
							sm_title($lang['common']['add']);
							$f=new TForm('index.php?m=media&d=postaddctg&returnto='.urlencode($_getvars['returnto']));
						}
					$f->AddText('title', $lang["title"], true);
					$f->AddFile('userfile', $lang['common']['thumbnail']);
					$f->AddCheckbox('public', $lang['common']['public']);
					$f->AddText('keywords', $lang['common']['seo_keywords']);
					$f->AddText('description', $lang['common']['seo_description']);
					if (sm_action('editctg'))
						{
							$q=new TQuery('sm_categories_media');
							$q->Add('id_ctg', intval($_getvars['id']));
							$f->LoadValuesArray($q->Get());
							unset($q);
						}
					if (is_array($_postvars))
						$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('title');
				}

			if (sm_action('list'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					include_once('includes/admintable.php');
					include_once('includes/adminbuttons.php');
					add_path_modules();
					sm_title($lang['module_galleies']['galleries']);
					add_path_current();
					$offset=abs(intval($_getvars['from']));
					$limit=30;
					$ui = new TInterface();
					$b=new TButtons();
					$b->AddButton('add', $lang['common']['add'], 'index.php?m=media&d=addctg&returnto='.urlencode(sm_this_url()));
					$ui->AddButtons($b);
					$t=new TGrid();
					$t->AddCol('id_ctg', 'id_ctg');
					$t->AddCol('title', 'title');
					$t->AddCol('public', $lang['module_galleies']['public']);
					$t->AddCol('keywords', 'keywords');
					$t->AddCol('description', 'description');
					$t->AddCol('items_count', $lang['count']);
					$t->AddCol('lastupdate', 'lastupdate');
					$t->AddEdit();
					$t->AddDelete();
					$q=new TQuery('sm_categories_media');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					for ($i = 0; $i<count($q->items); $i++)
						{
							$t->Label('id_ctg', $q->items[$i]['id_ctg']);
							$t->Label('title', $q->items[$i]['title']);
							$t->Label('public', $q->items[$i]['public']);
							$t->Label('keywords', $q->items[$i]['keywords']);
							$t->Label('description', $q->items[$i]['description']);
							$t->Label('items_count', $q->items[$i]['items_count']);
							$t->Label('lastupdate', $q->items[$i]['lastupdate']);
							$t->Url('edit', 'index.php?m=media&d=editctg&id='.$q->items[$i]['id_ctg'].'&returnto='.urlencode(sm_this_url()));
							$t->Url('delete', 'index.php?m=media&d=postdeletectg&id='.$q->items[$i]['id_ctg'].'&returnto='.urlencode(sm_this_url()));
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
					$m['title'] = 'Media';
					include_once('includes/admininterface.php');
					$ui = new TInterface();
					$ui->a('index.php?m=media&d=list', $lang['common']['list']);
					$ui->Output(true);
				}
		}

	
	
	/*
	 
	CREATE TABLE `sm_categories_media` (
  `id_ctg` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `public` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `items_count` int(11) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ctg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

	
	
	CREATE TABLE `sm_media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ctg` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT 'jpg',
  `title` varchar(255) DEFAULT NULL,
  `originalname` varchar(255) DEFAULT '',
  `filepath` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	*/

?>