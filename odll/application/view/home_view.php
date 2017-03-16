<?php //include_once('./application/analyticstracking.php');
session_start(); ?>

<div class="home fadeIn flexwrapper-top" id="home-mentors-top">
	<form method="get" action="<?php echo search.""; ?>" target="_top">
		<div class="home-text">
			<b>Help your child excel.</b><br/>
			Connect with proven tutors.</div>
		<select class="home-input" name="home-input">
			<?php
			echo "<option value='0'>All Subjects</option>";
			foreach($subjects as $s):
			echo "<option value='{$s[SubjectNo]}'>{$s['Subject']}</option>";
			endforeach;
			?>
		</select>
		<select class="home-select" name="home-select">
			<?php
			foreach($levels as $l):
			echo "<option value='{$l[LevelNo]}'>{$l['Level']}</option>";
			endforeach;
			?>
		</select>
		<select class="home-select2" name="home-select2">
			<?php
			if ($_SESSION['first_name'] != "") $UserCity = $user->CityNo;
			else $UserCity = 0;
			foreach($cities as $c):
			echo "<option value='{$c[CityNo]}'";
			if($UserCity == $c[CityNo]) echo " selected";
			echo ">{$c['City']}</option>";
			endforeach;
			?>
		</select>
		<input type="submit" class="home-submit" value="Find Mentor" />
	</form>
	<span class="hide-on-mobile hide-on-tablet"><br><br><br><br><br></span>
	<br><br><br>
</div>

<div class="flexwrapper-bottom" id="home-learnmore">
	<div class="flexwrapper-bottom-c">
		<div class="home-box">
			<amp-img src="<?php echo icons."odll-house.png" ?>" alt="ODLL" height="70" width="70"></amp-img><br><br>
			<span class="font15em"><b>What is ODLL?</b></span><br><br>
			On-Demand Leisure Learning (ODLL) is a platform to connect Students and Tutors in the same vicinity.<br><br>We support parents who need help in boosting their child's knowledge. We also encourage individuals to share their passion and time in mentoring younger Students.<br><br>
			<span class="hide-on-desktop hide-on-tablet">
				<span class="font15em"><b>How It Works</b></span><br><br>
				1. Book a Tutor through the ODLL website.<br>
				2. Receive Booking Confirmation from the ODLL Team.<br>
				3. Pay online! We will send you a Paypal link for easy payment.<br>
				4. Tutor travels to your home or your selected venue for the Learning Session.<br>
				5. Rate the Learning Experience!</span>
			<span class="hide-on-mobile">
				<center><a href="<?php echo home."how"; ?>" class="no-decor">
					<div class="read-more">Read More&nbsp;&nbsp;></div></a></center></span></div>
		<div class="home-box">
			<amp-img src="<?php echo icons."odll-person2.png" ?>" alt="Tutors" height="70" width="70"></amp-img><br><br>
			<span class="font15em"><b>For Tutors</b></span><br><br>
			You only work when you want to and where you want to! Aside from the additional work experience, you will have another source of income and a referral network. Yay!<br><br>
			<center><a href="<?php echo home."faq/tutors"; ?>" class="header-link"><div class="read-more">Read More&nbsp;&nbsp;></div></a></center></div>
		<div class="home-box">
			<amp-img src="<?php echo icons."odll-person.png" ?>" alt="Parents" height="70" width="70"></amp-img><br><br>
			<span class="font15em"><b>For Parents</b></span><br><br>
			We give you a convenient method and the freedom in choosing your child's Tutor. The Learning Session will be in your preferred location, at your leisure, following your schedule.<br><br>
			<center><a href="<?php echo home."faq/parents"; ?>" class="header-link"><div class="read-more">Read More&nbsp;&nbsp;></div></a></center></div>
	</div>
	<div class="home-contact">
		<span class="home-contact-text"><amp-img src="<?php echo icons."odll-phone.png" ?>" alt="Contact" height="15" width="15"></amp-img> &nbsp;<b>Contact Us</b></span>
		<span class="hide-on-mobile hide-on-tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span><a href="<?php echo home."contactus"; ?>" class="header-link">Send an Online Inquiry</a></span>
		<span class="hide-on-mobile hide-on-tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span><?php echo $contact->Email ?></span>
		<span class="hide-on-mobile hide-on-tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span><?php echo $contact->Mobile ?></span>
	</div>
</div>