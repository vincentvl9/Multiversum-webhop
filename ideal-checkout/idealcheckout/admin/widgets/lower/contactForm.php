<?php
	$sHtml = '';
	// [*] (stop deze code in iedere widget) 
	define('IDEALCHECKOUT_PATH', dirname(dirname(dirname(__DIR__))));
	require_once(IDEALCHECKOUT_PATH . '/includes/library.php');
	$sImagePath = idealcheckout_getRootUrl(2). 'images';
	$sLowerWidgetPath = idealcheckout_getRootUrl(2). 'widgets/lower';

	$sPhpPath = IDEALCHECKOUT_PATH . '/admin/php/';
	
	require_once($sPhpPath . 'index.php');
	
	$sHtml .= '
		
		
		<div class="contactForm lowerWidgetDiv" id="contactForm">
		<script>
			function minimizeContactForm(){
				jQuery(\'.contactForm\').toggleClass(\'minimized\');
				$( \'.contactFormContent\' ).slideToggle(\'fast\');
			}
			
			function removecontactForm(){
				$("#contactForm-lowerDraggable").css({ "display": "block", "left": "0px", "top": "0px" }); 
				$("#contactForm").remove();
				
				aLowerDroppableContents.splice($.inArray("contactForm-lowerDraggable", aLowerDroppableContents), 1, "");
				lowerSortableContent();
			}
			
			$(function() {
				$("#contactFormAttachement").change(function (){
					var fileName = $(this).val();
					fileName = fileName.substring(12);
					$(".contactFormFilename").html(fileName);
				});
			});
			
		</script>

		<link href="widgets/lower/css/contactForm.css" media="screen" rel="stylesheet" type="text/css">
		<div class="contactFormTopDeco widgetTopDeco brand-background-color">
		</div>
		<div class="contactFormTopInfo widgetTopInfo">
			Support form
			<div class="removeLowerWidget" onclick="removecontactForm()"></div>
			<div class="minusLowerWidget" onclick="minimizeContactForm()">
				
			</div>
			<div class="infoIconLowerWidget" data-balloon="Verstuur een mail naar iDEAL Checkout" data-balloon-pos="left">
				<img src="' . $sImagePath . '/info-icon.svg" class="svg" height="100%" >
			</div>
			<div class="lockWidgetsIcon noRightClick" onclick="widgetDragLock()"  data-balloon="vergrendel / ontgrendel widget locatie" data-balloon-pos="left">
				
			</div>
		</div>
		<div  class="lowerWidgetContent contactFormContent">
	';
	// [\*]
	
	// [**] codeer hier wat er in de widget moet komen te staan.
	$aForm = array('data' => array('contact' => '', 'email' => '', 'phone' => '', 'subject' => '', 'remarks' => ''), 'error' => array('contact' => false, 'email' => false, 'phone' => false, 'subject' => false, 'remarks' => false));


	if(isset($_POST['form']) && (strcasecmp($_POST['form'], 'support-form') ===0))
	{		
		if(!empty($_POST['contact']) && (strcmp($_POST['contact'], 'Contactpersoon') !== 0))
		{
			$aForm['data']['contact'] = $_POST['contact'];
		}
		else
		{
			$aForm['error']['contact'] = true;
		}

		
		if(!empty($_POST['email']) && (strcmp($_POST['email'], 'test@mail.nl') !== 0))
		{
			$sRegex = "/^([a-z0-9\-_\.]+)@([a-z0-9\-_\.]+)\.[a-z]{2,6}$/i";
			
			if(preg_match($sRegex, $_POST['email']))
			{
				// Email is valid
				$aForm['data']['email'] = $_POST['email'];
			}
			else
			{
				$aForm['error']['email'] = true;
			}
		}

		if(!empty($_POST['phone']) && (strlen($_POST['phone'] > 8)) && (strcmp($_POST['phone'], '0522746060') !== 0))
		{
			$aForm['data']['phone'] = $_POST['phone'];
		}
		else
		{
			$aForm['error']['phone'] = true;
		}
		
		if(!empty($_POST['subject']) && (strcasecmp($_POST['subject'], '-') !== 0))
		{
			$aForm['data']['subject'] = $_POST['subject'];
		}
		else
		{
			$aForm['error']['subject'] = true;
		}
		
		if(!empty($_POST['remarks']))
		{
			$aForm['data']['remarks'] = $_POST['remarks'];
		}
		else
		{
			$aForm['error']['remarks'] = true;
		}
		
		if(!empty($_POST['attachment']))
		{
			// Check file extension
			$aFileInfo = pathinfo($_POST['attachment']);
						
			//Not allowed fileextensions, determined by google
			$aProhibitedExtensions = array('ADE', 'ADP', 'BAT', 'CHM', 'CMD', 'COM', 'CPL', 'EXE', 'HTA', 'INS', 'ISP', 'JAR', 'JS', 'JSE', 'LIB', 'LNK', 'MDE', 'MSC', 'MSI', 'MSP', 'MST', 'NSH', 'PIF', 'SCR', 'SCT', 'SHB', 'SYS', 'VB', 'VBE', 'VBS', 'VXD', 'WSC', 'WSF', 'WSH');
			
			if(in_array($aFileInfo['extension'], $aProhibitedExtensions))
			{
				$aForm['error']['attachment'] = true;
			}
			else
			{
				$aForm['data']['attachment'] = $_POST['attachment'];
			}
		}
		
		if(!in_array(true, $aForm['error']))
		{
			$_SESSION['form']['support-form'] = $aForm['data'];
		
		
			// Send email to iDEAL Checkout
			$sFromName = 'iDEAL Checkout - Contact';
			$sFromMail = $aForm['data']['email'];
			$aToMails = array($aForm['data']['email'], 'info@ideal-checkout.nl');
			$sSubject =  'Backend support: ' . $aForm['data']['subject'] . ' voor ' . $aForm['data']['contact'];
			
			$sAttachment = '';
			
			if(!empty($aForm['data']['attachment']))
			{
				$sAttachment = $aForm['data']['attachment'];
			}
			

			$sTempDirectory = IDEALCHECKOUT_PATH . '/temp/';
			$sUploadFile = $sTempDirectory . basename($_FILES['attachment']['name']);

			
			if(move_uploaded_file($_FILES['attachment']['tmp_name'], $sUploadFile)) 
			{
			}
			/*
			else 
			{
				echo 'Bijlage afgekeurd, probeer opnieuw of laat de bijlage weg.';
				exit;
			}
			*/

			
			$sMessage = '';
			
			if(!empty($aForm['data']['remarks']))
			{
				$sMessage = $aForm['data']['remarks'];
			}

		
			foreach($aToMails as $sEmail)
			{
				clsMail::sendHtml($sFromName, $sFromMail, $sEmail, $sSubject, $sMessage, array($sUploadFile));
			}
			
			header('Location:' . idealcheckout_getRootUrl(2) . '?action=contactmail');
			exit;
			
		}else{
			header('Location:' . idealcheckout_getRootUrl(2) . '?action=contactmailerror');
			exit;
		}
	}
	
	
	$sHtml .= '
		<div class="mod-install-form">
			<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="form" value="support-form">
				<table border="0" cellpadding="0" cellspacing="0" class="list">
					<tr>
						<td' . (($aForm['error']['contact']) ? ' class="incorrect" ' : '') . '>
							<div class="input"><input class="textfield textfield_name contactFormField" placeholder="Uw naam *" name="contact" type="text" value="' . htmlentities($aForm['data']['contact']) . '"></div>
						</td>
					</tr>
					<tr>
						<td' . (($aForm['error']['email']) ? ' class="incorrect" ' : '') . '>
							<div class="input"><input class="textfield textfield_email contactFormField" placeholder="Uw mail adres *" name="email" type="text" value="' . htmlentities($aForm['data']['email']) . '"></div>
						</td>
					</tr>
					<tr>
						<td' . (($aForm['error']['phone']) ? ' class="incorrect" ' : '') . '>
							<div class="input"><input class="textfield textfield_phone contactFormField" placeholder="Uw telefoonnummer *" name="phone" type="text" value="' . htmlentities($aForm['data']['phone']) . '"></div>
						</td>
					</tr>
					<tr>
						<td' . (($aForm['error']['subject']) ? ' class="incorrect" ' : '') . '>
							<div class="select">
								<span style="font-size: 14px;">Ik heb een vraag over: </span>
								<select name="subject" class="contactformSelector">
									<option value="">-</option>
									<option value="algemeen" selected>Algemene vraag</option>
									<option value="bug">Bug/fout in beheeromgeving</option>
									<option value="technisch-support">Technisch support</option>
									<option value="plugin">De plugins</option>
									<option value="betaalkoppeling">Betaalkoppelingen</option>
									<option value="beheeromgeving">De IC beheeromgeving</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td' . (($aForm['error']['remarks']) ? ' class="incorrect" ' : '') . '>
							<div class="input"><input class="textarea textarea_remarks" placeholder="Opmerkingen / Bericht *" name="remarks" type="text" value="' . htmlentities($aForm['data']['remarks']) . '"></div>
						</td>
					</tr>
					<tr>
						
					</tr>
					<tr>
						<td><table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<div class="input">
										<input name="attachment" id="contactFormAttachement" class="contactFormAttachement" type="file" value="logs.zip">
										<label for="contactFormAttachement"> 
											<span>Bestand</span>
										</label>
										<div class="contactFormFilenameDiv">
											<span class="contactFormFilename"> Toevoegen </span>
										</div>
									</div>
								</td>
								<td>
									<input class="button button-submit" type="submit" value="Versturen">
								</td>
							</tr>
						</table></td>
					</tr>
				</table>
			</form>
		</div>';
	
	// [\**]
	
	// [*]
	$sHtml .= '
		</div>
		</div>
		
	';
	echo($sHtml);
	// [\*]
?>