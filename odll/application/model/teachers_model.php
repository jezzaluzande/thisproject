<?php
class teachers_model extends Application
{
	var $db;
	
    public function __construct()
    {
        $db = $this->loadDatabase();
		session_start();
	}
	
	function getMyBookingsM($userno, $type, $condition, $order, $limit)
	{
		//if($status != "") $status = "'{$status}'";
		$query = "SELECT s.Date, s.StartTime, c.UserChildNo, c.Nickname AS KidName, u.NickName, t.TeacherNo, d.SubjectNo, su.Subject, l.Level, s.BookingStatus FROM booking_details d
					JOIN booking_schedule s ON d.BookingDetailNo = s.BookingDetailNo
					JOIN user_children c ON d.UserChildNo = c.UserChildNo
					JOIN subjects su ON d.SubjectNo = su.SubjectNo
					JOIN levels l ON d.LevelNo = l.LevelNo
					JOIN teachers t ON d.TeacherNo = t.Teacherno
					JOIN users u ON t.UserNo = u.UserNo
					WHERE d.UserNo = '{$userno}'
					AND s.Type = '{$type}'
					{$condition}
					ORDER BY s.Date {$order}, s.StartTime, u.NickName
					{$limit}";
		//echo $query."<br>";
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	function getMyBookingsForReschedM($userno, $teacherno, $date, $subjectno, $childno)
	{
		$query = "SELECT *, d.CityNo FROM booking_details d
					JOIN booking_schedule s ON d.BookingDetailNo = s.BookingDetailNo
					JOIN user_children c ON d.UserChildNo = c.UserChildNo
					JOIN subjects su ON d.SubjectNo = su.SubjectNo
					JOIN levels l ON d.LevelNo = l.LevelNo
					JOIN teachers t ON d.TeacherNo = t.Teacherno
					JOIN users u ON t.UserNo = u.UserNo
					WHERE d.UserNo = '{$userno}'
					AND Type = 'session'
					AND t.TeacherNo = '{$teacherno}'
					AND Date = '{$date}'
					AND d.SubjectNo = '{$subjectno}'
					AND c.UserChildNo = '{$childno}'
					ORDER BY StartTime";
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	function getActiveSubjectsM()
	{
		$query = "SELECT DISTINCT(Subject), s.SubjectType FROM subjects s
					JOIN teacher_subject ts ON s.SubjectNo = ts.SubjectNo
					JOIN teachers t ON t.TeacherNo = ts.TeacherNo
					JOIN users u ON u.UserNo = t.UserNo
					WHERE u.Status = 'active' AND ts.Status = 'active'
					ORDER BY s.SubjectType, s.Subject";
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	/*function getAdvancedResultsM($select, $condition, $order)
	{
		if($condition != "") $condition = "AND {$condition}";
		if($order != "") $order = "ORDER BY {$order}";
		$query = "SELECT {$select} FROM subjects s
					JOIN teacher_subject ts ON s.SubjectNo = ts.SubjectNo
					JOIN levels l ON l.LevelNo = ts.LevelNo
					JOIN teachers t ON t.TeacherNo = ts.TeacherNo
					JOIN users u ON u.UserNo = t.UserNo
					JOIN cities c ON c.CityNo = u.CityNo
					WHERE ts.status = 'active' AND u.status = 'active' {$condition}
					{$order}";
		//echo $query;
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}*/
	
	function getSearchResultsM($level, $subject, $city)
	{
		if($subject == 0) { //All Subjects
			$subjcount = "SELECT * FROM teacher_subject WHERE Status='active' GROUP BY SubjectNo";
			$this->db->prepare($subjcount);
			$this->db->query();
			$subjcount = count($this->db->fetch('array'))-1;
			$con = "GROUP BY u.UserNo
					HAVING COUNT(*) > {$subjcount}";
		} else $con = "AND s.SubjectNo = '{$subject}'
					GROUP BY u.UserNo";
		$query = "SELECT (MAX(ts.RatePerHour) + MAX(tc.AdditionalCost)) AS TotalCost, t.TeacherNo, u.Nickname, u.Gender, u.Birthday, u.Address, c.City, t.Profession, t.TutoringStyle, ts.RatePerHour, t.Picture FROM teachers t
					JOIN users u ON u.UserNo = t.UserNo
					JOIN teacher_subject ts ON ts.TeacherNo = t.TeacherNo
					JOIN teacher_city tc ON tc.TeacherNo = t.TeacherNo
					JOIN subjects s ON ts.SubjectNo = s.SubjectNo
					JOIN cities c ON c.CityNo = u.CityNo
					WHERE ts.Status = 'active' AND tc.CityNo = '{$city}' AND ts.Level REGEXP '[[:<:]]{$level}[[:>:]]'
					{$con}";
		//echo $query;
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	function getBookmarksM($userno)
	{
		$query = "SELECT (MAX(ts.RatePerHour) + MAX(tc.AdditionalCost)) AS TotalCost, ub.*, s.Subject, l.Level, t.TeacherNo, u.Nickname, u.Gender, u.Birthday, u.Address, c.City, t.Profession, t.TutoringStyle, t.Picture FROM user_bookmarks ub
					JOIN teachers t ON t.TeacherNo = ub.TeacherNo
					JOIN users u ON u.UserNo = t.UserNo
					JOIN teacher_subject ts ON ts.TeacherNo = t.TeacherNo
					JOIN teacher_city tc ON tc.TeacherNo = t.TeacherNo
					JOIN subjects s ON ts.SubjectNo = s.SubjectNo
					JOIN levels l ON l.LevelNo = ub.LevelNo
					JOIN cities c ON c.CityNo = ub.CityNo
					WHERE ub.UserNo = '{$userno}'
					  AND tc.CityNo = ub.CityNo
					  AND (ts.SubjectNo = ub.SubjectSearched OR ub.SubjectSearched = '0')
					  GROUP BY t.TeacherNo
					  ORDER BY ub.SubjectSearched, ub.CityNo, u.Nickname, DATE(ub.Timestamp) DESC;";
					//AND ts.LevelNo = ub.LevelNo
		//echo $query;
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	function getTeachesM($teacherno, $levelno)
	{
		$query = "SELECT * FROM teacher_subject ts
					JOIN subjects s ON s.SubjectNo = ts.SubjectNo
					WHERE TeacherNo = '{$teacherno}'
					AND Level REGEXP '[[:<:]]{$levelno}[[:>:]]'
					AND Status='active'
					ORDER BY s.Subject";
		$this->db->prepare($query);
		$this->db->query();
		$results = $this->db->fetch('array');
		return $results;
	}
	
	/*function deleteM($table, $condition)
	{
		$this->db->prepare("DELETE FROM $table WHERE $condition");
		$this->db->query();
	}
	
	function getM($table, $condition, $order, $resultset)
	{
		$query = "SELECT * FROM $table";
		$query2 = "";
		if($condition != "")
			$query2 .= " WHERE $condition";
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
		
	function updateM($table, $changes, $column, $value)
	{
		$this->db->prepare("UPDATE $table SET $changes WHERE $column = '$value'");
		$this->db->query();
	}*/
}
?>