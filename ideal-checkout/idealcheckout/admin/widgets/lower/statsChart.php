<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	$sHtml .= '
		
		<div class="statsChart lowerWidgetDiv" id="statsChart">
		<script>
			function minimizeStatsChart(){
				jQuery(\'.statsChart\').toggleClass(\'minimized\');
				$( \'.statsChartContent\' ).slideToggle(\'fast\');
			}
			
			function removestatsChart(){
				$("#statsChart-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#statsChart").remove();
				
				aLowerDroppableContents.splice($.inArray("statsChart-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<link href="widgets/lower/css/statsChart.css" media="screen" rel="stylesheet" type="text/css">
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
			new Chart(document.getElementById("bar-chart-grouped"), {
				type: "bar",
				data: {
				  labels: ["' . getTransactions('5', 'SUCCESS', '1') . '", "' . getTransactions('4', 'SUCCESS', '1') . '", "' . getTransactions('3', 'SUCCESS', '1') . '", "' . getTransactions('2', 'SUCCESS', '1') . '", "' . getTransactions('1', 'SUCCESS', '1') . '", "' . getTransactions('0', 'SUCCESS', '1') . '", ],
				  datasets: [
					{
					  label: "SUCCESS",
					  backgroundColor: "#00A65A",
					  data: [' . getTransactions('5', 'SUCCESS', '0') . ',' . getTransactions('4', 'SUCCESS', '0') . ',' . getTransactions('3', 'SUCCESS', '0') . ',' . getTransactions('2', 'SUCCESS', '0') . ',' . getTransactions('1', 'SUCCESS', '0') . ',' . getTransactions('0', 'SUCCESS', '0') . ']
					}, {
					  label: "FAILED",
					  backgroundColor: "#DD4B39",
					  data: [' . getTransactions('5', 'FAILURE', '0') . ',' . getTransactions('4', 'FAILURE', '0') . ',' . getTransactions('3', 'FAILURE', '0') . ',' . getTransactions('2', 'FAILURE', '0') . ',' . getTransactions('1', 'FAILURE', '0') . ',' . getTransactions('0', 'FAILURE', '0') . ']
					}
				  ]
				},	
				options: {
					legend: {
						labels: {
							usePointStyle: true  //<-- set this
						},
						position: "top",
					}
				}
			});
		</script>
	'; 
	
	$sHtml .= '
		<div class="statsChartTopDeco widgetTopDeco">
		</div>
		<div class="statsChartTopInfo widgetTopInfo">
			Order status chart
			<div class="removeLowerWidget" onclick="removestatsChart()"></div>
			<div class="minusLowerWidget" onclick="minimizeStatsChart()"></div>
		
			<div class="infoIconLowerWidget" data-balloon="Geslaagde en mislukte transacties per maand" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()" data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
		<div  class="lowerWidgetContent statsChartContent">
		'; 
		if(getTransactions('0', 'SUCCESS', '0') > 0 ){
	
		$sHtml .= '
			<canvas id="bar-chart-grouped" width="2000" height="1000" style="width:100%;"></canvas>
		</div>
		';
		}else{
			$sHtml .= '
			<div class="noWidgetDataAvailable">
				<img src="' . $sImagePath . '/examples/orders-status-example.png" width="100%">
				<i><b>Dit is een voorbeeld, deze wordt automatisch vervangen zodra u eigen transacties gegenereerd heeft.</b></i>
			</div>
			<canvas id="bar-chart-grouped" width="2000" height="1000" style="display:none;"></canvas>
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