<?php

	class IngEpayPayments
	{
		private $sApiKey = false;
		private $sPaymentMethod = false;
		private $sLanguageCode = false;

		private $sReturnUrl = false;
		private $sReportUrl = false;

		private $sCachePath;
		private $aIssuers;
		private $sIssuer;

		private $sOrderId = false;
		private $fAmount = false;
		private $sDescription = false;

		private $aCustomerData = array();
		private $aProductData = array();

		private $aTransaction = false;


		public function __construct($sApiKey)
		{
			$this->sApiKey = $sApiKey;
		}

		public function setCachePath($sPath = false)
		{
			$this->sCachePath = $sPath;
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

				if(in_array($sPaymentMethod, array('ideal', 'bank-transfer', 'credit-card', 'bancontact')))
				{
					$this->sPaymentMethod = $sPaymentMethod;
					return true;
				}
				else
				{
					return false;
				}
			}
		}

		public function setOrder($sOrderId, $fAmount, $sDescription = false)
		{
			if(!empty($sOrderId) && !empty($fAmount) && !empty($sDescription))
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
			else
			{
				return false;
			}
		}

		public function setReturnUrl($sReturnUrl)
		{
			if(!empty($sReturnUrl))
			{
				$this->sReturnUrl = $sReturnUrl;
				return true;
			}
		}

		public function setReportUrl($sReportUrl)
		{
			if(!empty($sReportUrl))
			{
				$this->sReportUrl = $sReportUrl;
				return true;
			}
		}

		public function setIssuer($sIssuerId)
		{
			if(!empty($sIssuerId))
			{
				$this->sIssuer = $sIssuerId;
				return true;
			}
		}

		public function getTransaction()
		{
			if(empty($this->sApiKey))
			{
				idealcheckout_log('No API key found.', __FILE__, __LINE__);

				$this->aTransaction = array('error' => array('message' => 'No API key found.'));
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
			elseif(empty($this->sReportUrl))
			{
				idealcheckout_die('No report URL found.', __FILE__, __LINE__);
			}

			$aRequest = array();

			$aRequest['merchant_order_id'] = $this->sOrderId;
			$aRequest['amount'] = round($this->fAmount * 100);
			$aRequest['currency'] = 'EUR';
			$aRequest['description'] = $this->sDescription;
			$aRequest['return_url'] = $this->sReturnUrl;
			$aRequest['transactions'] = array();

			$aRequest['transactions'][0]['payment_method'] = $this->sPaymentMethod;

			if(strcasecmp($this->sPaymentMethod, 'ideal') === 0)
			{
				$aRequest['transactions'][0]['payment_method_details'] = array('issuer_id' => $this->sIssuer);
			}

			if(!empty($this->sReportUrl))
			{
				$aRequest['webhook_url'] = $this->sReportUrl; // Return URL
			}


			$sApiUrl = 'https://api.epay.ing.be/v1/orders/';
			$sPostData = json_encode($aRequest);

			$sResponse = idealcheckout_doHttpRequest($sApiUrl, $sPostData, true, 30, false, array('Content-Type: application/json', 'Authorization: Basic ' . base64_encode($this->sApiKey . ':')));

			
			if(!empty($sResponse))
			{
				$this->aTransaction = json_decode($sResponse, true);

				if($this->aTransaction)
				{
					if(isset($this->aTransaction['transactions'], $this->aTransaction['transactions'][0]['order_id']) && !in_array($this->aTransaction['transactions'][0]['status'], array('error', 'cancelled')))
					{
						return true;
					}
					elseif(!isset($this->aTransaction['error'], $this->aTransaction['error']['message']))
					{
						
						if(idealcheckout_getDebugMode())
						{
							if(isset($this->aTransaction['transactions'][0]['reason']))
							{
								$this->aTransaction = array('error' => array('message' => $this->aTransaction['transactions'][0]['reason']));
							}
							else
							{
								$this->aTransaction = array('error' => array('message' => 'Unknown response received from ING Checkout (See logs).'));
							}
						}
						else
						{
							$this->aTransaction = array('error' => array('message' => 'Unknown response received from ING Checkout (See logs).'));
						}
					}
				}
			}
			else
			{
				$this->aTransaction = array('error' => array('message' => 'No response received from ING Checkout (See logs).'));
			}

			idealcheckout_log($aRequest, __FILE__, __LINE__);
			idealcheckout_log($sResponse, __FILE__, __LINE__);

			return false;
		}

		public function getTransactionId()
		{
			if(!empty($this->aTransaction['transactions'][0]['order_id']))
			{
				return $this->aTransaction['transactions'][0]['order_id'];
			}

			return false;
		}

		public function getTransactionUrl()
		{
			if(!empty($this->aTransaction['transactions'][0]['payment_url']))
			{
				return $this->aTransaction['transactions'][0]['payment_url'];
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
			$sApiUrl = 'https://api.epay.ing.be/v1/orders/' . $sTransactionId . '/';

			$sResponse = idealcheckout_doHttpRequest($sApiUrl, false, true, 30, false, array('Authorization: Basic ' . base64_encode($this->sApiKey . ':')));

			if(!empty($sResponse))
			{
				$aResponse = json_decode($sResponse, true);

				if(!empty($aResponse['status']))
				{
					$sTransactionStatus = $aResponse['status'];

					if(in_array($sTransactionStatus, array('new', 'processing', 'see-transactions', 'error')))
					{
						return 'PENDING';
					}
					elseif(in_array($sTransactionStatus, array('completed')))
					{
						return 'SUCCESS';
					}
					elseif(in_array($sTransactionStatus, array('cancelled')))
					{
						return 'CANCELLED';
					}
					elseif(in_array($sTransactionStatus, array('expired')))
					{
						return 'FAILURE';
					}
				}
			}

			return '';
		}

		public function doIssuerRequest()
		{
			$sCacheFile = false;

			if($this->sCachePath)
			{
				$sCacheFile = $this->sCachePath . 'issuers.cache';

				if(file_exists($sCacheFile) == false)
				{
					// Attempt to create cache file
					if(@touch($sCacheFile))
					{
						@chmod($sCacheFile, 0777);
					}
				}

				if(file_exists($sCacheFile) && is_readable($sCacheFile) && is_writable($sCacheFile))
				{
					if(filemtime($sCacheFile) > strtotime('-24 Hours'))
					{
						// Read data from cache file
						if($sData = file_get_contents($sCacheFile))
						{
							return idealcheckout_unserialize($sData);
						}
					}
				}
				else
				{
					$sCacheFile = false;
				}
			}

			$this->aIssuerList = array();


			$sApiUrl = 'https://api.epay.ing.be/v1/ideal/issuers/';
			$sPostData = '';

			$sResponse = idealcheckout_doHttpRequest($sApiUrl, $sPostData, true, 30, false, array('Authorization: Basic ' . base64_encode($this->sApiKey . ':')));

			if(!empty($sResponse))
			{
				$this->aIssuerList = json_decode($sResponse, true);

				if($this->aIssuerList)
				{
					return $this->aIssuerList;
				}
				else
				{
					$this->aIssuerList = array('error' => array('message' => 'Cannot decode JSON response (See logs).'));
				}
			}
			else
			{
				$this->aIssuerList = array('error' => array('message' => 'No response received from ING Checkout (See logs).'));
			}
		}
	}

?>