<?php

	class IDEALCHECKOUT_INSTALL
	{
		public static $sSoftwareCode = false;
		public static $sProviderCode = false;

		// ...
		public static function setSoftware($sSoftwareCode = false)
		{
			self::$sSoftwareCode = false;

			if($sSoftwareCode === false)
			{
				// Try to autodetect code
				$sSoftwareCode = self::getSoftwareCode();
			}

			if($sSoftwareCode !== false)
			{
				$sSoftwarePath = self::getSoftwarePath();
				$sFile = self::getFileName($sSoftwareCode);

				if(is_file($sSoftwarePath . '/' . $sFile))
				{
					require_once($sSoftwarePath . '/' . $sFile);
				}
				else
				{
					$sSoftwareCode = false;
				}
			}

			if($sSoftwareCode)
			{
				self::$sSoftwareCode = $sSoftwareCode;
				return self::getClassName($sSoftwareCode);
			}

			return false;
		}

		// ...
		public static function setProvider($sProviderCode = false)
		{
			if($sProviderCode !== false)
			{
				$sProviderPath = self::getProviderPath();
				$sFile = self::getFileName($sProviderCode);

				if(is_file($sProviderCode . '/' . $sFile))
				{
					require_once($sProviderCode . '/' . $sFile);
				}
				else
				{
					$sSoftwareCode = false;
				}
			}

			self::$sProviderCode = $sProviderCode;
			return !empty($sProviderCode);
		}

		// Detect current software code (returns FALSE on failure)
		public static function getSoftwareCode()
		{
			if(!empty(self::$sSoftwareCode))
			{
				return self::$sSoftwareCode;
			}

			$sSoftwarePath = self::getSoftwarePath();
			$aFiles = self::getFiles($sSoftwarePath);

			if(sizeof($aFiles) > 1)
			{
				// Set files in reverse order so latest version numbers are checked first
				usort($aFiles, 'strnatcasecmp');
				$aFiles = array_reverse($aFiles);
			}

			foreach($aFiles as $iIndex => $sFile)
			{
				if(strpos($sFile, '.readme') !== false)
				{
					// ignore HTML files
				}
				else
				{
					require_once($sSoftwarePath . '/' . $sFile);

					$sClassName = self::getClassName($sFile);

					// Test software
					if(class_exists($sClassName, false))
					{
						$aTest = call_user_func($sClassName . '::isSoftware');

						if(is_array($aTest))
						{
							$bSoftwareFound = array_shift($aTest);
						}
						else
						{
							$bSoftwareFound = !empty($aTest);
						}

						if($bSoftwareFound)
						{
							return call_user_func($sClassName . '::getSoftwareCode');
						}
					}
				}
			}

			return false;
		}

		// ...
		public static function getSoftwareClass()
		{
			if(self::$sSoftwareCode)
			{
				return self::getClassName(self::$sSoftwareCode);
			}

			return false;
		}

		// ...
		public static function getSoftwareFile()
		{
			if(self::$sSoftwareCode)
			{
				return self::getFileName(self::$sSoftwareCode);
			}

			return false;
		}

		// ...
		public static function getSoftwarePath()
		{
			return IDEALCHECKOUT_PATH . '/install/software';
		}




		// Find available provider codes (/idealcheckout/install/providers/[code].php)
		public static function getProviderCodes()
		{
			$sProviderPath = self::getProviderPath();
			$aFiles = self::getFiles($sProviderPath);

			$aCodes = array();

			foreach($aFiles as $aFile)
			{
				$aCodes[] = substr($aFile, 0, -4);
			}

			return $aCodes;
		}

		// Find
		public static function getProviderPath()
		{
			return IDEALCHECKOUT_PATH . '/install/provider';
		}

		public static function getWebhookUrl()
		{
			return idealcheckout_getRootUrl(2) . 'idealcheckout/report.php';
		}


		public static function getPaymentMethods()
		{
			$aPaymentMethods = array();
			$aPaymentMethods[] = array('name' => 'iDEAL', 'code' => 'ideal');
			$aPaymentMethods[] = array('name' => 'Eenmalige Machtiging', 'code' => 'authorizedtransfer');
			// $aPaymentMethods[] = array('name' => 'Betalen bij ontvangst', 'code' => 'cashondelivery');
			$aPaymentMethods[] = array('name' => 'Creditcard', 'code' => 'creditcard');
			$aPaymentMethods[] = array('name' => 'Klarnaaccount', 'code' => 'klarnaaccount');
			$aPaymentMethods[] = array('name' => 'Klarnainvoice', 'code' => 'klarnainvoice');
			$aPaymentMethods[] = array('name' => 'Klarnabuynow', 'code' => 'klarnabuynow');
			$aPaymentMethods[] = array('name' => 'Maestro', 'code' => 'maestro');
			$aPaymentMethods[] = array('name' => 'Handmatig overboeken', 'code' => 'manualtransfer');
			$aPaymentMethods[] = array('name' => 'Mastercard', 'code' => 'mastercard');
			$aPaymentMethods[] = array('name' => 'Bancontact', 'code' => 'bancontact');
			$aPaymentMethods[] = array('name' => 'Payconiq', 'code' => 'payconiq');
			$aPaymentMethods[] = array('name' => 'PayPal', 'code' => 'paypal');
			$aPaymentMethods[] = array('name' => 'PaySafeCard', 'code' => 'paysafecard');
			$aPaymentMethods[] = array('name' => 'Sofort Banking', 'code' => 'sofortbanking');
			// $aPaymentMethods[] = array('name' => 'Acceptgiro', 'code' => 'transactionform');
			$aPaymentMethods[] = array('name' => 'Visa', 'code' => 'visa');
			$aPaymentMethods[] = array('name' => 'V-Pay', 'code' => 'vpay');

			return $aPaymentMethods;
		}




		// ...
		public static function getClassName($sCode)
		{
			if(strpos($sCode, '.php') !== false)
			{
				$sCode = substr($sCode, 0, -4);
			}

			return strtoupper(str_replace('-', '_', $sCode));
		}

		// ...
		public static function getFileName($sCode)
		{
			return strtolower(str_replace('_', '-', $sCode)) . '.php';
		}

		// ...
		public static function getFiles($sFolderPath, $bShowHiddenFiles = false, $bAddPath = false)
		{
			$aFiles = array();

			if(is_dir($sFolderPath))
			{
				if($oHandle = opendir($sFolderPath))
				{
					while(($sFile = readdir($oHandle)) !== false)
					{
						if($sFile == '.')
						{
							// Current Dir
						}
						elseif($sFile == '..')
						{
							// Parent Dir
						}
						else
						{
							$sFirstChar = substr($sFile, 0, 1);

							if($bShowHiddenFiles || !in_array($sFirstChar, array('.', '_', '-')))
							{
								if(is_file($sFolderPath . '/' . $sFile))
								{
									$aFiles[] = ($bAddPath ? $sFolderPath . '/' : '') . $sFile;
								}
							}
						}
					}

					if(sizeof($aFiles) > 1)
					{
						usort($aFiles, 'strcasecmp');
					}

					closedir($oHandle);
				}
			}

			return $aFiles;
		}

		// ...
		public static function getFolders($sFolderPath, $bShowHiddenFolders = false, $bAddPath = false)
		{
			$aFolders = array();

			if(is_dir($sFolderPath))
			{
				if($oHandle = opendir($sFolderPath))
				{
					while(($sFolder = readdir($oHandle)) !== false)
					{
						if($sFolder == '.')
						{
							// Current Dir
						}
						elseif($sFolder == '..')
						{
							// Parent Dir
						}
						else
						{
							$sFirstChar = substr($sFolder, 0, 1);

							if($bShowHiddenFolders || !in_array($sFirstChar, array('.', '_', '-')))
							{
								if(is_dir($sFolderPath . '/' . $sFolder))
								{
									$aFolders[] = ($bAddPath ? $sFolderPath . '/' : '') . $sFolder;
								}
							}
						}
					}

					if(sizeof($aFolders) > 1)
					{
						usort($aFolders, 'strcasecmp');
					}

					closedir($oHandle);
				}
			}

			return $aFolders;
		}



		public static function getFilesAndFolders($sSoftwareClass = false)
		{
			// Verify read/write privileges
			$aFilesAndFolders = array();

			// $aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'certificates';
			// $aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'authorizedtransfer.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'bancontact.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'creditcard.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'database.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'ideal.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'klarnaaccount.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'klarnabuynow.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'klarnainvoice.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'maestro.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'manualtransfer.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'mastercard.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'payconiq.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'paypal.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'paysafecard.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'sofortbanking.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'visa.php';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'configuration' . DS . 'vpay.php';

			if(false)
			{
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install';
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install' . DS . 'index.php';
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install' . DS . 'step-1.php';
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install' . DS . 'step-2.php';
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install' . DS . 'step-3.php';
				$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'install' . DS . 'step-4.php';
			}

			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'temp';
			$aFilesAndFolders[] = IDEALCHECKOUT_PATH . DS . 'temp' . DS . 'cache' . DS . 'issuers.cache';

			if(!empty($sSoftwareClass))
			{
				$sCallable = $sSoftwareClass . '::getFilesAndFolders';

				if(is_callable($sCallable))
				{
					$a = call_user_func($sSoftwareClass . '::getFilesAndFolders');

					if(is_array($a) && sizeof($a))
					{
						foreach($a as $sPath)
						{
							$aFilesAndFolders[] = $sPath;
						}
					}
				}
			}

			return $aFilesAndFolders;
		}


		// ...
		public static function getAdminFolder($aAdminSubFiles = false, $aAdminSubFolders = false)
		{
			$sAdminFolder = false;

			if(!is_array($aAdminSubFiles))
			{
				$aAdminSubFiles = array();
			}

			if(!is_array($aAdminSubFolders))
			{
				$aAdminSubFolders = array();
			}

			$aRootFolders = self::getFolders(SOFTWARE_PATH);

			foreach($aRootFolders as $sAdminFolder)
			{
				foreach($aAdminSubFiles as $sFile)
				{
					if(!is_file(SOFTWARE_PATH . '/' . $sAdminFolder . '/' . $sFile))
					{
						$sAdminFolder = false;
						break;
					}
				}

				if($sAdminFolder)
				{
					foreach($aAdminSubFolders as $sFolder)
					{
						if(!is_dir(SOFTWARE_PATH . '/' . $sAdminFolder . '/' . $sFolder))
						{
							$sAdminFolder = false;
							break;
						}
					}

					if($sAdminFolder)
					{
						return $sAdminFolder;
					}
				}
			}

			return false;
		}


		// Install plugin, return text
		public static function doInstall(&$aSettings)
		{
			self::setLog('Running installation.', __FILE__, __LINE__);

			// Set default timezone
			if(function_exists('date_default_timezone_set'))
			{
				date_default_timezone_set('Europe/Amsterdam');
			}

			$sCurrentTime = time();

			// Set read/write privileges or output instructions
			$aFilesAndFolders = self::getFilesAndFolders($aSettings['code']);

			foreach($aFilesAndFolders as $sFolder)
			{
				self::setLog('Changing CHMOD for: ' . $sFolder, __FILE__, __LINE__);
				self::chmodFolder($sFolder);
			}

			// Create #_idealcheckout table
			self::setLog('Creating database table #_idealcheckout', __FILE__, __LINE__);

			$sql = "CREATE TABLE IF NOT EXISTS `" . $aSettings['db_prefix'] . "idealcheckout_transactions` (
`id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
`order_id` VARCHAR(64) DEFAULT NULL,
`order_code` VARCHAR(64) DEFAULT NULL,
`order_params` TEXT DEFAULT NULL,
`store_code` VARCHAR(64) DEFAULT NULL,
`gateway_code` VARCHAR(64) DEFAULT NULL,
`language_code` VARCHAR(2) DEFAULT NULL,
`country_code` VARCHAR(2) DEFAULT NULL,
`currency_code` VARCHAR(3) DEFAULT NULL,
`transaction_id` VARCHAR(64) DEFAULT NULL,
`transaction_code` VARCHAR(64) DEFAULT NULL,
`transaction_params` TEXT DEFAULT NULL,
`transaction_date` INT(11) UNSIGNED DEFAULT NULL,
`transaction_amount` DECIMAL(10, 2) UNSIGNED DEFAULT NULL,
`transaction_description` VARCHAR(100) DEFAULT NULL,
`transaction_status` VARCHAR(16) DEFAULT NULL,
`transaction_url` TEXT DEFAULT NULL,
`transaction_payment_url` VARCHAR(255) DEFAULT NULL,
`transaction_success_url` VARCHAR(255) DEFAULT NULL,
`transaction_pending_url` VARCHAR(255) DEFAULT NULL,
`transaction_failure_url` VARCHAR(255) DEFAULT NULL,
`transaction_notify_url` VARCHAR(255) DEFAULT NULL,
`transaction_log` TEXT DEFAULT NULL,
PRIMARY KEY (`id`));";
			idealcheckout_database_execute($sql);




			$sql = "CREATE TABLE IF NOT EXISTS `" . $aSettings['db_prefix'] . "idealcheckout_ssl` (
`id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
`file_name` VARCHAR(255) DEFAULT NULL,
`file_content` TEXT DEFAULT NULL,
`enabled` TINYINT(1) DEFAULT NULL,
PRIMARY KEY (`id`));";
			idealcheckout_database_execute($sql);


			$sql = "SHOW COLUMNS FROM `" . $aSettings['db_prefix'] . "idealcheckout_ssl` LIKE `enabled`";

			if(!$aColumn = idealcheckout_database_getRecord($sql))
			{
				$sql = "ALTER TABLE `" . $aSettings['db_prefix'] . "idealcheckout_ssl` ADD COLUMN `enabled` TINYINT AFTER `file_content`";
				idealcheckout_database_execute($sql);
			}

			$sql = "CREATE TABLE IF NOT EXISTS `" . $aSettings['db_prefix'] . "idealcheckout_settings` (
`id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
`key_name` VARCHAR(255) DEFAULT NULL,
`value` VARCHAR(255) DEFAULT NULL,
PRIMARY KEY (`id`));";
			idealcheckout_database_execute($sql);


			$sql = "CREATE TABLE IF NOT EXISTS `" . $aSettings['db_prefix'] . "idealcheckout_users` (
`id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
`username` VARCHAR(255) DEFAULT NULL,
`password` VARCHAR(255) DEFAULT NULL,
`email` VARCHAR(255) DEFAULT NULL,
`enabled` TINYINT(1) DEFAULT NULL,
`access` TEXT DEFAULT NULL,
PRIMARY KEY (`id`));";
			idealcheckout_database_execute($sql);


			self::setLog('Primary installation completed.', __FILE__, __LINE__);
		}


		// Install plugin, return text
		public static function createUser(&$aFormValues)
		{
			self::setLog('Creating user.', __FILE__, __LINE__);

			// Set default timezone
			if(function_exists('date_default_timezone_set'))
			{
				date_default_timezone_set('Europe/Amsterdam');
			}

			$sCurrentTime = time();

			// Create #_idealcheckout table
			self::setLog('Creating user record #_idealcheckout_users', __FILE__, __LINE__);

			$sHashSalt = '';
			$sEncryptedPassword = hash('sha256', $sHashSalt . $aFormValues['backend_pass']);

			$sql = "SELECT `id` FROM `" . $aFormValues['db_prefix'] . "idealcheckout_users` WHERE (`email` = '" . idealcheckout_escapeSql($aFormValues['backend_email']) . "') ORDER BY `id` DESC LIMIT 1";

			// User doesnt exist already
			if(!idealcheckout_database_getRecord($sql))
			{
				// Insert user
				$sql = "INSERT INTO `" . $aFormValues['db_prefix'] . "idealcheckout_users` SET
`id` = NULL,
`username` = '" . idealcheckout_escapeSql($aFormValues['backend_email']) . "',
`password` = '" . idealcheckout_escapeSql($sEncryptedPassword) . "',
`email` = '" . idealcheckout_escapeSql($aFormValues['backend_email']) . "',
`enabled` = '1'";


				idealcheckout_database_execute($sql);

				return true;
			}
			else
			{
				// Alter existing user
				$sql = "UPDATE `" . $aFormValues['db_prefix'] . "idealcheckout_users` SET
`username` = '" . idealcheckout_escapeSql($aFormValues['backend_email']) . "',
`password` = '" . idealcheckout_escapeSql($sEncryptedPassword) . "',
`enabled` = '1' WHERE (`email` = '" . idealcheckout_escapeSql($aFormValues['backend_email']) . "') ORDER BY `id` DESC LIMIT 1";
				idealcheckout_database_execute($sql);

				return true;
			}

			return true;
			self::setLog('User added successfully.', __FILE__, __LINE__);
		}


		// Install plugin, return text
		public static function saveLicense(&$aFormValues)
		{
			self::setLog('Saving license.', __FILE__, __LINE__);

			// Set default timezone
			if(function_exists('date_default_timezone_set'))
			{
				date_default_timezone_set('Europe/Amsterdam');
			}

			$sCurrentTime = time();

			// Create #_idealcheckout table
			self::setLog('Saving license in #_idealcheckout_settings', __FILE__, __LINE__);


			$sql = "SELECT `value` FROM `" . $aFormValues['db_prefix'] . "idealcheckout_settings` WHERE (`key_name` = 'license') ORDER BY `id` DESC LIMIT 1";

			if(!$aRecord = idealcheckout_database_getRecord($sql))
			{
				$sql = "INSERT INTO `" . $aFormValues['db_prefix'] . "idealcheckout_settings` SET
`id` = NULL,
`key_name` = 'license',
`value` = '" . idealcheckout_escapeSql($aFormValues['license']) . "'";
				idealcheckout_database_execute($sql);

				return true;

			}
			else
			{
				$sql = "UPDATE `" . $aFormValues['db_prefix'] . "idealcheckout_settings` SET
`value` = '" . idealcheckout_escapeSql($aFormValues['license']) . "' WHERE (`key_name` = 'license') LIMIT 1";
				idealcheckout_database_execute($sql);

				return true;
			}

			return true;
			self::setLog('License saved successfully.', __FILE__, __LINE__);
		}



		// Verify server firewall
		public static function testFirewall($sSoftwareCode = false)
		{
			if($sSoftwareCode === false)
			{
				$sSoftwareCode = self::getSoftwareCode();
			}

			if(idealcheckout_getDebugMode())
			{
				idealcheckout_log('Testing firewall through httpRequest to www.ideal-checkout.nl', __FILE__, __LINE__);
			}

			$sFirewallCheck = @idealcheckout_doHttpRequest('https://www.ideal-checkout.nl/ping.php', array('url' => idealcheckout_getRootUrl(2), 'software' => $sSoftwareCode), true);

			if(idealcheckout_getDebugMode())
			{
				idealcheckout_log('Done testing firewall through httpRequest to www.ideal-checkout.nl, returned:' . $sFirewallCheck, __FILE__, __LINE__);
			}

			if(!empty($sFirewallCheck))
			{
				self::setLog('Firewall check completed, status: OK', __FILE__, __LINE__);
				return true;
			}
			else
			{
				self::setLog('Firewall check failed, status: NOK', __FILE__, __LINE__);
			}

			return false;
		}


		// Verify given FTP settings
		public static function testFtpSettings(&$aFormValues, &$aFormErrors)
		{
			if(!empty($aFormValues['ftp_host']) && !empty($aFormValues['ftp_user']) && !empty($aFormValues['ftp_pass']))
			{
				if(clsFtp::test($aFormValues['ftp_host'], $aFormValues['ftp_user'], $aFormValues['ftp_pass'], $aFormValues['ftp_port'], $aFormValues['ftp_passive']))
				{
					self::setLog('FTP check completed, status: OK', __FILE__, __LINE__);
					return true;
				}
				else
				{
					self::setLog('FTP check failed, status: NOK', __FILE__, __LINE__);
				}
			}

			return false;
		}


		// Verify given DB settings
		public static function testDatabaseSettings(&$aFormValues, &$aFormErrors = array())
		{
			if(!empty($aFormValues['db_host']) && !empty($aFormValues['db_user']) && !empty($aFormValues['db_name']) && !empty($aFormValues['db_type']))
			{
				if(strcasecmp($aFormValues['db_type'], 'mysqli') === 0)
				{
					if(idealcheckout_getDebugMode())
					{
						idealcheckout_log('Trying to connect to the database using mysqli with credentials:' . "\r\n" . $aFormValues['db_host'] . ' ' . $aFormValues['db_user'] . ' **password**', __FILE__, __LINE__);
					}

					$oDatabase = @mysqli_connect($aFormValues['db_host'], $aFormValues['db_user'], $aFormValues['db_pass']);

					if(idealcheckout_getDebugMode())
					{
						idealcheckout_log('Succesfully established connection to the database with mysqli', __FILE__, __LINE__);
					}

					if($oDatabase)
					{
						$aFormValues['db_success'] = idealcheckout_getTranslation(false, 'install', 'Database host, user and password verified.');

						if(@mysqli_select_db($oDatabase, $aFormValues['db_name']))
						{
							$aFormValues['db_success'] .= "\r\n" . idealcheckout_getTranslation(false, 'install', 'Database name verified.');

							if(empty($aFormValues['db_prefix']))
							{
								self::setLog('Database check completed, status: OK', __FILE__, __LINE__);
								return true;
							}
							else
							{
								$aTables = array();
								$iPrefixFound = 0;
								$bPrefixUnderscore = (strpos($aFormValues['db_prefix'], '_') !== false);

								$sQuery = "SHOW TABLES FROM `" . $aFormValues['db_name'] . "`;";
								$oRecordset = mysqli_query($oDatabase, $sQuery);

								if($aHeader = mysqli_fetch_assoc($oRecordset))
								{
									foreach($aHeader as $k => $v)
									{
										while($aRecord = mysqli_fetch_assoc($oRecordset))
										{
											$aTables[] = $aRecord[$k];

											if(strpos($aRecord[$k], $aFormValues['db_prefix']) === 0)
											{
												if(($bPrefixUnderscore === false) && ($iPrefixFound < 1) && (strpos($aRecord[$k], $aFormValues['db_prefix'] . '_') === 0))
												{
													self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
													self::setLog('Database prefix should end with an underscore.', __FILE__, __LINE__);

													$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database prefix should end with an underscore (Expecting prefix "{0}_" instead of "{0}").', array($aFormValues['db_prefix']));
													return false;
												}
												else
												{
													$iPrefixFound++;
												}
											}
										}

										break;
									}
								}

								if(sizeof($aTables) > 1)
								{
									usort($aTables, 'strcasecmp');
								}

								if($iPrefixFound > 2)
								{
									self::setLog('Database check completed, status: OK', __FILE__, __LINE__);

									$aFormValues['db_success'] .= "\r\n" . idealcheckout_getTranslation(false, 'install', 'Database prefix is verified (Found "{0}" {1}x).', array($aFormValues['db_prefix'], $iPrefixFound));
									return true;
								}
								else
								{
									self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
									self::setLog('Database prefix is invalid.', __FILE__, __LINE__);

									$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database prefix is invalid (Found "{0}" {1}x).', array($aFormValues['db_prefix'], $iPrefixFound));
								}
							}
						}
						else
						{
							self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
							self::setLog('Database name is invalid.', __FILE__, __LINE__);

							$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database name is invalid.');
						}
					}
					else
					{
						self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
						self::setLog('Database host, user and/or password are invalid.', __FILE__, __LINE__);

						$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database host, user and/or password are invalid.');
					}
				}
				else // if(strcasecmp($aFormValues['db_type'], 'mysql') === 0)
				{

					if(idealcheckout_getDebugMode())
					{
						idealcheckout_log('Trying to connect to the database using mysql with credentials:' . "\r\n" . $aFormValues['db_host'] . ' ' . $aFormValues['db_user'] . ' **password**', __FILE__, __LINE__);
					}


					$oDatabase = @mysql_connect($aFormValues['db_host'], $aFormValues['db_user'], $aFormValues['db_pass'], true);

					if(idealcheckout_getDebugMode())
					{
						idealcheckout_log('Succesfully established connection to the database with mysql', __FILE__, __LINE__);
					}

					if($oDatabase)
					{
						$aFormValues['db_success'] = idealcheckout_getTranslation(false, 'install', 'Database host, user and password are verified.');

						if(@mysql_select_db($aFormValues['db_name'], $oDatabase))
						{
							$aFormValues['db_success'] .= "\r\n" . idealcheckout_getTranslation(false, 'install', 'Database name is verified.');

							if(empty($aFormValues['db_prefix']))
							{
								self::setLog('Database check completed, status: OK', __FILE__, __LINE__);
								return true;
							}
							else
							{
								$aTables = array();
								$iPrefixFound = 0;
								$bPrefixUnderscore = (strpos($aFormValues['db_prefix'], '_') !== false);

								$sQuery = "SHOW TABLES FROM `" . $aFormValues['db_name'] . "`;";
								$oRecordset = mysql_query($sQuery, $oDatabase);

								if($aHeader = mysql_fetch_assoc($oRecordset))
								{
									foreach($aHeader as $k => $v)
									{
										while($aRecord = mysql_fetch_assoc($oRecordset))
										{
											$aTables[] = $aRecord[$k];

											if(strpos($aRecord[$k], $aFormValues['db_prefix']) === 0)
											{
												if(($bPrefixUnderscore === false) && ($iPrefixFound < 1) && (strpos($aRecord[$k], $aFormValues['db_prefix'] . '_') === 0))
												{
													$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database prefix should end with an underscore (Expecting prefix "{0}_" instead of "{0}").', array($aFormValues['db_prefix']));
													return false;
												}
												else
												{
													$iPrefixFound++;
												}
											}
										}

										break;
									}
								}

								if(sizeof($aTables) > 1)
								{
									usort($aTables, 'strcasecmp');
								}

								if($iPrefixFound > 2)
								{
									self::setLog('Database check completed, status: OK', __FILE__, __LINE__);

									$aFormValues['db_success'] .= "\r\n" . idealcheckout_getTranslation(false, 'install', 'Database prefix verified (Found "{0}" {1}x).', array($aFormValues['db_prefix'], $iPrefixFound));
									return true;
								}
								else
								{
									self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
									self::setLog('Database prefix is invalid.', __FILE__, __LINE__);

									$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database prefix invalid (Found "{0}" {1}x).', array($aFormValues['db_prefix'], $iPrefixFound));
								}
							}
						}
						else
						{
							self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
							self::setLog('Database name is invalid.', __FILE__, __LINE__);

							$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database name invalid.');
						}
					}
					else
					{
						self::setLog('Database check failed, status: NOK', __FILE__, __LINE__);
						self::setLog('Database host, user and/or password are invalid.', __FILE__, __LINE__);

						$aFormValues['db_error'] = idealcheckout_getTranslation(false, 'install', 'Database host, user and/or password invalid.');
					}
				}
			}

			return false;
		}

		public static function setLog($sMessage, $sFile = false, $iLine = false)
		{
			idealcheckout_log($sMessage, $sFile, $iLine, false);
		}



		// Does $sString starts with $sCompare?
		public static function stringStart($sString, $sCompare, $bCaseSensitive = false)
		{
			$sMatch = substr($sString, 0, strlen($sCompare));

			if($bCaseSensitive)
			{
				return (strcmp($sMatch, $sCompare) === 0);
			}
			else
			{
				return (strcasecmp($sMatch, $sCompare) === 0);
			}
		}



		// Retrieve a value from a configuration file
		public static function getFileValue($sFileData, $aRegularExpressions, $iIndex = 1)
		{
			$aMatches = array();

			if(!is_array($aRegularExpressions))
			{
				$aRegularExpressions = array($aRegularExpressions);
			}

			foreach($aRegularExpressions as $sRegex)
			{
				$aFiler = preg_match_all($sRegex, $sFileData, $aMatches);

				if(isset($aMatches[$iIndex][0]))
				{
					return $aMatches[$iIndex][0];
				}
			}

			return '';
		}



		// Write data to file.
		public static function setFile($sRelativePath, $sFileData = '', $aInstallSettings = false)
		{
			if(self::stringStart($sRelativePath, SOFTWARE_PATH))
			{
				$sRelativePath = substr($sRelativePath, strlen(SOFTWARE_PATH));
			}

			$sLocalPath = SOFTWARE_PATH . $sRelativePath;

			if(!FTP_ACCESS_REQUIRED && @file_put_contents($sLocalPath, $sFileData))
			{
				self::setLog('LOCAL: Creating file: ' . $sLocalPath, __FILE__, __LINE__);

				@chmod($sLocalPath, 0666);
				return true;
			}
			else // Try FTP
			{
				$aInstallSettings = self::getInstallSettings($aInstallSettings);
				$oFtp = self::getFtpConnection($aInstallSettings);

				if($oFtp)
				{
					$sRemotePath = $aInstallSettings['ftp_path'] . $sRelativePath;

					self::setLog('REMOTE: Creating file: ' . $sRemotePath, __FILE__, __LINE__);

					if($oFtp->createFile($sRemotePath, $sFileData))
					{
						return true;
					}
				}
			}

			return false;
		}

		public static function deleteFile($sRelativePath, $aInstallSettings = false)
		{
			if(self::stringStart($sRelativePath, SOFTWARE_PATH))
			{
				$sRelativePath = substr($sRelativePath, strlen(SOFTWARE_PATH));
			}

			$sLocalPath = SOFTWARE_PATH . $sRelativePath;

			if(is_file($sLocalPath))
			{
				if(!FTP_ACCESS_REQUIRED && @unlink($sLocalPath))
				{
					self::setLog('LOCAL: Removing file: ' . $sLocalPath, __FILE__, __LINE__);
					return true;
				}
				else // Try FTP
				{
					$aInstallSettings = self::getInstallSettings($aInstallSettings);
					$oFtp = self::getFtpConnection($aInstallSettings);

					if($oFtp)
					{
						$sRemotePath = $aInstallSettings['ftp_path'] . $sRelativePath;

						self::setLog('REMOTE: Removing file: ' . $sRemotePath, __FILE__, __LINE__);

						if($oFtp->deleteFile($sRemotePath))
						{
							return true;
						}
					}
				}
			}

			return false;
		}

		public static function deleteFolder($sRelativePath, $aInstallSettings = false)
		{
			if(self::stringStart($sRelativePath, SOFTWARE_PATH))
			{
				$sRelativePath = substr($sRelativePath, strlen(SOFTWARE_PATH));
			}

			$sLocalPath = SOFTWARE_PATH . $sRelativePath;

			if(is_dir($sLocalPath))
			{
				if(!FTP_ACCESS_REQUIRED && self::_deleteFolder($sLocalPath))
				{
					self::setLog('LOCAL: Removing folder: ' . $sLocalPath, __FILE__, __LINE__);
					return true;
				}
				else // Try FTP
				{
					$aInstallSettings = self::getInstallSettings($aInstallSettings);
					$oFtp = self::getFtpConnection($aInstallSettings);

					if($oFtp)
					{
						$sRemotePath = $aInstallSettings['ftp_path'] . $sRelativePath;

						self::setLog('REMOTE: Removing folder: ' . $sRemotePath, __FILE__, __LINE__);

						if($oFtp->deleteFolder($sRemotePath))
						{
							return true;
						}
					}
				}
			}

			return false;
		}


		public static function chmodFolder($sRelativePath, $iChmod = 0777, $aInstallSettings = false)
		{
			if(self::stringStart($sRelativePath, SOFTWARE_PATH))
			{
				$sRelativePath = substr($sRelativePath, strlen(SOFTWARE_PATH));
			}

			$sLocalPath = SOFTWARE_PATH . $sRelativePath;

			if(is_dir($sLocalPath))
			{
				if(is_writable($sLocalPath))
				{
					return true;
				}
				elseif(!FTP_ACCESS_REQUIRED)
				{
					if(self::_chmodFolder($sLocalPath, $iChmod))
					{
						self::setLog('LOCAL: Setting chmod(0777) to ' . $sLocalPath, __FILE__, __LINE__);
						return true;
					}

					return false;
				}
				else // Try FTP
				{
					$aInstallSettings = self::getInstallSettings($aInstallSettings);
					$oFtp = self::getFtpConnection($aInstallSettings);

					if($oFtp)
					{
						$sRemotePath = $aInstallSettings['ftp_path'] . $sRelativePath;

						self::setLog('REMOTE: Setting chmod(0777) to ' . $sRemotePath, __FILE__, __LINE__);

						if($_REQUEST['FTP_CONNECTION']->setChmod($sRemotePath))
						{
							return true;
						}
					}
				}
			}
			elseif(is_file($sLocalPath))
			{
				if(is_writable($sLocalPath))
				{
					return true;
				}
				elseif(!FTP_ACCESS_REQUIRED)
				{
					if(@chmod($sLocalPath, $iChmod))
					{
						self::setLog('LOCAL: Setting chmod(0777) to ' . $sLocalPath, __FILE__, __LINE__);
						return true;
					}

					return false;
				}
				else // Try FTP
				{
					$aInstallSettings = self::getInstallSettings($aInstallSettings);
					$oFtp = self::getFtpConnection($aInstallSettings);

					if($oFtp)
					{
						$sRemotePath = $aInstallSettings['ftp_path'] . $sRelativePath;

						self::setLog('REMOTE: Setting chmod(0777) to ' . $sRemotePath, __FILE__, __LINE__);

						if($_REQUEST['FTP_CONNECTION']->setChmod($sRemotePath))
						{
							return true;
						}
					}
				}
			}

			return false;
		}



		public static function getFtpConnection($aInstallSettings = false)
		{
			if(empty($_REQUEST['FTP_CONNECTION']))
			{
				$aInstallSettings = self::getInstallSettings($aInstallSettings);

				if(empty($aInstallSettings['ftp_host']))
				{
					self::addLog('Cannot find any FTP settings in $aInstallSettings', __FILE__, __LINE__);
					self::addLog($aInstallSettings, __FILE__, __LINE__);
					return false;
				}

				self::addLog('Creating FTP connection to ' . $aInstallSettings['ftp_host'], __FILE__, __LINE__);

				require_once(IDEALCHECKOUT_PATH . '/install/includes/ftp.cls.php');

				$_REQUEST['FTP_CONNECTION'] = new clsFtp();
				$bFtpConnected = $_REQUEST['FTP_CONNECTION']->connect($aInstallSettings['ftp_host'], $aInstallSettings['ftp_user'], $aInstallSettings['ftp_pass'], $aInstallSettings['ftp_port'], !empty($aInstallSettings['ftp_passive']), true);

				if(!$bFtpConnected)
				{
					return false;
				}

				// Set root folder
				if(!empty($aInstallSettings['ftp_path']))
				{
					$_REQUEST['FTP_CONNECTION']->setRemotePath($aInstallSettings['ftp_path']);
				}
			}

			return $_REQUEST['FTP_CONNECTION'];
		}

		public static function getInstallSettings($aInstallSettings = false)
		{
			if($aInstallSettings === false)
			{
				return include(IDEALCHECKOUT_PATH . '/configuration/install.php');
			}

			return $aInstallSettings;
		}

		public static function addLog($sString, $sFile = false, $iLine = false)
		{
			idealcheckout_log($sString, $sFile, $iLine, false);
		}


		public static function appendSlash($sString)
		{
			if(substr($sString, -1, 1) == '/')
			{
				return $sString;
			}

			return $sString . '/';
		}

		public static function prependSlash($sString)
		{
			if(substr($sString, 0, 1) == '/')
			{
				return $sString;
			}

			return '/' . $sString;
		}



		// Draw gateway input fields
		public static function drawFormFields($aSelectedGateway)
		{
			$sHtml = '';

			if(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Easy'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de ABN Amro)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value="TESTiDEALEASY"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Easy (Beveiligd)'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de ABN Amro)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-IN Key</b> <em>*</em> (Deze moet u laten instellen door de ABN Amro e-commerce helpdesk)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_in" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Hosted'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard, standaard "0")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Hash Key</b> <em>*</em> (Deze moet u zelf instellen in uw TEST en LIVE dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Integrated'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard, standaard "0")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Internet Kassa'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de ABN Amro/Ogone)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-IN Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_in" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-OUT Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_out" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Only'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de ABN Amro/Ogone)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-IN Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_in" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-OUT Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_out" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Zelfbouw'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze vindt u op uw TEST en LIVE dashboard, standaard "0")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Easy iDEAL - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant Secret</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Fortis Bank - iDEAL Hosted'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de Fortis Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de Fortis Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Hash Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Fortis Bank - iDEAL Integrated'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de Fortis Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de Fortis Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Friesland Bank - iDEAL Zakelijk'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de Friesland Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de Friesland Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Hash Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Friesland Bank - iDEAL Zakelijk Plus'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de Friesland Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de Friesland Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Direct E-Banking'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Authorized Transfer'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Manual Transfer'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Klarnaaccount'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Klarnainvoice'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Maestro'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Mastercard'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - PayPal'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - PaySafeCard'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Visa'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - VPAY'))
			{
				$sHtml .= '<tr class="hide-c"><td>Geen extra instellingen nodig voor deze test omgeving.</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Bank - iDEAL Advanced'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de ING Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de ING Bank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Bank - iDEAL Internet Kassa'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de ING Bank/Ogone)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-IN Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_in" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-OUT Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_out" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - ALL'))
			{
				// Select payment methods to configure
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Betaalmethoden</b><br>Gebruik de bovenstaande configuratie voor de onderstaande betaalmethoden<br><b>Let op:</b> Deze moeten ook actief zijn bij uw account</td></tr>';
				$sHtml .= '
					<tr class="hide-c"><td>&nbsp;</td></tr>
					<tr class="hide-c"><td><div class="label">iDEAL</div><input id="ideal" class="checkbox" name="payment_methods[]" value="iDEAL" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Creditcard</div><input id="creditcard" class="checkbox" name="payment_methods[]" value="Creditcard" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Overboeking</div><input id="manualtransfer" class="checkbox" name="payment_methods[]" value="Handmatige overboeking" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Bancontact</div><input id="bancontact" class="checkbox" name="payment_methods[]" value="Bancontact" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">PayPal</div><input id="paypal" class="checkbox" name="payment_methods[]" value="Paypal" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Klarna</div><input id="klarna" class="checkbox" name="payment_methods[]" value="Klarna" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Payconiq</div><input id="payconiq" class="checkbox" name="payment_methods[]" value="Payconiq" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">AfterPay</div><input id="afterpay" class="checkbox" name="payment_methods[]" value="Afterpay" type="checkbox"></td></tr>
					<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>API key</b> <em>*</em><br>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Handmatige overboeking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Paypal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Klarna'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Payconiq'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - AfterPay'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - ALL'))
			{
				// Select payment methods to configure
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Betaalmethoden</b><br>Gebruik de bovenstaande configuratie voor de onderstaande betaalmethoden<br><b>Let op:</b> Deze moeten ook actief zijn bij uw account</td></tr>';
				$sHtml .= '
					<tr class="hide-c"><td>&nbsp;</td></tr>
					<tr class="hide-c"><td><div class="label">iDEAL</div><input id="ideal" class="checkbox" name="payment_methods[]" value="iDEAL" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Creditcard</div><input id="creditcard" class="checkbox" name="payment_methods[]" value="Creditcard" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Overboeking</div><input id="manualtransfer" class="checkbox" name="payment_methods[]" value="Handmatige overboeking" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Bancontact</div><input id="bancontact" class="checkbox" name="payment_methods[]" value="Bancontact" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">PayPal</div><input id="paypal" class="checkbox" name="payment_methods[]" value="Paypal" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Klarna</div><input id="klarna" class="checkbox" name="payment_methods[]" value="Klarna" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Sofort</div><input id="sofort" class="checkbox" name="payment_methods[]" value="Sofort" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Payconiq</div><input id="payconiq" class="checkbox" name="payment_methods[]" value="Payconiq" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">AfterPay</div><input id="afterpay" class="checkbox" name="payment_methods[]" value="Afterpay" type="checkbox"></td></tr>
					<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>API key</b> <em>*</em><br>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Handmatige overboeking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Paypal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Klarna'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Payconiq'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - AfterPay'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.ing-checkout.com" target="_blank">ING Checkout Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - ALL'))
			{
				// Select payment methods to configure
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Betaalmethoden</b><br>Gebruik de bovenstaande configuratie voor de onderstaande betaalmethoden<br><b>Let op:</b> Deze moeten ook actief zijn bij uw account</td></tr>';
				$sHtml .= '
					<tr class="hide-c"><td>&nbsp;</td></tr>
					<tr class="hide-c"><td><div class="label">iDEAL</div><input id="ideal" class="checkbox" name="payment_methods[]" value="iDEAL" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Creditcard</div><input id="creditcard" class="checkbox" name="payment_methods[]" value="Creditcard" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Overboeking</div><input id="manualtransfer" class="checkbox" name="payment_methods[]" value="Handmatige overboeking" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Bancontact</div><input id="bancontact" class="checkbox" name="payment_methods[]" value="Bancontact" type="checkbox"></td></tr>
					<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>API key</b> <em>*</em><br>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.epay.ing.be" target="_blank">ING ePAY Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.epay.ing.be" target="_blank">ING ePAY Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.epay.ing.be" target="_blank">ING ePAY Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Handmatige overboeking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.epay.ing.be" target="_blank">ING ePAY Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://www.portal.epay.ing.be" target="_blank">ING ePAY Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Profile UID</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_profile_uid" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Profile UID</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_profile_uid" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Handmatig Overboeken'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Profile UID</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_profile_uid" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Profile UID</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_profile_uid" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Paypal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Profile UID</b> <em>*</em> (Deze vindt u op uw Online Betaalplatform Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_profile_uid" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Qantani Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Direct E-Banking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Qantani Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Qantani Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - PayPal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Qantani Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>API Key</b> <em>*</em> (Deze vindt u op uw Qantani Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_api_key" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabobank - iDEAL Internet Kassa'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>PSP ID</b> <em>*</em> (Deze ontvangt u van de Rabobank/Ogone)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_psp_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-IN Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_in" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>SHA-1-OUT Key</b> <em>*</em> (Deze moet u zelf instellen in uw iDEAL Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sha1_out" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabobank - iDEAL Professional'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em> (Deze ontvangt u van de Rabobank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sub ID</b> <em>*</em> (Deze ontvangt u van de Rabobank)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_sub_id" type="text" value="0"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key Pass</b> <em>*</em> (Wachtwoord waarmee uw private key file is gegenereerd)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_pass" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Key File</b> <em>*</em> (Bestandsnaam van uw private key file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_key_file" type="file" accept=".key" value="private.key"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Private Certificate File</b> <em>*</em> (Bestandsnaam van uw private certificate file)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_private_certificate_file" type="file" accept=".cer" value="private.cer"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - ALL'))
			{
				// Select payment methods to configure
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Betaalmethoden</b><br>Gebruik de bovenstaande configuratie voor de onderstaande betaalmethoden<br><b>Let op:</b> Deze moeten ook actief zijn bij uw account</td></tr>';
				$sHtml .= '
					<tr class="hide-c"><td>&nbsp;</td></tr>
					<tr class="hide-c"><td><div class="label">iDEAL</div><input id="ideal" class="checkbox" name="payment_methods[]" value="iDEAL" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Bancontact</div><input id="bancontact" class="checkbox" name="payment_methods[]" value="Bancontact" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Creditcard</div><input id="creditcard" class="checkbox" name="payment_methods[]" value="Creditcard" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">PayPal</div><input id="paypal" class="checkbox" name="payment_methods[]" value="PayPal" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Maestro</div><input id="maestro" class="checkbox" name="payment_methods[]" value="Maestro" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">MasterCard</div><input id="mastercard" class="checkbox" name="payment_methods[]" value="Mastercard" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">Visa</div><input id="visa" class="checkbox" name="payment_methods[]" value="Visa" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">vPay</div><input id="vpay" class="checkbox" name="payment_methods[]" value="vPay" type="checkbox"></td></tr>
					<tr class="hide-c"><td><div class="label">AfterPay</div><input id="afterpay" class="checkbox" name="payment_methods[]" value="AfterPay" type="checkbox"></td></tr>
					<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Afterpay'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Maestro'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Mastercard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Paypal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Visa'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - vPay'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Sandbox Mode</b> <em>*</em><br>Gebruik de Sandbox of Productie omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">Sandbox omgeving gebruiken</option><option value="false">Productie omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Refresh Token</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><textarea name="idealcheckout_refresh_token" type="text" rows="4" cols="50" value=""></textarea></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Signing key</b> <em>*</em><br>Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_signing_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webhook URL</b><br>In uw <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabobank Omnikassa 2.0 Dashboard</a> wordt gevraagd naar een "webhook url" Deze kunt u hieronder kopieeren.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input id="idealcheckout_webhook_url" type="text" value="' . self::getWebhookUrl() .  '"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value="002020000000001"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em><br>Gebruik de TEST of LIVE omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value="002020000000001_KEY1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value="002020000000001"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em><br>Gebruik de TEST of LIVE omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value="002020000000001_KEY1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Maestro'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value="002020000000001"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em><br>Gebruik de TEST of LIVE omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value="002020000000001_KEY1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Mastercard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value="002020000000001"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em><br>Gebruik de TEST of LIVE omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value="002020000000001_KEY1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><i>Testbetalingen zijn op dit moment nog niet mogelijk met Bancontact. Om testbetalingen<br>uit te voeren kunt u gebruik maken van o.a. iDEAL, MiniTix, Maestro, Visa en Mastercard.</i></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Visa'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value="002020000000001"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em><br>Gebruik de TEST of LIVE omgeving.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value="002020000000001_KEY1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - VPAY'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Winkel-ID</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Geheime Sleutel</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Sleutel versie/nummer</b> <em>*</em><br>Deze vindt u op de downloadsite van de <a href="https://download.omnikassa.rabobank.nl/" target="_blank" title="https://download.omnikassa.rabobank.nl/">Rabo OmniKassa</a>.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_hash_key_version" type="text" value="1"></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><i>Testbetalingen zijn op dit moment nog niet mogelijk met VPAY. Om testbetalingen<br>uit te voeren kunt u gebruik maken van o.a. iDEAL, MiniTix, Maestro, Visa en Mastercard.</i></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - Direct E-Banking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant KEY</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Shop ID</b> <em>*</em> (Deze ontvangt u van Sisow)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_shop_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant KEY</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Shop ID</b> <em>*</em> (Deze ontvangt u van Sisow)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_shop_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant KEY</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Shop ID</b> <em>*</em> (Deze ontvangt u van Sisow)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_shop_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - PayPal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Merchant ID</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Merchant KEY</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_merchant_key" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Shop ID</b> <em>*</em> (Deze ontvangt u van Sisow)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_shop_id" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Test Mode</b> <em>*</em> (Gebruik de TEST of LIVE omgeving)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><select name="idealcheckout_test_mode"><option value="true">TEST omgeving gebruiken</option><option value="false">LIVE omgeving gebruiken</option></select></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Creditcard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Layout Code</b> <em>*</em> (Deze vindt u terug in uw TargetPay Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_layout_code" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Direct E-Banking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Layout Code</b> <em>*</em> (Deze vindt u terug in uw TargetPay Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_layout_code" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - iDEAL'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Layout Code</b> <em>*</em> (Deze vindt u terug in uw TargetPay Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_layout_code" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Bancontact'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Layout Code</b> <em>*</em> (Deze vindt u terug in uw TargetPay Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_layout_code" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - PaySafeCard'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Layout Code</b> <em>*</em> (Deze vindt u terug in uw TargetPay Dashboard)</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_layout_code" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('PayDutch - Direct E-Banking'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Gebruikersnaam van uw PayDutch account</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_username" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Wachtwoord van uw PayDutch account</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_password" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>"ClientName" van uw PayDutch account</b> <em>*</em><br>(Deze moet u zelf instellen in uw PayDutch account bij "Technical Settings..")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_callback_username" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>"ClientPassword" van uw PayDutch account</b> <em>*</em><br>(Deze moet u zelf instellen in uw PayDutch account bij "Technical Settings..")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_callback_password" type="text" value=""></td></tr>';
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('PayDutch - WeDeal'))
			{
				$sHtml .= '<tr class="hide-c"><td><b>Gebruikersnaam van uw PayDutch account</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_username" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Wachtwoord van uw PayDutch account</b> <em>*</em></td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_password" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>"ClientName" van uw PayDutch account</b> <em>*</em><br>(Deze moet u zelf instellen in uw PayDutch account bij "Technical Settings..")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_callback_username" type="text" value=""></td></tr>';
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>"ClientPassword" van uw PayDutch account</b> <em>*</em><br>(Deze moet u zelf instellen in uw PayDutch account bij "Technical Settings..")</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_callback_password" type="text" value=""></td></tr>';
			}
			else
			{
				$sHtml .= '<tr class="hide-c"><td>Unknown gateway: ' . htmlentities($aSelectedGateway['code']) . '</td></tr>';
			}

			if(!in_array($aSelectedGateway['code'], array('ABN Amro - iDEAL Easy', 'ABN Amro - iDEAL Easy (Beveiligd)')))
			{
				$sHtml .= '<tr class="hide-c"><td>&nbsp;</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><b>Webmaster E-mail</b><br>Laat de plug-in, bij status updates, een e-mail verzenden naar het onderstaande adres.<br>U kunt meerdere adressen opgegeven door deze te scheiden met een komma.</td></tr>';
				$sHtml .= '<tr class="hide-c"><td><input name="idealcheckout_email_to" type="text" value="" placeholder="mijn@email.adres"></td></tr>';
			}

			return $sHtml;
		}

		// Save gateway input data to configuration file
		public static function saveFormFields($aSelectedGateway)
		{
			$sData = '';
			$sData .= '<' . '?' . 'php' . LF;
			$sData .= LF;
			$sData .= TAB . '/*' . LF;
			$sData .= TAB . TAB . 'This plug-in was developed by iDEAL Checkout.' . LF;
			$sData .= TAB . TAB . 'See www.ideal-checkout.nl for more information.' . LF;
			$sData .= LF;
			$sData .= TAB . TAB . 'This file was generated on ' . date('d-m-Y, H:i:s') . LF;
			$sData .= TAB . '*/' . LF;
			$sData .= LF;
			$sData .= LF;


			// Add gateway settings
			if(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Easy'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Easy\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easy\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Easy (Beveiligd)'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'PSP_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate/validate hashes' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_IN\'] = \'' . (empty($_POST['idealcheckout_sha1_in']) ? '' : addslashes($_POST['idealcheckout_sha1_in'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Easy (Beveiligd)\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easy-secure\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Hosted'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Hosted\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Integrated'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Integrated\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Internet Kassa'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'PSP_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate/validate hashes' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_IN\'] = \'' . (empty($_POST['idealcheckout_sha1_in']) ? '' : addslashes($_POST['idealcheckout_sha1_in'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_OUT\'] = \'' . (empty($_POST['idealcheckout_sha1_out']) ? '' : addslashes($_POST['idealcheckout_sha1_out'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Internet Kassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Only'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'PSP_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate/validate hashes' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_IN\'] = \'' . (empty($_POST['idealcheckout_sha1_in']) ? '' : addslashes($_POST['idealcheckout_sha1_in'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_OUT\'] = \'' . (empty($_POST['idealcheckout_sha1_out']) ? '' : addslashes($_POST['idealcheckout_sha1_out'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Only\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ABN Amro - iDEAL Zelfbouw'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Zelfbouw\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Easy iDEAL - iDEAL'))
			{
				$sData .= TAB . '// Your Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your Merchant Key' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_KEY\'] = \'' . (empty($_POST['idealcheckout_merchant_key']) ? '' : addslashes($_POST['idealcheckout_merchant_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your Merchant Secret Hash Key' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_SECRET\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Easy iDEAL - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.easy-ideal.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easyideal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Fortis Bank - iDEAL Hosted'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Fortis Bank - iDEAL Hosted\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.fortisbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Fortis Bank - iDEAL Integrated'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Fortis Bank - iDEAL Integrated\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.fortisbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Friesland Bank - iDEAL Zakelijk'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Friesland Bank - iDEAL Zakelijk\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.frieslandbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Friesland Bank - iDEAL Zakelijk Plus'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Friesland Bank - iDEAL Zakelijk Plus\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.frieslandbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Credit Card'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Credit Card\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Direct E-Banking'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Direct E-Banking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - AfterPay'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - AfterPay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'afterpay-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Carte Bleue'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Carte Bleue\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'cartebleue-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - eBon'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - eBon\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ebon-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Authorized Transfer'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Eenmalige machtiging\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'authorizedtransfer-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - FasterPay'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - FasterPay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'fasterpay-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Klarnaaccount'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Klarnaaccount\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'klarnaaccount-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Klarnainvoice'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Klarnainvoice\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'klarnainvoice-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - GiroPay'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - GiroPay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'giropay-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Manual Transfer'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Handmatige overboeking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - iDEAL'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Maestro'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Maestro\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Mastercard'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Mastercard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - MiniTix'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - MiniTix\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'minitix-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Bancontact'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - PayPal'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PayPal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - PaySafeCard'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PaySafeCard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paysafecard-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - PostePay'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PostePay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'postepay-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - Visa'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Visa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'visa-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - VPAY'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - VPAY\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('iDEAL Simulator - WebshopGiftCard'))
			{
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - WebshopGiftCard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'webshopgiftcard-simulator\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Bank - iDEAL Advanced'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Bank - iDEAL Advanced\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ingbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Bank - iDEAL Internet Kassa'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'PSP_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate/validate hashes' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_IN\'] = \'' . (empty($_POST['idealcheckout_sha1_in']) ? '' : addslashes($_POST['idealcheckout_sha1_in'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_OUT\'] = \'' . (empty($_POST['idealcheckout_sha1_out']) ? '' : addslashes($_POST['idealcheckout_sha1_out'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Bank - iDEAL Internet Kassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ingbank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - iDEAL'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Creditcard'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Handmatige overboeking'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Handmatige overboeking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Bancontact'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet -Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Paypal'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Paypal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Klarna'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Klarna\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'klarnainvoice-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Payconiq'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Payconiq\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'payconiq-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Kassa compleet - Afterpay'))
			{
				$sData .= TAB . '// KassaCompleet API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Kassa compleet - Afterpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://portal.kassacompleet.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'afterpay-ingkassacompleet\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - iDEAL'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Creditcard'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Handmatige overboeking'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Handmatige overboeking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Bancontact/Bancontact'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Bancontact/Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Paypal'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Paypal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Klarna'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Klarna\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'klarnainvoice-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Sofort'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Sofort\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'sofort-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - Payconiq'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Payconiq\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'payconiq-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING Checkout - AfterPay'))
			{
				$sData .= TAB . '// ING Checkout API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING Checkout - Afterpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'afterpay-ingcheckout\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - iDEAL'))
			{
				$sData .= TAB . '// ING ePAY API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING ePAY - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.be/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-ingepay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Creditcard'))
			{
				$sData .= TAB . '// ING ePAY API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING ePAY - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.be/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-ingepay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Handmatige overboeking'))
			{
				$sData .= TAB . '// ING ePAY API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING ePAY - Handmatige overboeking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.be/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-ingepay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('ING ePAY - Bancontact/Bancontact'))
			{
				$sData .= TAB . '// ING ePAY API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : $_POST['idealcheckout_api_key']) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'ING ePAY - Bancontact/Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.ing.be/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-ingepay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - iDEAL'))
			{
				$sData .= TAB . '// Online Betaalplatform API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Online Betaalplatform Profile UID' . LF;
				$sData .= TAB . '$aSettings[\'PROFILE_UID\'] = \'' . (empty($_POST['idealcheckout_profile_uid']) ? '' : addslashes($_POST['idealcheckout_profile_uid'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://onlinebetaalplatform.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-onlinebetaalplatform\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Creditcard'))
			{
				$sData .= TAB . '// Online Betaalplatform API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Online Betaalplatform Profile UID' . LF;
				$sData .= TAB . '$aSettings[\'PROFILE_UID\'] = \'' . (empty($_POST['idealcheckout_profile_uid']) ? '' : addslashes($_POST['idealcheckout_profile_uid'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://onlinebetaalplatform.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-onlinebetaalplatform\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Handmatig Overboeken'))
			{
				$sData .= TAB . '// Online Betaalplatform API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Online Betaalplatform Profile UID' . LF;
				$sData .= TAB . '$aSettings[\'PROFILE_UID\'] = \'' . (empty($_POST['idealcheckout_profile_uid']) ? '' : addslashes($_POST['idealcheckout_profile_uid'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Handmatig Overboeken\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://onlinebetaalplatform.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-onlinebetaalplatform\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Bancontact'))
			{
				$sData .= TAB . '// Online Betaalplatform API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Online Betaalplatform Profile UID' . LF;
				$sData .= TAB . '$aSettings[\'PROFILE_UID\'] = \'' . (empty($_POST['idealcheckout_profile_uid']) ? '' : addslashes($_POST['idealcheckout_profile_uid'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://onlinebetaalplatform.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-onlinebetaalplatform\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Online Betaalplatform - Paypal'))
			{
				$sData .= TAB . '// Online Betaalplatform API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Online Betaalplatform Profile UID' . LF;
				$sData .= TAB . '$aSettings[\'PROFILE_UID\'] = \'' . (empty($_POST['idealcheckout_profile_uid']) ? '' : addslashes($_POST['idealcheckout_profile_uid'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Paypal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://onlinebetaalplatform.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-onlinebetaalplatform\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Credit Card'))
			{
				$sData .= TAB . '// Qantani API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.qantani.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-qantani\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Direct E-Banking'))
			{
				$sData .= TAB . '// Qantani API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Direct E-Banking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.qantani.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-qantani\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - iDEAL'))
			{
				$sData .= TAB . '// Qantani API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.qantani.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-qantani\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - PayPal'))
			{
				$sData .= TAB . '// Qantani API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Paypal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.qantani.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-qantani\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Qantani - Bancontact'))
			{
				$sData .= TAB . '// Qantani API Key' . LF;
				$sData .= TAB . '$aSettings[\'API_KEY\'] = \'' . (empty($_POST['idealcheckout_api_key']) ? '' : addslashes($_POST['idealcheckout_api_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.qantani.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-qantani\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabobank - iDEAL Internet Kassa'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'PSP_ID\'] = \'' . (empty($_POST['idealcheckout_psp_id']) ? '' : addslashes($_POST['idealcheckout_psp_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate/validate hashes' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_IN\'] = \'' . (empty($_POST['idealcheckout_sha1_in']) ? '' : addslashes($_POST['idealcheckout_sha1_in'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'SHA_1_OUT\'] = \'' . (empty($_POST['idealcheckout_sha1_out']) ? '' : addslashes($_POST['idealcheckout_sha1_out'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabobank - iDEAL Internet Kassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabobank - iDEAL Professional'))
			{
				$sData .= TAB . '// Merchant ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate private key file' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' . (empty($_POST['idealcheckout_private_key_pass']) ? '' : addslashes($_POST['idealcheckout_private_key_pass'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_key_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_key_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' . LF;
				$sData .= TAB . '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' . (empty($_FILES['idealcheckout_private_certificate_file']['name']) ? '' : addslashes($_FILES['idealcheckout_private_certificate_file']['name'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabobank - iDEAL Professional\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - iDEAL'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Creditcard'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Creditcard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Afterpay'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Afterpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'afterpay-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Maestro'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Maestro\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Mastercard'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Mastercard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Bancontact'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Paypal'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Paypal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - Visa'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Visa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'visa-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa 2.0 - vPay'))
			{
				$sData .= TAB . '// Refresh Token' . LF;
				$sData .= TAB . '$aSettings[\'REFRESH_TOKEN\'] = \'' . (empty($_POST['idealcheckout_refresh_token']) ? '' : addslashes($_POST['idealcheckout_refresh_token'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Signing Key' . LF;
				$sData .= TAB . '$aSettings[\'SIGNING_KEY\'] = \'' . (empty($_POST['idealcheckout_signing_key']) ? '' : addslashes($_POST['idealcheckout_signing_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - vPay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-omnikassa-v2\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Credit Card'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' . document.getElementById('idealcheckout_hash_key_version').value . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Credit Card\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - iDEAL'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Maestro'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Maestro\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Mastercard'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Mastercard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Bancontact'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				// $sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = false;' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - Visa'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Visa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'visa-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Rabo OmniKassa - VPAY'))
			{
				$sData .= TAB . '// Webshop-ID' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your iDEAL Sub ID' . LF;
				$sData .= TAB . '$aSettings[\'SUB_ID\'] = \'' . (empty($_POST['idealcheckout_sub_id']) ? '0' : addslashes($_POST['idealcheckout_sub_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Password used to generate hash' . LF;
				$sData .= TAB . '$aSettings[\'HASH_KEY\'] = \'' . (empty($_POST['idealcheckout_hash_key']) ? '' : addslashes($_POST['idealcheckout_hash_key'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'KEY_VERSION\'] = \'' .(empty($_POST['idealcheckout_hash_key_version']) ? '' : addslashes($_POST['idealcheckout_hash_key_version'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				// $sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = false;' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - VPAY\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-omnikassa\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = false;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - Direct E-Banking'))
			{
				$sData .= TAB . '// Merchant ID or Email' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Merchant Key or password' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_KEY\'] = \'' . (empty($_POST['idealcheckout_merchant_key']) ? '' : addslashes($_POST['idealcheckout_merchant_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your SHOP ID (for multiple shops in 1 account)' . LF;
				$sData .= TAB . '$aSettings[\'SHOP_ID\'] = \'' . (empty($_POST['idealcheckout_shop_id']) ? '' : addslashes($_POST['idealcheckout_shop_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - Direct E-Banking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-sisow\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - iDEAL'))
			{
				$sData .= TAB . '// Merchant ID or Email' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Merchant Key or password' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_KEY\'] = \'' . (empty($_POST['idealcheckout_merchant_key']) ? '' : addslashes($_POST['idealcheckout_merchant_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your SHOP ID (for multiple shops in 1 account)' . LF;
				$sData .= TAB . '$aSettings[\'SHOP_ID\'] = \'' . (empty($_POST['idealcheckout_shop_id']) ? '' : addslashes($_POST['idealcheckout_shop_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-sisow\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - Bancontact'))
			{
				$sData .= TAB . '// Merchant ID or Email' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Merchant Key or password' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_KEY\'] = \'' . (empty($_POST['idealcheckout_merchant_key']) ? '' : addslashes($_POST['idealcheckout_merchant_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your SHOP ID (for multiple shops in 1 account)' . LF;
				$sData .= TAB . '$aSettings[\'SHOP_ID\'] = \'' . (empty($_POST['idealcheckout_shop_id']) ? '' : addslashes($_POST['idealcheckout_shop_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-sisow\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('Sisow - PayPal'))
			{
				$sData .= TAB . '// Merchant ID or Email' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_ID\'] = \'' . (empty($_POST['idealcheckout_merchant_id']) ? '' : addslashes($_POST['idealcheckout_merchant_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Merchant Key or password' . LF;
				$sData .= TAB . '$aSettings[\'MERCHANT_KEY\'] = \'' . (empty($_POST['idealcheckout_merchant_key']) ? '' : addslashes($_POST['idealcheckout_merchant_key'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Your SHOP ID (for multiple shops in 1 account)' . LF;
				$sData .= TAB . '$aSettings[\'SHOP_ID\'] = \'' . (empty($_POST['idealcheckout_shop_id']) ? '' : addslashes($_POST['idealcheckout_shop_id'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// Use TEST/LIVE mode; true=TEST, false=LIVE' . LF;
				$sData .= TAB . '$aSettings[\'TEST_MODE\'] = ' . (empty($_POST['idealcheckout_test_mode']) ? '' : addslashes($_POST['idealcheckout_test_mode'])) . ';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - PayPal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-sisow\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Credit Card'))
			{
				$sData .= TAB . '// TargetPay Layout Code' . LF;
				$sData .= TAB . '$aSettings[\'LAYOUT_CODE\'] = \'' . (empty($_POST['idealcheckout_layout_code']) ? '' : addslashes($_POST['idealcheckout_layout_code'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - Credit Card\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-targetpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Direct E-Banking'))
			{
				$sData .= TAB . '// TargetPay Layout Code' . LF;
				$sData .= TAB . '$aSettings[\'LAYOUT_CODE\'] = \'' . (empty($_POST['idealcheckout_layout_code']) ? '' : addslashes($_POST['idealcheckout_layout_code'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - Direct E-Banking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-targetpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - iDEAL'))
			{
				$sData .= TAB . '// TargetPay Layout Code' . LF;
				$sData .= TAB . '$aSettings[\'LAYOUT_CODE\'] = \'' . (empty($_POST['idealcheckout_layout_code']) ? '' : addslashes($_POST['idealcheckout_layout_code'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - iDEAL\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-targetpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - Bancontact'))
			{
				$sData .= TAB . '// TargetPay Layout Code' . LF;
				$sData .= TAB . '$aSettings[\'LAYOUT_CODE\'] = \'' . (empty($_POST['idealcheckout_layout_code']) ? '' : addslashes($_POST['idealcheckout_layout_code'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - Bancontact\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'bancontact-targetpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('TargetPay - PaySafeCard'))
			{
				$sData .= TAB . '// TargetPay Layout Code' . LF;
				$sData .= TAB . '$aSettings[\'LAYOUT_CODE\'] = \'' . (empty($_POST['idealcheckout_layout_code']) ? '' : addslashes($_POST['idealcheckout_layout_code'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - PaySafeCard\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'paysafecard-targetpay\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('PayDutch - Direct E-Banking'))
			{
				$sData .= TAB . '// PayDutch account settings' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_USERNAME\'] = \'' . (empty($_POST['idealcheckout_username']) ? '' : addslashes($_POST['idealcheckout_username'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_PASSWORD\'] = \'' . (empty($_POST['idealcheckout_password']) ? '' : addslashes($_POST['idealcheckout_password'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// PayDutch callback security settings' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_CALLBACK_USERNAME\'] = \'' . (empty($_POST['idealcheckout_callback_username']) ? '' : addslashes($_POST['idealcheckout_callback_username'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_CALLBACK_PASSWORD\'] = \'' . (empty($_POST['idealcheckout_callback_password']) ? '' : addslashes($_POST['idealcheckout_callback_password'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'PayDutch - Direct E-Banking\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.paydutch.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-paydutch\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}
			elseif(strtolower($aSelectedGateway['code']) == strtolower('PayDutch - WeDeal'))
			{
				$sData .= TAB . '// PayDutch account settings' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_USERNAME\'] = \'' . (empty($_POST['idealcheckout_username']) ? '' : addslashes($_POST['idealcheckout_username'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_PASSWORD\'] = \'' . (empty($_POST['idealcheckout_password']) ? '' : addslashes($_POST['idealcheckout_password'])) . '\';' . LF;
				$sData .= LF;
				$sData .= TAB . '// PayDutch callback security settings' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_CALLBACK_USERNAME\'] = \'' . (empty($_POST['idealcheckout_callback_username']) ? '' : addslashes($_POST['idealcheckout_callback_username'])) . '\';' . LF;
				$sData .= TAB . '$aSettings[\'PAYDUTCH_CALLBACK_PASSWORD\'] = \'' . (empty($_POST['idealcheckout_callback_password']) ? '' : addslashes($_POST['idealcheckout_callback_password'])) . '\';' . LF;
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// Basic gateway settings' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_NAME\'] = \'PayDutch - WeDeal\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.paydutch.nl/\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-paydutch\';' . LF;
				$sData .= TAB . '$aSettings[\'GATEWAY_VALIDATION\'] = true;' . LF;
			}

			if(!empty($_POST['idealcheckout_email_to']))
			{
				$sData .= LF;
				$sData .= LF;
				$sData .= TAB . '// E-mailadresses for transaction updates (comma seperated)' . LF;
				$sData .= TAB . '$aSettings[\'TRANSACTION_UPDATE_EMAILS\'] = \'' . (empty($_POST['idealcheckout_email_to']) ? '' : addslashes($_POST['idealcheckout_email_to'])) . '\';' . LF;
			}

			$sData .= LF;
			$sData .= '?' . '>';


			if(in_array($aSelectedGateway['code'], array('Rabobank - iDEAL Professional', 'Postcode iDEAL - iDEAL Professional', 'ABN Amro - iDEAL Zelfbouw', 'ING Bank - iDEAL Advanced', 'Fortis Bank - iDEAL Integrated')))
			{

				$aFormValues['db_host'] = 'localhost';
				$aFormValues['db_port'] = '';
				$aFormValues['db_user'] = '';
				$aFormValues['db_pass'] = '';
				$aFormValues['db_name'] = '';
				$aFormValues['db_prefix'] = '';

				$aDatabaseSettings = call_user_func($_REQUEST['software'] . '::getDatabaseSettings', $aFormValues);

				if(isset($_FILES['idealcheckout_private_key_file']['error']) && isset($_FILES['idealcheckout_private_certificate_file']['error']))
				{
					if(empty($_FILES['idealcheckout_private_key_file']['error']) && !empty($_FILES['idealcheckout_private_key_file']['tmp_name']) && empty($_FILES['idealcheckout_private_certificate_file']['error']) && !empty($_FILES['idealcheckout_private_certificate_file']['tmp_name'])) // No errors found
					{
						$sPrivateKeyData = file_get_contents($_FILES['idealcheckout_private_key_file']['tmp_name']);

						if(!empty($sPrivateKeyData))
						{
							$sPrivateKeyName = $_FILES['idealcheckout_private_key_file']['name'];

							// Check if key exists
							$sql = "SELECT `id` FROM `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` WHERE (`file_name` = '" . idealcheckout_escapeSql($sPrivateKeyName) . "') LIMIT 1";

							if(!idealcheckout_database_getRecord($sql))
							{
								// Save key
								$sql = "INSERT INTO `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` (`id`, `file_name`, `file_content`, `enabled`) VALUES (NULL, '" . idealcheckout_escapeSql($sPrivateKeyName) . "', '" . idealcheckout_escapeSql($sPrivateKeyData) . "', '1');";
								idealcheckout_database_execute($sql);
							}
							else
							{
								// Set other keyfiles to disabled and insert new one
								$sql = "UPDATE `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` SET `enabled` = '0' WHERE (`file_name` = '" .  idealcheckout_escapeSql($sPrivateKeyName) . "')";
								idealcheckout_database_execute($sql);

								// Save new key
								$sql = "INSERT INTO `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` (`id`, `file_name`, `file_content`, `enabled`) VALUES (NULL, '" . idealcheckout_escapeSql($sPrivateKeyName) . "', '" . idealcheckout_escapeSql($sPrivateKeyData) . "', '1');";
								idealcheckout_database_execute($sql);

							}

						}

						$sCertificateData = file_get_contents($_FILES['idealcheckout_private_certificate_file']['tmp_name']);

						if(!empty($sCertificateData))
						{
							$sPrivateCertificateName = $_FILES['idealcheckout_private_certificate_file']['name'];


							// Check if key exists
							$sql = "SELECT `id` FROM `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` WHERE (`file_name` = '" . idealcheckout_escapeSql($sPrivateCertificateName) . "') LIMIT 1";

							if(!idealcheckout_database_getRecord($sql))
							{
								// Save certificate
								$sql = "INSERT INTO `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` (`id`, `file_name`, `file_content`, `enabled`) VALUES (NULL, '" . idealcheckout_escapeSql($sPrivateCertificateName) . "', '" . idealcheckout_escapeSql($sCertificateData) . "', '1');";
								idealcheckout_database_execute($sql);
							}
							else
							{
								// Set other keyfiles to disabled and insert new one
								$sql = "UPDATE `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` SET `enabled` = '0' WHERE (`file_name` = '" .  idealcheckout_escapeSql($sPrivateCertificateName) . "')";
								idealcheckout_database_execute($sql);

								// Save new key
								$sql = "INSERT INTO `" . $aDatabaseSettings['db_prefix'] . "idealcheckout_ssl` (`id`, `file_name`, `file_content`, `enabled`) VALUES (NULL, '" . idealcheckout_escapeSql($sPrivateCertificateName) . "', '" . idealcheckout_escapeSql($sCertificateData) . "', '1');";
								idealcheckout_database_execute($sql);


							}
						}
					}
				}
				else
				{
					IDEALCHECKOUT_INSTALL::setLog('Cannot find private cer: ' . $sFilePath, __FILE__, __LINE__);
					return false;
				}
			}

			$sRootPath = dirname(dirname(__DIR__));

			$sFilePath = $sRootPath . '/configuration/' . $aSelectedGateway['type'] . '.php';

			if(!IDEALCHECKOUT_INSTALL::setFile($sFilePath, $sData))
			{
				IDEALCHECKOUT_INSTALL::setLog('Cannot create file: ' . $sFilePath, __FILE__, __LINE__);
				return false;
			}

			if(!empty($_SERVER['SERVER_NAME']))
			{
				$sStoreHost = $_SERVER['SERVER_NAME'];
				$aStoreHost = explode('.', $sStoreHost);
				$iStoreHost = sizeof($aStoreHost);

				$sStoreCode = md5($_SERVER['SERVER_NAME']);
				$sFilePath = '/idealcheckout/configuration/' . $aSelectedGateway['type'] . '.' . $sStoreCode . '.php';


				if(!IDEALCHECKOUT_INSTALL::setFile($sFilePath, $sData))
				{
					IDEALCHECKOUT_INSTALL::setLog('Cannot create file: ' . $sFilePath, __FILE__, __LINE__);
					return false;
				}

				if(($iStoreHost > 3) || ($iStoreHost < 2))
				{
					// $sStoreCode is not relevant for IP addresses or localhost.
				}
				elseif(strpos($sStoreHost, 'www.') === false) // No 'www.' found
				{
					$sStoreCode = md5('www.' . $_SERVER['SERVER_NAME']);
					$sFilePath = '/idealcheckout/configuration/' . $aSelectedGateway['type'] . '.' . $sStoreCode . '.php';

					if(!IDEALCHECKOUT_INSTALL::setFile($sFilePath, $sData))
					{
						IDEALCHECKOUT_INSTALL::setLog('Cannot create file: ' . $sFilePath, __FILE__, __LINE__);
						return false;
					}
				}
				elseif(strpos($sStoreHost, 'www.') === 0) // Starts with 'www.'
				{
					$sStoreCode = md5(substr($_SERVER['SERVER_NAME'], 4));
					$sFilePath = '/idealcheckout/configuration/' . $aSelectedGateway['type'] . '.' . $sStoreCode . '.php';

					if(!IDEALCHECKOUT_INSTALL::setFile($sFilePath, $sData))
					{
						IDEALCHECKOUT_INSTALL::setLog('Cannot create file: ' . $sFilePath, __FILE__, __LINE__);
						return false;
					}
				}
			}

			return true;
		}

		public static function _chmodFolder($sPath, $iChmod)
		{
			$aFiles = array_diff(scandir($sPath), array('.','..'));

			if(@chmod($sPath))
			{
				foreach($aFiles as $sFile)
				{
					if(is_dir($sPath . '/' . $sFile))
					{
						self::_chmodFolder($sPath . '/' . $sFile);
					}
					else
					{
						@chmod($sPath . '/' . $sFile);
					}
				}

				return true;
			}

			return false;
		}

		public static function _deleteFolder($sPath)
		{
			$aFiles = array_diff(scandir($sPath), array('.','..'));

			foreach($aFiles as $sFile)
			{
				if(is_dir($sPath . '/' . $sFile))
				{
					self::_deleteFolder($sPath . '/' . $sFile);
				}
				else
				{
					@unlink($sPath . '/' . $sFile);
				}
			}

			return @rmdir($sPath);
		}

		public static function output($sHtml, $sFormName = false, $iColspan = 1)
		{
			$sOutput = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>iDEAL Checkout Installatie Wizard</title>

		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-15">
		<meta http-equiv="content-language" content="nl-nl">

		<link href="https://www.ideal-checkout.nl/manuals/install/install.css" media="screen" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>';

			if($sFormName)
			{

				if(strcasecmp($sFormName, 'install-step-3') === 0)
				{
					$sOutput .= '
		<form action="" id="' . htmlentities($sFormName) . '" method="post" name="' . htmlentities($sFormName) . '" enctype="multipart/form-data">';

				}
				else
				{

				$sOutput .= '
		<form action="" id="' . htmlentities($sFormName) . '" method="post" name="' . htmlentities($sFormName) . '">';
				}

			$sOutput .= '
			<input name="form" type="hidden" value="' . htmlentities($sFormName) . '">';
			}

			$sOutput .= '
			<table align="center" border="0" cellpadding="3" cellspacing="0" width="580">
				<tr>
					<td align="left"' . (($iColspan > 1) ? ' colspan="' . $iColspan . '"' : '') . ' height="180" valign="top"><a href="https://www.ideal-checkout.nl" target="_blank"><img alt="iDEAL Checkout" border="0" height="131" src="https://www.ideal-checkout.nl/manuals/install/ideal-checkout-logo.png" width="456"></a></td>
				</tr>
				<tr>
					<td' . (($iColspan > 1) ? ' colspan="' . $iColspan . '"' : '') . '>&nbsp;</td>
				</tr>';

			if(substr(trim($sHtml), 0, 4) === '<tr>')
			{
				$sOutput .= $sHtml;
			}
			else
			{
				$sOutput .= '
				<tr>
					<td' . (($iColspan > 1) ? ' colspan="' . $iColspan . '"' : '') . '>' . $sHtml . '</td>
				</tr>';
			}

			$sOutput .= '
			</table>';

			if($sFormName)
			{
				$sOutput .= '
		</form>';
			}

			$sOutput .= '
	</body>
</html>';

			echo $sOutput;
			exit;
		}
	}

?>
