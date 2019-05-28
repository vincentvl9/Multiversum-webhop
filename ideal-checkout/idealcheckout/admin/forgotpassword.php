<?php


	@ini_set('display_errors', 1);
	@ini_set('display_startup_errors', 1);
	@error_reporting(E_ALL | E_STRICT);
	@error_reporting(E_ALL);


	define('IDEALCHECKOUT_PATH', dirname(__DIR__));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');


	$aDatabaseSettings = idealcheckout_getDatabaseSettings();
	$sImagePath = idealcheckout_getRootUrl(). 'images';


	$sql = "SELECT `value` FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_settings` WHERE (`key_name` = 'license') LIMIT 1";
	$sLicense = idealcheckout_database_getValue($sql);

	$sPhpPath = IDEALCHECKOUT_PATH . '/admin/php/';

	require_once($sPhpPath . 'index.php');

	// Load core library
	require_once('php/index.php');


	if(is_file(__DIR__ . '/debug.php'))
	{
		include_once(__DIR__ . '/debug.php');
	}


	session_set_cookie_params(3600, '/', '', false, true);
	session_start();

	$sHtml = '';
	// echo"user empty - ";

	$aFormValues = array('username' => '', 'controll' => '');
	$aFormErrors = array('username' => false, 'controll' => false);

	if(!empty($_GET['resetpassword'])){
		$aFormValues['username'] = $_GET['resetpassword'];
	}
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
					elseif(empty($_POST['controll']))
					{
						$aFormErrors['controll'] = true;
						$sHtml .= '<div class="errorInfo">Beantwoord de beveiligings vraag.</div>';
					}
					else
					{
						$sEnteredUsername = $_POST['username'];
						$sMathQuestion = $_POST['controll'];
						$sMathAnswer = $_POST['controllAnswer'];

						if($sMathAnswer == $sMathQuestion){
							//beveiligings vraag goed

							$sql = "SELECT * FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_users` WHERE (`username` = '" . idealcheckout_escapeSql($sEnteredUsername) . "') AND (`enabled` = '1') LIMIT 1;";
							//if($_SESSION['idealcheckout']['user'] = idealcheckout_database_getRecord($sql))
							if($aUser = idealcheckout_database_getRecord($sql))
							{

								//mail script hier

								$sFromName = 'iDEAL Checkout - Contact';
								$sFromMail = $aUser['username'];
								$sToMail = $sFromMail;

								$iRandNumber = rand(0, 9999) . rand(0, 9999) . rand(0, 9999);
								$sSecurityHash = hash('sha256', $iRandNumber);

								if(!empty($_GET['resetpassword'])){
									$sSubject =  'iDEAL Checkout admin wachtwoord wijzigen';
								}else{
									$sSubject =  'iDEAL Checkout admin wachtwoord reset';
								}

								$sAttachment = '';

								$sLink = idealcheckout_getRootUrl() . 'resetpassword.php?mail=' . $aUser['username'] . '&security=' . $sSecurityHash;
								//$sLink = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://' . str_replace('forgotpassword', 'resetpassword', $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . '?mail=' . $aUser['username'] . '&security=' . $sSecurityHash;

								if(!empty($_GET['resetpassword'])){
									$sMessage = 'U heeft een verzoek ingediend om het wachtwoord aan te passen.<br>Klik <a href="' . $sLink . '">HIER</a> om uw wachtwoord te wijzigen <br><br><br>Werkt de link niet? kopieer dan deze url in de browser: ' . $sLink . '';
								}else{
									$sMessage = 'U heeft aangegeven uw wachtwoord vergeten te zijn.<br>Klik <a href="' . $sLink . '">HIER</a> om uw wachtwoord te resetten <br><br><br>Werkt de link niet? kopieer dan deze url in de browser: ' . $sLink . '';
								}

								clsMail::sendHtml($sFromName, $sFromMail, $sToMail, $sSubject, $sMessage, array());

								$sHtml .= '<div class="errorInfo">E-mail met instructies verzonden.</div>';

								$sql = "UPDATE `" . $aDatabaseSettings['prefix'] . "idealcheckout_users` SET `password` = 'resettable" . $sSecurityHash . "' WHERE (`username` = '" . idealcheckout_escapeSql($aUser['username']) . "') AND (`enabled` = '1') LIMIT 1;";
								idealcheckout_database_execute($sql);
							}
							else
							{
								$aFormErrors['username'] = true;
								$sHtml .= '<div class="errorInfo">Gebruiker niet gevonden.</div>';
							}
						}else{
							//beveiligings vraag fout
							$aFormErrors['controll'] = true;
							$sHtml .= '<div class="errorInfo">Beveiligings vraag verkeerd beantwoord.</div>';
						}
					}
				}
			}
		}
	}

	$_SESSION['idealcheckout']['login:security_field'] = idealcheckout_getRandomCode(16);
	$_SESSION['idealcheckout']['login:security_value'] = idealcheckout_getRandomCode(16);

	$backgroundNumber = rand(1, 13);

	$iControllNumber1 = rand(1, 9);
	$iControllNumber2 = rand(1, 9);
	$iControllAnswer = $iControllNumber1 + $iControllNumber2;

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
				<div class="logIn-header-logo"><a href="index.php"><img src="' . $sImagePath . '/inlog-logo.png" width="100%" title="iDEAL Checkout"></a></div>';
			if(!empty($_GET['resetpassword'])){
				$sHtml .='
				<div class="reset-text">Om het wachtwoord te wijzigen, beantwoord onderstaande beveiligingsvraag</div>';
			}else{
				$sHtml .='
				<div class="reset-text">Vul onderstaande velden in om je wachtwoord te resetten</div>';
			}

			$sHtml .='
				<div class="login-form-div">
					<form class="login-form" id="loginform" action="" method="post" name="login">
						<input name="form" type="hidden" value="login">
						<input name="' . htmlentities($_SESSION['idealcheckout']['login:security_field']) . '" type="hidden" value="' . htmlentities($_SESSION['idealcheckout']['login:security_value']) . '">

						<div class="username-wrapper' . ($aFormErrors['username'] ? ' error' : '') . '">
							<input id="username" name="username" placeholder="E-mailadres" type="text" value="' . htmlentities($aFormValues['username']) . '" autocomplete="off">
						</div>

						<div class="password-wrapper' . ($aFormErrors['controll'] ? ' error' : '') . '">
							<input id="password" name="controll" placeholder="wat is: ' . $iControllNumber1 . ' + ' . $iControllNumber2 . '" type="text" value="' . htmlentities($aFormValues['controll']) . '">
							<input class="login-icon" type="submit" value="">
						</div>
						<input id="controllAnswer" name="controllAnswer" type="hidden" value="' . htmlentities($iControllAnswer) . '">
					</form>
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


?>
