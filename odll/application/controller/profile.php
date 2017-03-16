<?php
class Profile extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
		$this->loadModel('teachers_model');
	}
	
	function index()
	{
		if($_SERVER['HTTP_REFERER'] <> "/profile/settings") {
			$childno = $_GET['childno'];
			$this->misc_model->updateM("user_children", "Active = 'inactive'", "UserChildNo", "{$childno}");
			header("location:".profile."settings");
		}
		header("location:".profile."settings");
	}
	
	function me()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$data["completed"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'completed' OR s.BookingStatus='forfeedback') AND s.Date < DATE(NOW()) AND DATE(s.Date+INTERVAL 20 DAY) >= DATE(NOW())", "DESC", "LIMIT 10");
		$data["upcoming"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'pending' OR s.BookingStatus = 'pending_tutor') AND Date >= DATE(NOW())", "", "LIMIT 10");
		$data["reschedrequest"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND s.BookingStatus = 'pending_client' AND Date > DATE(NOW())", "", "");
		$data["forpayment"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND s.BookingStatus = 'notpaid' AND Date > DATE(NOW())", "", "");
		
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "UserNo = '{$userno}'", "", "", "object");
		if($data["teacher"] != "") {
			//$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "object")->Level; // Change to Level Name
			$data["ac"] = $this->misc_model->getM("*", "teacher_ac", "", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "YEAR DESC", "array");
			$data["referrals"] = $this->misc_model->getM("*", "teacher_referral", "", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "", "array");
			$data["schools"] = $this->misc_model->getM("*", "teacher_school", "", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "FIELD(Level, 'Others', 'Tertiary', 'Secondary')", "array");
			$data["subjects"] = $this->misc_model->getM("*", "teacher_subject ts", "subjects s ON s.SubjectNo = ts.SubjectNo", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "", "array");
			$data["rate"] = $this->misc_model->getM("MIN(RatePerHour) AS minn, MAX(RatePerHour) AS maxx", "teacher_subject ts", "", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "", "object");
			$data["cities"] = $this->misc_model->getM("*", "teacher_city tc", "cities c ON c.CityNo = tc.CityNo", "TeacherNo = '{$data["teacher"]->TeacherNo}'", "", "", "array");
		}
		
		$this->loadView('header', $data);
		$this->loadView('profile_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function bookmarks()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		$data["bookmarks"] = $this->teachers_model->getBookmarksM($userno);
		
		$this->loadView('header', $data);
		//$this->loadView('header-nonAMP', $data);
		$this->loadView('profile-bookmarks_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function bookmark($a)
	{
		$a = explode('+', $a);
		$data["levelno"] = $a['0'];
		$data["cityno"] = $a['1'];
		$data["subject"] = str_replace("%20", " ", $a['2']);
		$TeacherNo = $a['3'];
		
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
		
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level; // Change to Level Name
		$data["city"] = $this->misc_model->getM("*", "cities", "", "CityNo = '{$data["cityno"]}'", "", "", "object")->City;
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["userteacher"] = $this->misc_model->getM("*", "users u", "cities c ON c.CityNo = u.CityNo", "UserNo = '{$data["teacher"]->UserNo}'", "", "", "object");
		$data["schedule"] = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}'", "", "StartTime", "array");
		$data["scheduleb"] = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Day, 'M', 'T', 'W', 'TH', 'F', 'S', 'SU')", "array");
		$data["ac"] = $this->misc_model->getM("*", "teacher_ac", "", "TeacherNo = '{$TeacherNo}'", "", "YEAR DESC", "array");
		$data["referrals"] = $this->misc_model->getM("*", "teacher_referral", "", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		$data["schools"] = $this->misc_model->getM("*", "teacher_school", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Level, 'Others', 'Tertiary', 'Secondary')", "array");
		$data["subjects"] = $this->teachers_model->getTeachesM($TeacherNo);
		//$data["rate"] = $this->misc_model->getM("MIN(RatePerHour) AS minn, MAX(RatePerHour) AS maxx, MAX(AdditionalCost) AS addCost", "teacher_subject ts", "teacher_city tc ON tc.TeacherNo = ts.TeacherNo", "ts.TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["cities"] = $this->misc_model->getM("*", "teacher_city tc", "cities c ON c.CityNo = tc.CityNo", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		
		$data["rate"] = $this->misc_model->getM("(RatePerHour + AdditionalCost) AS TotalCost, ts.*, tc.*", "teacher_subject ts", "teacher_city tc ON tc.TeacherNo = ts.TeacherNo", "ts.TeacherNo = '{$TeacherNo}' AND ts.Level REGEXP '[[:<:]]{$data["levelno"]}[[:>:]]' AND tc.CityNo = '{$data["cityno"]}'", "", "", "object");
		
		$this->loadView('header', $data);
		$this->loadView('profile-bookmark_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function bookings($a)
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		$data["page"] = $a;
		
		if($a == "completed") {
			$data["bookings"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus='completed' OR s.BookingStatus='confirmed' OR s.BookingStatus='forfeedback') AND s.Date < DATE(NOW())", "DESC", "LIMIT 20");
			//$data["bookings2"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND s.BookingStatus = 'confirmed' AND Date < DATE(NOW())", "DESC", "");
		} else if($a == "cancelled") {
			$data["bookings"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND s.BookingStatus = 'cancelled'", "DESC", "LIMIT 20");
		} else {
			$data["today"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'pending' OR s.BookingStatus = 'pending_tutor' OR s.BookingStatus = 'notpaid') AND s.Date = DATE(NOW())", "", "");
			$data["tomorrow"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'pending' OR s.BookingStatus = 'pending_tutor' OR s.BookingStatus = 'notpaid') AND s.Date = DATE(NOW()+INTERVAL 1 DAY)", "", "");
			$data["thisweek"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'pending' OR s.BookingStatus = 'pending_tutor' OR s.BookingStatus = 'notpaid') AND s.Date > DATE(NOW()+INTERVAL 1 DAY) AND s.Date < DATE(NOW()+INTERVAL 7 DAY)", "", "");
			$data["nextweek"] = $this->teachers_model->getMyBookingsM($userno, "session", "AND (s.BookingStatus = 'confirmed' OR s.BookingStatus = 'pending' OR s.BookingStatus = 'pending_tutor' OR s.BookingStatus = 'notpaid') AND s.Date > DATE(NOW()+INTERVAL 7 DAY)", "", "");
		}
		
		$this->loadView('header', $data);
		if($a == "completed" || $a == "cancelled") $this->loadView('profile-bookings-c_view', $data);
		else $this->loadView('profile-bookings_view', $data);
	}
	
	function settings($a)
	{
		if($a == "edit")
			$data["page"] = $a;
		else if($a != "") {
			$a = explode('-', $a);
			if($a['0'] == "editchild") {
				$data["page"] = $a['0'];
				$data["childno"] = $a['1']; }
		}
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		$data["children"] = $this->misc_model->getM("*", "user_children uc", "levels l ON l.LevelNo = uc.LevelNoC JOIN child_learningtype cl ON cl.LearningTypeNo = uc.LearningTypeNoC JOIN child_reason r ON r.ReasonNo = uc.ReasonNoC", "uc.UserNo = '{$userno}' AND uc.Active='active'", "", "", "array");
		
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["reasons"] = $this->misc_model->getM("*", "child_reason", "", "", "", "Reason", "array");
		$data["types"] = $this->misc_model->getM("*", "child_learningtype", "", "", "", "LearningType", "array");
		
		$this->loadView('header', $data);
		$this->loadView('profile-settings_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function mentor($TeacherNo)
	{
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level; // Change to Level Name
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["user"] = $this->misc_model->getM("*", "users u", "cities c ON c.CityNo = u.CityNo", "UserNo = '{$data["teacher"]->UserNo}'", "", "", "object");
		$data["ac"] = $this->misc_model->getM("*", "teacher_ac", "", "TeacherNo = '{$TeacherNo}'", "", "YEAR DESC", "array");
		$data["referrals"] = $this->misc_model->getM("*", "teacher_referral", "", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		$data["schools"] = $this->misc_model->getM("*", "teacher_school", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Level, 'Others', 'Tertiary', 'Secondary')", "array");
		$data["subjects"] = $this->misc_model->getM("*", "teacher_subject ts", "subjects s ON s.SubjectNo = ts.SubjectNo", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		$data["rate"] = $this->misc_model->getM("MIN(RatePerHour) AS minn, MAX(RatePerHour) AS maxx", "teacher_subject ts", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["cities"] = $this->misc_model->getM("*", "teacher_city tc", "cities c ON c.CityNo = tc.CityNo", "TeacherNo = '{$TeacherNo}'", "", "", "array");
		
		$this->loadView('header', $data);
		$this->loadView('profile-mentor_view', $data);
		$this->loadView('footer', $data);
	}
}
?>