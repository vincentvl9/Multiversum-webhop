<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget)
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';

	$sHtml .= '

		<div class="paymentMethodConfigurator lowerWidgetDiv" id="paymentMethodConfigurator">
		<script type="text/javascript" src="widgets/lower/js/configuratieWizard/idealcheckout-plugin-configurator.js"></script>
		<script>
			function minimizepaymentMethodConfigurator(){
				jQuery(\'.paymentMethodConfigurator\').toggleClass(\'minimized\');
				$( \'.paymentMethodConfiguratorContent\' ).slideToggle(\'fast\');
			}

			function removepaymentMethodConfigurator(){
				$("#paymentMethodConfigurator-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" });
				$("#paymentMethodConfigurator").remove();

				aLowerDroppableContents.splice($.inArray("paymentMethodConfigurator-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}

			function paymentMethodConfiguratorSave(){
				var result = $(\'#paymentMethodConfiguratorResult\').val();
				var gateway = $(\'#idealcheckout_gateway\').val();
					$.ajax({
						type: "POST",
						url: "widgets/lower/paymentMethodConfiguratorHandler.php",
						data: {sResultData: result, sGatewayCode: gateway},
						success: function( response ){
							console.log( response );
							$("#paymentMethodConfiguratorResult").css("border", "4px solid #00A65A");
							setTimeout(function(){
								$("#paymentMethodConfiguratorResult").css("border", "");
							}, 3000);
						}
					});
			}

			createConfigFile(\'config_file_content\');
		</script>
		<link href="widgets/lower/css/paymentMethodConfigurator.css" media="screen" rel="stylesheet" type="text/css">


		<div class="paymentMethodConfiguratorTopDeco widgetTopDeco">
		</div>
		<div class="paymentMethodConfiguratorTopInfo widgetTopInfo">PSP Configurator
			<div class="removeLowerWidget" onclick="removepaymentMethodConfigurator()"></div>
			<div class="minusLowerWidget" onclick="minimizepaymentMethodConfigurator()"></div>
			<div class="infoIconLowerWidget" data-balloon="Configureer uw betaalmethoden" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">

			</div>
		</div>
		<div  class="lowerWidgetContent paymentMethodConfiguratorContent">
	';
	// [\*]

	// [**] codeer hier wat er in de widget moet komen te staan.
	$sHtml .= '
		<div id="config_file_content"></div>
		<div class="test-url"><a href="' . idealcheckout_getRootUrl(4) . 'idealcheckout/test.php" target="_blank">Klik hier om uw betaalmethode te testen.</div>
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
