<?php

	class OmnikassaPayment
	{
		private $sRefreshToken = false;
		private $sSigningKey = false;
		private $bTestMode = false;
		private $sCachePath = false;
		private $sPaymentMethod = false;
		private $sLanguageCode = false;
		
		private $aCustomerShippingData = array();
		private $sCustomerShippingString = false;
		
		private $aCustomerBillingData = array();
		private $sCustomerBillingString = false;
		
		private $aAdditionalCustomerData = array();
		private $sAdditionalCustomerString = false;
		
		private $aProductData = array();
		private $sProductString = false;

		private $sReturnUrl = false;
		private $sReportUrl = false;

		private $sOrderId = false;
		private $fAmount = false;
		private $sCurrencyCode = 'EUR';
		private $sDescription = false;
		private $sAccessToken = false;

		private $aTransaction = false;
		private $aTransactionResults = false;

		public function __construct($sRefreshToken, $sSigningKey)
		{
			$this->sRefreshToken = $sRefreshToken;
			$this->sSigningKey = $sSigningKey;
		}
		
		// Should point to directory where cache is strored
		public function setCachePath($sPath = false)
		{
			$this->sCachePath = $sPath;
		}		

		public function setLanguageCode($sLanguageCode = false)
		{
			if(is_bool($sLanguageCode))
			{
				$this->sLanguageCode = false;
				return true;
			}
			elseif(is_string($sLanguageCode))
			{
				$sLanguageCode = strtolower(substr($sLanguageCode, 0, 2));

				if(in_array($sLanguageCode, array('nl', 'fr', 'de', 'es', 'en')))
				{
					$this->sLanguageCode = $sLanguageCode;
					return true;
				}
			}

			return false;
		}

		public function setPaymentMethod($sPaymentMethod = false)
		{
			if(is_bool($sPaymentMethod))
			{
				$this->sPaymentMethod = false;
				return true;
			}
			elseif(is_string($sPaymentMethod))
			{
				$sPaymentMethod = strtolower($sPaymentMethod);

				if(in_array($sPaymentMethod, array('ideal', 'maestro', 'mastercard', 'paypal', 'visa', 'v_pay', 'bancontact', 'afterpay')))
				{
					$this->sPaymentMethod = $sPaymentMethod;
					return true;
				}
			}

			return false;
		}

		public function setTestmode($bEnabled = true)
		{
			return ($this->bTestMode = $bEnabled);
		}	
		
		
		public function setOrder($sOrderId, $sDescription = false)
		{
			$this->sOrderId = $sOrderId;
			$this->sDescription = $sDescription;

			if(empty($this->sDescription))
			{
				$this->sDescription = 'Webshop bestelling ' . $this->sOrderId;
			}

			return true;
		}
		
		
		public function setOrderAmount($fAmount)
		{
			$this->fAmount = $fAmount;
			
			return true;
		}
		
		public function setCurrencyCode($sCurrencyCode = false)
		{
			if(is_bool($sCurrencyCode))
			{
				$this->sCurrencyCode = false;
				return true;
			}
			elseif(is_string($sCurrencyCode))
			{
				$sCurrencyCode = strtoupper(substr($sCurrencyCode, 0, 3));

				if(in_array($sCurrencyCode, array('EUR')))
				{
					$this->sCurrencyCode = $sCurrencyCode;
					return true;
				}
			}

			return false;
		}

		
		public function setReturnUrl($sReturnUrl = false)
		{
			$this->sReturnUrl = $sReturnUrl;
			return true;
		}

/*
		public function setReportUrl($sReportUrl = false)
		{
			$this->sReportUrl = $sReportUrl;
			return true;
		}
*/
	
		public function setAccessToken()
		{
			$sCacheFile = false;

			// Used cached access token?
			if(($this->bTestMode == false) && $this->sCachePath)
			{
				$sStoreHost = md5($_SERVER['SERVER_NAME']);
				$sCacheFile = $this->sCachePath . 'token.' . $sStoreHost . '.cache';

				if(!file_exists($sCacheFile))
				{
					// Attempt to create cache file
					if(@touch($sCacheFile))
					{
						@chmod($sCacheFile, 0777);
					}
				}
				elseif(is_readable($sCacheFile) && is_writable($sCacheFile))
				{
					// Read data from cache file
					if($sData = file_get_contents($sCacheFile))
					{
						$aToken = idealcheckout_unserialize($sData);

						// Get current time to compare expiration of the access token
						$sCurrentTimestamp = time();

						if(isset($aToken['validUntil']))
						{
							// Change the valid until ISO notation to UNIX timestamp
							$sExpirationTimestamp = strtotime($aToken['validUntil']);

							if($sCurrentTimestamp <= $sExpirationTimestamp)
							{
								$this->sAccessToken = $aToken['token'];
								return true;

							}
						}
					}
				}
				else
				{
					$sCacheFile = false;
				}
			}

			$sUrl = 'https://betalen.rabobank.nl/omnikassa-api' . ($this->bTestMode ? '-sandbox' : '') . '/gatekeeper/refresh';

			$sResponse = idealcheckout_doHttpRequest($sUrl, '', true, 30, false, array('Expect:', 'Authorization: Bearer ' . $this->sRefreshToken));

			if(!empty($sResponse))
			{
				$aToken = idealcheckout_unserialize($sResponse);

				if(sizeof($aToken))
				{
					// Save data in cache?
					if($sCacheFile)
					{
						file_put_contents($sCacheFile, idealcheckout_serialize($aToken));
					}

					$this->sAccessToken = $aToken['token'];
					return true;
				}
				else
				{
					idealcheckout_log('Invalid response received from Rabo OmniKassa, check log files.', __FILE__, __LINE__);
					idealcheckout_log($sResponse, __FILE__, __LINE__);
				}
			}
			else
			{
				idealcheckout_log('No accesstoken could be created, check configuration.', __FILE__, __LINE__);
			}

			return false;
		}
	
		public function setCustomerShippingData($aCustomerShippingData, $sCustomerShippingString)
		{
			$this->aCustomerShippingData = $aCustomerShippingData;
			$this->sCustomerShippingString = $sCustomerShippingString;
			
		}
				
		public function setCustomerBillingData($aPaymentCustomerData, $sPaymentCustomerString)
		{
			$this->aPaymentCustomerData = $aPaymentCustomerData;
			$this->sPaymentCustomerString = $sPaymentCustomerString;
		}		
				
				
		public function setProductData($aProductData, $sProductString)
		{
			$this->aProductData = $aProductData;
			// $this->sProductString =  $sProductString;
			$this->sProductString =  substr($sProductString, 0, -1);
		}		
			
		public function setAdditionalCustomerData($aAdditionalCustomerData, $sAdditionalCustomerString)
		{
			$this->aAdditionalCustomerData = $aAdditionalCustomerData;
			$this->sAdditionalCustomerString = $sAdditionalCustomerString;
		}

			
		public function getTransaction()
		{
			if(empty($this->sSigningKey))
			{
				idealcheckout_log('No Signing Key found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No Signing Key found.'));
				return false;
			}
			elseif(empty($this->sAccessToken))
			{
				idealcheckout_log('No Access token could be generated.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No Access token could be generated.'));
				return false;				
			}
			elseif(empty($this->sOrderId))
			{
				idealcheckout_log('No order ID found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No order ID found.'));
				return false;
			}
			elseif(empty($this->fAmount))
			{
				idealcheckout_log('No amount found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No amount found.'));
				return false;
			}
			elseif($this->fAmount < 0.29)
			{
				idealcheckout_log('Amount ' . number_format($this->fAmount, 2, ',', '') . ' is to small to process order #' . $this->sOrderId . '.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'Amount ' . number_format($this->fAmount, 2, ',', '') . ' is to small to process order #' . $this->sOrderId . '.'));
				return false;
			}
			elseif(empty($this->sReturnUrl))
			{
				idealcheckout_log('No return URL found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No return URL found.'));
				return false;
			}

			$aRequest['timestamp'] = date('c', time());
			$aRequest['merchantOrderId'] = $this->sOrderId;
			$aRequest['amount'] = array();
			$aRequest['amount']['currency'] = 'EUR';
			$aRequest['amount']['amount'] = $this->fAmount;
			$aRequest['language'] = $this->sLanguageCode;
			$aRequest['description'] = $this->sDescription;
			$aRequest['merchantReturnURL'] = $this->sReturnUrl;
			
			// Optional
			$aRequest['orderItems'] = $this->aProductData;
			$aRequest['shippingDetail'] = $this->aCustomerShippingData;
			$aRequest['billingDetail'] = $this->aPaymentCustomerData;
			$aRequest['paymentBrand'] = strtoupper($this->sPaymentMethod);
			$aRequest['paymentBrandForce'] = 'FORCE_ALWAYS';
			$aRequest['customerInformation'] = $this->aAdditionalCustomerData;
			
		
			
			$sHashString = $aRequest['timestamp'] . ',' . $aRequest['merchantOrderId'] . ',' . $aRequest['amount']['currency'] . ',' . $aRequest['amount']['amount'] . ',' . $aRequest['language'] . ',' . $aRequest['description'] . ',' . $aRequest['merchantReturnURL'] . ',' . $this->sProductString . ',' . $this->sCustomerShippingString . ',' . $aRequest['paymentBrand'] . ',' . $aRequest['paymentBrandForce'] . ',' . $this->sAdditionalCustomerString . ',' . $this->sPaymentCustomerString;

			
			//$sHashString = $aRequest['timestamp'] . ',' . $aRequest['merchantOrderId'] . ',' . $aRequest['amount']['currency'] . ',' . $aRequest['amount']['amount'] . ',' . $aRequest['language'] . ',' . $aRequest['description'] . ',' . $aRequest['merchantReturnURL'] . ',' . $this->sProductString . ',' . $this->sCustomerShippingString . ',' . $this->sPaymentCustomerString;
			
			
			$sHash = hash_hmac('sha512', $sHashString, base64_decode($this->sSigningKey));			
			
			$aRequest['signature'] = $sHash;
	
			
			$sApiUrl = 'https://betalen.rabobank.nl/omnikassa-api' . ($this->bTestMode ? '-sandbox' : '') . '/order/server/api/order';
			$sPostData = json_encode($aRequest);				
			
			idealcheckout_log($sPostData, __FILE__,__LINE__);

			$sResponse = idealcheckout_doHttpRequest($sApiUrl, $sPostData, true, 30, false, array('Expect:', 'Authorization: Bearer ' . $this->sAccessToken));

			
			if(!empty($sResponse))
			{
				$this->aTransaction = json_decode($sResponse, true);

				if($this->aTransaction)
				{
					if(isset($this->aTransaction['signature']) && isset($this->aTransaction['redirectUrl']))
					{
						return true;
					}
					elseif(isset($this->aTransaction['errorCode'], $this->aTransaction['consumerMessage']))
					{
						if(strcasecmp($this->aTransaction['errorCode'], '5005') === 0)
						{
							$this->aTransaction = array('error' => array('message' => 'Error: ' . $this->aTransaction['errorCode'] . '. De betaling kan niet gestart worden omdat de geselecteerde betaalmethode nog niet actief is op het account. Neem contact op met de Rabobank OmniKassa. Meer informatie: <a target="_blank" href="https://www.ideal-checkout.nl/faq-ic/payment-providers/rabo-omnikassa-2-0/rok-2-error-codes">Klik hier</a>'));
						}
						elseif(strcasecmp($this->aTransaction['errorCode'], '5001') === 0)
						{
							$this->aTransaction = array('error' => array('message' => 'Error: ' . $this->aTransaction['errorCode'] . '. Er is iets mis gegaan bij het signeren van het bericht. Neem contact op met iDEAL Checkout. Meer informatie: <a target="_blank" href="https://www.ideal-checkout.nl/faq-ic/payment-providers/rabo-omnikassa-2-0/rok-2-error-codes">Klik hier</a>'));
						}
						else
						{
							$this->aTransaction = array('error' => array('message' => 'Unknown response received from Rabo OmniKassa (See logs).'));
						}
					}
					elseif(isset($this->aTransaction['errorCode'], $this->aTransaction['errorMessage']))
					{
						if(strcasecmp($this->aTransaction['errorCode'], '5001') === 0)
						{
							$this->aTransaction = array('error' => array('message' => 'Error: ' . $this->aTransaction['errorCode'] . '. Het verzoek is afgekeurd door de Rabo OmniKassa. Meer informatie vindt u hier: <a target="_blank" href="https://www.ideal-checkout.nl/faq-ic/payment-providers/rabo-omnikassa-2-0/authenticatie-errors">Klik hier</a>'));
						}
						else
						{
							$this->aTransaction = array('error' => array('message' => 'Unknown response received from Rabo OmniKassa (See logs).'));
						}
												
					}
				}
				else
				{
					$this->aTransaction = array('error' => array('message' => 'Cannot decode JSON response (See logs).'));
				}
			}
			else
			{
				$this->aTransaction = array('error' => array('message' => 'No response received from Rabo OmniKassa (See logs).'));
			}

			idealcheckout_log($aRequest, __FILE__, __LINE__);
			idealcheckout_log($sResponse, __FILE__, __LINE__);

			return false;
		}

	
		public function getTransactionUrl()
		{
			if(!empty($this->aTransaction['redirectUrl']))
			{
				return $this->aTransaction['redirectUrl'];
			}

			return false;
		}
		
		public function getTransactionSignature()
		{
			if(!empty($this->aTransaction['signature']))
			{
				return array('signature' => $this->aTransaction['signature']);
			}

			return false;
		}

		public function getError()
		{
			if(!empty($this->aTransaction['error']['message']))
			{
				return $this->aTransaction['error']['message'];
			}

			return false;
		}

		public function checkResponse($sOmniKassaStatus)
		{
			if(!empty($sOmniKassaStatus))
			{
				if(strcasecmp($sOmniKassaStatus, 'IN_PROGRESS') === 0)
				{
					return 'PENDING';
				}
				elseif(strcasecmp($sOmniKassaStatus, 'COMPLETED') === 0)
				{
					return 'SUCCESS';
				}
				elseif(strcasecmp($sOmniKassaStatus, 'CANCELLED') === 0)
				{
					return 'CANCELLED';
				}
				elseif(strcasecmp($sOmniKassaStatus, 'EXPIRED') === 0)
				{
					return 'FAILURE';
				}
				
			}

			return '';
		}
		
		
		
		
	}

?>