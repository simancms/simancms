<?php

	//------------------------------------------------------------------------------
	//|                                                                            |
	//|            Content Management System SiMan CMS                             |
	//|                                                                            |
	//------------------------------------------------------------------------------

	//==============================================================================
	//#revision 2017-04-20
	//==============================================================================

	if (!defined("admintabs_DEFINED"))
		{
			sm_use('ui.interface');
			sm_add_cssfile('common_admintabs.css');

			class TTabs extends TGenericInterface
				{
					var $activeindex;

					function __construct($activeindex = 0)
						{
							parent::__construct('', 0);
							$this->activeindex = $activeindex;
						}

					function Tab($title, $tab_url='')
						{
							$this->AddBlock($title);
							if (!empty($tab_url))
								$this->blocks[$this->currentblock]['taburl'] = $tab_url;
							return $this;
						}

					function SetTitleForIndex($title, $index)
						{
							$this->blocks[$index]['title']=$title;
						}

					function TitleForIndex($index)
						{
							return $this->blocks[$index]['title'];
						}

					function SetActiveIndex($activeindex)
						{
							$this->activeindex = $activeindex;
						}

					function SetActiveIndexCurrent()
						{
							$this->activeindex = count($this->blocks)-1;
							if ($this->activeindex==0)
								$this->activeindex=0;
						}

					function Output()
						{
							$this->blocks[$this->activeindex]['active'] = true;
							return $this->blocks;
						}
				}

			define("admintabs_DEFINED", 1);
		}
