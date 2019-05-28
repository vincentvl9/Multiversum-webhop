<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	function getPaymentMethod($iPASTMONTH, $sSTATUS, $iGETMONTHONLY, $sFunction){

		$iTransactionCount = 0;
		$aPaymentMethodCount = array();
		
		$iCurrentYear = date("Y");
		$iCurrentMonth = date("n");
		
		$iWantedStartMonth = ($iCurrentMonth - $iPASTMONTH);
		$iWantedStartYear = $iCurrentYear;
		$iWantedEndYear = $iWantedStartYear;
		
		$iWantedEndMonth = ($iWantedStartMonth + 1);
		if($iWantedEndMonth == 13){
			$iWantedEndMonth = 1;
			$iWantedEndYear = ($iWantedStartYear + 1);
		}
		
		$iWantedStartDate = strtotime('1-' . $iWantedStartMonth . '-' . $iWantedStartYear . '');
		$iWantedEndDate = strtotime('1-' . $iWantedEndMonth . '-' . $iWantedEndYear . '');
		
		$iMaxDate = date(strtotime('-1 Month'));		
		
		
		$aDatabaseSettings = idealcheckout_getDatabaseSettings();
		$sql = "SELECT `transaction_date`, `gateway_code` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`transaction_status` = '" .idealcheckout_escapeSql($sSTATUS) . "') AND (`transaction_date` >= '" . $iMaxDate . "') ORDER BY `id` DESC";
		
		$aTransaction = idealcheckout_database_getRecords($sql);
		
		if($iGETMONTHONLY == 0){
			foreach($aTransaction as $aRecord){
				if(htmlspecialchars($aRecord['transaction_date']) > $iWantedStartDate && htmlspecialchars($aRecord['transaction_date']) <= $iWantedEndDate){
						$iTransactionCount++;
						array_push($aPaymentMethodCount,  $aRecord['gateway_code']);
				}
				
			}
			
			$aPaymentMethodCounted = array_count_values($aPaymentMethodCount);
			arsort($aPaymentMethodCounted);
			$aPaymentMethodCountSum = array_sum($aPaymentMethodCounted);
			
			$sReturnString = '';
			
			foreach ($aPaymentMethodCounted as $sMethod => $Count) {
				$aPaymentMethodPercentage = round((100 / $aPaymentMethodCountSum * $Count), 2);
				if($sFunction == "METHOD"){
					$sReturnString .= '"' . $sMethod . '", '; 
				}else if($sFunction == "DATA"){
					$sReturnString .= '' . $aPaymentMethodPercentage . ', ';
				}
				
			}
			$sReturnString = substr($sReturnString, 0, -2);
			return $sReturnString;
			
		}else if($iGETMONTHONLY == 1){
			$sGETMONTH = date("F", $iWantedStartDate);
			return $sGETMONTH;
		}
	}
	
	$sHtml .= '
		
	'; 
	
	$sHtml .= '
		
		<div class="methodsHoriChart lowerWidgetDiv" id="methodsHoriChart">
		<link href="widgets/lower/css/methodsHoriChart.css" media="screen" rel="stylesheet" type="text/css">
		<script>
			function minimizeMethodsHoriChart(){
				jQuery(\'.methodsHoriChart\').toggleClass(\'minimized\');
				$( \'.methodsHoriChartContent\' ).slideToggle(\'fast\');
			}
			
			function removemethodsHoriChart(){
				$("#methodsHoriChart-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#methodsHoriChart").remove();
				
				aLowerDroppableContents.splice($.inArray("methodsHoriChart-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<script>
			new Chart(document.getElementById("methods-hori-line-chart"), {
				type: "horizontalBar",
				data: {
				  labels: [' . getPaymentMethod('0', 'SUCCESS', '0', 'METHOD') . '],
				  
				  datasets: [
					{
					  backgroundColor: ["rgba(245, 105, 84, 0.65)", "rgba(0, 192, 239, 0.65)", "rgba(243, 156, 18, 0.65)", "rgba(60, 141, 188, 0.65)", "rgba(255, 246, 0, 0.65)", "rgba(245, 105, 84, 0.65)", "rgba(0, 192, 239, 0.65)", "rgba(243, 156, 18, 0.65)", "rgba(60, 141, 188, 0.65)", "rgba(255, 246, 0, 0.65)"],
					  data: [' . getPaymentMethod('0', 'SUCCESS', '0', 'DATA') . ']
					}
				  ]
				},
				options: {
					legend: {
					display: false
					},
					tooltips: {
						enabled: true
					},
					scales: {
						yAxes: [{
							gridLines: {
							  display: false,
							  drawBorder: false
							},
							ticks: {
							  mirror: true,
							  padding: -10
							}
						}],
						  xAxes: [{
							gridLines: {
							  display: false,
							  drawBorder: false
							},
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}				
			});
		</script>
		<div class="methodsHoriChartTopDeco widgetTopDeco">
		</div>
		<div class="methodsHoriChartTopInfo widgetTopInfo">
			Methoden procent
			<div class="removeLowerWidget" onclick="removemethodsHoriChart()"></div>
			<div class="minusLowerWidget" onclick="minimizeMethodsHoriChart()"></div>
			<div class="infoIconLowerWidget" data-balloon="Betaalmethode percentage per geslaagde transactie deze maand" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	
	if(strlen(getPaymentMethod('0', 'SUCCESS', '0', 'METHOD')) > 0 ){
	
		$sHtml .= '
		<div  class="lowerWidgetContent methodsHoriChartContent">
			<canvas id="methods-hori-line-chart" width="2000" height="600" style="margin: auto;"></canvas>
		</div>		
		';
	}else{
		$sHtml .= '
		<div  class="lowerWidgetContent methodsHoriChartContent">
		<canvas id="methods-hori-line-chart" width="2000" height="600" style="display: none;"></canvas>
			<div class="noWidgetDataAvailable">
				<img src="' . $sImagePath . '/examples/method-bar-percent-example.png" width="100%">
				<i><b>Dit is een voorbeeld, deze wordt automatisch vervangen zodra u eigen transacties gegenereerd heeft.</b></i>
			</div>
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