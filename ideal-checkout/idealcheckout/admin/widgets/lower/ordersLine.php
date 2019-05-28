<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	$sHtml .= '
		<div class="ordersLine lowerWidgetDiv" id="ordersLine">
		<link href="widgets/lower/css/ordersLine.css" media="screen" rel="stylesheet" type="text/css">
		<script>
			function minimizeOrdersLine(){
				jQuery(\'.ordersLine\').toggleClass(\'minimized\');
				$( \'.ordersLineContent\' ).slideToggle(\'fast\');
			}
			
			function removeOrdersLine(){
				$("#ordersLine-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#ordersLine").remove();
				
				aLowerDroppableContents.splice($.inArray("ordersLine-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	
	function getTransactions($sPASTMONTH, $sSTATUS, $iGETMONTHONLY){

		$iTransactionCount = 0;
		
		$iCurrentYear = date("Y");
		$iCurrentMonth = date("n");
		
		$iWantedStartMonth = ($iCurrentMonth - $sPASTMONTH);
		$iWantedStartYear = $iCurrentYear;
		$iWantedEndYear = $iWantedStartYear;
		
		if($iWantedStartMonth == 0){
			$iWantedStartMonth = 12;
			$iWantedStartYear = ($iCurrentYear - 1);
		}else if($iWantedStartMonth == -1){
			$iWantedStartMonth = 11;
			$iWantedStartYear = ($iCurrentYear - 1);
		}else if($iWantedStartMonth == -2){
			$iWantedStartMonth = 10;
			$iWantedStartYear = ($iCurrentYear - 1);
		}else if($iWantedStartMonth == -3){
			$iWantedStartMonth = 9;
			$iWantedStartYear = ($iCurrentYear - 1);
		}else if($iWantedStartMonth == -4){
			$iWantedStartMonth = 8;
			$iWantedStartYear = ($iCurrentYear - 1);
		}else if($iWantedStartMonth == -5){
			$iWantedStartMonth = 7;
			$iWantedStartYear = ($iCurrentYear - 1);
		}
		$iWantedEndMonth = ($iWantedStartMonth + 1);
		if($iWantedEndMonth == 13){
			$iWantedEndMonth = 1;
			$iWantedEndYear = ($iWantedStartYear + 1);
		}
		
		$iWantedStartDate = strtotime('1-' . $iWantedStartMonth . '-' . $iWantedStartYear . '');
		$iWantedEndDate = strtotime('1-' . $iWantedEndMonth . '-' . $iWantedEndYear . '');
		
		//print_r(" startdateTS: ");
		//print_r($iWantedStartDate);
		//print_r(" enddateTS: ");
		//print_r($iWantedEndDate);
		//print_r(" startdate: ");
		//print_r(date('d/m/Y', $iWantedStartDate));
		//print_r(" enddate: ");
		//print_r(date('d/m/Y', $iWantedEndDate));
		
		$iMaxDate = date(strtotime('-6 Month'));		
		
		
		$aDatabaseSettings = idealcheckout_getDatabaseSettings();
		$sql = "SELECT `transaction_date` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`transaction_status` = '" .idealcheckout_escapeSql($sSTATUS) . "') AND (`transaction_date` >= '" . $iMaxDate . "') ORDER BY `id` DESC";
		
		$aTransaction = idealcheckout_database_getRecords($sql);
		
		if($iGETMONTHONLY == 0){
		foreach($aTransaction as $aRecord){
			if(htmlspecialchars($aRecord['transaction_date']) > $iWantedStartDate && htmlspecialchars($aRecord['transaction_date']) <= $iWantedEndDate){
					$iTransactionCount++;
			}
			
		}
		return $iTransactionCount;
		}else if($iGETMONTHONLY == 1){
			$sGETMONTH = date("F", $iWantedStartDate);
			return $sGETMONTH;
		}
	}
	
	$sHtml .= '
	<script>
		var options = {
		  type: "line",
		  data: {
			labels: ["' . getTransactions('5', 'SUCCESS', '1') . '", "' . getTransactions('4', 'SUCCESS', '1') . '", "' . getTransactions('3', 'SUCCESS', '1') . '", "' . getTransactions('2', 'SUCCESS', '1') . '", "' . getTransactions('1', 'SUCCESS', '1') . '", "' . getTransactions('0', 'SUCCESS', '1') . '", ],
			datasets: [
				{
					label: "SUCCES",
					backgroundColor: "rgba(75, 148, 191, 0.9)",
					data: [' . getTransactions('5', 'SUCCESS', '0') . ',' . getTransactions('4', 'SUCCESS', '0') . ',' . getTransactions('3', 'SUCCESS', '0') . ',' . getTransactions('2', 'SUCCESS', '0') . ',' . getTransactions('1', 'SUCCESS', '0') . ',' . getTransactions('0', 'SUCCESS', '0') . '],
					borderWidth: 1
				},	
					{
					label: "FAILED",
					backgroundColor: "rgba(210, 214, 222, 1)",
					data: [' . getTransactions('5', 'FAILURE', '0') . ',' . getTransactions('4', 'FAILURE', '0') . ',' . getTransactions('3', 'FAILURE', '0') . ',' . getTransactions('2', 'FAILURE', '0') . ',' . getTransactions('1', 'FAILURE', '0') . ',' . getTransactions('0', 'FAILURE', '0') . '],
					borderWidth: 1
					}
				]
		  },
		  options: {
			  legend: {
				labels: {
					usePointStyle: true  //<-- set this
				}
			},
			scales: {
				yAxes: [{
				ticks: {
					reverse: false
				}
			  }]
			}
		  }
		}

		var ctx = document.getElementById("orders-line-chart").getContext("2d");
		ordersLineChart = new Chart(ctx, options);
	</script>
	'; 
	
	if(getTransactions('0', 'SUCCESS', '0') > 0 ){
	
		$sHtml .= '
			<div class="ordersLineTopDeco widgetTopDeco">
			</div>
			<div class="ordersLineTopInfo widgetTopInfo">
				Orders line chart
				<div class="removeLowerWidget" onclick="removeOrdersLine()"></div>
				<div class="minusLowerWidget" onclick="minimizeOrdersLine()"></div>
				<div class="infoIconLowerWidget" data-balloon="Geslaagde en mislukte transacties per maand" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
					
				</div>
			</div>
			<div  class="lowerWidgetContent ordersLineContent">
				<canvas id="orders-line-chart" width="2100" height="900" style="margin: auto;"></canvas>
			</div>
		';
	}else{
		$sHtml .= '
			<div class="ordersLineTopDeco widgetTopDeco">
			</div>
			<div class="ordersLineTopInfo widgetTopInfo">
				Orders line chart
				<div class="removeLowerWidget" onclick="removeOrdersLine()"></div>
				<div class="minusLowerWidget" onclick="minimizeOrdersLine()"></div>
				<div class="infoIconLowerWidget" data-balloon="Geslaagde en mislukte transacties per maand" data-balloon-pos="left">
					<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
				</div>
				<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
					
				</div>
			</div>
			<div class="lowerWidgetContent ordersLineContent">
				<div class="noWidgetDataAvailable">	
					<img src="' . $sImagePath . '/examples/orders-line-example.png" width="100%">
					<i><b>Dit is een voorbeeld, deze wordt automatisch vervangen zodra u eigen transacties gegenereerd heeft.</b></i>
				</div>
				<canvas id="orders-line-chart" width="2100" height="900" style="display: none;"></canvas>
			</div>
		';
	}
	// [\**]
	
	// [*]
	$sHtml .= '
		</div>
	';
	echo($sHtml);
	// [\*]
?>