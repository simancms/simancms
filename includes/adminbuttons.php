<?php

//------------------------------------------------------------------------------
//|                                                                            |
//|            Content Management System SiMan CMS                             |
//|                                                                            |
//------------------------------------------------------------------------------

if (!defined("adminbuttons_DEFINED"))
	{
		//2011-07-02
		$special['cssfiles'][count($special['cssfiles'])]='common_adminbuttons.css';
		
		class TButtons
			{
				var $bar;
				function TButtons($buttonbar_title='')
					{
						$this->bar['buttonbar_title']=$buttonbar_title;
					}
				function AddButton($name, $title, $url='', $type='button', $style='', $messagebox_messgae='')
					{
						$this->bar['buttons'][$name]['name']=$name;
						$this->bar['buttons'][$name]['caption']=$title;
						$this->bar['buttons'][$name]['url']=$url;
						$this->bar['buttons'][$name]['type']=$type;
						$this->bar['buttons'][$name]['message']=addslashes($messagebox_messgae);
					}
				function AddSeparator($name, $title=' | ', $style='')
					{
						$this->bar['buttons'][$name]['name']=$name;
						$this->bar['buttons'][$name]['caption']=$title;
						$this->bar['buttons'][$name]['style']=$style;
						$this->bar['buttons'][$name]['type']='separator';
					}
				function AddMessageBox($name, $title, $url, $messagebox_messgae, $style='')
					{
						$this->AddButton($name, $title, $url, 'messagebox', $style, $messagebox_messgae);
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