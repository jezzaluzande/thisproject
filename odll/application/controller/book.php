<?php
class Book extends Application
{
	public $day_order = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	public $booking_frequency = 60;
	
	function __construct()
	{
		$this->loadModel('misc_model');
		$this->loadModel('teachers_model');
	}
	
	function index()
	{
		$a = explode('+', $_GET["bookingreference"]);
		$data["levelno"] = $a['0'];
		$data["cityno"] = $a['1'];
		$data["subjectno"] = $a['2'];
		$TeacherNo = $a['3'];
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		if(isset($_GET['month']))	$data["month"] = $_GET['month'];	else $data["month"] = date("m");
		if(isset($_GET['year']))	$data["year"] = $_GET['year'];		else $data["year"] = date("Y");
		if(isset($_GET['day']))		$data["day"] = $_GET['day'];		else $data["day"] = 0;
		
		// Calendar References
		$data["selected_date"] = mktime(0, 0, 0, $data["month"], 01, $data["year"]); 
		$data["back_month"] = date("m", strtotime("-1 month", $data["selected_date"]));
		$data["back_year"] = date("Y", strtotime("-1 month", $data["selected_date"]));
		$data["forward_month"] = date("m", strtotime("+1 month", $data["selected_date"]));
		$data["forward_year"] = date("Y", strtotime("+1 month", $data["selected_date"]));
		$data["day_order"] = $this->day_order;
		$data["days"] = $this->getCalendarDays($data['month'], $data['year']);
		$data["day_closed"] = $this->getTeacherClosedDays($TeacherNo);
		$data["bookings_per_day"] = $this->getBookingsPerDay($TeacherNo, "");
		foreach($data["day_order"] as $d) {
			$dee = substr($d, 0, 3);
			$data[$dee] = $this->getTeacherDaySched($TeacherNo, $dee); }
		$data["booking_frequency"] = $this->booking_frequency;
		
		// Other References
		$data["children"] = $this->misc_model->getM("*", "user_children", "", "UserNo = '{$userno}' AND Active='active' AND LevelNoC='{$data["levelno"]}'", "", "", "array");
		$data["page"] = "booking";
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["reasons"] = $this->misc_model->getM("*", "child_reason", "", "", "", "Reason", "array");
		$data["types"] = $this->misc_model->getM("*", "child_learningtype", "", "", "", "LearningType", "array");
		if($data["subjectno"] == 0) $data["subject"] = "All Subjects";
		else $data["subject"] = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$data["subjectno"]}'", "", "", "object")->Subject;
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level;
		$data["city"] = $this->misc_model->getM("*", "cities", "", "CityNo = '{$data["cityno"]}'", "", "", "object")->City;
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["userteacher"] = $this->misc_model->getM("*", "users u", "cities c ON c.CityNo = u.CityNo", "UserNo = '{$data["teacher"]->UserNo}'", "", "", "object");
		$data["schedule"] = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')", "array");
		$data["rate"] = $this->misc_model->getM("(RatePerHour + AdditionalCost) AS TotalCost, ts.*, tc.*", "teacher_subject ts", "teacher_city tc ON tc.TeacherNo = ts.TeacherNo", "ts.TeacherNo = '{$TeacherNo}' AND ts.Level REGEXP '[[:<:]]{$data["levelno"]}[[:>:]]' AND tc.CityNo = '{$data["cityno"]}'", "", "", "object");
		
		$this->loadView('book_view', $data);
	}
	
	function cancel($a)
	{
		$a = explode('+', $a);
		$BookingDate = $a['0'];	$data["bookingdate"] = $BookingDate;
		$TeacherNo = $a['1'];
		$ChildNo = $a['2'];
		$SubjectNo = $a['3'];	$data["subjectno"] = $SubjectNo;
		$TimeStart = $a['4'];
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$BookingScheduleNo = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}' AND s.StartTime = '{$TimeStart}'", "", "", "object")->BookingScheduleNo;
		$this->misc_model->updateM("booking_schedule", "BookingStatus = 'cancelled'", "BookingScheduleNo", "{$BookingScheduleNo}");
		$this->misc_model->addM("booking_changes", "BookingScheduleNo, ChangeDone, ChangedBy, Timestamp", "'{$BookingScheduleNo}', 'cancelled', 'client', NOW()");
		
		header("location:".profile."bookings");
	}
	
	function reschedule($a)
	{
		$a = explode('+', $a);
		$BookingDate = $a['0'];	$data["bookingdate"] = $BookingDate;
		$TeacherNo = $a['1'];
		$ChildNo = $a['2'];
		$SubjectNo = $a['3'];	$data["subjectno"] = $SubjectNo;
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$data["month"] = date("m", strtotime($BookingDate));	$month = $data["month"];
		$data["year"] = date("Y", strtotime($BookingDate));		$year = $data["year"];
		$data["day"] = date("d", strtotime($BookingDate));		$day = $data["day"];
		
		// Calendar References
		$data["selected_date"] = mktime(0, 0, 0, $data["month"], 01, $data["year"]); 
		$data["back_month"] = date("m", strtotime("-1 month", $data["selected_date"]));
		$data["back_year"] = date("Y", strtotime("-1 month", $data["selected_date"]));
		$data["forward_month"] = date("m", strtotime("+1 month", $data["selected_date"]));
		$data["forward_year"] = date("Y", strtotime("+1 month", $data["selected_date"]));
		$data["day_order"] = $this->day_order;
		$data["days"] = $this->getCalendarDays($data['month'], $data['year']);
		$data["day_closed"] = $this->getTeacherClosedDays($TeacherNo);
		$data["bookings_per_day"] = $this->getBookingsPerDay($TeacherNo, "{$BookingDate}_{$ChildNo}_{$SubjectNo}");
		foreach($data["day_order"] as $d) {
			$dee = substr($d, 0, 3);
			$data[$dee] = $this->getTeacherDaySched($TeacherNo, $dee); }
		$data["booking_frequency"] = $this->booking_frequency;
		
		/** RESCHEDULE START **/
		
		// $slots_booked contains all timeslots of the teacher blocked as "session"
		$slots_booked = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}'", "", "s.StartTime", "array");
		
		$RescheduleFee = 0;
		$starttimearray = array();
		$starttimearray2 = array();
		foreach($slots_booked as $r):
			$starttimearray[] = array(
				"date" => $r['Date'],
				"start" => $r['StartTime']
			);
			array_push($starttimearray2, $r['StartTime']);
			$data["levelno"] = $r["LevelNo"];
			$data["cityno"] = $r["CityNo"];
			$data["RatePerHour"] = $r["RatePerHour"];
			$data["slots_booked"] .= "{$r["Date"]}_{$r["StartTime"]}|";
			$finish_time = strtotime($r["StartTime"]) + 3600;
			$finish_time = date("H:i:s", $finish_time);
			$js_slots[] = "{$r["Date"]}_{$r["StartTime"]}-{$finish_time}";
			
			// Compute RescheduleFee
			if($r["BookingStatus"] == "pending") {
				// NO FEE - Tutor hasn't confirmed yet
				$RescheduleFee = $RescheduleFee + 0;
			} else if($r["BookingStatus"] == "notpaid" || $r["BookingStatus"] == "confirmed") {
				// WITH FEE - Tutor has confirmed already
				$source_timestamp = strtotime($r['Date']." ".$r['StartTime']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				$now_timestamp = strtotime(date("Y-m-d H:i:s"));
				
				// P50 - Booking is scheduled in MORE than 12 hours from reschedule
				if($new_timestamp > $now_timestamp) {
					$RescheduleFee = $RescheduleFee + 50;
				// 50% - Booking is scheduled in LESS than 12 hours from reschedule
				} else if($new_timestamp < $now_timestamp) {
					$RescheduleFee = $RescheduleFee + ($r['RatePerHour']/2);
				}
			}
			$data["RescheduleFee"] = $RescheduleFee;
		endforeach;
		$data["js_slots"] = $js_slots;
		
		$checkday = date("D", strtotime($BookingDate));
		$dee2 = $this->getTeacherDaySched($TeacherNo, $checkday);
		$data["booking_start_time_r"] = strtotime($dee2->StartTime);
		$data["booking_end_time_r"]	= strtotime($dee2->EndTime) - 3600;
		
		$hourscount = count($starttimearray2);
		$sessiongroup = array();
		$sesh = array("start" => $starttimearray2[0]);
		array_push($sessiongroup, $sesh);
		for($i = 0; $i < $hourscount-1; $i++) {
			$plusone = strtotime($starttimearray2[$i]) + 3600;
			$nextone = strtotime($starttimearray2[$i+1]);
			if($plusone == $nextone) {
				$sessiongroup[$i]['end']= date("H:i:s", $nextone);
			} else {
				$sessiongroup[$i+1]['start']= date("H:i:s", $nextone);
			}
		}
		$freeup = array();
		for($n = 0; $n < $i+1; $n++) {
			$freeup = $this->freeUpBefore($freeup, $sessiongroup[$n]['start'], $dee2->StartTime, $dee2->EndTime, $BookingDate);
			if($sessiongroup[$n]['end'] == "")
				$freeup = $this->freeUpAfter($freeup, $sessiongroup[$n]['start'], $dee2->StartTime, $dee2->EndTime, $BookingDate);
			else
				$freeup = $this->freeUpAfter($freeup, $sessiongroup[$n]['end'], $dee2->StartTime, $dee2->EndTime, $BookingDate);
		}
		
		// Function array_diff for multidimensional arrays
		function multidimensional_array_diff($a, $b) { 
			return $a['start'] - $b['start']; }
		
		// Contains all timeslots (this teacher) blocked as "session" or "transportation"
		$blocked_time = $this->misc_model->getM("s.Date, s.StartTime, s.Type, s.BookingStatus", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND s.Date='{$BookingDate}' AND (s.Type='transportation' OR s.Type='session')", "", "", "array");
		
		if($blocked_time != "") {
			foreach($blocked_time as $e):
				$bookings[] = array(
					"date" => $e["Date"],
					"start" => $e["StartTime"]
				);
			endforeach;
			// Remove from bookings: slots to be rescheduled
			$bookings = array_udiff($bookings, $starttimearray, 'multidimensional_array_diff');
			// Remove from bookings: unnecessary transportation time
			$bookings = array_udiff($bookings, $freeup, 'multidimensional_array_diff');
			$data["bookings"] = $bookings;
		}
		
		/** RESCHEDULE END **/
		
		// Other References
		$data["child"] = $this->misc_model->getM("*", "user_children", "", "UserChildNo = '{$ChildNo}'", "", "", "object");
		$data["page"] = "reschedule";
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["reasons"] = $this->misc_model->getM("*", "child_reason", "", "", "", "Reason", "array");
		$data["types"] = $this->misc_model->getM("*", "child_learningtype", "", "", "", "LearningType", "array");
		if($data["subjectno"] == 0) $data["subject"] = "All Subjects";
		else $data["subject"] = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$data["subjectno"]}'", "", "", "object")->Subject;
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["levelno"]}'", "", "", "object")->Level;
		$data["city"] = $this->misc_model->getM("*", "cities", "", "CityNo = '{$data["cityno"]}'", "", "", "object")->City;
		$data["teacher"] = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$TeacherNo}'", "", "", "object");
		$data["userteacher"] = $this->misc_model->getM("*", "users u", "cities c ON c.CityNo = u.CityNo", "UserNo = '{$data["teacher"]->UserNo}'", "", "", "object");
		$data["schedule"] = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}'", "", "FIELD(Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')", "array");
		$data["rate"] = $this->misc_model->getM("(RatePerHour + AdditionalCost) AS TotalCost, ts.*, tc.*", "teacher_subject ts", "teacher_city tc ON tc.TeacherNo = ts.TeacherNo", "ts.TeacherNo = '{$TeacherNo}' AND ts.Level REGEXP '[[:<:]]{$data["levelno"]}[[:>:]]' AND tc.CityNo = '{$data["cityno"]}'", "", "", "object");
		
		$this->loadView('book_view', $data);
	}
		
	function submitBooking()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$_POST['address'] = str_replace("'", "&#039;", $_POST['address']);
		$_POST['landmark'] = str_replace("'", "&#039;", $_POST['landmark']);
		$this->misc_model->updateM("users", "Address = '{$_POST['address']}', AddressLandmark='{$_POST['landmark']}'", "UserNo", "{$userno}");
		
		if($_POST['childno'] == 0) {
			$_POST['nickname'] = str_replace("'", "&#039;", $_POST['nickname']);
			$_POST['notes'] = str_replace("'", "&#039;", $_POST['notes']);
			$this->misc_model->addM("user_children", "Nickname, Birthday, Gender, LevelNoC, LearningTypeNoC, ReasonNoC, Notes, UserNo, Active", "'{$_POST["nickname"]}', '{$_POST["birthday"]}', '{$_POST["gender"]}', {$_POST["level"]}, {$_POST["type"]}, {$_POST["reason"]}, '{$_POST["notes"]}', {$userno}, 'active'");
			$childno = $this->misc_model->getM("*", "user_children", "", "UserNo = '{$userno}'", "", "UserChildNo DESC", "object")->UserChildNo;
		} else {
			$childno = $_POST['childno'];
		}
		$levelno = $this->misc_model->getM("*", "user_children", "", "UserChildNo = '{$childno}'", "", "", "object")->LevelNoC;
		
		$this->misc_model->addM("booking_details", "UserNo, UserChildNo, TeacherNo, SubjectNo, LevelNo, CityNo, RatePerHour, Timestamp", "'{$userno}', '{$childno}', '{$_POST['teacherno']}', '{$_POST['subjectno']}', '{$levelno}', '{$_POST['cityno']}', '{$_POST['cost_per_slot']}', NOW()");
		$bookingno = $this->misc_model->getM("*", "booking_details", "", "UserNo = '{$userno}'", "", "Timestamp DESC", "object")->BookingDetailNo;
		
		$slots_booked = explode('|', $_POST['slots_booked']);
		foreach($slots_booked as $exp) {
			$e = explode('_', $exp);
			if(strlen($exp) > 0) {
				$this->misc_model->addM("booking_schedule", "BookingDetailNo, Date, StartTime, Type, BookingStatus, PaymentStatus, Timestamp", "'{$bookingno}', '{$e['0']}', '{$e['1']}', 'session', 'pending', '0', NOW()");
			}
		}
		
		// Block one hour before and one hour after for tutor transportation
		$added_slots = $this->misc_model->getM("*", "booking_schedule", "", "BookingDetailNo = '{$bookingno}' AND Type='session'", "", "Date, StartTime", "array");
		$olddate = "";
		$oldtime = "";
		foreach($added_slots as $as):
			if($olddate == $as['Date']) {
				// If previous time and current time are not consecutive, insert after slot. If consecutive, do nothing
				if(strtotime($oldtime) != strtotime($as['StartTime'])-3600) {
					$this->blockTranspo($bookingno, $olddate, $oldtime, "+", "", "pending"); }
			} else {
				if($oldtime != "") {
					$this->blockTranspo($bookingno, $olddate, $oldtime, "+", "", "pending"); }
				// Insert before time slot
				$this->blockTranspo($bookingno, $as['Date'], $as['StartTime'], "-", "", "pending");
			}
			$olddate = $as['Date'];
			$oldtime = $as['StartTime'];
		endforeach;
		// Insert after last slot
		$this->blockTranspo($bookingno, $olddate, $oldtime, "+", "", "pending");
		
		header("location:".book."thankyou");
	}
	
	function submitResched()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$TeacherNo = $_POST['teacherno'];
		$BookingDate = $_POST['bookingdate'];
		$SubjectNo = $_POST['subjectno'];
		$ChildNo = $_POST['childno'];
		$levelno = $this->misc_model->getM("*", "user_children", "", "UserChildNo = '{$ChildNo}'", "", "", "object")->LevelNoC;
		
		// Function in_array for multidimensional arrays
		function search_array($needle, $haystack) {
			if(in_array($needle, $haystack)) {
				return true; }
			foreach($haystack as $element) {
				if(is_array($element) && search_array($needle, $element))
					return true; }
			return false;
		}

		// Function array_diff for multidimensional arrays
		function arr_diff($a1,$a2){
			foreach($a1 as $k=>$v){
				unset($dv);
				if(is_int($k)){
					// Compare values
					if(array_search($v,$a2)===false) $dv=$v;
					else if(is_array($v)) $dv=arr_diff($v,$a2[$k]);
					if($dv) $diff[]=$dv;
				} else {
					// Compare noninteger keys
					if(!$a2[$k]) $dv=$v;
					else if(is_array($v)) $dv=arr_diff($v,$a2[$k]);
					if($dv) $diff[$k]=$dv;
				}
			}
			return $diff;
		}
		
		$resched = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}'", "", "s.StartTime", "array");
		
		$origsched = array();
		$datearray = array();
		foreach($resched as $r):
			$origsched[] = array(
				"date" => $r['Date'],
				"start" => $r['StartTime']
			);
			if(!in_array($r['Date'], $datearray)) {
				array_push($datearray, $r['Date']);
			}
		endforeach;
		
		$slots_booked = explode('|', $_POST['slots_booked']);
		$newsched = array();
		foreach($slots_booked as $exp) {
			$e = explode('_', $exp);
			if(strlen($exp) > 0) {
				$newsched[] = array(
					"date" => $e['0'],
					"start" => $e['1']
				);
			}
		}
				
		for($i = 0; $i < count($newsched); $i++) {
			if(search_array($newsched[$i], $origsched)) { // If slot is in orig sched, do nothing
			} else {
				if(!in_array($newsched[$i]['date'], $datearray)) {
					array_push($datearray, $newsched[$i]['date']); }
				$BookingDetailNo = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}'", "", "s.StartTime LIMIT 1", "object")->BookingDetailNo;
				$this->misc_model->addM("booking_schedule", "BookingDetailNo, Date, StartTime, Type, BookingStatus, Timestamp", "'{$BookingDetailNo}', '{$newsched[$i]['date']}', '{$newsched[$i]['start']}', 'session', 'pending_tutor', NOW()");
			}
		}
		
		$toremove = array();
		$toremove = arr_diff($origsched, $newsched);
		for($i = 0; $i < count($toremove); $i++) {
			$BookingScheduleNo = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$toremove[$i]['date']}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}' AND s.StartTime='{$toremove[$i]['start']}'", "", "", "object")->BookingScheduleNo;
			$this->misc_model->updateM("booking_schedule", "BookingStatus = 'rescheduled'", "BookingScheduleNo", "{$BookingScheduleNo}");
			$this->misc_model->addM("booking_changes", "BookingScheduleNo, ChangeDone, ChangedBy, Timestamp", "'{$BookingScheduleNo}', 'rescheduled', 'client', NOW()");
		}
		
		for($x = 0; $x < count($datearray); $x++) {
			$existingbookings = $this->misc_model->getM("s.Date, s.StartTime, s.Type", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND s.Date='{$datearray[$x]}' AND s.Type='session'", "", "s.StartTime", "array");
			
			$sessiongroup = array();
			$sesh = array("start" => $existingbookings[0]['StartTime']);
			array_push($sessiongroup, $sesh);
			$numgroup = 0;
			for($i = 0; $i < count($existingbookings); $i++) {
				$plusone = strtotime($existingbookings[$i]['StartTime']) + 3600;
				$nextone = strtotime($existingbookings[$i+1]['StartTime']);
				if($plusone != $nextone) {
					if($numgroup == 0) $numgroup++;
					if($existingbookings[$i+1] == "") $nextone = strtotime($existingbookings[$i]['StartTime']);
					$sessiongroup[$numgroup]['start']= date("H:i:s", $nextone);
				} else {
					$nn = $i;
					$plusonee=$plusone;
					$nextonee=$nextone;
					while($plusonee == $nextonee) {
						$nextone=$nextonee;
						$plusonee = strtotime($existingbookings[$nn+1]['StartTime']) + 3600;
						$nextonee = strtotime($existingbookings[$nn+2]['StartTime']);
						$nn++;
					}
					$sessiongroup[$numgroup]['end']= date("H:i:s", $nextone);
					if($existingbookings[$nn+2] != "")
						$sessiongroup[$numgroup+1]['start']= date("H:i:s", $nextonee);
					$i = $nn;
					$numgroup++;
				}
			}
			$BookingDetailNo = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$datearray[$x]}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}'", "", "s.StartTime LIMIT 1", "object")->BookingDetailNo;
			
			// Clean up transpotation; block one hour before and one hour after for tutor transportation
			$transponeeded = array();
			for($n = 0; $n < count($sessiongroup); $n++) {
				$transponeeded = $this->blockTranspo($BookingDetailNo, $datearray[$x], $sessiongroup[$n]['start'], "-", $transponeeded, "pending");
				if($sessiongroup[$n]['end'] == "")
					$transponeeded = $this->blockTranspo($BookingDetailNo, $datearray[$x], $sessiongroup[$n]['start'], "+", $transponeeded, "pending");
				else
					$transponeeded = $this->blockTranspo($BookingDetailNo, $datearray[$x], $sessiongroup[$n]['end'], "+", $transponeeded, "pending");
			}
			$transpocurrent = $this->misc_model->getM("s.StartTime", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND s.Date='{$datearray[$x]}' AND s.Type='transportation'", "", "s.StartTime", "array");
			$transpocurrent_array = array();
			foreach($transpocurrent as $et):
				array_push($transpocurrent_array, $et['StartTime']);
			endforeach;
			$transpotoremove = array();
			$transpotoremove = array_diff($transpocurrent_array, $transponeeded);
			foreach($transpotoremove as $t) {
				$this->misc_model->deleteM("booking_schedule", "Date = '{$datearray[$x]}' AND StartTime = '{$t}' AND Type='transportation'");
			}
		}
		header("location:".profile."bookings");
	}
	
	function thankyou()
	{
		$lala = $this->misc_model->getM("*", "users", "", "UserNo = '1'", "", "", "object");
		echo "{$lala->NickName} - {$lala->Address} - {$lala->AddressLandmark})";
		
		$this->loadView('header', $data);
		$this->loadView('book-thankyou_view', $data);
	}

	/** INTERNAL FUNCTIONS **/
	
	function changeDay($a)
	{
		$a = explode('+', $a);
		$month = $a['0'];
		$year = $a['1'];
		$day = $a['2'];
		$slots_per_day = $a['3'];
		$slots_booked = $a['4'];
		$TeacherNo = $a['5'];
		$cost_per_slot = $a['6'];
		$page = $a['7'];
		$pages = explode('_', $page);
		$page = $pages['0'];
		$ChildNo = $pages['1'];
		$BookingDate = $pages['2'];
		$SubjectNo = $pages['3'];
				
		$userno = $_SESSION['userno'];
		
		$current_day = "{$year}-{$month}-{$day}";
		$day_order = $this->day_order;
		$is_slot_booked_today = 0;
		
		// Contains all timeslots (this teacher) blocked as "session" or "transportation"
		$blocked_time = $this->misc_model->getM("s.Date, s.StartTime, s.Type", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND s.Date='{$current_day}' AND (s.Type='transportation' OR s.Type='session')", "", "s.Date, s.StartTime", "array");
		
		if($blocked_time != "") {
			foreach($blocked_time as $e):
				$date = $e["Date"];
				$bookings_per_day[$date][] = $e["StartTime"];
				$bookings[] = array(
					"date" => $date,
					"start" => $e["StartTime"]
				);
				if($date == "{$year}-{$month}-{$day}") {
					$is_slot_booked_today = 1;
				}
			endforeach;
		}
		
		// Contains all timeslots (this teacher) blocked by current user as "session"
		$existingbookings_user = $this->misc_model->getM("s.StartTime, s.Date, d.UserChildNo, d.SubjectNo, d.LevelNo", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.UserNo='{$userno}' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$current_day}' AND s.Type = 'session'", "", "s.StartTime", "array");
		
		// Contains all timeslots (all teachers) blocked by current user as "session"
		$existingbookings_user2 = $this->misc_model->getM("s.StartTime, s.Date, d.UserChildNo, d.SubjectNo, d.LevelNo, u.NickName, u.LastName", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo JOIN teachers t ON d.TeacherNo = t.TeacherNo JOIN users u ON t.UserNo = u.UserNo", "d.UserNo='{$userno}' AND d.TeacherNo <> '{$TeacherNo}' AND s.Date = '{$current_day}' AND s.Type = 'session'", "", "s.StartTime", "array");
		
		// Function array_diff for multidimensional arrays
		function multidimensional_array_diff2($a, $b) { 
			return $a['start'] - $b['start']; }
		
		if($page == "resched" && $BookingDate == "{$year}-{$month}-{$day}") {
			// Remove from bookings_per_day
			$bookings_per_day = $this->getBookingsPerDay($TeacherNo, "{$BookingDate}_{$ChildNo}_{$SubjectNo}");
			// Remove from bookings
			$resched = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}'", "", "s.StartTime", "array");
			
			$starttimearray = array();
			foreach($resched as $r):
				$starttimearray[] = array(
					"date" => $r['Date'],
					"start" => $r['StartTime']
				);
			endforeach;
			// Remove from bookings: slots to be rescheduled
			$bookings = array_udiff($bookings, $starttimearray, 'multidimensional_array_diff2'); 
		}
		
		$sessiongroup = array();
		$sesh = array("start" => $existingbookings_user[0][0]);
		array_push($sessiongroup, $sesh);
		$numgroup = 0;
		for($i = 0; $i < count($existingbookings_user); $i++) {
			$plusone = strtotime($existingbookings_user[$i][0]) + 3600;
			$nextone = strtotime($existingbookings_user[$i+1][0]);
			if($plusone != $nextone) {
				if($numgroup == 0) $numgroup++;
				if($existingbookings_user[$i+1] == "") $nextone = strtotime($existingbookings_user[$i]['StartTime']);
				$sessiongroup[$numgroup]['start']= date("H:i:s", $nextone);
			} else {
				$nn = $i;
				$plusonee=$plusone;
				$nextonee=$nextone;
				while($plusonee == $nextonee) {
					$nextone=$nextonee;
					$plusonee = strtotime($existingbookings_user[$nn+1][0]) + 3600;
					$nextonee = strtotime($existingbookings_user[$nn+2][0]);
					$nn++;
				}
				$sessiongroup[$numgroup]['end']= date("H:i:s", $nextone);
				if($existingbookings_user[$nn+2] != "")
					$sessiongroup[$numgroup+1]['start']= date("H:i:s", $nextonee);
				$i = $nn;
				$numgroup++;
			}
		}
			
		$checkday = date("D", strtotime($current_day));
		$dee2 = $this->getTeacherDaySched($TeacherNo, $checkday);
		
		if($bookings != "") {
			$freeup = array();
			for($n = 0; $n < count($sessiongroup); $n++) {
				$freeup = $this->freeUpBefore($freeup, $sessiongroup[$n]['start'], $dee2->StartTime, $dee2->EndTime, "{$year}-{$month}-{$day}");
				if($sessiongroup[$n]['end'] == "")
					$freeup = $this->freeUpAfter($freeup, $sessiongroup[$n]['end'], $dee2->StartTime, $dee2->EndTime, "{$year}-{$month}-{$day}");
				else
					$freeup = $this->freeUpAfter($freeup, $sessiongroup[$n]['end'], $dee2->StartTime, $dee2->EndTime, "{$year}-{$month}-{$day}");
			}
			// Remove from bookings: unnecessary transportation time
			$bookings = array_udiff($bookings, $freeup, 'multidimensional_array_diff2');
		}
		
		// Check booked slots for selected date and only show the booking form if there are available slots	
		$slots_selected_day = 0;
		if(isset($bookings_per_day[$current_day]))
			$slots_selected_day = count($bookings_per_day[$current_day]);
		
		foreach($day_order as $d):
			$dee = substr($d, 0, 3);
			$sched_{$dee} = $this->getTeacherDaySched($TeacherNo, $dee);
			switch($checkday) {
				case($dee):	$booking_start_time = strtotime($sched_{$dee}->StartTime);
							$booking_end_time	= strtotime($sched_{$dee}->EndTime) - 3600; break;
			}
		endforeach;
		
		// Print slot availability
		if($day != 0 && $slots_selected_day < $slots_per_day) {
			if($existingbookings_user != "") {
				echo "My bookings this day for the same tutor:<br>";
				foreach($existingbookings_user as $eb):
					$childname = $this->misc_model->getM("*", "user_children", "", "UserNo = '{$eb["UserChildNo"]}'", "", "", "object")->Nickname;
					$subjname = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$eb["SubjectNo"]}'", "", "", "object")->Subject;
					$levelname = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$eb["LevelNo"]}'", "", "", "object")->Level;
					echo "- {$eb['StartTime']} - {$childname} {$subjname} {$levelname}<br>";
				endforeach;
				echo "<br>"; }
			if($existingbookings_user2 != "") {			
				echo "My bookings this day for another tutor:<br>";
				foreach($existingbookings_user2 as $eb):
					$childname = $this->misc_model->getM("*", "user_children", "", "UserNo = '{$eb["UserChildNo"]}'", "", "", "object")->Nickname;
					$subjname = $this->misc_model->getM("*", "subjects", "", "SubjectNo = '{$eb["SubjectNo"]}'", "", "", "object")->Subject;
					$levelname = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$eb["LevelNo"]}'", "", "", "object")->Level;
					echo "{$eb['NickName']} {$eb['LastName']} - {$eb['StartTime']} - {$childname} {$subjname} {$levelname}<br>";
				endforeach;
				echo "<br>"; }
			echo "The following slots are available on <span> ".date_format(date_create($current_day), 'F j, Y')."</span>:<br><br>
			<table width='400' border='0' cellpadding='2' cellspacing='0' id='booking'>
			<tr><th width='270'>Start</th>
				<th width='150'>Price</th>
				<th width='20'>Book</th></tr>";
			for($i = $booking_start_time; $i<= $booking_end_time; $i = $i + $this->booking_frequency * 60) {
				$slots[] = date("H:i:s", $i); }
			
			// Loop through $bookings array and do not display any previously booked slots
			if($is_slot_booked_today == 1) { // $is_slot_booked_today created in function 'make_booking_array'
				foreach($bookings as $i => $b) {
					$slots = array_diff($slots, array($b['start']));
				}
			}
			
			foreach($slots as $i => $start) {		
				$finish_time = strtotime($start) + $booking_frequency * 60 + 3600;
				$slot = explode('%7C', $slots_booked);
				$checked = "no";
				
				// If rescheduling booking scheduled today, remove availability of other slots today
				$displayday = $current_day;
				if($page == "resched" && $displayday == $BookingDate && strtotime($BookingDate) == strtotime("now")) {
					for($ctr=0; $ctr < count($slot)-1; $ctr++) {
						if($displayday == substr($slot[$ctr], 0, -9) && $start == substr($slot[$ctr], 11)) $checked = "yes";
						else if($checked == "yes") {}
						else $checked = "no";
					} if($checked == "yes") {
					echo "<tr>\r\n
						<td>".date("g:i A", strtotime($start))." to ".date("g:i A", $finish_time)."</td>\r\n
						<td>Php ".number_format($cost_per_slot, 2)."</td>\r\n
						<td width='110'><input value='{$displayday}_{$start}-".date("H:i:s", $finish_time)."' class='fields' type='checkbox' onclick='updateSlots(value)' checked></td>
					</tr>"; }
				} else {
					for($ctr=0; $ctr < count($slot)-1; $ctr++) {
						if($displayday == substr($slot[$ctr], 0, -9) && $start == substr($slot[$ctr], 11)) $checked = "yes";
						else if($checked == "yes") {}
						else $checked = "no";
					}
					echo "<tr>\r\n
						<td>".date("g:i A", strtotime($start))." to ".date("g:i A", $finish_time)."</td>\r\n
						<td>Php ".number_format($cost_per_slot, 2)."</td>\r\n
						<td width='110'><input value='{$displayday}_{$start}-".date("H:i:s", $finish_time)."' class='fields' type='checkbox' onclick='updateSlots(value)' ";
						if($checked == "yes") echo "checked";
						echo "></td>
					</tr>";
				}
			}
			echo "</table>";
			
		} else if($day != 0) echo "Fully booked.";
	}
	
	function changeMonth($a)
	{
		$a = explode('+', $a);
		$month = $a['0'];
		$year = $a['1'];
		$TeacherNo = $a['2'];
		$cost_per_slot = $a['3'];
		$page = $a['4'];
		$pages = explode('_', $page);
		$page = $pages['0'];
		$ChildNo = $pages['1'];
		$BookingDate = $pages['2'];
		$SubjectNo = $pages['3'];
		
		// Calendar References
		$selected_date = mktime(0, 0, 0, $month, 01, $year); 
		$back_month = date("m", strtotime("-1 month", $selected_date));
		$back_year = date("Y", strtotime("-1 month", $selected_date));
		$forward_month = date("m", strtotime("+1 month", $selected_date));
		$forward_year = date("Y", strtotime("+1 month", $selected_date));
		$day_order = $this->day_order;
		$days = $this->getCalendarDays($month, $year);
		$day_closed = $this->getTeacherClosedDays($TeacherNo);
		if($page == "resched") {
			$bookings_per_day = $this->getBookingsPerDay($TeacherNo, "{$BookingDate}_{$ChildNo}_{$SubjectNo}");
		} else {
			$bookings_per_day = $this->getBookingsPerDay($TeacherNo, ""); }
		foreach($day_order as $d) {
			$dee = substr($d, 0, 3);
			$sched_{$dee} = $this->getTeacherDaySched($TeacherNo, $dee); }
		
		// Print new calendar
		echo "<table border='0' cellpadding='0' cellspacing='0' id='calendar'>
		<tr id='week'>
			<td align='left'><input type='button' value='&laquo' ";
			if($page == "booking") {
				echo "onclick='changeMonth({$back_month}, {$back_year}, {$TeacherNo}, {$cost_per_slot}, \"booking\")'";
			} else if($page == "resched") {
				echo "onclick='changeMonth({$back_month}, {$back_year}, {$TeacherNo}, {$cost_per_slot}, \"resched_{$ChildNo}_{$BookingDate}_{$SubjectNo}\")'";
			} echo " /></td>
			<td colspan='5' id='center_date'>".date("F Y", $selected_date)."</td>
			<td align='right'><input type='button' value='&raquo;' ";
			if($page == "booking") {
				echo "onclick='changeMonth({$forward_month}, {$forward_year}, {$TeacherNo}, {$cost_per_slot}, \"booking\")'";
			} else if($page == "resched") {
				echo "onclick='changeMonth({$forward_month}, {$forward_year}, {$TeacherNo}, {$cost_per_slot}, \"resched_{$ChildNo}_{$BookingDate}_{$SubjectNo}\")'";
			} echo " /></td></tr>
		<tr>";
			foreach($day_order as $r) {
				echo "<th>".substr($r, 0, 3)."</th>";
			} echo "</tr>
		<tr>";
			// Loop through the date array
			foreach($days as $i => $r) {
				$j = $i + 1; $tag = 0;	 		
				
				// CLOSED: If the the current day is found in the day_closed array, bookings are not allowed on this day  
				if(in_array($r['dayname'], $day_closed)) {			
					echo "\r\n<td width='21' valign='top' class='closed'>{$r['daynumber']}</td>";		
					$tag = 1; }
				
				// RESCHED-SAME DAY: If rescheduling during same day, show booking date
				if ($page == "resched") {
					if (date("Y-m-d", mktime(0, 0, 0, $month, sprintf("%02s", $r['daynumber']) + 0, $year)) == date("Y-m-d", strtotime($BookingDate)) && $tag != 1) {
						echo "\r\n<td width='21' valign='top'><input type='button' onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")' class='part_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1; }
				}
				
				// PAST: Past days are greyed out
				$startdate = strtotime("+2 day");
				if (mktime(0, 0, 0, $month, sprintf("%02s", $r['daynumber']) + 1, $year) < $startdate && $tag != 1) {		
					echo "\r\n<td width='21' valign='top' class='past'>";
						if($r['daynumber'] != 'blank') echo $r['daynumber'];
					echo "</td>";
					$tag = 1; }
				
				// BLANK: If the element is set as 'blank', insert blank day
				if($r['dayname'] == 'blank' && $tag != 1) {		
					echo "\r\n<td width='21' valign='top' class='unavailable'></td>";	
					$tag = 1; }
				
				// BOOKED: Now check the booking array $this->booking to see whether we have a booking on this day 				
				$current_day = $year.'-'.$month.'-'.sprintf("%02s", $r['daynumber']);
				$checkday = date("D", strtotime($current_day));
				foreach($day_order as $d):
					$dee = substr($d, 0, 3);
					switch($checkday) {
						case($dee):	$booking_start_time = strtotime($sched_{$dee}->StartTime);
									$booking_end_time	= strtotime($sched_{$dee}->EndTime) - 3600; break;
					}
				endforeach;
				$slots_per_day = 0;
				for($i = $booking_start_time; $i<= $booking_end_time; $i = $i + $this->booking_frequency * 60) {
					$slots_per_day ++;
				}
				
				if(isset($bookings_per_day[$current_day]) && $tag == 0) {
					$current_day_slots_booked = count($bookings_per_day[$current_day]);
					if($current_day_slots_booked < $slots_per_day) {
						echo "\r\n<td width='21' valign='top'><input type='button' ";
						if($page == "booking") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"booking\")'";
						} else if($page == "resched") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"resched_{$ChildNo}_{$BookingDate}_{$SubjectNo}\")'";
						} echo " class='part_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1;
					} else {
						echo "\r\n<td width='21' valign='top'><input type='button' ";
						if($page == "booking") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"booking\")'";
						} else if($page == "resched") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"resched_{$ChildNo}_{$BookingDate}_{$SubjectNo}\")'";
						} echo " class='fully_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1;
					}
				}
				if($tag == 0) {
					$daynumber = sprintf("%02s", $r['daynumber']);
					echo "\r\n<td width='21' valign='top'><input type='button' ";
					if($page == "booking") {
						echo "onclick='changeDay({$month}, {$year}, {$daynumber}, {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"booking\")'";
					} else if($page == "resched") {
						echo "onclick='changeDay({$month}, {$year}, {$daynumber}, {$slots_per_day}, {$TeacherNo}, {$cost_per_slot}, \"resched_{$ChildNo}_{$BookingDate}_{$SubjectNo}\")'";
					} echo " class='green' value='{$r['daynumber']}' />";
				}
				// The modulus function below ($j % 7 == 0) adds a <tr> tag to every seventh cell + 1;
				if($j % 7 == 0 && $i >1) {
					echo "\r\n</tr>\r\n<tr>"; // Use modulus to give us a <tr> after every seven <td> cells
				}
			}
		echo "</tr></table>";
	}
	
	function changeSlots($a)
	{
		$a = explode('+', $a);
		$amt = $a['1'];
		$a = explode('%7C', $a['0']);
		foreach($a as $exp) {
			$e = explode('_', $exp);
			if(strlen($exp) > 0) {
				$time = explode('-', $e['1']);
				echo date_format(date_create($e['0']), 'F j').": ".date("g:i A", strtotime($time['0']))." - ".date("g:i A", strtotime($time['1']))."<br>";
			}
		}
		echo "<br>
		<span id='currency'>Php&nbsp;</span>
		<span id='total'>".number_format($amt, 2)."</span>";
	}
	
	function blockTranspo($bookingno, $date, $time, $sign, $transpoarray, $status)
	{
		if($sign == "+")
			$changetime = strtotime($time) + 3600;	// Block hour after
		else $changetime = strtotime($time) - 3600;	// Block hour before
		$time = date('H:i:s', $changetime);
		array_push($transpoarray, $time);
		
		// Check if slot is already booked as "transporation"
		$check = $this->misc_model->getM("*", "booking_schedule s", "", "s.Type = 'transportation' AND s.Date = '{$date}' AND s.StartTime = '{$time}'", "", "", "array");
		if($check == "") {
			$this->misc_model->addM("booking_schedule", "BookingDetailNo, Date, StartTime, Type, BookingStatus, Timestamp", "'{$bookingno}', '{$date}', '{$time}', 'transportation', '{$status}', NOW()");
		} // else, do nothing since it's already blocked
		
		if($transpoarray != "")
			return $transpoarray;
	}
	
	function freeUpAfter($freeup, $time, $start, $end, $date)
	{
		$plusone = strtotime($time) + 3600;
		$plusonee = date('H:i:s', $plusone);
		$plustwo = strtotime($time) + (3600*2);
		$plustwoo = date('H:i:s', $plustwo);
		
		// Check if next hour is tagged as "transportation"
		$plusone_type = $this->misc_model->getM("Type", "booking_schedule", "", "StartTime = '{$plusonee}' AND Date = '{$date}' AND BookingStatus = 'confirmed' AND (Type='session' OR Type='transportation')", "", "", "object")->Type;
		
		// Get details of the hour after the "transportation" tag
		$plustwo_all = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.StartTime = '{$plustwoo}' AND s.Date = '{$date}' AND s.BookingStatus = 'confirmed' AND (s.Type='session' OR s.Type='transportation')", "", "", "object");
		
		$start = strtotime($start);
		$end = strtotime($end);
		if($plusone >= $start && $plusone <= $end && $plusone_type == "transportation") {
			$insert = array("date" => $date, "start" => date("H:i:s", $plusone));
			if($plustwo_all == "") {
				// No booking for the hour after the "transportation" tag
				array_push($freeup, $insert);
			} else if($plustwo_all->Type == "transportation") {
				// Hour after is also tagged as "transporation"
				array_push($freeup, $insert);
			} else if($plustwo_all->Type == "session" && $plustwo_all->UserNo == $_SESSION['userno']) {
				// Hour after is booked to same user
				array_push($freeup, $insert);
			} else {
				// Hour is not available
			}
		}
		return $freeup;
	}
	
	function freeUpBefore($freeup, $time, $start, $end, $date)
	{
		$minusone = strtotime($time) - 3600;
		$minusonee = date('H:i:s', $minusone);
		$minustwo = strtotime($time) - (3600*2);
		$minustwoo = date('H:i:s', $minustwo);
		
		// Check if previous hour is tagged as "transportation"
		$minusone_type = $this->misc_model->getM("Type", "booking_schedule", "", "StartTime = '{$minusonee}' AND Date = '{$date}' AND BookingStatus = 'confirmed' AND (Type='session' OR Type='transportation')", "", "", "object")->Type;
		
		// Get details of the hour before the "transportation" tag
		$minustwo_all = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.StartTime = '{$minustwoo}' AND s.Date = '{$date}' AND s.BookingStatus = 'confirmed' AND (s.Type='session' OR s.Type='transportation')", "", "", "object");
		
		$start = strtotime($start);
		$end = strtotime($end);
		if($minusone >= $start && $minusone <= $end && $minusone_type == "transportation") {
			$insert = array("date" => $date, "start" => date("H:i:s", $minusone));
			if($minustwo_all == "") {
				// No booking for the hour before the "transportation" tag
				array_push($freeup, $insert);
			} else if($minustwo_all->Type == "transportation") {
				// Hour before is also tagged as "transporation"
				array_push($freeup, $insert);
			} else if($minustwo_all->Type == "session" && $minustwo_all->UserNo == $_SESSION['userno']) {
				// Hour before is booked to same user
				array_push($freeup, $insert);
			} else {
				// Hour is not available
			}
		}
		return $freeup;
	}
	
	function getBookingsPerDay($TeacherNo, $a)
	{
		if($a != "") {
			$a = explode('_', $a);
			$BookingDate = $a['0'];
			$ChildNo = $a['1'];
			$SubjectNo = $a['2'];
			$existingbookings = $this->misc_model->getM("s.Date, s.StartTime", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND (s.Type='session' OR s.Type='transportation') AND (s.Date, s.StartTime) NOT IN ( SELECT s.Date, s.StartTime FROM booking_schedule s JOIN booking_details d ON s.BookingDetailNo = d.BookingDetailNo WHERE s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}')", "", "", "array");
		} else $existingbookings = $this->misc_model->getM("s.Date, s.StartTime", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "d.TeacherNo = '{$TeacherNo}' AND (s.Type='session' OR s.Type='transportation') AND (s.BookingStatus <> 'pending' OR s.BookingStatus <> 'cancelled')", "", "", "array");
		
		if($existingbookings != "") {
			foreach($existingbookings as $e):
				$date = $e["Date"];
				$bookings_per_day[$date][] = $e["StartTime"];
			endforeach;
		}
		return $bookings_per_day;
	}
	
	function getDays()
	{
		$day_order = $this->day_order;
		foreach($day_order as $d) {
			$dee = substr($d, 0, 3);
			$sched_{$dee} = $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}' AND Day = '{$dee}'", "", "", "object");
			if($sched_{$dee} == "")
				$day_closed[] = $d;
		}
		return $day_closed;
	}
	
	function getSlotsPerDay($current_day)
	{
		$day_order = $this->day_order;
		$TeacherNo = 3;
		$booking_frequency = 60;
		$checkday = date("D", strtotime($current_day));
		
		foreach($day_order as $d):
			$dee = substr($d, 0, 3);
			switch($checkday) {
				case($dee):	$booking_start_time = $this->getTeacherDaySched($TeacherNo, $dee)->StartTime;
							$booking_end_time	= $this->getTeacherDaySched($TeacherNo, $dee)->EndTime; break;
			}
		endforeach;
		
		$slots_per_day = 0;
		$booking_end_time = strtotime($booking_end_time) - 3600;
		for($i = strtotime($booking_start_time); $i<= $booking_end_time; $i = $i + $booking_frequency * 60) {
			$slots_per_day ++;
		}
		return $slots_per_day;
	}
	
	function getTeacherClosedDays($TeacherNo)
	{
		$day_order = $this->day_order;
		foreach($day_order as $d) {
			$dee = substr($d, 0, 3);
			$sched_dee = $this->getTeacherDaySched($TeacherNo, $dee);
			if($sched_dee == "")
				$day_closed[] = $d;
		}
		return $day_closed;
	}
	
	function getTeacherDaySched($TeacherNo, $dee)
	{
		return $this->misc_model->getM("*", "teacher_schedule", "", "TeacherNo = '{$TeacherNo}' AND Day = '{$dee}'", "", "", "object");
	}
	
	function getCalendarDays($month, $year)
	{
		$num_days_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		// Make $days array containing the Day Number and Day Number in the selected month
		for ($i = 1; $i <= $num_days_month; $i++) {
			// Work out the Day Name ( Monday, Tuesday... ) from the $month and $year variables
			$d = mktime(0, 0, 0, $month, $i, $year); 
			// Create the array
			$days[] = array("daynumber" => $i, "dayname" => date("l", $d));
		}
		
		// Add blanks (blank days) to the start and end of the array
		$first_day = $days[0]['dayname'];	$s = 0;
		foreach($this->day_order as $i => $r) {
			// Compare the $first_day to the Day Order
			if($first_day == $r && $s == 0) {
				$s = 1;  // Set flag to 1 stop further processing
			} elseif($s == 0) {
				$blank = array(
					"daynumber" => 'blank',
					"dayname" => 'blank'
				);
				array_unshift($days, $blank);
			}
		}
		$pad_end = 7 - (count($days) % 7);
		if ($pad_end < 7) {
			$blank = array(
				"daynumber" => 'blank',
				"dayname" => 'blank'
			);
			for ($i = 1; $i <= $pad_end; $i++) {							
				array_push($days, $blank);
			}
		}
		
		return $days;
	}
}
?>