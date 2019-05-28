<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	$sHtml .= '
		
		<div class="lowerWidget3 lowerWidgetDiv" id="lowerWidget3">
		<script>
			function minimizelowerWidget3(){
				jQuery(\'.lowerWidget3\').toggleClass(\'minimized\');
				$( \'.lowerWidget3Content\' ).slideToggle(\'fast\');
			}
			
			function removelowerWidget3(){
				$("#lowerWidget3-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#lowerWidget3").remove();
				
				aLowerDroppableContents.splice($.inArray("lowerWidget3-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<link href="widgets/lower/css/lowerwidget3.css" media="screen" rel="stylesheet" type="text/css">
		<div class="lowerWidget3TopDeco widgetTopDeco">
		</div>
		<div class="lowerWidget3TopInfo widgetTopInfo">
			WIDGET EXPL
			<div class="removeLowerWidget" onclick="removelowerWidget3()"></div>
			<div class="minusLowerWidget" onclick="minimizelowerWidget3()"></div>
			<div class="infoIconLowerWidget" data-balloon="Whats up!" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
		<div  class="lowerWidgetContent lowerWidget3Content">
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