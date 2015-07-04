<?php

	/*
	Module Name: Code Generator
	Module URI: http://simancms.org/
	Description: Code generator for UI
	Version: 1.6.9
	Revision: 2015-05-27
	Author URI: http://simancms.org/
	*/

	sm_default_action('prepare');
	
	function parse_mysql_create($modulename, $sql)
		{
			preg_match_all('/`(.+)` (\w+)\(? ?(\d*) ?\)?/', $sql, $fields, PREG_SET_ORDER);
			$result['fields']=$fields;
			if (preg_match('/CREATE\s+(?:TEMPORARY\s+)?TABLE\s+(?:IF NOT EXISTS\s+)?([^\s]+)/i', $sql, $matches))
				{
					$tableName = $matches[1];
				}
			$result['table']=str_replace('`', '', $tableName);
			if (preg_match('#.*PRIMARY\s+KEY\s+\(`(.*?)`\).*#i', $sql, $matches))
				{
					$result['id']=$matches[1];
				}
			return $result;
		}
	
	function get_postdelete_code($modulename, $sql, $moduletitle)
		{
			$info=parse_mysql_create($modulename, $sql);
			$str="
			if (sm_action('postdelete'))
				{
					\$q=new TQuery('".$info['table']."');
					\$q->Add('".$info['id']."', intval(\$_getvars['id']));
					\$q->Remove();
					sm_extcore();
					sm_saferemove('index.php?m='.sm_current_module().'&d=view&id='.intval(\$_getvars['id']));
					sm_redirect(\$_getvars['returnto']);
				}
			";
			return $str;
		}
	function get_postadd_code($modulename, $sql, $moduletitle)
		{
			$info=parse_mysql_create($modulename, $sql);
			$str="
			if (sm_action('postadd', 'postedit'))
				{
					\$q=new TQuery('".$info['table']."');\n";
			for ($i = 0; $i<count($info['fields']); $i++)
				{
					if ($info['id']==$info['fields'][$i][1])
						continue;
					if ($info['fields'][$i][2]=='tinyint')
						$str.="\t\t\t\t\t\$q->Add('".$info['fields'][$i][1]."', intval(\$_postvars['".$info['fields'][$i][1]."']));\n";
					else
						$str.="\t\t\t\t\t\$q->Add('".$info['fields'][$i][1]."', dbescape(\$_postvars['".$info['fields'][$i][1]."']));\n";
				}
			$str.="\t\t\t\t\tif (sm_action('postadd'))
						\$q->Insert();
					else
						\$q->Update('".$info['id']."', intval(\$_getvars['id']));
					sm_redirect(\$_getvars['returnto']);
				}
			";
			return $str;
		}
	
	function get_add_code($modulename, $sql, $moduletitle)
		{
			$info=parse_mysql_create($modulename, $sql);
			$setfocus='';
			$str="
			if (sm_action('add', 'edit'))
				{
					add_path_modules();
					add_path('".$moduletitle."', 'index.php?m='.sm_current_module().'&d=list');
					sm_use('ui.interface');
					sm_use('ui.form');
					\$ui = new TInterface();
					if (!empty(\$error))
						\$ui->NotificationError(\$error);
					if (sm_action('edit'))
						{
							sm_title(\$lang['common']['edit']);
							\$f=new TForm('index.php?m='.sm_current_module().'&d=postedit&id='.intval(\$_getvars['id']).'&returnto='.urlencode(\$_getvars['returnto']));
						}
					else
						{
							sm_title(\$lang['common']['add']);
							\$f=new TForm('index.php?m='.sm_current_module().'&d=postadd&returnto='.urlencode(\$_getvars['returnto']));
						}\n";
			for ($i = 0; $i<count($info['fields']); $i++)
				{
					if ($info['id']==$info['fields'][$i][1])
						continue;
					if (empty($setfocus))
						$setfocus=$info['fields'][$i][1];
					if ($info['fields'][$i][2]=='tinyint')
						$str.="\t\t\t\t\t\$f->AddCheckbox('".$info['fields'][$i][1]."', '".$info['fields'][$i][1]."');\n";
					elseif ($info['fields'][$i][2]=='text')
						$str.="\t\t\t\t\t\$f->AddTextarea('".$info['fields'][$i][1]."', '".$info['fields'][$i][1]."');\n";
					else
						$str.="\t\t\t\t\t\$f->AddText('".$info['fields'][$i][1]."', '".$info['fields'][$i][1]."');\n";
				}
			$str.="\t\t\t\t\tif (sm_action('edit'))
						{
							\$q=new TQuery('".$info['table']."');
							\$q->Add('".$info['id']."', intval(\$_getvars['id']));
							\$f->LoadValuesArray(\$q->Get());
							unset(\$q);
						}
					\$f->LoadValuesArray(\$_postvars);
					\$ui->AddForm(\$f);
					\$ui->Output(true);
					sm_setfocus('".$setfocus."');
				}
			";
			return $str;
		}
	
	function get_list_code($modulename, $sql, $moduletitle)
		{
			$info=parse_mysql_create($modulename, $sql);
			$str="
			if (sm_action('list'))
				{
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_use('ui.grid');
					include_once('includes/adminbuttons.php');
					add_path_modules();
					add_path('".$moduletitle."', 'index.php?m='.sm_current_module().'&d=list');
					sm_title('".$moduletitle."');
					\$offset=abs(intval(\$_getvars['from']));
					\$limit=30;
					\$ui = new TInterface();
					\$b=new TButtons();
					\$b->AddButton('add', \$lang['common']['add'], 'index.php?m='.sm_current_module().'&d=add&returnto='.urlencode(sm_this_url()));
					\$ui->AddButtons(\$b);
					\$t=new TGrid();\n";
			for ($i = 0; $i<count($info['fields']); $i++)
				$str.="\t\t\t\t\t\$t->AddCol('".$info['fields'][$i][1]."', '".$info['fields'][$i][1]."');\n";
			$str.="\t\t\t\t\t\$t->AddEdit();
					\$t->AddDelete();
					\$q=new TQuery('".$info['table']."');
					\$q->Limit(\$limit);
					\$q->Offset(\$offset);
					\$q->Select();
					for (\$i = 0; \$i<count(\$q->items); \$i++)
						{\n";
			for ($i = 0; $i<count($info['fields']); $i++)
				$str.="\t\t\t\t\t\t\t\$t->Label('".$info['fields'][$i][1]."', \$q->items[\$i]['".$info['fields'][$i][1]."']);\n";
			$str.="\t\t\t\t\t\t\t\$t->Url('edit', 'index.php?m='.sm_current_module().'&d=edit&id='.\$q->items[\$i]['".$info['id']."'].'&returnto='.urlencode(sm_this_url()));
							\$t->Url('delete', 'index.php?m='.sm_current_module().'&d=postdelete&id='.\$q->items[\$i]['".$info['id']."'].'&returnto='.urlencode(sm_this_url()));
							\$t->NewRow();
						}
					\$ui->AddGrid(\$t);
					\$ui->AddPagebarParams(\$q->Find(), \$limit, \$offset);
					\$ui->AddButtons(\$b);
					\$ui->Output(true);
				}
			";
			return ($str);
		}
	
	function get_admin_code($modulename, $sql, $moduletitle)
		{
			$info=parse_mysql_create($modulename, $sql);
			$str="
				if (\$userinfo['level']==3)
				{
					if (sm_action('admin'))
						{
							add_path_modules();
							sm_title('".$moduletitle."');
							sm_use('ui.interface');
							\$ui = new TInterface();
							\$ui->a('index.php?m='.sm_current_module().'&d=list', \$lang['common']['list']);
							\$ui->Output(true);
						}
					if (sm_action('install'))
						{
							sm_register_module('".$modulename."', '".$moduletitle."');
							//sm_register_autoload('".$modulename."');
							//sm_register_postload('".$modulename."');
							sm_redirect('index.php?m=admin&d=modules');
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('".$modulename."');
							//sm_unregister_autoload('".$modulename."');
							//sm_unregister_postload('".$modulename."');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
			";
			return $str;
		}

	//$info=parse_mysql_create($modulename, $sql);
	if (sm_action('generate'))
		{
			$modulename=$_postvars['module'];
			$moduletitle=$_postvars['title'];
			$sql=$_postvars['sql'];
			$info='<'.'?'."php\n\n";
			$info.="/*\n";
			$info.="Module Name: ".$moduletitle."\n";
			$info.="Module URI: http://simancms.org/\n";
			$info.="Description: ".$moduletitle."\n";
			$info.="Version: 1.0\n";
			$info.="Revision: ".date('Y-m-d')."\n";
			$info.="Author URI: http://simancms.org/\n";
			$info.="*/\n\n";
			$info.='if ($userinfo[\'level\']>0)'."\n\t\t{\n";;
			$info.=get_postdelete_code($modulename, $sql, $moduletitle);
			$info.=get_postadd_code($modulename, $sql, $moduletitle);
			$info.=get_add_code($modulename, $sql, $moduletitle);
			$info.=get_list_code($modulename, $sql, $moduletitle);
			$info.="\n\t\t}\n";
			$info.=get_admin_code($modulename, $sql, $moduletitle);
			$info.="\n";
			$info.="\n?".'>';
			sm_title('Code Generator');
			sm_use('ui.interface');
			sm_use('ui.form');
			$ui = new TInterface();
			//$ui->html('<pre>'.$info.'</pre>');
			$f = new TForm(false);
			$f->AddTextarea('php', 'PHP');
			$f->SetValue('php', $info);
			$ui->AddForm($f);
			$ui->Output(true);
		}
	
	if (sm_action('prepare'))
		{
			sm_title('Code Generator');
			sm_use('ui.interface');
			sm_use('ui.form');
			$ui = new TInterface();
			$f = new TForm('index.php?m=modulegenerator&d=generate');
			$f->AddText('module', 'Module ID')->SetFocus();
			$f->AddText('title', 'Module Title');
			$f->AddTextarea('sql', 'SQL Create Query');
			$ui->AddForm($f);
			$ui->Output(true);
		}
	
	if ($userinfo['level'] == 3)
		{
			if (sm_action('admin'))
				{
					sm_redirect('index.php?m=modulegenerator&d=prepare');
				}
			if (sm_action('install'))
				{
					sm_register_module('modulegenerator', 'Code Generator');
					sm_redirect('index.php?m=admin&d=modules');
				}
			if (sm_action('uninstall'))
				{
					sm_unregister_module('modulegenerator');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>