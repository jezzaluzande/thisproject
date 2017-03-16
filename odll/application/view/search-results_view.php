<?php //include_once('./application/analyticstracking.php'); ?>

<div class="flexwrapper-whole">
	<span class='hide-on-desktop hide-on-tablet expandable-search'>
		<input id='expandable-toggle-search' class='expandable-toggle-search' type='checkbox'>
		<label for='expandable-toggle-search'>Search Condition</label>
		<div id='expandable-div-search'>
			<section>
				<?php echo "<center><form method='get' action='".search."' target='_top'>
				<input type='text' class='home-input-s' name='home-input' value='{$subject}' />
				<select class='home-select-s' name='home-select'>";
					foreach($levels as $l):
					echo "<option value='{$l[LevelNo]}'";
					if ($l[LevelNo] == $levelno) echo " selected";
					echo ">{$l['Level']}</option>";
					endforeach;
				echo "</select>
				<select class='home-select2-s' name='home-select2'>";
					foreach($cities as $c):
					echo "<option value='{$c[CityNo]}'";
					if ($c[CityNo] == $cityno) echo " selected";
					echo ">{$c['City']}</option>";
					endforeach;
				echo "</select>
				<input type='submit' class='home-submit-s' value='Find Mentor' />
				</form></center>"; ?>
			</section>
		</div>
	</span>
	
	<?php echo "<span class='hide-on-mobile searchcondition'><form method='get' action='".search."' target='_top'>
			<select class='home-input-s' name='home-input'>";
				echo "<option value='0'";
				if ($subjectno == 0) echo " selected";
				echo ">All Subjects</option>";
				foreach($subjects as $s):
				echo "<option value='{$s[SubjectNo]}'";
				if ($s[SubjectNo] == $subjectno) echo " selected";
				echo ">{$s['Subject']}</option>";
				endforeach;
			echo "</select>
			<select class='home-select-s' name='home-select'>";
				foreach($levels as $l):
				echo "<option value='{$l[LevelNo]}'";
				if ($l[LevelNo] == $levelno) echo " selected";
				echo ">{$l['Level']}</option>";
				endforeach;
			echo "</select>
			<select class='home-select2-s' name='home-select2'>";
				foreach($cities as $c):
				echo "<option value='{$c[CityNo]}'";
				if ($c[CityNo] == $cityno) echo " selected";
				echo ">{$c['City']}</option>";
				endforeach;
			echo "</select>
			<input type='submit' class='home-submit-s' value='Find Mentor' />
			</form></span><br>"; ?>
	
	<div class="static2-page"><?php
		
		if($results != "") {
			echo "<h1>".ucwords($subject)." ({$level}) tutor";
			if(count($results) > 1) echo "s";
			echo " in {$city}</h1>";
			
			foreach($results as $r):
				$age = date('Y-m-d') - $r['Birthday'];
				echo "
				<a href='".search."profile/{$levelno}+{$cityno}+{$subjectno}+{$r['TeacherNo']}' class='no-decor'>
				<div class='flexwrapper-results'>
				<span><amp-img
					src='".img3."{$r['Picture']}'
					class='picture-results' alt='odll-teacher-picture'
					media='(min-width: 601px)' width='100' height='100'
					></amp-img>
					<amp-img
					src='".img3."{$r['Picture']}'
					class='picture-results' alt='odll-teacher-picture'
					media='(max-width: 600px)' width='75' height='75'
					></amp-img></span>
				<span class='results-text'><label>{$r['Nickname']}</label>, Php ".number_format($r['TotalCost'], 2, '.', '')."<br>
					<span class='hide-on-desktop hide-on-tablet'>".mb_strimwidth($r['TutoringStyle'], 0, 140, "...")."</span>
					<span class='hide-on-mobile'>".mb_strimwidth($r['TutoringStyle'], 0, 180, "...")."</span></span>
				</div>
				</a>";
			endforeach;
		} else {
			/*onclick="javascript: form.action='<?php echo search."findteacher";*/
			echo "<h1>No available ".ucwords($subject)." ({$level}) tutor in {$city} at the moment.</h1>
			<div class='div-center'>";
			if($_SESSION['first_name'] == "")
				echo "Would you like us to look for a teacher for you?<br>
				You must be logged in to continue. [Log in link]";
				
			else { echo "
				<form method='post' id='noresults-form'
					action-xhr='https://localhost/odll/application/form/messagesent.php'
					target='_blank'
					class='noresults'>
				<label>I need a teacher for:</label><br>
				<input type='text' id='noresults-search' name='noresults-search' maxlength='90' required value='{$subject} - {$level}' /><br>";
				if($user->MobileNo == "")
					echo "<label>My mobile no. for faster updates:</label><br>
					<input type='text' id='noresults-mobile' name='noresults-mobile' maxlength='90' value='{$user->MobileNo}' placeholder='eg. +63 999 000 0000' /><br>";
				echo "<label>My Preferences:</label><br>
				<textarea id='noresults-message' name='noresults-message' placeholder='eg. interactive tutoring style, available on weekdays at 4:00 PM' rows='5' maxlength='500'></textarea><br>
				<center><input class='odll-button' type='submit' value='Find Me A Teacher' /></center>
				<div submit-success><template type='amp-mustache'>
					Success! Thanks {{name}} for trying the <code>amp-form</code> demo!</template></div>
				<div submit-error><template type='amp-mustache'>
					Error! Thanks {{name}} for trying the <code>amp-form</code> demo with an error response.</template></div>
				</form>";
			}
			echo "</div>";
		} ?>
		
	</div>
</div>