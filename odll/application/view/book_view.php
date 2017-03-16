<?php //include_once('./application/analyticstracking.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Calendar</title>
	<link type="text/css" rel="stylesheet" href="<?php echo css."booking-page.css" ?>"/>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Kadwa" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	
	<?php if($page == "booking") { ?>
	<script type="text/javascript">
		var check_array = [];
		window.onload = function showAddChild() {
			val = document.getElementById("childno").value
			if(val == 0) {
				document.getElementById("student_details").style.display="block";
			} else document.getElementById("student_details").style.display="none";
		}
	</script>
	<?php } else if($page == "reschedule") { ?>
	<script type="text/javascript">
		var check_array = <?php echo '["' . implode('", "', $js_slots) . '"]' ?>;
	</script>
	<?php } ?>
	
	<script type="text/javascript">
	function addChild(val) {
		if(val == 0) {
			document.getElementById("student_details").style.display="block";
		} else document.getElementById("student_details").style.display="none";
	}	
	
	function changeMonth(m, y, t, c, p) {
		if(m < 10)
			m = "0"+m;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest(); }
		else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("outer_calendar").innerHTML=xmlhttp.responseText; } }
		xmlhttp.open("GET","<?php echo book."changeMonth/" ?>"+m+"+"+y+"+"+t+"+"+c+"+"+p, true);
		xmlhttp.send();
	}
	
	function changeDay(m, y, d, s, t, c, p) {
		slots_booked = document.getElementById("slots_booked").value;
		if(m < 10)
			m = "0"+m;
		if(d < 10)
			d = "0"+d;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest(); }
		else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("outer_booking").innerHTML=xmlhttp.responseText; } }
		xmlhttp.open("GET","<?php echo book."changeDay/" ?>"+m+"+"+y+"+"+d+"+"+s+"+"+slots_booked+"+"+t+"+"+c+"+"+p, true);
		xmlhttp.send();
	}
	
	function updateSlots(dataval) {
		if(jQuery.inArray(dataval, check_array) == -1) {
			check_array.push(dataval);
		} else {
			// Remove clicked value from the array
			check_array.splice($.inArray(dataval, check_array) ,1);	
		}
		slots=''; hidden=''; basket = 0;
		
		cost_per_slot = $("#cost_per_slot").val();
		
		for (i=0; i< check_array.length; i++) {
			slots += check_array[i] + '|';
			hidden += check_array[i].substring(0, 19) + '|';
			basket = (basket + parseFloat(cost_per_slot));
		}
		
		// Update hidden slots_booked form element with booked slots
		$("#slots_booked").val(hidden);		
		
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest(); }
		else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("selected_slots").innerHTML=xmlhttp.responseText; } }
		xmlhttp.open("GET","<?php echo book."changeSlots/" ?>"+slots+"+"+basket, true);
		xmlhttp.send();
	}
	</script>
</head>
<body>
	<?php echo "<div id='booking-wrapper'>

	<div id='booking-left'>";
		if($page == "booking") {
			echo "<a href='".search."profile/{$levelno}+{$cityno}+{$subjectno}+{$teacher->TeacherNo}'>&laquo Back to {$userteacher->NickName}'s profile</a>";
		} else if ($page == "reschedule") {
			echo "<a href='".profile."bookings'>&laquo Back to my bookings</a>";
		}
		echo "<div id='tutor-details'>
			<label>Tutor Details</label><br><br>
			<img src='".img3."{$teacher->Picture}'
				class='picture-profile' alt='odll-teacher-picture'
				media='(max-width: 600px)' width='160' height='160' /><br>
			{$userteacher->NickName}<br><br>";
			$i = 1;
			foreach($schedule as $s):
				if($i != 1 && $i <= count($schedule)) echo "<br>";
				echo "{$s['Day']}: ".date("g:i A", strtotime($s['StartTime']))." - ".date("g:i A", strtotime($s['EndTime']));
				$i++;
			endforeach;
			if($teacher->Holidays == "Yes")
				echo "<br>Holidays, upon client's request.";
			echo "<br><br>
			Rate of <b>Php {$rate->TotalCost}</b> per hour for ".ucwords($subject)." ({$level}) tutorial in {$city}.
		</div>
	</div>
	
	<div id='booking-right'>
		<h1>Book ".ucwords($subject)." ({$level}) tutor in {$city}</h1>
		<div id='calendar-wrapper'>
		
		<div id='outer_calendar'>
		<table border='0' cellpadding='0' cellspacing='0' id='calendar'>
		<tr id='week'>
			<td align='left'>";
			if($page == "booking") {
				echo "<input type='button' value='&laquo' onclick='changeMonth({$back_month}, {$back_year}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"booking\")' />";
			} else if($page == "reschedule") {
				echo "<input type='button' value='&laquo' onclick='changeMonth({$back_month}, {$back_year}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")' />";
			}
			echo "</td>
			<td colspan='5' id='center_date'>".date("F Y", $selected_date)."</td>
			<td align='right'>";
			if($page == "booking") {
				echo "<input type='button' value='&raquo;' onclick='changeMonth({$forward_month}, {$forward_year}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"booking\")' />";
			} else if($page == "reschedule") {
				echo "<input type='button' value='&raquo;' onclick='changeMonth({$forward_month}, {$forward_year}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")' />";
			}
			echo" </td>
		</tr>
		<tr>";
			foreach($day_order as $r) {
				echo "<th>".substr($r, 0, 3)."</th>";
			}
		echo "</tr>
		<tr>";
			foreach($days as $i => $r) { // Loop through the date array
				$j = $i + 1; $tag = 0;	 		
				
				// If the the current day is found in the day_closed array, bookings are not allowed on this day  
				if(in_array($r['dayname'], $day_closed)) {			
					echo "\r\n<td width='21' valign='top' class='closed'>{$r['daynumber']}</td>";		
					$tag = 1; }
				
				// If rescheduling during same day, show booking date
				if ($page == "reschedule") {
					if (date("Y-m-d", mktime(0, 0, 0, $month, sprintf("%02s", $r['daynumber']) + 0, $year)) == date("Y-m-d", strtotime($bookingdate)) && $tag != 1) {
						echo "\r\n<td width='21' valign='top'><input type='button' onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")' class='part_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1; }
				}
				
				// Past days are greyed out
				$startdate = strtotime("+2 day");
				if (mktime(0, 0, 0, $month, sprintf("%02s", $r['daynumber']) + 1, $year) < $startdate && $tag != 1) {
					echo "\r\n<td width='21' valign='top' class='past'>";
						if($r['daynumber'] != 'blank') echo $r['daynumber'];
					echo "</td>";
					$tag = 1; }
				
				// If the element is set as 'blank', insert blank day
				if($r['dayname'] == 'blank' && $tag != 1) {		
					echo "\r\n<td width='21' valign='top' class='unavailable'></td>";	
					$tag = 1; }
				
				// Now check the booking array $this->booking to see whether we have a booking on this day 				
				$current_day = $year.'-'.$month.'-'.sprintf("%02s", $r['daynumber']);
				$checkday = date("D", strtotime($current_day));
				foreach($day_order as $d):
					$dee = substr($d, 0, 3);
					switch($dee) {
						case("Mon"): $dee2 = $Mon; break;
						case("Tue"): $dee2 = $Tue; break;
						case("Wed"): $dee2 = $Wed; break;
						case("Thu"): $dee2 = $Thu; break;
						case("Fri"): $dee2 = $Fri; break;
						case("Sat"): $dee2 = $Sat; break;
						case("Sun"): $dee2 = $Sun; break;
					}
					switch($checkday) {
						case($dee):	$booking_start_time = strtotime($dee2->StartTime);
									$booking_end_time	= strtotime($dee2->EndTime) - 3600; break;
					}
				endforeach;
					
				$slots_per_day = 0;
				for($i = $booking_start_time; $i<= $booking_end_time; $i = $i + $booking_frequency * 60) {
					$slots_per_day ++;
				}
				if(isset($bookings_per_day[$current_day]) && $tag == 0) {
					$current_day_slots_booked = count($bookings_per_day[$current_day]);
					
					if($current_day_slots_booked < $slots_per_day) {
						echo "\r\n<td width='21' valign='top'><input type='button' ";
						if($page == "booking") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"booking\")'";
						} else if($page == "reschedule") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")'";
						}
						echo " class='part_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1;
					} else {
						echo "\r\n<td width='21' valign='top'><input type='button' ";
						if($page == "booking") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"booking\")'";
						} else if($page == "reschedule") {
							echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")'";
						}
						echo " class='fully_booked' value='{$r['daynumber']}' /></td>";
						$tag = 1;
					}
				}
				if($tag == 0) {
					echo "\r\n<td width='21' valign='top'><input type='button' ";
					if($page == "booking") {
						echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"booking\")'";
					} else if($page == "reschedule") {
						echo "onclick='changeDay({$month}, {$year}, ".sprintf("%02s", $r['daynumber']).", {$slots_per_day}, {$teacher->TeacherNo}, {$rate->TotalCost}, \"resched_{$child->UserChildNo}_{$bookingdate}_{$subjectno}\")'";
					}
					echo " class='green' value='{$r['daynumber']}' /></td>";
				}
				// The modulus function below ($j % 7 == 0) adds a <tr> tag to every seventh cell + 1;
				if($j % 7 == 0 && $i >1) {
					echo "\r\n</tr>\r\n<tr>"; // Use modulus to give us a <tr> after every seven <td> cells
				}
			}
		echo "</tr></table></div>
		<div id='outer_legend'>
			<table cellpadding='0' cellspacing='5'>
			<tr><td colspan=2>Legend:</td></tr>
			<tr><td class='legend-green'></td>
				<td>Available</td></tr>
			<tr><td class='legend-part'></td>
				<td>Partly Booked</td></tr>
			<tr><td class='legend-unavailable'></td>
				<td>Not Available</td></tr>
			<tr><td class='legend-full'></td>
				<td>Fully Booked</td></tr>
			</table></div>
		</div>
		
		<label>Available Slots</label>
		<div id='outer_booking'>";
			if($page == "booking") echo	"Please select date.";
			else if($page == "reschedule") {
				$displayday = "{$year}-{$month}-{$day}";
				echo "The following slots are available on <span> ".date_format(date_create($displayday), 'F j, Y')."</span><br><br>
				<table width='400' border='0' cellpadding='2' cellspacing='0' id='booking'>
				<tr><th width='270'>Start</th>
					<th width='150'>Price</th>
					<th width='20'>Book</th></tr>";
				for($i = $booking_start_time_r; $i<= $booking_end_time_r; $i = $i + 3600) {
					$slots[] = date("H:i:s", $i);
				}
				// Loop through $bookings array and do not display any previously booked slots
				foreach($bookings as $i => $b) {
					// Remove any booked slots from the $slots array
					$slots = array_diff($slots, array($b['start']));
				}
				
				// Loop through the $slots array and create the booking table
				foreach($slots as $i => $start) {			
					$finish_time = strtotime($start) + 3600;
					$slot = explode('|', $slots_booked);
					$checked = "no";
					
					// If rescheduling booking scheduled today, remove availability of other slots today
					if($displayday == $bookingdate && strtotime($bookingdate) == strtotime("now")) {
						for($ctr=0; $ctr < count($slot)-1; $ctr++) {
							if($displayday == substr($slot[$ctr], 0, -9) && $start == substr($slot[$ctr], 11)) $checked = "yes";
							else if($checked == "yes") {}
							else $checked = "no";
						} if($checked == "yes") {
						echo "<tr>\r\n
							<td>".date("g:i A", strtotime($start))." to ".date("g:i A", $finish_time)."</td>\r\n
							<td>Php ".number_format($rate->TotalCost, 2)."</td>\r\n
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
							<td>Php ".number_format($rate->TotalCost, 2)."</td>\r\n
							<td width='110'><input value='{$displayday}_{$start}-".date("H:i:s", $finish_time)."' class='fields' type='checkbox' onclick='updateSlots(value)' ";
							if($checked == "yes") echo "checked";
							echo "></td>
						</tr>";
					}
				}
				echo "</table>";
			}
		echo "</div>
	
		<label>My Selection</label>
		<div id='outer_basket'>
			<div id='selected_slots'>";
			if($page == "booking") {	
				echo "Please select a schedule.<br><br>
				<span id='currency'>Php&nbsp;</span>
				<span id='total'>0.00</span>";
			} else if($page == "reschedule") {
				$a = explode('|', $slots_booked);
				foreach($a as $exp) {
					$e = explode('_', $exp);
					if(strlen($exp) > 0) {
						$time = explode('-', $e['1']);
						$finish_time = strtotime($time['0']) + 3600;
						echo date_format(date_create($e['0']), 'F j').": ".date("g:i A", strtotime($time['0']))." - ".date("g:i A", $finish_time)."<br>";
					}
				}
				$amt = $RatePerHour * (count($a)-1);
				echo "<br>
				<span id='currency'>Php&nbsp;</span>
				<span id='total'>".number_format($amt, 2)."</span>";
			}
			echo "</div>
		</div>
		
		<label>Session Details</label>";
			if($page == "booking") {
				echo "<form method='post' action='".book."submitBooking'>";
			} else if ($page == "reschedule") {
				echo "<form method='post' action='".book."submitResched'>";
			} echo "
		<div id='other_details'>";
			if($page == "booking") {
				echo "Tutorial for <select id='childno' name='childno' onchange='addChild(this.value)'>";
				foreach($children as $c):
					echo "<option value='{$c['UserChildNo']}'>{$c['Nickname']}</option>";
				endforeach;
				echo "<option value='0'>-- Add New --</option>
				</select><br>
				
				<fieldset id='student_details'>
					<legend>Student Details</legend>
					These details will help the tutor <br><br>
					Nickname <input type='text' name='nickname' maxlength='40' /><br>
					Gender <input type='radio' name='gender' value='male' checked /> Male
							<input type='radio' name='gender' value='female' /> Female<br>
					Birthday <input type='date' name='birthday' /><br>
					Grade Level {$level}<input type='hidden' name='level' value='{$levelno}'><br>
					Reason <select name='reason'>";
						foreach($reasons as $r):
							echo "<option value='{$r['ReasonNo']}'>{$r['Reason']}</option>";
						endforeach;
						echo "</select><br>
					Learning Type <select name='type'>";
						foreach($types as $t):
							echo "<option value='{$t['LearningTypeNo']}'>{$t['LearningType']}</option>";
						endforeach;
						echo "</select><br>
					Notes <textarea name='notes'></textarea>
				</fieldset>
				Address {$city} <textarea name='address' required>{$user->Address}</textarea><br>
				Landmark <textarea name='landmark'>{$user->AddressLandmark}</textarea>";
			} else if ($page == "reschedule") {
				echo "Tutorial for {$child->Nickname}<br>
				Address ({$city}) {$user->Address}<br>
				Landmark {$user->AddressLandmark}
				<input type='hidden' name='childno' value='{$child->UserChildNo}'>
				<input type='hidden' name='bookingdate' value='{$bookingdate}'>";
			} echo "
		</div>";
		
		if ($page == "reschedule" && $RescheduleFee != "") {
			echo "<label>Rescheduling Fee</label>
			<div id='other_details'>
			Administration Fee will be charged based on the following criteria:<br>
			- Php 50.00 per hour for changes in bookings that start in more than 12 hours.<br>
			- 50% of the rate per hour for changes in bookings that start in less than 12 hours.<br><br>
			You may be charged a <b>maximum of Php ".number_format($RescheduleFee, 2)."</b> for changes in the schedule.
			</div>";
		}
		
		echo "<div id='basket_details'>
			<input type='hidden' name='reschedfee' value='{$RescheduleFee}'>
			<input type='hidden' name='teacherno' value='{$teacher->TeacherNo}'>
			<input type='hidden' name='subjectno' value='{$subjectno}'>
			<input type='hidden' name='cityno' value='{$cityno}'>
			<input type='hidden' name='slots_booked' id='slots_booked' value='{$slots_booked}'>
			<input type='hidden' name='cost_per_slot' id='cost_per_slot' value='{$rate->TotalCost}'>";
			if($page == "booking") {
				echo "<input type='submit' value='Make Booking'>";
			} else if ($page == "reschedule") {
				echo "<input type='submit' value='Submit Reschedule'>";
			} echo "
		</div>
			
		</form>
		
	</div>
	</div>"; ?>
</body>
</html>