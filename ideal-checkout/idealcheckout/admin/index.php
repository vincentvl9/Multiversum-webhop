<?php


	@ini_set('display_errors', 1);
	@ini_set('display_startup_errors', 1);
	// @error_reporting(E_ALL | E_STRICT);
	@error_reporting(E_ALL);


	define('IDEALCHECKOUT_PATH', dirname(__DIR__));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');


	$aDatabaseSettings = idealcheckout_getDatabaseSettings();
	$sImagePath = idealcheckout_getRootUrl(). 'images';


	$sql = "SELECT `value` FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_settings` WHERE (`key_name` = 'license') LIMIT 1";
	$sLicense = idealcheckout_database_getValue($sql);


	// Load core library
	require_once('php/index.php');


	if(is_file(__DIR__ . '/debug.php'))
	{
		include_once(__DIR__ . '/debug.php');
	}


	session_set_cookie_params(3600, '/', '', false, true);
	session_start();

	if(!isset($_SESSION['idealcheckout']))
	{
		$_SESSION['idealcheckout'] = array();
	}


	if(empty($_SESSION['idealcheckout']['user']))
	{
		$sHtml = '';
		// echo"user empty - ";

		$aFormValues = array('username' => '', 'password' => '');
		$aFormErrors = array('username' => false, 'password' => false);


		// echo"values and errors reset - ";

		// See is session is properly started
		if(!empty($_SESSION['idealcheckout']['login:security_field']) && !empty($_SESSION['idealcheckout']['login:security_value']))
		{
			// Verify security post fields
			if(!empty($_POST['form']) && !empty($_POST[$_SESSION['idealcheckout']['login:security_field']]))
			{
				// See if form=login
				if(strcasecmp($_POST['form'], 'login') === 0)
				{
					if(strcasecmp($_POST[$_SESSION['idealcheckout']['login:security_field']], $_SESSION['idealcheckout']['login:security_value']) === 0)
					{
						if(empty($_POST['username']))
						{
							$aFormErrors['username'] = true;
							$sHtml .= '<div class="errorInfo">Geef een E-mail op.</div>';
						}
						elseif(empty($_POST['password']))
						{
							$aFormErrors['password'] = true;
							$sHtml .= '<div class="errorInfo">Geef een wachtwoord op.</div>';
						}
						else
						{
							$aFormValues['username'] = $_POST['username'];
							$aFormValues['password'] = $_POST['password'];
							// Encrypt password to test it in database

							$sHashSalt = "";
							$sEncryptedPassword = hash('sha256', $sHashSalt . $aFormValues['password']);
							$sql = "SELECT * FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_users` WHERE (`username` = '" . idealcheckout_escapeSql($aFormValues['username']) . "') AND (`password` = '" . idealcheckout_escapeSql($sEncryptedPassword) . "') AND (`enabled` = '1') LIMIT 1;";
							if($_SESSION['idealcheckout']['user'] = idealcheckout_database_getRecord($sql))
							{
								header('Location: index.php');
								exit;
							}
							else
							{
								$aFormErrors['username'] = true;
								$aFormErrors['password'] = true;
								$sHtml .= '<div class="errorInfo">Email of wachtwoord niet gevonden.</div>';
							}
						}
					}
				}
			}
		}

		$_SESSION['idealcheckout']['login:security_field'] = idealcheckout_getRandomCode(16);
		$_SESSION['idealcheckout']['login:security_value'] = idealcheckout_getRandomCode(16);

		$backgroundNumber = rand(1, 13);

		$sHtml .= '<!doctype html>
		<html style="background-image: url(\'' . $sImagePath . '/backgrounds/' . $backgroundNumber . '.jpg\'); height: 100%">
			<head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				<link href="css/fonts.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/login.css?t=' . time() . '" media="screen" rel="stylesheet" type="text/css">
				<link href="css/branding.css" media="screen" rel="stylesheet" type="text/css">
				<script>
					function showDisclaimer(){
						if (document.getElementById("disclaimer-text").style.display == "none"){
							document.getElementById("disclaimer-text").style.display = "block";
						}else{
							document.getElementById("disclaimer-text").style.display = "none";
						}
					}
				</script>
			</head>
			<body>
				<div class="content">
					<div class="logIn-header-logo"><img src="' . $sImagePath . '/inlog-logo.png" width="100%" title="iDEAL Checkout"></div>

					<div class="login-form-div">
						<form class="login-form" action="" method="post" name="login">
							<input name="form" type="hidden" value="login">
							<input name="' . htmlentities($_SESSION['idealcheckout']['login:security_field']) . '" type="hidden" value="' . htmlentities($_SESSION['idealcheckout']['login:security_value']) . '">

							<div class="username-wrapper' . ($aFormErrors['username'] ? ' error' : '') . '">
								<input id="username" name="username" placeholder="E-mailadres" type="text" value="' . htmlentities($aFormValues['username']) . '">
							</div>
							<div class="password-wrapper' . ($aFormErrors['password'] ? ' error' : '') . '">
								<input id="password" name="password" placeholder="wachtwoord" type="password" value="' . htmlentities($aFormValues['password']) . '">
								<input class="login-icon" type="submit" value="">
							</div>
						</form>
						<div class="forgot-password-link">
							<a href="forgotpassword.php">Wachtwoord vergeten?</a>
						</div>
					</div>
					<div class="login-bottom-text">
						<a class="login-bottom-link" href="https://www.ideal-checkout.nl/">Payments for Websites & Webshops</a><br>
						<a class="login-bottom-disclaimer" onclick="showDisclaimer()"> Disclaimer </a>


					</div>
				<div id="disclaimer-text" style="display: none;">
						De handelsmerken, handelsnamen, beelden, logo\'s die de producten en diensten van www.ideal-checkout.nl herkenbaar maken, alsmede het ontwerp, tekst en grafische mogelijkheden van de website zijn het eigendom van CodeBrain.<br> Tenzij dit uitdrukkelijk is bepaald, zal niets van hetgeen hierin is vervat, worden uitgelegd als het verlenen van een licentie of recht uit hoofde van het auteursrecht of enig ander intellectueel eigendomsrecht van CodeBrain, Alle rechten voorbehouden.<br> Anders is bepaald voor alle foto\'s gebruikt binnen deze website, deze vallen allen onder Fair Use en Creative Commons Zero (CC0) license zoals bepaald en verstrekt op Pexels.com.<br>
					</div>
				</div>
			</body>
		</html>';

		echo $sHtml;

	}
	elseif(!preg_match('/^([a-z0-9\-]+)$/', "index.php") || !is_file($sViewPath . "index.php" . '.php'))
	{

		$iPremiumUser = 0; //$_SESSION['idealcheckout']['user']['premium'];
		$sUsername = $_SESSION['idealcheckout']['user']['username'];

		session_regenerate_id();

		$sUsernameMd5 = md5($sUsername);
		if (strpos($sUsername, '@') !== false){
			$sUsernameCleaned = substr($sUsername, 0, strpos($sUsername, "@"));
		}else{
			$sUsernameCleaned = $sUsername;
		}

		$sUrl = $_SERVER['HTTP_HOST'];


		$sHashString = $sLicense . ',' . $sUrl;
		$sSignature = hash_hmac('sha512', $sHashString, $sLicense);

		header('Content-Type: text/html; charset=UTF-8');

		$sHtml = '
		<!doctype html>
		<html style="height: 100%">
			<head>
				<title>iDEAL Dashboard</title>

				<meta http-equiv="content-type" content="text/html; charset=UTF-8">
				<meta http-equiv="content-language" content="nl-nl">
				<meta name="robots" content="index, follow">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">

				<script type="text/javascript">
					var sUsernameMd5 = "' . $sUsernameMd5 . '";
					var iPremiumUser = ' . $iPremiumUser . ';
				</script>

				<link href="js/jquery-ui.css" media="screen" rel="stylesheet" type="text/css">
				<link href="js/jquery-ui.theme.min.css" media="screen" rel="stylesheet" type="text/css">

				<link href="css/fonts.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/header.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/sidebar.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/content.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/widgets.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/footer.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/body.css" media="screen" rel="stylesheet" type="text/css">

				<link href="css/layout.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/branding.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/settingsdialog.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/messagedialog.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/profilemenu.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/notificationmenu.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/infodialog.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/balloon.css" media="screen" rel="stylesheet" type="text/css">
				<link href="css/responsive.css" media="screen" rel="stylesheet" type="text/css">

				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
				<script type="text/javascript" src="js/jquery-ui.min.js"></script>
				<script type="text/javascript" src="js/scripts.js"></script>
				<script type="text/javascript" src="js/Chart.js"></script>
				<script type="text/javascript" src="https://www.ideal-checkout.nl/api/js?license=' . $sLicense . '&signature=' . $sSignature . '"></script>
				<script type="text/javascript" src="js/jquery.cookie.js"></script>

				<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>  <!-- zorgt ervoor dat jquery ui werkt op mobiele apparaten mbt slepen ed -->

				<script type="text/javascript">

					var iLoadWidth = 0;
					$(document).ready(function() {
						$(\'.disablePspInfoButton\').slideToggle(\'fast\');
						//is_screen_1024_1200();
						is_screen_less_1024();
						iLoadWidth = $(document).width();
					});



					$(window).resize(function() {

						is_screen_less_1024();

						var iWidth = $(document).width();

						if( iWidth < 1024 && iLoadWidth > 1024 ){
							window.location.reload( false );
							//location.reload();
						}else if( iWidth > 1024 && iLoadWidth < 1024 ){
							window.location.reload( false );
							//location.reload();
						}

					});


					function is_screen_1024_1200()
					{
						/*
						var iWidth = $(document).width();

						if( iWidth <= 1200 && iWidth >= 1024 ){
							jQuery(\'.header-logo\').addClass(\'mobile\');
							jQuery(\'#sidebar\').slideUp(\'slow\');
							jQuery(\'.content\').addClass(\'full-width-content\');
						}
						*/
					}

					function is_screen_less_1024()
					{
						var iWidth = $(document).width();

						if( iWidth <= 1024 ){
							jQuery(\'.thin-menu-icon\').css(\'display\', \'none\');
						}else{
							jQuery(\'.thin-menu-icon\').css(\'display\', \'block\');
						}
					}
				</script>
				<script type="text/javascript">
					function toggle_header_info()
					{
						jQuery(\'.pspInfoContainer\').toggleClass(\'minimized\');
						jQuery(\'#info-header\').slideToggle(\'fast\');
					}
					function hideTopWidget(){
						jQuery(\'.topWidgetContainer\').toggleClass(\'minimized\');
						jQuery(\'.content-row-upper\').slideToggle(\'fast\');
					}
					function displayTopWidget(){
						jQuery(\'.topWidgetContainer\').removeClass(\'minimized\');
						jQuery(\'.content-row-upper\').slideDown(\'slow\');
					}
				</script>
				<script>
					function openSettingsDialog(){
						$(\'#settingsDialog\').toggle("slide", { direction: "right" });
					}

					function openProfileMenu(){
						$(\'#infoDialog\').css("display", "none");
						$(\'#notificationMenu\').css("display", "none");
						$(\'#profileMenu\').toggle("slide", { direction: "right" });
					}
					function openNotificationMenu(){
						$(\'#infoDialog\').css("display", "none");
						$(\'#profileMenu\').css("display", "none");
						$(\'#notificationMenu\').toggle("slide", { direction: "right" });
					}

					function openMessageDialog(){
						$( \'#messageDialog\' ).toggle("slide", { direction: "right" });
					}

					function openInfoDialog(){
						$(\'#profileMenu\').css("display", "none");
						$(\'#notificationMenu\').css("display", "none");
						$( \'#infoDialog\' ).toggle("slide", { direction: "right" });
					}

					$( function() {
						$( "#headerInfoAccordion" ).accordion({
							collapsible: true,
							heightStyle: "content"
						});
						$( "#UserGearIconDialogAccordion" ).accordion({
							collapsible: true,
							heightStyle: "content"
						});
						$( "#UserHornIconAccordion" ).accordion({
							collapsible: true,
							heightStyle: "content"
						});
					});

					function openHelpDialog(){
						$( \'#helpDialog\' ).dialog();
					}
				</script>

				<script>
					function disablePspInfo(){
						$(\'.pspInfoContainer\').slideToggle(\'fast\');
						$(\'.disablePspInfoButton\').slideToggle(\'fast\');
					}

					function disableTopWidget(){
						$(\'.topWidgetContainer\').slideToggle(\'fast\');
						$(\'.disableTopWidgetButton\').slideToggle(\'fast\');
					}
				</script>
				<script>
					Chart.defaults.global.defaultFontColor = "#686868";
					Chart.defaults.global.defaultFontFamily = "OpenSansRegular";
					Chart.defaults.global.defaultFontSize = 14;

				</script>
				<script>
					setTimeout( function(){
							$(\'.noRightClick\').bind(\'contextmenu\', function(e) {
								return false;
							}); }, 1200);

					function disableRightClick(){
						setTimeout( function(){
							$(\'.noRightClick\').bind(\'contextmenu\', function(e) {
								return false;
							}); }, 500);
					}
				</script>
				<script>
					function logOut(){
						window.location.replace("logout.php");
					}
				</script>
				<script>
					function activateHeaderMenuBtn(){

						var iWidth = $(document).width();

						if( iWidth <= 1024 ){

							if($(\'.sidebar\').hasClass(\'sidebar-thin\'))
							{
								jQuery(\'.sidebar-thin\').slideToggle(\'slow\');
								jQuery(\'.content\').toggleClass(\'wide-width-content\');
							}else{
								jQuery(\'.header-logo\').toggleClass(\'mobile\');
								jQuery(\'.sidebar\').slideToggle(\'slow\');
							}

							jQuery(\'.content\').toggleClass(\'full-width-content\');

							setTimeout( function(){ for(k in Chart.instances) { Chart.instances[k].resize(); Chart.instances[k].render(); } }, 1100);
						}else{

							jQuery(\'.sidebar\').show();
							jQuery(\'.header-logo\').toggleClass(\'mobile\');

							jQuery(\'.sidebar\').toggleClass(\'sidebar-thin\');
							jQuery(\'.content\').toggleClass(\'wide-width-content\');

							setTimeout( function(){ for(k in Chart.instances) { Chart.instances[k].resize(); Chart.instances[k].render(); } }, 1100);
						}
					}



					function mobileWidgetSelect(){


					}

				</script>
				<script type="text/javascript">

					function showToolTip(){
						$(".content-header-left").text("Widgets kunt u slepen vanuit het menu en plaatsen op uw dashboard!");

						setTimeout(
						function()
						{
							$(".content-header-left").empty();
							$(".content-header-left").append(\'<img class="svg" src="' . $sImagePath . '/info-icon.svg" onclick="showToolTip()" style="cursor: pointer; height: 18px; vertical-align: bottom;" title="Laat hint zien">\');
						}, 5000);
					}

					$( document ).ready(function() {

						var iWidth = $(document).width();

						if( iWidth >= 1024 ){
							showToolTip();
						}
					});
				</script>

				<script>
					$(function(){
						jQuery(\'img.svg\').each(function(){
							var $img = jQuery(this);
							var imgID = $img.attr(\'id\');
							var imgClass = $img.attr(\'class\');
							var imgURL = $img.attr(\'src\');

							jQuery.get(imgURL, function(data) {
								// Get the SVG tag, ignore the rest
								var $svg = jQuery(data).find(\'svg\');

								// Add replaced images ID to the new SVG
								if(typeof imgID !== \'undefined\') {
									$svg = $svg.attr(\'id\', imgID);
								}
								// Add replaced images classes to the new SVG
								if(typeof imgClass !== \'undefined\') {
									$svg = $svg.attr(\'class\', imgClass+\' replaced-svg\');
								}

								// Remove any invalid XML tags as per http://validator.w3.org
								$svg = $svg.removeAttr(\'xmlns:a\');

								// Check if the viewport is set, else we gonna set it if we can.
								if(!$svg.attr(\'viewBox\') && $svg.attr(\'height\') && $svg.attr(\'width\')) {
									$svg.attr(\'viewBox\', \'0 0 \' + $svg.attr(\'height\') + \' \' + $svg.attr(\'width\'))
								}

								// Replace image with new SVG
								$img.replaceWith($svg);

							}, \'xml\');

						});
					});
				</script>

				<script>

					window.onload = function setInitialMenuItemColor(){
						jQuery(".info-section-icons-holder .gearIcon svg").css("fill", "#B2B2B2");
						jQuery(".news-section-icons-holder .hornIcon svg").css("fill", "#B2B2B2");
					};

					function changeUserDialog(activeSection)
					{
						jQuery(".info-section-icons-holder svg").css("fill", "#626262");

						switch(activeSection){
							case "gearIcon":
								jQuery(".info-section-content-holder").children().css("display", "none");
								jQuery(".UserGearIconDialog").css("display", "block");
								jQuery(".info-section-icons-holder .gearIcon svg").css("fill", "#B2B2B2");
								break;

							case "phoneIcon":
								jQuery(".info-section-content-holder").children().css("display", "none");
								jQuery(".UserPhoneIconDialog").css("display", "block");
								jQuery(".info-section-icons-holder .phoneIcon svg").css("fill", "#B2B2B2");
								break;

							case "mailIcon":
								jQuery(".info-section-content-holder").children().css("display", "none");
								jQuery(".UserMailIconDialog").css("display", "block");
								jQuery(".info-section-icons-holder .mailIcon svg").css("fill", "#B2B2B2");
								break;
							default:
								alert(activeSection);
						}



					}

					function changeNewsDialog(activeSection)
					{
						jQuery(".news-section-icons-holder svg").css("fill", "#626262");

						switch(activeSection){
							case "hornIcon":
								jQuery(".news-section-content-holder").children().css("display", "none");
								jQuery(".UserHornIconDialog").css("display", "block");
								jQuery(".news-section-icons-holder .hornIcon svg").css("fill", "#B2B2B2");
								break;

							case "flagIcon":
								jQuery(".news-section-content-holder").children().css("display", "none");
								jQuery(".UserFlagIconDialog").css("display", "block");
								jQuery(".news-section-icons-holder .flagIcon svg").css("fill", "#B2B2B2");
								break;

							case "speechIcon":
								jQuery(".news-section-content-holder").children().css("display", "none");
								jQuery(".UserSpeechIconDialog").css("display", "block");
								jQuery(".news-section-icons-holder .speechIcon svg").css("fill", "#B2B2B2");
								break;

							default:
								alert(activeSection);
						}
					}
				</script>


			</head>';

			$sAction = '';

			if(!empty($_GET))
			{
				if(strcasecmp($_GET['action'], 'contactmail') === 0)
				{
					$sAction = ' Uw email is succesvol verzonden, dank u wel.';
				}

				if(strcasecmp($_GET['action'], 'contactmailerror') === 0)
				{
					$sAction = ' Verplichte velden incorrect ingevuld';
				}
			}


			$sHtml .= '
			<body class="brand-body" style="min-height: 100%;">
				<header>
					<div class="header-logo"></div>
					<div class="header-menu" onclick="activateHeaderMenuBtn();">
						<img class="menu-icon icon svg" height="20" width="20" style="cursor: pointer; cursor: hand;" src="' . $sImagePath . '/menu-icon.svg">
					</div>
					<div class="header-account">

						<div class="header-account-section" >
							<div class="header-account-section-icon" onclick="openProfileMenu()">
								<img class="profile-icon icon svg"  height="22" width="22" src="' . $sImagePath . '/profile-icon.svg">
							</div>
							<div id="profileMenu" class="profileMenu" style="display: none">
								<div class="profile">
									<div class="info-section-title">
										<b>GEBRUIKER: '. $sUsernameCleaned .'</b>
										<div class="closeHeaderAccSectionBtn" onclick="openProfileMenu()">
											<img src="' . $sImagePath . '/cross-icon.svg" height="100%" >
										</div>
									</div>

									<div class="info-section-icons-holder">

										<div class="profile-menu-icon-holder gearIcon" title="instellingen" onclick="changeUserDialog(\'gearIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/settings-icon.svg">
										</div>

										';

										if((date('N') <= 5) && (date('G') >= 9) && (date('G') < 12))
										{
											$sHtml .= '
										<div class="profile-menu-icon-holder" title="iDEAL Checkout">
											<a href="tel:+31522746060"><img class="profile-menu-icon icon svg" src="' . $sImagePath . '/phone-icon.svg"></a>
										</div>
											';
										}
										else
										{
											$sHtml .= '
										<div id="test-click" class="profile-menu-icon-holder phoneIcon" title="iDEAL Checkout" onclick="changeUserDialog(\'phoneIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/phone-forbid-icon.svg">
										</div>
											';
										}

										$sHtml .= '
										<div class="profile-menu-icon-holder mailIcon" title="iDEAL Checkout" onclick="changeUserDialog(\'mailIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/messages-icon.svg" id="messages-icon">
										</div>

										<div class="profile-menu-icon-holder" title="iDEAL Checkout" onclick="window.open(\'https://twitter.com/idealcheckout\', \'_blank\');">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/twitter-icon.svg">
										</div>

										<div class="profile-menu-icon-holder" title="FAQ" onclick="window.open(\'https://www.ideal-checkout.nl/faq-ic\', \'_blank\');">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/search-icon.svg">
										</div>

										<div class="profile-menu-icon-holder" title="uitloggen" onclick="logOut()">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/logOut-icon.svg">
										</div>

									</div>
									<div class="info-section-content-holder">

										<div class="UserGearIconDialog" style="display: block;">

											<div id="UserGearIconDialogAccordion" class="UserGearIconDialogAccordion">
												<h3>DASHBOARD RESETTEN</h3>
												<div>
													<p>
														<button class="resetBtn" onclick="resetTopWidgets()">Reset Tools sectie</button>
														<button class="resetBtn" onclick="resetLowerWidgets()">Reset widgets</button>
													</p>
												</div>

												<h3>WACHTWOORD WIJZIGEN</h3>
												<div>
													<p>
														<button type="button" class="resetBtn" onclick="window.location.replace(\'forgotpassword.php?resetpassword=' . $sUsername . '\')">Wijzig wachtwoord</button>
													</p>
												</div>
											</div>
										</div>

										<div class="UserPhoneIconDialog" style="display: none;">
											Sorry op dit moment is iDEAL Checkout niet telefonisch bereikbaar.<br>
											iDEAL Checkout is iedere werkdag van 09:00 tot 12:00 uur telefonisch bereikbaar.
											U kunt altijd een mail sturen.
										</div>

										<div class="UserMailIconDialog" style="display: none;">
											<b>CONTACT OPNEMEN VIA EMAIL</b> <br>
											<br>
											Voor algemene vragen kunt u mailen naar: <br>
											<a href="mailto:info@ideal-checkout.nl">info@ideal-checkout.nl</a><br>
											<br>
											Voor vragen met betrekking tot support kunt u mailen naar: <br>
											<a href="mailto:support@ideal-checkout.nl">support@ideal-checkout.nl</a><br>

										</div>

									</div>
								</div>
							</div>
						</div>

						<div class="header-account-section" >
							<div class="header-account-section-icon" onclick="openNotificationMenu()">
								<img class="notification-icon icon svg"  height="22" width="22" src="' . $sImagePath . '/bell-icon.svg">
							</div>
							<div id="notificationMenu" class="notificationMenu" style="display: none">
								<div class="notification">
								<div class="news-section-title">
									<b>NIEUWS</b>
									<div class="closeHeaderAccSectionBtn" onclick="openNotificationMenu()">
										<img src="' . $sImagePath . '/cross-icon.svg" height="100%" >
									</div>
								</div>

									<div class="news-section-icons-holder">

										<div class="profile-menu-icon-holder hornIcon" title="nieuws" onclick="changeNewsDialog(\'hornIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/bullhorn-icon.svg">
										</div>

										<a href="https://www.ideal-checkout.nl/faq-ic/algemeen/ideal-checkout-builds" target="_blank">
										<div class="profile-menu-icon-holder flagIcon" title="updates" onclick="changeNewsDialog(\'flagIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/flag-icon.svg">
										</div>
										</a>

										<div class="profile-menu-icon-holder speechIcon" title="live helpdesk" onclick="changeNewsDialog(\'speechIcon\')">
											<img class="profile-menu-icon icon svg" src="' . $sImagePath . '/speech-icon.svg">
										</div>

									</div>


									<div class="news-section-content-holder">

										<div id="UserHornIconDialog" class="UserHornIconDialog" style="display: block">
											<div id="UserHornIconAccordion" class="UserHornIconAccordion">';




			$sUrl = 'https://www.ideal-checkout.nl/api/news/';


			$aRequest = array();
			$aRequest['license_key'] = $sLicense;

			$sPostData = json_encode($aRequest);

			$sResponse = idealcheckout_doHttpRequest($sUrl, $sPostData, true, 30, false);


			if(!empty($sResponse))
			{
				$aItems = unserialize($sResponse);

				if(sizeof($aItems))
				{
					foreach($aItems as $aNewsItem)
					{
						$sHtml .= '
						<h3>' . $aNewsItem['list_title'] . '</h3>
						<div class="news-text">
							' . $aNewsItem['detail_text'] . '
						</div>';
					}
				}
			}


												$sHtml .= '
											</div>
										</div>

										<div id="UserSpeechIconDialog" class="UserSpeechIconDialog" style="display: none; text-align: center;">
											<span style="font-size: 12px; color: #B2B2B2;">Aan live helpdesk / chat word nog hard gewerkt.</span>
										</div>

									</div>
								</div>
							</div>
						</div>

						<div class="header-account-section">
							<div class="header-account-section-icon" onclick="openInfoDialog()">
								<img class="info-icon icon svg" height="22" width="22" src="' . $sImagePath . '/bulb-icon.svg">
							</div>
							<div id="infoDialog" class="infoDialog" title="Berichten" style="display: none">
								<div class="info">
									<div class="info-section-title">
										<b>INFORMATIE</b>
										<div class="closeHeaderAccSectionBtn" onclick="openInfoDialog()">
											<img src="' . $sImagePath . '/cross-icon.svg" height="100%" >
										</div>
									</div>
									<div id="headerInfoAccordion">
										<h3>PSP & iDEAL Checkout informatie tonen</h3>
										<div>
											<p>Om de PSP en iDeal Checkout informatie te tonen klikt u bij het bovenste widget "Contact informatie" op het plus icoontje.<br>
											Wanneer dit widget niet in beeld staat kunt u deze vinden onder het kopje "TOOL"->"ADD CONTACT" in het menu.</p>
										</div>
										<h3>Tools tonen of verbergen</h3>
										<div>
											<p>Om tools te tonen volgt u de volgende stappen</p>
											<ul>
												<li>1. activeer het tools menu onder het kopje "TOOL"->"ADD TOOLS"</li>
												<li>2. vouw het nieuw verschenen "Tools sectie" open door op het plus icoontje te klikken</li>
												<li>3. in het menu kunt u diverse tools vinden om in de "Tools sectie" te slepen, onder het kopje "TOOL"->"TOOLBOX"</li>
											</ul>
										</div>
										<h3>Widgets toevoegen</h3>
										<div>
											<p>Om widgets toe te voegen kunt u een widget kiezen in het menu, bijvoorbeeld onder het kopje "STATISTIEK"->"BARS"->"Methoden Procent".<br>
											en sleep uw gewenste widget (in dit geval "Methoden Procent") naar een leeg (grijs) kader in het widget veld. <br>
											Als u een ander widget wilt vervangen met uw nieuwe widget kunt u het nieuwe widget (in dit geval "Methoden Procent") slepen over een widget dat al in het widget veld staat.</p>
										</div>
										<h3>Widgets herindelen</h3>
										<div>
											<p>Wanneer u uw widgets wilt herindelen kunt u dit doen door in een widget op het slot icoontje te klikken.<br>
											Alle widgets zullen inklappen en nu kunt u uw widget naar een nieuwe plek slepen.</p>
										</div>
										<h3>Widgets verwijderen</h3>
										<div>
											<p>Wanneer u een widget wilt weghalen zonder een nieuwe te plaatsen kunt u in het desbetreffende widget op het kruis icoontje klikken. <br>
											Hierna zal het widget uit het widget veld verdwijnen en in op de juiste plek in het menu weer verschijnen.</p>
										</div>
										<h3>Dashboard resetten</h3>
										<div>
											<p>U kunt uw dashboard indeling resetten onder "GEBRUIKER"->"TANDWIEL ICOON"->"DASHBOARD RESETTEN"<br>
											"GEBRUIKER" (persoon icoontje) kunt u rechts boven in het scherm vinden naast het bel icoontje.</p>
										</div>
									</div>
								</div>
							</div>
						</div>


					</div>
				</header>
	<div class="body">
		<div class="sidebar" id="sidebar">


			<ul>
				<ul class="sidebar-menu">


				<div class="sidebar-header-container closed">
					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/linechart-icon.svg">
						<span>STATISTIEK</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>

					<li class="treeview closed" id="menu-tree4">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/barchart-icon.svg">
						<span class="wideMenuText">Bars</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="statsChart-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Status</a>
							</div>
						</li>
						<li>
							<div id="methodsHoriChart-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Methoden Procent</a>
							</div>
						</li>
						<li>
							<div id="periodTransactions-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Transacties</a>
							</div>
						</li>

					  </ul>

					</li>

					<li class="treeview closed" id="menu-tree3">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/donutchart-icon.svg">
						<span class="wideMenuText">Donut</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="methodsDonut-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Methoden aantal</a>
							</div>
						</li>
						<li>
							<div id="methodsPercentDonut-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Methoden procent</a>
							</div>
						</li>

					  </ul>

					</li>

					<li class="treeview closed" id="menu-tree2">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/flowchart-icon.svg">
						<span class="wideMenuText">Flow</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="ordersLine-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Orders Line-chart</a>
							</div>
						</li>

						</ul>

					</li>
					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>
				<div class="sidebar-header-container closed">

					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/tasks-icon.svg">
						<span>OVERZICHT</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>

					<li class="treeview closed" id="menu-tree5">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/misc-icon.svg">
						<span class="wideMenuText">Orders</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">';

						if($iPremiumUser == 1)
						{
							//alleen weergeven bij premium users
							$sHtml .= '
							<li>
								<div id="orders-pr-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
									<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Orders</a>
								</div>
							</li>';
						}
						else
						{
							//alleen weergeven bij non-premium users
							$sHtml .= '
							<li>
								<div id="orders-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
									<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Orders</a>
								</div>
							</li>';
						}

						$sHtml .= '
					  </ul>

					</li>

					<li class="treeview closed" id="menu-tree6">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/maps-icon.svg">
						<span class="wideMenuText">Locaties</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="ordersWorldMap-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Wereld Kaart</a>
							</div>
						</li>
						<li>
							<div id="ordersEuMap-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> EU Kaart</a>
							</div>
						</li>
						<li>
							<div id="ordersNetherlandsMap-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> NL Kaart</a>
							</div>
						</li>

					  </ul>

					</li>
					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>
				<div class="sidebar-header-container closed">
					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/briefcase-icon.svg">
						<span> TOOL </span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>

					<li class="treeview closed" id="menu-tree6">
						<a href="#">
							<img class="thinMenuIcon svg"  src="' . $sImagePath . '/briefcase-icon.svg">
							<span class="wideMenuText">TOOLBOX</span>
							<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
						</a>
						<ul class="treeview-menu">

							<li>
								<div id="currencyconverter-topDraggable" title="sleep mij" onmousedown="displayTopWidget();" ontouchstart="displayTopWidget();mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
									<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> CurrencyConverter</a>
								</div>
							</li>
							<!--
							<li>
								<div id="paymentgenerator-topDraggable" title="sleep mij" onmousedown="displayTopWidget();" ontouchstart="displayTopWidget();mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
									<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> PaymentGenerator</a>
								</div>
							</li>
							-->
						</ul>
					</li>

					<li class="treeview closed" id="menu-tree6">
						<a href="#" class="disableTopWidgetButton" onclick="disableTopWidget()" style="display:block;">
							<img class="thinMenuIcon svg"  src="' . $sImagePath . '/plus-square-icon.svg">
							<span class="wideMenuText">ADD TOOLBAR</span>
						</a>
						<ul class="treeview-menu">
						</ul>
					</li>

					<li class="treeview closed" id="menu-tree6">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/pencilruler.svg">
						<span class="wideMenuText">Tools</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="paymentMethodConfigurator-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg">PSP Configurator</a>
							</div>
						</li>
					  </ul>
					</li>

					<li class="treeview closed" id="menu-tree6">
						<a href="#" class="disablePspInfoButton" onclick="disablePspInfo()" style="display:block;">
							<img class="thinMenuIcon svg"  src="' . $sImagePath . '/plus-square-icon.svg">
							<span class="wideMenuText">ADD CONTACT</span>
						</a>
						<ul class="treeview-menu">
						</ul>
					</li>

					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>
				<div class="sidebar-header-container closed">
					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/lifering-icon.svg">
						<span>SUPPORT</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>

					<li class="treeview closed" id="menu-tree7">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/extra-icon.svg">
						<span class="wideMenuText">Formulieren</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="contactForm-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> Support form</a>
							</div>
						</li>

					  </ul>

					</li>

					<li class="treeview closed" id="menu-tree8">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/extra-icon.svg">
						<span class="wideMenuText">Logs & Updates</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li>
							<div id="changelog-lowerDraggable" title="sleep mij" ontouchstart="mobileWidgetSelect();" ontouchcancel="mobileWidgetSelect();">
								<a href="#"><img class="circle-icon icon svg"  src="' . $sImagePath . '/circle.svg"> changelog</a>
							</div>
						</li>

					  </ul>

					</li>
					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>
				<div class="sidebar-header-container closed">
					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/wrench-icon.svg">
						<span>EXTRA</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>

					<li class="treeview closed" id="menu-tree12">
					  <a href="https://www.ideal-checkout.nl/ssl" target="_blank">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/ssl-icon.svg">
						<span class="wideMenuText">SSL Aanschaffen</span>

					  </a>
					</li>

					<li class="treeview closed" id="menu-tree13">
					  <a href="https://www.ideal-checkout.nl/over-ons/donatie" target="_blank">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/coffee-icon.svg">
						<span class="wideMenuText">Doneren</span>

					  </a>
					</li>';

					if($iPremiumUser != 1)
					{
						//alleen weergeven bij non-premium users
						$sHtml .= '
					<li class="treeview closed" id="menu-tree14">
					  <a href="https://www.ideal-checkout.nl/index.php?core[page]=150" target="_blank">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/premium-icon.svg">
						<span class="wideMenuText">Premium</span>
					  </a>
					</li>';

					}

					$sHtml .= '

					<li class="treeview closed" id="menu-tree15">
					  <a href="https://www.ideal-checkout.nl/over-ons/reviews" target="_blank">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/star-icon.svg">
						<span class="wideMenuText">Reviews</span>

					  </a>
					</li>
					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>
				<div class="sidebar-header-container closed">
					<div class="sidebar-header wideMenuText">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/ic-icon.svg">
						<span>IC</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">
					</div>


					<li class="treeview closed" id="menu-tree9">
					  <a href="#">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/extra-icon.svg">
						<span class="wideMenuText">Archief</span>
						<img class="treeviewArrowIcon" src="' . $sImagePath . '/treeview-menu-icon.png">

					  </a>
					  <ul class="treeview-menu">
						<li class="treeview closed" id="menu-tree10">
							<a href="https://www.ideal-checkout.nl/faq-ic/algemeen/ideal-checkout-builds" target="_blank">
								<img class="thinMenuIcon svg"  src="' . $sImagePath . '/archive-icon.svg">
								<span class="wideMenuText">Builds</span>

							</a>
						</li>

					  </ul>

					</li>

					<li class="treeview closed" id="menu-tree11">
					  <a href="https://www.ideal-checkout.nl/faq-ic" target="_blank">
						<img class="thinMenuIcon svg"  src="' . $sImagePath . '/info-icon-plain.svg">
						<span class="wideMenuText">F.A.Q</span>

					  </a>
					</li>
					<div class="treeviewGoBackDiv">
						<img class="treeviewGoBackIcon svg" src="' . $sImagePath . '/return-icon.svg">
					</div>
				</div>

				</ul>
			</ul>


		</div>
					<div class="content" id="content-data">
						<div class="content-header" id="content-header" >
							<div style="overflow: hidden;">
								<div class="content-header-left">Welkom '. $sUsernameCleaned .'</div>
								<div class="notification-text" id="overlay"><span class="text">' . $sAction . '</span></div>
								<div class="content-header-right">' . date('d-m-Y') . '</div>
							</div>
						<div class="pspInfoContainer minimized">
							<div class="pspInfoTopDeco brand-background-color">
							</div>
							<div class="pspInfoTopInfo">Contact informatie
								<div class="crossPspInfo removeLowerWidget minusLowerWidget" onclick="disablePspInfo()"><img src="' . $sImagePath . '/cross-icon.svg" height="100%" ></div>
								<div class="minusLowerWidget" onclick="toggle_header_info()"></div>
							</div>
									<div class="info-header" id="info-header">
										<img class="header-psp-logo" src="' . $sImagePath . '/web-header-logo-mobile.png">
										<div class="header-producten">
											<b>Producten</b> <br>
											iDEAL Checkout
										</div>
										<div class="header-config-dashboard">
											<b>Email Support</b> <br>
											Stuur een email met uw vraag naar: <br>
											E-mail: <a href="mailto: support@ideal-checkout.nl">support@ideal-checkout.nl</a><br>
											Bereikbaar: werkdagen van 09:00 tot 17:00 uur.
										</div>
										<div class="header-support">
											<b>Telefonisch Support</b> <br>
											Voor support kunt u bellen met: <br>
											Tel: <a href="callto:+310522746060">+31 (0522) 746 060</a><br>
											Bereikbaar: werkdagen van 09:00 tot 17:00 uur.
										</div>
										<div class="header-support">
											<b>FAQ</b> <br>
											Vind antwoord op veelgestelde vragen vragen, <br>
											in onze uitgebreide FAQ. <br>
											Deze is te vinden op: <br>
											<a href="https://www.ideal-checkout.nl/faq-ic" target="_blank">www.ideal-checkout.nl/faq</a>
										</div>
									</div>

							</div>
						</div>
						<div class="content-section">
							<div class="topWidgetContainer minimized" style="display:none;">
								<div class="topWidgetsTopDeco">
								</div>
								<div class="topWidgetsTopInfo">Tools sectie
									<div class="crossTopWidget minusLowerWidget" onclick="disableTopWidget()"><img src="' . $sImagePath . '/cross-icon.svg" height="100%" ></div>
									<div class="minusLowerWidget" onclick="hideTopWidget()"></div>
								</div>
								<div class="content-row content-row-upper" id="content-row-upper-col1">
									<div class="content-col" id="col1-topDroppable"></div>
									<div class="content-col" id="col2-topDroppable"></div>
									<div class="content-col" id="col3-topDroppable"></div>
									<div class="content-col" id="col4-topDroppable"></div>
								</div>
							</div>';

							if($iPremiumUser == 1){
								$sHtml .= '
								<div class="content-row content-row-lower">
									<div class="content-col content-row-lower-col" id="content-row-lower-col1">
										<div class="content-bigCol" id="bigCol1-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol2-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol3-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol4-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol5-lowerDroppable"></div>
										<div class="cleared"></div>
									</div>
									<div class="content-col content-row-lower-col" id="content-row-lower-col2">
										<div class="content-bigCol" id="bigCol6-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol7-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol8-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol9-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol10-lowerDroppable"></div>
										<div class="cleared"></div>
									</div>
									<div class="cleared"></div>
								</div>';
							}else{
								$sHtml .= '
								<div class="content-row content-row-lower">
									<div class="content-col content-row-lower-col" id="content-row-lower-col1">
										<div class="content-bigCol" id="bigCol1-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol2-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol3-lowerDroppable"></div>
										<div class="cleared"></div>
									</div>
									<div class="content-col content-row-lower-col" id="content-row-lower-col2">
										<div class="content-bigCol" id="bigCol6-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol7-lowerDroppable"></div>
										<div class="content-bigCol" id="bigCol8-lowerDroppable"></div>
										<div class="cleared"></div>
									</div>
									<div class="cleared"></div>
								</div>';
							}

							$sHtml .= '
						</div>
					</div>
					<div class="cleared"></div>
				</div>

				<footer>

					<div class="footer-copyright footer-left">
						<a href="http://www.ideal-checkout.nl" target="_blank">&copy; http://www.ideal-checkout.nl</a>
					</div>
					<div class="footer-pluginversion footer-right">
						Dashboard v0.9.7
					</div>
				</footer>

				<script type="text/javascript">


				$(\'.sidebar-header-container\').click(function(e)
				{
					$(this).children(\'.treeview\').each(function() {
						var iDpBlockCount = 0;

						if($(this).find(\'div\').length){
							$(this).find(\'div\').each(function() {
								if($(this).css("display") != "none"){
									iDpBlockCount = 1;
								}
							});
						}else{
							iDpBlockCount = 1;
						}

						if($(this).find(\'div\').length){
							if(iDpBlockCount == 0){
								$(this).css("display", "none");
							}else{
								$(this).css("display", "block");
							}
						}
					});
				});



				$(\'.sidebar-header\').click(function(e)
				{

					var iWidth = $(document).width();

					$(\'.sidebar-menu\').addClass(\'active\');

					if($(\'.treeview\').hasClass(\'active\')){
						$(\'.treeview-menu\').css(\'display\', \'none\')
						$(\'.treeview\').removeClass(\'active\')
						$(\'.treeview\').addClass(\'closed\')
					}

					if($(this).parent().hasClass(\'active\'))
					{
						$(\'.sidebar-header-container.active .treeview\').slideUp(\'fast\');
						$(this).parent().removeClass(\'active\').addClass(\'closed\');

						$(\'.sidebar-menu\').removeClass(\'active\');
					}
					else
					{
						$(\'.sidebar-header-container.active .treeview\').slideUp(\'fast\');
						$(this).parent().siblings().removeClass(\'active\').addClass(\'closed\');
						$(this).parent().removeClass(\'closed\').addClass(\'active\');
						$(\'.sidebar-header-container.active .treeview\').slideDown(\'fast\');

						if( iWidth <= 1024 ){
							$( ".sidebar-header-container.active li" ).first().removeClass(\'closed\').addClass(\'active\');
							$( ".sidebar-header-container.active li" ).first().css(\'display\', \'flex\');
							$( ".sidebar-header-container.active li ul" ).first().css(\'display\', \'flex\');
						}
					}
				});

				$(\'.treeview\').click(function(e)
				{

					var iWidth = $(document).width();

					if($(this).hasClass(\'active\'))
					{
						$(\'.treeview.active .treeview-menu\').css(\'display\', \'none\');
						$(this).removeClass(\'active\').addClass(\'closed\');


						if( iWidth <= 1024 ){
							/* test code VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV*/
							$(\'.sidebar-header-container.active .treeview.active\').removeClass(\'active\').addClass(\'closed\');
							$(\'.sidebar-header-container.active .treeview.closed\').css(\'display\', \'block\');
						}
					}
					else
					{
						$(\'.treeview.active .treeview-menu\').css(\'display\', \'none\');

						var oChildren = $(this).closest(\'ul\').children();

						jQuery(oChildren).children().removeClass(\'active\').addClass(\'closed\');

						$(this).removeClass(\'closed\').addClass(\'active\');
						$(\'.treeview.active .treeview-menu\').slideDown(\'fast\');


						if( iWidth <= 1024 ){
							/* test code VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV*/
							//$(\'.sidebar-header-container.active .treeview.closed\').css(\'display\', \'none\');
							$(\'.sidebar-header-container.active .treeview.active\').css(\'display\', \'flex\');
						}
					}



				});


				$(\'.sidebar-header\').click(function(e)
				{

						var iWidth = $(document).width();

						if( iWidth <= 1024 ){
							$(\'.sidebar-header\').css(\'display\', \'none\');
						}
				});

				$(\'.treeviewGoBackDiv\').click(function(e)
				{
						var iWidth = $(document).width();

						if( iWidth <= 1024 ){
							$(\'.treeview.active .treeview-menu\').css(\'display\', \'none\');
							$(\'.sidebar-header-container.active li\').css(\'display\', \'none\');
							$(\'.sidebar-header-container.active li ul li\').css(\'display\', \'flex\');
							$(\'.sidebar-header-container.active li.active\').removeClass(\'active\').addClass(\'closed\');
							$(\'.sidebar-header-container.active\').removeClass(\'active\').addClass(\'closed\');
							$(\'.sidebar-header\').css(\'display\', \'flex\');
						}
				});




				</script>
				<script>

					// close menus when clicked outside of said menu keywords:[ menu sluiten buiten klikken click ]

					var iWidth = $(document).width();

					$(document).mouseup(function (e)
					{
						var button = $(".header-account-section, .header-account-section-icon, .header-account-section-icon svg, .header-account-section-icon svg path");

						var container = $(".profileMenu");
						if(button.is(e.target))
						{
						}else if(container.css("display") != "none"){
							if (!container.is(e.target) && container.has(e.target).length === 0)
							{
								container.slideUp("fast");
							}
						}

						var container = $(".notificationMenu");
						if(button.is(e.target))
						{
						}else if(container.css("display") != "none"){
							if (!container.is(e.target) // if the target of the click isnt the container...
								&& container.has(e.target).length === 0) // ... nor a descendant of the container
							{
								container.slideUp("fast");
							}
						}

						var container = $(".infoDialog");
						if(button.is(e.target))
						{
						}else if(container.css("display") != "none"){
							if (!container.is(e.target) // if the target of the click isnt the container...
								&& container.has(e.target).length === 0) // ... nor a descendant of the container
							{
								container.slideUp("fast");
							}
						}
					});

					if( iWidth <= 1024 ){
						$(document).mouseup(function (e)
						{
							var button = $(".header-menu, .header-menu svg, .header-menu svg path");
							var container = $(".sidebar");
							if(container.css("display") != "none"){

								if(button.is(e.target))
								{
								}else if (!container.is(e.target) && container.has(e.target).length === 0)
								{
									container.slideUp("fast");
								}
							}
						});
					}else{
						$(document).mouseup(function (e)
						{
							var container = $(".sidebar-thin");
							if($(".sidebar-thin").length){
								if (!container.is(e.target) // if the target of the click isnt the container...
									&& container.has(e.target).length === 0) // ... nor a descendant of the container
								{
									$(\'.sidebar-header-container.active .treeview\').slideUp(\'fast\');
									$(this).parent().siblings().removeClass(\'active\').addClass(\'closed\');
									$(\'.sidebar-menu.active\').removeClass(\'active\').addClass(\'closed\');
									$(\'.sidebar-header-container.active\').removeClass(\'active\').addClass(\'closed\');
									$(\'.treeview.active .treeview-menu\').css(\'display\', \'none\');
									$(\'.treeview.active\').removeClass(\'active\').addClass(\'closed\');
								}
							}
						});
					}
				</script>

			</body>
		</html>';

		/*



						.siblings().removeClass(\'active\').addClass(\'closed\');









				$(\'.treeview\').click(function(e)
				{
					if($(this).hasClass(\'active\'))
					{

						console.log(\'Item was active, going to remove\');

						$(\'.treeview.active .treeview-menu\').slideUp(\'fast\');
						$(this).removeClass(\'active\').addClass(\'closed\');

						console.log(\'Active has been removed, closed was added\');


					}
					else
					{

						console.log(\'Item was closed, going to activate\');


						$(\'.treeview.active .treeview-menu\').slideUp(\'fast\');
						$(this).siblings().removeClass(\'active\').addClass(\'closed\');

						console.log(\'Active has been removed of \' + this + \', closed was added\');

						$(this).removeClass(\'closed\').addClass(\'active\');
						$(\'.treeview.active .treeview-menu\').slideDown(\'fast\');

						console.log(\'Current active element (\' + this + \') has received the class\');

					}
				});


		*/




		echo $sHtml;
		exit;

	}


?>
