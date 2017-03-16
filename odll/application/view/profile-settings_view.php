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
	<div class="static2-page settings">
		<h1>My Settings</h1>
		
		<?php
		if($teacher != "") echo "<a href='".profile."mentor/{$teacher->TeacherNo}'>View My Tutor Profile</a><br><br>";
		
		echo "<form method='post' action-xhr='".form_updateprofile."' target='_top' class='hide-inputs'>
		
		<span class='hide-me'>
		<div class='settings-buttons'>";
			if($page == "edit") echo "<input type='submit' value='Save Changes'>
				<a href='".profile."settings' class='settings-button'>Cancel</a>";
			else echo "<a href='".profile."settings/edit' class='settings-button'>Edit</a>"; echo "</div>
		
		<div class='settings-table'>
		<section>
			<div class='column'><label>First Name</label><br>";
				if($page == "edit") echo "<input type='text' name='firstname' value='{$user->FirstName}' maxlength='40' required />";
				else echo "{$user->FirstName}";
				echo "</div>
			<div class='column'><label>Last Name</label><br>";
				if($page == "edit") echo "<input type='text' name='lastname' value='{$user->LastName}' maxlength='40' required />";
				else echo "{$user->LastName}";
				echo "</div>
		</section>
		<section>
			<div class='column'><label>Nickname</label><br>";
				if($page == "edit") echo "<input type='text' name='nickname' value='{$user->NickName}' maxlength='40' required />";
				else echo "{$user->NickName}";
				echo "</div>
			<div class='column'><label>Birthday</label><br>";
				$editbirthday = date_format(date_create($user->Birthday), 'Y-m-d');
				if($page == "edit") echo "<input type='date' name='birthday' value='{$editbirthday}' />";
				else echo date_format(date_create($user->Birthday), 'F j, Y');
				echo "</div>
			<div class='column'><label>Gender</label><br>";
				if($page == "edit") {
					echo "<input type='radio' name='gender' value='male' ";
						if($user->Gender == "male") echo "checked";
					echo "/> Male <input type='radio' name='gender' value='female' ";
						if($user->Gender == "female") echo "checked";
					echo "/> Female";
				} else echo "{$user->Gender}";
				echo "</div>
		</section>
		<section>
			<div class='column'><label>Mobile</label><br>";
				if($page == "edit") echo "<input type='text' name='mobile' value='{$user->MobileNo}' maxlength='40' />";
				else echo "{$user->MobileNo}";
				echo "</div>
			<div class='column'><label>E-mail</label><br>";
				if($page == "edit") echo "<input type='email' name='email' value='{$user->Email}' maxlength='90' required />";
				else echo "{$user->Email}";
				echo "</div>
		</section>
		<section>
			<div class='column'><label>Alternate Mobile</label><br>";
				if($page == "edit") echo "<input type='text' name='mobile2' value='{$user->MobileNo2}' maxlength='40' />";
				else echo "{$user->MobileNo2}";
				echo "</div>
			<div class='column'><label>Alternate E-mail</label><br>";
				if($page == "edit") echo "<input type='email' name='email2' value='{$user->Email2}' maxlength='90' />";
				else echo "{$user->Email2}";
				echo "</div>
		</section>
		<section>
			<div class='column'><label>Address</label><br>";
				if($page == "edit") echo "<textarea name='address'>{$user->Address}</textarea>";
				else echo "{$user->Address}";
				echo "</div>
		</section>
		<section>
			<div class='column'><label>Landmark</label><br>";
				if($page == "edit") echo "<textarea name='landmark'>{$user->AddressLandmark}</textarea>";
				else echo "{$user->AddressLandmark}";
				echo "</div>
		</section>
		</div>
		</span>
		<div submit-success><template type='amp-mustache'>{{message}} <a href='".profile."settings'>Refresh Page</a>.</template></div>
		</form>
		
		<form method='post' action-xhr='".form_addchild."' target='_top'>
			<input type='checkbox' id='settings-add-c'><br><br>";
			
			if(count($children) < 1)
				echo "<center>
				Add here the students you are booking tutorial sessions for<br><br>
				<label for='settings-add-c' class='odll-button' id='settings-button-add'>Add Student</label>
				</center>";
			else {
				echo "No. of Students: ".count($children)."&nbsp;&nbsp;
				<label for='settings-add-c' class='settings-button' id='settings-button-add'>Add Student</label>";
			}
			
			echo "<div class='settings-add'>
			<section>
				<div class='column'><label class='label'>Nickname</label><br><input type='text' name='c-nickname' required /></div>
				<div class='column'><label class='label'>Birthday</label><br><input type='date' name='c-birthday' required /></div>
				<div class='column'><label class='label'>Gender</label><br>
					<input name='c-gender' type='radio' value='male' checked /> Male
					<input name='c-gender' type='radio' value='female' /> Female</div>
			</section><section>
				<div class='column'><label class='label'>Grade Level</label><br><select name='c-level'>";
					foreach($levels as $l):
						echo "<option value='{$l['LevelNo']}'>{$l['Level']}</option>";
					endforeach;
					echo "</select></div>
				<div class='column'><label class='label'>Reason for Tutorial</label><br><select name='c-reason'>";
					foreach($reasons as $r):
						echo "<option value='{$r['ReasonNo']}'>{$r['Reason']}</option>";
					endforeach;
					echo "</select></div>
				<div class='column'><label class='label'>Learning Type</label><br><select name='c-type'>";
					foreach($types as $t):
						echo "<option value='{$t['LearningTypeNo']}'>{$t['LearningType']}</option>";
					endforeach;
					echo "</select></div>
			</section><section>
				<div class='column'><label class='label'>Notes</label><br><textarea name='c-notes'></textarea></div>
			</section>
			<center><input type='submit' value='Add Student' />
				<label for='settings-add-c' class='settings-button' id='settings-button-cancel'>Cancel</label></center>
			</div>
			
			<div submit-success><template type='amp-mustache'>{{message}}</template></div>
			<div submit-error><template type='amp-mustache'>{{message}}</template></div>
		</form>";
		
		if(count($children) > 0) {
		foreach($children as $c):
			if($page == "editchild" && $childno == $c['UserChildNo']) {
				
				echo "<form method='post' action-xhr='".form_updatechild."' target='_top' class='hide-inputs'>
				<span class='hide-me'>
				<div class='settings-buttons'>
					<input type='submit' value='Save Changes' />
					<a href='".profile."settings' class='settings-button'>Cancel</a></div>
				
				<input type='hidden' name='userchildno' value='{$c['UserChildNo']}' />
				<div class='settings-table'>
				<section>
					<div class='column'><label>Nickname</label><br>
						<input type='text' name='nickname' value='{$c['Nickname']}' maxlength='40' /></div>
					<div class='column'><label>Birthday</label><br>";
						$editbirthday = date_format(date_create($c['Birthday']), 'Y-m-d');
						echo "<input type='date' name='birthday' value='{$editbirthday}' /></div>
					<div class='column'><label>Gender</label><br>
						<input type='radio' name='gender' value='male' ";
							if($c['Gender'] == "male") echo "checked";
						echo "/> Male <input type='radio' name='gender' value='female' ";
							if($c['Gender'] == "female") echo "checked";
						echo "/> Female</div>						
				</section>
				<section>
					<div class='column'><label>Grade Level</label><br>
						<select name='level'>";
						foreach($levels as $l):
							echo "<option value='{$l['LevelNo']}'";
								if($l['LevelNo'] == $c['LevelNoC']) echo " selected";
							echo ">{$l['Level']}</option>";
						endforeach;
						echo "</select></div>
					<div class='column'><label>Reason</label><br>
						<select name='reason'>";
						foreach($reasons as $r):
							echo "<option value='{$r['ReasonNo']}'";
								if($r['ReasonNo'] == $c['ReasonNoC']) echo " selected";
							echo ">{$r['Reason']}</option>";
						endforeach;
						echo "</select></div>
					<div class='column'><label>Learning Type</label><br>
						<select name='type'>";
						foreach($types as $t):
							echo "<option value='{$t['LearningTypeNo']}'";
								if($t['LearningTypeNo'] == $c['LearningTypeNoC']) echo " selected";
							echo ">{$t['LearningType']}</option>";
						endforeach;
						echo "</select></div>
				</section>
				<section>
					<div class='column'><label>Notes</label><br>
						<textarea name='notes'>{$c['Notes']}</textarea></div>
				</section>
				</div>
				</span>
				<div submit-success><template type='amp-mustache'>{{message}} <a href='".profile."settings'>Refresh Page</a>.</template></div>
				</form><br>";
			
			} else echo "
				
				<form method='get' action='".profile."' target='_top' class='settings-buttons'>
					<input type='checkbox' id='settings-delete-c'>
					<a href='".profile."settings/editchild-{$c['UserChildNo']}' class='settings-button'>Edit</a>
					<label for='settings-delete-c' class='settings-button' id='settings-button-delete'>Delete</label>
					<input type='submit' value='Confirm Delete' class='settings-delete-hide' />
					<label for='settings-delete-c' class='settings-button settings-delete-hide' id='settings-button-cancel'>Cancel</label>
					<input type='hidden' value='{$c['UserChildNo']}' name='childno' />
				</form>
				
				<div class='settings-table'>
				<section>
					<div class='column'><label>Nickname</label><br>
						{$c['Nickname']}</div>
					<div class='column'><label>Birthday</label><br>
						".date_format(date_create($c['Birthday']), 'F j, Y')."</div>
					<div class='column'><label>Gender</label><br>
						{$c['Gender']}</div>
				</section>
				<section>
					<div class='column'><label>Grade Level</label><br>
						{$c['Level']}</div>
					<div class='column'><label>Reason for Tutorial</label><br>
						{$c['Reason']}</div>
					<div class='column'><label>Learning Type</label><br>
						{$c['LearningType']}</div>
				</section>
				<section>
					<div class='column'><label>Notes</label><br>
						{$c['Notes']}</div>
				</section>
				</div><br>";
		endforeach;
		} ?>
		
	</div>
</div>