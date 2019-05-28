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
	session_regenerate_id();

	$sHtml = '';
	// echo"user empty - ";

	$aFormValues = array('firstPassword' => '', 'secondPassword' => '');
	$aFormErrors = array('firstPassword' => false, 'secondPassword' => false);


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
					if(empty($_POST['firstPassword']))
					{
						$aFormErrors['firstPassword'] = true;
						$sHtml .= '<div class="errorInfo">Geef een nieuw wachtwoord op.</div>';
					}
					elseif(empty($_POST['secondPassword']))
					{
						$aFormErrors['secondPassword'] = true;
						$sHtml .= '<div class="errorInfo">Herhaal wachtwoord.</div>';
					}
					else
					{
						$aFormValues['firstPassword'] = $_POST['firstPassword'];
						$aFormValues['secondPassword'] = $_POST['secondPassword'];
						$aFormValues['username'] = $_POST['username'];

						if($aFormValues['firstPassword'] == $aFormValues['secondPassword']){
							//beide wachtwoorden het zelfde
							if((strlen($aFormValues['firstPassword']) < 8) || !preg_match('#[0-9]+#', $aFormValues['firstPassword']) || !preg_match('#[A-Z]+#', $aFormValues['firstPassword']) || !preg_match('#[a-z]+#', $aFormValues['firstPassword'])){
								$sHtml .= '<div class="errorInfo">Minimaal 8 karakters, één nummer en hoofdletter</div>';
							}else{
								$sSecurityHash = $_GET['security'];
								$sql = "SELECT * FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_users` WHERE (`username` = '" . idealcheckout_escapeSql($aFormValues['username']) . "') AND (`password` = 'resettable" . $sSecurityHash . "') AND (`enabled` = '1') LIMIT 1;";
								//if($_SESSION['idealcheckout']['user'] = idealcheckout_database_getRecord($sql))
								if($aUser = idealcheckout_database_getRecord($sql))
								{

									//reset wachtwoord hier

									$sHashSalt = '';
									$sHashWord = $sHashSalt . $_POST['firstPassword'];
									$sEncryptedPassword = hash('sha256', $sHashWord);

									$sql = "UPDATE `" . $aDatabaseSettings['prefix'] . "idealcheckout_users` SET `password` = '" . idealcheckout_escapeSql($sEncryptedPassword) . "' WHERE (`username` = '" . idealcheckout_escapeSql($aUser['username']) . "') AND (`password` = 'resettable" . $sSecurityHash . "') AND (`enabled` = '1') LIMIT 1;";
									idealcheckout_database_execute($sql);


									$sHtml .= '<div class="errorInfo">Wachtwoord opgeslagen</div>
									<script>
										setTimeout(function() {
											window.location.replace("index.php");
										}, 3000);
									</script>
									';

								}
								else
								{
									//$aFormErrors['firstPassword'] = true;
									$sHtml .= '<div class="errorInfo">Nice try</div>';
									//echo var_dump(idealcheckout_database_getRecord($sql));
								}
							}
						}else{
							//beveiligings vraag fout
							$aFormErrors['secondPassword'] = true;
							$sHtml .= '<div class="errorInfo">Wachtwoorden komen niet overeen</div>';
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
				<div class="logIn-header-logo"><a href="index.php"><img src="' . $sImagePath . '/inlog-logo.png" width="100%" title="iDEAL Checkout"></a></div>
				<div class="reset-text">Vul uw nieuwe wachtwoord in</div>
				<div class="login-form-div">
					<form class="login-form" action="" method="post" name="login">
						<input name="form" type="hidden" value="login">
						<input name="' . htmlentities($_SESSION['idealcheckout']['login:security_field']) . '" type="hidden" value="' . htmlentities($_SESSION['idealcheckout']['login:security_value']) . '">

						<div class="username-wrapper' . ($aFormErrors['firstPassword'] ? ' error' : '') . '">
							<input id="username" name="firstPassword" placeholder="Wachtwoord" type="password" value="' . htmlentities($aFormValues['firstPassword']) . '">
						</div>

						<div class="password-wrapper' . ($aFormErrors['secondPassword'] ? ' error' : '') . '">
							<input id="password" name="secondPassword" placeholder="Herhaal wachtwoord" type="password" value="' . htmlentities($aFormValues['secondPassword']) . '">
							<input class="login-icon" type="submit" value="">
						</div>
						<input id="" name="username" type="hidden" value="' . htmlentities($_GET['mail']) . '">
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
