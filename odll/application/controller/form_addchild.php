<?php
class Form_AddChild extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
	}
	
	function index()
	{
		$_POST['c-nickname'] = str_replace("'", "&#039;", $_POST['c-nickname']);
		$_POST['c-notes'] = str_replace("'", "&#039;", $_POST['c-notes']);
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "object");
		
		$check = $this->misc_model->getM("*", "user_children", "", "Nickname = '{$_POST['c-nickname']}' AND Birthday = '{$_POST['c-birthday']}' AND Gender = '{$_POST['c-gender']}' AND UserNo = '{$userno}'", "", "array");
		
		if(count($check) > 0) {
			$message = "NOT ADDED: Duplicate entry for {$_POST['c-nickname']}.";
		} else {
			$this->misc_model->addM("user_children", "Nickname, Birthday, Gender, LevelNoC, LearningTypeNoC, ReasonNoC, Notes, UserNo, Active", "'{$_POST["c-nickname"]}', '{$_POST["c-birthday"]}', '{$_POST["c-gender"]}', {$_POST["c-level"]}, {$_POST["c-type"]}, {$_POST["c-reason"]}, '{$_POST["c-notes"]}', {$userno}, 'active'");
			$message = "Student '{$_POST['c-nickname']}' added.";
		}
		
		echo '{
			"message": "'.$message.'"
		}';
	}
}
?>