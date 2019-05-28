<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
		
	$sHtml .= '
		<div class="changelog lowerWidgetDiv" id="changelog">
		<script>
			function minimizechangelog(){
				jQuery(\'.changelog\').toggleClass(\'minimized\');
				$( \'.changelogContent\' ).slideToggle(\'fast\');
			}
			
			$( function() {
				$( "#changeLogWidgetAccordion" ).accordion({
					collapsible: true,
					heightStyle: "content"
				});
			});
			
					
			function removechangelog(){
				$("#changelog-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#changelog").remove();
				
				aLowerDroppableContents.splice($.inArray("changelog-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		
		<link href="widgets/lower/css/changelog.css" media="screen" rel="stylesheet" type="text/css">
		<div class="changelogTopDeco widgetTopDeco">
		</div>
		<div class="changelogTopInfo widgetTopInfo">
			Changelog
			<div class="removeLowerWidget" onclick="removechangelog()"></div>
			<div class="minusLowerWidget" onclick="minimizechangelog()"></div>
			<div class="infoIconLowerWidget" data-balloon="Changelogs van iDEAL Checkout" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
		<div  class="lowerWidgetContent changelogContent">';
	// [\*]
	
	$aDatabaseSettings = idealcheckout_getDatabaseSettings();
	
	$sql = "SELECT `value` FROM `" . $aDatabaseSettings['prefix'] . "idealcheckout_settings` WHERE (`key_name` = 'license') LIMIT 1";
	$sLicense = idealcheckout_database_getValue($sql);
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	$sHtml .= '
			
			<div id="changeLogWidgetAccordion">';
			
			
			$sUrl = 'https://www.ideal-checkout.nl/api/changelog/';
			
			
			$aRequest = array();
			$aRequest['license_key'] = $sLicense;
			
			$sPostData = json_encode($aRequest);
		
			$sResponse = idealcheckout_doHttpRequest($sUrl, $sPostData, true, 30, false);
			
			
			if(!empty($sResponse))
			{			
				$aItems = unserialize($sResponse);
			
				if(sizeof($aItems))
				{
					foreach($aItems as $aChangelog)
					{
						$sDate = ltrim($aChangelog['list_title'], 'b');
						$sTimestamp = strtotime($sDate);
						
						$sItemDate = date('d-m-Y', $sTimestamp);
		
			
						$sHtml .= '
				<h3>Build ' . $aChangelog['list_title'] . '<span class="changeLogWidgetAccordionDate">' . $sItemDate . '</span></h3>
				<div>' . $aChangelog['detail_text'] . '</div>';
				
					}
				}
			}
			
	// [\**]
	
	// [*]
	$sHtml .= '
		</div>
		</div>
	';
	echo($sHtml);
	// [\*]
?>