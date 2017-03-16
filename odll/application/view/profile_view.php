<?php //include_once('./application/analyticstracking.php'); ?>

<div class="grid-100 tablet-grid-100 mobile-grid-100 flexwrapper-whole">
	<span class="hide-on-mobile"><div class="profile-tabs">
		<?php $url = $_SERVER['REQUEST_URI'];
		echo "<a href='".profile."me' class='";
				if(strpos($url, "/profile/me") !== false) echo "profile-tab-selected";
				else echo "profile-tab";
				echo "'>Account</a>
			<a href='".profile."bookmarks' class='";
				if(strpos($url, "/profile/bookmarks") !== false) echo "profile-tab-selected";
				else echo "profile-tab";
				echo "'>Bookmarked Tutors</a>
			<a href='".profile."bookings' class='";
				if(strpos($url, "/profile/bookings") !== false) echo "profile-tab-selected";
				else echo "profile-tab";
				echo "'>Appointments</a>
			<a href='".profile."settings' class='";
				if(strpos($url, "/profile/settings") !== false) echo "profile-tab-selected";
				else echo "profile-tab";
				echo "'>Settings</a>";
		?></div><br><br><br><br></span>
	<div class="static2-page">
		<h1>My Account</h1>
		
		<?php 
		//if($teacher != "") echo "<a href='".profile."mentor/{$teacher->TeacherNo}'>View My Tutor Profile</a><br><br>";
		function getSessionGroups($schedarray) {
			$sessiongroup = array();
			for($i = 0; $i < count($schedarray); $i++) {
				if($schedarray[$i]['Date'] == $schedarray[$i+1]['Date']
					&& $schedarray[$i]['UserChildNo'] == $schedarray[$i+1]['UserChildNo']
					&& $schedarray[$i]['BookingStatus'] == $schedarray[$i+1]['BookingStatus']
					&& $schedarray[$i]['TeacherNo'] == $schedarray[$i+1]['TeacherNo']
					&& $schedarray[$i]['SubjectNo'] == $schedarray[$i+1]['SubjectNo']) {
					$plusone = strtotime($schedarray[$i]['StartTime']) + 3600;
					$nextone = strtotime($schedarray[$i+1]['StartTime']);
					if($plusone == $nextone) {
						if($timestart == "")
							$timestart = $schedarray[$i]['StartTime'];
						else {
							$timeend = strtotime($schedarray[$i+1]['StartTime']) + 3600;
							$timeend = date("H:i:s", $timeend);
						}
					} else {
						$timeend = strtotime($schedarray[$i]['StartTime']) + 3600;
						$timeend = date("H:i:s", $timeend);
						$sesh = array("Date" => $schedarray[$i]['Date'],
							"UserChildNo" => $schedarray[$i]['UserChildNo'],
							"KidName" => $schedarray[$i]['KidName'],
							"TeacherNo" => $schedarray[$i]['TeacherNo'],
							"NickName" => $schedarray[$i]['NickName'],
							"SubjectNo" => $schedarray[$i]['SubjectNo'],
							"Subject" => $schedarray[$i]['Subject'],
							"Level" => $schedarray[$i]['Level'],
							"Status" => $schedarray[$i]['BookingStatus'],
							"TimeStart" => $timestart,
							"TimeEnd" => $timeend);
						array_push($sessiongroup, $sesh);
					}
				} else {
					if($timestart == "") {
						$timestart = $schedarray[$i]['StartTime'];
					}
					if($timeend == "") {
						$timeend = strtotime($schedarray[$i]['StartTime']) + 3600;
						$timeend = date("H:i:s", $timeend);
					}
					$sesh = array("Date" => $schedarray[$i]['Date'],
						"UserChildNo" => $schedarray[$i]['UserChildNo'],
						"KidName" => $schedarray[$i]['KidName'],
						"TeacherNo" => $schedarray[$i]['TeacherNo'],
						"NickName" => $schedarray[$i]['NickName'],
						"SubjectNo" => $schedarray[$i]['SubjectNo'],
						"Subject" => $schedarray[$i]['Subject'],
						"Level" => $schedarray[$i]['Level'],
						"Status" => $schedarray[$i]['BookingStatus'],
						"TimeStart" => $timestart,
						"TimeEnd" => $timeend);
					array_push($sessiongroup, $sesh);
					$timestart = ""; $timeend = "";
				}
			}
			return $sessiongroup;
		}
		
		echo "Hi, {$user->NickName}! You have {$user->Points} credits (<a href=''>How to earn credits?</a>)<br><br>";
		
		if($forpayment != "") {
			echo "<b>FOR PAYMENT</b><br>";
			$sessiongroup = getSessionGroups($forpayment);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']}) <span class='red-i'>*Payment due on ".date('F j (D), g:i A', $new_timestamp).".</span></span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		
		if($reschedrequest != "") {
			echo "<b>RESCHEDULE REQUEST BY TUTOR</b><br>";
			$sessiongroup = getSessionGroups($reschedrequest);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})</span><br>
				<span class='margin-l'><a href=''>Confirm Reschedule</a></span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		
		if($upcoming != "" || $completed != "") {
			echo "<b>MY BOOKINGS</b><br><br>";
		}
		
		//For Payment (BookingStatus: pending_tutor / notpaid; PaymentStatus: [amttopay], 0)<br>
		//Confirmed (BookingStatus: confirmed; PaymentStatus: notpaid)<br>
		//Pending Tutor Confirmation (BookingStatus: pending / pending_tutor; PaymentStatus: 0 / paid)<br>
		//Booking status: forfeedback (incl. action button), completed<br>
		
		if($upcoming != "") {
			echo "<b>Upcoming Appointments</b>";
			$sessiongroup = getSessionGroups($upcoming);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})";
				if($t['Status'] == "pending" || $t['Status'] == "pending_tutor") echo "<span class='blue-i'> *Pending tutor confirmation</span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><a href='".profile."bookings'>View All</a>";
			echo "<br><br>";
		}
		
		if($completed != "") {
			echo "<b>Completed Appointments</b>";
			$sessiongroup = getSessionGroups($completed);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})</span>";
				if($t['Status'] == 'forfeedback')
					echo "<br><span class='margin-l'><a href='".book."feedback/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Provide Feedback</a></span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><a href='".profile."bookings/completed'>View All</a><br><br>";
		}
		
		//echo "<br><br>Other REMINDERS:<br>
		//- replies, inquiries about requested tutors?<br>
		
		?>
	</div>
</div>