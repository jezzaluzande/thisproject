<?php
class Form_InquireTutor extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
	}
	
	function index()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "object");
		$userno = $data["user"]->UserNo;
		
		$message = str_replace("'", "&#039;", $_POST['message']);
		$mobile = str_replace("'", "&#039;", $_POST['mobile']);
		$now = date("j-M-Y H:i:s");
		
		$this->misc_model->addM("received_inquiries", "Inquiry, UserNo, Timestamp, TeacherNo, SubjectNo, LevelNo, CityNo", "'{$message}', '{$userno}', NOW(), '{$_POST['teacherno']}', '{$_POST['subjectno']}', '{$_POST['levelno']}', '{$_POST['cityno']}'");
		
		if($mobile != "") {
			$this->misc_model->updateM("users", "MobileNo='{$mobile}'", "UserNo", "{$userno}");
		}
				
		$subject = $this->misc_model->getM("*", "subjects", "", "Subject LIKE '%{$data["subject"]}%'", "", "object");
		$level = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$_POST['levelno']}'", "", "object")->Level;
		$city = $this->misc_model->getM("*", "cities", "", "CityNo = '{$_POST['cityno']}'", "", "object")->City;
		$teacher = $this->misc_model->getM("*", "teachers", "", "TeacherNo = '{$_POST['teacherno']}'", "", "object");
		
		$to = $this->misc_model->getM("*", "odll_details", "", "Detail = 'Contact Us'", "", "object")->Email;
		$subject = "TUTOR INQUIRY FROM ".$data["user"]->FirstName." ".$data["user"]->LastName." ON ".$now;
		$txt = wordwrap($message, 70);
$txt = "Name: {$data["user"]->FirstName} {$data["user"]->LastName} (User No. {$userno})
E-mail: {$data["user"]->Email}
Mobile: {$data["user"]->MobileNo}
Sent On: {$now}

Tutor: {$teacher->FirstName} '{$teacher->Nickname}' {$teacher->LastName}
Teacher No.: {$teacher->TeacherNo}
Subject: {$subject}
Level: {$level}
City:  {$city}
Searching For: {$txt}";
		//mail($to,$subject,$txt);
		
		echo '{
			"message": "Inquiry sent."
		}';
	}
}
?>