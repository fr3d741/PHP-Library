<?php
	session_start();
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include('CommandBuilder.php');
	
	echo "<br>";
	$select = new Select();
	$msg = $select->Columns("*")->Table("Users")->OrderBy("login")->Ascending()->Build();
	echo "Select: " . $msg;
	
	//$query = "INSERT INTO ErrorLog('$errMsg', NULL, '$info' )";
	echo "<br>";
	$insert = new Insert();
	$errMsg = "proba";
	$msg = $insert->Table("ErrorLog")->Values(array("$errMsg", NULL, '$info'))->Build();
	echo "Insert: " . $msg;
	
	echo "<br>Test exp: " . (new Insert())->Table('ErrorLog')->Values(array("$errMsg", 'NULL', '$info'))->Build();
	
	$uid = 5;
	$select2 = new Select();
	$query = $select2->Table('csoportok')->Columns(array("name", "ID"))->Condition("userID=" . $uid)->OrderBy("name")->Build();
	
	echo "<br> Teszt: $query";
	
	$select2->Clear();
	$query = $select2->Table('csoportok')->Columns(array('*'))->Condition("visibility=1")->Build();
	echo "<br> Csoport teszt: " . $query;
	
	include('WebFacade.php');
	
	echo "<br>";
	$tableBuilder = new TableBuilder();
	$tableBuilder->SetRowProperty(1, "id", "piff");
	$tableBuilder->SetHeader(0,"Header1");
	$tableBuilder->SetHeader(1,"Header2");
	$tableBuilder->SetCell(1, 0, "Row1");
	$tableBuilder->SetCell(2, 0, "Row2");
	$tableBuilder->SetCell(1, 1, "Cell(1,1)");
	$tableBuilder->SetCell(2, 1, "Cell(2,1)");
	$tableBuilder->SetCell(1, 2, "Cell(1,2)");
	$tableBuilder->SetCell(2, 2, "Cell(2,2)");
	echo $tableBuilder->Build();
	
	echo "<br><br>";
	
					// $rowStr = "<tr";
			// if (array_key_exists($rowIndex, $_rowProperties))
			// {
				// foreach ($_rowProperties[$rowIndex] as &$value)
				// {
					// $rowStr .= " " . $value . "=" . $_rowProperties[$rowIndex][$value];
				// }
			// }

?>