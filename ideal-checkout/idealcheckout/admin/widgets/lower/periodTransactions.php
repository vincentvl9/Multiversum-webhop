<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	function getTransactions($HDWM){
		
		$aDatabaseSettings = idealcheckout_getDatabaseSettings();
		
		$iMaxDate = date(strtotime('-12 Month'));
		$sql = "SELECT `transaction_date` FROM `" . $aDatabaseSettings['table'] . "` WHERE (`transaction_status` = 'SUCCESS') AND (`transaction_date` >= '" . $iMaxDate . "') ORDER BY `id` DESC";
		$aTransactions = idealcheckout_database_getRecords($sql);

		//print_r($aTransactions);
		
		$sCurrentTime = time();
		$iHDWMCounter = 0;
		
		//print_r($sCurrentTime);
		foreach($aTransactions as $aRecord){
			if($HDWM == "hour"){
				if(htmlspecialchars($aRecord['transaction_date']) >= ($sCurrentTime - 3600) ){
					$iHDWMCounter++;
				}
				
			}else if($HDWM == "day"){
				if(htmlspecialchars($aRecord['transaction_date']) >= ($sCurrentTime - 86400) ){
					$iHDWMCounter++;
				}
				
			}else if($HDWM == "week"){
				if(htmlspecialchars($aRecord['transaction_date']) >= ($sCurrentTime - 604800) ){
					$iHDWMCounter++;
				}
				
			}else if($HDWM == "month"){
				if(htmlspecialchars($aRecord['transaction_date']) >= ($sCurrentTime - 2592000) ){
					$iHDWMCounter++;
				}
				
			}else if($HDWM == "year"){
				if(htmlspecialchars($aRecord['transaction_date']) >= ($sCurrentTime - 31536000) ){
					$iHDWMCounter++;
				}
				
				
			}else{
				print_r('please configure the $HDWM variable with either hour, day, week or month ');
			}	
		}
		return $iHDWMCounter;
	} 

	$sHtml .= '
		
	'; 
	
	
	$sHtml .= '
		
		<div class="periodTransactions lowerWidgetDiv" id="periodTransactions">
		<link href="widgets/lower/css/periodTransactions.css" media="screen" rel="stylesheet" type="text/css">
		<script>
			function minimizePeriodTransactions(){
				jQuery(\'.periodTransactions\').toggleClass(\'minimized\');
				$( \'.periodTransactionsContent\' ).slideToggle(\'fast\');
			}
			
			function removeperiodTransactions(){
				$("#periodTransactions-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#periodTransactions").remove();
				
				aLowerDroppableContents.splice($.inArray("periodTransactions-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<script>
			new Chart(document.getElementById("transactions-period-bar-chart"), {
				type: "horizontalBar",
				data: {
				  labels: ["Afgelopen uur: ' . getTransactions("hour") . ' ", "Afgelopen 24 uur: ' . getTransactions("day") . ' ", "Afgelopen 07 dagen: ' . getTransactions("week") . ' ", "Afgelopen 30 dagen: ' . getTransactions("month") . ' ", "Afgelopen 365 dagen: ' . getTransactions("year") . ' "],
				  
				  datasets: [
					{
					  //label: "SUCCESS",
					  backgroundColor: ["rgba(245, 105, 84, 0.65)", "rgba(0, 192, 239, 0.65)", "rgba(243, 156, 18, 0.65)", "rgba(60, 141, 188, 0.65)", "rgba(210, 214, 222, 0.65)"],
					  data: [' . (getTransactions("hour") * 24 ) . ',' . (getTransactions("day") / 1 ) . ',' . (getTransactions("week") / 7) . ',' . (getTransactions("month") / 30) . ',' . (getTransactions("year") / 365) . ']
					}
				  ]
				},
				options: {
					legend: {
					display: false
					},
					tooltips: {
						enabled: false
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
							  display: false
							}
						}]
					}
				}				
			});
		</script>
		<div class="periodTransactionsTopDeco widgetTopDeco">
		</div>
		<div class="periodTransactionsTopInfo widgetTopInfo">
			Transacties periode
			<div class="removeLowerWidget" onclick="removeperiodTransactions()"></div>
			<div class="minusLowerWidget" onclick="minimizePeriodTransactions()"></div>
			<div class="infoIconLowerWidget" data-balloon="Geslaagde transacties per periode in verhouding tot elkaar" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	$sHtml .= '
		<div class="lowerWidgetContent periodTransactionsContent">
			<canvas id="transactions-period-bar-chart" width="2000" height="800" style="margin: auto;"></canvas>
		</div>	
	';
	// [\**]
	
	// [*]
	$sHtml .= '
		</div>
	';
	echo($sHtml);
	// [\*]
?>