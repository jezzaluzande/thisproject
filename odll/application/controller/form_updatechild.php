<?php
class Form_UpdateChild extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
	}
	
	function index()
	{
		$_POST['nickname'] = str_replace("'", "&#039;", $_POST['nickname']);
		$_POST['notes'] = str_replace("'", "&#039;", $_POST['notes']);
		
		$this->misc_model->updateM("user_children", "Nickname='{$_POST["nickname"]}', Birthday='{$_POST["birthday"]}', Gender='{$_POST["gender"]}', LevelNoC={$_POST["level"]}, LearningTypeNoC={$_POST["type"]}, ReasonNoC='{$_POST["reason"]}', Notes='{$_POST["notes"]}'", "UserChildNo", "{$_POST['userchildno']}");
		
		echo '{
			"message": "Student Profile Updated."
		}';
	}
}
?>