<?php

	class TableBuilder
	{
		private $_columnCount;
		private $_rowCount;
		private $_isHeaderSet = false;
		
		//[row][column]
		private $_table;
		
		//[property]=value
		private $_tableProperty;
		
		//[row][property]=value
		private $_rowProperties;
		
		public function __construct()
		{}
		
		public function SetColumnCount($columns)
		{
			$this->_columnCount = $columns;
		}
		
		public function SetRowCount($row)
		{
			$this->_rowCount = $row;
		}
		
		public function SetHeader($index, $value)
		{
			$this->_isHeaderSet = true;
			$this->_table[0][$index] = $value;
		}

		public function SetProperty($property, $value)
		{
			$this->_tableProperty[$property]=$value;
		}

		public function SetRowProperty($index, $property, $value)
		{
			$this->_rowProperties[$index][$property]=$value;
		}
		
		public function SetCell($row, $column, $value)
		{
			$this->_table[$row][$column] = $value;
		}
		
		public function Build()
		{
			$table = "<table";
			if (is_array($this->_tableProperty))
			{
				foreach ($this->_tableProperty as $key => $value)
				{
					$table .= " " . $key . "=" . $value;
				}
			}
			$table .= ">\n";
			$rowIndex = 0;
			
			if ($this->_isHeaderSet == true)
			{
				$rowIndex = 1;
				$table .= "<tr" . $this->GetRowProperties(0) . " >";
				$array = $this->_table[0];
				foreach ($array as $value)
				{
						$table .= "<th>$value</th>";
				}

				$table .= "</tr>\n";
			}
			
			$rowcount = count($this->_table);
			for ($j = $rowIndex; $j < $rowcount; $j++)
			{
				$table .= $this->BuildRow($this->_table[$j], $j);
			}

			$table .= "</table>\n";
			return $table;
		}
		
		private function BuildRow($rowArray, $rowIndex)
		{
			$rowStr = "<tr". $this->GetRowProperties($rowIndex) . ">";
			foreach ($rowArray as &$value)
			{
				$rowStr .= $this->BuildCell($value);
			}
			
			unset($value);
			$rowStr .= "</tr>\n";
			return $rowStr;
		}
		
		private function GetRowProperties($rowIndex)
		{
			$property = "";
			if (array_key_exists($rowIndex, $this->_rowProperties))
			{
				foreach ($this->_rowProperties[$rowIndex] as $key => $value)
				{
					$property .= " " . $key . "=" . $value;
				}
			}

			return $property;
		}
		private function BuildCell($cell)
		{
			return "<td>$cell</td>";
		}
	}
?>