<?php

	class clsMail
	{
		// Send HTML mail
		public static function sendHtml($sFromName, $sFromMail, $sToMail, $sSubject, $sMessage, $aFiles = false, $iPriority = 3, $sBounceMail = '')
		{
			$bUseClsLog = false;

			require_once('external/phpmailer.php');

			$oPhpMailer = new PHPMailer();

			$oPhpMailer->Encoding = '7bit';
			$oPhpMailer->LE = LF;


			$oPhpMailer->AddAddress($sToMail);
			$oPhpMailer->Sender = $sFromMail;
			$oPhpMailer->From = $sFromMail;
			$oPhpMailer->FromName = $sFromName;

			$oPhpMailer->Priority = $iPriority;
			$oPhpMailer->Subject = $sSubject;
			// $oPhpMailer->Body = $sMessage;
			$oPhpMailer->AltBody = 'Please use a HTML compatible email viewer!';
			$oPhpMailer->MsgHTML($sMessage);
			
			if($aFiles)
			{				
				for($i = 0; $i < sizeof($aFiles); $i++)
				{
					$oPhpMailer->AddAttachment($aFiles[$i]);
				}
			}

			if($oPhpMailer->Send())
			{
				
				return true;
			}
			else
			{
				return false;
			}
		

		}
	}

?>