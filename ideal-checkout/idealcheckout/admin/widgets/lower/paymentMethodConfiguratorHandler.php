<?php

	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');

	/*
		Create the settings array
	*/

	$aGateWays = array();
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Easy', 'type' => 'ideal', 'name' => 'iDEAL Easy');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Easy (Beveiligd)', 'type' => 'ideal', 'name' => 'iDEAL Easy (Beveiligd)');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Hosted', 'type' => 'ideal', 'name' => 'iDEAL Hosted');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Integrated', 'type' => 'ideal', 'name' => 'iDEAL Integrated');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Internet Kassa', 'type' => 'ideal', 'name' => 'iDEAL Internet Kassa');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Only', 'type' => 'ideal', 'name' => 'iDEAL Only');
	$aGateWays[] = array('code' => 'ABN Amro - iDEAL Zelfbouw', 'type' => 'ideal', 'name' => 'iDEAL Zelfbouw');
	$aGateWays[] = array('code' => 'Easy iDEAL - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Direct E-Banking / Sofortbanking');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Authorized Transfer', 'type' => 'authorizedtransfer', 'name' => 'Eenmalige machtiging');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Manual Transfer', 'type' => 'manualtransfer', 'name' => 'Handmatige overboeking');
	$aGateWays[] = array('code' => 'iDEAL Simulator - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'iDEAL Simulator - iDEAL Advanced/Integrated/Professional/Zelfbouw', 'type' => 'ideal', 'name' => 'iDEAL Advanced/Integrated/Professional/Zelfbouw');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Klarnaaccount', 'type' => 'klarnaaccount', 'name' => 'Klarnaaccount');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Klarnainvoice', 'type' => 'klarnainvoice', 'name' => 'Klarnainvoice');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Maestro', 'type' => 'maestro', 'name' => 'Maestro');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Mastercard', 'type' => 'mastercard', 'name' => 'Mastercard');
	$aGateWays[] = array('code' => 'iDEAL Simulator - MisterCash', 'type' => 'mistercash', 'name' => 'MisterCash / Bancontact');
	$aGateWays[] = array('code' => 'iDEAL Simulator - PayPal', 'type' => 'paypal', 'name' => 'PayPal');
	$aGateWays[] = array('code' => 'iDEAL Simulator - PaySafeCard', 'type' => 'paysafecard', 'name' => 'PaySafeCard');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Sofortbanking (Direct E-Banking)');
	$aGateWays[] = array('code' => 'iDEAL Simulator - Visa', 'type' => 'visa', 'name' => 'Visa');
	$aGateWays[] = array('code' => 'iDEAL Simulator - VPAY', 'type' => 'vpay', 'name' => 'VPAY');
	$aGateWays[] = array('code' => 'ING Bank - iDEAL Advanced', 'type' => 'ideal', 'name' => 'iDEAL Advanced');
	$aGateWays[] = array('code' => 'Mollie - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'Mollie - Direct E-Banking/Sofort', 'type' => 'directebanking', 'name' => 'Direct E-Banking/Sofort');
	$aGateWays[] = array('code' => 'Mollie - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'Mollie - Mistercash', 'type' => 'mistercash', 'name' => 'Bancontact/Mistercash');
	$aGateWays[] = array('code' => 'Mollie - PayPal', 'type' => 'paypal', 'name' => 'PayPal');
	$aGateWays[] = array('code' => 'Mollie - PaySafeCard', 'type' => 'paysafecard', 'name' => 'PaySafeCard');
	$aGateWays[] = array('code' => 'Online Betaalplatform - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'Online Betaalplatform - Handmatig Overboeken', 'type' => 'manualtransfer', 'name' => 'Handmatig Overboeken');
	$aGateWays[] = array('code' => 'Online Betaalplatform - MisterCash', 'type' => 'mistercash', 'name' => 'MisterCash');
	$aGateWays[] = array('code' => 'Online Betaalplatform - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'Online Betaalplatform - Paypal', 'type' => 'paypal', 'name' => 'Paypal');
	$aGateWays[] = array('code' => 'PayCheckout - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'PayCheckout - Direct E-Banking/Sofort', 'type' => 'directebanking', 'name' => 'Direct E-Banking/Sofort');
	$aGateWays[] = array('code' => 'PayCheckout - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'PayCheckout - Klarnaaccount', 'type' => 'klarnaaccount', 'name' => 'Klarnaaccount');
	$aGateWays[] = array('code' => 'PayCheckout - Klarnainvoice', 'type' => 'klarnainvoice', 'name' => 'Klarnainvoice');
	$aGateWays[] = array('code' => 'PayCheckout - Paypal', 'type' => 'paypal', 'name' => 'Paypal');
	$aGateWays[] = array('code' => 'PayCheckout - MisterCash', 'type' => 'mistercash', 'name' => 'Mistercash');
	$aGateWays[] = array('code' => 'PayCheckout - Sepa', 'type' => 'manualtransfer', 'name' => 'Sepa');
	$aGateWays[] = array('code' => 'Postcode iDEAL - iDEAL Professional', 'type' => 'ideal', 'name' => 'iDEAL Professional');
	$aGateWays[] = array('code' => 'Qantani - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'Qantani - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Direct E-Banking / Sofortbanking');
	$aGateWays[] = array('code' => 'Qantani - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'Qantani - PayPal', 'type' => 'paypal', 'name' => 'PayPal');
	$aGateWays[] = array('code' => 'Qantani - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Sofortbanking (Direct E-Banking)');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - iDEAL', 'type' => 'ideal', 'name' => 'Rabo OmniKassa 2.0 - iDEAL');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - Afterpay', 'type' => 'afterpay', 'name' => 'Rabo OmniKassa 2.0 - Afterpay');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - Maestro', 'type' => 'maestro', 'name' => 'Rabo OmniKassa 2.0 - Maestro');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - Mastercard', 'type' => 'mastercard', 'name' => 'Rabo OmniKassa 2.0 - Mastercard');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - MisterCash', 'type' => 'mistercash', 'name' => 'Rabo OmniKassa 2.0 - MisterCash');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - Paypal', 'type' => 'paypal', 'name' => 'Rabo OmniKassa 2.0 - Paypal');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - Visa', 'type' => 'visa', 'name' => 'Rabo OmniKassa 2.0 - Visa');
	$aGateWays[] = array('code' => 'Rabo OmniKassa 2.0 - VPAY', 'type' => 'vpay', 'name' => 'Rabo OmniKassa 2.0 - vPay');
	$aGateWays[] = array('code' => 'Rabobank - iDEAL Internet Kassa', 'type' => 'ideal', 'name' => 'iDEAL Internet Kassa');
	$aGateWays[] = array('code' => 'Rabobank - iDEAL Professional', 'type' => 'ideal', 'name' => 'iDEAL Professional');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - Creditcard', 'type' => 'creditcard', 'name' => 'OmniKassa - Creditcard (Visa en Mastercard gecombineerd)');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - iDEAL', 'type' => 'ideal', 'name' => 'OmniKassa - iDEAL');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - Maestro', 'type' => 'maestro', 'name' => 'OmniKassa - Maestro');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - Mastercard', 'type' => 'mastercard', 'name' => 'OmniKassa - Mastercard');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - MiniTix', 'type' => 'minitix', 'name' => 'OmniKassa - MiniTix');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - MisterCash', 'type' => 'mistercash', 'name' => 'OmniKassa - MisterCash / Bancontact');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - Visa', 'type' => 'visa', 'name' => 'OmniKassa - Visa');
	$aGateWays[] = array('code' => 'Rabo OmniKassa - VPAY', 'type' => 'vpay', 'name' => 'OmniKassa - VPAY');
	$aGateWays[] = array('code' => 'Sisow - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Direct E-Banking / Sofortbanking');
	$aGateWays[] = array('code' => 'Sisow - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'Sisow - MisterCash', 'type' => 'mistercash', 'name' => 'MisterCash / Bancontact');
	$aGateWays[] = array('code' => 'Sisow - PayPal', 'type' => 'paypal', 'name' => 'PayPal');
	$aGateWays[] = array('code' => 'Sisow - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Sofortbanking (Direct E-Banking)');
	$aGateWays[] = array('code' => 'TargetPay - Creditcard', 'type' => 'creditcard', 'name' => 'Creditcard');
	$aGateWays[] = array('code' => 'TargetPay - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Direct E-Banking / Sofortbanking');
	$aGateWays[] = array('code' => 'TargetPay - iDEAL', 'type' => 'ideal', 'name' => 'iDEAL');
	$aGateWays[] = array('code' => 'TargetPay - MisterCash', 'type' => 'mistercash', 'name' => 'MisterCash / Bancontact');
	$aGateWays[] = array('code' => 'TargetPay - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Sofortbanking (Direct E-Banking)');
	$aGateWays[] = array('code' => 'TargetPay - PaySafeCard', 'type' => 'paysafecard', 'name' => 'PaySafeCard');
	$aGateWays[] = array('code' => 'PayDutch - Direct E-Banking', 'type' => 'directebanking', 'name' => 'Direct E-Banking / Sofortbanking', );
	$aGateWays[] = array('code' => 'PayDutch - WeDeal', 'type' => 'ideal', 'name' => 'WeDeal');


	/*
		Handle the POST and REQUEST
	*/

	if(isset($_POST['sResultData']))
	{
		$sResultData = $_POST['sResultData'];
	}
	else
	{
		echo 'FAILURE';
	}

	if(isset($_POST['sGatewayCode']))
	{
		$sGatewayCode = $_POST['sGatewayCode'];
	}
	else
	{
		echo 'FAILURE';
	}

	$iIndex = array_search($sGatewayCode, array_column($aGateWays, 'code'));

	if($iIndex !== false)
	{
		$sStoreHost = $_SERVER['SERVER_NAME'];
		$aStoreHost = explode('.', $sStoreHost);
		$iStoreHost = sizeof($aStoreHost);

		$sGateway = $aGateWays[$iIndex]['type'];

		$aFiles = array();
		$aFiles[] = $sGateway . '.php';
		$aFiles[] = $sGateway . '.' . md5($sStoreHost) . '.php';

		if(strpos($sStoreHost, 'www.') === false)
		{
			$aFiles[] = $sGateway . '.' . md5('www.' . $sStoreHost) . '.php';
		}
		else
		{
			$sWwwStoreHost = str_replace('www.', '', $sStoreHost);
			$aFiles[] = $sGateway . '.' . md5($sWwwStoreHost) . '.php';
		}

		foreach($aFiles as $sFile)
		{
			if(file_exists($sFile))
			{
				unlink(IDEALCHECKOUT_PATH . '/configuration/' . $sFile);
			}

			$oHandle = fopen(IDEALCHECKOUT_PATH . '/configuration/' . $sFile, 'w');
			fwrite($oHandle, $sResultData);
		}

		echo 'SAVE SUCCESFULL';
	}
	else
	{
		echo 'FATAL ERROR: Payment Service Provider was not found.';
	}

?>