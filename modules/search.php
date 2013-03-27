<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|         Система керування вмістом сайту SiMan CMS                          |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//|               (c) Aged Programmer's Group                                  |
//|                http://www.apserver.org.ua                                  |
//|                                                                            |
//------------------------------------------------------------------------------

//==============================================================================
//#ver 1.6.1.3                                                                 |
//==============================================================================


if (empty($modules[$modules_index]["mode"])) $modules[$modules_index]["mode"]='search';

$modules[$modules_index]["module"]='search';

if (strcmp($modules[$modules_index]["mode"], 'search')==0)
	{
		if (!empty($modules[$modules_index]["bid"]))
			{
				$modules[$modules_index]["mode"]='shortview';
			}
		else
			{
				sm_event('beforesearch', array($_getvars['q']));
				$_getvars['q']=trim($_getvars['q']);
				while (strpos($_getvars['q'], '  '))
					str_replace('  ', ' ', $_getvars['q']);
				$_getvars['q']=addslashesJ($_getvars['q']);
				$special['search_text']=$_getvars['q'];
				if (empty($_getvars['q']))
					$modules[$modules_index]['title']=$lang['module_search']['search'];
				else
					{
						$modules[$modules_index]['title']=$lang['module_search']['search_results'];
						
						$sql="SELECT * FROM ".$tableprefix."modules";
						$result=database_db_query($nameDB, $sql, $lnkDB);
						$srch_elem=0;
						$i=0;
						while ($row=database_fetch_object($result))
							{
								$from_record=$_getvars['from'];
								if (empty($from_record)) $from_record=0;
								$from_page=ceil( ($from_record+1) / $_settings['search_items_by_page'] );
								$modules[$modules_index]['pages']['url']='index.php?m=search&q='.$_getvars['q'];
								$modules[$modules_index]['pages']['selected']=$from_page;
								$modules[$modules_index]['pages']['interval']=$_settings['search_items_by_page'];
								if (empty($row->search_doing)) continue;
								$srch_table=$tableprefix.$row->search_table;
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
										if ($j!=0) $filter.=' OR ';
										$filter.=' (';
										for ($k=0; $k<count($srch_query); $k++)
											{
												if ($k!=0) $filter.=$srch_mode;
												if ($srch_comparefull==1)
													{
														$filter.=$srch_fields[$j].'LIKE \''.$srch_query[$k].'\'';
													}
												else
													{
														$filter.=$srch_fields[$j].' LIKE \'%'.$srch_query[$k].'%\'';
													}
											}
										$filter.=')';
									}
								if (strcmp($srch_module, 'content')==0)
									$sql='SELECT '.$tableprefix.'content.* FROM '.$tableprefix.'content, '.$tableprefix.'categories WHERE '.$tableprefix.'content.id_category_c='.$tableprefix.'categories.id_category AND '.$tableprefix.'categories.can_view<='.$userinfo['level']." AND ($filter)";
								else
									$sql="SELECT * FROM $srch_table WHERE $filter";
								$srresult=database_db_query($nameDB, $sql, $lnkDB);
								while ($srrow=database_fetch_array($srresult))
									{
										if ($from_record<=$i && $i<$from_record+$_settings['search_items_by_page'])
											{
												$modules[$modules_index]['search'][$srch_elem]['title']=$srrow[$srch_title];
												$modules[$modules_index]['search'][$srch_elem]['url']='index.php?m='.$srch_module.'&d='.$srch_doing.'&'.$srch_var.'='.$srrow[$srch_idfield];
												$modules[$modules_index]['search'][$srch_elem]['text']=strip_tags($srrow[$srch_text]);
												if (strlen($modules[$modules_index]['search'][$srch_elem]['text'])>250)
													$modules[$modules_index]['search'][$srch_elem]['text']=substr($modules[$modules_index]['search'][$srch_elem]['text'], 0, 250).'...';
												if (empty($modules[$modules_index]['search'][$srch_elem]['title']))
													$modules[$modules_index]['search'][$srch_elem]['title']=$row->module_title;
												$srch_elem++;
											}
										$i++;
									}
								$modules[$modules_index]['result_count']=$i;
								$modules[$modules_index]['pages']['records']=$modules[$modules_index]['result_count'];
								$modules[$modules_index]['pages']['pages']=ceil($modules[$modules_index]['pages']['records'] / $_settings['search_items_by_page']);
							}
					}
				sm_event('aftersearch', array($_getvars['q']));
			}
	}

if (strcmp($modules[$modules_index]["mode"], 'shortview')==0)
	{
		$modules[$modules_index]['title']=$lang['module_search']['search'];
	}

if ($userinfo['level']==3)
	{
		if (strcmp($modules[$modules_index]["mode"], 'admin')==0)
			{
				$modules[$modules_index]['title']=$lang['settings'];
			}
	}

?>