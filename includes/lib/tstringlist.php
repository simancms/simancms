<?php

	if (!defined("TStringList_DEFINED"))
		{

			/**
			 * Class TStringList
			 */
			class TStringList
				{
					// http://www.delphibasics.co.uk/RTL.asp?Name=tstringlist
					protected $items=Array();

					/**
					 * TStringList constructor.
					 * @param mixed $init_with_items - strings, separated by newline to initialize list. Use NULL if no initialization needed. Default value is NULL
					 */
					function __construct($init_with_items=NULL)
						{
							if ($init_with_items!==NULL)
								$this->Load($init_with_items);
						}

					/**
					 * Will add the given string to the list, returning its allocated index position (starting with 0).
					 * @param string $str
					 * @return int - index of new item
					 */
					function Add($str)
						{
							$this->items[]=$str;
							return $this->Count()-1;
						}

					/**
					 * Deletes all strings from the list.
					 */
					function Clear()
						{
							$this->items=Array();
						}

					/**
					 * Deletes the sting at selected index
					 */
					function DeleteByIndex($index)
						{
							if ($index<$this->Count())
								array_splice($this->items, $index, 1);
						}

					/**
					 * Returns the number of strings in the list
					 * @return int
					 */
					function Count()
						{
							return count($this->items);
						}

					/**
					 * Gets the index position of the first string matching the given string (starting with 0). Or false if not found.
					 * @param string $str
					 * @return int
					 */
					function IndexOf($str)
						{
							for ($i = 0; $i < $this->Count(); $i++)
								if (strcmp($this->Strings($i), $str)==0)
									return $i;
							return false;
						}

					/**
					 * Return true if at leas one the strings matching the given string
					 * @param string $str
					 * @return bool
					 */
					function Contains($str)
						{
							return $this->IndexOf($str)!==false;
						}

					/**
					 * Populates the list form the string. Using both \n or \r\n as delimiter
					 * @param string $str
					 * @return int
					 */
					function Load($str)
						{
							$str=str_replace("\r", '', $str);
							$this->items=explode("\n", $str);
						}

					/**
					 * Returns the item with the index $index or all items as array if no $index used
					 * @param int $index
					 * @return string
					 */
					function Strings($index=NULL)
						{
							if ($index===NULL)
								return $this->items;
							else
								return $this->items[$index];
						}

					/**
					 * Get the list via a big string. This string will have each string terminated with \n or \r\n
					 * @param bool $rn - Return \r\n if true and \n if false. Default value false
					 * @return string
					 */
					function Text($rn=false)
						{
							return implode($rn?"\r\n":"\n", $this->items);
						}

				}

			define("TStringList_DEFINED", 1);
		}
