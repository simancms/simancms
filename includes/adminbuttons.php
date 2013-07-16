<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

if (!defined("adminbuttons_DEFINED"))
	{
		sm_add_cssfile('common_adminbuttons.css');
		
		class TButtons
			{
				var $bar;
				function TButtons($buttonbar_title='')
					{
						$this->bar['buttonbar_title']=$buttonbar_title;
					}
				function AddButton($name, $title, $url='', $type='button', $style='', $messagebox_message='', $javascript='')
					{
						if (empty($name))
							$name=md5(rand(1, 999999));
						$this->bar['buttons'][$name]['name']=$name;
						$this->bar['buttons'][$name]['caption']=$title;
						$this->bar['buttons'][$name]['url']=$url;
						$this->bar['buttons'][$name]['type']=$type;
						$this->bar['buttons'][$name]['javascript']=$javascript;
						$this->bar['buttons'][$name]['style']=$style;
						$this->bar['buttons'][$name]['message']=addslashes($messagebox_message);
					}
				function AddSeparator($name, $title=' | ', $style='')
					{
						$this->bar['buttons'][$name]['name']=$name;
						$this->bar['buttons'][$name]['caption']=$title;
						$this->bar['buttons'][$name]['style']=$style;
						$this->bar['buttons'][$name]['type']='separator';
					}
				function AddToggle($name, $title, $toggle_id, $style='')
					{
						$javascript="tmp=document.getElementById('".$toggle_id."');tmp.style.display=(tmp.style.display=='none')?'':'none';";
						$this->AddButton($name, $title, '', 'button', $style, '', $javascript);
					}
				function AddMessageBox($name, $title, $url, $messagebox_message, $style='')
					{
						$this->AddButton($name, $title, $url, 'messagebox', $style, $messagebox_message);
					}
				function Bold($name)
					{
						$this->bar['buttons'][$name]['bold']=1;
					}
				function Width($name, $width)
					{
						$this->bar['buttons'][$name]['width']=$width;
					}
				function Height($name, $height)
					{
						$this->bar['buttons'][$name]['height']=$height;
					}
				function Style($name, $style)
					{
						$this->bar['buttons'][$name]['style']=$style;
					}
				function AssignImage($name, $imagename)
					{
						$this->bar['buttons'][$name]['image']=$imagename;
					}
				function Output()
					{
						$this->bar['count']=count($this->bar['buttons']);
						return $this->bar;
					}
			}

		define("adminbuttons_DEFINED", 1);
	}

?>