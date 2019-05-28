<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(dirname(__FILE__) . '/omnikassa.cls.php');


	class Gateway extends GatewayCore
	{
		// Load iDEAL settings
		public function __construct()
		{
			$this->init();
		}

		// Setup payment
		public function doSetup()
		{
			global $aIdealCheckout;

			$sHtml = '';

			// Look for proper GET's en POST's
			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid transaction request.</p>';
			}
			else
			{
				$sOrderId = $_GET['order_id'];
				$sOrderCode = $_GET['order_code'];


				// Lookup transaction
				if($this->getRecordByOrder())
				{
					if(strcmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
					{
						$sHtml .= '<p>Transaction already completed</p>';
					}
					elseif((strcmp($this->oRecord['transaction_status'], 'OPEN') === 0) && !empty($this->oRecord['transaction_url']))
					{
						header('Location: ' . $this->oRecord['transaction_url']);
						exit;
					}
					else
					{
						$sReturnUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/return.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code'];
						// $sReportUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/report.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code'];
						$sLanguageCode = (empty($this->oRecord['language_code']) ? 'nl' : $this->oRecord['language_code']);

						$oOmnikassa = new OmnikassaPayment($this->aSettings['REFRESH_TOKEN'], $this->aSettings['SIGNING_KEY']);

						if(!empty($this->aSettings['TEST_MODE']))
						{
							$oOmnikassa->setTestmode();
						}

						$oOmnikassa->setPaymentMethod('afterpay');
						$oOmnikassa->setCachePath($this->aSettings['CACHE_PATH']);
						$oOmnikassa->setLanguageCode($sLanguageCode);
						$oOmnikassa->setOrder($this->oRecord['order_id'], $this->oRecord['transaction_description']);
						$oOmnikassa->setOrderAmount(round($this->oRecord['transaction_amount'] * 100));
						$oOmnikassa->setCurrencyCode($this->oRecord['currency_code']);
						$oOmnikassa->setReturnUrl($sReturnUrl);
						$oOmnikassa->setAccessToken();

						// Should be added
						// $oOmnikassa->setReportUrl($sReportUrl);


						$bCustomerShippingDataError = false;
						$aCustomerShippingData = array();
						$sCustomerShippingString = '';

						$bPaymentCustomerDataError = false;
						$aPaymentCustomerData = array();
						$sPaymentCustomerString = '';

						$bProductDataError = false;
						$aProductData = array();
						$sProductString = '';


						$bAdditionalCustomerDataError = false;
						$aAdditionalCustomerData = array();
						$sAdditionalCustomerString = '';

						if(!empty($this->oRecord['order_params']))
						{
							$aOrderParams = idealcheckout_unserialize($this->oRecord['order_params']);

							if(isset($aOrderParams['customer']) && isset($aOrderParams['customer']['shipment_first_name']) && isset($aOrderParams['customer']['shipment_last_name']) && isset($aOrderParams['customer']['shipment_street_name']) && isset($aOrderParams['customer']['shipment_street_number']) && isset($aOrderParams['customer']['shipment_zipcode']) && isset($aOrderParams['customer']['shipment_city']) && isset($aOrderParams['customer']['shipment_country_code']))
							{
								$aCustomerShippingData['firstName'] = substr($aOrderParams['customer']['shipment_first_name'], 0, 20);
								$aCustomerShippingData['middleName'] = '';
								$aCustomerShippingData['lastName'] = substr($aOrderParams['customer']['shipment_last_name'], 0, 50);
								$aCustomerShippingData['street'] = substr($aOrderParams['customer']['shipment_street_name'], 0, 100);
								$aCustomerShippingData['houseNumber'] = substr($aOrderParams['customer']['shipment_street_number'], 0, 5);
								
								if(!empty($aOrderParams['customer']['shipment_street_number_extension']))
								{
									$aCustomerShippingData['houseNumberAddition'] = $aOrderParams['customer']['shipment_street_number_extension'];
								}
								else
								{
									$aCustomerShippingData['houseNumberAddition'] = '';									
								}
								
								
								$aCustomerShippingData['postalCode'] = substr($aOrderParams['customer']['shipment_zipcode'], 0, 10);
								$aCustomerShippingData['city'] = substr($aOrderParams['customer']['shipment_city'], 0, 40);
								$aCustomerShippingData['countryCode'] = substr($aOrderParams['customer']['shipment_country_code'], 0, 2);

								$sCustomerShippingString = $aCustomerShippingData['firstName'] . ',' . $aCustomerShippingData['middleName'] . ',' . $aCustomerShippingData['lastName'] . ',' . $aCustomerShippingData['street'] . ',' . $aCustomerShippingData['houseNumber'] . ',' . $aCustomerShippingData['houseNumberAddition'] . ',' . $aCustomerShippingData['postalCode'] . ',' . $aCustomerShippingData['city'] . ',' . $aCustomerShippingData['countryCode'];

							}
							else
							{
								idealcheckout_log('Customer data is incomplete.', __FILE__, __LINE__);
								$bCustomerShippingDataError = true;
							}

							if(isset($aOrderParams['customer']) && isset($aOrderParams['customer']['payment_first_name']) && isset($aOrderParams['customer']['payment_last_name']) && isset($aOrderParams['customer']['payment_street_name']) && isset($aOrderParams['customer']['payment_street_number']) && isset($aOrderParams['customer']['payment_zipcode']) && isset($aOrderParams['customer']['payment_city']) && isset($aOrderParams['customer']['payment_country_code']))
							{
								$aPaymentCustomerData['firstName'] = substr($aOrderParams['customer']['payment_first_name'], 0, 20);
								$aPaymentCustomerData['middleName'] = '';
								$aPaymentCustomerData['lastName'] = substr($aOrderParams['customer']['payment_last_name'], 0, 50);
								$aPaymentCustomerData['street'] = substr($aOrderParams['customer']['payment_street_name'], 0, 100);
								$aPaymentCustomerData['houseNumber'] = substr($aOrderParams['customer']['payment_street_number'], 0, 5);
								
								if(!empty($aOrderParams['customer']['payment_street_number_extension']))
								{
									$aPaymentCustomerData['houseNumberAddition'] = $aOrderParams['customer']['payment_street_number_extension'];
								}
								else
								{
									$aPaymentCustomerData['houseNumberAddition'] = '';
								}
								
								$aPaymentCustomerData['postalCode'] = substr($aOrderParams['customer']['payment_zipcode'], 0, 10);
								$aPaymentCustomerData['city'] = substr($aOrderParams['customer']['payment_city'], 0, 40);
								$aPaymentCustomerData['countryCode'] = substr($aOrderParams['customer']['payment_country_code'], 0, 2);

								$sPaymentCustomerString = $aPaymentCustomerData['firstName'] . ',' . $aPaymentCustomerData['middleName'] . ',' . $aPaymentCustomerData['lastName'] . ',' . $aPaymentCustomerData['street'] . ',' . $aPaymentCustomerData['houseNumber'] . ',' . $aPaymentCustomerData['houseNumberAddition'] . ',' . $aPaymentCustomerData['postalCode'] . ',' . $aPaymentCustomerData['city'] . ',' . $aPaymentCustomerData['countryCode'];

							}
							else
							{
								idealcheckout_log('PaymentCustomer data is incomplete.', __FILE__, __LINE__);
								$bPaymentCustomerDataError = true;
							}

							if(isset($aOrderParams['customer']) && isset($aOrderParams['customer']['payment_first_name']) && isset($aOrderParams['customer']['payment_phone']) && isset($aOrderParams['customer']['payment_email']))
							{
								$aAdditionalCustomerData['emailAddress'] = $aOrderParams['customer']['payment_email'];
								
								if(!empty($aOrderParams['customer']['payment_date_of_birth']))
								{
									$aAdditionalCustomerData['dateOfBirth'] = date('d-m-Y', strtotime($aOrderParams['customer']['payment_date_of_birth']));
								}
								else
								{
									$aAdditionalCustomerData['dateOfBirth'] = '';
								}
								
								
								if(!empty($aOrderParams['customer']['payment_gender']))
								{
									$aAdditionalCustomerData['gender'] = $aOrderParams['customer']['payment_gender'];
								}
								else
								{
									$aAdditionalCustomerData['gender'] = '';									
								}

								// Get initials
								// Replace all non alphabetic characters for a hyphen
								$sFirstname = preg_replace('/([^A-Za-z]+)/', '-', $aOrderParams['customer']['payment_first_name']);
								$aFirstnameParts = explode('-', $sFirstname);

								$sInitials = '';

								foreach($aFirstnameParts as $aFirstname)
								{
									$sInitials .= strtoupper(substr($aFirstname, 0, 1));
								}

								$aAdditionalCustomerData['initials'] = $sInitials;
								$aAdditionalCustomerData['telephoneNumber'] = preg_replace('/\s+/', '', $aOrderParams['customer']['payment_phone']);

								$sAdditionalCustomerString = $aAdditionalCustomerData['emailAddress'] . ',' . $aAdditionalCustomerData['dateOfBirth'] . ',' . $aAdditionalCustomerData['gender'] . ',' . $aAdditionalCustomerData['initials'] . ',' . $aAdditionalCustomerData['telephoneNumber'];


							}
							else
							{
								idealcheckout_log('Additional Customer data is incomplete.', __FILE__, __LINE__);
								// $bAdditionalCustomerDataError = true;
							}



							if(isset($aOrderParams['products']) && is_array($aOrderParams['products']) && sizeof($aOrderParams['products']))
							{
								foreach($aOrderParams['products'] as $k => $v)
								{
									if(isset($v['code']) && isset($v['description']) && isset($v['quantity']) && isset($v['price_incl']) && isset($v['vat']))
									{
										$iVatCategory = '1';
										
										if(strcasecmp($v['vat'], '21') === 0)
										{
											$iVatCategory = '1';
										}
										elseif(strcasecmp($v['vat'], '6') === 0)
										{
											$iVatCategory = '2';
										}
										elseif(strcasecmp($v['vat'], '0') === 0)
										{
											$iVatCategory = '3';
										}
										else
										{
											$iVatCategory = '4';
										}


										$aProduct = array();

										$aProduct['id'] = substr($v['code'], 0, 25);
										$aProduct['name'] = substr(preg_replace('/([^a-zA-Z0-9_\- ]+)/', '', $v['description']), 0, 50);
										$aProduct['description'] = substr(preg_replace('/([^a-zA-Z0-9_\- ]+)/', '', $v['description']), 0, 100);

										$aProduct['quantity'] = intval($v['quantity']);


										$aProduct['amount'] = array();
										$aProduct['amount']['currency'] = 'EUR';
										$aProduct['amount']['amount'] = round($v['price_incl'] * 100);


										$aProduct['tax'] = array();
										$aProduct['tax']['currency'] = 'EUR';
										$aProduct['tax']['amount'] = 0;

										$aProduct['vatCategory'] = $iVatCategory;

										$aProduct['category'] = 'PHYSICAL';

										$aProductData[] = $aProduct;

										$sProductString .= $aProduct['id'] . ',' . $aProduct['name'] . ',' . $aProduct['description'] . ',' . $aProduct['quantity'] . ',' . $aProduct['amount']['currency'] . ',' . $aProduct['amount']['amount'] . ',' . $aProduct['tax']['currency'] . ',' . $aProduct['tax']['amount'] . ',' . $aProduct['category'] . ',' . $aProduct['vatCategory'] . ',';
									}
									else
									{
										idealcheckout_log('Product data is incomplete.', __FILE__, __LINE__);
										idealcheckout_log($v, __FILE__, __LINE__);
										$bProductDataError = true;
									}
								}
							}
							else
							{
								idealcheckout_log('Product data is incomplete.', __FILE__, __LINE__);
								$bProductDataError = true;
							}


							if($bCustomerShippingDataError || $bPaymentCustomerDataError || $bProductDataError)
							{
								idealcheckout_log('This application doesn\'t seem to support Rabo OmniKassa 2.0.', __FILE__, __LINE__);
							}
							else
							{
								$oOmnikassa->setCustomerShippingData($aCustomerShippingData, $sCustomerShippingString);
								$oOmnikassa->setCustomerBillingData($aPaymentCustomerData, $sPaymentCustomerString);
								$oOmnikassa->setProductData($aProductData, $sProductString);

								$oOmnikassa->setAdditionalCustomerData($aAdditionalCustomerData, $sAdditionalCustomerString);
							}
						}

						if($oOmnikassa->getTransaction())
						{
							$this->oRecord['transaction_url'] = $oOmnikassa->getTransactionUrl();
							$aTransactionSignature = $oOmnikassa->getTransactionSignature();

							$this->oRecord['transaction_params'] = idealcheckout_serialize($aTransactionSignature);

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= 'Requesting transaction on ' . date('Y-m-d, H:i:s') . '; recieved ID: "' . $this->oRecord['transaction_id'] . '" and URL: "' . $this->oRecord['transaction_url'] . '".';
							$this->save();

							header('Location: ' . $this->oRecord['transaction_url']);
							exit;
						}
						else
						{
							$sHtml .= '<p>' . $oOmnikassa->getError() . '</p>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid transaction request.</p>';
				}
			}

			idealcheckout_output($sHtml);
		}


		// Catch return
		public function doReturn()
		{
			$sHtml = '';

			if(empty($_GET['idealcheckout_order_id']) || empty($_GET['idealcheckout_order_code']) && empty($_GET['status']) && empty($_GET['order_id']) && empty($_GET['signature']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				$sMerchantOrderId = $_GET['order_id'];
				$sOmniKassaStatus = $_GET['status'];
				$sOmniKassaSignature = $_GET['signature'];

				// Lookup record
				if($this->getRecordByOrder())
				{

					// Verify status
					$oOmnikassa = new OmnikassaPayment($this->aSettings['REFRESH_TOKEN'], $this->aSettings['SIGNING_KEY']);

					if(!empty($this->aSettings['TEST_MODE']))
					{
						$oOmnikassa->setTestmode();
					}

					$sHashString = $sMerchantOrderId . ',' . $sOmniKassaStatus;
					$sHash = hash_hmac('sha512', $sHashString, base64_decode($this->aSettings['SIGNING_KEY']));

					if(strcasecmp($sOmniKassaSignature, $sHash) === 0)
					{
						$sTransactionStatus = $oOmnikassa->checkResponse($sOmniKassaStatus);

						if(in_array($sTransactionStatus, array('SUCCESS', 'OPEN', 'CANCELLED', 'FAILURE')))
						{
							$bStatusChanged = ((strcasecmp($this->oRecord['transaction_status'], $sTransactionStatus) !== 0) && !in_array($this->oRecord['transaction_status'], array('SUCCESS')));

							if($bStatusChanged)
							{
								$this->oRecord['transaction_status'] = $sTransactionStatus;

								if(empty($this->oRecord['transaction_log']) == false)
								{
									$this->oRecord['transaction_log'] .= "\n\n";
								}

								$this->oRecord['transaction_log'] .= 'Executing StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $this->oRecord['transaction_id'] . '. Recieved: ' . $this->oRecord['transaction_status'];

								$this->save();

								// Handle status change
								if(function_exists('idealcheckout_update_order_status'))
								{
									idealcheckout_update_order_status($this->oRecord, 'doReturn');
								}
							}


							// Set status message
							if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
							{
								if(!empty($this->oRecord['transaction_success_url']))
								{
									header('Location: ' . $this->oRecord['transaction_success_url']);
									exit;
								}

								$sHtml .= '<p>Uw betaling is met succes ontvangen.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1)) . '\'"></p>';
							}
							elseif(strcmp($this->oRecord['transaction_status'], 'OPEN') === 0)
							{
								if(!empty($this->oRecord['transaction_pending_url']))
								{
									header('Location: ' . $this->oRecord['transaction_pending_url']);
									exit;
								}

								$sHtml .= '<p>Uw betaling is in behandeling.<br><input style="margin: 6px;" type="button" value="Terug naar de website" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1)) . '\'"></p>';
							}
							elseif(strcasecmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
							{
								if(!empty($this->oRecord['transaction_failure_url']))
								{
									header('Location: ' . $this->oRecord['transaction_failure_url']);
									exit;
								}

								$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
							else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
							{
								if(!empty($this->oRecord['transaction_failure_url']))
								{
									header('Location: ' . $this->oRecord['transaction_failure_url']);
									exit;
								}

								$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code']) . '\'"></p>';
							}
						}
						else
						{
							$sHtml .= '<p>Er is geen status gevonden.</p>';
						}
					}
					else
					{
						$sHtml .= '<p>Invalid return request.</p>';
					}
				}
				else
				{
					$sHtml .= '<p>Invalid return request.</p>';
				}
			}

			idealcheckout_output($sHtml);
		}


		// Catch report
		public function doReport()
		{
			$sHtml = '';

			$sJsonData = @file_get_contents('php://input');	
		
			if(empty($sJsonData))
			{
				$sHtml .= '<p>Invalid notification call.</p>';
			}
			else
			{
				$aPostData = json_decode($sJsonData, true);

				if(!empty($aPostData['authentication']) && !empty($aPostData['expiry']) && !empty($aPostData['eventName']) && !empty($aPostData['poiId']) && !empty($aPostData['signature']))
				{			
					$sAuthToken = $aPostData['authentication'];
					$sExpiryString = $aPostData['expiry'];
					$sEventnameString = $aPostData['eventName'];
					$sPoiIdString = $aPostData['poiId'];
					$sSignatureString = $aPostData['signature'];
					
					// Controleer de signature met de data die is verstuurd
					$sHashString = $sAuthToken . ',' . $sExpiryString . ',' . $sEventnameString . ',' . $sPoiIdString;
					
					$sHash = hash_hmac('sha512', $sHashString, base64_decode($this->aSettings['SIGNING_KEY']));
					
					if(hash_equals($sSignatureString, $sHash))
					{
						$sEventName = 'merchant.order.status.changed'; //$aPostData['eventName'];
						$bNextOrder = false;
								
						$sApiUrl = 'https://betalen.rabobank.nl/omnikassa-api' . ($this->aSettings['TEST_MODE'] ? '-sandbox' : '') . '/order/server/api/events/results/merchant.order.status.changed';
				
						$aDatabaseSettings = idealcheckout_getDatabaseSettings();
						$aResult = array();
					
						do
						{
							$sResponse = idealcheckout_doHttpRequest($sApiUrl, false, true, 30, false, array('Expect:', 'Authorization: Bearer ' . $sAuthToken));	
							$aResult = json_decode($sResponse, true);
							
							if(!empty($aResult))
							{
								$sSignature = $aResult['signature'];
								$bMoreOrders = $aResult['moreOrderResultsAvailable'];	
								
								if($bMoreOrders)
								{
									$sMoreOrdersAvailable = 'true';
								}
								else
								{
									$sMoreOrdersAvailable = 'false';
									
								}
								
								$sHashString = $sMoreOrdersAvailable . ',';
													
								// Validate total signature
								foreach($aResult['orderResults'] as $aTransaction)
								{
									// Setup total hash string
									$sTransactionString = $aTransaction['merchantOrderId'] . ',' . $aTransaction['omnikassaOrderId'] . ',' . $aTransaction['poiId'] . ',' . $aTransaction['orderStatus'] . ',' . $aTransaction['orderStatusDateTime'] . ',' . $aTransaction['errorCode'] . ',' . $aTransaction['paidAmount']['currency'] . ',' . $aTransaction['paidAmount']['amount'] . ',' . $aTransaction['totalAmount']['currency'] . ',' . $aTransaction['totalAmount']['amount'];
									
									$sHashString .= $sTransactionString . ',';
								}
								
								// Cut off last comma
								$sHashString = substr($sHashString, 0, -1);
								$sHash = hash_hmac('sha512', $sHashString, base64_decode($this->aSettings['SIGNING_KEY']));
									
								if(hash_equals($sSignature, $sHash))
								{
									foreach($aResult['orderResults'] as $aTransaction)
									{
										$sMerchantOrderId = $aTransaction['merchantOrderId'];							
										$sOmnikassaStatus = $aTransaction['orderStatus'];
										
										$sql = "SELECT * FROM `" . $aDatabaseSettings['table'] . "` WHERE (`order_id` = '" . idealcheckout_escapeSql($sMerchantOrderId) . "') ORDER BY `id` DESC LIMIT 1";
										$this->oRecord = idealcheckout_database_getRecord($sql);
										
										
										if(strcasecmp($sOmnikassaStatus, 'COMPLETED') === 0)
										{
											$sTransactionStatus = 'SUCCESS';	
										}
										elseif(strcasecmp($sOmnikassaStatus, 'CANCELLED') === 0)
										{
											$sTransactionStatus = 'CANCELLED';
										}
										elseif(strcasecmp($sOmnikassaStatus, 'EXPIRED') === 0)
										{
											$sTransactionStatus = 'FAILURE';
										}
										else // pending
										{
											$sTransactionStatus = 'PENDING';	
										}
										
										$this->oRecord['transaction_status'] = $sTransactionStatus;
										$this->oRecord['transaction_id'] = $aTransaction['omnikassaOrderId'];

										if(empty($this->oRecord['transaction_log']) == false)
										{
											$this->oRecord['transaction_log'] .= "\n\n";
										}

										$this->oRecord['transaction_log'] .= 'Executing Callback StatusRequest on ' . date('Y-m-d, H:i:s') . ' for #' . $aTransaction['omnikassaOrderId'] . '. Recieved: ' . $this->oRecord['transaction_status'];

										$this->save();

										// Handle status change
										if(function_exists('idealcheckout_update_order_status'))
										{
											idealcheckout_update_order_status($this->oRecord, 'doReport');
										}
									}
								}
							}						
						}
						while($bMoreOrders);	
					}
				}
				else
				{
					$sHtml .= '<p>Invalid notification call.</p>';
				}
			}
			
			idealcheckout_output($sHtml);
		}
	}

?>
