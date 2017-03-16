<?php
class Form_CancelBooking extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
	}
	
	function index()
	{
		$BookingDate = $_GET["CancelDate"];
		$TeacherNo = $_GET["CancelTeacherNo"];
		$ChildNo = $_GET["CancelUserChildNo"];
		$SubjectNo = $_GET["CancelSubjectNo"];
		$TimeStart = $_GET["TimeStart"];
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		foreach ($TimeStart as $t) {
			$BookingScheduleNo = $this->misc_model->getM("*", "booking_schedule s", "booking_details d ON s.BookingDetailNo = d.BookingDetailNo", "s.Type = 'session' AND d.TeacherNo = '{$TeacherNo}' AND s.Date = '{$BookingDate}' AND d.UserChildNo = '{$ChildNo}' AND d.SubjectNo = '{$SubjectNo}' AND s.StartTime = '{$t}'", "", "", "object")->BookingScheduleNo;
			$this->misc_model->updateM("booking_schedule", "Type = 'cancelled'", "BookingScheduleNo", "{$BookingScheduleNo}");
			$this->misc_model->addM("booking_changes", "BookingScheduleNo, ChangeDone, ChangedBy, Timestamp", "'{$BookingScheduleNo}', 'cancelled', 'client', NOW()");
		}
		
		header("location:".profile."bookings");
	}
}
?>