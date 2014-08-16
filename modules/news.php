<?php

	//------------------------------------------------------------------------------
	//|            Content Management System SiMan CMS                             |
	//|                http://www.simancms.org                                     |
	//------------------------------------------------------------------------------

	/*
	Module Name: News
	Module URI: http://simancms.org/modules/news/
	Description: News management. Base CMS module
	Version: 1.6.7
	Revision: 2014-05-01
	Author URI: http://simancms.org/
	*/

	if (!defined("SIMAN_DEFINED"))
		{
			print('Hacking attempt!');
			exit();
		}

	if ($userinfo['level']>=(isset($_settings['news_view_level']) ? $_settings['news_view_level'] : 0))
		{ // user level view restrictions start
			$tmp_view_ctg = '';

			if (strpos($m["mode"], '|') !== false)
				{
					$tmp = explode('|', $m["mode"]);
					$m["mode"] = $tmp[0];
					$m['limitnews'] = $tmp[1];
					if (!empty($tmp[2]))
						$m["bid"] = $tmp[2];
					unset($tmp);
				}

			if ($m["bid"] == 1 && empty($m["mode"]))
				{
					$m["mode"] = 'shortnews';
					$m["bid"] = '';
				}

			if (empty($m["mode"])) $m["mode"] = 'listnews';

			$tmp_short_news = 0;


			if (sm_action('listnews') || sm_action('listdate'))
				{
					$tmp_view_ctg = $_getvars['ctg'];
				}
			elseif (sm_action('shortnews'))
				{
					$tmp_short_news = 1;
					$m["mode"] = 'listnews';
					$tmp_view_ctg = $m["bid"];
					//$_getvars['ctg']='';
				}
			elseif (sm_action('viewctg'))
				{
					$_getvars['ctg'] = $_getvars["ctgid"];
					$tmp_view_ctg = $_getvars['ctg'];
					$m["mode"] = 'listnews';
				}

			if (sm_action('listnews') || sm_action('listdate'))
				{
					$m["module"] = 'news';
					if (!empty($tmp_view_ctg))
						{
							$tmp_view_ctg_first = explode(',', $tmp_view_ctg);
							$tmp_view_ctg_first = $tmp_view_ctg_first[0];
							$sql = "SELECT * FROM ".$tableprefix."categories_news WHERE id_category=".intval($tmp_view_ctg_first);
							$result = execsql($sql);
							while ($row = database_fetch_object($result))
								{
									$m['title'] = $row->title_category;
									if ($special['categories']['getctg'] == 1)
										$special['categories']['id'] = $row->id_category;
								}
						}
					$ctg_id = $tmp_view_ctg;
					if (sm_action('listnews'))
						{
							if (!empty($ctg_id))
								sm_page_viewid('news-'.$m["mode"].'-'.$ctg_id);
							else
								sm_page_viewid('news-'.$m["mode"]);
						}
					else
						{
							sm_page_viewid('news-'.$m["mode"].'-'.$_getvars['dy'].'-'.$_getvars['dm'].'-'.$_getvars['dd']);
						}
					$from_record = abs(intval($_getvars['from']));
					$from_page = ceil(($from_record+1)/$_settings['news_by_page']);
					if (sm_action('listdate'))
						$m['pages']['url'] = 'index.php?m=news&d=listdate&dy='.$_getvars['dy'].'&dm='.$_getvars['dm'].'&dd='.$_getvars['dd'];
					elseif (!empty($tmp_view_ctg))
						$m['pages']['url'] = 'index.php?m=news&d=listnews&ctg='.$tmp_view_ctg;
					else
						$m['pages']['url'] = 'index.php?m=news&d=listnews';
					$m['pages']['selected'] = $from_page;
					$m['pages']['interval'] = $_settings['news_by_page'];
					if (empty($m['title']))
						$m['title'] = $lang['news'];
					$sql2 = " WHERE date_news<=".time();
					if (sm_action('listdate'))
						{
							if (!empty($_getvars['dy']))
								{
									if (empty($_getvars['dm']))
										{
											$tmp['monthstart'] = 1;
											$tmp['monthend'] = intval(date('m', time()));
										}
									else
										{
											$tmp['monthstart'] = intval($_getvars['dm']);
											$tmp['monthend'] = intval($_getvars['dm']);
										}
									if (empty($_getvars['dd']))
										{
											$tmp['daystart'] = 1;
											$tmp['dayend'] = date('d', mktime(23, 59, 59, ($tmp['monthend']<12 ? 1 : $tmp['monthend'] = 1), 1, ($tmp['monthend']<12 ? $_getvars['dy'] : intval($_getvars['dy'])+1))-86400);
										}
									else
										{
											$tmp['daystart'] = intval($_getvars['dd']);
											$tmp['dayend'] = intval($_getvars['dd']);
										}
									$tmp_date_filter1 = mktime(0, 0, 0, $tmp['monthstart'], $tmp['daystart'], $_getvars['dy']);
									$tmp_date_filter2 = mktime(23, 59, 59, $tmp['monthend'], $tmp['dayend'], $_getvars['dy']);
									$sql2 = " AND date_news>=$tmp_date_filter1 AND date_news<=$tmp_date_filter2 ";
								}
							$m["mode"] = 'listnews';
						}
					$m['id_category_n'] = $tmp_view_ctg;
					if (!empty($ctg_id) /* && $tmp_short_news!=1*/)
						{
							if (strpos($ctg_id, ',') !== false)
								{
									$ctg_id = explode(',', $ctg_id);
									for ($i = 0; $i<count($ctg_id); $i++)
										{
											$ctg_id[$i] = intval($ctg_id[$i]);
										}
									$ctg_id = implode(', ', $ctg_id);
									$sql2 .= " AND id_category_n IN ($ctg_id) ";
								}
							else
								{
									$sql2 .= " AND id_category_n = ".intval($ctg_id);
								}
						}
					$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."filesystem.* FROM ".$tableprefix."news LEFT JOIN ".$tableprefix."filesystem ON ".$tableprefix."news.filename_news=".$tableprefix."filesystem.id_fs";
					$sql .= " $sql2 ORDER BY date_news DESC";
					if ($tmp_short_news == 0)
						{
							$sql .= " LIMIT ".intval($_settings['news_by_page'])." OFFSET ".intval($from_record);
						}
					elseif (!empty($m['limitnews']))
						{
							$sql .= " LIMIT ".intval($m['limitnews']);
						}
					else
						{
							$sql .= " LIMIT ".intval($_settings['short_news_count']);
						}
					$result = execsql($sql);
					$i = 0;
					while ($row = database_fetch_assoc($result))
						{
							sm_event('onbeforelistnewsprocessing', $i);
							$m["newsid"][$i][0] = $row['id_news'];
							$m["newsid"][$i][1] = $row['date_news'];
							$m["newsid"][$i][1] = strftime($lang["datemask"], $m["newsid"][$i][1]);
							$m["newsid"][$i][8] = strftime($lang["timemask"], $row['date_news']);
							$m["newsid"][$i][2] = $row['text_news'];
							$m["newsid"][$i][3] = $row['text_news'];
							$m["newsid"][$i][5] = $row['title_news'];
							if ($row['filename_news'] != 0)
								{
									$m["newsid"][$i][7] = $row['filename_fs'];
								}
							else
								{
									$m["newsid"][$i][7] = 'index.php?m=news&d=view&nid='.$row['id_news'];
								}
							if ($_settings['news_use_image'] == 1)
								{
									if (file_exists('files/thumb/news'.$m["newsid"][$i][0].'.jpg'))
										{
											$m["newsid"][$i][6] = 'files/thumb/news'.$m["newsid"][$i][0].'.jpg';
										}
									elseif (file_exists('files/img/news'.$m["newsid"][$i][0].'.jpg'))
										{
											$m["newsid"][$i][6] = 'ext/showimage.php?img=news'.$m["newsid"][$i][0];
											if (!empty($_settings['news_image_preview_width']))
												$m["newsid"][$i][6] .= '&width='.$_settings['news_image_preview_width'];
											if (!empty($_settings['news_image_preview_height']))
												$m["newsid"][$i][6] .= '&height='.$_settings['news_image_preview_height'];
										}
								}
							if ($tmp_short_news == 0)
								{
									$tmp_cut_news = $_settings['news_anounce_cut'];
								}
							else
								{
									$tmp_cut_news = $_settings['short_news_cut'];
								}
							$u = 0;
							if ($_settings['news_use_preview'] == 1 && !empty($row['preview_news']))
								{
									$m["newsid"][$i][3] = $row['preview_news'];
									$m["newsid"][$i][4] = 1;
									$u = 1;
								}
							if (strlen($row['text_news'])>$tmp_cut_news && $u != 1)
								{
									$m["newsid"][$i][3] = cut_str_by_word($row['text_news'], $tmp_cut_news, '...');
									if ($tmp_short_news == 0)
										$m["newsid"][$i][4] = 1;
									else
										$m["newsid"][$i][4] = 0;
								}
							if ($row['type_news'] == 0)
								{
									$m["newsid"][$i][2] = nl2br($m["newsid"][$i][2]);
									$m["newsid"][$i][3] = nl2br($m["newsid"][$i][3]);
								}
							sm_event('onlistnewsprocessed', $i);
							sm_add_title_modifier($m["newsid"][$i][5]);
							sm_add_content_modifier($m["newsid"][$i][2]);
							sm_add_content_modifier($m["newsid"][$i][3]);
							$i++;
						}
					if ($tmp_short_news == 0)
						{
							$sql = "SELECT count(*) FROM ".$tableprefix."news".$sql2;
							$result = execsql($sql);
							$m['pages']['records'] = 0;
							while ($row = database_fetch_row($result))
								{
									$m['pages']['records'] = $row[0];
								}
							$m['pages']['pages'] = ceil($m['pages']['records']/$_settings['news_by_page']);
							$m['short_news'] = 0;
						}
					else
						{
							$m['pages']['pages'] = 0;
							$m['short_news'] = 1;
						}
					sm_add_title_modifier($m['title']);
				}

			if (sm_action('view'))
				{
					$m["module"] = 'news';
					if (!empty($m["bid"])) $m["nid"] = $m["bid"];
					$news_id = intval($m["nid"]);
					if ($_settings['allow_alike_news'] == 1)
						$tmp_no_alike_news = 0;
					else
						$tmp_no_alike_news = 1;
					if (empty($news_id) && $modules_index == 0) $news_id = intval($_getvars["nid"]);
					if (empty($news_id))
						{
							$m["title"] = $lang["error"];
							$m["text"] = $lang["error_cannot_found"];
						}
					else
						{
							$sql = "SELECT ".$tableprefix."news.*, ".$tableprefix."categories_news.* FROM ".$tableprefix."news, ".$tableprefix."categories_news WHERE ".$tableprefix."news.id_category_n=".$tableprefix."categories_news.id_category AND id_news=".intval($news_id);
							$result = execsql($sql);
							while ($row = database_fetch_assoc($result))
								{
									sm_event('onbeforenewsprocessing', 0);
									sm_page_viewid('news-'.$m["mode"].'-'.$row['id_news']);
									$m['row'] = $row;
									$m["id"] = $row['id_news'];
									if ($_settings['news_use_title'])
										{
											if (empty($row['title_news']))
												{
													$m["title"] = strftime($lang["datemask"], $row['date_news']);
													if ($_settings['news_use_time'] == '1')
														$m["title"] = strftime($lang["timemask"], $row['date_news']).' '.$m["title"];
												}
											else
												$m["title"] = $row['title_news'];
										}
									else
										$m["title"] = $lang['news'].' :: '.strftime($lang["datemask"], $row['date_news']);
									$m["date"] = $row['date_news'];
									$m["news_time"] = strftime($lang["timemask"], $row['date_news']);
									$m["news_date"] = strftime($lang["datemask"], $row['date_news']);
									$m["date"] = strftime($lang["datemask"], $m["date"]);
									$m["text"] = $row['text_news'];
									$m["id_category"] = intval($row['id_category_n']);
									if ($special['categories']['getctg'] == 1)
										$special['categories']['id'] = $row['id_category_n'];
									if ($row['no_alike_news'] == 1)
										$tmp_no_alike_news = 1;
									if ($_settings['news_use_image'] == 1)
										{
											if (file_exists('files/fullimg/news'.$m["id"].'.jpg'))
												{
													$m['news_image'] = 'files/fullimg/news'.$m["id"].'.jpg';
												}
											elseif (file_exists('files/img/news'.$m['id'].'.jpg'))
												{
													$m['news_image'] = 'ext/showimage.php?img=news'.$m["id"];
													if (!empty($_settings['news_image_fulltext_width']))
														$m['news_image'] .= '&width='.$_settings['news_image_fulltext_width'];
													if (!empty($_settings['news_image_fulltext_height']))
														$m['news_image'] .= '&height='.$_settings['news_image_fulltext_height'];
												}
										}
									if ($row['type_news'] == 0)
										{
											$m["text"] = nl2br($m["text"]);
										}
									if ($userinfo['level']>=intval(sm_settings('news_editor_level')))
										{
											$m["can_edit"] = 1;
											$m["can_delete"] = 1;
										}
									elseif (!empty($userinfo['groups']))
										{
											if (compare_groups($userinfo['groups'], $row['groups_modify']) == 1)
												{
													$m["can_edit"] = 1;
													$m["can_delete"] = 1;
												}
										}
									if ($tmp_no_alike_news != 1 && $m['panel'] == 'center')
										{
											$tmpsql = "SELECT * FROM ".$tableprefix."news WHERE id_category_n=".intval($m["id_category"])." AND id_news<>".intval($news_id)." ORDER BY date_news DESC LIMIT ".intval($_settings['alike_news_count']);
											$tmpresult = execsql($tmpsql);
											$j = 0;
											while ($tmprow = database_fetch_assoc($tmpresult))
												{
													$m['alike_news'][$j]['id'] = $tmprow['id_news'];
													$m['alike_news'][$j]['title'] = $tmprow['title_news'];
													$m['alike_news'][$j]["date"] = strftime($lang["datemask"], $tmprow['date_news']);
													if (!empty($tmprow['filename_news']))
														$m['alike_news'][$j]["fullink"] = get_filename($tmprow['filename_news']);
													else
														$m['alike_news'][$j]["fullink"] = 'index.php?m=news&d=view&nid='.$tmprow['id_news'];
													$m['alike_news'][$j]['preview'] = $tmprow['preview_news'];
													if (empty($m['alike_news'][$j]['preview']))
														$m['alike_news'][$j]['preview'] = cut_str_by_word($tmprow['text_news'], $_settings['news_anounce_cut'], '...');
													if (empty($m['alike_news'][$j]['title']))
														$m['alike_news'][$j]['title'] = $m['alike_news'][$j]['preview'];
													sm_add_title_modifier($m['alike_news'][$j]['title']);
													sm_add_content_modifier($m['alike_news'][$j]['preview']);
													$j++;
												}
											$m['alike_news_present'] = $j;
										}
									else
										$m['alike_news_present'] = 0;
									$m['attachments'] = sm_get_attachments('news', $row['id_news']);
									if ($modules_index == 0)
										{
											if (!empty($special['meta']['keywords']) && !empty($row['keywords_news']))
												{
													$special['meta']['keywords'] = ($row['keywords_news']).', '.$special['meta']['keywords'];
												}
											elseif (!empty($row['keywords_news']))
												{
													$special['meta']['keywords'] = $row['keywords_news'];
												}
											if (!empty($row['description_news']))
												$special['meta']['description'] = $row['description_news'];
										}
									sm_event('onnewsprocessed', $i);
									sm_event('onviewnews', array($m["id"]));
								}
							sm_add_content_modifier($m["text"]);
						}
					sm_add_title_modifier($m['title']);
				}

			if ($userinfo['level']>0)
				include('modules/inc/memberspart/news.php');
		}// user level view restrictions end

?>