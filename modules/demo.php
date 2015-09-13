<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: SiMan CMS Demo
	Module URI: http://simancms.org/modules/demo/
	Description: Examples of usage
	Version: 1.6.9
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("demo_FUNCTIONS_DEFINED"))
		{
			define("demo_FUNCTIONS_DEFINED", 1);
		}

	if (sm_is_installed(sm_current_module()) && $userinfo['level'] > 0)
		{                 
			sm_default_action('demos');
			if (sm_action('htmlshortcuts'))
				{
					sm_title('UI HTML-shortcuts');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					$ui = new TInterface();
					$ui->p('Paragraph simple');
					$ui->p_open();
					$ui->html('Paragraph open');
					$ui->br();
					$ui->html('BR tag');
					$ui->hr();
					$ui->html('HR tag');
					$ui->br();
					$ui->html('Paragraph close');
					$ui->p_close();
					$ui->div('Div with classname demo-red', '', 'demo-red');
					$ui->div('Div with style', '', '', 'background:#ccccff;');
					$ui->h(1, 'H1');
					$ui->h(2, 'H2');
					$ui->h(3, 'H3');
					$ui->h(4, 'H4');
					$ui->h(5, 'H5');
					$ui->h(6, 'H6');
					$ui->a(sm_homepage(), 'Clickable URL');
					$ui->style('.demo-red{background:#ffcccc;}');
					$ui->Output(true);
				}
			if (sm_action('forms'))
				{
					sm_title('UI HTML-shortcuts');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.form');
					$values=Array(
						'text'=>'Text',
						'select'=>2, 
						'checkbox1'=>1,
						'checkbox3'=>'+'
					);
					$ui = new TInterface();
					$f=new TForm('index.php?m=demo&d=forms');
					$f->AddText('text', 'Text field')->SetFocus();
					$f->AddSelectVL('select', 'Select field', Array(1, 2, 3), Array('Label 1', 'Label 2', 'Label 3'));
					$f->AddTextarea('textarea', 'Textarea field');
					$f->Separator('Checkboxes');
					$f->AddCheckbox('checkbox1', 'Checkbox 1');
					$f->AddCheckbox('checkbox2', 'Checkbox 2 (label after control)');
					$f->LabelAfterControl();
					$f->AddCheckbox('checkbox3', 'Checkbox 3 (custom value)', '+');
					$f->Separator('Separator');
					$f->AddEditor('editor', 'Editor');
					$f->SaveButton('Custom Submit Button Title');
					$f->LoadValuesArray($values);
					$f->SetValue('textarea', 'Custom value');
					$ui->Add($f);
					$ui->Output(true);
				}
			if (sm_action('ajaxresponder'))
				{
					out(strftime($lang['datetimemask'], time()).'<br />');
					for ($i = 0; $i < 5+rand(1, 10); $i++)
						{
							out('Line '.$i.'<br />');
						}
				}
			if (sm_action('grid'))
				{
					sm_title('UI TGrid - Table');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.grid');
					$src=Array(
						Array(
							'text'=>'Sample text 0',
							'url'=>''
						)
					);
					for ($i = 1; $i < 21; $i++)
						{
							$src[]=Array(
								'text'=>'Sample text '.$i,
								'url'=>'index.php?m=demo&d=grid&testid='.$i,
								'expand'=>'Expander for row #'.$i
							);
						}
					$ui = new TInterface();
					$t=new TGrid();
					$t->AddCol('n', '#', '5%');
					$t->AddCol('text', 'Text', '55%');
					$t->ColumnAddClass('text', 'at-align-center');
					$t->AddCol('note', 'Note', '40%');
					$t->AddCol('view', 'Actions', '16');
					$t->AddEdit();
					$t->AddDelete();
					$t->AddCol('chk1', '', '10');
					$t->HeaderBulkCheckbox('chk1');
					$t->AddCol('chk2', '', '10');
					$t->HeaderBulkCheckbox('chk2');
					$t->HeaderAutoColspanFor('view');
					for ($i = 0; $i < count($src); $i++)
						{
							if ($i==1)
								{
									$t->RowHighlightError();
									$t->Label('note', 'Error for row');
									$t->CellAlignCenter('note');
								}
							if ($i==2)
								{
									$t->RowHighlightInfo();
									$t->Label('note', 'Info for row');
									$t->CellAlignCenter('note');
								}
							if ($i==3)
								{
									$t->RowHighlightSuccess();
									$t->Label('note', 'Success for row');
									$t->CellAlignCenter('note');
								}
							if ($i==4)
								{
									$t->RowHighlightWarning();
									$t->Label('note', 'Warning for row');
									$t->CellAlignCenter('note');
								}
							if ($i==5)
								{
									$t->RowHighlightAttention();
									$t->Label('note', 'Attention for row');
									$t->CellAlignCenter('note');
								}
							if ($i==10)
								{
									$t->CellHighlightError('text');
									$t->Label('note', '&lt;- Error for cell');
								}
							if ($i==11)
								{
									$t->CellHighlightInfo('text');
									$t->Label('note', '&lt;- Info for cell');
								}
							if ($i==12)
								{
									$t->CellHighlightSuccess('text');
									$t->Label('note', '&lt;- Success for cell');
								}
							if ($i==13)
								{
									$t->CellHighlightWarning('text');
									$t->Label('note', '&lt;- Warning for cell');
								}
							if ($i==14)
								{
									$t->CellHighlightAttention('text');
									$t->Label('note', '&lt;- Attention for cell');
								}
							$t->Label('n', $i);
							$t->Label('text', $src[$i]['text']);
							$t->URL('text', $src[$i]['url']);
							$t->Image('view', 'info');
							if ($i==0)
								{
									$t->ExpandAJAX('view', 'index.php?m=demo&d=ajaxresponder&ajax=1');
									$t->Label('note', 'With AJAX expander -&gt;');
									$t->CellAlignRight('note');
									$t->CellAlignLeft('text');
								}
							elseif (!empty($src[$i]['expand']))
								{
									$t->ExpanderHTML($src[$i]['expand']);
									$t->Expand('view');
								}
							if ($i==17)
								{
									$t->Label('note', 'Drop down menu');
									$t->DropDownItem('note', 'Item 1', 'index.php?m=demo&d=htmlshortcuts');
									$t->DropDownItem('note', 'Item 1 (confirm)', 'index.php?m=demo&d=htmlshortcuts', 'Are you sure?');
								}
							$t->Checkbox('chk1', 'chk1[]', $i, (intval($sm['g']['testid'])==$i));
							$t->Checkbox('chk2', 'chk2[]', $i);
							$t->URL('edit', 'index.php?m=demo&d=forms');
							$t->URL('delete', 'index.php?m=demo&d=grid');
							$t->NewRow();
						}
					$t->SingleLineLabel('Single Line Notification');
					$t->NewRow();
					$ui->Add($t);
					$ui->Output(true);
				}
			if (sm_action('regular'))
				{
					sm_title('Smarty Template');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					$m['module']='demo';
				}
			if (sm_action('demos'))
				{
					sm_title('Available Demos');
					add_path_home();
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$nav=new TNavigation();
					$nav->AddItem('Smarty Template', 'index.php?m=demo&d=regular');
					$nav->AddItem('UI HTML-shortcuts', 'index.php?m=demo&d=htmlshortcuts');
					$nav->AddItem('UI TGrid - Table', 'index.php?m=demo&d=grid');
					$nav->AddItem('UI TForm - From', 'index.php?m=demo&d=forms');
					$ui->Add($nav);
					$ui->Output(true);
				}
			if ($userinfo['level'] == 3)
				{
					if (sm_action('admin'))
						{
							add_path_modules();
							add_path('Demo', 'index.php?m=demo&d=admin');
							sm_use('ui.interface');
							sm_title('Demo');
							$ui = new TInterface();
							$ui->a('index.php?m=demo', 'View Demos');
							$ui->Output(true);

						}
					if (sm_action('install'))
						{
							sm_register_module('demo', $lang['module_demo']['module_demo']);
							sm_redirect('index.php?m=admin&d=modules');
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('demo');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
		}
	if (!sm_is_installed(sm_current_module()) && $userinfo['level'] == 3)
		{
			if (sm_action('install'))
				{
					sm_register_module('demo', 'Demo');
					sm_redirect('index.php?m=admin&d=modules');
				}
		}

?>