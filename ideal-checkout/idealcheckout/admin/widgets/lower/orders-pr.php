<?php
	
	$sHtml = '';	
	
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	
	$sHtml .= '
		
		<div class="orders-pr lowerWidgetDiv" id="orders-pr">
		<script>
			function minimizeOrderspr(){
				jQuery(\'.orders-pr\').toggleClass(\'minimized\');
				$( \'.orders-prContent\' ).slideToggle(\'fast\');
			}
			
			function removeorderspr(){
				$("#orders-pr-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#orders-pr").remove();
				
				aLowerDroppableContents.splice($.inArray("orders-pr-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<link href="widgets/lower/css/orders-pr.css" media="screen" rel="stylesheet" type="text/css">
		<script>
			function fShowMoreTransactionsOrdersPr(){
				jQuery(\'.orders-pr\').toggleClass(\'showMore\');
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
	
	$iMaxDate = date(strtotime('-1 Month'));
	
	$aDatabaseSettings = idealcheckout_getDatabaseSettings();
	$sql = "SELECT `order_id`, `gateway_code`, `transaction_id`, `transaction_status`, `order_params` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`transaction_date` >= '" . $iMaxDate . "') ORDER BY `transaction_date` DESC;";
	$aTransactions = idealcheckout_database_getRecords($sql);
		

		if(sizeof($aTransactions))
		{			
			$iShowCounter = 0;
			$sHtml .= '
			<div class="orders-pr-TopDeco widgetTopDeco">
			</div>
			<div class="orders-pr-TopInfo widgetTopInfo">
				Orders premium
				<div class="removeLowerWidget" onclick="removeorderspr()"></div>
				<div class="minusLowerWidget" onclick="minimizeOrderspr()"></div>
				<div class="infoIconLowerWidget" data-balloon="Transacties afgelopen 30 dagen" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				</div>
			</div>
			<div class="lowerWidgetContent orders-prContent">
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
						<td><a href="' . $sImagePath . '/dummy-transaction-page.png"><span id="openerpr' . $orderId . '" style="text-decoration:none; color:#00acd6; cursor:pointer;">' . $orderId . '</span></a></td>
						
						
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
				
			}
			
		}
		else
		{
			$sHtml .= '
			<div class="orders-pr-TopDeco widgetTopDeco"></div>
			<div class="orders-pr-TopInfo widgetTopInfo">
				Orders premium
				<div class="minusLowerWidget" onclick="minimizeOrderspr()"></div>
				<div class="infoIconLowerWidget" data-balloon="Transacties afgelopen 30 dagen" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
					
				</div>
			</div>
			<div  class="lowerWidgetContent orders-prContent">
				<div class="noWidgetDataAvailable">
					Helaas is er de afgelopen 30 dagen geen transactie
				</div>
			</div>
		
			<script>
				$(".ordersPrButtonBar").css("display", "none");
				$(".orders-pr").css("height", "auto");
			</script>
			
			<table>
		';
		}	
	$sHtml .= '
			</table>
	';
	
	// [\**]
	
	
	
	
	// [*]
	$sHtml .= '
	</div>
	</div>	
	<div class="ordersPrButtonBar">
		<button class="ShowMoreTransactionsOrdersPrButton" onclick="fShowMoreTransactionsOrdersPr()"> Meer </button>
		<button class="PlaceNewOrderOrdersPrButton" onclick=""> Nieuw order </button>
	</div> 
	';
	echo($sHtml);
	// [\*]
?>