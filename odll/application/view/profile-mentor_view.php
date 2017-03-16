<?php //include_once('./application/analyticstracking.php'); ?>

<div class="grid-100 tablet-grid-100 mobile-grid-100 flexwrapper-whole">
	<div class="static2-page">
		<span class="font15em"><b>Profile</b></span><br/>
		<div class="line-black">&nbsp;</div>
		
		<?php
		$age = date('Y-m-d') - $user->Birthday;
		echo "<amp-img src='".img3."{$teacher->Picture}' alt='odll-teacher-picture' class='picture-profile'></amp-img><br><br>
			{$user->NickName} {$user->LastName}<br>
			".ucwords($user->Gender).", {$age}, {$user->City}<br>
			{$teacher->Profession}<br><br>
			Teaches: ";
		
		$i = 1;
		foreach($subjects as $s):
			echo "{$s['Subject']}";
			if($i < count($subjects)) echo ", ";
			else echo "<br><br>";
			$i++;
		endforeach;
		
		echo "<b>Availability</b><br>{$teacher->Availability}<br>";
		
		$i = 1;
		foreach($cities as $c):
			echo "{$c['City']}";
			if($i < count($cities)) echo ", ";
			else echo "<br><br>";
			$i++;
		endforeach;
		
		echo "<b>Rate per Hour</b> - ";
		
		if($rate->minn != $rate->maxx) echo "PHP {$rate->minn} - {$rate->maxx}<br><br>";
		else echo "PHP {$rate->minn}<br><br>";
		
		if($referrals != "") {
			echo "<b>Referrals</b><br>";
			foreach($referrals as $r):
				echo "<i>\"{$r['Referral']}\"</i><br>
				- {$r['Client']}, ".date_format(date_create($r['ReferralDate']), 'Y-m-d')."<br><br>";
			endforeach;
		}
		
		echo "<b>Experience</b><br>{$teacher->Experience}<br><br>
		<b>Tutoring Style</b><br>{$teacher->TutoringStyle}<br><br>";
		
		if($ac != "") {
			echo "<b>Awards and Certifications</b><br><br>";
			foreach($ac as $ac):
				echo "{$ac['Title']}<br>";
				if($ac['Details'] != "") echo "{$ac['Details']}<br>";
				echo "- {$ac['AwardingBody']}, ";
				if($ac['Month'] != "") echo "{$ac['Month']} ";
				echo "{$ac['Year']}<br><br>";
			endforeach;
		}
		
		echo "<b>Educational Background</b><br><br>";
		foreach($schools as $s):
			echo "{$s['School']}<br>
			- {$s['Course']}, ";
			if($s['MonthGraduated'] != "") echo "{$s['MonthGraduated']} ";
			echo "{$s['YearGraduated']}<br><br>";
		endforeach;
		?>
		
	</div>
</div>