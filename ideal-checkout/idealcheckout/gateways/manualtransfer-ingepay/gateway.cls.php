<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(dirname(__FILE__) . '/ingepay.cls.php');

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

				if($this->getRecordByOrder())
				{
					if(strcasecmp($this->oRecord['transaction_status'] , 'SUCCESS') === 0)
					{
						$sHtml .= '<p>Transaction already completed</p>';
					}
					elseif((strcasecmp($this->oRecord['transaction_status'], 'OPEN') === 0) && (!empty($this->oRecord['transaction_url'])))
					{
						header('Location: ' . $this->oRecord['transaction_url']);
						exit;
					}
					else
					{

						$sReturnUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/return.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code'];
						$sReportUrl = idealcheckout_getRootUrl(1) . 'idealcheckout/report.php?idealcheckout_order_id=' . $this->oRecord['order_id'] . '&idealcheckout_order_code=' . $this->oRecord['order_code'];

						$oIngEpay = new IngEpayPayments($this->aSettings['API_KEY']);

						$oIngEpay->setPaymentMethod('bank-transfer');
						$oIngEpay->setOrder($this->oRecord['order_id'], $this->oRecord['transaction_amount'], $this->oRecord['transaction_description']);
						$oIngEpay->setReturnUrl($sReturnUrl);
						$oIngEpay->setReportUrl($sReportUrl);

						if($oIngEpay->getTransaction())
						{
							$this->oRecord['transaction_id'] = $oIngEpay->getTransactionId();
							$this->oRecord['transaction_params'] = $oIngEpay->getPaymentDetails();

							
							
							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$aTransactionParams = $this->oRecord['transaction_params'];


							$this->oRecord['transaction_log'] .= 'Requesting transaction on ' . date('Y-m-d, H:i:s') . '; recieved ID: "' . $this->oRecord['transaction_id'] . '" and Params: "' . $aTransactionParams . '".';

							$this->oRecord['transaction_status'] = $oIngEpay->getStatus($this->oRecord['transaction_id']);
							$this->save();

							if(($this->oRecord['transaction_status'] == 'PENDING') && ($this->oRecord['gateway_code'] == 'manualtransfer'))
							{
								$aManualTransferTransactionParams = json_decode($this->oRecord['transaction_params'], true);
								$fAmount = json_decode($this->oRecord['transaction_amount'], true);
								$sCurrency = $oIngEpay->getValuta($this->oRecord['currency_code']);

								$sHtml .= "<p>Your order has been successfully processed!</p><p>Your order will be shipped after your payment to the credentials with the Reference shown below.</p>";
								$sHtml .= "Amount: " . $sCurrency . '' . $fAmount . ",-<br>";
								$sHtml .= "Iban: " . $aManualTransferTransactionParams['consumer_iban'] . "<br>";
								$sHtml .= "Name of recipient: " . $aManualTransferTransactionParams['consumer_name'] . "<br>";
								$sHtml .= "Reference: " . $aManualTransferTransactionParams['reference'] ."<br>";

								$sHtml .= "Click <a href='" . $this->oRecord['transaction_pending_url'] . "'>here</a> to return to the shop";
							}
						}
						else
						{
							$sHtml .= '<p>' . $oIngEpay->getError() . '</p>';
						}
					}
				}
				else
				{
					$sHtml .= '<p>Invalid request.</p>';
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
					$oIngEpay = new IngEpayPayments($this->aSettings['API_KEY']);
					$sTransactionStatus = $oIngEpay->getStatus($this->oRecord['transaction_id']);


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