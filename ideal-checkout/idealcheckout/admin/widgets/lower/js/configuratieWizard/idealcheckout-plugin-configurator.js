
	function createConfigFile(f_element_id, f_step)
	{
		var LF = '\n';
		var TAB = '	';

		if(f_step == 'step1')
		{
			var sAPP = getSelectValue('idealcheckout_application');
			var sPSP = getSelectValue('idealcheckout_psp');
			var sGATEWAY = getSelectValue('idealcheckout_gateway');

			var sHtml = '';
			sHtml += '<input id="idealcheckout_application" name="idealcheckout_application" type="hidden" value="' + sAPP + '">';
			sHtml += '<input id="idealcheckout_psp" name="idealcheckout_psp" type="hidden" value="' + sPSP + '">';
			sHtml += '<input id="idealcheckout_gateway" name="idealcheckout_gateway" type="hidden" value="' + sGATEWAY + '">';

			if(sGATEWAY == 'ABN Amro - iDEAL Easy')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de ABN Amro.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value="TESTiDEALEASY"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Easy (Beveiligd)')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de ABN Amro.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>SHA-1-IN Key</b> (Deze moet je laten instellen door de ABN Amro)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_in" value=""></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Hosted')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de ABN Amro.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de ABN Amro, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Hash Key</b> (Deze moet je zelf instellen in je iDEAL dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Integrated')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de ABN Amro.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de ABN Amro, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Internet Kassa')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de ABN Amro/Ogone.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value=""></div>';
				sHtml += '<div><b>SHA-1-IN Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_in" value=""></div>';
				sHtml += '<div><b>SHA-1-OUT Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_out" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Only')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de ABN Amro/Ogone.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value=""></div>';
				sHtml += '<div><b>SHA-1-IN Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_in" value=""></div>';
				sHtml += '<div><b>SHA-1-OUT Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_out" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Zelfbouw')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de ABN Amro.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de ABN Amro, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'Easy iDEAL - iDEAL')
			{
				sHtml += '<div><b>Merchant ID</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Merchant Key</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_key" value=""></div>';
				sHtml += '<div><b>Merchant Secret</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Fortis Bank - iDEAL Hosted')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de Fortis Bank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Fortis Bank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Hash Key</b> (Deze moet je zelf instellen in je iDEAL dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
			}
			else if(sGATEWAY == 'Fortis Bank - iDEAL Integrated')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de Fortis Bank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Fortis Bank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'Friesland Bank - iDEAL Zakelijk')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de Friesland Bank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Friesland Bank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Hash Key</b> (Deze moet je zelf instellen in je iDEAL dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
			}
			else if(sGATEWAY == 'Friesland Bank - iDEAL Zakelijk Plus')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de Friesland Bank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Friesland Bank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - AfterPay')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Carte Bleue')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Click and Buy')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Credit Card')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Direct E-Banking')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - eBon')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Authorized Transfer')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - FasterPay')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - GiroPay')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Klarnaaccount')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Klarnainvoice')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Manual Transfer')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - iDEAL')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Maestro')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Mastercard')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - MiniTix')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - MisterCash')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - PayPal')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - PaySafeCard')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - PostePay')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - Visa')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - VPAY')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'iDEAL Simulator - WebshopGiftCard')
			{
				// sHtml += '<div>Geen extra instellingen nodig voor deze test omgeving.</div>';
			}
			else if(sGATEWAY == 'ING Bank - iDEAL Advanced')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de ING Bank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de ING Bank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'ING Bank - iDEAL Internet Kassa')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de ING Bank/Ogone.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value=""></div>';
				sHtml += '<div><b>SHA-1-IN Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_in" value=""></div>';
				sHtml += '<div><b>SHA-1-OUT Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_out" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Mollie - iDEAL')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - Creditcard')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - Direct E-Banking/Sofort')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - Handmatig overboeken')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - Mistercash')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - PayPal')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Mollie - PaySafeCard')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Mollie dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Online Betaalplatform - iDEAL')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
				sHtml += '<div><b>Profile UID</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_profile_uid" value=""></div>';
			}
			else if(sGATEWAY == 'Online Betaalplatform - Creditcard')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
				sHtml += '<div><b>Profile UID</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_profile_uid" value=""></div>';
			}
			else if(sGATEWAY == 'Online Betaalplatform - MisterCash')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
				sHtml += '<div><b>Profile UID</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_profile_uid" value=""></div>';
			}
			else if(sGATEWAY == 'Online Betaalplatform - PayPal')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
				sHtml += '<div><b>Profile UID</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_profile_uid" value=""></div>';
			}
			else if(sGATEWAY == 'Online Betaalplatform - Sepa')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
				sHtml += '<div><b>Profile UID</b> (Deze vind u op het Online Betaalplatform dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_profile_uid" value=""></div>';
			}
			else if(sGATEWAY == 'PayCheckout - iDEAL')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - Creditcard')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - Direct ebanking')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - Klarnaaccount')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - Klarnainvoice')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - PayPal')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - Mistercash')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'PayCheckout - SEPA')
			{
				sHtml += '<div><b>Webshop ID</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_webshop_id" value=""></div>';
				sHtml += '<div><b>Encryption Password</b> (Deze vind u op het PayCheckout dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_encryption_password" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Postcode iDEAL - iDEAL Professional')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van Postcode iDEAL.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze kan op 0 blijven staan.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'Qantani - Credit Card')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Qantani dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Qantani - Direct E-Banking')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Qantani dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Qantani - iDEAL')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Qantani dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Qantani - PayPal')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Qantani dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Qantani - Mistercash')
			{
				sHtml += '<div><b>API key</b> (Deze vind u op het Qantani dashboard.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_api_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabobank - iDEAL Internet Kassa')
			{
				sHtml += '<div><b>PSP ID</b> (Deze vind je op het dashboard van de Rabobank/Ogone.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_psp_id" value=""></div>';
				sHtml += '<div><b>SHA-1-IN Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_in" value=""></div>';
				sHtml += '<div><b>SHA-1-OUT Key</b> (Deze moet je zelf instellen in je iDEAL Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sha1_out" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Rabobank - iDEAL Professional')
			{
				sHtml += '<div><b>Merchant ID</b> (Deze vind je op het dashboard van de Rabobank.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank, standaard is deze 0.)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Private Key Pass</b> (Wachtwoord waarmee je private key file is gegenereerd)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_pass" value=""></div>';
				sHtml += '<div><b>Private Key File</b> (Bestandsnaam van je private key file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_key_file" value="private.key"></div>';
				sHtml += '<div><b>Private Certificate File</b> (Bestandsnaam van je private certificate file)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_private_certificate_file" value="private.cer"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - iDEAL')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Creditcard')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Maestro')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Mastercard')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - MisterCash')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - PayPal')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Visa')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - VPAY')
			{
				sHtml += '<div><b>Sandbox Mode</b> (Werk met de Sandbox of Productie omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="false">Productie (Echte betalingen)</option><option value="true">Sandbox (Test betalingen)</option></select></div>';
				sHtml += '<div><b>Refresh Token</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_refresh_token" value=""></div>';
				sHtml += '<div><b>Signing Key</b><br> (Deze vindt u op de beheeromgeving van de <a href="https://bankieren.rabobank.nl/omnikassa-dashboard/" target="_blank" title="https://bankieren.rabobank.nl/omnikassa-dashboard/">Rabo OmniKassa 2.0</a> onder beheer)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_signing_key" value=""></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Credit Card')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - iDEAL')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Maestro')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Mastercard')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - MiniTix')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - MisterCash')
			{
				// sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				// sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				// sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				// sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';

				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
				sHtml += '<div><i>Testbetalingen zijn op dit moment nog niet mogelijk met MisterCash. Om testbetalingen<br>uit te voeren kun je gebruik maken van o.a. iDEAL, MiniTix, Maestro, Visa en Mastercard.</i></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Visa')
			{
				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Sub ID</b> (Deze vind je op het dashboard van de Rabobank)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_sub_id" value="0"></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
			}
			else if(sGATEWAY == 'Rabo OmniKassa - VPAY')
			{
				// sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value="002020000000001"></div>';
				// sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				// sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
				// sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_hash_key" value="002020000000001_KEY1"></div>';
				// sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				// sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';

				sHtml += '<div><b>Winkel-ID</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Geheime Sleutel</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key" value=""></div>';
				sHtml += '<div><b>Sleutel versie/nummer</b><br> (Deze vind je op de downloadsite van de Rabo OmniKassa <a target="_blank" href="https://download.omnikassa.rabobank.nl">https://download.omnikassa.rabobank.nl</a>)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_hash_key_version" value="1"></div>';
				sHtml += '<div><i>Testbetalingen zijn op dit moment nog niet mogelijk met VPAY. Om testbetalingen<br>uit te voeren kun je gebruik maken van o.a. iDEAL, MiniTix, Maestro, Visa en Mastercard.</i></div>';
			}
			else if(sGATEWAY == 'Sisow - Direct E-Banking')
			{
				sHtml += '<div><b>Merchant ID</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Merchant KEY</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_key" value=""></div>';
				sHtml += '<div><b>Shop ID</b> (Deze ontvang je van Sisow)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_shop_id" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Sisow - iDEAL')
			{
				sHtml += '<div><b>Merchant ID</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Merchant KEY</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_key" value=""></div>';
				sHtml += '<div><b>Shop ID</b> (Deze ontvang je van Sisow)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_shop_id" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Sisow - MisterCash')
			{
				sHtml += '<div><b>Merchant ID</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Merchant KEY</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_key" value=""></div>';
				sHtml += '<div><b>Shop ID</b> (Deze ontvang je van Sisow)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_shop_id" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'Sisow - PayPal')
			{
				sHtml += '<div><b>Merchant ID</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_id" value=""></div>';
				sHtml += '<div><b>Merchant KEY</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_merchant_key" value=""></div>';
				sHtml += '<div><b>Shop ID</b> (Deze ontvang je van Sisow)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_shop_id" value=""></div>';
				sHtml += '<div><b>Test Mode</b> (Werk met de TEST of LIVE omgeving)</div>';
				sHtml += '<div><select id="idealcheckout_test_mode" ><option value="true">Ja (Test betalingen)</option><option value="false">Nee (Echte betalingen)</option></select></div>';
			}
			else if(sGATEWAY == 'TargetPay - Credit Card')
			{
				sHtml += '<div><b>Layout Code</b> (Deze vind je terug in je TargetPay Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_layout_code" value=""></div>';
			}
			else if(sGATEWAY == 'TargetPay - Direct E-Banking')
			{
				sHtml += '<div><b>Layout Code</b> (Deze vind je terug in je TargetPay Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_layout_code" value=""></div>';
			}
			else if(sGATEWAY == 'TargetPay - iDEAL')
			{
				sHtml += '<div><b>Layout Code</b> (Deze vind je terug in je TargetPay Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_layout_code" value=""></div>';
			}
			else if(sGATEWAY == 'TargetPay - MisterCash')
			{
				sHtml += '<div><b>Layout Code</b> (Deze vind je terug in je TargetPay Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_layout_code" value=""></div>';
			}
			else if(sGATEWAY == 'TargetPay - PaySafeCard')
			{
				sHtml += '<div><b>Layout Code</b> (Deze vind je terug in je TargetPay Dashboard)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_layout_code" value=""></div>';
			}
			else if(sGATEWAY == 'PayDutch - Direct E-Banking')
			{
				sHtml += '<div><b>Gebruikersnaam van je PayDutch account</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_username" value=""></div>';
				sHtml += '<div><b>Wachtwoord van je PayDutch account</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_password" value=""></div>';
				sHtml += '<div><b>"ClientName" van je PayDutch account</b><br>(Deze moet je zelf instellen in je PayDutch account bij "Technical Settings..")</div>';
				sHtml += '<div><input class="text" id="idealcheckout_callback_username" value=""></div>';
				sHtml += '<div><b>"ClientPassword" van je PayDutch account</b><br>(Deze moet je zelf instellen in je PayDutch account bij "Technical Settings..")</div>';
				sHtml += '<div><input class="text" id="idealcheckout_callback_password" value=""></div>';
			}
			else if(sGATEWAY == 'PayDutch - WeDeal')
			{
				sHtml += '<div><b>Gebruikersnaam van je PayDutch account</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_username" value=""></div>';
				sHtml += '<div><b>Wachtwoord van je PayDutch account</b></div>';
				sHtml += '<div><input class="text" id="idealcheckout_password" value=""></div>';
				sHtml += '<div><b>"ClientName" van je PayDutch account</b><br>(Deze moet je zelf instellen in je PayDutch account bij "Technical Settings..")</div>';
				sHtml += '<div><input class="text" id="idealcheckout_callback_username" value=""></div>';
				sHtml += '<div><b>"ClientPassword" van je PayDutch account</b><br>(Deze moet je zelf instellen in je PayDutch account bij "Technical Settings..")</div>';
				sHtml += '<div><input class="text" id="idealcheckout_callback_password" value=""></div>';
			}
			else
			{
				sHtml += '<div>Unknown gateway: ' + sGATEWAY + '</div>';
			}

			if(sAPP == 'iDEAL Checkout Betaal Formulier')
			{
				sHtml += '<div>&nbsp;</div>';
				sHtml += '<div><b>E-mailadres webmaster</b><br>(De webmaster ontvang per e-mail een melding bij een succesvolle betaling)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_email_to" value="mijn@email-adres.nl"></div>';
			}
			else if(sAPP == 'WordPress & Event Manager PRO')
			{
				sHtml += '<div>&nbsp;</div>';
				sHtml += '<div><b>Betaling voltooid URL</b><br>(Laat de bezoeker deze URL zien na een succesvolle betaling)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_redirect_on_success_url" value=""></div>';
				sHtml += '<div><b>Betaling in behandeling URL</b><br>(Laat de bezoeker deze URL zien bij een betaling die nog de status "wordt verwerkt" heeft)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_redirect_on_pending_url" value=""></div>';
				sHtml += '<div><b>Betaling geannuleerd URL</b><br>(Laat de bezoeker deze URL zien na een geannuleerde betaling)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_redirect_on_cancel_url" value=""></div>';
				sHtml += '<div>&nbsp;</div>';
				sHtml += '<div><b>E-mailadressen t.b.v. status updates</b><br>(Laat de plug-in bij status updates een e-mail verzenden naar opgegeven adressen)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_email_to" value=""></div>';
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Easy')
			{
				// Nothing
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Easy (Beveiligd)')
			{
				// Nothing
			}
			else
			{
				sHtml += '<div>&nbsp;</div>';
				sHtml += '<div><b>E-mailadressen t.b.v. status updates</b><br>(Laat de plug-in bij status updates een e-mail verzenden naar opgegeven adressen)</div>';
				sHtml += '<div><input class="text" id="idealcheckout_email_to" value=""></div>';
			}

			sHtml += '<div><div class="input"><input type="button" class="paymentMethodConfiguratorButton button-back" value="Terug" onclick="javascript: createConfigFile(\'' + f_element_id + '\', \'step0\')"> <input type="button" class="paymentMethodConfiguratorButton button-submit" value="Verder" onclick="javascript: createConfigFile(\'' + f_element_id + '\', \'step2\')"></div></div>';

			document.getElementById(f_element_id).innerHTML = sHtml;
		}
		else if(f_step == 'step2')
		{
			// Find selected APP & PSP
			var sAPP = getSelectValue('idealcheckout_application');
			var sPSP = getSelectValue('idealcheckout_psp');
			var sGATEWAY = getSelectValue('idealcheckout_gateway');

			var sHtml = '';
			sHtml += '<input id="idealcheckout_application" name="idealcheckout_application" type="hidden" value="' + sAPP + '">';
			sHtml += '<input id="idealcheckout_psp" name="idealcheckout_psp" type="hidden" value="' + sPSP + '">';
			sHtml += '<input id="idealcheckout_gateway" name="idealcheckout_gateway" type="hidden" value="' + sGATEWAY + '">';
			sHtml += '<textarea id="paymentMethodConfiguratorResult" wrap="off">';
			sHtml += '&lt;?php' + LF;
			sHtml += LF;
			sHtml += TAB + '/*' + LF;
			sHtml += TAB + TAB + 'Let us help you to create a suitable configuration file for your iDEAL Checkout plug-in.' + LF;
			sHtml += TAB + TAB + 'Go to: http://www.ideal-checkout.nl/' + LF;
			sHtml += TAB + '*/' + LF;
			sHtml += LF;
			sHtml += LF;


			// Add gateway settings
			if(sGATEWAY == 'ABN Amro - iDEAL Easy')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Easy\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easy\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Easy (Beveiligd)')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'PSP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate/validate hashes' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_IN\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_in').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Easy (Beveiligd)\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easy-secure\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Hosted')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Hosted\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Integrated')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Integrated\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Internet Kassa')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'PSP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate/validate hashes' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_IN\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_in').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_OUT\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_out').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Internet Kassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Only')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'PSP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate/validate hashes' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_IN\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_in').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_OUT\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_out').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN Amro - iDEAL Only\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ABN Amro - iDEAL Zelfbouw')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ABN AMRO - iDEAL Zelfbouw\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.abnamro.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Easy iDEAL - iDEAL')
			{
				sHtml += TAB + '// Your Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your Merchant Key' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your Merchant Secret Hash Key' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_SECRET\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Easy iDEAL - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.easy-ideal.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-easyideal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Fortis Bank - iDEAL Hosted')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Fortis Bank - iDEAL Hosted\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.fortisbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Fortis Bank - iDEAL Integrated')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Fortis Bank - iDEAL Integrated\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.fortisbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Friesland Bank - iDEAL Zakelijk')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Friesland Bank - iDEAL Zakelijk\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.frieslandbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Friesland Bank - iDEAL Zakelijk Plus')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Friesland Bank - iDEAL Zakelijk Plus\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.frieslandbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Credit Card')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Credit Card\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Direct E-Banking')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Direct E-Banking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - AfterPay')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - AfterPay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'afterpay-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Carte Bleue')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Carte Bleue\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'cartebleue-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - eBon')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - eBon\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ebon-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Authorized Transfer')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Eenmalige machtiging\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'authorizedtransfer-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - FasterPay')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - FasterPay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'fasterpay-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - GiroPay')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - GiroPay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'giropay-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Klarnaaccount')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Klarnaccount\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'klarnaaccount-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Klarnainvoice')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Klarnaccount\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'klarnainvoice-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Manual Transfer')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Handmatige overboeking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - iDEAL')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ING Bank - iDEAL Advanced')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ING Bank - iDEAL Advanced\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ingbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Maestro')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Maestro\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Mastercard')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Mastercard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - MiniTix')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - MiniTix\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'minitix-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - MisterCash')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - PayPal')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - PaySafeCard')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PaySafeCard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paysafecard-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - PostePay')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - PostePay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'postepay-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - Visa')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - Visa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'visa-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - VPAY')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - VPAY\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'iDEAL Simulator - WebshopGiftCard')
			{
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'iDEAL Simulator - WebshopGiftCard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ideal-simulator.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'webshopgiftcard-simulator\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ING Bank - iDEAL Basic')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ING Bank - iDEAL Basic\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ingbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-lite\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'ING Bank - iDEAL Internet Kassa')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'PSP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate/validate hashes' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_IN\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_in').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_OUT\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_out').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'ING Bank - iDEAL Internet Kassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.ingbank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Mollie - iDEAL')
			{
				sHtml += TAB + '// Mollie API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Mollie - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.mollie.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-mollie\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Mollie - Creditcard')
			{
				sHtml += TAB + '// Mollie API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Mollie - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.mollie.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-mollie\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Mollie - Direct E-Banking/Sofort')
			{
				sHtml += TAB + '// Mollie API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Mollie - Direct E-Banking/Sofort\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.mollie.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-mollie\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Mollie - Mistercash')
			{
				sHtml += TAB + '// Mollie API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Mollie - Mistercash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.mollie.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-mollie\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Mollie - PayPal')
			{
				sHtml += TAB + '// Mollie API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Mollie - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.mollie.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-mollie\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Online Betaalplatform - iDEAL')
			{
				sHtml += TAB + '// Online Betaalplatform API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += TAB + '// Online Betaalplatform Profile UID' + LF;
				sHtml += TAB + '$aSettings[\'PROFILE_UID\'] = \'' + escapePhp(document.getElementById('idealcheckout_profile_uid').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.onlinebetaalplatform.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-onlinebetaalplatform\';' + LF;
			}
			else if(sGATEWAY == 'Online Betaalplatform - Creditcard')
			{
				sHtml += TAB + '// Online Betaalplatform API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += TAB + '// Online Betaalplatform Profile UID' + LF;
				sHtml += TAB + '$aSettings[\'PROFILE_UID\'] = \'' + escapePhp(document.getElementById('idealcheckout_profile_uid').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.onlinebetaalplatform.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-onlinebetaalplatform\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Online Betaalplatform - MisterCash')
			{
				sHtml += TAB + '// Online Betaalplatform API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += TAB + '// Online Betaalplatform Profile UID' + LF;
				sHtml += TAB + '$aSettings[\'PROFILE_UID\'] = \'' + escapePhp(document.getElementById('idealcheckout_profile_uid').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.onlinebetaalplatform.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-onlinebetaalplatform\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Online Betaalplatform - PayPal')
			{
				sHtml += TAB + '// Online Betaalplatform API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += TAB + '// Online Betaalplatform Profile UID' + LF;
				sHtml += TAB + '$aSettings[\'PROFILE_UID\'] = \'' + escapePhp(document.getElementById('idealcheckout_profile_uid').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.onlinebetaalplatform.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-onlinebetaalplatform\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Online Betaalplatform - SEPA')
			{
				sHtml += TAB + '// Online Betaalplatform API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += TAB + '// Online Betaalplatform Profile UID' + LF;
				sHtml += TAB + '$aSettings[\'PROFILE_UID\'] = \'' + escapePhp(document.getElementById('idealcheckout_profile_uid').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Online Betaalplatform - Sepa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.onlinebetaalplatform.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-onlinebetaalplatform\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - iDEAL')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - Creditcard')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - Direct ebanking')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - Direct ebanking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - Klarnaaccount')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - Klarnaaccount\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'klarnaaccount-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - Klarnainvoice')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - Klarnainvoice\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'klarnainvoice-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - PayPal')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - Mistercash')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - Mistercash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayCheckout - SEPA')
			{
				sHtml += TAB + '// Webshop ID' + LF;
				sHtml += TAB + '$aSettings[\'WEBSHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_webshop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Encryption Password' + LF;
				sHtml += TAB + '$aSettings[\'ENCRYPTION_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_encryption_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayCheckout - SEPA\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://www.paycheckout.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'manualtransfer-paycheckout\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Postcode iDEAL - iDEAL Professional')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Postcode iDEAL - iDEAL Professional\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'https://services.postcode.nl/ideal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-postcodeideal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Qantani - Credit Card')
			{
				sHtml += TAB + '// Qantani API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.qantani.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-qantani\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Qantani - Direct E-Banking')
			{
				sHtml += TAB + '// Qantani API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Direct E-Banking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.qantani.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-qantani\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Qantani - iDEAL')
			{
				sHtml += TAB + '// Qantani API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.qantani.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-qantani\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Qantani - PayPal')
			{
				sHtml += TAB + '// Qantani API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.qantani.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-qantani\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Qantani - Mistercash')
			{
				sHtml += TAB + '// Qantani API Key' + LF;
				sHtml += TAB + '$aSettings[\'API_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_api_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Qantani - Mistercash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.qantani.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-qantani\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Rabobank - iDEAL Internet Kassa')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'PSP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_psp_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate/validate hashes' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_IN\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_in').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'SHA_1_OUT\'] = \'' + escapePhp(document.getElementById('idealcheckout_sha1_out').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabobank - iDEAL Internet Kassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-internetkassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabobank - iDEAL Professional')
			{
				sHtml += TAB + '// Merchant ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_sub_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate private key file' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_PASS\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_pass').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-KEY-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_KEY_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_key_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Name of your PRIVATE-CERTIFICATE-FILE (should be located in /idealcheckout/certificates/)' + LF;
				sHtml += TAB + '$aSettings[\'PRIVATE_CERTIFICATE_FILE\'] = \'' + escapePhp(document.getElementById('idealcheckout_private_certificate_file').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabobank - iDEAL Professional\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-professional-v3\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - iDEAL')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Creditcard')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - Creditcard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Maestro')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - Maestro\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Mastercard')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - Mastercard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - MisterCash')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - PayPal')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - Visa')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - Visa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'visa-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa 2.0 - VPAY')
			{
				sHtml += TAB + '// Refresh Token' + LF;
				sHtml += TAB + '$aSettings[\'REFRESH_TOKEN\'] = \'' + escapePhp(document.getElementById('idealcheckout_refresh_token').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Signing key' + LF;
				sHtml += TAB + '$aSettings[\'SIGNING_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_signing_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa 2.0 - VPAY\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-omnikassa-v2\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Credit Card')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Credit Card\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - iDEAL')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Maestro')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Maestro\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'maestro-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Mastercard')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Mastercard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mastercard-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - MiniTix')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - MiniTix\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'minitix-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - MisterCash')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				// sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = false;' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - Visa')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - Visa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'visa-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Rabo OmniKassa - VPAY')
			{
				sHtml += TAB + '// Webshop-ID' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your iDEAL Sub ID' + LF;
				sHtml += TAB + '$aSettings[\'SUB_ID\'] = \'0\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Password used to generate hash' + LF;
				sHtml += TAB + '$aSettings[\'HASH_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'KEY_VERSION\'] = \'' + escapePhp(document.getElementById('idealcheckout_hash_key_version').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				// sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = false;' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Rabo OmniKassa - VPAY\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.rabobank.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'vpay-omnikassa\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = false;' + LF;
			}
			else if(sGATEWAY == 'Sisow - Direct E-Banking')
			{
				sHtml += TAB + '// Merchant ID or Email' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Merchant Key or password' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your SHOP ID (for multiple shops in 1 account)' + LF;
				sHtml += TAB + '$aSettings[\'SHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_shop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - Direct E-Banking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-sisow\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Sisow - iDEAL')
			{
				sHtml += TAB + '// Merchant ID or Email' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Merchant Key or password' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your SHOP ID (for multiple shops in 1 account)' + LF;
				sHtml += TAB + '$aSettings[\'SHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_shop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-sisow\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Sisow - MisterCash')
			{
				sHtml += TAB + '// Merchant ID or Email' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Merchant Key or password' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your SHOP ID (for multiple shops in 1 account)' + LF;
				sHtml += TAB + '$aSettings[\'SHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_shop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-sisow\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'Sisow - PayPal')
			{
				sHtml += TAB + '// Merchant ID or Email' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Merchant Key or password' + LF;
				sHtml += TAB + '$aSettings[\'MERCHANT_KEY\'] = \'' + escapePhp(document.getElementById('idealcheckout_merchant_key').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Your SHOP ID (for multiple shops in 1 account)' + LF;
				sHtml += TAB + '$aSettings[\'SHOP_ID\'] = \'' + escapePhp(document.getElementById('idealcheckout_shop_id').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// Use TEST/LIVE mode; true=TEST, false=LIVE' + LF;
				sHtml += TAB + '$aSettings[\'TEST_MODE\'] = ' + getSelectValue('idealcheckout_test_mode') + ';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'Sisow - PayPal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.sisow.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paypal-sisow\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'TargetPay - Credit Card')
			{
				sHtml += TAB + '// TargetPay Layout Code' + LF;
				sHtml += TAB + '$aSettings[\'LAYOUT_CODE\'] = \'' + escapePhp(document.getElementById('idealcheckout_layout_code').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - Credit Card\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'creditcard-targetpay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'TargetPay - Direct E-Banking')
			{
				sHtml += TAB + '// TargetPay Layout Code' + LF;
				sHtml += TAB + '$aSettings[\'LAYOUT_CODE\'] = \'' + escapePhp(document.getElementById('idealcheckout_layout_code').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - Direct E-Banking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-targetpay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'TargetPay - iDEAL')
			{
				sHtml += TAB + '// TargetPay Layout Code' + LF;
				sHtml += TAB + '$aSettings[\'LAYOUT_CODE\'] = \'' + escapePhp(document.getElementById('idealcheckout_layout_code').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - iDEAL\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-targetpay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'TargetPay - MisterCash')
			{
				sHtml += TAB + '// TargetPay Layout Code' + LF;
				sHtml += TAB + '$aSettings[\'LAYOUT_CODE\'] = \'' + escapePhp(document.getElementById('idealcheckout_layout_code').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - MisterCash\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'mistercash-targetpay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'TargetPay - PaySafeCard')
			{
				sHtml += TAB + '// TargetPay Layout Code' + LF;
				sHtml += TAB + '$aSettings[\'LAYOUT_CODE\'] = \'' + escapePhp(document.getElementById('idealcheckout_layout_code').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'TargetPay - PaySafeCard\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.targetpay.com/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'paysafecard-targetpay\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayDutch - Direct E-Banking')
			{
				sHtml += TAB + '// PayDutch account settings' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_USERNAME\'] = \'' + escapePhp(document.getElementById('idealcheckout_username').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// PayDutch callback security settings' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_CALLBACK_USERNAME\'] = \'' + escapePhp(document.getElementById('idealcheckout_callback_username').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_CALLBACK_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_callback_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayDutch - Direct E-Banking\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.paydutch.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'directebanking-paydutch\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}
			else if(sGATEWAY == 'PayDutch - WeDeal')
			{
				sHtml += TAB + '// PayDutch account settings' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_USERNAME\'] = \'' + escapePhp(document.getElementById('idealcheckout_username').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += TAB + '// PayDutch callback security settings' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_CALLBACK_USERNAME\'] = \'' + escapePhp(document.getElementById('idealcheckout_callback_username').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'PAYDUTCH_CALLBACK_PASSWORD\'] = \'' + escapePhp(document.getElementById('idealcheckout_callback_password').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Basic gateway settings' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_NAME\'] = \'PayDutch - WeDeal\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_WEBSITE\'] = \'http://www.paydutch.nl/\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_METHOD\'] = \'ideal-paydutch\';' + LF;
				sHtml += TAB + '$aSettings[\'GATEWAY_VALIDATION\'] = true;' + LF;
			}

			if(sAPP == 'iDEAL Checkout Betaal Formulier')
			{
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// E-mail settings' + LF;
				sHtml += TAB + '$aSettings[\'EMAIL_TO\'] = \'' + escapePhp(document.getElementById('idealcheckout_email_to').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'EMAIL_FROM\'] = \'&quot;iDEAL Checkout Betaal Formulier&quot; &lt;' + document.getElementById('idealcheckout_email_to').value + '&gt;\';' + LF;
			}
			else if(sAPP == 'WordPress & Event Manager PRO')
			{
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// Feedback URLs' + LF;
				sHtml += TAB + '$aSettings[\'redirect_on_success_url\'] = \'' + escapePhp(document.getElementById('idealcheckout_redirect_on_success_url').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'redirect_on_pending_url\'] = \'' + escapePhp(document.getElementById('idealcheckout_redirect_on_pending_url').value) + '\';' + LF;
				sHtml += TAB + '$aSettings[\'redirect_on_cancel_url\'] = \'' + escapePhp(document.getElementById('idealcheckout_redirect_on_cancel_url').value) + '\';' + LF;
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// E-mailadresses for transaction updates (comma seperated)' + LF;
				sHtml += TAB + '$aSettings[\'TRANSACTION_UPDATE_EMAILS\'] = \'' + escapePhp(document.getElementById('idealcheckout_email_to').value) + '\';' + LF;
			}
			else if(document.getElementById('idealcheckout_email_to') && document.getElementById('idealcheckout_email_to').value)
			{
				sHtml += LF;
				sHtml += LF;
				sHtml += TAB + '// E-mailadresses for transaction updates (comma seperated)' + LF;
				sHtml += TAB + '$aSettings[\'TRANSACTION_UPDATE_EMAILS\'] = \'' + escapePhp(document.getElementById('idealcheckout_email_to').value) + '\';' + LF;
			}

			sHtml += LF;
			sHtml += LF;
			sHtml += '?&gt;</textarea>';

			sHtml += '<div><div class="input"><input type="button" class="paymentMethodConfiguratorButton button-back" value="Terug" onclick="javascript: createConfigFile(\'' + f_element_id + '\', \'step1\')"> <input type="button" class="paymentMethodConfiguratorButton button-submit" value="Opnieuw" onclick="javascript: createConfigFile(\'' + f_element_id + '\', \'step0\')"> <input type="button" class="paymentMethodConfiguratorButton button-apply" value="Opslaan" onclick="paymentMethodConfiguratorSave()"></div></div>';

			document.getElementById(f_element_id).innerHTML = sHtml;
		}
		else
		{
			var sPSP = getUrlValue('psp').toLowerCase();

			if(sPSP == 'abnamro')
			{
				var sPspSelectOptions = '<option value="abnamro">ABN Amro</option>';
			}
			else if(sPSP == 'easyideal')
			{
				var sPspSelectOptions = '<option value="easyideal">Easy iDEAL</option>';
			}
			else if(sPSP == 'fortisbank')
			{
				var sPspSelectOptions = '<option value="fortisbank">Fortis Bank</option>';
			}
			else if(sPSP == 'frieslandbank')
			{
				var sPspSelectOptions = '<option value="frieslandbank">Friesland Bank</option>';
			}
			else if(sPSP == 'idealsimulator')
			{
				var sPspSelectOptions = '<option value="idealsimulator">iDEAL Simulator</option>';
			}
			else if(sPSP == 'ingbank')
			{
				var sPspSelectOptions = '<option value="ingbank">ING Bank</option>';
			}
			else if(sPSP == 'mollie')
			{
				var sPspSelectOptions = '<option value="mollie">Mollie</option>';
			}
			else if(sPSP == 'onlinebetaalplatform')
			{
				var sPspSelectOptions = '<option value="mollie">Online Betaalplatform</option>';
			}
			else if(sPSP == 'paycheckout')
			{
				var sPspSelectOptions = '<option value="paycheckout">PayCheckout</option>';
			}
			else if(sPSP == 'qantani')
			{
				var sPspSelectOptions = '<option value="qantani">Qantani</option>';
			}
			else if(sPSP == 'rabobank')
			{
				var sPspSelectOptions = '<option value="rabobank">Rabobank</option>';
			}
			else if(sPSP == 'sisow')
			{
				var sPspSelectOptions = '<option value="sisow">Sisow</option>';
			}
			else if(sPSP == 'targetpay')
			{
				var sPspSelectOptions = '<option value="targetpay">TargetPay</option>';
			}
			else if(sPSP == 'paydutch')
			{
				var sPspSelectOptions = '<option value="paydutch">PayDutch</option>';
			}
			else
			{
				sPSP = 'abnamro';

				var sPspSelectOptions = '<option value="abnamro">ABN Amro</option><option value="easyideal">Easy iDEAL</option><option value="fortisbank">Fortis Bank</option><option value="frieslandbank">Friesland Bank</option><option value="idealsimulator">iDEAL Simulator</option><option value="ingbank">ING Bank</option><option value="mollie">Mollie</option><option value="onlinebetaalplatform">Online Betaalplatform</option><option value="paycheckout">PayCheckout</option><option value="postcode">Postcode iDEAL</option><option value="qantani">Qantani</option><option value="rabobank">Rabobank</option><option value="sisow">Sisow</option><option value="targetpay">TargetPay</option><option value="paydutch">PayDutch</option>';
			}

			var sHtml = '';
			sHtml += '<div>Kies je software pakket:</div>';
			sHtml += '<div><select id="idealcheckout_application" name="idealcheckout_application" ><optgroup label="Gratis Pakketten"><option value="CS Cart">CS Cart</option><option value="DantoCart">DantoCart</option><option value="Drupal &amp; Commerce">Drupal &amp; Commerce</option><option value="FreeWebshop">FreeWebshop</option><option value="Joomla & AceShop (Joomla to OpenCart Bridge)">Joomla & AceShop (Joomla to OpenCart Bridge)</option><option value="Joomla & AyelShop (Joomla to OpenCart Bridge)">Joomla & AyelShop (Joomla to OpenCart Bridge)</option><option value="Joomla &amp; HikaShop">Joomla &amp; HikaShop</option><option value="Joomla &amp; JoomDonation">Joomla &amp; JoomDonation</option><option value="Joomla & MijoShop (Joomla to OpenCart Bridge)">Joomla & MijoShop (Joomla to OpenCart Bridge)</option><option value="Joomla &amp; VirtueMart">Joomla &amp; VirtueMart</option><option value="Magento">Magento</option><option value="OpenCart">OpenCart</option><option value="OsCommerce">OsCommerce</option><option value="OsDate">OsDate</option><option value="PrestaShop">PrestaShop</option><option value="QuickCart">QuickCart</option><option value="SmartJobBoard">SmartJobBoard</option><option value="TomatoCart">TomatoCart</option><option value="WebsiteBaker &amp; Bakery Shop">WebsiteBaker &amp; Bakery Shop</option><option value="WordPress &amp; eShop">WordPress &amp; eShop</option><option value="WordPress &amp; MarketPress">WordPress &amp; MarketPress</option><option value="WordPress &amp; WooCommerce">WordPress &amp; WooCommerce</option><option value="WordPress &amp; WPSC">WordPress &amp; WPSC</option><option value="XT Commerce">XT Commerce</option><option value="XT Commerce Veyton">XT Commerce Veyton</option><option value="ZenCart">ZenCart</option></optgroup><optgroup label="Betaalde Pakketten"><option value="Joomla &amp; Event Booking">Joomla &amp; Event Booking</option><option value="Joomla &amp; LoveFactory">Joomla &amp; LoveFactory</option><option value="WordPress &amp; Event Manager PRO">WordPress &amp; Event Manager PRO</option><option value="WordPress &amp; Group Buying">WordPress &amp; Group Buying</option><option value="WordPress &amp; Templatic (eCom Framework)">WordPress &amp; Templatic (eCom Framework)</option></optgroup><optgroup label="Overige scripts"><option value="iDEAL Checkout Betaal Formulier">iDEAL Checkout Betaal Formulier</option></optgroup></select></div>';
			sHtml += '<div>Kies je Payment Service Provider:</div>';
			sHtml += '<div><select id="idealcheckout_psp" name="idealcheckout_psp" onchange="javascript: setGateways(this.value);" onselect="javascript: setGateways(this.value);" onkeyup="javascript: setGateways(this.value);">' + sPspSelectOptions + '</select></div>';
			sHtml += '<div>Kies je Betaalmethode:</div>';
			sHtml += '<div><select id="idealcheckout_gateway" name="idealcheckout_gateway" ><option value="">-</option></select></div>';

			sHtml += '<div><div class="input"><input type="button" class="paymentMethodConfiguratorButton button-submit" value="Verder" onclick="javascript: createConfigFile(\'' + f_element_id + '\', \'step1\')"></div></div>';

			document.getElementById(f_element_id).innerHTML = sHtml;

			setGateways(sPSP);
		}
	}

	function setGateways(sPSP)
	{
		var oSelectElement = document.getElementById('idealcheckout_gateway');
		oSelectElement.options.length = 0;

		var bMSIE = (navigator.appVersion.indexOf('MSIE') > -1);

		if(bMSIE)
		{
			if(sPSP == 'abnamro')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Easy';
				oOptionElement.innerText = 'iDEAL Easy';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Easy (Beveiligd)';
				oOptionElement.innerText = 'iDEAL Easy (Beveiligd)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Hosted';
				oOptionElement.innerText = 'iDEAL Hosted';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Integrated';
				oOptionElement.innerText = 'iDEAL Integrated';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Internet Kassa';
				oOptionElement.innerText = 'iDEAL Internet Kassa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Only';
				oOptionElement.innerText = 'iDEAL Only';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Zelfbouw';
				oOptionElement.innerText = 'iDEAL Zelfbouw';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 6;
			}
			else if(sPSP == 'easyideal')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Easy iDEAL - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 0;
			}
			else if(sPSP == 'fortisbank')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Fortis Bank - iDEAL Hosted';
				oOptionElement.innerText = 'iDEAL Hosted';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Fortis Bank - iDEAL Integrated';
				oOptionElement.innerText = 'iDEAL Integrated';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 1;
			}
			else if(sPSP == 'frieslandbank')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Friesland Bank - iDEAL Zakelijk';
				oOptionElement.innerText = 'iDEAL Zakelijk';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Friesland Bank - iDEAL Zakelijk Plus';
				oOptionElement.innerText = 'iDEAL Zakelijk Plus';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 1;
			}
			else if((sPSP == 'idealcheckout') || (sPSP == 'idealsimulator'))
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - AfterPay';
				oOptionElement.innerText = 'AfterPay';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Carte Bleue';
				oOptionElement.innerText = 'Carte Bleue';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Click and Buy';
				oOptionElement.innerText = 'Click and Buy';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Credit Card';
				oOptionElement.innerText = 'Credit Card';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Direct E-Banking';
				oOptionElement.innerText = 'Direct E-Banking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - eBon';
				oOptionElement.innerText = 'eBon';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Authorized Transfer';
				oOptionElement.innerText = 'Eenmalige machtiging';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - FasterPay';
				oOptionElement.innerText = 'FasterPay';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - GiroPay';
				oOptionElement.innerText = 'GiroPay';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Klarnaaccount';
				oOptionElement.innerText = 'Klarnaaccount';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Klarnainvoice';
				oOptionElement.innerText = 'Klarnainvoice';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Manual Transfer';
				oOptionElement.innerText = 'Handmatige overboeking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Maestro';
				oOptionElement.innerText = 'Maestro';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Mastercard';
				oOptionElement.innerText = 'Mastercard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - MiniTix';
				oOptionElement.innerText = 'MiniTix';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - MisterCash';
				oOptionElement.innerText = 'MisterCash (Bancontact / MisterCash)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - PaySafeCard';
				oOptionElement.innerText = 'PaySafeCard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - PostePay';
				oOptionElement.innerText = 'PostePay';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - Visa';
				oOptionElement.innerText = 'Visa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - VPAY';
				oOptionElement.innerText = 'VPAY';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'iDEAL Simulator - WebshopGiftCard';
				oOptionElement.innerText = 'WebshopGiftCard';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 10;
			}
			else if(sPSP == 'ingbank')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ING Bank - iDEAL Advanced';
				oOptionElement.innerText = 'iDEAL Advanced';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ING Bank - iDEAL Internet Kassa';
				oOptionElement.innerText = 'iDEAL Internet Kassa';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 0;
			}
			else if(sPSP == 'mollie')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Mollie - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Mollie - Creditcard';
				oOptionElement.innerText = 'Creditcard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Mollie - Mistercash';
				oOptionElement.innerText = 'Mistercash';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Mollie - Direct E-Banking/Sofort';
				oOptionElement.innerText = 'Direct E-Banking/Sofort';
				oSelectElement.appendChild(oOptionElement);


				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Mollie - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);


				oSelectElement.selectedIndex = 0;
			}
			else if(sPSP == 'onlinebetaalplatform')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Online Betaalplatform - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Online Betaalplatform - Creditcard';
				oOptionElement.innerText = 'Creditcard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Online Betaalplatform - Mistercash';
				oOptionElement.innerText = 'Mistercash';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Online Betaalplatform - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Online Betaalplatform - SEPA';
				oOptionElement.innerText = 'Sepa';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 0;
			}
			else if(sPSP == 'paycheckout')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - Creditcard';
				oOptionElement.innerText = 'Creditcard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - Direct ebanking';
				oOptionElement.innerText = 'Direct ebanking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - Klarnaaccount';
				oOptionElement.innerText = 'Klarnaaccount';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - Klarnainvoice';
				oOptionElement.innerText = 'Klarnainvoice';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - Mistercash';
				oOptionElement.innerText = 'Mistercash';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayCheckout - SEPA';
				oOptionElement.innerText = 'SEPA';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 0;

			}
			else if(sPSP == 'postcode')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Postcode iDEAL - iDEAL Professional';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 0;
			}
			else if(sPSP == 'qantani')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Qantani - Credit Card';
				oOptionElement.innerText = 'Credit Card';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Qantani - Direct E-Banking';
				oOptionElement.innerText = 'Direct E-Banking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Qantani - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Qantani - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Qantani - Mistercash';
				oOptionElement.innerText = 'Mistercash';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 2;
			}
			else if(sPSP == 'rabobank')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabobank - iDEAL Internet Kassa';
				oOptionElement.innerText = 'iDEAL Internet Kassa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabobank - iDEAL Professional';
				oOptionElement.innerText = 'iDEAL Professional';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - iDEAL';
				oOptionElement.innerText = 'OmniKassa / iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - Creditcard';
				oOptionElement.innerText = 'OmniKassa / Creditcard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - Maestro';
				oOptionElement.innerText = 'OmniKassa / Maestro';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - Mastercard';
				oOptionElement.innerText = 'OmniKassa / Mastercard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - MisterCash';
				oOptionElement.innerText = 'OmniKassa / MisterCash (Bancontact)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - PayPal';
				oOptionElement.innerText = 'OmniKassa / PayPal';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - Visa';
				oOptionElement.innerText = 'OmniKassa / Visa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa 2.0 - VPAY';
				oOptionElement.innerText = 'OmniKassa / VPAY';
				oSelectElement.appendChild(oOptionElement);


				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - Credit Card';
				oOptionElement.innerText = 'OmniKassa / Credit Card (Visa en Mastercard gecombineerd)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - iDEAL';
				oOptionElement.innerText = 'OmniKassa / iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - Maestro';
				oOptionElement.innerText = 'OmniKassa / Maestro';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - Mastercard';
				oOptionElement.innerText = 'OmniKassa / Mastercard';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - MisterCash';
				oOptionElement.innerText = 'OmniKassa / MisterCash (Bancontact / MisterCash)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - MiniTix';
				oOptionElement.innerText = 'OmniKassa / MiniTix';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - Visa';
				oOptionElement.innerText = 'OmniKassa / Visa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Rabo OmniKassa - VPAY';
				oOptionElement.innerText = 'OmniKassa / VPAY';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 4;
			}
			else if(sPSP == 'sisow')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Sisow - Direct E-Banking';
				oOptionElement.innerText = 'Direct E-Banking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Sisow - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Sisow - MisterCash';
				oOptionElement.innerText = 'MisterCash (Bancontact / MisterCash)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'Sisow - PayPal';
				oOptionElement.innerText = 'PayPal';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 1;
			}
			else if(sPSP == 'targetpay')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'TargetPay - Credit Card';
				oOptionElement.innerText = 'Credit Card';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'TargetPay - Direct E-Banking';
				oOptionElement.innerText = 'Direct E-Banking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'TargetPay - iDEAL';
				oOptionElement.innerText = 'iDEAL';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'TargetPay - MisterCash';
				oOptionElement.innerText = 'MisterCash (Bancontact / MisterCash)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'TargetPay - PaySafeCard';
				oOptionElement.innerText = 'PaySafeCard';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 2;
			}
			else if(sPSP == 'paydutch')
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayDutch - Direct E-Banking';
				oOptionElement.innerText = 'Direct E-Banking';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'PayDutch - WeDeal';
				oOptionElement.innerText = 'WeDeal';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 1;
			}
			else
			{
				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Easy';
				oOptionElement.innerText = 'iDEAL Easy';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Easy (Beveiligd)';
				oOptionElement.innerText = 'iDEAL Easy (Beveiligd)';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Hosted';
				oOptionElement.innerText = 'iDEAL Hosted';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Integrated';
				oOptionElement.innerText = 'iDEAL Integrated';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Internet Kassa';
				oOptionElement.innerText = 'iDEAL Internet Kassa';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Only';
				oOptionElement.innerText = 'iDEAL Only';
				oSelectElement.appendChild(oOptionElement);

				var oOptionElement = document.createElement('option');
				oOptionElement.value = 'ABN Amro - iDEAL Zelfbouw';
				oOptionElement.innerText = 'iDEAL Zelfbouw';
				oSelectElement.appendChild(oOptionElement);

				oSelectElement.selectedIndex = 6;
			}
		}
		else
		{
			if(sPSP == 'abnamro')
			{
				var sGatewaySelectOptions = '<option value="ABN Amro - iDEAL Easy">iDEAL Easy</option><option value="ABN Amro - iDEAL Easy (Beveiligd)">iDEAL Easy (Beveiligd)</option><option value="ABN Amro - iDEAL Hosted">iDEAL Hosted</option><option value="ABN Amro - iDEAL Integrated">iDEAL Integrated</option><option value="ABN Amro - iDEAL Internet Kassa">iDEAL Internet Kassa</option><option value="ABN Amro - iDEAL Only">iDEAL Only</option><option value="ABN Amro - iDEAL Zelfbouw">iDEAL Zelfbouw</option>';
			}
			else if(sPSP == 'easyideal')
			{
				var sGatewaySelectOptions = '<option value="Easy iDEAL - iDEAL" selected="selected">iDEAL</option>';
			}
			else if(sPSP == 'fortisbank')
			{
				var sGatewaySelectOptions = '<option value="Fortis Bank - iDEAL Hosted">iDEAL Hosted</option><option value="Fortis Bank - iDEAL Integrated">iDEAL Integrated</option>';
			}
			else if(sPSP == 'frieslandbank')
			{
				var sGatewaySelectOptions = '<option value="Friesland Bank - iDEAL Zakelijk">iDEAL Zakelijk</option><option value="Friesland Bank - iDEAL Zakelijk Plus">iDEAL Zakelijk Plus</option>';
			}
			else if((sPSP == 'idealcheckout') || (sPSP == 'idealsimulator'))
			{
				var sGatewaySelectOptions = '<option value="iDEAL Simulator - AfterPay">AfterPay</option><option value="iDEAL Simulator - Carte Bleue">Carte Bleue</option><option value="iDEAL Simulator - Click and Buy">Click and Buy</option><option value="iDEAL Simulator - Credit Card">Credit Card</option><option value="iDEAL Simulator - Direct E-Banking">Direct E-Banking</option><option value="iDEAL Simulator - eBon">eBon</option><option value="iDEAL Simulator - Authorized Transfer">Eenmalige machtiging</option><option value="iDEAL Simulator - FasterPay">FasterPay</option><option value="iDEAL Simulator - Klarnaaccount">Klarnaaccount</option><option value="iDEAL Simulator - Klarnainvoice">Klarnainvoice</option><option value="iDEAL Simulator - GiroPay">GiroPay</option><option value="iDEAL Simulator - Manual Transfer">Handmatige overboeking</option><option value="iDEAL Simulator - iDEAL" selected="selected">iDEAL</option><option value="iDEAL Simulator - iDEAL Advanced/Professional">iDEAL Advanced/Professional</option><option value="iDEAL Simulator - iDEAL Basic/Lite">iDEAL Basic/Lite</option><option value="iDEAL Simulator - Maestro">Maestro</option><option value="iDEAL Simulator - Mastercard">Mastercard</option><option value="iDEAL Simulator - MiniTix">MiniTix</option><option value="iDEAL Simulator - MisterCash">MisterCash (Bancontact / MisterCash)</option><option value="iDEAL Simulator - PayPal">PayPal</option><option value="iDEAL Simulator - PaySafeCard">PaySafeCard</option><option value="iDEAL Simulator - PostePay">PostePay</option><option value="iDEAL Simulator - Visa">Visa</option><option value="iDEAL Simulator - VPAY">VPAY</option><option value="iDEAL Simulator - WebshopGiftCard">WebshopGiftCard</option>';
			}
			else if(sPSP == 'ingbank')
			{
				var sGatewaySelectOptions = '<option value="ING Bank - iDEAL Advanced">iDEAL Advanced</option><option value="ING Bank - iDEAL Internet Kassa">iDEAL Internet Kassa</option>';
			}
			else if(sPSP == 'mollie')
			{
				var sGatewaySelectOptions = '<option value="Mollie - iDEAL" selected="selected">iDEAL</option><option value="Mollie - Mistercash">Mistercash</option><option value="Mollie - Creditcard">Creditcard</option><option value="Mollie - Direct E-Banking/Sofort">Direct E-Banking/Sofort</option><option value="Mollie - PayPal">PayPal</option>';
			}
			else if(sPSP == 'onlinebetaalplatform')
			{
				var sGatewaySelectOptions = '<option value="Online Betaalplatform - iDEAL" selected="selected">iDEAL</option><option value="Online Betaalplatform - Creditcard">Creditcard</option><option value="Online Betaalplatform - Mistercash">Mistercash</option><option value="Online Betaalplatform - PayPal">PayPal</option><option value="Online Betaalplatform - SEPA">Sepa</option>';
			}
			else if(sPSP == 'paycheckout')
			{
				var sGatewaySelectOptions = '<option value="PayCheckout - iDEAL" selected="selected">iDEAL</option><option value="PayCheckout - Creditcard">Creditcard</option><option value="PayCheckout - Direct E-Banking/Sofort">Direct E-Banking/Sofort</option><option value="PayCheckout - Klarnaaccount">Klarnaaccount</option><option value="PayCheckout - Klarnainvoice">Klarnainvoice</option><option value="PayCheckout - PayPal">PayPal</option><option value="PayCheckout - Mistercash">Mistercash</option><option value="PayCheckout - SEPA">Sepa</option>';
			}
			else if(sPSP == 'postcode')
			{
				var sGatewaySelectOptions = '<option value="Postcode iDEAL - iDEAL Professional">iDEAL</option>';
			}
			else if(sPSP == 'qantani')
			{
				var sGatewaySelectOptions = '<option value="Qantani - Credit Card">Credit Card</option><option value="Qantani - Direct E-Banking">Direct E-Banking</option><option value="Qantani - iDEAL" selected="selected">iDEAL</option><option value="Qantani - PayPal">PayPal</option><option value="Qantani - Mistercash">Mistercash</option>';
			}
			else if(sPSP == 'rabobank')
			{
				var sGatewaySelectOptions = '<option value="Rabobank - iDEAL Internet Kassa">iDEAL Internet Kassa</option><option value="Rabobank - iDEAL Professional">iDEAL Professional</option><optgroup label="Rabo Omnikassa 2.0"><option value="Rabo OmniKassa 2.0 - iDEAL" selected="selected">iDEAL</option><option value="Rabo OmniKassa 2.0 - Maestro">Maestro</option><option value="Rabo OmniKassa 2.0 - Mastercard">Mastercard</option><option value="Rabo OmniKassa 2.0 - MisterCash">MisterCash (Bancontact)</option><option value="Rabo OmniKassa 2.0 - PayPal">PayPal</option><option value="Rabo OmniKassa 2.0 - Visa">Visa</option><option value="Rabo OmniKassa 2.0 - VPAY">VPAY</option></optgroup><optgroup label="Rabo Omnikassa 1.0"><option value="Rabo OmniKassa - Credit Card">Credit Card (Visa en Mastercard gecombineerd)</option><option value="Rabo OmniKassa - iDEAL">iDEAL</option><option value="Rabo OmniKassa - Maestro">Maestro</option><option value="Rabo OmniKassa - Mastercard">Mastercard</option><option value="Rabo OmniKassa - MisterCash">MisterCash (Bancontact / MisterCash)</option><option value="Rabo OmniKassa - Visa">Visa</option><option value="Rabo OmniKassa - VPAY">VPAY</option></optgroup>';
			}
			else if(sPSP == 'sisow')
			{
				var sGatewaySelectOptions = '<option value="Sisow - Direct E-Banking">Direct E-Banking</option><option value="Sisow - iDEAL" selected="selected">iDEAL</option><option value="Sisow - MisterCash">MisterCash (Bancontact / MisterCash)</option><option value="Sisow - PayPal">PayPal</option>';
			}
			else if(sPSP == 'targetpay')
			{
				var sGatewaySelectOptions = '<option value="TargetPay - Credit Card">Credit Card</option><option value="TargetPay - Direct E-Banking">Direct E-Banking</option><option value="TargetPay - iDEAL" selected="selected">iDEAL</option><option value="TargetPay - MisterCash">MisterCash (Bancontact / MisterCash)</option><option value="TargetPay - PaySafeCard">PaySafeCard</option>';
			}
			else if(sPSP == 'paydutch')
			{
				var sGatewaySelectOptions = '<option value="PayDutch - Direct E-Banking">Direct E-Banking</option><option value="PayDutch - WeDeal" selected="selected">WeDeal</option>';
			}
			else
			{
				var sGatewaySelectOptions = '<option value="ABN Amro - iDEAL Easy">iDEAL Easy</option><option value="ABN Amro - iDEAL Easy (Beveiligd)">iDEAL Easy (Beveiligd)</option><option value="ABN Amro - iDEAL Hosted">iDEAL Hosted</option><option value="ABN Amro - iDEAL Integrated">iDEAL Integrated</option><option value="ABN Amro - iDEAL Internet Kassa">iDEAL Internet Kassa</option><option value="ABN Amro - iDEAL Only">iDEAL Only</option><option value="ABN Amro - iDEAL Zelfbouw">iDEAL Zelfbouw</option>';
			}

			document.getElementById('idealcheckout_gateway').innerHTML = sGatewaySelectOptions;
		}
	}

	function getSelectValue(f_element_id)
	{
		var oElement = document.getElementById(f_element_id);

		try
		{
			var iIndex = oElement.selectedIndex;
			var sValue = oElement.options[iIndex].value;
		}
		catch(e1)
		{
			var sValue = oElement.value;
		}

		return sValue;
	}

	function getUrlValue(f_var_name)
	{
		var sUrlQuery = window.location.search;
		var sSearch = f_var_name + '=';
		var iPos = sUrlQuery.indexOf(sSearch);

		if(iPos >= 0)
		{
			sUrlQuery = sUrlQuery.substr(iPos + sSearch.length, sUrlQuery.length - (iPos + sSearch.length));

			if((iPos = sUrlQuery.indexOf('&')) >= 0)
			{
				sUrlQuery = sUrlQuery.substr(0, iPos);
			}
			else if((iPos = sUrlQuery.indexOf('#')) >= 0)
			{
				sUrlQuery = sUrlQuery.substr(0, iPos);
			}

			return decodeURIComponent(sUrlQuery);
		}
		else
		{
			return '';
		}
	}

	function escapePhp(f_string)
	{
		return f_string.replace('\'', '\\\'');
	}
