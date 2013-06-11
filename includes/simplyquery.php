<?php

if (!defined("simplyquery_DEFINED"))
	{
		//2012-10-21
		
		class TQuery
			{
				var $fields;
				var $values;
				var $tableprefix;
				var $tablename;
				var $noquote;
				var $iswhere;
				var $selectfields;
				var $limit;
				var $offset;
				var $orderby;
				public $items;
				public $sql;
				function TQuery($tablename, $tableprefix='')
					{
						$this->tableprefix=$tableprefix;
						$this->tablename=$tablename;
						return $this;
					}
				//Add($expression)
				//Add($fieldname, $value='')
				function Add($fieldname, $value=NULL, $operation='=')
					{
						if (func_num_args()==0)
							return;
						$fieldname=func_get_arg(0);
						if (func_num_args()>1)
							{
								$value=func_get_arg(1);
								if ($value===NULL)
									$value='';
							}
						$this->fields[count($this->fields)]=$fieldname;
						$this->values[count($this->values)]=$value;
						$this->noquote[count($this->fields)-1]=false;
						$this->iswhere[count($this->fields)-1]=false;
						return $this;
					}
				function AddWhere($fieldname, $value=NULL, $operation='=')
					{
						if (func_num_args()==0)
							return;
						$fieldname=func_get_arg(0);
						if (func_num_args()>1)
							{
								$value=func_get_arg(1);
								if ($value===NULL)
									$value='';
							}
						$this->fields[count($this->fields)]=$fieldname;
						$this->values[count($this->values)]=$value;
						$this->noquote[count($this->fields)-1]=false;
						$this->iswhere[count($this->fields)-1]=true;
						return $this;
					}
				function AddNotEmpty($fieldname, $value)
					{
						if(!empty($value))
							$this->Add($fieldname, $value);
						return $this;
					}
				function AddFunction($fieldname, $function)
					{
						$this->Add($fieldname, $function);
						$this->noquote[count($this->fields)-1]=true;
						return $this;
					}
				function AddExpression($fieldname, $expression)
					{
						$this->Add($fieldname, $expression);
						$this->noquote[count($this->fields)-1]=true;
						return $this;
					}
				function AddNumeric($fieldname, $value)
					{
						$this->Add($fieldname, floatval($value));
						$this->noquote[count($this->fields)-1]=true;
						return $this;
					}
				function AddPost($fieldname, $prefix='')
					{
						global $_postvars;
						$this->Add($fieldname, addslashesJ($_postvars[$prefix.$fieldname]));
						return $this;
					}
				function AddPostNllist($nllist, $prefix='')
					{
						$f=nllistToArray($nllist);
						for ($i=0; $i<count($f); $i++)
							{
								$f[$i]=str_replace("\t", "", $f[$i]);
								$f[$i]=trim($f[$i]);
								if (empty($f[$i])) continue;
								$this->AddPost($f[$i], $prefix);
							}
						return $this;
					}
				function Insert($execute=true)
					{
						$sqlf='';
						$sqlv='';
						for ($i=0; $i<count($this->fields); $i++)
							{
								if ($i!=0)
									{
										$sqlf.=', ';
										$sqlv.=', ';
									}
								$sqlf.='`'.$this->fields[$i].'`';
								if ($this->noquote[$i])
									$sqlv.=$this->values[$i];
								else
									$sqlv.='\''.$this->values[$i].'\'';
							}
						$this->sql="INSERT INTO ".$this->tableprefix.$this->tablename." (".$sqlf.") VALUES (".$sqlv.")";
						if ($execute)
							return insertsql($this->sql);
					}
				//Update($keyfield, $keyvalue, $execute=true)
				//Update($keyfield, $keyvalue)
				//Update($statement)
				function Update()
					{
						$keyfield=func_get_arg(0);
						if (func_num_args()>1)
							$keyvalue=func_get_arg(1);
						if (func_num_args()<3)
							$execute=true;
						else
							$execute=func_get_arg(2);
						$sql=$this->GetPairs(', ', 'notwhere');
						$this->sql="UPDATE ".$this->tableprefix.$this->tablename." SET ".$sql." WHERE ";
						if (func_num_args()==1)
							$this->sql.=$keyfield;
						elseif (func_num_args()!=0)
							$this->sql.="`".$keyfield."` = '".$keyvalue."'";
						$sql=$this->GetPairs(' AND ', 'where');
						if (!empty($sql))
							{
								if (func_num_args()!=0)
									$this->sql.=' AND ';
								$this->sql.='('.$sql.')';
							}
						elseif (func_num_args()==0)
							return;
						if ($execute)
							execsql($this->sql);
					}
				function Remove($addsql='', $execute=true)
					{
						$sql=$this->GetPairs();
						$this->sql="DELETE FROM ".$this->tableprefix.$this->tablename." WHERE (".$sql.")";
						if (!empty($addsql))
							$this->sql.=' '.$addsql;
						if ($execute)
							execsql($this->sql);
					}
				private function GetPairs($combine_with=' AND ', $filter='no')
					{
						$sql='';
						for ($i=0; $i<count($this->fields); $i++)
							{
								if ($filter=='where' && !$this->iswhere[$i])
									continue;
								elseif ($filter=='notwhere' && $this->iswhere[$i])
									continue;
								if (!empty($sql))
									{
										$sql.=$combine_with;
									}
								if ($this->values[$i]===NULL)
									$sql.=$this->fields[$i];
								elseif ($this->noquote[$i])
									$sql.='`'.$this->fields[$i].'` = '.$this->values[$i];
								elseif (is_array($this->values[$i]) && count($this->values[$i])>0)
									{
										$list='';
										for ($j = 0; $j < count($this->values[$i]); $j++)
											{
												if ($j>0)
													$list.=', ';
												$list.="'".dbescape($this->values[$i][$j])."'";
											}
										$sql.='`'.$this->fields[$i].'` IN ('.$list.')';
									}
								else
									$sql.='`'.$this->fields[$i].'` = \''.$this->values[$i].'\'';
							}
						return $sql;
					}
				//Return matches count
				function Find($addsql='')
					{
						$sql=$this->GetPairs(' AND ');
						if (!empty($sql))
							$this->sql="SELECT count(*) FROM ".$this->tableprefix.$this->tablename." WHERE (".$sql.")";
						else
							$this->sql="SELECT count(*) FROM ".$this->tableprefix.$this->tablename;
						if (!empty($addsql))
							$this->sql.=' '.$addsql;
						$r=getsql($this->sql, 'r');
						return intval($r[0]);
					}
				function ChangeValue($fieldname, $value)
					{
						$u=0;
						for ($i=0; $i<count($this->fields); $i++)
							{
								if ($this->fields[$i]==$fieldname)
									{
										$u=1;
										$this->values[$i]=$value;
									}
							}
						if ($u!=1)
							$this->Add($fieldname, $value);
						return $this;
					}
				function Clear($tablename='', $tableprefix='')
					{
						$this->fields=Array();
						$this->values=Array();
						$this->noquote=Array();
						$this->sql='';
						if (!empty($tableprefix))
							{
								$this->tableprefix=$tableprefix;
								$this->tablename=$tablename;
							}
						return $this;
					}
				// $excludeFields='f1|f2|f3'
				// return last inserted id when copied 1 row or count of copied rows in other case
				function CopyDataTo($destinationTable, $conditionWhere, $excludeFields='')
					{
						global $nameDB, $lnkDB;
						$exclude=explode('|', $excludeFields);
						$srcF=getsqlarray(" SHOW FIELDS FROM ".$this->tableprefix.$this->tablename);
						for ($i=0; $i<count($srcF); $i++)
							$src[$i]=$srcF[$i]['Field'];
						$destF=getsqlarray(" SHOW FIELDS FROM ".$destinationTable);
						for ($i=0; $i<count($destF); $i++)
							$dest[$i]=$destF[$i]['Field'];
						for ($i=0; $i<count($src); $i++)
							if (in_array($src[$i], $dest) && !in_array($src[$i], $exclude))
								$fields[]=$src[$i];
						$sql.="SELECT * FROM ".$this->tableprefix.$this->tablename;
						if (!empty($conditionWhere))
							$sql.=" WHERE ".$conditionWhere;
						$result=database_db_query($nameDB, $sql, $lnkDB);
						$cnt=0;
						$q=new TQuery($destinationTable);
						while ($row=database_fetch_array($result))
							{
								$q->Clear();
								for ($i=0; $i<count($fields); $i++)
									$q->Add($fields[$i], addslashesJ($row[$fields[$i]]));
								$id=$q->Insert();
								$cnt++;
							}
						if ($cnt==1)
							return $id;
						else
							return $cnt;
					}
				function Limit($count)
					{
						$this->limit=$count;
						return $this;
					}
				function Offset($count)
					{
						$this->offset=$count;
						return $this;
					}
				function OrderBy($orderbyfileds)
					{
						$this->orderby=$orderbyfileds;
					}
				function SelectFields($list='*')
					{
						$this->selectfields=$list;
						return $this;
					}
				function Select($addsql='', $type='a')
					{
						if (empty($this->selectfields))
							$this->SelectFields();
						$sql=$this->GetPairs(' AND ');
						$this->sql="SELECT ".$this->selectfields." FROM ".$this->tableprefix.$this->tablename;
						if (!empty($sql))
							$this->sql.=" WHERE (".$sql.")";
						if (!empty($addsql))
							{
								if (empty($sql))
									$this->sql.=" WHERE ";
								$this->sql.=' '.$addsql;
							}
						if (!empty($this->orderby))
							$this->sql.=' ORDER BY '.$this->orderby;
						if (!empty($this->limit))
							$this->sql.=' LIMIT '.$this->limit;
						if (!empty($this->offset))
							$this->sql.=' OFFSET '.$this->offset;
						$this->items=getsqlarray($this->sql, $type);
						return $this->items[0];
					}
				function Get($addsql='', $type='a')
					{
						$this->Limit(1);
						return $this->Select($addsql, $type);
					}
				function Count()
					{
						return count($this->items);
					}
				function isEmpty()
					{
						return $this->Count()==0;
					}
			}
		
		function CheckForNonEmpty($nllist)
			{
				$result=true;
				global $_postvars;
				$f=nllistToArray($nllist);
				for ($i=0; $i<count($f); $i++)
					{
						$f[$i]=str_replace("\t", "", $f[$i]);
						$f[$i]=trim($f[$i]);
						if (empty($f[$i])) continue;
							if (empty($_postvars[$f[$i]]))
								$result=false;
					}
				return $result;
			}
		define("simplyquery_DEFINED", 1);
	}
?>