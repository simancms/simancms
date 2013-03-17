<?php

function stripnofloat($str)
	{
		$res='';
		$cifrepresent=0;
		$cifrepresentafter=0;
		$commapresent=0;
		$str=$str.' ';
		for ($i=0; $i<strlen($str); $i++)
			{
				if ($str[$i]=='0' || $str[$i]=='1' || $str[$i]=='2' || $str[$i]=='3' || $str[$i]=='4' || $str[$i]=='5' || $str[$i]=='6' || $str[$i]=='7' || $str[$i]=='8' || $str[$i]=='9')
					{
						$res.=$str[$i];
						if ($cifrepresent==0)
							$cifrepresent=1;
						if ($commapresent==1)
							$cifrepresentafter=1;
					}
				elseif ($str[$i]==',' || $str[$i]=='.')
					{
						if ($commapresent==1)
							break;
						if ($cifrepresent==0)
							break;
						if ($cifrepresentafter==1)
							break;
						$res.='.';
						$commapresent=1;
					}
				elseif ($cifrepresent==0)
					{
						continue;
					}
				else
					break;
			}
		if  ($cifrepresent==0)
			return 0;
		if ($commapresent==1 && $cifrepresentafter==0)
			$res.='0';
		return floatval($res);
	}

?>