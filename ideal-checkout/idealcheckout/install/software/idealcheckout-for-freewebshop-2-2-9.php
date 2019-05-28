<?php

	class IDEALCHECKOUT_FOR_FREEWEBSHOP_2_2_9
	{
		// Return the software name
		public static function getSoftwareName()
		{
			return 'Free Webshop 2.2.9+';
		}



		// Return the software code
		public static function getSoftwareCode()
		{
			return str_replace('_', '-', substr(basename(__FILE__), 0, -4));
		}



		// Return path to main cinfig file (if any)
		public static function getConfigFile()
		{
			$aFiles[] = SOFTWARE_PATH . DS . 'includes' . DS . 'settings.inc.php';
		}



		// Return path to main cinfig file (if any)
		public static function getConfigData()
		{
			$sConfigFile = self::getConfigFile();

			// Detect DB settings via configuration file
			if(is_file($sConfigFile))
			{
				return file_get_contents($sConfigFile);
			}

			return '';
		}



		// Find default database settings
		public static function getDatabaseSettings($aSettings)
		{
			$aSettings['db_prefix'] = 'fws_';
			$sConfigData = self::getConfigData();

			if(!empty($sConfigData))
			{
				$aSettings['db_host'] = IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/dblocation = "([^"]+)";/');
				$aSettings['db_user'] = IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/dbuser = "([^"]+)";/');
				$aSettings['db_pass'] = IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/dbpass = "([^"]+)";/');
				$aSettings['db_name'] = IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/dbname = "([^"]+)";/');
				$aSettings['db_prefix'] = IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/dbtablesprefix = "([^"]+)";/');
				$aSettings['db_type'] = (version_compare(PHP_VERSION, '5.3', '>') ? 'mysqli' : 'mysql');
			}

			return $aSettings;
		}



		// See if current software == self::$sSoftwareCode
		public static function isSoftware()
		{
			$aFiles = array();
			$aFiles[] = SOFTWARE_PATH . DS . 'includes' . DS . 'settings.inc.php';

			$aFiles[] = SOFTWARE_PATH . DS . 'addons';
			$aFiles[] = SOFTWARE_PATH . DS . 'cats';
			$aFiles[] = SOFTWARE_PATH . DS . 'langs';

			$aFiles[] = SOFTWARE_PATH . DS . 'about.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'accesslogadmin.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'adminedit.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'cart.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'checkout.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'conditions.php';
			$aFiles[] = SOFTWARE_PATH . DS . 'loadmain.php';

			foreach($aFiles as $sFile)
			{
				if(!is_file($sFile) && !is_dir($sFile))
				{
					return false;
				}
			}

			return true;
		}




		// Install plugin, return text
		public static function doInstall($aSettings)
		{
			IDEALCHECKOUT_INSTALL::doInstall($aSettings);



			$sql = "SELECT `id` FROM `" . $aSettings['db_prefix'] . "payment` WHERE (`id` = '90') AND (`description` = 'iDEAL') LIMIT 1;";
			if(!idealcheckout_database_isRecord($sql)) // See if key was already added
			{
				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (90, 'iDEAL', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutideal_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met iDEAL\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 90), (2, 90), (3, 90), (5, 90), (6, 90), (7, 90), (8, 90), (9, 90);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (92, 'authorizedtransfer', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutauthorizedtransfer_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Eenmalige Machtiging\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 92), (2, 92), (3, 92), (5, 92), (6, 92), (7, 92), (8, 92), (9, 92);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (95, 'creditcard', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutcreditcard_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Creditcard\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 95), (2, 95), (3, 95), (5, 95), (6, 95), (7, 95), (8, 95), (9, 95);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (96, 'sofortbanking', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutsofortbanking_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Sofort Banking\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 96), (2, 96), (3, 96), (5, 96), (6, 96), (7, 96), (8, 96), (9, 96);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (98, 'bancontact', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutbancontact_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Bancontact\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 98), (2, 98), (3, 98), (5, 98), (6, 98), (7, 98), (8, 98), (9, 98);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (100, 'maestro', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutmaestro_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Maestro\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 100), (2, 100), (3, 100), (5, 100), (6, 100), (7, 100), (8, 100), (9, 100);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (101, 'manualtransfer', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutmanualtransfer_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met handmatige overboeking\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 101), (2, 101), (3, 101), (5, 101), (6, 101), (7, 101), (8, 101), (9, 101);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (102, 'mastercard', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutmastercard_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Mastercard\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 102), (2, 102), (3, 102), (5, 102), (6, 102), (7, 102), (8, 102), (9, 102);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (104, 'mistercash', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutmistercash_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met MisterCash\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 104), (2, 104), (3, 104), (5, 104), (6, 104), (7, 104), (8, 104), (9, 104);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (105, 'paypal', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutpaypal_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met PayPal\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 105), (2, 105), (3, 105), (5, 105), (6, 105), (7, 105), (8, 105), (9, 105);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (106, 'paysafecard', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutpaysafecard_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met PaySafeCard\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 106), (2, 106), (3, 106), (5, 106), (6, 106), (7, 106), (8, 106), (9, 106);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (108, 'visa', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutvisa_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Visa\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 108), (2, 108), (3, 108), (5, 108), (6, 108), (7, 108), (8, 108), (9, 108);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (109, 'vpay', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutvpay_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met V PAY\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 109), (2, 109), (3, 109), (5, 109), (6, 109), (7, 109), (8, 109), (9, 109);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (110, 'vpay', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutpayconiq_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Payconiq\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 110), (2, 110), (3, 110), (5, 110), (6, 110), (7, 110), (8, 110), (9, 110);";

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (111, 'klarnabuynow', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutklarnabuynow_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Klarna Buy Now\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 111), (2, 111), (3, 111), (5, 111), (6, 111), (7, 111), (8, 111), (9, 111);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (112, 'klarnaaccount', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutklarnaaccount_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Klarna Account\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 112), (2, 112), (3, 112), (5, 112), (6, 112), (7, 112), (8, 112), (9, 112);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "payment` (`id`, `description`, `code`, `system`) VALUES (113, 'klarnainvoice', '<form name=\"autosubmit\" method=\"post\" action=\"idealcheckoutklarnainvoice_checkout.php\" id=\"form1\"><input type=\"hidden\" name=\"total\" value=\"%total%\"><input type=\"hidden\" name=\"webid\" value=\"%webid%\"><input type=\"submit\" value=\"Afrekenen met Klarna Invoice\"></form>', 1);";
				idealcheckout_database_execute($sql);

				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 113), (2, 113), (3, 113), (5, 113), (6, 113), (7, 113), (8, 113), (9, 113);";
				idealcheckout_database_execute($sql);
			}

			$sql = "SHOW COLUMNS FROM `" . $aSettings['db_prefix'] . "idealcheckout_settings` LIKE `webshop_package`";
			
			if(!$aColumn = idealcheckout_database_getRecord($sql))
			{
				$sql = "INSERT INTO `" . $aSettings['db_prefix'] . "idealcheckout_settings` SET
`id` = NULL, 
`keyname` = 'webshop_package',
`value` = 'freewebshop'";
				idealcheckout_database_execute($sql);
				
			}
			else
			{
				$sql = "UPDATE `" . $aSettings['db_prefix'] . "idealcheckout_settings` SET
`value` = 'freewebshop' WHERE (`keyname` = 'webshop_package') LIMIT 1";
				idealcheckout_database_execute($sql);
			}
			
			

			return true;
		}



		// Install plugin, return text
		public static function getInstructions($aSettings)
		{
			$sHtml = '';
			$sHtml .= '<ol>';
			$sHtml .= '<li>Log in op de beheeromgeving van uw webshop.</li>';
			$sHtml .= '<li>Ga naar Administratie / Betalingsopties.</li>';
			$sHtml .= '<li>Verwijder betaalmethoden die je niet wilt aanbieden.</li>';
			$sHtml .= '</ol>';

			return $sHtml;
		}
	}

?>