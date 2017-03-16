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
	<div class="static2-page">
		
		
		<h1>My Bookmarked Tutors</h1>
		
		<?php
		foreach($bookmarks as $r):
			if($r['SubjectSearched'] == 0) $r['Subject'] = "All Subjects";
			$bookmarkquery = "{$r['Subject']} ({$r['Level']}) in {$r['City']}";
			if($bookmarkquery1 == $bookmarkquery) echo "";
			else echo "<br>{$bookmarkquery}<br>";
			echo "
			<a href='".profile."bookmark/{$r['LevelNo']}+{$r['CityNo']}+{$r['SubjectSearched']}+{$r['TeacherNo']}' class='no-decor'>
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
			$bookmarkquery1 = $bookmarkquery;
		endforeach;
		?>
		
		
	</div>
</div>