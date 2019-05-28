<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	$sHtml .= '
		<link href="widgets/top/css/topwidget1.css" media="screen" rel="stylesheet" type="text/css">
		<div class="topWidget1" id="topWidget1">
		<div class="topwidget1TopDeco widgetTopDeco">
		</div>
		<div class="topwidget1TopInfo widgetTopInfo">
			<b>WIDGET EXPL</b>				
		</div>
		<div onmouseover="disableLowerDragging()" onmouseout="enableLowerDragging()" class="topWidgetContent">
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	$sHtml .= '
			
	';
	// [\**]
	
	// [*]
	$sHtml .= '
		</div>
		</div>
	';
	echo($sHtml);
	// [\*]
?>