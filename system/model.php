<?php

class Model {

	private $connection;

	public function __construct()
	{
		global $config;

		$this->connection = $config['db_connection'];
	}

	public function loadModel($name)
	{
		require_once(APP_DIR .'models/'. strtolower($name) .'.php');

		$model = new $name;
		return $model;
	}

	public function escapeString($string)
	{
		return mysqli_real_escape_string($this->connection, $string);
	}

	public function escapeArray($array)
	{
	    //array_walk_recursive($array, create_function('&$v', '$v = mysqli_real_escape_string($v);'));
		return $array;
	}
	
	public function to_bool($val)
	{
	    return !!$val;
	}
	
	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}
	
	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}
	
	public function to_datetime($val)
	{
		//return date('g:i A F jS, Y');
	    return date('g:i A F jS, Y', $val);
	}
	
	public function query($qry, $res = true, $raw = false)
	{
		if ($res) {
			$result = mysqli_query($this->connection, $qry);
			if ($raw) return $result;

			$resultObjects = array();
	
			while($row = mysqli_fetch_object($result)) $resultObjects[] = $row;
	
			return $resultObjects;
		} else {
			mysqli_query($this->connection, $qry) or die('MySQL Error: '. mysqli_error($this->connection));
		}
	}

	public function execute($qry)
	{
		$exec = mysqli_query($this->connection, $qry) or die('MySQL Error: '. mysqli_error($this->connection));
		return $exec;
	}

	public function finished()
	{
		//$this->connection->close();
	}
    
}
?>
