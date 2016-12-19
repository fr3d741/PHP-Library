<?php
class Connection
{
	private $_user;
	private $_password;
	private $_database;
	private $_hostname;
	
	private $_connection;
	
	public function __construct()
	{
		//include('config.php'); // config.php shoud contain contain database's $hostname, $user, $pass and $database and $mailto
		$this->_hostname = $hostname;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_database = $database;

		$this->_connection = new mysqli($this->_hostname, $this->_user, $this->_pass, $this->_database);
	}

	public function execute($command)
	{
		include_once('CommandBuilder.php');
		$result = $this->_connection->query($command);
		if ($this->_connection->connect_error)
		{
			$errMsg = $this->_connection->errno . " : " . $this->_connection->connect_error . "\n";
			$info = $this->gatherErrorInfo();

			$insert = new Insert();
			$query = $this->sanitize($insert->Table('ErrorLog')->Values(array("$errMsg", 'NULL', "$info"))->Build());
			$this->_connection->query($query);
			if ($this->_connection->connect_error)
			{
				$doubleCheck = $this->_connection->errno . " : " . $this->_connection->connect_error . "\n";
				$msg = $command . "\n" . $errMsg . "\n" . $info . "\n" . $query . "\n" . $doubleCheck;
				$this->HandleDatabaseFail($this->sanitize($msg));
			}
		}

		return $result;
	}
	
	public function sanitize($message)
	{
		return $this->_connection->escape_string($message);
	}
	
	private function gatherErrorInfo()
	{
		$info = $_SERVER['HTTP_USER_AGENT'] . "\n";
		$info .= " ; User: " . $_SESSION['loggedUserID'] . "\n";
		$info .= " ; Query: " . $_SERVER['QUERY_STRING'] . "\n";
		$info .= " ; Referer: " . $_SERVER['HTTP_REFERER'] . "\n";
		$info .= " ; Remote: " . $_SERVER['REMOTE_ADDR'] . "\n";
		$info .= " ; Script: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
		$info .= " ; Req URI: " . $_SERVER['REQUEST_URI'] . "\n";
		return $info;
	}

	private function HandleDatabaseFail($info)
	{
		mail( $mailto, "crashReport", $info );
	}
}?>