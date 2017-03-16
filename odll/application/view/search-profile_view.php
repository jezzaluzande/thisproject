<?php //include_once('./application/analyticstracking.php'); ?>

<div class="grid-100 tablet-grid-100 mobile-grid-100 flexwrapper-whole">
	<div class="static2-page">
		
		<?php
		if($bookmarked == "true") $star = "star-full";
		else $star = "star-empty";
		
		echo "<div class='flexwrapper-header-profile'>
			<a href='".search."results/{$levelno}+{$cityno}+{$subjectno}'><amp-img src='".icons."odll-arrow-left.png' alt='Back' height='45' width='45'></amp-img></a>
			<span class='profile'><h1>{$userteacher->NickName}</h1></span>
			<form method='post' action-xhr='".form_bookmark."' target='_top' class='hide-inputs'>
			<input type='submit' value='' class='{$star} hide-me' />
				<input type='hidden' name='levelno' value='{$levelno}' />
				<input type='hidden' name='cityno' value='{$cityno}' />
				<input type='hidden' name='subjectno' value='{$subjectno}' />
				<input type='hidden' name='teacherno' value='{$teacher->TeacherNo}' />
			<div submit-success><template type='amp-mustache'><input type='submit' value='' class='{{star}}' /></template></div>
		</form></div>";
		
		$age = date_diff(date_create($userteacher->Birthday), date_create('today'))->y;
		echo "<center>".ucwords($subject)." ({$level}) tutor in {$city}<br><br>
		<amp-img
			src='".img3."{$teacher->Picture}'
			class='picture-profile' alt='odll-teacher-picture'
			media='(min-width: 601px)' width='250' height='250'
			on='tap:lightbox1'
			role='button'
			tabindex='0'
			></amp-img>
		<amp-img
			src='".img3."{$teacher->Picture}'
			class='picture-profile' alt='odll-teacher-picture'
			media='(max-width: 600px)' width='160' height='160'
			on='tap:lightbox1'
			role='button'
			tabindex='0'
			></amp-img>
		<amp-image-lightbox id='lightbox1' layout='nodisplay'></amp-image-lightbox><br>
		".ucwords($userteacher->Gender).", {$age}, {$userteacher->City}<br>";
			
		if($teacher->Profession == "Full-Time Tutor") echo "Full-Time Tutor";
		else echo "Part-Time Tutor | {$teacher->Profession}";
		echo "</center><br>
		
		<table class='profile-details'>
		<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-creditcard.png' alt='Rate' height='35' width='35'></amp-img>
			<label>Rate per Hour</label>&nbsp;
			<span class='help-tip'><p>Rate is for ".ucwords($subject)." ({$level}) tutorial in {$city}, inclusive of transportation costs.</p></span></div>
			<p>Php ".number_format($rate->TotalCost, 2, '.', '')."</p>
			</td></tr>
		<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-books.png' alt='Subjects' height='35' width='35'></amp-img>
			<label>Teaches</label></div>
			<p>";
			$i = 1;
			foreach($subjects as $s):
				if($i != 1 && $i <= count($subjects)) echo ", ";
				echo "{$s['Subject']}";
				$i++;
			endforeach;
			echo "</p></td></tr>
		<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-calendar.png' alt='Calendar' height='35' width='35'></amp-img>
			<label>Availability</label></div>
			<p>";
			$i = 1;
			foreach($schedule as $s):
				if($i != 1 && $i <= count($schedule)) echo "<br>";
				echo "{$s['Day']}: ".date("g:i A", strtotime($s['StartTime']))." - ".date("g:i A", strtotime($s['EndTime']));
				$i++;
			endforeach;
			if($teacher->Holidays == "Yes")
				echo "<br>Holidays, upon client's request.";
			echo "<br></p></td></tr>";
			
		/** if($_SESSION['first_name'] != "")
			echo "BOOK";
		else echo "You need to be logged in. [Log in link]" **/
		echo "<tr><td>
			<form method='post' action-xhr='".form_inquiretutor."' target='_top'>
				<input type='checkbox' id='inquire-tutor'>
				<input type='hidden' name='levelno' value='{$levelno}' />
				<input type='hidden' name='cityno' value='{$cityno}' />
				<input type='hidden' name='subjectno' value='{$subjectno}' />
				<input type='hidden' name='teacherno' value='{$teacher->TeacherNo}' />
				<span class='profile-details-label inquire-tutor-label'><label>Send Inquiry</label><br></span>
				<label for='inquire-tutor' class='odll-button' id='inquire-tutor-show'>Inquire</label>
				<span class='inquire-tutor-div'>
					<textarea id='inquire-message' name='message' placeholder='What do you want to ask?' rows='5' maxlength='500'></textarea><br>";
					if($user->MobileNo == "")
						echo "<input type='text' id='mobile' name='inquire-mobile' maxlength='90' placeholder='Your Mobile No. (for faster contact!)' /><br>";
					echo "<br>
					<input type='submit' value='Send Inquiry' class='odll-button' />
					<label for='inquire-tutor' class='odll-button' id='inquire-tutor-hide'>Cancel</label>
				</span>
				<div submit-success><template type='amp-mustache'>{{message}}</template></div>
				<div submit-error><template type='amp-mustache'>{{message}}</template></div>
			<br><br></form>
			<form method='get' action='".book."' target='_top'>
				<input type='hidden' name='bookingreference' value='{$levelno}+{$cityno}+{$subjectno}+{$teacher->TeacherNo}' />
				<input type='hidden' name='month' value='".date("m")."' />
				<input type='hidden' name='year' value='".date("Y")."' />
				<input type='submit' class='odll-button' value='Book Now' />
			</form>
			</td></tr>";
		if($referrals != "") {
			echo "<tr><td><div class='profile-details-label'>
				<amp-img src='".icons."odll-referral.png' alt='Referral' height='35' width='35'></amp-img>
				<label>Reference</label></div><p>";
			foreach($referrals as $r):
				echo "<i>\"{$r['Referral']}\"</i><br>
				<span class='profile-details-indent'>- {$r['Client']}, ".date_format(date_create($r['ReferralDate']), 'F Y')."</span>";
			endforeach;
			echo "</p></td></tr>";
		}
		echo "<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-briefcase.png' alt='Experience' height='35' width='35'></amp-img>
			<label>Experience</label></div><p>{$teacher->Experience}</p>
			</td></tr>
		<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-clipboard.png' alt='Tutoring Style' height='35' width='35'></amp-img>
			<label>Tutoring Style</label></div><p>{$teacher->TutoringStyle}</p>
			</td></tr>";
		if($ac != "") {
			echo "<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-library.png' alt='Educational Background' height='35' width='35'></amp-img>
			<label>Awards and Certifications</label></div><p>";
			foreach($ac as $acc):
				echo "{$acc['Title']}<br>";
				if($acc['Details'] != "") echo "<span class='profile-details-indent'>{$acc['Details']}</span><br>";
				echo "<span class='profile-details-indent'>{$acc['AwardingBody']}, ";
				if($acc['Month'] != "") echo "{$acc['Month']} ";
				echo "{$acc['Year']}</span>";
			endforeach;
			echo "</p></td></tr>";
		}
		echo "<tr><td><div class='profile-details-label'>
			<amp-img src='".icons."odll-library.png' alt='Educational Background' height='35' width='35'></amp-img>
			<label>Educational Background</label></div><p>";
			foreach($schools as $s):
				echo "{$s['School']}<br>
				<span class='profile-details-indent'>{$s['Course']}, ";
				if($s['MonthGraduated'] != "") echo "{$s['MonthGraduated']} ";
				echo "{$s['YearGraduated']}</span>";		
			endforeach;
		echo "</td></tr>
		</table>";
		?>
		
		<br>
	</div>
</div>