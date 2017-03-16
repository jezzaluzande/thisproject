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
		echo "<a href='".profile."bookings'>UPCOMING</a> | ";
		if($page == "completed") {
			echo "<b>COMPLETED</b> | 
			<a href='".profile."bookings/cancelled'>CANCELLED</a>";	
		} else if($page == "cancelled") {
			echo "<a href='".profile."bookings/completed'>COMPLETED</a> | 
			<b>CANCELLED</b>";
		} echo "<br>";
		
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
		
		if($bookings != "") {
			$sessiongroup = getSessionGroups($bookings);
			foreach($sessiongroup as $t):
				$hours = $t['TimeEnd'] - $t['TimeStart'];
				if($olddate != $t['Date'])
					echo "<br>".date('F j (D)', strtotime($t['Date']));
				echo "<br><span class='margin-l'>".date("g:i A", strtotime($t['TimeStart']))." to ".date("g:i A", strtotime($t['TimeEnd']))." - {$t['NickName']} | {$t['KidName']} ({$t['Level']} - {$t['Subject']})</span>";
				if($t['Status'] == 'forfeedback')
					echo "<br><span class='margin-l'><a href='".book."feedback/{$t['Date']}+{$t['TeacherNo']}+{$t['UserChildNo']}+{$t['SubjectNo']}'>Provide Feedback</a></span>";
				$olddate = $t['Date'];
			endforeach;
			echo "<br><br>";
		}
		
		?>
	</div>
</div>