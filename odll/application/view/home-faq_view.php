<?php //include_once('./application/analyticstracking.php'); ?>

<div class="flexwrapper-whole">
	<div class="static-page-header"><h1>Frequently Asked Questions</h1></div>
	<div class="static-page fadeIn">
		
		<!--<span class="font-2 font13em"><center>back to top, home
			<a href="#faq-parents" class="smoothScroll">PARENTS</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a href="#faq-mentors" class="smoothScroll">TUTORS</a></center></span><br>-->
		
		<amp-accordion>
		<?php
		echo "<section class='faq-accordion'";
			if ($page=="") echo " expanded";
			echo "><h3>General</h3><div class='faq-accordion-c'>
		<ul>";
		$i = 1;
		foreach($faqs as $f):
			if($i==4) {
				echo "</ul></div></section><section class='faq-accordion'";
				if ($page=="parents") echo " expanded";
				echo "><h3>For Parents</h3><div id='faqp' class='faq-accordion-c'><ul>";
			} else if($i==9) {
				echo "</ul></div></section><section class='faq-accordion'";
				if ($page=="tutors") echo " expanded";
				echo "><h3>For Tutors</h3><div id='faqt' class='faq-accordion-c'><ul>";
			}
			echo "<br><li><b>{$f['Question']}</b><br>
			".nl2br($f['Answer'])."</li>";
			$i++;
		endforeach;
		?>
		
		<br><li><b>How can I Join?</b><br>
		Submit the <a href="https://goo.gl/forms/BQIN3WWeJVFhTjiQ2" target="_blank">Online Application Form</a> to start your application process. We will review your submitted form, create your ODLL Profile, and contact you in no time!</li>
		
		</div>
		</section>
		</amp-accordion>
		
		<div class="hide-on-desktop hide-on-tablet"><br><center><a href="#faq-top" class="header-link smoothScroll"><div class="read-more">Top&nbsp;&nbsp;<span class="icon-circle-up"></span></div></a></center></div>
		<div class="hide-on-desktop hide-on-tablet"><br><center><a href="<?php echo home.""; ?>" class="header-link"><div class="read-more">Home&nbsp;&nbsp;<span class="icon-home3"></span></div></a></center></div>
		<br>
		
	</div>
</div>