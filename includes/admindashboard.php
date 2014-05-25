<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	if (!defined("admindashboard_DEFINED"))
		{
			sm_add_cssfile('common_admindashboard.css');

			class TDashBoard
				{
					var $board;
					private $currentitem;

					function TDashBoard()
						{
						}

					function AddItem($title, $url, $image='', $name='')
						{
							if (empty($name))
								$name = md5(rand(1, 999999));
							$this->SetActiveItem($name);
							return $this;
						}
					
					function SetActiveItem($name)
						{
							$this->currentitem=$this->board['items'][$name];
							$this->currentitem['name']=$name;
							return $this;
						}
					
					function SetURL($url, $name='')
						{
							if (!empty($name))
								$this->SetActiveItem($name);
							$this->currentitem['url']=$url;
							return $this;
						}
					
					function SetTitle($title, $name='')
						{
							if (!empty($name))
								$this->SetActiveItem($name);
							$this->currentitem['title']=$title;
							return $this;
						}
					
					function SetImage($image, $name='')
						{
							if (!empty($name))
								$this->SetActiveItem($name);
							$this->currentitem['image']=$image;
							return $this;
						}
					
					function AddClassname($classname, $name='')
						{
							if (!empty($name))
								$this->SetActiveItem($name);
							$this->currentitem['class'].=(empty($this->currentitem['class'])?'':' ').$classname;
							return $this;
						}
					
					function AddClassnameGlobal($classname)
						{
							$this->board['class'].=(empty($this->board['class'])?'':' ').$classname;
							return $this;
						}
					
					function SetStyle($style, $name='')
						{
							if (!empty($name))
								$this->SetActiveItem($name);
							$this->currentitem['style']=$style;
							return $this;
						}
					
					function SetStyleGlobal($style)
						{
							$this->board['style']=$style;
							return $this;
						}
					
					function Output()
						{
							$this->board['count']=count($this->board['items']);
							return $this->board;
						}
				}

			define("admindashboard_DEFINED", 1);
		}

?>