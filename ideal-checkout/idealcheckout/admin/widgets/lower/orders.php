<?php
	
	$sHtml = '';	
	
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	
	$sHtml .= '
		
		<div class="orders lowerWidgetDiv" id="orders">
		<script>
			function minimizeOrders(){
				jQuery(\'.orders\').toggleClass(\'minimized\');
				$( \'.ordersContent\' ).slideToggle(\'fast\');
			}
			
			function removeorders(){
				$("#orders-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#orders").remove();
				
				aLowerDroppableContents.splice($.inArray("orders-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<link href="widgets/lower/css/orders.css" media="screen" rel="stylesheet" type="text/css">
		<script>
			function fShowMoreTransactionsWidget1(){
				jQuery(\'.orders\').toggleClass(\'showMore\');
			}
		</script>
	';
	// [\*]
	
	
	
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	
	/* $aDatabaseSettings = idealcheckout_getDatabaseSettings();
			
	$sql = "SELECT `transaction_date` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`transaction_status` = 'SUCCESS') ORDER BY `id` DESC";
	$aTransactions = idealcheckout_database_getRecords($sql);
	
	print_r($aTransactions);
	*/
	
	$aDatabaseSettings = idealcheckout_getDatabaseSettings();
	$sql = "SELECT `order_id`, `gateway_code`, `transaction_id`, `transaction_status`, `order_params` FROM `" . $aDatabaseSettings['table'] . "` ORDER BY `transaction_date` DESC LIMIT 5;";
	$aTransactions = idealcheckout_database_getRecords($sql);
		

		if(sizeof($aTransactions))
		{			
			$iShowCounter = 0;
			
			$sHtml .= '
			<div class="ordersTopDeco widgetTopDeco">
			</div>
			<div class="ordersTopInfo widgetTopInfo">
				Orders
				<div class="removeLowerWidget" onclick="removeorders()"></div>
				<div class="minusLowerWidget" onclick="minimizeOrders()"></div>
				<div class="infoIconLowerWidget" data-balloon="Transactie overzicht" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
					
				</div>
			</div>
			<div  class="lowerWidgetContent ordersContent">
			<div class="transaction-overview">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<th style="width: 168px">Order</th>
						<th>Methode</th>
						<th style="width: 36px">Status</th>
					</tr>
			';
			foreach($aTransactions as $aRecord)
			{				
				$aParams = idealcheckout_unserialize($aRecord['order_params']);
				$transactionId = htmlspecialchars($aRecord['transaction_id']);
				$orderId = htmlspecialchars($aRecord['order_id']);
				$sTransactionStatus = htmlspecialchars($aRecord['transaction_status']);
				//if(strlen($transactionId) > 32) $transactionId = substr($transactionId, 0, 29).'...';
				$sHtml .= '
					<tr>
						<td><a href="' . $sImagePath . '/dummy-transaction-page.png"><span id="opener' . $orderId . '" style="text-decoration:none; color:#00acd6; cursor:pointer;">' . $orderId . '</span></a></td>
						
						<td style="word-wrap: break-word">' . htmlspecialchars($aRecord['gateway_code']) . '</td>
				';

				if($sTransactionStatus == "SUCCES" || $sTransactionStatus == "SUCCESS"){
					$sHtml .= '
						<td><img class="circle-icon icon" title="succes" height="18" style="margin-top:11px;margin-bottom:7px;margin-left:11px;" src="' . $sImagePath . '/bar-green.png"></td>
				';	
				}else if($sTransactionStatus == "CANCELLED"){
					$sHtml .= '
						<td><img class="circle-icon icon" title="geannuleerd" height="18" style="margin-top:11px;margin-bottom:7px;margin-left:11px;" src="' . $sImagePath . '/bar-yellow.png"></td>
				';	
				}else if($sTransactionStatus == "FAILURE" || $sTransactionStatus == "EXPIRED"){
					$sHtml .= '
						<td><img class="circle-icon icon" title="gefaald of verlopen" height="18" style="margin-top:11px;margin-bottom:7px;margin-left:11px;" src="' . $sImagePath . '/bar-red.png"></td>
				';	
				}else if($sTransactionStatus == "OPEN" || $sTransactionStatus == "PENDING" ){
					$sHtml .= '
						<td><img class="circle-icon icon" title="open / afwachten" height="18" style="margin-top:11px;margin-bottom:7px;margin-left:11px;" src="' . $sImagePath . '/bar-purple.png"></td>
				';	
				}else{
					$sHtml .= '
						<td><img class="circle-icon icon" title="onbekend" height="18" style="margin-top:11px;margin-bottom:7px;margin-left:11px;" src="' . $sImagePath . '/bar-black.png"></td>
				';	
				}
				$sHtml .= '
					</tr>
				';	
				
			if(++$iShowCounter >= 5 ) break; 
			}
			
		}
		else
		{
			$sHtml .= '
			<div class="ordersTopDeco widgetTopDeco">
			</div>
			<div class="ordersTopInfo widgetTopInfo">
				Orders
				<div class="removeLowerWidget" onclick="removeorders()"></div>
				<div class="minusLowerWidget" onclick="minimizeOrders()"></div>
				<div class="infoIconLowerWidget" data-balloon="Transactie overzicht" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
					
				</div>
			</div>
			<div  class="lowerWidgetContent ordersContent">
				<div class="noWidgetDataAvailable">
					<img src="' . $sImagePath . '/examples/orders-example.png" width="100%">
					<i><b>Dit is een voorbeeld, deze wordt automatisch vervangen zodra u eigen transacties gegenereerd heeft.</b></i>
				</div>
			</div>
		
			<script>
				$(".ordersButtonBar").css("display", "none");
				$(".ordersPremiumNotification").css("display", "none");
				$(".orders").css("height", "auto");
			</script>
			
			<table>
		';
		}	
	$sHtml .= '
			</table>
			<div class="ordersPremiumNotification">
				<b>Deze feature is beschikbaar in de premium versie.</b> <br>
				Upgrade <a href="https://www.ideal-checkout.nl/home/premium-features" target="_blank">HIER</a> naar premium.
			</div>
	';
	
	// [\**]
	
	
	
	
	// [*]
	$sHtml .= '
	</div>
	</div>
	<div class="ordersButtonBar">
		<button class="ShowMoreTransactionsWidget1Button" onclick="fShowMoreTransactionsWidget1()"> Meer </button>
		<button class="PlaceNewOrderWidget1Button" onclick=""> Nieuw order </button>
	</div> 
	
	';
	echo($sHtml);
	// [\*]
?>