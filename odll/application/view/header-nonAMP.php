<?php session_start(); ?>
<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$host = 'localhost';
$user = 'jezzaluzande';
$password = 'abcd1234';
$db = 'odll';

$link = mysqli_connect($host, $user, $password);
mysqli_select_db($link, $db) or die(mysql_error());

//include('connect.php'); 
include('class_calendar.php');

$calendar = new booking_diary($link);

if(isset($_GET['month'])) $month = $_GET['month']; else $month = date("m");
if(isset($_GET['year'])) $year = $_GET['year']; else $year = date("Y");
if(isset($_GET['day'])) $day = $_GET['day']; else $day = 0;

// Unix Timestamp of the date a user has clicked on
$selected_date = mktime(0, 0, 0, $month, 01, $year); 

// Unix Timestamp of the previous month which is used to give the back arrow the correct month and year 
$back = strtotime("-1 month", $selected_date); 

// Unix Timestamp of the next month which is used to give the forward arrow the correct month and year 
$forward = strtotime("+1 month", $selected_date);

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>ODLL: On-Demand Leisure Learning</title>
		<!--<link rel="canonical" href="https://www.odllearning.com" />-->
		<link rel="canonical" href="https://localhost/odll" />
		<meta name="viewport" content="width=device-width,minimum-scale=1" />
		<meta name="description" content="Book a tutor and schedule the learning session in your home, at your leisure." />
		<!-- <meta http-equiv="Cache-Control" content="no-cache" />
		<META http-equiv="Pragma" content="no-cache">
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="window-target" content="_top"> -->
		
		<meta property="og:image" content="<?php echo img."ODLL-Tutor-1.jpg" ?>" />
		<meta property="og:url" content="https://www.odllearning.com" />
		<meta property="og:title" content="ODLL: On-Demand Leisure Learning" />
		<meta property="og:description" content="Book a tutor and schedule the learning session in your home, at your leisure." />
		<meta property="fb:app_id" content="1771374356434820" />
		
		<link href="https://fonts.googleapis.com/css?family=Kadwa" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
		
		
		<div class="flexwrapper-header">
			
			<div class=""><a href="<?php echo home.""; ?>" class="header-logo font15em">ODLL</a></div>
			<div class="hide-on-mobile"><center>
				<?php
				$url = $_SERVER['REQUEST_URI'];
				echo "
				<a href='".home."how' class='";
					if ($url == "/odll/home/how") echo "header-link-selected";
					else echo "header-link";
					echo "'>How It Works</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."faq' class='";
					if ($url == "/odll/home/faq") echo "header-link-selected";
					else echo "header-link";
					echo "'>FAQ</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."apply' class='";
					if ($url == "/odll/home/apply") echo "header-link-selected";
					else echo "header-link";
					echo "'>Join</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."contactus' class='";
					if ($url == "/odll/home/contactus") echo "header-link-selected";
					else echo "header-link";
					echo "'>Contact Us</a>";
				?></center></div>
			<div class="hide-on-mobile header-signup-wrapper">
				<?php
				if ($_SESSION['first_name'] != "") echo "<a href='".profile."me/{$_SESSION['userno']}' class='header-link'>Hi ".$_SESSION['first_name']."</a> [<a href='".home."fblogout' class='header-link'>Log Out</a>] ";
				else echo "<a href='".home."fblogin' class='header-link'>Log In</a>";
				/* <amp-img src='".img."odll-facebook-login2.png' width='90' alt='odll-facebook-login'></amp-img> */
				?></div>
		</div>
		
		<link href="style.css" rel="stylesheet" type="text/css">

<link href="http://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

<script type="text/javascript">

var check_array = [];

$(document).ready(function(){

	$(".fields").click(function(){
	
		dataval = $(this).data('val');
	
		// Show the Selected Slots box if someone selects a slot
		if($("#outer_basket").css("display") == 'none') { 
			$("#outer_basket").css("display", "block");
		}

		if(jQuery.inArray(dataval, check_array) == -1) {
			check_array.push(dataval);
		} else {
			// Remove clicked value from the array
			check_array.splice($.inArray(dataval, check_array) ,1);	
		}
		
		slots=''; hidden=''; basket = 0;
		
		cost_per_slot = $("#cost_per_slot").val();
		//cost_per_slot = parseFloat(cost_per_slot).toFixed(2)

		for (i=0; i< check_array.length; i++) {
			slots += check_array[i] + '\r\n';
			hidden += check_array[i].substring(0, 8) + '|';
			basket = (basket + parseFloat(cost_per_slot));
		}
		
		// Populate the Selected Slots section
		$("#selected_slots").html(slots);
		
		// Update hidden slots_booked form element with booked slots
		$("#slots_booked").val(hidden);		

		// Update basket total box
		basket = basket.toFixed(2);
		$("#total").html(basket);	

		// Hide the basket section if a user un-checks all the slots
		if(check_array.length == 0)
		$("#outer_basket").css("display", "none");
		
	});
	
	
	$(".classname").click(function(){
	
		msg = '';
	
		if($("#name").val() == '')
		msg += 'Please enter a Name\r\n';

		if($("#email").val() == '')
		msg += 'Please enter an Email address\r\n';

		if($("#phone").val() == '')
		msg += 'Please enter a Phone number\r\n';	

		if(msg != '') {
			alert(msg);
			return false;
		}

	});

	// Firefox caches the checkbox state.  This resets all checkboxes on each page load 
	$('input:checkbox').removeAttr('checked');
	
});




</script>
	</head>
	<?php flush(); 
	echo "<body>
	<amp-sidebar id='mobile-sidebar' layout='nodisplay' class='mobile-sidebar hide-on-desktop hide-on-tablet'>
		<a href='".home."'"; if ($url == "/odll/" || $url == "/odll/home") echo " class='sidebar-link-selected'"; echo ">Home</a><br>
		<a href='".home."how'"; if (strpos($url, "/home/how") !== FALSE) echo " class='sidebar-link-selected'"; echo ">How It Works</a><br>
		<a href='".home."faq'"; if (strpos($url, "/home/faq") !== FALSE) echo " class='sidebar-link-selected'"; echo ">FAQ</a><br>
		<a href='".home."apply'"; if (strpos($url, "/home/apply") !== FALSE) echo " class='sidebar-link-selected'"; echo ">Join</a><br>
		<a href='".home."contactus'"; if (strpos($url, "/home/contactus") !== FALSE || strpos($url, "/home/messagesent") !== FALSE) echo " class='sidebar-link-selected'"; echo ">Contact Us</a><br>";
		if ($_SESSION['first_name'] != "") echo "<a href='".profile."me' class='header-link'>My Account</a><br>
			<a class='mobile-sidebar-margin' href='".profile."bookmarks'>Tutors</a><br>
			<a class='mobile-sidebar-margin' href='".profile."bookings'>Appointments</a><br>
			<a class='mobile-sidebar-margin' href='".profile."settings'>Settings</a><br>";
		else echo "<a href='".home."fblogin'>Log In</a>";
	echo "</amp-sidebar>
	?>"; /*<a href='#' on='tap:sidebar1.close'>Close</a>*/
	?>