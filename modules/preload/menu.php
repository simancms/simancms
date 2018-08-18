<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#ver 1.6.10
	//#revision 2016-02-02
	//==============================================================================

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	class SMNavigation
		{
			var $items=Array();
			var $current_item;
			var $menu_id;
			var $menupointer;

			function __construct(&$pointer, $menu_id='')
				{
					$this->menupointer=&$pointer;
					$this->menu_id=$menu_id;
				}
			
			function Clear()
				{
					$this->items=Array();
					$this->Rebuild();
				}

			function AddRootItem($title='', $url='', $id=NULL)
				{
					if ($id===NULL)
						$id='mi-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999);
					$this->items[]=Array(
						'id'=>$id,
						'level'=>1,
						'title'=>$title,
						'root'=>NULL,
						'url'=>$url,
						'partial'=>false
					);
					$this->current_item=&$this->items[count($this->items)-1];
					$this->current_item['active']=$this->isActive($this->current_item);
					$this->current_item['opened']=$this->current_item['active'];
					return $this;
				}

			function AddSubItem($root_id, $title='', $url='', $id=NULL)
				{
					if (!$this->SetCurrentItemByID($root_id, $this->items))
						{
							return $this->AddRootItem($title, $url, $id);
						}
					if ($id===NULL)
						$id='msi-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999).'-'.mt_rand(1111, 9999);
					$this->current_item['items'][]=Array(
						'id'=>$id,
						'level'=>$this->current_item['level']+1,
						'title'=>$title,
						'root_id'=>$this->current_item['id'],
						'url'=>$url,
						'partial'=>false
					);
					$this->SetCurrentItemByID($id, $this->current_item['items']);
					$this->current_item['active']=$this->isActive($this->current_item);
					$this->current_item['opened']=$this->current_item['active'];
					if ($this->current_item['opened'])
						{
							$this->SetOpened($this->current_item['root_id']);
							$this->SetCurrentItemByID($id, $this->items);
						}
					return $this;
				}

			protected function CheckActive()
				{
					$this->current_item['active']=$this->isActive($this->current_item);
					$this->current_item['opened']=$this->current_item['active'];
					if ($this->current_item['opened'] && !empty($this->current_item['root_id']))
						{
							$id=$this->CurrentItemID();
							$this->SetOpened($this->current_item['root_id']);
							$this->SetCurrentItemByID($id, $this->items);
						}
				}

			protected function SetOpened($id=NULL)
				{
					if ($id!==NULL)
						$this->SetCurrentItemByID($id, $this->items);
					$this->current_item['opened']=true;
					if (!empty($this->current_item['root_id']))
						$this->SetOpened($this->current_item['root_id']);
				}

			protected function SetPosition($position)
				{
					$this->current_item['position']=$position;
				}

			function SetPartialActive($partial=true)
				{
					$this->current_item['partial']=$partial;
					$this->CheckActive();
				}

			function SetAlt($value)
				{
					$this->current_item['alt']=$value;
				}

			function SetHTMLAttributes($value)
				{
					$this->current_item['html_attributes']=$value;
				}

			function SetHTMLBegin($value)
				{
					$this->current_item['html_begin']=$value;
				}

			function SetHTMLEnd($value)
				{
					$this->current_item['html_end']=$value;
				}

			function SetOpenInNewPage($value=true)
				{
					$this->current_item['newpage']=$value;
				}

			protected function SetCurrentItemByID($id, &$items)
				{
					for ($i = 0; $i<count($items); $i++)
						{
							if (strcmp($items[$i]['id'], $id)==0)
								{
									$this->current_item=&$items[$i];
									return true;
								}
							if (is_array($items[$i]['items']))
								if ($this->SetCurrentItemByID($id, $items[$i]['items']))
									return true;
						}
					return false;
				}

			function CurrentItemID()
				{
					return $this->current_item['id'];
				}

			protected function isActive(&$item)
				{
					global $sm;
					if (empty($item['url']))
						return false;
					$tmp_index = strpos(sm_settings('resource_url'), '/');
					$main_suburl = substr(sm_settings('resource_url'), $tmp_index);
					if (
						(strcmp($main_suburl.$item['url'], $sm['server']['REQUEST_URI']) == 0
						||
						strcmp($main_suburl.$item['url'], $sm['server']['REQUEST_URI'].'index.php') == 0)
						|| (sm_is_index_page() && strcmp($item['url'], 'http://'.sm_settings('resource_url')) == 0)
					)
						return true;
					if ($item['partial'])
						{
							if (strpos($sm['server']['REQUEST_URI'], $main_suburl.$item['url']) === 0)
								return true;
						}
					return false;
				}

			protected function RebuildItems(&$items)
				{
					for ($i = 0; $i<count($items); $i++)
						{
							$index=count($this->menupointer);
							$this->menupointer[$index]['id'] = $items[$i]['id'];
							$this->menupointer[$index]['first'] = $i==0?1:0;
							$this->menupointer[$index]['last'] = $i==count($items[$i]['items'])-1?1:0;
							$this->menupointer[$index]['mid'] = $this->menu_id;
							$this->menupointer[$index]['pos'] = $items[$i]['position'];
							$this->menupointer[$index]['submenu_position'] = $i;
							$this->menupointer[$index]['add_param'] = $this->menu_id.'|'.$items[$i]['id'];
							$this->menupointer[$index]['level'] = $items[$i]['level'];
							$this->menupointer[$index]['submenu_from'] = $items[$i]['root_id'];
							$this->menupointer[$index]['sublines_count'] = count($items[$i]['items']);
							$this->menupointer[$index]['is_submenu'] = count($items[$i]['items'])>0?1:0;
							$this->menupointer[$index]['url'] = $items[$i]['url'];
							$this->menupointer[$index]['caption'] = $items[$i]['title'];
							$this->menupointer[$index]['partial'] = $items[$i]['partial'];
							$this->menupointer[$index]['alt'] = $items[$i]['alt'];
							$this->menupointer[$index]['attr'] = $items[$i]['html_attributes'];
							$this->menupointer[$index]['newpage'] = $items[$i]['newpage'];
							$this->menupointer[$index]['html_begin'] = $items[$i]['html_begin'];
							$this->menupointer[$index]['html_end'] = $items[$i]['html_end'];
							if (intval(sm_settings('menuitems_use_image')) == 1)
								{
									if (file_exists('./files/img/menuitem'.$this->menupointer[$index]['id'].'.jpg'))
										$this->menupointer[$index]['image'] = 'files/img/menuitem'.$this->menupointer[$index]['id'].'.jpg';
								}
							$this->menupointer[$index]['opened']=$items[$i]['opened']?1:0;
							$this->menupointer[$index]['active']=$items[$i]['active']?1:0;
							if (is_array($items[$i]['items']))
								$this->RebuildItems($items[$i]['items']);
						}
				}

			function Rebuild()
				{
					$this->menupointer=Array();
					$this->RebuildItems($this->items);
				}

			function LoadFromDB()
				{
					global $sm, $row;
					$q=new TQuery($sm['t'].'menu_lines');
					$q->AddWhere('id_menu_ml', intval($this->menu_id));
					$q->OrderBy('submenu_from, position');
					$q->Open();
					while ($row = $q->Fetch())
						{
							sm_event('onbeforemenulineprocessing', $row['id_ml']);
							if (empty($row['submenu_from']))
								$this->AddRootItem($row['caption_ml'], $row['url'], $row['id_ml']);
							else
								$this->AddSubItem($row['submenu_from'], $row['caption_ml'], $row['url'], $row['id_ml']);
							$this->SetPosition($row['position']);
							if (intval($row['partial_select'])==1)
								$this->SetPartialActive();
							$this->SetAlt($row['alt_ml']);
							$this->SetHTMLAttributes($row['attr_ml']);
							$this->SetOpenInNewPage($row['newpage_ml']);
						}
					$this->Rebuild();
				}
		}

	function siman_add_modifier_menu(&$menu)
		{
			for ($i = 0; $i<count($menu); $i++)
				{
					sm_add_content_modifier($menu[$i]['caption']);
				}
		}

	if (!function_exists('siman_load_menu'))
		{
			function siman_menu_open_detect(&$menu, $opened_id)
				{
					if (intval($opened_id) == 0)
						return;
					for ($i = 0; $i<count($menu); $i++)
						{
							if (intval($menu[$i]['id']) == intval($opened_id) && intval($opened_id) != intval($menu[$i]['submenu_from']))
								{
									$menu[$i]['opened'] = true;
									siman_menu_open_detect($menu, $menu[$i]['submenu_from']);
								}
						}
				}

			function siman_load_menu($menu_id, $maxlevel = -1)
				{
					$rmenu=Array();
					$nav=new SMNavigation($rmenu, $menu_id);
					$nav->LoadFromDB();
					return $rmenu;
				}
		}

	function sm_add_menuitem(&$menu, $title, $url, $level = 1, $partial_select = '', $alt_text = '', $newpage = 0)
		{
			global $sm;
			$i = count($menu);
			$menu[$i]['url'] = $url;
			$menu[$i]['caption'] = $title;
			$menu[$i]['partial'] = $partial_select;
			$menu[$i]['level'] = $level;
			$menu[$i]['alt'] = $alt_text;
			$menu[$i]['newpage'] = $newpage;
			if ($level == 1)
				{
					$menu[$i]['last'] = 1;
					if ($i == 0)
						$menu[$i]['first'] = 1;
					else
						$menu[$i-1]['last'] = 0;
				}
			$tmp_index = strpos(sm_settings('resource_url'), '/');
			$main_suburl = substr(sm_settings('resource_url'), $tmp_index);
			if (
				(strcmp($main_suburl.$menu[$i]['url'], $sm['server']['REQUEST_URI']) == 0
				||
				strcmp($main_suburl.$menu[$i]['url'], $sm['server']['REQUEST_URI'].'index.php') == 0)
				|| (sm_is_index_page() && strcmp($menu[$i]['url'], 'http://'.sm_settings('resource_url')) == 0)
			)
				$menu[$i]['active'] = '1';
			if ($menu[$i]['active'] != '1' && $menu[$i]['partial'] == 1)
				{
					if (strpos($sm['server']['REQUEST_URI'], $main_suburl.$menu[$i]['url']) === 0)
						$menu[$i]['active'] = '1';
				}
			if (empty($menu[$i]['url']))
				$menu[$i]['active'] = '0';
		}

	if (!sm_empty_settings('upper_menu_id'))
		{
			$sm['contols']['uppermenu']=new SMNavigation($sm['s']['uppermenu'], sm_settings('upper_menu_id'));
			$sm['contols']['uppermenu']->LoadFromDB();
			siman_add_modifier_menu($sm['s']['uppermenu']);
		}

	if (!sm_empty_settings('bottom_menu_id'))
		{
			$sm['contols']['bottommenu']=new SMNavigation($sm['s']['bottommenu'], sm_settings('bottom_menu_id'));
			$sm['contols']['bottommenu']->LoadFromDB();
			siman_add_modifier_menu($sm['s']['bottommenu']);
		}

?>