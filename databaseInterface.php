<?php
class Database
{
	include('Connection.php');
	
	private $_connection = new Connection();
	 
	function __construct()
	{
	}
}
?>