<?php
	
	if (!defined("SMBlock_DEFINED"))
		{
			Class SMBlock
				{
					protected $info;
					
					function __construct($id_or_cachedinfo)
						{
							if (is_array($id_or_cachedinfo))
								$this->info = $id_or_cachedinfo;
							else
								$this->info = TQuery::ForTable(sm_table_prefix().'blocks')->AddWhere('id_block', intval($id_or_cachedinfo))->Get();
						}
					
					function ID()
						{
							return intval($this->info['id_block']);
						}
					
					function Exists()
						{
							return !empty($this->info['id_block']);
						}
					
					
					function Panel()
						{
							return $this->info['panel_block'];
						}
					
					function SetPanel($val)
						{
							$this->UpdateValues(Array('panel_block' => $val));
						}
					
					
					function Position()
						{
							return intval($this->info['position_block']);
						}
					
					function SetPosition($val)
						{
							$this->UpdateValues(Array('position_block' => intval($val)));
						}
					
					
					function ModuleName()
						{
							return $this->info['name_block'];
						}
					
					function SetModuleName($val)
						{
							$this->UpdateValues(Array('name_block' => $val));
						}
					
					
					function Caption()
						{
							return $this->info['caption_block'];
						}
					
					function SetCaption($val)
						{
							$this->UpdateValues(Array('caption_block' => $val));
						}
					
					
					function ActionIDValue()
						{
							return intval($this->info['showed_id']);
						}
					
					function SetActionIDValue($val)
						{
							$this->UpdateValues(Array('showed_id' => intval($val)));
						}
					
					
					function HasActionIDValue()
						{
							return !empty($this->info['showed_id']);
						}
					
					
					function Level()
						{
							return intval($this->info['level']);
						}
					
					function SetLevel($val)
						{
							$this->UpdateValues(Array('level' => intval($val)));
						}
					
					
					function ShowOnModule()
						{
							return $this->info['show_on_module'];
						}
					
					function SetShowOnModule($val)
						{
							$this->UpdateValues(Array('show_on_module' => $val));
						}
					
					
					function ShowOnAction()
						{
							return $this->info['show_on_doing'];
						}
					
					function SetShowOnAction($val)
						{
							$this->UpdateValues(Array('show_on_doing' => $val));
						}
					
					
					function ShowOnCtgID()
						{
							return intval($this->info['show_on_ctg']);
						}
					
					function SetShowOnCtgID($val)
						{
							$this->UpdateValues(Array('show_on_ctg' => intval($val)));
						}
					
					
					function isDontShowModifier()
						{
							return !empty($this->info['dont_show_modif']);
						}
					
					function SetDontShowModifier($val = true)
						{
							$this->UpdateValues(Array('dont_show_modif' => $val ? 1 : 0));
						}
					
					
					function UnsetDontShowModifier()
						{
							$this->UpdateValues(Array('dont_show_modif' => 0));
						}
					
					
					function ActionValue()
						{
							return $this->info['doing_block'];
						}
					
					function SetActionValue($val)
						{
							$this->UpdateValues(Array('doing_block' => $val));
						}
					
					
					function HasActionValue()
						{
							return !empty($this->info['doing_block']);
						}
					
					
					function isNoBorders()
						{
							return !empty($this->info['no_borders']);
						}
					
					function SetNoBorders($val = true)
						{
							$this->UpdateValues(Array('no_borders' => $val ? 1 : 0));
						}
					
					
					function UnsetNoBorders()
						{
							$this->UpdateValues(Array('no_borders' => 0));
						}
					
					
					function RewriteTitleTo()
						{
							return $this->info['rewrite_title'];
						}
					
					function SetRewriteTitleTo($val)
						{
							$this->UpdateValues(Array('rewrite_title' => $val));
						}
					
					
					function HasRewriteTitleTo()
						{
							return !empty($this->info['rewrite_title']);
						}
					
					
					function GroupsViewNLList()
						{
							return $this->info['groups_view'];
						}
					
					function SetGroupsView($val)
						{
							$this->UpdateValues(Array('groups_view' => $val));
						}
					
					
					function HasGroupsView()
						{
							return !empty($this->info['groups_view']);
						}
					
					
					function ThisLevelOnlyValue()
						{
							return !empty($this->info['thislevelonly']);
						}
					
					function SetThisLevelOnlyValue($val = true)
						{
							$this->UpdateValues(Array('thislevelonly' => intval($val)));
						}
					
					

					function ShowOnDevice()
						{
							return $this->info['show_on_device'];
						}
					
					function SetShowOnDevice($val)
						{
							$this->UpdateValues(Array('show_on_device' => $val));
						}
					
					
					function HasShowOnDevice()
						{
							return !empty($this->info['show_on_device']);
						}
					
					
					function ShowOnViewIDs()
						{
							return $this->info['show_on_viewids'];
						}
					
					function SetShowOnViewIDs($val)
						{
							$this->UpdateValues(Array('show_on_viewids' => $val));
						}
					
					
					function HasShowOnViewIDs()
						{
							return !empty($this->info['show_on_viewids']);
						}
					
					
					function Classname()
						{
							return $this->info['classname_block'];
						}
					
					function SetClassname($val)
						{
							$this->UpdateValues(Array('classname_block' => $val));
						}
					
					
					function HasClassname()
						{
							return !empty($this->info['classname_block']);
						}
					
					
					function StaticText()
						{
							return $this->info['text_block'];
						}
					
					function SetStaticText($val)
						{
							$this->UpdateValues(Array('text_block' => $val));
						}
					
					
					function HasStaticText()
						{
							return !empty($this->info['text_block']);
						}
					
					
					function EditSourceURL()
						{
							return $this->info['editsource_block'];
						}
					
					function SetEditSourceURL($val)
						{
							$this->UpdateValues(Array('editsource_block' => $val));
						}
					
					
					function HasEditSourceURL()
						{
							return !empty($this->info['editsource_block']);
						}
					
					
					function ShowOnTheme()
						{
							return $this->info['showontheme'];
						}
					
					function SetShowOnTheme($val)
						{
							$this->UpdateValues(Array('showontheme' => $val));
						}
					
					
					function HasShowOnTheme()
						{
							return !empty($this->info['showontheme']);
						}
					
					
					function ShowOnLanguage()
						{
							return $this->info['showonlang'];
						}
					
					function SetShowOnLanguage($val)
						{
							$this->UpdateValues(Array('showonlang' => $val));
						}
					
					
					function HasShowOnLanguage()
						{
							return !empty($this->info['showonlang']);
						}
					
					
					function UpdateValues($params)
						{
							unset($params['id_block']);
							if (empty($params) || !is_array($params))
								return;
							$q = new TQuery(sm_table_prefix().'blocks');
							foreach ($params as $key => $val)
								{
									$this->info[$key] = $val;
									$q->Add($key, dbescape($this->info[$key]));
								}
							$q->Update('id_block', intval($this->ID()));
						}
					
					public static function CreateNonVisibleBlock($panel_index=0)
						{
							if (intval($panel_index)==0)
								$panel_index='c';
							$q = new TQuery(sm_table_prefix().'blocks');
							$q->Add('panel_block', dbescape($panel_index));
							$q->Add('position_block', intval(TQuery::ForTable(sm_table_prefix().'blocks')->AddWhere('panel_block', dbescape($panel_index))->SelectFields('max(position_block) as mp')->Limit(1)->Select()->items[0]['mp'])+1);
							$q->Add('level', 999999);
							$q->Add('show_on_viewids', '');//For compatibility with not updated data strcucture
							$block=new SMBlock($q->Insert());
							return $block;
						}
					
				}
			
			define("SMBlock_DEFINED", 1);
		}
