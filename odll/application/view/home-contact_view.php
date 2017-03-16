<?php //include_once('./application/analyticstracking.php'); ?>

<div class="flexwrapper-whole">
	<div class="static-page-header"><h1>Contact Us</h1></div>
	<div class="static-page fadeIn contactus">
		
		<center><h3>Do you have questions or suggestions?
		<span class="hide-on-desktop"><br></span>
		We'd love to hear from you!</h3></center>
		
		<div class="flexwrapper-half">
			<?php echo "<div id='contactus-left'>
				<form method='post' id='contactus-form'
					action-xhr='".form_contactus."'
					target='_top'
					class='hide-inputs'>
				<span class='hide-me'>
				<label>Name</label><br>
				<input type='text' id='contactus-name' name='contactus-name' maxlength='90' required";
					if($_SESSION['first_name'] != "")
						echo " value='{$user->NickName} {$user->LastName}' ";
					else echo " placeholder='Juan Dela Cruz' ";
					echo "/><br>
				<label class='hide-me'>E-mail Address</label><br>
				<input type='email' id='contactus-email' name='contactus-email' maxlength='90' required";
					if($_SESSION['first_name'] != "")
						echo " value='{$user->Email}' ";
					else echo " placeholder=\"example@email.com\" ";
					echo "/><br>
				<label>Message</label><br>
				<textarea id='contactus-message' name='contactus-message' placeholder='What do you want to tell us?' rows='5' maxlength='500' required ></textarea><br>
				<center><input class='odll-button' type='submit' value='Send Message' /></center></span>
				<div submit-success><template type='amp-mustache'>
					Thank you for getting in touch!<br><br>We have received your message<br>and we will reply to<br>{{email}} shortly.<br><br>
					Go back to <a href='".home."'>ODLL Home</a>.</template></div>
				<div submit-error><template type='amp-mustache'>
					Oh no! Seems like something is wrong. Please e-mail us at {$contact->Email} instead.</template></div>
				</form>
			</div>"; ?>
			
			<div id="contactus-right">
				<label>You may also contact us<span class="hide-on-mobile"><br></span>through the following:</label><br><br>
				<label>E-mail</label><br><?php echo $contact->Email ?><br><br>
				<label>Mobile</label><br><?php echo $contact->Mobile ?>
			</div>
		</div>
		
		<div class="hide-on-desktop hide-on-tablet"><br><center><a href="<?php echo home.""; ?>" class="header-link"><div class="read-more">Back to Home</div></a></center></div>
		
	</div>
</div>