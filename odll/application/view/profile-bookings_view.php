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
	<div class="static2-page mybookings">
		<h1>My Appointments</h1>
		
		<?php
		echo "<b>UPCOMING</b></b> | 
		<a href='".profile."bookings/completed'>COMPLETED</a> | 
		<a href='".profile."bookings/cancelled'>CANCELLED</a><br><br>";
		
		/**
		ARRAY
push - appends
pop - minus last one
shift - minus first one
unshift - puts at the start
diff - compares, returns unique of array1

TRACK CHANGES
resched
cancelled
paid
confirmed
**/
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
		
		if($today != "") {
			echo "<label>TODAY</label>";
			$sessiongroup = getSessionGroups($today);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})";
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				if($t['Status'] == "pending" || $t['Status'] == "pending_tutor") echo "<span class='blue-i'> *Pending tutor confirmation</span>";
				if($t['Status'] == "notpaid") echo "<span class='red-i'> *Payment due on ".date('F j (D), g:i A', $new_timestamp).".</span>";
				echo "<br><span class='margin-l'><a href='".book."reschedule/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Reschedule</a> | <label for='settings-cancelb' class='settings-button' id='settings-button-cancelb'>Cancel</label></span>
				<input type='checkbox' id='settings-cancelb'>";
				if($hours > 1) {
					echo "<form method='get' action='".form_cancelbooking."' target='_top' class='settings-cancel-hide'>
					<input type='hidden' name='CancelDate' value='{$t['Date']}' />
					<input type='hidden' name='CancelTeacherNo' value='{$t['TeacherNo']}' />
					<input type='hidden' name='CancelUserChildNo' value='{$t['UserChildNo']}' />
					<input type='hidden' name='CancelSubjectNo' value='{$t['SubjectNo']}' />";
					for($ctr = 0; $ctr < $hours; $ctr++) {
						$time = strtotime($t['TimeStart']) + (3600*$ctr);
						$timeplusone = strtotime(date("H:i:s", $time)) + 3600;
						echo "<input type='checkbox' name='TimeStart[]' value='".date("H:i:s", $time)."' checked /> ".date("g:i A", $time)." - ".date("g:i A", $timeplusone)."<br>";
					}
					echo "<input type='submit' value='Confirm Cancellation' class='' />
					<label for='settings-cancelb' class='settings-button' id='settings-button-cancel'>Cancel</label>
					</form>";
				} else echo "<a href='".book."cancel/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}+{$t['TimeStart']}' class='settings-cancel-hide'>Confirm Cancellation</a>";
				echo "</span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		if($tomorrow != "") {
			echo "<label>TOMORROW</label>";
			$sessiongroup = getSessionGroups($tomorrow);
			foreach($sessiongroup as $t):
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})";
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				if($t['Status'] == "pending" || $t['Status'] == "pending_tutor") echo "<span class='blue-i'> *Pending tutor confirmation</span>";
				if($t['Status'] == "notpaid") echo "<span class='red-i'> *Payment due on ".date('F j (D), g:i A', $new_timestamp).".</span>";
				echo "<br><span class='margin-l'><a href='".book."reschedule/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Reschedule</a> | <label for='settings-cancelb' class='settings-button' id='settings-button-cancelb'>Cancel</label></span>
				<input type='checkbox' id='settings-cancelb'>";
				if($hours > 1) {
					echo "<form method='get' action='".form_cancelbooking."' target='_top' class='settings-cancel-hide'>
					<input type='hidden' name='CancelDate' value='{$t['Date']}' />
					<input type='hidden' name='CancelTeacherNo' value='{$t['TeacherNo']}' />
					<input type='hidden' name='CancelUserChildNo' value='{$t['UserChildNo']}' />
					<input type='hidden' name='CancelSubjectNo' value='{$t['SubjectNo']}' />";
					for($ctr = 0; $ctr < $hours; $ctr++) {
						$time = strtotime($t['TimeStart']) + (3600*$ctr);
						$timeplusone = strtotime(date("H:i:s", $time)) + 3600;
						echo "<input type='checkbox' name='TimeStart[]' value='".date("H:i:s", $time)."' checked /> ".date("g:i A", $time)." - ".date("g:i A", $timeplusone)."<br>";
					}
					echo "<input type='submit' value='Confirm Cancellation' class='' />
					<label for='settings-cancelb' class='settings-button' id='settings-button-cancel'>Cancel</label>
					</form>";
				} else echo "<a href='".book."cancel/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}+{$t['TimeStart']}' class='settings-cancel-hide'>Confirm Cancellation</a>";
				echo "</span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		if($thisweek != "") {
			echo "<label>NEXT 7 DAYS</label>";
			$sessiongroup = getSessionGroups($thisweek);
			foreach($sessiongroup as $t):
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})";
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				if($t['Status'] == "pending" || $t['Status'] == "pending_tutor") echo "<span class='blue-i'> *Pending tutor confirmation</span>";
				if($t['Status'] == "notpaid") echo "<span class='red-i'> *Payment due on ".date('F j (D), g:i A', $new_timestamp).".</span>";
				echo "<br><span class='margin-l'><a href='".book."reschedule/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Reschedule</a> | <label for='settings-cancelb' class='settings-button' id='settings-button-cancelb'>Cancel</label></span>
				<input type='checkbox' id='settings-cancelb'>";
				if($hours > 1) {
					echo "<form method='get' action='".form_cancelbooking."' target='_top' class='settings-cancel-hide'>
					<input type='hidden' name='CancelDate' value='{$t['Date']}' />
					<input type='hidden' name='CancelTeacherNo' value='{$t['TeacherNo']}' />
					<input type='hidden' name='CancelUserChildNo' value='{$t['UserChildNo']}' />
					<input type='hidden' name='CancelSubjectNo' value='{$t['SubjectNo']}' />";
					for($ctr = 0; $ctr < $hours; $ctr++) {
						$time = strtotime($t['TimeStart']) + (3600*$ctr);
						$timeplusone = strtotime(date("H:i:s", $time)) + 3600;
						echo "<input type='checkbox' name='TimeStart[]' value='".date("H:i:s", $time)."' checked /> ".date("g:i A", $time)." - ".date("g:i A", $timeplusone)."<br>";
					}
					echo "<input type='submit' value='Confirm Cancellation' class='' />
					<label for='settings-cancelb' class='settings-button' id='settings-button-cancel'>Cancel</label>
					</form>";
				} else echo "<a href='".book."cancel/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}+{$t['TimeStart']}' class='settings-cancel-hide'>Confirm Cancellation</a>";
				echo "</span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		if($nextweek != "") {
			echo "<label>UPCOMING</label>";
			$sessiongroup = getSessionGroups($nextweek);
			foreach($sessiongroup as $t):
				if($olddate != $t['Date'])
					echo "<br>".date_format(date_create($t['Date']), 'F j (D)');
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})";
				$source_timestamp = strtotime($t['Date']." ".$t['TimeStart']);
				$new_timestamp = strtotime("-12 hour 00 minute", $source_timestamp);
				if($t['Status'] == "pending" || $t['Status'] == "pending_tutor") echo "<span class='blue-i'> *Pending tutor confirmation</span>";
				if($t['Status'] == "notpaid") echo "<span class='red-i'> *Payment due on ".date('F j (D), g:i A', $new_timestamp).".</span>";
				echo "<br><span class='margin-l'><a href='".book."reschedule/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Reschedule</a> | <label for='settings-cancelb' class='settings-button' id='settings-button-cancelb'>Cancel</label></span>
				<input type='checkbox' id='settings-cancelb'>";
				if($hours > 1) {
					echo "<form method='get' action='".form_cancelbooking."' target='_top' class='settings-cancel-hide'>
					<input type='hidden' name='CancelDate' value='{$t['Date']}' />
					<input type='hidden' name='CancelTeacherNo' value='{$t['TeacherNo']}' />
					<input type='hidden' name='CancelUserChildNo' value='{$t['UserChildNo']}' />
					<input type='hidden' name='CancelSubjectNo' value='{$t['SubjectNo']}' />";
					for($ctr = 0; $ctr < $hours; $ctr++) {
						$time = strtotime($t['TimeStart']) + (3600*$ctr);
						$timeplusone = strtotime(date("H:i:s", $time)) + 3600;
						echo "<input type='checkbox' name='TimeStart[]' value='".date("H:i:s", $time)."' checked /> ".date("g:i A", $time)." - ".date("g:i A", $timeplusone)."<br>";
					}
					echo "<input type='submit' value='Confirm Cancellation' class='' />
					<label for='settings-cancelb' class='settings-button' id='settings-button-cancel'>Cancel</label>
					</form>";
				} else echo "<a href='".book."cancel/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}+{$t['TimeStart']}' class='settings-cancel-hide'>Confirm Cancellation</a>";
				echo "</span>";
				$olddate = $t['Date'];
			endforeach;
			$olddate = "";
			echo "<br><br>";
		}
		
		?>
	</div>
</div>