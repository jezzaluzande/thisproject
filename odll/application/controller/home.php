<?php
class Home extends Application
{
	function __construct()
	{
		$this->loadModel('misc_model');
		$this->loadModel('teachers_model');
	}
	
	function index()
	{
		$data["subjects"] = $this->misc_model->getM("s.SubjectNo, s.Subject", "subjects s", "teacher_subject t ON s.SubjectNo=t.SubjectNo", "t.Status='active'", "s.SubjectNo", "s.Subject", "array");
		$data["levels"] = $this->misc_model->getM("*", "levels", "", "", "", "", "array");
		$data["cities"] = $this->misc_model->getM("*", "cities c", "teacher_city ts ON ts.CityNo = c.CityNo", "", "c.CityNo", "c.City", "array");
		$data["contact"] = $this->misc_model->getM("*", "odll_details", "", "Detail = 'Contact Us'", "", "", "object");
		
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$this->loadView('header', $data);
		//$this->loadView('home-mentors_view', $data);
		//$this->loadView('home-videos_view', $data);
		$this->loadView('home_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function underconstruction()
	{
		$this->loadView('header-underconstruction', $data);
		$this->loadView('home-underconstruction_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function privacy()
	{
		$this->loadView('header', $data);
		$this->loadView('home-privacy_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function fblogin()
	{
		session_start();
		/*require_once( '../odll/application/fbconfig.php' );
		//require_once($_SERVER['DOCUMENT_ROOT'].'/application/fbconfig.php');
		$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
		$loginUrl = $helper->getLoginUrl('http://localhost/odll/fbcallback.php', $permissions);
		header("location: ".$loginUrl);*/
		
		/* CLIENT SAMPLE */
		$_SESSION['userno'] = 1;
		$_SESSION['first_name'] = "OneClient";
		$_SESSION['last_name'] = "LastName";
		$_SESSION['gender'] = "female";
		$_SESSION['email'] = "useremail@email.com";
		$_SESSION['language'] = "English";
		$_SESSION['country'] = "Philippines";
		$_SESSION['age'] = 25;
		
		/* TUTOR SAMPLE */
		/*$_SESSION['userno'] = 3;
		$_SESSION['first_name'] = "ThreeTutor";
		$_SESSION['last_name'] = "LastName";
		$_SESSION['gender'] = "female";
		$_SESSION['email'] = "useremail@email.com";
		$_SESSION['language'] = "English";
		$_SESSION['country'] = "Philippines";
		$_SESSION['age'] = 25;
		*/
		header("location:".home."");
	}
	
	function addUser()
	{
		$fn = $_SESSION['first_name'];
		$ln = $_SESSION['last_name'];
		$nn = $_SESSION['first_name'];
		$g = $_SESSION['gender'];
		$e = $_SESSION['email'];
		$l = $_SESSION['language'];
		$c = $_SESSION['country'];
		$a = $_SESSION['age'];
		
		if($this->misc_model->getM("*", "users", "", "FBEmail = '{$e}'", "",  "", "object") == "") {
			$this->misc_model->addM("users", "FirstName, LastName, Nickname, Gender, FBEmail, Password, DateCreated, Status, Language, Country, Age", "'{$fn}', '{$ln}', '{$nn}', '{$g}', '{$e}', 'facebook', NOW(), 'active', '{$l}', '{$c}', '{$a}'");
			$_SESSION['userno'] = $this->misc_model->getM("*", "users", "", "FBEmail = '{$e}'", "", "object")->UserNo;
		} else $_SESSION['userno'] = $this->misc_model->getM("*", "users", "", "FBEmail = '{$e}'", "", "", "object")->UserNo;
		
		header("location:".home."");
	}
	
	function fblogout()
	{
		session_start(); 
		$fb = new Facebook\Facebook([
			'app_id' => '1771374356434820',
			'app_secret' => 'e8389c2b4bf7b69846d385db532bd434',
			'default_graph_version' => 'v2.5',
		]);
		session_destroy(); 
 		header("Location:".home);
	}
		
	function why()
	{
		$this->loadView('header', $data);
		$this->loadView('home-why_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function how()
	{
		
		$this->loadView('header', $data);
		$this->loadView('home-how_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function faq($page)
	{
		$data["page"] = $page;
		$data["faqs"] = $this->misc_model->getM("*", "faqs", "", "Status = 'active'", "", "", "array");
		$this->loadView('header', $data);
		$this->loadView('home-faq_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function apply()
	{
		$this->loadView('header', $data);
		$this->loadView('home-apply_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function applicationform()
	{
		$data["cities"] = $this->misc_model->getM("*", "cities", "", "", "", "", "array");
		
		$this->loadView('header', $data);
		$this->loadView('home-applicationform_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function contactus()
	{
		$userno = $_SESSION['userno'];
		$data["user"] = $this->misc_model->getM("*", "users", "", "UserNo = '{$userno}'", "", "", "object");
		
		$data["contact"] = $this->misc_model->getM("*", "odll_details", "", "Detail = 'Contact Us'", "", "", "object");
		$this->loadView('header', $data);
		$this->loadView('home-contact_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function search()
	{
		header("location:".home."results/{$_POST["home-select"]}_{$_POST["home-input"]}");
	}
	
	function results($a)
	{
		//Functions search and results are separated so when a page is refreshed, form resubmission won't appear
		$a = explode('_', $a);
		$data["level"] = $a['0']; // Numberical value
		$data["subject"] = str_replace("%20", " ", $a['1']);
		$data["results"] = $this->teachers_model->getSearchResultsM($data["level"], $data["subject"]);
		$data["level"] = $this->misc_model->getM("*", "levels", "", "LevelNo = '{$data["level"]}'", "", "", "object")->Level; // Change to Level Name
		$this->loadView('header', $data);
		$this->loadView('home-results_view', $data);
		//$this->loadView('footer', $data);
	}
	
	function subjects()
	{
		$data["subjects"] = $this->teachers_model->getActiveSubjectsM();
		$this->loadView('header', $data);
		$this->loadView('home-subjects_view', $data);
		//$this->loadView('footer', $data);
	}
}
?>