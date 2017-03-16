<?php session_start(); ?>

<!doctype html>
<html amp lang="en">
	<head>
		<meta charset="utf-8">
		<script async src="https://cdn.ampproject.org/v0.js"></script>
		<title>ODLL: On-Demand Leisure Learning</title>
		<!--<link rel="canonical" href="https://www.odllearning.com" />-->
		<link rel="canonical" href="https://localhost/odll" />
		<meta name="viewport" content="width=device-width,minimum-scale=1" />
		<meta name="description" content="Book a tutor and schedule the learning session in your home, at your leisure." />
		<!-- <meta http-equiv="Cache-Control" content="no-cache" />
		<META http-equiv="Pragma" content="no-cache">
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="window-target" content="_top"> -->
		
		<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
		<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
		<script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
		<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>
		<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
		<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
		
		<meta property="og:image" content="<?php echo img."ODLL-Tutor-1.jpg" ?>" />
		<meta property="og:url" content="https://www.odllearning.com" />
		<meta property="og:title" content="ODLL: On-Demand Leisure Learning" />
		<meta property="og:description" content="Book a tutor and schedule the learning session in your home, at your leisure." />
		<meta property="fb:app_id" content="1771374356434820" />
		
		<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
		
		<link href="https://fonts.googleapis.com/css?family=Kadwa" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		
		<style amp-custom>
		
		html {
			font-family: 'Roboto', sans-serif;
			color: #15314d;
			overflow-y: scroll;
			font-size: 16px;
		}
		@media only screen and (max-width: 600px) {
			html { font-size: 14px; }
		}
		body {
			margin: 0px;
			padding: 0px;
		}
		
		/** GENERAL **/
		
		@media (max-width: 600px) { .hide-on-mobile { display: none; } }
		@media (min-width: 601px) and (max-width: 1024px) { .hide-on-tablet { display: none; } }
		@media (min-width: 1025px) { .hide-on-desktop { display: none; } }
		
		button, input, select, textarea {
			font-family: inherit;
			font-size: 100%;
		}
		
		h1, h3, .font-2 { font-family: 'Kadwa', serif; }
		.font13em {	font-size: 1.3em; }
		.font15em { font-size: 1.5em; }
		.font20em {	font-size: 2.0em; }
		.font23em {	font-size: 2.3em; }
		.font40em {	font-size: 4.0em; }

		.red-i { color: red; font-style: italic; font-size: 0.95em; }
		.blue-i { color: blue; font-style: italic; font-size: 0.95em; }
		.center { text-align: center; }
		.no-decor { text-decoration: none; }
		.margin-l { margin-left: 20px; }
		.link-button {
			text-decoration: none;
			color: #15314d;
			font-size: 2.5em;
		}
		.odll-button {
			height: 35px;
		}
		.read-more {
			padding: 10px 10px 10px 10px;
			border: 1px solid #bcbcbc;
			width: 150px;
			height: 30px;
			line-height: 30px;
			color: #15314d;
			text-decoration: none;
			text-transform: uppercase;
			font-size: 0.85em;
			font-weight: bold;
			background: linear-gradient(#fff, #efefef);
		}
		@media only screen and (max-width: 600px) {
			.read-more {
				font-size: 1em;
				font-weight: normal;
			}
		}
		
		.line-black {
			height: 2px;
			width: 100%;
			margin-bottom: 1em;
			background-color: black;
		}

		::-webkit-scrollbar {
			width: 8px;
		}
		::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
		}
		::-webkit-scrollbar-thumb {
			background: #34495e; 
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
		}

		/* make keyframes that tell the start state and the end state of our object */
		@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
		@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
		@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
		@-o-keyframes fadein { from { opacity:0; } to { opacity: 1; } }

		.fadeIn {
			opacity: 0; /* make things invisible upon start */
			-webkit-animation: fadeIn ease-in 1; /* call our keyframe named fadeIn, use animattion ease-in and repeat it only 1 time */
			-moz-animation: fadeIn ease-in 1;
			animation: fadeIn ease-in 1;

			-webkit-animation-fill-mode: forwards; /* this makes sure that after animation is done we remain at the last keyframe value (opacity: 1)*/
			-moz-animation-fill-mode: forwards;
			animation-fill-mode: forwards;

			-webkit-animation-duration: 0.5s;
			-moz-animation-duration: 0.5s;
			animation-duration: 0.5s;
		}

		.flexwrapper-header {
			width: 100%;
			display: flex;
			justify-content: space-around;
			flex-flow: row nowrap;
			position: fixed;
			z-index: 200;
			height: 50px;
			align-items: center;
			background-color: #34495e;
		}
		.flexwrapper-header-profile {
			width: 100%;
			display: flex;
			justify-content: space-between;
			flex-flow: row nowrap;
			align-items: center;
			border-bottom: 1px solid #ccc;
			margin: 5px 0px 20px 0px;
		}
		@media only screen and (max-width: 600px) {
			.flexwrapper-header-profile {
				margin: 5px 10px 20px 10px;
				width: auto;
			}
		}
		.flexwrapper-whole {
			margin-top: 23px;
			display: flex;
			align-content: flex-start;
			justify-content: center;
			flex-direction: column;
			flex-wrap: wrap;
			align-items: center;
		}
		.flexwrapper-top {
			text-align: center;
			display: flex;
			flex-direction: column;
			min-height: 100%;
		}
		@media only screen and (min-width: 601px) {
			.flexwrapper-top { height: 100%; }
		}
		@media only screen and (max-width: 600px) {
			.flexwrapper-top {	padding-bottom: 20px; }
		}
		.flexwrapper-bottom {
			text-align: center;
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
		}
		.flexwrapper-bottom-c {
			max-width: 1024px;
			display: flex;
			flex-flow: row wrap;
			justify-content: space-between;
		}
		@media only screen and (max-width: 720px) {
			.flexwrapper-bottom-c { flex-direction: column; }
		}
		.flexwrapper-half {
			display: flex;
			flex-flow: row wrap;
			justify-content: center;
		}
		.flexwrapper-results {
			display: flex;
			flex-flow: row nowrap;
			justify-content: space-between;
			padding-top: 20px;
		}
		.flexwrapper-footer {
			display: flex;
			justify-content: space-between;
			flex-flow: row nowrap;
			padding: 0px 40px 0px 40px;
			font-size: 0.9em;
			background-color: #34495e;
			color: white;
			height: 35px;
			line-height: 35px;
		}
		@media only screen and (max-width: 400px) {
			.flexwrapper-footer { font-size: 0.75em; }
		}

		.header-logo {
			text-decoration: none;
			color: white;
			font-weight: bold;
		}
		.header-link, .mobile-sidebar a {
			text-decoration: none;
			color: white;
		}
		.header-link-selected, .mobile-sidebar .sidebar-link-selected {
			text-decoration: none;
			color: #fedf81;
		}
		.header-signup-wrapper {
			text-align: right;
		}
		
		.mobile-sidebar-button {
			color: white;
			font-size: 25px;
			position: fixed;
			z-index: 300;
			height: 50px;
			line-height: 50px;
			background-color: #34495e;
			top: 0px;
			left: 15px;
		}
		.mobile-sidebar {
			background-color: #333;
			padding: 30px 30px 0px 30px;
			font-size: 1.6em;
		}
		.mobile-sidebar-margin {
			margin-left: 20px;
		}
		
		/** HOME **/

		.home {
			opacity: 0;
			transition: opacity 1s;
			background-color: #27d1af;
		}
		.home-loaded {
			opacity: 1;
		}
		.home-text {
			padding: 150px 0px 30px 0px;
			font-family: 'Kadwa', serif;
			font-size: 2.3em;
		}
		@media (max-width: 1024px) {
			.home-text {
				padding: 100px 10px 60px 10px;
				font-size: 2em;
				line-height: 1.6em;
			}
		}
		.home-box {
			line-height: 1.5em;
			flex: 1;
			padding: 40px 20px 60px 20px;
		}
		@media only screen and (max-width: 720px) {
			.home-box {
				border-top: 1px dotted #34495e;
			}
		}
		.home-contact {
			display: flex;
			flex-direction: row;
			justify-content: center;
			flex-wrap: wrap;
			background-color: #3f5a75;
			color: white;
			padding: 30px 0px 30px 0px;
			width: 100%;
		}
		@media (min-width: 721px) and (max-width: 1024px) {
			.home-contact {
				padding: 20px 15px 20px 15px;
				justify-content: space-around;
			}
		}
		
		@media only screen and (max-width: 720px) {
			.home-contact {
				flex-direction: column;
				line-height: 3em;
				padding: 30px 0px 20px 0px;
				justify-content: space-around;
			}
			.home-contact-text {
				font-size: 1.5em;
			}
		}
		.home-input:focus, .home-select:focus, .home-select2:focus,
		.home-input, .home-select, .home-select2, .home-submit,
		.home-input-s:focus, .home-select-s:focus, .home-select2-s:focus,
		.home-input-s, .home-select-s, .home-select2-s, .home-submit-s {
			border: 1px solid #27d1af;
			outline: none;
		}
		.home-input-s, .home-select-s, .home-select2-s, .home-submit-s {
			border: 1px solid #ccc;
		}
		.home-input-s:focus, .home-select-s:focus, .home-select2-s:focus {
			border: 1px solid #34495e;
		}
		.home-select, .home-select2, .home-submit,
		.home-select-s, .home-select2-s, .home-submit-s {
			height: 45px;
			width: 150px;
			padding-left: 5px;
			margin-left: -6px;
		}
		.home-input, .home-input-s {
			width: 200px;
			height: 45px;
			padding: 5px 5px 5px 10px;
		}
		.home-select, .home-select2, .home-select-s, .home-select2-s {
			cursor: pointer;
		}
		.home-submit, .home-submit-s {
			color: #15314d;
			background-image: linear-gradient(to bottom, #fedf81, #fedf81 50%, #fcd970 50%, #fcd970);
			font-weight: bold;
			border: 1px solid #27d1af;
			border-bottom: 1px solid #fcd970;
		}
		.home-submit-s {
			border: 1px solid #ccc;
		}
		@media (max-width: 600px) {
			.home-input-s, .home-select-s, .home-select2-s, .home-submit-s,
			.home-input, .home-select, .home-select2, .home-submit {
				width: 250px;
				margin: 0px 0px 10px 0px;
			}
			.home-input-s, .home-input {
				width: 233px;
				margin-left: 0px;
			}
		}
		
		/*** HOME - MENTORS ***/

		.home-mentors {
			opacity: 0;
			transition: opacity 1s;
			background-color: #e6e6e6;
		}
		.home-mentors h1{ font-size: 15pt; }
		.home-mentors-text {
			padding: 70px 0px 0px 0px;
			line-height: 1.7em;
			font-size: 1.3em;
			font-family: 'Kadwa', serif;
		}
		.home-mentors-announce {
			padding: 70px 20px 20px 20px;
			border-bottom: 1px dotted #aa2121;
			color: #aa2121;
			background-color: #fdfc9d;
			font-size: 1em;
			line-height: 1.4em;
		}
		@media only screen and (max-width: 600px) {
			.home-mentors-announce {
				padding: 50px 20px 10px 20px;
			}
			.home-mentors-text {
				padding: 30px 20px 30px 20px;
				line-height: 1.7em;
				font-size: 1.2em;
			}
		}
		
		#video-left, #video-right {
			font-size: 1.5em;
			font-weight: bold;
			font-family: 'Kadwa', serif;
			height: auto;
			width: 420px;
			margin: 20px 20px 0px 20px;
		}
		@media only screen and (max-width: 960px) {
			#video-left, #video-right {
				width: 600px;
				margin: 10px 10px 0px 10px;
			}
		}
		.home-videos-text {
			padding: 30px 20px 50px 20px;
			font-size: 1.2em;
			line-height: 1.5em;
			font-family: 'Kadwa', serif;
		}
		@media only screen and (max-width: 600px) {
			.home-videos-text {
				padding: 30px 20px 20px 20px;
				font-size: 1.15em;
			}
		}
		
		/** HELP TIP **/

		.help-tip {
			position: relative; /*absolute*/
			text-align: center;
			background-color: #fdfc9d;
			border-radius: 50%;
			width: 20px;
			height: 20px;
			font-size: 14px;
			line-height: 22px;
			cursor: default;
		}
		.help-tip:before {
			content:'?';
			font-weight: bold;
		}
		.help-tip:hover p {
			display:block;
			transform-origin: 100% 0%;
			-webkit-animation: fadeIn 0.3s ease-in-out;
			animation: fadeIn 0.3s ease-in-out;
		}
		.help-tip p {
			display: none;
			text-align: left;
			background-color: #fdfc9d;
			padding: 15px;
			width: 230px;
			position: absolute;
			border-radius: 3px;
			box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.2);
			right: -150px;
			line-height: 1.4;
		}
		.help-tip p:before {
			position: absolute;
			content: '';
			width:0;
			height: 0;
			border:6px solid transparent;
			border-bottom-color:#fdfc9d;
			right:155px;
			top:-12px;
		}
		.help-tip p:after {
			width:100%;
			height:40px;
			content:'';
			position: absolute;
			top:-40px;
			left:0;
		}
		
		/** STATIC AND OTHER PAGES **/
		
		.static-page-header {
			background-color: #34495e;
			text-align: center;
			width: 100%;
			height: 90px;
			color: #fedf81;
		}
		.static-page-header h1 { 
			font-size: 1.7em;
		}
		@media only screen and (max-width: 600px) {
			.static-page-header {
				margin-top: 0px;
				height: 70px;
			}
			.static-page-header h1 {
				font-size: 1.4em;
			}
		}
		.static-page {
			line-height: 1.6em;
			margin: 30px 20px 40px 20px;
			/*max-width: 800px;*/
		}
		.static-page p {
			margin: 20px 0px 20px 20px;
		}
		.contactus h3 {
			margin-top: -5px;
		}
		@media only screen and (min-width: 841px) {
			.static-page {
				width: 800px;
			}
		}
		@media (min-width: 601px) and (max-width: 840px) {
			.static-page {
				margin: 40px 0px 20px 0px;
				line-height: 1.45em;
				max-width: 580px;
			}
		}
		@media only screen and (max-width: 600px) {
			.static-page {
				margin: 20px 20px 20px 20px;
				line-height: 1.45em;
			}
			.static-page p {
				margin: 20px 0px 20px 10px;
			}
			.static-page h3 {
				line-height: 1.6em;
			}
		}
		.static2-page h1 {
			font-size: 1.25em;
		}
		.static2-page {
			line-height: 1.6em;
		}
		@media only screen and (min-width: 841px) {
			.static2-page {
				margin: 0px 0px 40px 0px;
				width: 700px;
			}
		}
		@media (min-width: 601px) and (max-width: 840px) {
			.static2-page {
				margin: 0px 30px 20px 30px;
				line-height: 1.45em;
				width: auto;
			}
		}
		@media only screen and (max-width: 600px) {
			.static2-page {
				margin: 0px 20px 20px 20px;
				width: auto;
			}
		}
		
		.faq-accordion {
			width: 800px;
			margin-bottom: 20px;
		}
		.faq-accordion h3 {
			padding: 10px;
			margin-top: -10px;
		}
		.faq-accordion-c ul {
			list-style-position: outside;
			margin-top: -10px;
		}
		@media (min-width: 601px) and (max-width: 840px) {
			.faq-accordion { width: 580px; }
		}
		@media only screen and (max-width: 600px) {
			.faq-accordion { width: 340px; }
			.faq-accordion-c ul { margin-left: -10px; }
		}
		@media only screen and (min-width: 840px) {
			.embed-youtube { width: 580px; }
		}
		
		.div-center {
			display: flex;
			justify-content: center;
			padding: 10px;
		}
		.picture-results, .picture-profile {
			border: 1.5px solid grey;
			border-radius: 50%;
			outline: none;
		}
		.picture-results {
			margin-right: 20px;
		}
		@media (max-width: 600px) {
			.picture-results {
				margin-right: 10px;
			}
		}
		.results-text {
			color: #15314d;
			line-height: 1.35em;
		}
		.results-text label {
			font-weight: bold;
			font-size: 1.2em;
			line-height: 1.9em;
		}
		@media (max-width: 600px) {
			.results-text {
				font-size: 0.8em;
				line-height: 1.2em;
			}
		}
		.star-full, .star-empty {
			margin: 0;
			padding: 0;
			text-align: right;
			border: none;
			outline: none;
			width: 45px;
			height: 45px;
			cursor: pointer;
		}
		.star-full {
			background: url(<?php echo icons."odll-star-full.png" ?>) no-repeat left top;
			background-size: 45px 45px;
		}
		.star-empty {
			background: url(<?php echo icons."odll-star-empty.png" ?>) no-repeat left top;
			background-size: 45px 45px;
		}
		
		/** FORMS **/
		
		form.amp-form-submit-success [submit-success],
		form.amp-form-submit-error [submit-error]{
			
		}
		form.amp-form-submit-success [submit-success] {
			
		}
		form.amp-form-submit-error [submit-error] {
			
		}
		.hide-inputs.amp-form-submit-success > .hide-me {
			display: none;
		}
		@media only screen and (min-width: 841px) {
			#contactus-left {
				min-width: 360px;
				margin-top: 10px;
			}
			#contactus-right {
				min-width: 220px;
				margin-top: 10px;
				text-align: right;
			}
		}
		@media only screen and (max-width: 840px) {
			#contactus-right {
				margin-top: 30px;
				text-align: center;
				width: 300px;
			}
		}
		#contactus-left label, #contactus-right label, .noresults label {
			font-size: 0.9em;
		}
		#contactus-form input[type=text], #contactus-form input[type=email], #contactus-form textarea,
		#noresults-form input[type=text], #noresults-form input[type=email], #noresults-form textarea,
		#inquire-form input[type=text], #inquire-form input[type=email], #inquire-form textarea,
		#noresults-form select {
			padding: 5px;
			width: 350px;
			resize: none;
			border: none;
			border: 1px solid #bdc3c7;
			background-color: #fff;
			margin-bottom: 15px;
			outline: none;
		}
		#contactus-form textarea, #noresults-form textarea, #inquire-form textarea {
			height: 96px;
		}
		@media only screen and (max-width: 600px) {
			#contactus-form input[type=text], #contactus-form input[type=email], #contactus-form textarea,
			#noresults-form input[type=text], #noresults-form input[type=email], #noresults-form textarea,
			#inquire-form input[type=text], #inquire-form input[type=email], #inquire-form textarea {
				font-size: 0.9em;
				padding: 2px 2px 2px 5px;
				min-width: 250px;
				max-width: 300px;
			}
		}
		@media only screen and (max-width: 350px) {
			#contactus-form input[type=text], #contactus-form input[type=email], #contactus-form textarea,
			#noresults-form input[type=text], #noresults-form input[type=email], #noresults-form textarea,
			#inquire-form input[type=text], #inquire-form input[type=email], #inquire-form textarea {
				font-size: 0.9em;
				padding: 2px 2px 2px 5px;
				max-width: 280px;
			}
		}
		#contactus-form input[type=text]:focus, #contactus-form input[type=email]:focus, #contactus-form textarea:focus,
		#noresults-form input[type=text]:focus, #noresults-form input[type=email]:focus, #noresults-form textarea:focus,
		#inquire-form input[type=text]:focus, #inquire-form input[type=email]:focus, #inquire-form textarea:focus {
			border: 1px solid #34495e;
		}
		#contactus-form input[type=submit], #inquire-form input[type=submit],
		#noresults-form input[type=submit], #apply-button, .odll-button {
			line-height: 35px;
			color: #15314d;
			font-weight: bold;
			width: 200px;
			height: 45px;
			padding: 5px 20px 5px 20px;
			border: 0px;
			border-radius: 4px;
			background-image: linear-gradient(to bottom, #fedf81, #fedf81 50%, #fcd970 50%, #fcd970);
		}
		#contactus-form input[type=submit]:hover, #inquire-form input[type=submit]:hover,
		#noresults-form input[type=submit]:hover, #apply-button:hover, .odll-button:hover,
		.home-submit:hover, .home-submit-s:hover {
			cursor: pointer;
			background-image: linear-gradient(to bottom, #fedf81, #fedf81 50%, #fedf81 50%, #fedf81);
		}
		#inquire-form tr {
			background-color: green;
		}
		
		/** TUTOR APP FORM **/
		
		.appform-accordion {
			width: 800px;
			margin-bottom: 20px;
		}
		.appform-accordion h3 {
			padding: 10px;
			margin: -10px 0px 20px 0px;
		}
		.appform-accordion-c {
			margin: 0px 0px 30px 60px;
		}
		@media (min-width: 601px) and (max-width: 840px) {
			.appform-accordion { width: 580px; }
		}
		@media only screen and (max-width: 600px) {
			.appform-accordion { width: 340px; }
		}
		
		/** EXPANDABLE **/
		
		.searchcondition {
			margin-top: 20px;
		}
		.expandable-toggle-search, .expandable-toggle-profile1, .expandable-toggle-profile2 {
			display: none;
			visibility: hidden;
		}
		.expandable-search section, .expandable-profile section  {
			padding: 15px 15px;
		}
		.expandable-search #expandable-toggle-search:checked ~ #expandable-div-search,
		.expandable-profile #expandable-toggle-profile1:checked ~ #expandable-div-profile1,
		.expandable-profile #expandable-toggle-profile2:checked ~ #expandable-div-profile2 {
			height:auto;
		}
		.expandable-search #expandable-toggle-search:checked ~ label::before,
		.expandable-profile #expandable-toggle-profile1:checked ~ .expandable-label-profile1::before,
		.expandable-profile #expandable-toggle-profile2:checked ~ .expandable-label-profile2::before {
			content: "-";
		}
		.expandable-search {
			margin-top: 5px;
			left: 0;
			width: 100%;
			background-color: #e6e6e6;
			/*position: fixed;*/
			z-index: 150;
		}
		.expandable-search label {
			display: block;
			padding: 10px;
			text-align: center;
			border-bottom: 1px solid #CCC;
			text-transform: uppercase;
		}
		.expandable-search label::before {
			font-family: Consolas, monaco, monospace;
			font-weight: bold;
			font-size: 1em;
			content: "+";
			vertical-align: text-top;
			display: inline-block;
			width: 20px;
			height: 20px;
			margin-right: 3px;
			background: radial-gradient(ellipse at center, #CCC 50%, transparent 50%);
		}
		.expandable-search #expandable-div-search {
			height: 0px;
			overflow: hidden;
			transition: height 0.5s;
			background-color: #e6e6e6;
			color: #FFF;
		}
		.expandable-profile {
			/*margin-top: 5px;*/
			left: 0;
			width: 100%;
			z-index: 150;
		}
		.expandable-label-profile1, .expandable-label-profile2 {
			/*display: block;*/
			padding: 10px 100px 10px 100px;
			text-align: center;
			border: 1px solid red;
			text-transform: uppercase;
		}
		.expandable-label-profile1::before, .expandable-label-profile2::before {
			font-family: Consolas, monaco, monospace;
			font-weight: bold;
			font-size: 1em;
			content: "+";
			vertical-align: text-top;
			display: inline-block;
			width: 20px;
			height: 20px;
			margin-right: 3px;
			background: radial-gradient(ellipse at center, #CCC 50%, transparent 50%);
			margin-bottom: 15px;
		}
		.expandable-profile #expandable-div-profile1, .expandable-profile #expandable-div-profile2 {
			height: 0px;
			overflow: hidden;
			transition: height 0.5s;
			color: #FFF;
		}
		.expandable-label-profile1, #expandable-div-profile1 {
			background-color: yellow;
		}
		.expandable-label-profile2, #expandable-div-profile2 {
			background-color: blue;
		}
		
		/** USER PROFILE **/
				
		.profile h1 {
			font-size: 2.2em;
		}
		.profile-details td {
			padding: 15px 25px 0px 20px;
		}
		.profile-details tr:nth-child(odd) { background: #e5effb }
		.profile-details label {
			font-weight: bold;
			font-size: 1.1em;
			margin-left: 10px;
		}
		.profile-details-label {
			display: flex;
			flex-flow: row nowrap;
			align-items: center;
		}
		.profile-details p {
			margin-left: 45px;
		}
		.profile-details-indent {
			margin-left: 20px;
			display: block;
		}
		.profile-tabs {
			position: fixed;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-flow: row nowrap;
			background-color: #efefef;
			width: 100%;
			top: 50px;
			left: 0;
			height: 60px;
			z-index: 300;
			margin-bottom: 20px;
		}
		.profile-tab, .profile-tab-selected {
			text-decoration: none;
			color: #15314d;
			padding: 0px 30px 0px 30px;
		}
		.profile-tab-selected {
			font-weight: bold;
		}
		.mybookings label {
			font-weight: bold;
		}
		
		
		.inquire-tutor-div, .inquire-tutor-label {
			display: none;
		}
		#inquire-tutor, #inquire-tutor-show, #book-tutor {
			display: inline;
		}
		#inquire-tutor:checked ~ #inquire-tutor-show, #inquire-tutor:checked ~ #book-tutor {
			display: none;
		}
		#inquire-tutor:checked ~ .inquire-tutor-div {
			display: block;
		}
		#inquire-tutor:checked ~ .inquire-tutor-label {
			display: inline;
		}
		
		
		
		
		
		.settings-buttons {
			text-align: right;
			margin: 0px 0px 5px 0px;
		}
		.settings-button {
			font: Arial;
			color: black;
			font-size: 1em;
			padding: 2px 6px 2px 6px;
			border: 1px solid #a6a6a6;
			border-radius: 1px;
			background: linear-gradient(#f8f8f8, #dddddd);
			text-decoration: none;
		}
		.settings-button:hover {
			border-color: #757575;
			cursor: default;
		}
		.settings section {
			display: flex;
			margin: 0 auto;
		}
		.settings-table {
			border-right: 1px solid #999;
			border-bottom: 1px solid #999;
			background: linear-gradient(#fff, #f3f3f3);
		}
		.settings-table label, .settings-add .label {
			font-size: 0.9em;
			color: #333;
		}
		.settings-table .column {
			padding: 5px;
			flex-grow: 1;
			flex-shrink: 1;
			flex-basis: 0;
			border-top: 1px solid #999;
			border-left: 1px solid #999;
		}
		
		.settings-add {
			display: none;
			background-color: #fdfc9d;
			margin: 20px 0px 20px 0px;
			border: 1px dotted #666;
			padding: 10px;
		}
		.settings-add .column {
			padding: 5px;
			flex-grow: 1;
			flex-shrink: 1;
			flex-basis: 0;
		}
		#settings-add-c:checked ~ .settings-add { //:not(:checked)
			display: block;
			-webkit-animation: slide-down .7s ease-out;
			-moz-animation: slide-down .7s ease-out;
		}
		
		@-webkit-keyframes slide-down {
			0% { opacity: 0; -webkit-transform: translateY(-100%); }   
			100% { opacity: 1; -webkit-transform: translateY(0); }
		}
		@-moz-keyframes slide-down {
			0% { opacity: 0; -moz-transform: translateY(-100%); }   
			100% { opacity: 1; -moz-transform: translateY(0); }
		}
		
		#settings-add-c:checked ~ #settings-button-add {
			display: none;
		}
		#settings-add-c:checked ~ #settings-button-cancel {
			display: inline;
		}
		
		#settings-delete-c, #settings-add-c, #settings-cancelb, #inquire-tutor {
			opacity: 0;
		}
		#settings-delete-c:checked ~ #settings-button-delete,
		#settings-cancelb:checked ~ #settings-button-cancelb {
			display: none;
		}
		#settings-delete-c:checked ~ .settings-delete-hide,
		#settings-cancelb:checked ~ .settings-cancel-hide {
			display: inline;
		}
		
		.settings-delete-hide, .settings-cancel-hide {
			display: none;
		}
		
		.table-like {
			display: table-row;
		}
		.table-like div {
			display: table-cell;
			margin-top: 10px;
			margin-bottom: 10px;
			vertical-align: top;
			padding: 0px 5px 10px 5px;
			line-height: 1.5em;
		}
		.table-like-a {
			width: 240px;
			text-align: right;
		}
		.table-like-a2 {
			width: 320px;
			text-align: right;
		}
		.table-like .support {
			font-size: 0.9em;
			font-style: italic;
			color: #575757;
			margin-top: 2px;
			line-height: 1.4em;
		}
		.table-like input[type=text], .table-like input[type=email], .table-like input[type=month], 
		.table-like input[type=date], .table-like textarea {
			width: 320px;
			resize: none;
			outline: none;
		} 
		.table-like textarea {
			height: 96px;
		}
		.table-like select {
			width: 100px;
			outline: none;
		}
		</style>
	</head>
	<?php flush(); 
	echo "<body>"; ?>
	
	<span class="icon-menu mobile-sidebar-button hide-on-desktop hide-on-tablet"></span> <!-- on='tap:mobile-sidebar.toggle' -->
		<div class="flexwrapper-header">
			
			<div class=""><a href="<?php echo home.""; ?>" class="header-logo font15em">ODLL</a></div>
			<div class="hide-on-mobile"><center>
				<?php
				$url = $_SERVER['REQUEST_URI'];
				echo "
				<a href='".home."how' class='";
					if (strpos($url, "/home/how") !== false) echo "header-link-selected";
					else echo "header-link";
					echo "'>How It Works</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."faq' class='";
					if (strpos($url, "/home/faq") !== false) echo "header-link-selected";
					else echo "header-link";
					echo "'>FAQ</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."apply' class='";
					if (strpos($url, "/profile/apply") !== false) echo "header-link-selected";
					else echo "header-link";
					echo "'>Join</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='".home."contactus' class='";
					if (strpos($url, "/profile/contactus") !== false) echo "header-link-selected";
					else echo "header-link";
					echo "'>Contact Us</a>";
				?></center></div>
			<div class="hide-on-mobile header-signup-wrapper">
				<?php
				if ($_SESSION['first_name'] != "") echo "<a href='".profile."me' class='header-link'>Hi ".$_SESSION['first_name']."</a> [<a href='".home."fblogout' class='header-link'>Log Out</a>] ";
				else echo "<a href='".home."fblogin' class='header-link'>Log In</a>";
				/* <amp-img src='".img."odll-facebook-login2.png' width='90' alt='odll-facebook-login'></amp-img> */
				?></div>
		</div>
		
	<?php echo "
	<amp-sidebar id='mobile-sidebar' layout='nodisplay' class='mobile-sidebar hide-on-desktop hide-on-tablet'>
		<a href='".home."'"; if ($url == "/odll/" || $url == "/odll/home") echo " class='sidebar-link-selected'"; echo ">Home</a><br>
		<a href='".home."how'"; if (strpos($url, "/home/how") !== FALSE) echo " class='sidebar-link-selected'"; echo ">How It Works</a><br>
		<a href='".home."faq'"; if (strpos($url, "/home/faq") !== FALSE) echo " class='sidebar-link-selected'"; echo ">FAQ</a><br>
		<a href='".home."apply'"; if (strpos($url, "/home/apply") !== FALSE) echo " class='sidebar-link-selected'"; echo ">Join</a><br>
		<a href='".home."contactus'"; if (strpos($url, "/home/contactus") !== FALSE || strpos($url, "/home/messagesent") !== FALSE) echo " class='sidebar-link-selected'"; echo ">Contact Us</a><br>";
		if ($_SESSION['first_name'] != "") echo "<a href='".profile."me' class='header-link'>My Account</a><br>
			<a class='mobile-sidebar-margin' href='".profile."bookmarks'>Tutors</a><br>
			<a class='mobile-sidebar-margin' href='".profile."bookings'>Appointments</a><br>
			<a class='mobile-sidebar-margin' href='".profile."settings'>Settings</a><br>";
		else echo "<a href='".home."fblogin'>Log In</a>";
	echo "</amp-sidebar>
	?>"; /*<a href='#' on='tap:sidebar1.close'>Close</a>*/
	?>