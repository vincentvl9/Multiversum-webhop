<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	
	$sHtml .= '
		
		<div class="ordersNetherlandsMap lowerWidgetDiv" id="ordersNetherlandsMap">
		<script>
			function minimizeordersNetherlandsMap(){
				jQuery(\'.ordersNetherlandsMap\').toggleClass(\'minimized\');
				$( \'.ordersNetherlandsMapContent\' ).slideToggle(\'fast\');
			}
			
			function removeordersNetherlandsMap(){
				$("#ordersNetherlandsMap-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#ordersNetherlandsMap").remove();
				
				aLowerDroppableContents.splice($.inArray("ordersNetherlandsMap-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
		</script>
		<link href="widgets/lower/css/ordersNetherlandsMap.css" media="screen" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="widgets/lower/js/mapael/raphael.min.js"></script>
		<script type="text/javascript" src="widgets/lower/js/mapael/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="widgets/lower/js/mapael/jquery.mapael.min.js"></script>
		<script type="text/javascript" src="widgets/lower/js/mapael/maps/netherlands_provinces.min.js"></script>
		<div class="ordersNetherlandsMapTopDeco widgetTopDeco">
		</div>
		<div class="ordersNetherlandsMapTopInfo widgetTopInfo">
			Orders Nederland map
			<div class="removeLowerWidget" onclick="removeordersNetherlandsMap()"></div>
			<div class="minusLowerWidget" onclick="minimizeordersNetherlandsMap()"></div>
			<div class="infoIconLowerWidget" data-balloon="Bestellingen per provincie, afgelopen 30 dagen" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
		<div  class="lowerWidgetContent ordersNetherlandsMapContent">
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
		
	$sHtml .= '			
		<script type="text/javascript">
		setTimeout(function() {
			$(".ordersNetherlandsMapContainer").mapael({
				map : {
					name : "netherlands"
					// Enable zoom on map
					, zoom: {
						enabled: false,
						maxlevel: 20
					}
				},
				legend: {
                    area: {
                        display: false,
                        title: "Bestellingen",
                        slices: [
							{
								min: "0 ",
								max: "99",
								attrs: {
									fill: "#343434"
								},
								label: "Less than 99"
							},
                            {
                                min: 999999999,
                                attrs: {
                                    fill: "#01565E"
                                },
                                label: "More than 12"
                            }
                        ]
                    }
				},
				areas: {
					"Drenthe": {
						"value": "2",
						"tooltip": {
							"content": "<span style=\"font-weight:bold;\"> Drenthe </span> <br> bestellingen: 2"
						}
					},
					"END": {
						"value": 0,
						"href": "#",
						"tooltip": {
							"content": "END"
						}
					}
				}
			});
		}, 1000);
		</script>
		
		<div class="ordersNetherlandsMapContainer">
			<div class="map">Alternative content</div>
			<div class="areaLegend"></div>
		</div>
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