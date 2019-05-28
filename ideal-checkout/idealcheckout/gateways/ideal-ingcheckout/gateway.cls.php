<?php

	require_once(dirname(dirname(__FILE__)) . '/gateway.core.cls.5.php');
	require_once(dirname(__FILE__) . '/ingcheckout.cls.php');

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
						$oIngCheckout = new IngCheckoutPayment($this->aSettings['API_KEY']);
						$oIngCheckout->setCachePath($this->aSettings['CACHE_PATH']);

						$aIssuerList = $oIngCheckout->doIssuerRequest();
						$sIssuerList = '';

						if(empty($this->oRecord['transaction_log']) == false)
						{
							$this->oRecord['transaction_log'] .= '\n\n';
						}

						$this->oRecord['transaction_log'] .= 'Executing IssuerRequest on ' . date('Y-m-d, H:i:s') . '.';
						$this->save();

						foreach($aIssuerList as $aIssuer)
						{
							$sIssuerList .= '<option value="' . $aIssuer['id'] . '">' . $aIssuer['name'] . '</option>';
						}

						$sHtml .= '
							<form action="' . htmlspecialchars(idealcheckout_getRootUrl(1) . 'idealcheckout/transaction.php?order_id=' . $sOrderId . '&order_code=' . $sOrderCode) . '" method="post" id="checkout">
								<p><b>Kies uw bank</b><br><select name="issuer_id" style="margin: 6px; width: 200px;">' . $sIssuerList . '</select><br><input type="submit" value="Verder"></p>
							</form>';
					}
				}
				else
				{
					$sHtml .= '<p>Invalid issuer request.</p>';
				}
			}
			idealcheckout_output($sHtml);
		}


		// Execute payment
		public function doTransaction()
		{
			$sHtml = '';

			// Look for proper GET's en POST's
			if(empty($_POST['issuer_id']) || empty($_GET['order_id']) || empty($_GET['order_code']))
			{
				$sHtml .= '<p>Invalid transaction request.</p>';
			}
			else
			{
				$sIssuerId = $_POST['issuer_id'];
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

						$oIngCheckout = new IngCheckoutPayment($this->aSettings['API_KEY']);
						$oIngCheckout->setPaymentMethod('ideal');
						$oIngCheckout->setOrder($this->oRecord['order_id'], $this->oRecord['transaction_amount'], $this->oRecord['transaction_description']);
						$oIngCheckout->setReturnUrl($sReturnUrl);
						$oIngCheckout->setReportUrl($sReportUrl);
						$oIngCheckout->setIssuer($sIssuerId);
						
						if($oIngCheckout->getTransaction())
						{
							$this->oRecord['transaction_id'] = $oIngCheckout->getTransactionId();
							$this->oRecord['transaction_url'] = $oIngCheckout->getTransactionUrl();

							if(empty($this->oRecord['transaction_log']) == false)
							{
								$this->oRecord['transaction_log'] .= "\n\n";
							}

							$this->oRecord['transaction_log'] .= 'Requesting transaction on ' . date('Y-m-d, H:i:s') . '; recieved ID: "' . $this->oRecord['transaction_id'] . '" and URL: "' . $this->oRecord['transaction_url'] . '".';
							$this->save();

							header('Location: ' . $this->oRecord['transaction_url']);
							exit;
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

			if(empty($_GET['idealcheckout_order_id']) || empty($_GET['idealcheckout_order_code']))
			{
				$sHtml .= '<p>Invalid return request.</p>';
			}
			else
			{
				// Lookup record
				if($this->getRecordByOrder())
				{
					// Verify status
					$oIngCheckout = new IngCheckoutPayment($this->aSettings['API_KEY']);
					$sTransactionStatus = $oIngCheckout->getStatus($this->oRecord['transaction_id']);

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
						elseif(in_array($this->oRecord['transaction_status'], array('PENDING', 'OPEN')))
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
						else
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
			exit;
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
					$oIngCheckout = new IngCheckoutPayment($this->aSettings['API_KEY']);
					$sTransactionStatus = $oIngCheckout->getStatus($this->oRecord['transaction_id']);


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