<?php

	if(!empty($_GET['phpinfo']))
	{
		phpinfo();
		exit;
	}

	require_once(dirname(dirname(__FILE__)) . '/includes/library.php');
	require_once(dirname(__FILE__) . '/includes/install.php');
	require_once(dirname(__FILE__) . '/includes/ftp.cls.php');

	$sConfigFile = dirname(dirname(__FILE__)) . '/configuration/install.php';

	if(is_file($sConfigFile))
	{
		header('Location: step-2.php');
		exit;
	}

	$aCurlVersion = array();
	
	if(function_exists('curl_version'))
	{
		$aCurlVersion = curl_version();
	}
	
	$sPhpVersion = PHP_VERSION;
	$sOpensslVersion = (defined('OPENSSL_VERSION_TEXT') ? OPENSSL_VERSION_TEXT : '<b>Onbekend</b>');
	$sCurlVersion = (!empty($aCurlVersion['version']) ? $aCurlVersion['version'] : '<b>Onbekend</b>') . (!empty($aCurlVersion['ssl_version']) ? ' met ' . $aCurlVersion['ssl_version'] : '');
	$sMysqliVersion = (function_exists('mysqli_get_client_info') ? mysqli_get_client_info() : '<b>Onbekend</b>');

	
	$sHtml = '
	<tr>
		<td><h1>iDEAL Checkout Installatie Wizard</h1></td>
	</tr>
	<tr>
		<td>Welkom bij de installatie wizard van deze plug-in. Heeft u vragen over de installatie van de plug-ins, wilt u gebruik maken van onze installatie service, of heeft u suggesties om zaken te verbeteren, kijk dan op <a href="https://www.ideal-checkout.nl" target="_blank">www.ideal-checkout.nl</a> of neem contact met ons op.<br><br>Deze plug-in wordt u GRATIS aangeboden door <a href="https://www.ideal-checkout.nl" target="_blank">iDEAL Checkout</a>. Donaties worden zeer op prijs gesteld!</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><h3>Let op!</h3></td>
	</tr>
	<tr>
		<td>Op sommige server configuraties kan de installatie wizard of de plugin/betaalmethode niet correct werken. Enkele voorbeelden van server beperkingen die voor problemen kunnen zorgen:<br>&nbsp;
			<ul>
				<li>Verouderde PHP versie (Minimaal versie 5.3, u gebruikt versie: ' . $sPhpVersion . ')</li>
				<li>Verouderde OpenSSL bibliotheek (U gebruikt versie: ' . $sOpensslVersion . ')</li>
				<li>Ontbreken van de cUrl bibliotheek (U gebruikt versie: ' . $sCurlVersion . ')</li>
				<li>Ontbreken van de MySQLi bibliotheek (U gebruikt versie: ' . $sMysqliVersion . ')</li>
				<li>Onjuiste configuratie van de ca-bundle, <a href="http://en.wikipedia.org/wiki/Intermediate_certificate_authorities" target="_blank">klik hier</a> (nieuw venster) voor meer informatie</li>
				<li>Ontbreken van de beveiligingspatch "veiligheidspatch 5746" van 2010.</li>
			</ul>
			
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><a href="index.php?phpinfo=1" target="_blank">PHP informatie bekijken</a> <small>(Nieuw venster)</small></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><h3>Gebruik</h3></td>
	</tr>
	<tr>
		<td><p>De plugins en de bijbehorende broncode(s) en enige gelijkenis hierop, zijn en blijven intellectueel eigendom van iDEAL Checkout. Het is niet toegestaan de broncode(s) aan te passen, dan wel te verhandelen of in te zetten voor eigen gewin, zonder hierover schriftelijk overeenstemming te hebben van iDEAL Checkout en zijn eigenaren.<br><br>Op deze regels is uitsluitend Nederlands recht van toepassing.<br><br>Het gebruik van onze scripts/plug-ins is op eigen risico! Maak altijd een back-up (van uw bestanden en uw database) voor u de scripts installeert.</p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><h3>iDEAL Checkout</h3></td>
	</tr>
	<tr>
		<td><p>Wij zijn experts op gebied van PHP en online betaalsystemen, maar hebben echter beperkte kennis van het specifieke software pakket dat u gebruikt. Voor vragen over het gebruik, installatie of configuratie van uw webshop/webapplicatie kunt u dan ook het beste contact op nemen met de makers van deze software.</p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><h3>Webshop installeren en testen</h3></td>
	</tr>
	<tr>
		<td><p>Zorg dat uw webshop/webapplicatie volledig heb geinstalleerd en geconfigureerd is. Test uw webshop grondig voordat u de installatie van onze plug-in start, zodat u zeker weet dat alles goed functioneert.<br>Enkele zaken waarmee u al rekening kunt houden bij het configureren van uw webshop:</p>
		<ul>
			<li>Veel Nederlandse banken en PSP\'s ondersteunen alleen transacties in EURO\'s.</li>
			<li>Alle iDEAL transansacties worden altijd uitgevoerd in EURO\'s.</li>
			<li>De plug-ins zijn voor het NEDERLANDSE publiek ontwikkeld en bevatten daarom, waar nodig, Nederlandse teksten.</li>
		</ul></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><hr size="1"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><input onclick="javascript: window.location.href = \'step-1.php\';" type="button" value="Verder"></td>
	</tr>';

	IDEALCHECKOUT_INSTALL::output($sHtml);

?>