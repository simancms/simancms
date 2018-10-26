<?php

	/*
	Module Name: Code Generator
	Module URI: http://simancms.org/
	Description: Code generator for UI
	Version: 1.6.16
	Revision: 2018-10-26
	Author URI: http://simancms.org/
	*/

	sm_default_action('prepare');

	if (sm_is_installed(sm_current_module()))
		{
			function parse_mysql_create($sql)
				{
					preg_match_all('/`(.+)` (\w+)\(? ?(\d*) ?\)?/', $sql, $fields, PREG_SET_ORDER);
					$result['fields'] = $fields;
					if (preg_match('/CREATE\s+(?:TEMPORARY\s+)?TABLE\s+(?:IF NOT EXISTS\s+)?([^\s]+)/i', $sql, $matches))
						{
							$tableName = $matches[1];
						}
					$result['table'] = str_replace('`', '', $tableName);
					if (strcmp(substr($result['table'], 0, strlen(sm_table_prefix())), sm_table_prefix()) == 0)
						{
							$result['tableprefix'] = 'sm_table_prefix().';
							$result['table'] = substr($result['table'], strlen(sm_table_prefix()));
						}
					if (preg_match('#.*PRIMARY\s+KEY\s+\(`(.*?)`\).*#i', $sql, $matches))
						{
							$result['id'] = $matches[1];
						}
					return $result;
				}

			function get_postdelete_code($data)
				{
					$info = parse_mysql_create($data['sql']);
					$str = "
			if (sm_action('postdelete'))
				{
					\$q=new TQuery(".$info['tableprefix']."'".$info['table']."');
					\$q->AddWhere('".$info['id']."', intval(\$_getvars['id']));
					\$q->Remove();
					sm_extcore();
					sm_saferemove('index.php?m='.sm_current_module().'&d=view&id='.intval(\$_getvars['id']));
					sm_redirect(\$_getvars['returnto']);
				}
			";
					return $str;
				}

			function get_postadd_code($data)
				{
					$info = parse_mysql_create($data['sql']);
					$req = '';
					for ($i = 0; $i < count($data['fields']); $i++)
						{
							if ($data['fields'][$i]['required'])
								{
									if (!empty($req))
										$req .= ' || ';
									$req .= "empty(\$_postvars['".$data['fields'][$i]['name']."'])";
								}
						}
					$str = "
			if (sm_action('postadd', 'postedit'))
				{
					\$error_message='';\n";
					if (!empty($req))
						$str .= "\t\t\t\t\tif (".$req.")
						\$error_message=\$lang['messages']['fill_required_fields'];\n";
					$str .= "\t\t\t\t\tif (empty(\$error_message))
						{
							\$q=new TQuery(".$info['tableprefix']."'".$info['table']."');\n";
					for ($i = 0; $i < count($data['fields']); $i++)
						{
							if ($info['id'] == $data['fields'][$i]['name'])
								continue;
							if ($data['fields'][$i]['control'] == 'disabled')
								continue;
							if ($data['fields'][$i]['datatype'] == 'tinyint' || $data['fields'][$i]['datatype'] == 'int')
								$str .= "\t\t\t\t\t\t\t\$q->Add('".$data['fields'][$i]['name']."', intval(\$_postvars['".$data['fields'][$i]['name']."']));\n";
							elseif ($data['fields'][$i]['datatype'] == 'decimal')
								$str .= "\t\t\t\t\t\t\t\$q->Add('".$data['fields'][$i]['name']."', floatval(\$_postvars['".$data['fields'][$i]['name']."']));\n";
							else
								$str .= "\t\t\t\t\t\t\t\$q->Add('".$data['fields'][$i]['name']."', dbescape(\$_postvars['".$data['fields'][$i]['name']."']));\n";
						}
					$str .= "\t\t\t\t\t\t\tif (sm_action('postadd'))
								\$q->Insert();
							else
								\$q->Update('".$info['id']."', intval(\$_getvars['id']));
							sm_redirect(\$_getvars['returnto']);
						}
					else
						sm_set_action(Array('postadd'=>'add', 'postedit'=>'edit'));
				}
			";
					return $str;
				}

			function get_add_code($data)
				{
					$info = parse_mysql_create($data['sql']);
					$setfocus = '';
					$str = "
			if (sm_action('add', 'edit'))
				{
					".($data['breadcrumbs']=='control'?'add_path_modules()':'add_path_home()').";
					add_path('".$data['moduletitle']."', 'index.php?m='.sm_current_module().'&d=list');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.form');
					\$ui = new TInterface();
					if (!empty(\$error_message))
						\$ui->NotificationError(\$error_message);
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
					for ($i = 0; $i < count($data['fields']); $i++)
						{
							if ($info['id'] == $data['fields'][$i]['name'])
								continue;
							if ($data['fields'][$i]['control'] == 'disabled')
								continue;
							if (empty($setfocus))
								$setfocus = $data['fields'][$i]['name'];
							if ($data['fields'][$i]['datatype'] == 'tinyint')
								$str .= "\t\t\t\t\t\$f->AddCheckbox('".$data['fields'][$i]['name']."', '".$data['fields'][$i]['caption']."'".($data['fields'][$i]['required'] ? ', true' : '').");\n";
							elseif ($data['fields'][$i]['datatype'] == 'text')
								$str .= "\t\t\t\t\t\$f->AddTextarea('".$data['fields'][$i]['name']."', '".$data['fields'][$i]['caption']."'".($data['fields'][$i]['required'] ? ', true' : '').");\n";
							elseif ($data['fields'][$i]['datatype'] == 'editor')
								$str .= "\t\t\t\t\t\$f->AddEditor('".$data['fields'][$i]['name']."', '".$data['fields'][$i]['caption']."'".($data['fields'][$i]['required'] ? ', true' : '').");\n";
							else
								$str .= "\t\t\t\t\t\$f->AddText('".$data['fields'][$i]['name']."', '".$data['fields'][$i]['caption']."'".($data['fields'][$i]['required'] ? ', true' : '').");\n";
						}
					$str .= "\t\t\t\t\tif (sm_action('edit'))
						{
							\$q=new TQuery(".$info['tableprefix']."'".$info['table']."');
							\$q->AddWhere('".$info['id']."', intval(\$_getvars['id']));
							\$f->LoadValuesArray(\$q->Get());
							unset(\$q);
						}
					\$f->LoadValuesArray(\$_postvars);
					\$ui->Add(\$f);
					\$ui->Output(true);
					sm_setfocus('".$setfocus."');
				}
			";
					return $str;
				}

			function get_list_code($data)
				{
					$info = parse_mysql_create($data['sql']);
					$str = "
			if (sm_action('list'))
				{
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_use('ui.grid');
					sm_use('ui.buttons');
					".($data['breadcrumbs']=='control'?'add_path_modules()':'add_path_home()').";
					add_path('".$data['moduletitle']."', 'index.php?m='.sm_current_module().'&d=list');
					sm_title('".$data['moduletitle']."');
					\$offset=abs(intval(\$_getvars['from']));
					\$limit=30;
					\$ui = new TInterface();
					\$b=new TButtons();
					\$b->AddButton('add', \$lang['common']['add'], 'index.php?m='.sm_current_module().'&d=add&returnto='.urlencode(sm_this_url()));
					\$ui->Add(\$b);
					\$t=new TGrid();\n";
					for ($i = 0; $i < count($data['fields']); $i++)
						{
							$str .= "\t\t\t\t\t\$t->AddCol('".$data['fields'][$i]['name']."', '".$data['fields'][$i]['caption']."');\n";
						}
					$str .= "\t\t\t\t\t\$t->AddEdit();
					\$t->AddDelete();
					\$q=new TQuery(".$info['tableprefix']."'".$info['table']."');
					\$q->Limit(\$limit);
					\$q->Offset(\$offset);
					\$q->Select();
					for (\$i = 0; \$i<\$q->Count(); \$i++)
						{\n";
					for ($i = 0; $i < count($data['fields']); $i++)
						{
							$str .= "\t\t\t\t\t\t\t\$t->Label('".$data['fields'][$i]['name']."', \$q->items[\$i]['".$data['fields'][$i]['name']."']);\n";
						}
					$str .= "\t\t\t\t\t\t\t\$t->URL('edit', 'index.php?m='.sm_current_module().'&d=edit&id='.\$q->items[\$i]['".$info['id']."'].'&returnto='.urlencode(sm_this_url()));
							\$t->URL('delete', 'index.php?m='.sm_current_module().'&d=postdelete&id='.\$q->items[\$i]['".$info['id']."'].'&returnto='.urlencode(sm_this_url()));
							\$t->NewRow();
						}
					if (\$t->RowCount()==0)
						\$t->SingleLineLabel(\$lang['messages']['nothing_found']);
					\$ui->Add(\$t);
					\$ui->AddPagebarParams(\$q->TotalCount(), \$limit, \$offset);
					\$ui->Add(\$b);
					\$ui->Output(true);
				}
			";
					return ($str);
				}

			function get_admin_code($data)
				{
					$info = parse_mysql_create($data['sql']);
					$str = "
			if (\$userinfo['level']==3)
				{
					if (sm_action('admin'))
						{
							add_path_modules();
							sm_title('".$data['moduletitle']."');
							sm_use('ui.interface');
							\$ui = new TInterface();
							\$ui->a('index.php?m='.sm_current_module().'&d=list', \$lang['common']['list']);
							\$ui->Output(true);
						}
					if (sm_action('install'))
						{
							sm_register_module('".$data['modulename']."', '".$data['moduletitle']."');
							//sm_register_autoload('".$data['modulename']."');
							//sm_register_postload('".$data['modulename']."');
							sm_redirect('index.php?m=admin&d=modules');
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('".$data['modulename']."');
							//sm_unregister_autoload('".$data['modulename']."');
							//sm_unregister_postload('".$data['modulename']."');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
			";
					return $str;
				}

			if (sm_action('generate'))
				{
					$data=Array(
						'modulename'=>$_postvars['module'],
						'moduletitle'=>$_postvars['title'],
						'author_uri'=>$_postvars['author_uri'],
						'module_uri'=>$_postvars['module_uri'],
						'description'=>$_postvars['description'],
						'access_level'=>intval($_postvars['level']),
						'sql'=>$_postvars['sql'],
						'breadcrumbs'=>$_postvars['breadcrumbs'],
						'fields'=>Array(),
					);
					$info = parse_mysql_create($data['sql']);
					for ($i = 0; $i < count($info['fields']); $i++)
						{
							$data['fields'][$i]['name'] = $info['fields'][$i][1];
							$data['fields'][$i]['datatype'] = $info['fields'][$i][2];
							$data['fields'][$i]['control'] = $_postvars['field_'.$i];
							$data['fields'][$i]['caption'] = $_postvars['fieldcap_'.$i];
							$data['fields'][$i]['required'] = intval($_postvars['required_'.$i]) == 1;
						}
					$info = '<'.'?'."php\n\n";
					$info .= "/*\n";
					$info .= "Module Name: ".$data['moduletitle']."\n";
					$info .= "Module URI: ".$data['module_uri']."\n";
					$info .= "Description: ".$data['description']."\n";
					$info .= "Version: 1.0\n";
					$info .= "Revision: ".date('Y-m-d')."\n";
					$info .= "Author URI: ".$data['author_uri']."\n";
					$info .= "*/\n\n";
					$info .= '	if ($userinfo[\'level\']>='.$data['access_level'].')'."\n\t\t{\n";;
					$info .= get_postdelete_code($data);
					$info .= get_postadd_code($data);
					$info .= get_add_code($data);
					$info .= get_list_code($data);
					$info .= get_admin_code($data);
					$info .= "\n\t\t}\n";
					sm_title('Code Generator');
					sm_use('ui.interface');
					sm_use('ui.form');
					$ui = new TInterface();
					//$ui->html('<pre>'.$info.'</pre>');
					$f = new TForm(false);
					$f->AddTextarea('php', 'PHP Code');
					$f->SetFieldAttribute('php', 'wrap', 'off');
					$f->MergeColumns('php');
					$f->SetValue('php', $info);
					$ui->Add($f);
					$ui->style('#php{height:500px;}');
					$ui->Output(true);
				}

			if (sm_action('prepare'))
				{
					sm_title('Code Generator');
					sm_use('ui.interface');
					sm_use('ui.form');
					if (empty($_postvars['sql']))
						$_getvars['type']='';
					$ui = new TInterface();
					if ($_getvars['type'] == 'fields')
						$f = new TForm('index.php?m=modulegenerator&d=generate');
					else
						$f = new TForm('index.php?m=modulegenerator&d=prepare&type=fields');
					$f->AddText('module', 'Module ID (file name)')->SetFocus();
					$f->AddText('title', 'Module Title');
					$f->AddText('description', 'Module Description');
					$f->AddSelectVL('level', 'Access Level', [0, 1, 2, 3], [$lang['all_users'], $lang['logged_users'], $lang['power_users'], $lang['administrators']])->WithValue(3);
					$f->AddSelectVL('breadcrumbs', 'Breadcrumbs', ['control', 'home'], ['Control Panel', 'Home']);
					$f->AddText('author_uri', 'Author URL')->WithValue(sm_homepage());
					$f->AddText('module_uri', 'Module URL')->WithValue(sm_homepage());
					$f->AddTextarea('sql', 'SQL Create Query');
					if ($_getvars['type'] == 'fields')
						{
							$info = parse_mysql_create($_postvars['sql']);
							for ($i = 0; $i < count($info['fields']); $i++)
								{
									$f->Separator('Field: '.$info['fields'][$i][1]);
									$f->AddSelectVL('field_'.$i, 'Type', Array('text', 'textarea', 'editor', 'checkbox', 'disabled'), Array('Text', 'Textarea', 'WYSIWYG editor', 'Checkbox', 'Disabled'));
									if ($info['fields'][$i][2] == 'tinyint')
										$f->WithValue('checkbox');
									elseif ($info['fields'][$i][2] == 'text')
										$f->WithValue('textarea');
									else
										$f->WithValue('text');
									$f->AddText('fieldcap_'.$i, 'Caption');
									$cap = str_replace('_', ' ', $info['fields'][$i][1]);
									$cap = strtoupper(substr($cap, 0, 1)).substr($cap, 1);
									$f->WithValue($cap);
									$f->AddCheckbox('required_'.$i, 'Required');
								}
						}
					$f->LoadValuesArray($_postvars);
					$f->SaveButton('Next');
					$ui->Add($f);
					$ui->Output(true);
				}

			if ($userinfo['level'] == 3)
				{
					if (sm_action('admin'))
						{
							sm_redirect('index.php?m=modulegenerator&d=prepare');
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('modulegenerator');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
		}
	if (!sm_is_installed(sm_current_module()) && $userinfo['level'] == 3)
		{
			if (sm_action('install'))
				{
					sm_register_module('modulegenerator', 'Code Generator');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}
