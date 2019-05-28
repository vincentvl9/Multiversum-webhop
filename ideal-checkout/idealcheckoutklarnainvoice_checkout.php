<?php

	// Load gateway library & settings
	require_once(dirname(__FILE__) . '/idealcheckout/includes/library.php');

	
	// Validate $_POST data
	if(isset($_POST['webid']) && isset($_POST['total']) && preg_match('/([a-zA-Z0-9\-]+)/', $_POST['webid']) && preg_match('/([0-9]+)(\.[0-9]{0,2})?/', $_POST['total']))
	{
		$aDatabaseSettings = idealcheckout_getDatabaseSettings();


		// Connect to database
		idealcheckout_database_setup();



		$sWebId = $_POST['webid'];
		$sTotal = number_format($_POST['total'], 2, '.', '');

		// See if order exists
		$sql = "SELECT * FROM `" . $aDatabaseSettings['prefix'] . "order` WHERE (`WEBID` = '" . idealcheckout_escapeSql($sWebId) . "') AND (`TOPAY` = '" . idealcheckout_escapeSql($sTotal) . "') ORDER BY `ID` DESC LIMIT 1;";
		$oRecordSet = idealcheckout_database_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . idealcheckout_database_error());

		if($oRecordSet && ($oRecord = idealcheckout_database_fetch_assoc($oRecordSet)))
		{
			// See if transaction record exists
			$sql = "SELECT `order_id`, `order_code`, `transaction_status` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`order_id` = '" . idealcheckout_escapeSql($sWebId) . "') ORDER BY `id` DESC LIMIT 1;";
			$oRecordSet = idealcheckout_database_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . idealcheckout_database_error());

			if($oRecordSet && ($oRecord2 = idealcheckout_database_fetch_assoc($oRecordSet)))
			{
				if(strcasecmp($oRecord2['transaction_status'], 'SUCCESS') === 0)
				{
					idealcheckout_output('<p>Uw betaling is met succes ontvangen.</p>');
				}
				elseif(strcasecmp($oRecord2['transaction_status'], 'PENDING') === 0)
				{
					idealcheckout_output('<p>Uw betaling is met succes ontvangen.</p>');
				}

				// Transaction not finished, restart payment
				header('Location: ' . idealcheckout_getRootUrl() . 'idealcheckout/setup.php?order_id=' . $oRecord2['order_id'] . '&order_code=' . $oRecord2['order_code']);
				exit;
			}
			else
			{
				$sStoreCode = idealcheckout_getStoreCode();
				$sGatewayCode = 'klarnainvoice';
				$sLanguageCode = 'nl'; // nl, de, en
				$sCountryCode = '';
				$sCurrencyCode = 'EUR';

				$sOrderId = $oRecord['WEBID'];
				$sOrderCode = idealcheckout_getRandomCode(32);
				$aOrderParams = array();
				$sTransactionId = idealcheckout_getRandomCode(32);
				$sTransactionCode = idealcheckout_getRandomCode(32);
				$fTransactionAmount = round($oRecord['TOPAY'], 2);
				$sTransactionDescription = idealcheckout_getTranslation($sLanguageCode, 'idealcheckout', 'Webshop order #{0}', array($sOrderId));


				$sql = "SELECT `" . $aDatabaseSettings['prefix'] . "customer`.* FROM `" . $aDatabaseSettings['prefix'] . "order` LEFT JOIN `" . $aDatabaseSettings['prefix'] . "customer` ON `" . $aDatabaseSettings['prefix'] . "order`.`CUSTOMERID` = `" . $aDatabaseSettings['prefix'] . "customer`.`ID` WHERE (`" . $aDatabaseSettings['prefix'] . "order`.`WEBID` = '" . $sOrderId . "') LIMIT 1;";
				if($aCustomer = idealcheckout_database_getRecord($sql))
				{
					$aOrderParams['customer'] = array();

					// Shipment data
					list($sStreetName, $sStreetNumber, $sExtension) = idealcheckout_mb_splitAddress($aCustomer['ADDRESS']);

					if(substr($aCustomer['COUNTRY'], 0, 1) == 'B') // Treat as BE
					{
						$sCountryCode = 'BE';
						$sCountryName = 'Belgie';
					}
					else // Treat as NL
					{
						$sCountryCode = 'NL';
						$sCountryName = 'Nederland';
					}

					$aOrderParams['customer']['shipment_company'] = $aCustomer['COMPANY'];
					$aOrderParams['customer']['shipment_name'] = $aCustomer['INITIALS'] . ' ' . trim($aCustomer['MIDDLENAME'] . ' ' . $aCustomer['LASTNAME']);
					$aOrderParams['customer']['shipment_first_name'] = $aCustomer['INITIALS'];
					$aOrderParams['customer']['shipment_last_name'] = $aCustomer['LASTNAME'];
					$aOrderParams['customer']['shipment_gender'] = '';
					$aOrderParams['customer']['shipment_date_of_birth'] = '';
					$aOrderParams['customer']['shipment_phone'] = $aCustomer['PHONE'];
					$aOrderParams['customer']['shipment_email'] = $aCustomer['EMAIL'];
					$aOrderParams['customer']['shipment_address'] = $aCustomer['ADDRESS'];
					$aOrderParams['customer']['shipment_street_name'] = $sStreetName;
					$aOrderParams['customer']['shipment_street_number'] = $sStreetNumber;
					$aOrderParams['customer']['shipment_street_number_extension'] = $sExtension;
					$aOrderParams['customer']['shipment_zipcode'] = $aCustomer['ZIP'];
					$aOrderParams['customer']['shipment_city'] = $aCustomer['CITY'];
					$aOrderParams['customer']['shipment_country_code'] = $sCountryCode;
					$aOrderParams['customer']['shipment_country_name'] = $sCountryName;


					// Payment data
					$aOrderParams['customer']['payment_company'] = $aCustomer['COMPANY'];
					$aOrderParams['customer']['payment_name'] = $aCustomer['INITIALS'] . ' ' . trim($aCustomer['MIDDLENAME'] . ' ' . $aCustomer['LASTNAME']);
					$aOrderParams['customer']['payment_first_name'] = $aCustomer['INITIALS'];
					$aOrderParams['customer']['payment_last_name'] = $aCustomer['LASTNAME'];
					$aOrderParams['customer']['payment_gender'] = '';
					$aOrderParams['customer']['payment_date_of_birth'] = '';
					$aOrderParams['customer']['payment_phone'] = $aCustomer['PHONE'];
					$aOrderParams['customer']['payment_email'] = $aCustomer['EMAIL'];
					$aOrderParams['customer']['payment_address'] = $aCustomer['ADDRESS'];
					$aOrderParams['customer']['payment_street_name'] = $sStreetName;
					$aOrderParams['customer']['payment_street_number'] = $sStreetNumber;
					$aOrderParams['customer']['payment_street_number_extension'] = $sExtension;
					$aOrderParams['customer']['payment_zipcode'] = $aCustomer['ZIP'];
					$aOrderParams['customer']['payment_city'] = $aCustomer['CITY'];
					$aOrderParams['customer']['payment_country_code'] = $sCountryCode;
					$aOrderParams['customer']['payment_country_name'] = $sCountryName;


					$aOrderParams['products'] = array();
					$aOrderParams['products'][] = array('code' => $sOrderId, 'description' => $sTransactionDescription, 'quantity' => 1, 'price_incl' => $fTransactionAmount, 'price_excl' => $fTransactionAmount, 'vat' => 0);
				}

				// Insert into #_transactions
				$sql = "INSERT INTO `" . $aDatabaseSettings['table'] . "` SET 
`id` = NULL, 
`order_id` = '" . idealcheckout_escapeSql($sOrderId) . "', 
`order_code` = '" . idealcheckout_escapeSql($sOrderCode) . "', 
`order_params` = '" . idealcheckout_escapeSql(idealcheckout_serialize($aOrderParams)) . "', 
`store_code` = " . (empty($sStoreCode) ? "NULL" : "'" . idealcheckout_escapeSql($sStoreCode) . "'") . ", 
`gateway_code` = '" . idealcheckout_escapeSql($sGatewayCode) . "', 
`language_code` = " . (empty($sLanguageCode) ? "NULL" : "'" . idealcheckout_escapeSql($sLanguageCode) . "'") . ", 
`country_code` = " . (empty($sCountryCode) ? "NULL" : "'" . idealcheckout_escapeSql($sCountryCode) . "'") . ", 
`currency_code` = '" . idealcheckout_escapeSql($sCurrencyCode) . "', 
`transaction_id` = '" . idealcheckout_escapeSql($sTransactionId) . "', 
`transaction_code` = '" . idealcheckout_escapeSql($sTransactionCode) . "', 
`transaction_params` = NULL, 
`transaction_date` = '" . idealcheckout_escapeSql(time()) . "', 
`transaction_amount` = '" . idealcheckout_escapeSql($fTransactionAmount) . "', 
`transaction_description` = '" . idealcheckout_escapeSql($sTransactionDescription) . "', 
`transaction_status` = NULL, 
`transaction_url` = NULL, 
`transaction_payment_url` = NULL, 
`transaction_success_url` = NULL, 
`transaction_pending_url` = NULL, 
`transaction_failure_url` = NULL, 
`transaction_log` = NULL;";

				idealcheckout_database_query($sql) or die('SQL: ' . $sql . '<br><br>Error: ' . idealcheckout_database_error());

				// Start payment
				header('Location: ' . idealcheckout_getRootUrl() . 'idealcheckout/setup.php?order_id=' . $sOrderId . '&order_code=' . $sOrderCode);
				exit;
			}
		}
		else
		{
			die('Invalid ordernumber or amount.');
		}
	}
	else
	{
		die('Invalid ordernumber or amount.');
	}

?>