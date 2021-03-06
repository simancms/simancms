<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: Search
	Module URI: http://simancms.org/modules/search/
	Description: Search module. Base CMS module
	Version: 1.6.16
	Revision: 2018-08-01
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		exit('Hacking attempt!');

	sm_default_action('search');

	if (intval(sm_settings('search_module_disabled'))==0)
		{
			if (sm_action('search'))
				{
					if (!empty($m["bid"]))
						{
							sm_set_action('shortview');
						}
					else
						{
							sm_template('search');
							sm_event('beforesearch', array($_getvars['q']));
							$_getvars['q']=trim($_getvars['q']);
							while (strpos($_getvars['q'], '  '))
								str_replace('  ', ' ', $_getvars['q']);
							$special['search_text']=$_getvars['q'];
							if (empty($_getvars['q']))
								sm_title($lang['module_search']['search']);
							else
								{
									sm_title($lang['module_search']['search_results']);
									$result=execsql("SELECT * FROM ".sm_table_prefix()."modules");
									$srch_elem=0;
									$i=0;
									while ($row=database_fetch_object($result))
										{
											$from_record=abs(intval($_getvars['from']));
											if (empty($from_record))
												$from_record=0;
											$from_page=ceil(($from_record+1)/sm_settings('search_items_by_page'));
											if (empty($row->search_doing))
												continue;
											$srch_table=sm_table_prefix().$row->search_table;
											$srch_module=$row->module_name;
											$srch_doing=$row->search_doing;
											$srch_var=$row->search_var;
											$srch_title=$row->search_title;
											$srch_fields=$row->search_fields;
											$srch_idfield=$row->search_idfield;
											$srch_text=$row->search_text;
											$srch_mode=' AND ';
											$srch_comparefull=0;
											$srch_fields=explode(' ', $srch_fields);
											$srch_query=explode(' ', $_getvars['q']);
											$filter='';
											for ($j=0; $j<count($srch_fields); $j++)
												{
													if ($j!=0)
														$filter.=' OR ';
													$filter.=' (';
													for ($k=0; $k<count($srch_query); $k++)
														{
															if ($k!=0)
																$filter.=$srch_mode;
															if ($srch_comparefull==1)
																{
																	$filter.=$srch_fields[$j].'LIKE \''.dbescape($srch_query[$k]).'\'';
																}
															else
																{
																	$filter.=$srch_fields[$j].' LIKE \'%'.dbescape($srch_query[$k]).'%\'';
																}
														}
													$filter.=')';
												}
											if (strcmp($srch_module, 'content')==0)
												$sql='SELECT '.sm_table_prefix().'content.* FROM '.sm_table_prefix().'content, '.sm_table_prefix().'categories WHERE '.sm_table_prefix().'content.id_category_c='.sm_table_prefix().'categories.id_category AND '.sm_table_prefix().'categories.can_view<='.intval($userinfo['level'])." AND refuse_direct_show=0 AND disable_search=0 AND ($filter)";
											elseif (strcmp($srch_module, 'news')==0)
												$sql="SELECT * FROM $srch_table WHERE disable_search=0 AND ($filter)";
											else
												$sql="SELECT * FROM $srch_table WHERE $filter";
											$srresult=execsql($sql);
											while ($srrow=database_fetch_array($srresult))
												{
													if ($from_record<=$i && $i<$from_record+sm_settings('search_items_by_page'))
														{
															$m['search'][$srch_elem]['title']=$srrow[$srch_title];
															$m['search'][$srch_elem]['url']=sm_fs_url('index.php?m='.$srch_module.'&d='.$srch_doing.'&'.$srch_var.'='.$srrow[$srch_idfield]);
															$m['search'][$srch_elem]['text']=strip_tags($srrow[$srch_text]);
															if (strlen($m['search'][$srch_elem]['text'])>250)
																$m['search'][$srch_elem]['text']=substr($m['search'][$srch_elem]['text'], 0, 250).'...';
															if (empty($m['search'][$srch_elem]['title']))
																$m['search'][$srch_elem]['title']=$row->module_title;
															$srch_elem++;
														}
													$i++;
												}
											$m['result_count']=$i;
											sm_pagination_init($m['result_count'], sm_settings('search_items_by_page'), $from_record, 'index.php?m=search&q='.urlencode($_getvars['q']));
										}
								}
							sm_event('aftersearch', array($_getvars['q']));
						}
				}

			if (sm_action('shortview'))
				{
					sm_template('search');
					sm_title($lang['module_search']['search']);
				}
		}

	if ($userinfo['level']==3)
		include('modules/inc/adminpart/search.php');
