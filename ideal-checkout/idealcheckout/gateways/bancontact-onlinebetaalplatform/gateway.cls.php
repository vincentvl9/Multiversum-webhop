<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(dirname(__FILE__) . '/onlinebetaalplatform.cls.php');

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
						$sReturnUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/return.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code'];
						$sReportUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/report.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code'];
						$sLanguageCode = (empty($this->oRecord['language_code']) ? 'nl' : $this->oRecord['language_code']);
						
						$oOnlineBetaalPlatform = new OnlineBetaalPlatformPayment($this->aSettings['API_KEY'], $this->aSettings['PROFILE_UID']);						
						
						if(!empty($this->aSettings['TEST_MODE']))
						{
							$oOnlineBetaalPlatform->setTestmode();
						}
						
						$oOnlineBetaalPlatform->setPaymentMethod('bcmc'); // Bancontact MisterCash
						$oOnlineBetaalPlatform->setLanguageCode($sLanguageCode);
						$oOnlineBetaalPlatform->setOrder($this->oRecord['order_id'], $this->oRecord['transaction_amount'], $this->oRecord['transaction_description']);
						$oOnlineBetaalPlatform->setReturnUrl($sReturnUrl);
						$oOnlineBetaalPlatform->setReportUrl($sReportUrl);

						
						// Validate order_params
						$bProductDataError = false;
						$aProductData = array();
						
						$bCustomerDataError = false;
						$aCustomerData = array();
						
						if(!empty($this->oRecord['order_params']))
						{
							$aOrderParams = idealcheckout_unserialize($this->oRecord['order_params']);

							if(isset($aOrderParams['customer']) && isset($aOrderParams['customer']['shipment_first_name']) && isset($aOrderParams['customer']['shipment_last_name']) && isset($aOrderParams['customer']['shipment_email']))
							{
								$aCustomerData['buyer_name_first'] = substr($aOrderParams['customer']['shipment_first_name'], 0, 32);
								$aCustomerData['buyer_name_last'] = substr($aOrderParams['customer']['shipment_last_name'], 0, 32);
								$aCustomerData['buyer_emailaddress'] = substr($aOrderParams['customer']['shipment_email'], 0, 100);
							}
							else
							{
								idealcheckout_log('Customer data is incomplete.', __FILE__, __LINE__);
								$bCustomerDataError = true;
							}

							if(isset($aOrderParams['products']) && is_array($aOrderParams['products']) && sizeof($aOrderParams['products']))
							{
								foreach($aOrderParams['products'] as $k => $v)
								{
									if(isset($v['code']) && isset($v['description']) && isset($v['quantity']) && isset($v['price_incl']))
									{
										$aProduct = array();
										$aProduct['name'] = substr(preg_replace('/([^a-zA-Z0-9_\- ]+)/', '', $v['description']), 0, 32);
										$aProduct['code'] = substr(preg_replace('/([^a-zA-Z0-9_\-]+)/', '_', $v['code']), 0, 25);
										
										$aProduct['price'] = round($v['price_incl'] * 100);
										$aProduct['quantity'] = intval($v['quantity']);

										$aProductData[] = $aProduct;
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
						
							if($bProductDataError)
							{
								idealcheckout_log('This application doesn\'t seem to support Online Betaal Platform.', __FILE__, __LINE__);
							}
							else
							{
								$oOnlineBetaalPlatform->setCustomerData($aCustomerData);
								$oOnlineBetaalPlatform->setProductData($aProductData);
							}
						}
						
						if($oOnlineBetaalPlatform->getTransaction())
						{
							$this->oRecord['transaction_id'] = $oOnlineBetaalPlatform->getTransactionId();
							$this->oRecord['transaction_url'] = $oOnlineBetaalPlatform->getTransactionUrl();

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
							$sHtml .= '<p>' . $oOnlineBetaalPlatform->getError() . '</p>';
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

			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				// Lookup record
				if($this->getRecordByOrder())
				{
					// Verify status
					$oOnlineBetaalPlatform = new OnlineBetaalPlatformPayment($this->aSettings['API_KEY'], $this->aSettings['PROFILE_UID']);	
					
					if(!empty($this->aSettings['TEST_MODE']))
					{
						$oOnlineBetaalPlatform->setTestmode();
					}
					
					$sTransactionStatus = $oOnlineBetaalPlatform->getStatus($this->oRecord['transaction_id']);

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

							$sHtml .= '<p>Uw betaling is geannuleerd. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
						}
						else // if(strcasecmp($this->oRecord['transaction_status'], 'FAILURE') === 0)
						{
							if(!empty($this->oRecord['transaction_failure_url']))
							{
								header('Location: ' . $this->oRecord['transaction_failure_url']);
								exit;
							}

							$sHtml .= '<p>Uw betaling is mislukt. Probeer opnieuw te betalen.<br><input style="margin: 6px;" type="button" value="Verder" onclick="javascript: document.location.href = \'' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/setup.php?order_id=' . $this->oRecord['order_id'] . '&order_code=' . $this->oRecord['order_code']) . '\'"></p>';
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

			idealcheckout_output($sHtml);
		}


		// Catch return
		public function doReport()
		{
			$sHtml = '';

			if(empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				// Lookup record
				if($this->getRecordByOrder())
				{
					// Verify status
					$oOnlineBetaalPlatform = new OnlineBetaalPlatformPayment($this->aSettings['API_KEY'], $this->aSettings['PROFILE_UID']);
					
					if(!empty($this->aSettings['TEST_MODE']))
					{
						$oOnlineBetaalPlatform->setTestmode();
					}
					
					$sTransactionStatus = $oOnlineBetaalPlatform->getStatus($this->oRecord['transaction_id']);

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
								idealcheckout_update_order_status($this->oRecord, 'doReport');
							}
						}


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