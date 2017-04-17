<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: SiMan CMS Demo
	Module URI: http://simancms.org/modules/demo/
	Description: Examples of usage
	Version: 1.6.14
	Author: SiMan CMS Team
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	if (!defined("demo_FUNCTIONS_DEFINED"))
		{
			define("demo_FUNCTIONS_DEFINED", 1);
		}

	if (sm_is_installed(sm_current_module()) && ($userinfo['level'] > 0 || intval(sm_settings('demo_public')) > 0))
		{
			sm_default_action('demos');
			if (sm_action('htmlshortcuts', 'forms', 'grid', 'regular', 'buttons', 'modal', 'exchangelistener', 'exchangesender', 'fa', 'uitabs'))
				sm_delayed_action('demo', 'footercode');
			//start-htmlshortcuts
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
			//end-htmlshortcuts
			//start-uitabs
			if (sm_action('uitabs'))
				{
					sm_title('UI Tabs');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.tabs');
					$ui = new TInterface();
					$tabs=new TTabs();
					$tabs->Tab('Tab 1');
					$tabs->p('First tab');
					$tabs->Tab('Tab 2');
					$tabs->p('Second tab');
					$tabs->Tab('Tab With URL', 'index.php?m=demo');
					$ui->Add($tabs);
					$ui->Output(true);
				}
			//end-uitabs
			//start-fa
			if (sm_action('fa'))
				{
					sm_title('UI Font Awesome Helper');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.fa');
					$ui = new TInterface();
					$ui->p(FA::EmbedCodeFor('star').' - Star');
					$icon=FA::Icon('database');
					$ui->p($icon->Code());
					$icon->Size('2x');
					$ui->p($icon->Code());
					$icon->Size('3x');
					$ui->p($icon->Code());
					$icon->Size('4x');
					$ui->p($icon->Code());
					$icon->Size('5x');
					$ui->p($icon->Code());
					$ui->Output(true);
				}
			//end-fa
			//start-buttons
			if (sm_action('buttons'))
				{
					sm_title('UI Buttons');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.buttons');
					$ui = new TInterface();
					$b=new TButtons();
					$b->Button('Regular Button', 'index.php?m=demo&d=buttons');
					$b->MessageBox('Confirmarion (default)', 'index.php?m=demo&d=buttons');
					$b->MessageBox('Confirmarion (custom)', 'index.php?m=demo&d=buttons', 'Are you sure you want to visit this page?');
					$b->Button('Bold', 'index.php?m=demo&d=buttons')->Bold();
					$b->Button('Custom Class', 'index.php?m=demo&d=buttons')->AddClassname('btn-danger');
					$b->AddButton('cst', 'Custom Style', 'index.php?m=demo&d=buttons');
					$b->Style('cst', 'text-decoration:underline; color:#00aa00;');
					$b->Button('Dropdown');
					$b->DropDownItem('Dropdown URL', 'http://simancms.org/');
					$b->DropDownItem('Dropdown URL target=_blank', 'http://simancms.org/', true);
					$b->DropDownSeparator();
					$b->DropDownOnClick('Dropdown OnClick', "alert('OnClick');");
					$b->DropDownMessageBox('Dropdown Confirmation', 'http://simancms.org/');
					$ui->Add($b);
					$ui->Output(true);
				}
			//end-buttons
			//start-modal
			if (sm_action('modal'))
				{
					sm_title('UI Modal Helper');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.buttons');
					sm_use('ui.modal');
					$ui = new TInterface();
					$b=new TButtons();
					//--------------------------------
					$modal1=new TModalHelper();
					$modal1->SetAJAXSource('index.php?m=demo&d=ajaxresponder&ajax=1');
					$b->Button('Modal with AJAX');
					$b->OnClick($modal1->GetJSCode());
					//--------------------------------
					$modal2=new TModalHelper();
					$modal2->SetContent('Hardocded HTML content, custom with and height');
					$modal2->SetWidth('200px');
					$modal2->SetHeight('10%');
					$b->Button('Modal with Hardocded Content/Dimensions');
					$b->OnClick($modal2->GetJSCode());
					//--------------------------------
					$modal3=new TModalHelper();
					$modal3->SetContentDOMSource('#hiddendiv');
					$b->Button('Modal with DOM Content and Close Helper');
					$b->OnClick($modal3->GetJSCode());
					//--------------------------------
					$ui->Add($b);
					$ui->div('Hidden DOM-element used as content for modal. Click to <a href="javascript:;" onclick="'.TModalHelper::GetCloseJSCode().'">close</a>', 'hiddendiv', '', 'display:none;');
					$ui->Output(true);
				}
			//end-modal
			//start-forms
			if (sm_action('forms'))
				{
					sm_title('UI TForm - From');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.form');
					$values = Array(
						'text' => 'Text',
						'select' => 2,
						'checkbox1' => 1,
						'checkbox3' => '+'
					);
					$ui = new TInterface();
					$f = new TForm('index.php?m=demo&d=forms');
					$f->AddText('text', 'Text field')->SetFocus();
					$f->AddText('calendar', 'Text field with calendar')->Calendar();
					$f->AddSelectVL('select', 'Select field', Array(1, 2, 3), Array('Label 1', 'Label 2', 'Label 3'));
					$f->AddTextarea('textarea', 'Textarea field');
					$f->Separator('Checkboxes');
					$f->AddCheckbox('checkbox1', 'Checkbox 1');
					$f->AddCheckbox('checkbox2', 'Checkbox 2 (label after control)');
					$f->LabelAfterControl();
					$f->AddCheckbox('checkbox3', 'Checkbox 3 (custom value)', '+');
					$f->Separator('Separator');
					$f->AddEditor('editor', 'Editor');
					$f->SetSaveButtonHelperText('Some submit buutton note (optional)');
					$f->SaveButton('Custom Submit Button Title');
					$f->LoadValuesArray($values);
					$f->SetValue('textarea', 'Custom value');
					$ui->Add($f);
					$ui->h(2, 'Form without action and submission');
					$f2=new TForm(false);
					$f2->AddText('dummy_field', 'Some Field')->WithValue('Some value');
					$ui->Add($f2);
					$ui->Output(true);
				}
			//end-forms
			if (sm_action('ajaxresponder'))
				{
					out(strftime($lang['datetimemask'], time()).'<br />');
					for ($i = 0; $i < 5 + rand(1, 10); $i++)
						{
							out('Line '.$i.'<br />');
						}
				}
			//start-grid
			if (sm_action('grid'))
				{
					sm_title('UI TGrid - Table');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.grid');
					$src = Array(
						Array(
							'text' => 'Sample text 0',
							'url' => ''
						)
					);
					for ($i = 1; $i < 21; $i++)
						{
							$src[] = Array(
								'text' => 'Sample text '.$i,
								'url' => 'index.php?m=demo&d=grid&testid='.$i,
								'expand' => 'Expander for row #'.$i
							);
						}
					$ui = new TInterface();
					$t = new TGrid();
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
							if ($i == 1)
								{
									$t->RowHighlightError();
									$t->Label('note', 'Error for row');
									$t->CellAlignCenter('note');
								}
							if ($i == 2)
								{
									$t->RowHighlightInfo();
									$t->Label('note', 'Info for row');
									$t->CellAlignCenter('note');
								}
							if ($i == 3)
								{
									$t->RowHighlightSuccess();
									$t->Label('note', 'Success for row');
									$t->CellAlignCenter('note');
								}
							if ($i == 4)
								{
									$t->RowHighlightWarning();
									$t->Label('note', 'Warning for row');
									$t->CellAlignCenter('note');
								}
							if ($i == 5)
								{
									$t->RowHighlightAttention();
									$t->Label('note', 'Attention for row');
									$t->CellAlignCenter('note');
								}
							if ($i == 10)
								{
									$t->CellHighlightError('text');
									$t->Label('note', '&lt;- Error for cell');
								}
							if ($i == 11)
								{
									$t->CellHighlightInfo('text');
									$t->Label('note', '&lt;- Info for cell');
								}
							if ($i == 12)
								{
									$t->CellHighlightSuccess('text');
									$t->Label('note', '&lt;- Success for cell');
								}
							if ($i == 13)
								{
									$t->CellHighlightWarning('text');
									$t->Label('note', '&lt;- Warning for cell');
								}
							if ($i == 14)
								{
									$t->CellHighlightAttention('text');
									$t->Label('note', '&lt;- Attention for cell');
								}
							$t->Label('n', $i);
							$t->Label('text', $src[$i]['text']);
							$t->URL('text', $src[$i]['url']);
							$t->Image('view', 'info');
							if ($i == 0)
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
							if ($i == 17)
								{
									$t->Label('note', 'Drop down menu');
									$t->DropDownItem('note', 'Item 1', 'index.php?m=demo&d=htmlshortcuts');
									$t->DropDownItem('note', 'Item 1 (confirm)', 'index.php?m=demo&d=htmlshortcuts', 'Are you sure?');
								}
							$t->Checkbox('chk1', 'chk1[]', $i, (intval($sm['g']['testid']) == $i));
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
			//end-grid
			//start-regular
			if (sm_action('regular'))
				{
					sm_title('Smarty Template');
					add_path_home();
					add_path('Demos', 'index.php?m=demo');
					add_path_current();
					$m['module'] = 'demo';
				}
			//end-regular
			//start-exchangesender
			if (sm_action('exchangesender'))
				{
					sm_use('ui.interface');
					sm_use('ui.buttons');
					sm_use('ui.exchange');
					sm_title('Modal');
					$ui = new TInterface();
					$b=new TButtons();
					$b->Button('Send Values and Close');
					$sender=new TExchangeSender($_getvars['listener']);
					$sender->Add('field1', 'Test 1');
					$sender->Add('field2', 'Test 2');
					$sender->Add('field3', 'Test 3');
					$sender->SetCloseWindowRequest();
					$b->OnClick($sender->GetJSCode());
					$ui->Add($b);
					$ui->Output(true);
				}
			//end-exchangesender
			//start-exchangelistener
			if (sm_action('exchangelistener'))
				{
					sm_use('ui.interface');
					sm_use('ui.form');
					sm_use('ui.exchange');
					sm_title('UI Exchange Listener');
					$ui = new TInterface();
					$f = new TForm(false);
					$f->AddText('field1', 'Field 1');
					$f->AddText('field2', 'Field 2');
					$f->AddText('field3', 'Field 3');
					$ui->Add($f);
					$listener=new TExchangeListener();
					$listener->Add('field1');
					$listener->Add('field2');
					$listener->Add('field3');
					$ui->javascript($listener->GetJSCode());
					unset($listener);
					$ui->AddBlock('Exchange');
					$ui->a('index.php?m=demo&d=exchangesender&listener='.sm_pageid(), 'Open Page with Sender', '', 'btn btn-default', '', '', 'target="_blank"');
					$ui->Output(true);
				}
			//end-exchangelistener
			//start-demos
			if (sm_action('demos'))
				{
					sm_title('Available Demos');
					add_path_home();
					add_path_current();
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$nav = new TNavigation();
					$nav->AddItem('Smarty Template', 'index.php?m=demo&d=regular');
					$nav->AddItem('UI HTML-shortcuts', 'index.php?m=demo&d=htmlshortcuts');
					$nav->AddItem('UI TGrid - Table', 'index.php?m=demo&d=grid');
					$nav->AddItem('UI TForm - From', 'index.php?m=demo&d=forms');
					$nav->AddItem('UI TButtons - Buttons', 'index.php?m=demo&d=buttons');
					$nav->AddItem('UI TTabs - Tabs', 'index.php?m=demo&d=uitabs');
					$nav->AddItem('UI TModalHelper - Modal Helper', 'index.php?m=demo&d=modal');
					$nav->AddItem('UI TExchangeListener/TExchangeSender - Exchange values between pages', 'index.php?m=demo&d=exchangelistener');
					$nav->AddItem('UI FA - Font Awesome Helper', 'index.php?m=demo&d=fa');
					$ui->Add($nav);
					$ui->Output(true);
				}
			//end-demos
			if (sm_action('footercode'))
				{
					$action=$_getvars['d'];
					$str=file_get_contents(__FILE__);
					if (strpos($str, '//start-'.$action)!==false && strpos($str, '//end-'.$action)!==false)
						$code=substr($str, strpos($str, '//start-'.$action)+9+strlen($action), strpos($str, '//end-'.$action)-(strpos($str, '//start-'.$action)+9+strlen($action)));
					sm_title('Code Example');
					sm_use('ui.interface');
					sm_use('ui.navigation');
					$ui = new TInterface();
					$ui->html('<textarea wrap="off" style="width:99%; height:150px;">'.htmlescape($code).'</textarea>');
					$ui->Output(true);
				}
			if ($userinfo['level'] == 3)
				{
					if (sm_action('updatesettings'))
						{
							sm_update_settings('demo_public', intval($sm['g']['public']));
							sm_redirect($sm['g']['returnto']);
						}
					if (sm_action('admin'))
						{
							add_path_modules();
							add_path('Demo', 'index.php?m=demo&d=admin');
							sm_use('ui.interface');
							sm_use('ui.buttons');
							sm_title('Demo');
							$ui = new TInterface();
							$b = new TButtons();
							$b->Button('View Demos', 'index.php?m=demo');
							if (intval(sm_settings('demo_public')) > 0)
								$b->Button('Set Public Access OFF', 'index.php?m='.sm_current_module().'&d=updatesettings&public=0&returnto='.urlencode(sm_this_url()));
							else
								$b->Button('Set Public Access ON', 'index.php?m='.sm_current_module().'&d=updatesettings&public=1&returnto='.urlencode(sm_this_url()));
							$ui->Add($b);
							$ui->Output(true);
						}
					if (sm_action('uninstall'))
						{
							sm_unregister_module('demo');
							sm_delete_settings('demo_public');
							sm_redirect('index.php?m=admin&d=modules');
						}
				}
		}
	if (!sm_is_installed(sm_current_module()) && $userinfo['level'] == 3)
		{
			if (sm_action('install'))
				{
					sm_register_module('demo', 'Demo');
					sm_add_settings('demo_public', 0);
					sm_redirect('index.php?m=admin&d=modules');
				}
		}
