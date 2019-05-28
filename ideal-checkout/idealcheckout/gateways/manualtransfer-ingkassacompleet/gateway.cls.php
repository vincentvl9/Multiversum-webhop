<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(dirname(__FILE__) . '/ingkassacompleet.cls.php');

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
						$sReportUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/report.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code'];

						$oKassaCompleet = new KassaCompleetPayment($this->aSettings['API_KEY']);
						$oKassaCompleet->setPaymentMethod('bank-transfer');
						$oKassaCompleet->setOrder($this->oRecord['order_id'], $this->oRecord['transaction_amount'], $this->oRecord['transaction_description']);
						$oKassaCompleet->setReturnUrl($sReturnUrl);
						$oKassaCompleet->setReportUrl($sReportUrl);

						
						$bOrderLinesError = false;
						$aOrderlines = array();
						$aOrderline = array();
						$sOrderlinesString = '';

						$bCustomerDataError = false;
						$aCustomerData = array();
						$sCustomerDataString = '';

						if(!empty($this->oRecord['order_params']))
						{
							$aOrderParams = idealcheckout_unserialize($this->oRecord['order_params']);						
							
							
							if(isset($aOrderParams['customer']['shipment_street_name']) && isset($aOrderParams['customer']['shipment_street_number']) && isset($aOrderParams['customer']['shipment_zipcode']) && isset($aOrderParams['customer']['shipment_city']) && isset($aOrderParams['customer']['shipment_country_code']) && isset($aOrderParams['customer']['payment_street_name']) && isset($aOrderParams['customer']['payment_street_number']) && isset($aOrderParams['customer']['payment_zipcode']) && isset($aOrderParams['customer']['payment_city']) && isset($aOrderParams['customer']['payment_date_of_birth']) && isset($aOrderParams['customer']['payment_country_code']) && isset($aOrderParams['customer']['payment_email']) && isset($aOrderParams['customer']['payment_first_name']) && isset($aOrderParams['customer']['payment_gender']) && isset($aOrderParams['customer']['payment_last_name']) && isset($aOrderParams['customer']['payment_phone']) && isset($this->oRecord['language_code']))
							{
								$aCustomerPaymentAddress = array($aOrderParams['customer']['payment_street_name'], $aOrderParams['customer']['payment_street_number'], $aOrderParams['customer']['payment_zipcode'], $aOrderParams['customer']['payment_city']);
								
								$aCustomerData['additional_address'] = array();
								$aCustomerData['additional_address']['address'] = implode(' ', $aCustomerPaymentAddress);
								$aCustomerData['additional_address']['address_type'] = 'billing';
								$aCustomerData['additional_address']['country'] = $aOrderParams['customer']['shipment_country_code'];
								
								
								$aCustomerShipmentAddress = array($aOrderParams['customer']['shipment_street_name'], $aOrderParams['customer']['shipment_street_number'], $aOrderParams['customer']['shipment_zipcode'], $aOrderParams['customer']['shipment_city']);

								$aCustomerData['address'] = implode(' ', $aCustomerShipmentAddress);
								$aCustomerData['address_type'] = 'customer';
								
								if(!empty($aOrderParams['customer']['payment_date_of_birth']))
								{								
									if(is_int($aOrderParams['customer']['payment_date_of_birth']))
									{
										$aCustomerData['birthdate'] = date('Y-m-d', $aOrderParams['customer']['payment_date_of_birth']);
									}
									else
									{
										$aCustomerData['birthdate'] = $aOrderParams['customer']['payment_date_of_birth'];
									}
								}
								else
								{
									$aCustomerData['birthdate'] = '';
								}
								
								
								$aCustomerData['country'] = $aOrderParams['customer']['payment_country_code'];
								$aCustomerData['email_address'] = $aOrderParams['customer']['payment_email'];
								$aCustomerData['first_name'] = $aOrderParams['customer']['payment_first_name'];

								if($aOrderParams['customer']['payment_gender'] == 'M')
								{
									$aCustomerData['gender'] = 'male';
								}
								elseif($aOrderParams['customer']['payment_gender'] == 'V')
								{
									$aCustomerData['gender'] = 'female';
								}
								elseif($aOrderParams['customer']['payment_gender'] == '')
								{
									$aCustomerData['gender'] = '';
								}

								$aCustomerData['ip_address'] = $_SERVER['REMOTE_ADDR'];
								$aCustomerData['last_name'] = $aOrderParams['customer']['payment_last_name'];
								$aCustomerData['locale'] = $this->oRecord['language_code'];
								$aCustomerData['phone_numbers'][0] = $aOrderParams['customer']['payment_phone'];
							}
							else
							{
								idealcheckout_log('Customer data incomplete.', __file__, __line__);
								$bCustomerDataError = true;
							}

							if(isset($aOrderParams['products'][0]['price_incl']) && isset($this->oRecord['currency_code']) && isset($aOrderParams['products'][0]['description']) && isset($aOrderParams['products'][0]['vat']) && isset($aOrderParams['products'][0]['quantity']))
							{
								$iProductCount = 1;

								foreach($aOrderParams['products'] as $aOrderProduct)
								{
									$aOrderline['amount'] = round($aOrderProduct['price_incl'] * 100);
									$aOrderline['currency'] = $this->oRecord['currency_code'];
									$aOrderline['merchant_order_line_id'] = strval($iProductCount);
									$aOrderline['name'] = $aOrderProduct['description'];
									$aOrderline['quantity'] = intval($aOrderProduct['quantity']);
									$aOrderline['type'] = 'physical';
									$aOrderline['vat_percentage'] = round($aOrderProduct['vat'] * 100);

									$aOrderLines[] = $aOrderline;
									$iProductCount++;
								}
							}
							else
							{
								idealcheckout_log('Product data incomplete.', __file__, __line__);
								$bOrderLinesError = true;
							}

							if($bCustomerDataError || $bOrderLinesError)
							{
								idealcheckout_log('This application doesn\'t seem to support ING Kassa Compleet', __FILE__, __LINE__);
							}
							else
							{
								$oKassaCompleet->setProductData($aOrderLines);
								$oKassaCompleet->setCustomerData($aCustomerData);
							}
						}
						else
						{
							idealcheckout_log('Data incomplete.', __file__, __line__);
						}

						if(($bOrderLinesError == false) && ($bCustomerDataError == false))
						{
							if($oKassaCompleet->getTransaction())
							{
								$this->oRecord['transaction_id'] = $oKassaCompleet->getTransactionId();
								$this->oRecord['transaction_params'] = $oKassaCompleet->getPaymentDetails();

								if(empty($this->oRecord['transaction_log']) == false)
								{
									$this->oRecord['transaction_log'] .= "\n\n";
								}

								$aTransactionParams = $this->oRecord['transaction_params'];

								$this->oRecord['transaction_log'] .= 'Requesting transaction on ' . date('Y-m-d, H:i:s') . '; recieved ID: "' . $this->oRecord['transaction_id'] . '" and Params: "' . $aTransactionParams . '".';

								$this->oRecord['transaction_status'] = $oKassaCompleet->getStatus($this->oRecord['transaction_id']);
								$this->save();

								if(($this->oRecord['transaction_status'] == 'PENDING') && ($this->oRecord['gateway_code'] == 'manualtransfer'))
								{
									$aManualTransferTransactionParams = json_decode($this->oRecord['transaction_params'], true);
									$fAmount = json_decode($this->oRecord['transaction_amount'], true);
									$sCurrency = $oKassaCompleet->getValuta($this->oRecord['currency_code']);

									
									$sHtml .= "<p>Your order has been successfully processed!</p><p>Your order will be shipped after your payment to the credentials with the Reference shown below.</p>";
									$sHtml .= "Amount: " . $sCurrency . '' . $fAmount . "<br>";
									$sHtml .= "Iban: " . $aManualTransferTransactionParams['consumer_iban'] . "<br>";
									$sHtml .= "Name of recipient: " . $aManualTransferTransactionParams['consumer_name'] . "<br>";
									$sHtml .= "Reference: " . $aManualTransferTransactionParams['reference'] ."<br>";

									$sHtml .= "Click <a href='" . $this->oRecord['transaction_pending_url'] . "'>here</a> to return to the shop";
								}
							}
							else
							{
								$sHtml .= '<p>' . $oKassaCompleet->getError() . '</p>';
							}
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
				$aResponse = json_decode($sJsonData);

				if(empty($aResponse['order_id']))
				{
					// No order_id was gotten from the webhook request
					$sHtml .= '<p>No OrderID found from ING.</p>';
					idealcheckout_output($sHtml);
					return false;
				}

				$sOrderId = $aResponse['order_id'];

				if($this->getRecordByOrder($sOrderId))
				{
					// Verify status
					$oKassaCompleet = new KassaCompleetPayment($this->aSettings['API_KEY']);
					$sTransactionStatus = $oKassaCompleet->getStatus($this->oRecord['transaction_id']);


					if(in_array($sTransactionStatus, array('SUCCESS', 'OPEN', 'PENDING', 'CANCELLED', 'FAILURE')))
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
								idealcheckout_update_order_status($this->oRecord, 'doReport');
							}
						}

						idealcheckout_doTransactionLog();

						// Set status message
						if(strcasecmp($this->oRecord['transaction_status'], 'SUCCESS') === 0)
						{
							$sHtml .= '<p>Uw betaling is met succes ontvangen.</p>';
						}
						elseif(strcmp($this->oRecord['transaction_status'], 'OPEN') === 0)
						{
							$sHtml .= '<p>Uw betaling is in behandeling.</p>';
						}
						elseif(strcasecmp($this->oRecord['transaction_status'], 'CANCELLED') === 0)
						{
							$sHtml .= '<p>Uw betaling is geannuleerd.</p>';
						}
						else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
						{
							$sHtml .= '<p>Uw betaling is mislukt.</p>';
						}
					}
					else
					{
						$sHtml .= '<p>Er is geen status gevonden.</p>';
					}
				}
				else
				{
					$sHtml .= '<p>Invalid report request.</p>';
				}
			}

			idealcheckout_output($sHtml);
		}
	}

?>