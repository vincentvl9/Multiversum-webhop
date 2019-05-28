<?php


	// Update order status when required
	function idealcheckout_update_order_status($aRecord, $sView)
	{
		idealcheckout_log('Updating status to "' . $aRecord['transaction_status'] . '" for order #' . $aRecord['order_id'], __FILE__, __LINE__);
		idealcheckout_log($aRecord, __FILE__, __LINE__);


		// Find order state
		if(strcasecmp($aRecord['transaction_status'], 'SUCCESS') === 0)
		{
			idealcheckout_log('Calling success URL: ' . $aRecord['transaction_success_url'], __FILE__, __LINE__);
			idealcheckout_doHttpRequest($aRecord['transaction_success_url']);
		}
		elseif((strcasecmp($aRecord['transaction_status'], 'PENDING') === 0) || (strcasecmp($aRecord['transaction_status'], 'OPEN') === 0))
		{
			idealcheckout_log('Calling pending/open URL: ' . $aRecord['transaction_pending_url'], __FILE__, __LINE__);
			idealcheckout_doHttpRequest($aRecord['transaction_pending_url']);
		}
		else
		{
			idealcheckout_log('Calling cancel/failure URL: ' . $aRecord['transaction_failure_url'], __FILE__, __LINE__);
			idealcheckout_doHttpRequest($aRecord['transaction_failure_url']);
		}

		idealcheckout_log('Sending mail for order #' . $aRecord['order_id'], __FILE__, __LINE__);
		idealcheckout_sendMail($aRecord);
	}


?>