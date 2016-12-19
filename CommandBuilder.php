<?php
	class Condition{
		
		//{exp op} {exp op}
		private $_expressions = array();

		function Expression($expression)
		{
			array_push($this->_expressions, $expression);
			return $this;
		}
		
		function And_()
		{
			array_push($this->_expressions, "AND");
			return $this;
		}
		
		function Or_()
		{
			array_push($this->_expressions, "OR");
			return $this;
		}
		
		function Get()
		{
			$exp = "";
			foreach ($this->_expressions as $value)
			{
				$exp .= " " . $value;
			}
			$exp .= " ";
			return $exp;
		}
	}

	abstract class CommandBuilder
	{
		protected $_array = array();
		
		abstract public function Build();
		
		abstract protected function Init();
		
		public function Clear()
		{
			unset($this->_array);
			$this->_array = array();
			$this->Init();
		}

		public function Table($table)
		{
			$this->_array['table'] = $table;
			return $this;
		}
		
		public function Columns($array)
		{
			$this->_array['columns'] = $array;
			return $this;
		}
		
		public function OrderBy($column)
		{
			$this->_array['order'] = $column;
			return $this;
		}
		
		public function Ascending()
		{
			$this->_array['direction'] = "ASC";
			return $this;
		}
		
		public function Descending()
		{
			$this->_array['direction'] = "DESC";
			return $this;
		}
		
		public function Select()
		{
			$this->_array['command'] = "SELECT";
			return $this;
		}
		
		public function Insert()
		{
			$this->_array['command'] = "INSERT INTO";
			return $this;
		}
	
		public function Condition($expression)
		{
			$this->_array['condition'] = " WHERE $expression";
			return $this;
		}
	
		public function Values($array)
		{
			$this->_array['values'] = $array;
			return $this;
		}
		
		public function Limit($limit)
		{
			$this->_array['limit'] = $limit;
			return $this;
		}
		
		protected function dumpArray($array)
		{
			$msg = "";
			for ($i = 0; $i < count($array); $i++)
			{
				if ($i != 0)
					$msg .= ",";

				if ($array[$i] == NULL)
					$msg .= "NULL";
				else
					$msg .= "'" . $array[$i] . "'";
			}
			return $msg;
		}
		
		protected function dumpArrayWithoutQuote($array)
		{
			$msg = "";
			for ($i = 0; $i < count($array); $i++)
			{
				if ($i != 0)
					$msg .= ",";

				if ($array[$i] == NULL)
					$msg .= "NULL";
				else
					$msg .= $array[$i];
			}
			return $msg;
		}
	}
	
	class Select extends CommandBuilder
	{
		public function __construct()
		{
			$this->Init();
		}
		
		protected function Init()
		{
			$this->_array['command'] = "SELECT";
		}

		private function HandleOrder($cmd)
		{
			$array = $this->_array;
			if (array_key_exists('order', $array ))
			{
				$cmd .= " ORDER BY " . $array['order'];
				if (array_key_exists('direction', $array ))
				{
					$cmd .= " " . $array['direction'];
				}
			}
			
			return $cmd;
		}
		
		private function HandleCondition($cmd)
		{
			$array = $this->_array;
			if (array_key_exists('condition', $array))
			{
				$cmd .= $array['condition'];
			}
			
			return $cmd;
		}		

		public function Build()
		{
			$array = $this->_array;
			$cmd = $array['command'] . " " . $this->dumpArrayWithoutQuote($array['columns']) . " FROM " . $array['table'];
			$cmd = $this->HandleCondition($cmd);
			$cmd = $this->HandleOrder($cmd);
			if (array_key_exists('limit', $array))
			{
				$cmd .= " " . $array['limit'];
			}
			
			return $cmd;
		}		
	}
	
	class Insert extends CommandBuilder
	{
		public function __construct()
		{
			$this->Init();
		}

		protected function Init()
		{
			$this->_array['command'] = "INSERT INTO";
		}
		
		public function Build()
		{
			//$query = "INSERT INTO ErrorLog('$errMsg', NULL, '$info' )";
			$array = $this->_array;
			$values = $array['values'];
			$cmd = $array['command'] . " " . $array['table'] . " VALUES(" . $this->dumpArray($values) . ")";
			return $cmd;
		}
	}

	class SQLExpressionBuilder
	{
		private $exp = "";
		
		public function Condition($cond)
		{
			$this->_exp .= $cond . " ";
			return $this;
		}
		
		public function _And_()
		{
			$this->_exp .= "AND ";
			return $this;
		}
		
		public function _Or_()
		{
			$this->_exp .= "OR ";
			return $this;
		}
	}
?>