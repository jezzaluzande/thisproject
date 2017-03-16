<?php
class misc_model extends Application
{
	/*
	addM(Table, Columns, Values)
	deleteM(Table, Column, Value)
	getM(Table, Column, Value, Order, Resultset)
	updateM(Table, Changes, Column, Value)
	*/

    var $db;
	
    public function __construct()
    {
        $db = $this->loadDatabase();
		session_start();
	}
	
	function addM($table, $columns, $values)
	{
		$this->db->prepare("INSERT INTO $table($columns) VALUE($values)");
		$this->db->query();
		//echo "<br><br>INSERT INTO $table($columns) VALUE($values)<br>";
	}
	
	function deleteM($table, $condition)
	{
		$this->db->prepare("DELETE FROM $table WHERE $condition");
		$this->db->query();
		//echo "DELETE FROM $table WHERE $condition";
	}
	
	function getM($select, $table, $join, $condition, $group, $order, $resultset)
	{
		$query = "SELECT $select FROM $table";
		$query2 = "";
		if($join != "")
			$query2 .= " JOIN $join";
		if($condition != "")
			$query2 .= " WHERE $condition";
		if($group != "")
			$query2 .= " GROUP BY $group";
		if($order != "")
			$query2 .= " ORDER BY $order";
		//echo $query." ".$query2."<br>";
		$this->db->prepare($query." ".$query2);
		$this->db->query();
		if($resultset == "object")
			$results = $this->db->fetch('object');
		if($resultset == "array")
			$results = $this->db->fetch('array');
		return $results;
	}
	
	function getDateTodayM()
	{
		$this->db->prepare("SELECT DATE(NOW()) AS Result");
		$this->db->query();
		$results = $this->db->fetch('object');
		return $results;
	}
	
	function updateM($table, $changes, $column, $value)
	{
		$this->db->prepare("UPDATE $table SET $changes WHERE $column = '$value'");
		$this->db->query();
		//echo "UPDATE $table SET $changes WHERE $column = '$value'<br>";
	}
}
?>