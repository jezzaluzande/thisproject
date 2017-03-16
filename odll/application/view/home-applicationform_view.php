<?php //include_once('./application/analyticstracking.php'); ?>

<div class="flexwrapper-whole">
	<div class="static-page-header"><h1>Tutor Application Form</h1></div>
	<div class="static-page fadeIn">
		
		<form method="post" action-xhr="<?php echo submitapplication."" ?>" target="_top" class="appform-accordion">
		<amp-accordion>
		
		<section expanded><h3>Personal Details</h3><span class="appform-accordion-c">
			<div class="table-like">
				<div class="table-like-a"><label for="firstname">First Name *</label></div>
				<div><input type="text" id="firstname" name="firstname" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="middlename">Middle Name *</label></div>
				<div><input type="text" id="middlename" name="middlename" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="lastname">Last Name *</label></div>
				<div><input type="text" id="lastname" name="lastname" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="nickname">Nickname *</label></div>
				<div><input type="text" id="nickname" name="nickname" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="birthday">Date of Birth *</label></div>
				<div><input type="date" id="birthday" name="birthday" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="gender">Gender *</label></div>
				<div><input type="radio" name="gender" value="male" checked> Male 
					<input type="radio" name="gender" value="female"> Female</div>
			</div><div class="table-like">			
				<div class="table-like-a"><label for="mobileno">Mobile No. *</label></div>
				<div><input type="text" id="mobileno" name="mobileno" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="email">E-mail Address *</label></div>
				<div><input type="email" id="email" name="email" required /></div>
			</div><div class="table-like">			
				<div class="table-like-a"><label for="profession">Current Profession *</label></div>
				<div><input type="text" id="profession" name="profession" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="college">College Attended *</div>
				<div><input type="text" id="college" name="college" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="course">Course *</div>
				<div><input type="text" id="course" name="course" required /></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="collegeyear">Date Graduated</div>
				<div><input type="month" id="collegeyear" name="collegeyear" /></div>
			</div>
		</span></section>
		
		<section><h3>Tutorial Details</h3><span class="appform-accordion-c">
			<div class="table-like">
				<div class="table-like-a2"><label for="subjslevels">Subjects and Grade Levels *<p class="support">Please be specific with the subjects and grade levels you can teach.</span></label></div>
				<div><textarea id="subjslevels" name="subjslevels" required></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="rate">Rate per Hour *<p class="support">Must be in PHP and must already include all expenses. Provide details if rate varies.</span></label></div>
				<div><textarea id="rate" name="rate" required></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a"><label for="tutoredprev">Have You Tutored Previously? *</label></div>
				<div><select id="tutoredprev" name="tutoredprev"><option value="yes">Yes</option><option value="no">No</option></select></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="experience">Experience *<p class="support">Your teaching experience that may help clients in considering you.</span></label></div>
				<div><textarea id="experience" name="experience" required></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="style">Tutoring Style *<p class="support">Your tutoring style that may help clients in considering you.</span></label></div>
				<div><textarea id="style" name="style" required></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="cities">Cities *<p class="support">The tutorial session will be done in the client's home. Only select cities you are comfortable to go to and to teach in.</span></label></div>
				<div><?php foreach($cities as $c):
					echo "<input type='checkbox' id='cities' name='cities' value='{$c[CityNo]}'>{$c[City]}<br>";
					endforeach; ?></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="locationpref">Location Preferences<p class="support">Your specific preferences in the location (ie. Alabang) from city or cities selected above.</p></label></div>
				<div><textarea id="locationpref" name="locationpref"></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="availability">Availability Schedule *<p class="support">Your general availability schedule to conduct tutorial services (ie. MWF 2:00 to 6:00 PM)</span></label></div>
				<div><textarea id="availability" name="availability" required></textarea></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="holidays">Are you open to working on holidays? *</label></div>
				<div><select id="holidays" name="holidays"><option value="yes">Yes</option><option value="no">No</option></select></div>
			</div>
		</span></section>
		
		<section><h3>Last</h3><span class="appform-accordion-c">
			<div class="table-like">
				<div class="table-like-a2"><label for="learnodll">How did you learn about ODLL? *</div>
				<div><input type="text" id="learnodll" name="learnodll" required /></div>
			</div><div class="table-like">
				<div class="table-like-a2"><label for="comments">Comments<p class="support">Any other details you would like us to keep in mind.</span></label></div>
				<div><textarea id="comments" name="comments"></textarea></div>
			</div>
		</span></section>
		
		</amp-accordion>
		<center><input type="submit" value="Submit Form" class="odll-button" /></center>
		</form>
		
		<div class="hide-on-desktop hide-on-tablet"><br><center><a href="<?php echo home.""; ?>" class="header-link"><div class="read-more">Back to Home</div></a></center></div>
		
	</div>
</div>