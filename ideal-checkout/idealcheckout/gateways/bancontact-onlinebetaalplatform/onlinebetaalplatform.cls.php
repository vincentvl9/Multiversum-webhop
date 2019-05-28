<?php

	/*
		API DOCS: https://onlinebetaalplatform.nl/nl/public/developer/api
	*/

	class OnlineBetaalPlatformPayment
	{
		private $sApiKey = false;
		private $sProfileUid = false;
		private $bTestMode = false;
		private $sPaymentMethod = false;
		private $sLanguageCode = false;

		private $sReturnUrl = false;
		private $sReportUrl = false;

		private $sOrderId = false;
		private $fAmount = false;
		private $sDescription = false;

		private $aTransaction = false;
		private $aCustomerData = array();
		private $aProductData = array();

		public function __construct($sApiKey, $sProfileUid)
		{
			$this->sApiKey = $sApiKey;
			$this->sProfileUid = $sProfileUid;
		}
		
		public function setTestmode($bEnabled = true)
		{
			return ($this->bTestMode = $bEnabled);
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

				// Currently restricted to language codes NL and EN
				if(in_array($sLanguageCode, array('nl', 'en')))
				{
					$this->sLanguageCode = $sLanguageCode;
					return true;
				}
				else
				{
					$this->sLanguageCode = 'nl';
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

				if(in_array($sPaymentMethod, array('ideal', 'bcmc', 'paypal')))
				{
					$this->sPaymentMethod = $sPaymentMethod;
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

		public function setReportUrl($sReportUrl = false)
		{
			$this->sReportUrl = $sReportUrl;
			return true;
		}

		public function setOrder($sOrderId, $fAmount, $sDescription = false)
		{
			$this->sOrderId = $sOrderId;
			$this->fAmount = $fAmount;
			$this->sDescription = $sDescription;

			if(empty($this->sDescription))
			{
				$this->sDescription = 'Webshop bestelling ' . $this->sOrderId;
			}

			return true;
		}

		public function setProductData($aProductData = array())
		{
			$this->aProductData = $aProductData;
		}

		public function setCustomerData($aCustomerData = array())
		{
			$this->aCustomerData = $aCustomerData;
		}

		
		public function getTransaction()
		{
			if(empty($this->sApiKey))
			{
				idealcheckout_log('No API key found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No API key found.'));
				return false;
			}
			elseif(empty($this->sProfileUid))
			{
				idealcheckout_log('No Profile UID found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No Profile UID found.'));
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
			elseif($this->fAmount < 1.00)
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
	
			
/*
			elseif(empty($this->sReportUrl))
			{
				idealcheckout_die('No report URL found.', __FILE__, __LINE__);
			}
*/


			$aRequest = array();
			
			// Order data
			$aRequest['metadata'] = array('order_id' => $this->sOrderId); // Order id as extra data
			$aRequest['total_price'] = round($this->fAmount * 100); // Order amount in cents
			$aRequest['payment_flow'] = 'direct';
			$aRequest['checkout'] = 'false';
			$aRequest['profile_uid'] = $this->sProfileUid;

			// Set return URL
			if(!empty($this->sReturnUrl))
			{
				$aRequest['return_url'] = $this->sReturnUrl; // Return URL
			}

			// Set report URL
			if(!empty($this->sReportUrl))
			{
				$aRequest['notify_url'] = $this->sReportUrl; // Report URL
			}

			// Set locale
			if(!empty($this->sLanguageCode))
			{
				$aRequest['locale'] = $this->sLanguageCode;
			}

			// Set payment method
			if(!empty($this->sPaymentMethod))
			{
				$aRequest['payment_method'] = $this->sPaymentMethod;
			}
	
			if(!empty($this->aCustomerData))
			{
				$aRequest = array_merge($aRequest, $this->aCustomerData);
			}
	
	
			if(empty($this->aProductData))
			{
				idealcheckout_log('No product data found, use default product.', __FILE__, __LINE__);
				
				$aProduct = array();

				$aProduct['name'] = $this->sDescription;
				$aProduct['code'] = $this->sOrderId;
				$aProduct['price'] = round($this->fAmount * 100);
				$aProduct['quantity'] = '1';

				$aRequest['products'] = array($aProduct);				
			}
			else
			{
				$aRequest['products'] = $this->aProductData;				
			}
	
			if($this->bTestMode)
			{
				$sApiUrl = 'https://api-sandbox.onlinebetaalplatform.nl/v1/transactions';
			}
			else
			{
				$sApiUrl = 'https://api.onlinebetaalplatform.nl/v1/transactions';
			}

			$sPostData = json_encode($aRequest);

			$sResponse = idealcheckout_doHttpRequest_curl($sApiUrl, $sPostData, true, 30, false, array('Authorization: Basic ' . base64_encode($this->sApiKey . ':')));
			
			if(!empty($sResponse))
			{		
				$this->aTransaction = json_decode($sResponse, true);			
				
				if($this->aTransaction)
				{
					if(isset($this->aTransaction['uid'], $this->aTransaction['redirect_url']))
					{
						return true;
					}
					elseif(!isset($this->aTransaction['error'], $this->aTransaction['error']['message']))
					{
						$this->aTransaction = array('error' => array('message' => 'Unknown response received from Online Betaal Platform (See logs).'));
					}
				}
				else
				{
					$this->aTransaction = array('error' => array('message' => 'Cannot decode JSON response (See logs).'));
				}
			}
			else
			{
				$this->aTransaction = array('error' => array('message' => 'No response received from Online Betaal Platform (See logs).'));
			}

			idealcheckout_log($aRequest, __FILE__, __LINE__, true);
			idealcheckout_log($sResponse, __FILE__, __LINE__, true);

			return false;
		}

		public function getTransactionId()
		{
			if(!empty($this->aTransaction['uid']))
			{
				return $this->aTransaction['uid'];
			}

			return false;
		}

		public function getTransactionUrl()
		{
			if(!empty($this->aTransaction['redirect_url']))
			{
				return $this->aTransaction['redirect_url'];
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

		public function getStatus($sTransactionId)
		{
			if($this->bTestMode)
			{
				$sUrl = 'https://api-sandbox.onlinebetaalplatform.nl/v1/transactions';
			}
			else
			{
				$sUrl = 'https://api.onlinebetaalplatform.nl/v1/transactions';
			}
			
			$sApiUrl = $sUrl . '/' . $sTransactionId;
			
			$sResponse = idealcheckout_doHttpRequest_curl($sApiUrl, false, true, 30, false, array('Authorization: Basic ' . base64_encode($this->sApiKey . ':')));

			if(!empty($sResponse))
			{
				$aResponse = json_decode($sResponse, true);
				
				if(!empty($aResponse['status']))
				{
					if(in_array($aResponse['status'], array('pending')))
					{
						return 'PENDING';
					}
					elseif(in_array($aResponse['status'], array('completed')))
					{
						return 'SUCCESS';
					}
					elseif(in_array($aResponse['status'], array('cancelled', 'failed', 'expired')))
					{
						return 'CANCELLED';
					}
				}
			}

			return '';
		}
	}

?>