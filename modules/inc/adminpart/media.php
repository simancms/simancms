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

			if (sm_action('postdelete'))
				{
					$q = new TQuery('sm_media');
					$q->Add('id', intval($_getvars['id']));
					$q->Remove();
					sm_extcore();
					sm_saferemove('index.php?m=media&d=view&id='.intval($_getvars['id']));
					sm_redirect($_getvars['returnto']);
				}

			if (sm_action('postadd'))
				{
					$filename='files/img/mediaimage'.$id.'.'.pathinfo($_uplfilevars['userfile']['name'], PATHINFO_EXTENSION);
					if (sm_upload_file('userfile', $filename)!==false)
						{
							$extension=pathinfo($_uplfilevars['userfile']['name'], PATHINFO_EXTENSION);
							$ctg=TQuery::ForTable('sm_categories_media')->Add('id_ctg', intval($_getvars['ctg']))->Get();
							$q = new TQuery('sm_media');
							$q->Add('id_ctg', intval($ctg['id_ctg']));
							$q->Add('type', dbescape($_uplfilevars['userfile']['type']));
							$q->Add('title', dbescape(pathinfo($_uplfilevars['userfile']['name'], PATHINFO_EXTENSION)));
							$q->Add('originalname', dbescape($_uplfilevars['userfile']['name']));
							$q->Add('alt_text', dbescape($_postvars['alt_text']));
							$q->Add('description', dbescape($_postvars['description']));
							$id=$q->Insert();
							$q = new TQuery('sm_media');
							$q->Add('filepath', dbescape($filename));
							$q->Update('id', intval($id));
							sm_redirect($_getvars['returnto']);
						}
					else
						{
							sm_set_action('add');
						}
				}

			if (sm_action('postedit'))
				{
					$q = new TQuery('sm_media');
					$q->Add('id_ctg', dbescape($_postvars['id_ctg']));
					$q->Add('type', dbescape($_postvars['type']));
					$q->Add('title', dbescape($_postvars['title']));
					$q->Add('originalname', dbescape($_postvars['originalname']));
					$q->Add('filepath', dbescape($_postvars['filepath']));
					$q->Add('alt_text', dbescape($_postvars['alt_text']));
					$q->Add('description', dbescape($_postvars['description']));
					if (sm_action('postadd'))
						$q->Insert();
					else
						$q->Update('id', intval($_getvars['id']));
					sm_redirect($_getvars['returnto']);
				}

			if (sm_action('edit'))
				{
					add_path_modules();
					add_path('Media', 'index.php?m=media&d=list');
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					if (!empty($error))
						$ui->div($error, '', 'error alert-error');
					sm_title($lang['common']['edit']);
					$f = new TForm('index.php?m=media&d=postedit&id='.intval($_getvars['id']).'&returnto='.urlencode($_getvars['returnto']));
					$f->AddText('id_ctg', 'id_ctg');
					$f->AddText('type', 'type');
					$f->AddText('title', 'title');
					$f->AddText('originalname', 'originalname');
					$f->AddText('filepath', 'filepath');
					$f->AddText('alt_text', 'alt_text');
					$f->AddTextarea('description', 'description');
					if (sm_action('edit'))
						{
							$q = new TQuery('sm_media');
							$q->Add('id', intval($_getvars['id']));
							$f->LoadValuesArray($q->Get());
							unset($q);
						}
					if (is_array($_postvars))
						$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('id_ctg');
				}

			if (sm_action('add'))
				{
					add_path_modules();
					add_path('Media', 'index.php?m=media&d=list');
					$ctg=TQuery::ForTable('sm_categories_media')->Add('id_ctg', intval($_getvars['ctg']))->Get();
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					$ui = new TInterface();
					if (!empty($error))
						$ui->div($error, '', 'error alert-error');
					sm_title($lang['common']['add']);
					$f = new TForm('index.php?m=media&d=postadd&returnto='.urlencode($_getvars['returnto']));
					$f->AddFile('userfile', $lang['common']['file']);
					if (is_array($_postvars))
						$f->LoadValuesArray($_postvars);
					$ui->AddForm($f);
					$ui->Output(true);
					sm_setfocus('userfile');
				}

			if (sm_action('list'))
				{
					include_once('includes/admininterface.php');
					include_once('includes/adminform.php');
					include_once('includes/admintable.php');
					include_once('includes/adminbuttons.php');
					add_path_modules();
					add_path('Media', 'index.php?m=media&d=list');
					sm_title('Media');
					$offset = abs(intval($_getvars['from']));
					$limit = 30;
					$ui = new TInterface();
					$b = new TButtons();
					$b->AddButton('add', $lang['common']['add'], 'index.php?m=media&d=add&ctg='.intval($_getvars['ctg']).'&returnto='.urlencode(sm_this_url()));
					$ui->AddButtons($b);
					$t = new TGrid();
					$t->AddCol('id', 'id');
					$t->AddCol('id_ctg', 'id_ctg');
					$t->AddCol('type', 'type');
					$t->AddCol('title', 'title');
					$t->AddCol('originalname', 'originalname');
					$t->AddCol('filepath', 'filepath');
					$t->AddCol('alt_text', 'alt_text');
					$t->AddCol('description', 'description');
					$t->AddEdit();
					$t->AddDelete();
					$q = new TQuery('sm_media');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					for ($i = 0; $i < count($q->items); $i++)
						{
							$t->Label('id', $q->items[$i]['id']);
							$t->Label('id_ctg', $q->items[$i]['id_ctg']);
							$t->Label('type', $q->items[$i]['type']);
							$t->Label('title', $q->items[$i]['title']);
							$t->Label('originalname', $q->items[$i]['originalname']);
							$t->Label('filepath', $q->items[$i]['filepath']);
							$t->Label('alt_text', $q->items[$i]['alt_text']);
							$t->Label('description', $q->items[$i]['description']);
							$t->Url('edit', 'index.php?m=media&d=edit&id='.$q->items[$i]['id'].'&returnto='.urlencode(sm_this_url()));
							$t->Url('delete', 'index.php?m=media&d=postdelete&id='.$q->items[$i]['id'].'&returnto='.urlencode(sm_this_url()));
							$t->NewRow();
						}
					$ui->AddGrid($t);
					$ui->AddPagebarParams($q->Find(), $limit, $offset);
					$ui->AddButtons($b);
					$ui->Output(true);
				}

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

			if (sm_action('libraries'))
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
					$t->AddCol('id_ctg', $lang['common']['id']);
					$t->AddCol('image', $lang['common']['image']);
					$t->AddCol('title', $lang['common']['title']);
					$t->AddCol('public', $lang['common']['public']);
					$t->AddCol('items_count', $lang['count']);
					$t->AddEdit();
					$t->AddDelete();
					$q=new TQuery('sm_categories_media');
					$q->OrderBy('lastupdate DESC');
					$q->Limit($limit);
					$q->Offset($offset);
					$q->Select();
					for ($i = 0; $i<count($q->items); $i++)
						{
							$t->Label('id_ctg', $q->items[$i]['id_ctg']);
							$t->Image('image', 'files/img/mediagallery'.$q->items[$i]['id_ctg'].'.jpg');
							$t->Image('image', sm_thumburl('mediagallery'.$q->items[$i]['id_ctg'], 50, 50));
							$t->Label('title', $q->items[$i]['title']);
							$t->Url('title', 'index.php?m=media&d=list&ctg='.$q->items[$i]['id_ctg']);
							$t->Label('public', $q->items[$i]['public']);
							$t->Label('items_count', $q->items[$i]['items_count']);
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
					$ui->a('index.php?m=media&d=libraries', $lang['common']['list']);
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