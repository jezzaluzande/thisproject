<?php
class Search extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
		$this->loadModel('teachers_model');
	}
	
	function index()
	{
		header("location:".search."results/{$_GET["home-select"]}+{$_GET["home-select2"]}+{$_GET["home-input"]}");
	}
	
	function advanced()
	{
		$data["subjects"] = $this->teachers_model->getActiveSubjectsM();
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["cities"] = $this->misc_model->getM("*", "cities", "", "", "", "", "array");
		
		$data["results"] = $this->teachers_model->getAdvancedResultsM("DISTINCT(c.CityNo), c.City", "", "c.City");
		
		$this->loadView('header', $data);
		$this->loadView('search-advanced_view', $data);
		$this->loadView('footer', $data);
	}
	
	function getItems($a)
	{
		echo $a;
		
		$a = explode('_', $a);
		$type = $a['0'];
		$typeno = $a['1'];
		
		if($type == "city") {
			$results = $this->teachers_model->getAdvancedResultsM("DISTICNT(s.SubjectType)", "c.CityNo = {$typeno}", "s.SubjectType");
		}
		
		//echo "<select id='' name='' onchange=''>";
		foreach($results as $r):
			echo "<option value=''>{$r['SubjectType']}</option>";
		endforeach;
		//echo "</select>";
	}
	
	function results($a)
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		//Functions search and results are separated so when a page is refreshed, form resubmission won't appear
		$a = explode('+', $a);
		$data["levelno"] = $a['0']; // Numerical value
		$data["cityno"] = $a['1'];
		$data["subjectno"] = $a['2'];
		$data["results"] = $this->teachers_model->getSearchResultsM($data["levelno"], $data["subjectno"], $data["cityno"]);
		if($data["subjectno"]==0) $data["subject"] = "All Subjects";
		else $data["subject"] = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$data["subjectno"]}'", "", "", "object")->Subject; // Change to Subject Name
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level; // Change to Level Name
		$data["city"] = $this->misc_model->getM("*", "cities", "", "CityNo = '{$data["cityno"]}'", "", "", "object")->City; // Change to City Name
		$data["subjects"] = $this->misc_model->getM("s.SubjectNo, s.Subject", "subjects s", "teacher_subject t ON s.SubjectNo=t.SubjectNo", "t.Status='active'", "s.SubjectNo", "s.Subject", "array");
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["cities"] = $this->misc_model->getM("*", "cities c", "teacher_city ts ON ts.CityNo = c.CityNo", "", "c.CityNo", "c.City", "array");
		
		$this->loadView('header', $data);
		$this->loadView('search-results_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function profile($a)
	{
		$a = explode('+', $a);
		$data["levelno"] = $a['0'];
		$data["cityno"] = $a['1'];
		$data["subjectno"] = $a['2'];
		$TeacherNo = $a['3'];
		
		$bookmark = explode('_', $TeacherNo);
		$TeacherNo = $bookmark['0'];
		$bookmark = $bookmark['1'];
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		$data["children"] = $this->misc_model->getM("*", "user_children", "", "UserNo = '{$userno}' AND Active='active'", "", "", "array");
		
		$bookmarkcheck = $this->misc_model->getM("*", "user_bookmarks", "", "UserNo = '{$userno}' AND TeacherNo = '{$TeacherNo}'", "", "", "object");
		if($bookmarkcheck) $data["bookmarked"] = "true";
		else $data["bookmarked"] = "false";
		if($bookmark == "b" && !$bookmarkcheck) {
			$this->misc_model->addM("user_bookmarks", "UserNo, TeacherNo, SubjectSearched, LevelNo, CityNo, Timestamp", "{$userno}, {$TeacherNo}, '{$data["subject"]}', {$data["levelno"]}, {$data["cityno"]}, NOW()");
			$data["bookmarked"] = "true";
		} else if($bookmark == "bd" && $bookmarkcheck) {
			$this->misc_model->deleteM("user_bookmarks", "UserNo = '{$userno}' AND TeacherNo = '{$TeacherNo}'");
			$data["bookmarked"] = "false";
		}
		
		if($data["subjectno"]==0) $data["subject"] = "All Subjects";
		else $data["subject"] = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$data["subjectno"]}'", "", "", "object")->Subject; // Change to Subject Name
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level; // Change to Level Name
		$data["city"] = $this->misc_model->getM("*", "cities", "", "CityNo = '{$data["cityno"]}'", "", "", "object")->City;
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["userteacher"] = $this->misc_model->getM("*", "users u", "cities c ON c.CityNo = u.CityNo", "UserNo = '{$data["teacher"]->UserNo}'", "", "", "object");
		$data["schedule"] = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')", "array");
		$data["ac"] = $this->misc_model->getM("*", "teacher_ac", "", "TeacherNo = '{$TeacherNo}'", "", "YEAR DESC", "array");
		$data["referrals"] = $this->misc_model->getM("*", "teacher_referral", "", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		$data["schools"] = $this->misc_model->getM("*", "teacher_school", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Level, 'Others', 'Tertiary', 'Secondary')", "array");
		$data["subjects"] = $this->teachers_model->getTeachesM($TeacherNo, $data["levelno"]);
		$data["cities"] = $this->misc_model->getM("*", "teacher_city tc", "cities c ON c.CityNo = tc.CityNo", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		
		$data["rate"] = $this->misc_model->getM("(RatePerHour + AdditionalCost) AS TotalCost, ts.*, tc.*", "teacher_subject ts", "teacher_city tc ON tc.TeacherNo = ts.TeacherNo", "ts.TeacherNo = '{$TeacherNo}' AND ts.Level REGEXP '[[:<:]]{$data["levelno"]}[[:>:]]' AND tc.CityNo = '{$data["cityno"]}'", "", "", "object");
		
		$this->loadView('header', $data);
		$this->loadView('search-profile_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function thankyou($a)
	{
		$this->loadView('header', $data);
		$this->loadView('search-book2_view', $data);
		$this->loadView('footer', $data);
	}
	
	function findteacher()
	{
		$data['request'] = $_POST['noresults-search'];
		$search = str_replace("'", "&#039;", $_POST['noresults-search']);
		$name = str_replace("'", "&#039;", $_POST['noresults-name']);
		$email = str_replace("'", "&#039;", $_POST['noresults-email']);
		$mobile = str_replace("'", "&#039;", $_POST['noresults-mobile']);
		$message = str_replace("'", "&#039;", $_POST['noresults-message']);
		$now = date("j-M-Y H:i:s");
		
		if($_SESSION['first_name'] != "")
			$userno = $this->misc_model->getM("*", "users", "", "Email = '{$_SESSION[email]}'", "", "", "object")->UserNo;
		else $userno = 0;
		
		$this->misc_model->addM("received_requests", "SearchingFor, Name, Email, Mobile, Notes, UserNo, Timestamp", "'{$search}', '{$name}', '{$email}', '{$mobile}', '{$message}', '{$userno}', NOW()");
		
		$to = $this->misc_model->getM("*", "odll_details", "", "Detail = 'Contact Us'", "", "", "object")->Email;
		$subject = "TEACHER REQUEST FROM ".$name." ON ".$now;
		$txt = wordwrap($message, 70);
$txt = "Name: {$name} (User No. {$userno})
E-mail: {$email}
Mobile: {$mobile}
Sent On: {$now}

Searching For: {$search}
{$txt}";
		//mail($to,$subject,$txt);
		
		$this->loadView('header', $data);
		$this->loadView('search-findteacher_view', $data);
		$this->loadView('footer', $data);
	}
	
	function subjects()
	{
		$data["subjects"] = $this->teachers_model->getActiveSubjectsM();
		$data["cities"] = $this->misc_model->getM("*", "cities", "", "", "", "", "array");
		$this->loadView('header', $data);
		$this->loadView('search-subjects_view', $data);
		$this->loadView('footer', $data);
	}
}
?>