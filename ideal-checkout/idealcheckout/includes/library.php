<?php

	// Set default debug settings
	if(is_file(__DIR__ . '/debug.php'))
	{
		include_once(__DIR__ . '/debug.php');
	}

	if(is_file(__DIR__ . '/update.order.status.php'))
	{
		include_once(__DIR__ . '/update.order.status.php');
	}

	// Create a random code with N digits.
	function idealcheckout_getRandomCode($iLength = 64)
	{
		$aCharacters = array('a', 'b', 'c', 'd', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

		$sResult = '';
		$sFunc = function_exists('random_int') ? 'random_int' : 'rand';
		$iMax = sizeof($aCharacters) - 1;

		for($i = 0; $i < $iLength; $i++)
		{
			$sResult .= $aCharacters[$sFunc(0, $iMax)];
		}

		return $sResult;
	}


	// Find HASH salt
	function idealcheckout_getHashSalt($sStoreCode = false)
	{
		$aData = idealcheckout_getDatabaseSettings();
		return md5((is_string($sStoreCode) ? $sStoreCode : idealcheckout_getStoreCode()) . idealcheckout_serialize($aData));
	}


	// Find default store code
	function idealcheckout_getStoreCode()
	{
		return md5($_SERVER['SERVER_NAME']);
	}


	// Retrieve ROOT url of script
	function idealcheckout_getRootUrl($iParent = 0)
	{
		// Use a fixed ROOT_URL
		// return 'http://www.example.com/';
		$aWebsiteSettings = idealcheckout_getWebsiteSettings();

		if(!empty($aWebsiteSettings['root_url']))
		{
			if(substr($aWebsiteSettings['root_url'], -1, 1) == '/')
			{
				$_REQUEST['ROOT_URL'] = $aWebsiteSettings['root_url'];
			}
			else
			{
				$_REQUEST['ROOT_URL'] = $aWebsiteSettings['root_url'] . '/';
			}

			return $_REQUEST['ROOT_URL'];
		}


		// Detect installation directory based on current URL
		$sRootUrl = '';

		// Detect scheme
		if(isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'ON') === 0))
		{
			$sRootUrl .= 'https://';
		}
		else
		{
			$sRootUrl .= 'http://';
		}

		// Detect domain
		$sRootUrl .= $_SERVER['HTTP_HOST'];

		 // Detect port
		if((strpos($_SERVER['HTTP_HOST'], ':') === false) && isset($_SERVER['SERVER_PORT']))
		{
			if(isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'ON') === 0))
			{
				if((strcmp($_SERVER['SERVER_PORT'], '443') !== 0) && (strcmp($_SERVER['SERVER_PORT'], '80') !== 0))
				{
					$sRootUrl .= ':' . $_SERVER['SERVER_PORT'];
				}
			}
			elseif(strcmp($_SERVER['SERVER_PORT'], '80') !== 0)
			{
				$sRootUrl .= ':' . $_SERVER['SERVER_PORT'];
			}
		}

		$sRootUrl .= '/';

		// Detect path
		if(isset($_SERVER['SCRIPT_NAME']))
		{
			$a = explode('/', substr($_SERVER['SCRIPT_NAME'], 1));

			while(sizeof($a) > ($iParent + 1))
			{
				$sRootUrl .= $a[0] . '/';
				array_shift($a);
			}
		}

		$_REQUEST['ROOT_URL'] = $sRootUrl;
		return $_REQUEST['ROOT_URL'];
	}



	// Retrieve ROOT url of script
	function idealcheckout_getRootPath()
	{
		$sRootPath = dirname(dirname(__DIR__));

		if(strpos($sRootPath, '\\') !== false)
		{
			$sRootPath .= '\\';
		}
		else
		{
			$sRootPath .= '/';
		}

		return $sRootPath;
	}


	// Retrieve ROOT url of script
	function idealcheckout_isLocalFile($sFile)
	{
		if(strpos($sFile, '../') === false) // No relative paths..
		{
			$sRootPath = idealcheckout_getRootPath();
			$sFilePath = substr($sFile, 0, strlen($sRootPath));

			if(strcmp($sFilePath, $sRootPath) === 0)
			{
				if(@is_file($sFile) && @is_readable($sFile))
				{
					return true;
				}
			}
		}

		return false;
	}


	// See if website is in debug mode
	function idealcheckout_getDebugMode()
	{
		if(is_file(__DIR__ . '/debug.php'))
		{
			return true;
		}
		elseif(is_dir(dirname(__DIR__) . '/install'))
		{
			return true;
		}

		return false;
	}


	function idealcheckout_prepareSql($sQuery, $aValues = array(), $aLikes = array(), $aNames = array())
	{
		$aSearch = array();
		$aReplace = array();

		// Escape normal values
		foreach($aValues as $k => $v)
		{
			if(is_string($v))
			{
				$v = idealcheckout_escapeSql($v, false);
			}

			$aSearch[] = '{' . $k . '}';
			$aReplace[] = $v;
		}

		// Escape like values
		foreach($aLikes as $k => $v)
		{
			if(is_string($v))
			{
				$v = idealcheckout_escapeSql($v, true);
			}

			$aSearch[] = '{' . $k . '}';
			$aReplace[] = $v;
		}

		// Escape database/table/column name (only a-z, A-Z, 0-9, - and _ are allowed)
		foreach($aNames as $k => $v)
		{
			if(is_string($v))
			{
				$v = preg_replace('/([^a-zA-Z0-9\-_]+)/', '', $v);
			}

			$aSearch[] = '{' . $k . '}';
			$aReplace[] = $v;
		}

		$sQuery = idealcheckout_replace($sQuery, $aSearch, $aReplace);
		return $sQuery;
	}

	// Escape SQL values
	function idealcheckout_escapeSql($sString, $bEscapeLike = false)
	{
		global $aIdealCheckout;
		$oDatabaseConnection = idealcheckout_database_setup();

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			$sString = mysqli_real_escape_string($oDatabaseConnection, $sString);
		}
		else
		{
			$sString = mysql_real_escape_string($sString, $oDatabaseConnection);
		}

		if($bEscapeLike)
		{
			$sString = str_replace(array('_', '%'), array('\\_', '\\%'), $sString);
		}

/*
		if($bEscapeLike)
		{
			// _ : represents a single character in a LIKE value
			// % : represents 0 or more character in a LIKE value
			$sString = str_replace(array('\\', '\'', '_', '%'), array('\\\\', '\\\'', '\\_', '\\%'), $sString);
		}
		else
		{
			$sString = str_replace(array('\\', '\''), array('\\\\', '\\\''), $sString);
		}
*/

		return $sString;
	}


	// Escape quoted strings
	function idealcheckout_escapeQuotes($sString, $bEscapeDoubleQuotes = false)
	{
		if($bEscapeDoubleQuotes)
		{
			$sString = str_replace(array('\\', '"'), array('\\\\', '\\"'), $sString);
		}
		else
		{
			$sString = str_replace(array('\\', '\''), array('\\\\', '\\\''), $sString);
		}

		return $sString;
	}

	function idealcheckout_replace($sString, $aSearch, $aReplace)
	{
		$sResult = '';

		if(!is_array($aSearch))
		{
			$aSearch = array($aSearch);
		}

		while(strlen($sString))
		{
			$bMatchFound = false;

			foreach($aSearch as $iIndex => $sSearch)
			{
				$iLength = strlen($sSearch);
				$sCompare = substr($sString, 0, $iLength);

				if(strcmp($sCompare, $sSearch) === 0)
				{
					$bMatchFound = true;

					if(is_array($aReplace))
					{
						if(isset($aReplace[$iIndex]))
						{
							$sResult .= $aReplace[$iIndex];
						}
					}
					else
					{
						$sResult .= $aReplace;
					}

					$sString = substr($sString, $iLength);
					break;
				}
			}

			if(!$bMatchFound)
			{
				// Go to next char
				$sResult .= substr($sString, 0, 1);
				$sString = substr($sString, 1);
			}
		}

		return $sResult;
	}



	// Serialize data
	function idealcheckout_serialize($mData)
	{
		return json_encode($mData);
	}

	// See if data contains serialized strings (possible injection?!)
	function idealcheckout_serialize_hasInjection($mData)
	{
		if(is_array($mData) || is_object($mData))
		{
			foreach($mData as $k => $v)
			{
				if(idealcheckout_serialize_hasInjection($v) || idealcheckout_serialize_hasInjection($k))
				{
					return true;
				}
			}
		}
		elseif(is_string($mData) && strpos($mData, ':'))
		{
			if(preg_match('/([aAoOsS]:[0-9]+:[\{"])/', $mData))
			{
				return true;
			}
		}

		return false;
	}


	// Unserialize data
	function idealcheckout_unserialize($sString)
	{
		// Recalculate multibyte strings
		// $sString = preg_replace_callback('/s:(\d+):"(.*?)";/', 'idealcheckout_unserialize_callback', $sString);
		// return unserialize($sString);

		if(empty($sString))
		{
			idealcheckout_log('String is empty.', __FILE__, __LINE__);
			return array();

		}

		if(substr($sString, 0, 2) == 'a:') // Treat as SERIALIZED string
		{
			// Recalculate multibyte strings
			if(PHP_VERSION < 7)
			{
				$sString = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $sString);
			}

			return unserialize($sString);
		}
		else // Treat as JSON string
		{
			return json_decode($sString, true);
		}
	}

	function idealcheckout_unserialize_callback($aMatch)
	{
		return 's:' . strlen($aMatch[2]) .':"' . $aMatch[2] . '";';
	}


	// Replace characters with accents
	function idealcheckout_escapeAccents($sString)
	{
		return str_replace(array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ð', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', '§', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', '€', 'Ð', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', '§', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Ÿ', chr(96), chr(132), chr(133), chr(145), chr(146), chr(147), chr(148), chr(150), chr(151)), array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'ed', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 's', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'EUR', 'ED', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'S', 'U', 'U', 'U', 'U', 'Y', 'Y', '\'', '"', '...', '\'', '\'', '"', '"', '-', '-'), $sString);
	}

	// Load data from an URL
	function idealcheckout_doHttpRequest($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30, $bDebug = false, $aAdditionalHeaders = false)
	{
		if(!empty($sUrl))
		{
			$aServerSettings = idealcheckout_getServerSettings();

			if(!empty($aServerSettings['httprequest']) && (strcmp($aServerSettings['httprequest'], 'fsock') === 0))
			{
				if(in_array('sockets', get_loaded_extensions())) // Prefer FSOCK
				{
					return idealcheckout_doHttpRequest_fsock($sUrl, $sPostData, $bRemoveHeaders, $iTimeout, $bDebug, $aAdditionalHeaders);
				}
				else
				{
					idealcheckout_die('idealcheckout_doHttpRequest: Cannot detect fsock, check your server configuration: ../idealcheckout/configuration/server.php ', __FILE__, __LINE__);
				}
			}
			else
			{
				if(in_array('curl', get_loaded_extensions()) && function_exists('curl_init'))
				{
					return idealcheckout_doHttpRequest_curl($sUrl, $sPostData, $bRemoveHeaders, $iTimeout, $bDebug, $aAdditionalHeaders);
				}
				else
				{
					idealcheckout_die('idealcheckout_doHttpRequest: Cannot detect cURL, check your server configuration: ../idealcheckout/configuration/server.php ', __FILE__, __LINE__);
				}
			}
		}

		return '';
	}

	// doHttpRequest (Uses sockets-library)
	function idealcheckout_doHttpRequest_fsock($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30, $bDebug = false, $aAdditionalHeaders = false)
	{
		$aUrl = parse_url($sUrl);

		$sRequestUrl = '';

		if(in_array($aUrl['scheme'], array('ssl', 'https')))
		{
			$sRequestUrl .= 'ssl://';

			if(empty($aUrl['port']))
			{
				$aUrl['port'] = 443;
			}
		}
		elseif(empty($aUrl['port']))
		{
			$aUrl['port'] = 80;
		}

		$sRequestUrl .= $aUrl['host'];
		$iRequestPort = intval($aUrl['port']);

		$sErrorNumber = 0;
		$sErrorMessage = '';

		$oSocket = fsockopen($sRequestUrl, $iRequestPort, $sErrorNumber, $sErrorMessage, $iTimeout);
		$sResponse = '';

		if($oSocket)
		{
			$sRequest = ($sPostData ? 'POST' : 'GET') . ' ' . (empty($aUrl['path']) ? '/' : $aUrl['path']) . (empty($aUrl['query']) ? '' : '?' . $aUrl['query']) . ' HTTP/1.0' . "\r\n";
			$sRequest .= 'Host: ' . $aUrl['host'] . "\r\n";
			$sRequest .= 'Accept: text/html' . "\r\n";
			$sRequest .= 'Accept-Charset: charset=ISO-8859-1,utf-8' . "\r\n";

			if(is_array($sPostData))
			{
				$sPostData = str_replace(array('%5B', '%5D'), array('[', ']'), http_build_query($sPostData));
			}

			if($sPostData)
			{
				$sRequest .= 'Content-Length: ' . strlen($sPostData) . "\r\n";


				if(substr($sPostData, 0, 1) == '{') // JSON string
				{
					$sRequest .= 'Content-Type: application/json';
				}
				else
				{
					$sRequest .= 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . "\r\n" . "\r\n";

				}

				$sRequest .= $sPostData;
			}
			else
			{
				$sRequest .= "\r\n";
			}


			if($bDebug === true)
			{
				echo "\r\n" . "\r\n" . '<h1>SEND DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sRequest)) . '</code>' . "\r\n" . "\r\n";
			}


			// Send data
			fputs($oSocket, $sRequest);

			// Recieve data
			while(!feof($oSocket))
			{
				$sResponse .= @fgets($oSocket, 128);
			}

			fclose($oSocket);


			if($bDebug === true)
			{
				echo "\r\n" . "\r\n" . '<h1>RECIEVED DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sResponse)) . '</code>' . "\r\n" . "\r\n";
			}


			if($bRemoveHeaders) // Remove headers from reply?
			{
				list($sHeader, $sBody) = preg_split('/(\\r?\\n){2,2}/', $sResponse, 2);
				return $sBody;
			}
			else
			{
				return $sResponse;
			}
		}
		else
		{
			if($bDebug)
			{
				echo "\r\n" . "\r\n" . 'Cannot connect to: ' . htmlspecialchars($sRequestUrl);
			}

			die('Socket error: ' . htmlspecialchars($sErrorMessage));
		}
	}

	// doHttpRequest (Uses curl-library)
	function idealcheckout_doHttpRequest_curl($sUrl, $sPostData = false, $bRemoveHeaders = false, $iTimeout = 30, $bDebug = false, $aAdditionalHeaders = false)
	{
		global $bIdealcheckoutCurlVerificationError;

		if(!isset($bIdealcheckoutCurlVerificationError))
		{
			$bIdealcheckoutCurlVerificationError = false;
		}

		$aUrl = parse_url($sUrl);

		$bHttps = false;
		$sRequestUrl = '';

		if(in_array($aUrl['scheme'], array('ssl', 'https')))
		{
			$sRequestUrl .= 'https://';
			$bHttps = true;

			if(empty($aUrl['port']))
			{
				$aUrl['port'] = 443;
			}
		}
		else
		{
			$sRequestUrl .= 'http://';

			if(empty($aUrl['port']))
			{
				$aUrl['port'] = 80;
			}
		}

		$sRequestUrl .= $aUrl['host'] . (empty($aUrl['path']) ? '/' : $aUrl['path']) . (empty($aUrl['query']) ? '' : '?' . $aUrl['query']);

		if(is_array($sPostData))
		{
			$sPostData = str_replace(array('%5B', '%5D'), array('[', ']'), http_build_query($sPostData));
		}


		if($bDebug === true)
		{
			$sRequest  = 'Requested URL: ' . $sRequestUrl . "\r\n";
			$sRequest .= 'Portnumber: ' . $aUrl['port'] . "\r\n";

			if($sPostData)
			{
				$sRequest .= 'Posted data: ' . $sPostData . "\r\n";
			}

			echo "\r\n" . "\r\n" . '<h1>SEND DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sRequest)) . '</code>' . "\r\n" . "\r\n";
		}


		$oCurl = curl_init();
		$oCertInfo = false;

		if($bHttps && idealcheckout_getDebugMode())
		{
			$oCertInfo = tmpfile();

			$sHostName = ($bHttps ? 'https://' : 'http://') . $aUrl['host'] . (empty($aUrl['port']) ? '' : ':' . $aUrl['port']);
			idealcheckout_getUrlCertificate($sHostName);
		}

		curl_setopt($oCurl, CURLOPT_URL, $sRequestUrl);
		curl_setopt($oCurl, CURLOPT_PORT, $aUrl['port']);

		if($bHttps && ($bIdealcheckoutCurlVerificationError == false))
		{
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);

			if($oCertInfo)
			{
				curl_setopt($oCurl, CURLOPT_STDERR, $oCertInfo);
				curl_setopt($oCurl, CURLOPT_VERBOSE, true);
				curl_setopt($oCurl, CURLOPT_CERTINFO, true);
			}
		}

		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);

		// Adjustment htaccess 301 redirect
		curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($oCurl, CURLOPT_MAXREDIRS, 10);


		curl_setopt($oCurl, CURLOPT_TIMEOUT, $iTimeout);
		curl_setopt($oCurl, CURLOPT_HEADER, $bRemoveHeaders == false);


		if(substr($sPostData, 0, 1) == '{') // JSON string
		{
			if(!is_array($aAdditionalHeaders))
			{
				$aAdditionalHeaders = array();
			}

			$aAdditionalHeaders[] = 'Content-Type: application/json';
		}


		if(is_array($aAdditionalHeaders) && sizeof($aAdditionalHeaders))
		{
			curl_setopt($oCurl, CURLOPT_HTTPHEADER, $aAdditionalHeaders);
		}


		if($sPostData != false)
		{
			curl_setopt($oCurl, CURLOPT_POST, true);
			curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sPostData);
		}

		$sResponse = curl_exec($oCurl);


		// Capture certificate info
		if($bHttps && $oCertInfo)
		{
			fseek($oCertInfo, 0);

			$sCertInfo = '';

			while($s = fread($oCertInfo, 8192))
			{
				$sCertInfo .= $s;
			}

			fclose($oCertInfo);

			idealcheckout_log('cURL Retrieved SSL Certificate:' . "\r\n" . $sCertInfo, __FILE__, __LINE__);
		}

		if(idealcheckout_getDebugMode())
		{
			if(curl_errno($oCurl) && (strpos(curl_error($oCurl), 'self signed certificate') !== false))
			{
				idealcheckout_log('cURL error #' . curl_errno($oCurl) . ': ' . curl_error($oCurl), __FILE__, __LINE__);
				idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
				$bIdealcheckoutCurlVerificationError = true;

				curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($oCurl, CURLOPT_VERBOSE, false);
				curl_setopt($oCurl, CURLOPT_CERTINFO, false);

				// cURL Retry
				$sResponse = curl_exec($oCurl);
			}

			if(curl_errno($oCurl) == CURLE_SSL_CACERT)
			{
				idealcheckout_log('cURL error #' . curl_errno($oCurl) . ': ' . curl_error($oCurl), __FILE__, __LINE__);
				idealcheckout_log('ca-bundle.crt not installed?!', __FILE__, __LINE__);
				idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);

				$sBundlePath = dirname(__DIR__) . '/certificates/ca-bundle.crt';

				if(is_file($sBundlePath))
				{
					curl_setopt($oCurl, CURLOPT_CAINFO, $sBundlePath);

					// cURL Retry
					$sResponse = curl_exec($oCurl);
				}
			}

			if((curl_errno($oCurl) == CURLE_SSL_PEER_CERTIFICATE) || (curl_errno($oCurl) == 77))
			{
				idealcheckout_log('cURL error: ' . curl_error($oCurl), __FILE__, __LINE__);
				idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
				curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);

				// cURL Retry
				$sResponse = curl_exec($oCurl);
			}

			if(curl_errno($oCurl) && (strpos(curl_error($oCurl), 'error setting certificate verify locations') !== false))
			{
				idealcheckout_log('cURL error: ' . curl_error($oCurl), __FILE__, __LINE__);
				idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
				curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);

				// cURL Retry
				$sResponse = curl_exec($oCurl);
			}

			if(curl_errno($oCurl) && (strpos(curl_error($oCurl), 'certificate subject name ') !== false) && (strpos(curl_error($oCurl), ' does not match target host') !== false))
			{
				idealcheckout_log('cURL error: ' . curl_error($oCurl), __FILE__, __LINE__);
				idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
				curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);

				// cURL Retry
				$sResponse = curl_exec($oCurl);
			}
		}

		if(curl_errno($oCurl))
		{
			idealcheckout_log('cURL cannot rely on SSL verification. All SSL verification is disabled from this point.', __FILE__, __LINE__);
			idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
			$bIdealcheckoutCurlVerificationError = true;

			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_VERBOSE, false);
			curl_setopt($oCurl, CURLOPT_CERTINFO, false);

			// cURL Retry
			$sResponse = curl_exec($oCurl);
		}

		if(curl_errno($oCurl))
		{
			// cURL Failed
			idealcheckout_log('cURL error: ' . curl_error($oCurl), __FILE__, __LINE__);
			idealcheckout_log(curl_getinfo($oCurl), __FILE__, __LINE__);
			idealcheckout_die('Error while calling url: ' . $sRequestUrl, __FILE__, __LINE__);
		}

		curl_close($oCurl);


		if($bDebug === true)
		{
			echo "\r\n" . "\r\n" . '<h1>RECIEVED DATA:</h1>' . "\r\n" . '<code style="display: block; background: #E0E0E0; border: #000000 solid 1px; padding: 10px;">' . str_replace(array("\n", "\r"), array('<br>' . "\r\n", ''), htmlspecialchars($sResponse)) . '</code>' . "\r\n" . "\r\n";
		}


		if(empty($sResponse))
		{
			return '';
		}

		return $sResponse;
	}


	// Curl verifcation error has occured
	function idealcheckout_getCurlVerificationError()
	{
		global $bIdealcheckoutCurlVerificationError;

		if(isset($bIdealcheckoutCurlVerificationError))
		{
			return $bIdealcheckoutCurlVerificationError;
		}

		return false;
	}


	// Curl verifcation error has occured
	function idealcheckout_getUrlCertificate($sUrl, $bDebug = false)
	{
		if($bDebug || idealcheckout_getDebugMode())
		{
			if(version_compare(PHP_VERSION, '5.3.0') < 0)
			{
				idealcheckout_log('PHP version is to low for retrieving certificates.', __FILE__, __LINE__);
			}
			else
			{
				if($oStream = @stream_context_create(array('ssl' => array('capture_peer_cert' => true))))
				{
					idealcheckout_log('Fetching peer certificate for: ' . $sUrl, __FILE__, __LINE__);

					if($oHandle = @fopen($sUrl, 'rb', false, $oStream))
					{
						if(function_exists('stream_context_get_params'))
						{
							$aParams = stream_context_get_params($oHandle);

							if(isset($aParams['options'], $aParams['options']['ssl'], $aParams['options']['ssl']['peer_certificate']))
							{
								$oPeerCertificate = $aParams['options']['ssl']['peer_certificate'];

								$sTempPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'peers' . DIRECTORY_SEPARATOR;

								// Save certificate
								if(@openssl_x509_export_to_file($oPeerCertificate, $sTempPath . 'peer.' . microtime(true) . '.crt'))
								{
									return true;
								}
							}
							else
							{
								return false;
							}
						}
						else
						{
							idealcheckout_log('Stream function does not exist on this PHP version.', __FILE__, __LINE__);
						}
					}

					idealcheckout_log('Peer certificate capture failed for: ' . $sUrl, __FILE__, __LINE__);
				}
			}
		}

		return false;
	}


	// Print html to screen
	function idealcheckout_output($sHtml, $bImage = true)
	{
		global $aIdealCheckout;

		// Detect idealcheckout folder
		$sRootUrl = idealcheckout_getRootUrl();

		if(($iStrPos = strpos($sRootUrl, '/idealcheckout/')) !== false)
		{
			$sRootUrl = substr($sRootUrl, 0, $iStrPos) . '/';
		}

		// Detect gateway name & image
		$sTitle = 'Checkout';
		$sImage = 'gateway.png';
		$sColor = '#999999';

		if($bImage && !empty($aIdealCheckout['record']['gateway_code']))
		{
			if(strcasecmp($aIdealCheckout['record']['gateway_code'], 'afterpay') === 0)
			{
				$sTitle = 'AfterPay';
				$sImage = 'afterpay.png';
				$sColor = '#759D41';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'authorizedtransfer') === 0)
			{
				$sTitle = 'Eenmalige machtiging / Incasso';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'creditcard') === 0)
			{
				$sTitle = 'CreditCard Checkout';
				$sImage = 'creditcard.png';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'sofortbanking') === 0)
			{
				$sTitle = 'Sofort Checkout';
				$sImage = 'sofortbanking.png';
				$sColor = '#F18E00';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'ideal') === 0)
			{
				$sTitle = 'iDEAL Checkout';
				$sImage = 'ideal.png';
				$sColor = '#CC0066';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'maestro') === 0)
			{
				$sTitle = 'Maestro Checkout';
				$sImage = 'maestro.png';
				$sColor = '#CC0000';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'klarnaaccount') === 0)
			{
				$sTitle = 'Klarna Checkout';
				$sImage = 'klarna.png';
				$sColor = '#137FC3';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'klarnainvoice') === 0)
			{
				$sTitle = 'Klarna Checkout';
				$sImage = 'klarna.png';
				$sColor = '#137FC3';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'klarnabuynow') === 0)
			{
				$sTitle = 'Klarna Buy Now Checkout';
				$sImage = 'klarnabuynow.png';
				$sColor = '#137FC3';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'mastercard') === 0)
			{
				$sTitle = 'Mastercard Checkout';
				$sImage = 'mastercard.png';
				$sColor = '#FFAA18';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'mistercash') === 0)
			{
				$sTitle = 'MisterCash Checkout';
				$sImage = 'mistercash.png';
				$sColor = '#0083C6';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'bancontact') === 0)
			{
				$sTitle = 'Bancontact Checkout';
				$sImage = 'bancontact.png';
				$sColor = '#0083C6';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'manualtransfer') === 0)
			{
				$sTitle = 'Overboeking';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'paypal') === 0)
			{
				$sTitle = 'PayPal Checkout';
				$sImage = 'paypal.png';
				$sColor = '#0E569F';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'payconiq') === 0)
			{
				$sTitle = 'Payconiq Checkout';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'paysafecard') === 0)
			{
				$sTitle = 'PaySafeCard Checkout';
				$sImage = 'paysafecard.png';
				$sColor = '#008ACA';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'visa') === 0)
			{
				$sTitle = 'Visa Checkout';
				$sImage = 'visa.png';
				$sColor = '#1C1E75';
			}
			elseif(strcasecmp($aIdealCheckout['record']['gateway_code'], 'vpay') === 0)
			{
				$sTitle = 'V PAY Checkout';
				$sImage = 'vpay.png';
				$sColor = '#0023A1';
			}
		}


		$sOutput = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>' . $sTitle . '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15">
		<style type="text/css">

html, body, form, div
{
	margin: 0px;
	padding: 0px;
}

div.wrapper
{
	padding: 50px 0px 0px 0px;
	text-align: center;
}

div.error
{
	margin: 10px 0px 10px 0px;
	padding: 8px 8px 8px 8px;
	text-align: center;

	font-family: Arial;
	font-size: 12px;
	background-color: #FFE0E0;
	border: #FF0000 dashed 1px;
}

p
{
	font-family: Arial;
	font-size: 15px;
}

a
{
	color: ' . $sColor . ' !important;
}

td
{
	font-family: Arial;
	font-size: 12px;
}

		</style>

	</head>
	<body>

		<!--

			This ' . $sTitle . ' script is developed by:

			iDEAL Checkout

			Support & Information:
			W. http://www.ideal-checkout.nl
			E. info@ideal-checkout.nl
			T. 0522 - 746 060

		-->
';

		$sInstallFolder = dirname(__DIR__) . '/install';

		if(is_dir($sInstallFolder))
		{
			$sOutput .= '
		<div class="error">De map /idealcheckout/install is nog niet verwijderd van uw webserver. Verwijder deze map met een FTP programma voor dat uw website live gaat!</div>';
		}

		$sOutput .= '
		<div class="wrapper">
			' . ($bImage ? '<p><img alt="' . $sTitle . '" border="0" src="' . $sRootUrl . 'idealcheckout/images/' . $sImage . '"></p>' : '') . '

' . $sHtml . '

		</div>

	</body>
</html>';

		echo $sOutput;
		exit;
	}


	// Translate text using language files
	function idealcheckout_getTranslation($sLanguageCode = false, $sGroup, $sKey, $aParams = array())
	{
		global $aIdealCheckout;

		if(empty($sLanguageCode))
		{
			if(!empty($aIdealCheckout['record']['language']))
			{
				$sLanguageCode = strtolower($aIdealCheckout['record']['language']);
			}
			elseif(!empty($aIdealCheckout['language']))
			{
				$sLanguageCode = strtolower($aIdealCheckout['language']);
			}
			else
			{
				$sLanguageCode = 'en';
			}
		}

		if(!isset($aIdealCheckout['translations'][$sLanguageCode][$sGroup]))
		{
			$sTranslationFile = dirname(__DIR__) . '/translations/' . $sGroup . '.' . $sLanguageCode . '.php';

			if(file_exists($sTranslationFile))
			{
				$aIdealCheckout['translations'][$sLanguageCode][$sGroup] = include_once($sTranslationFile);
			}
		}

		if(isset($aIdealCheckout['translations'][$sLanguageCode][$sGroup][$sKey]))
		{
			$sText = $aIdealCheckout['translations'][$sLanguageCode][$sGroup][$sKey];
		}
		else
		{
			$sText = $sKey;
		}

		if(is_array($aParams) && sizeof($aParams))
		{
			foreach($aParams as $k => $v)
			{
				$sText = str_replace('{' . $k . '}', $v, $sText);
			}
		}

		return $sText;
	}


	// Load database settings
	function idealcheckout_getDatabaseSettings($sStoreCode = false)
	{
		global $aIdealCheckout;

		if(empty($sStoreCode))
		{
			if(!empty($aIdealCheckout['record']['store_code']))
			{
				$sStoreCode = $aIdealCheckout['record']['store_code'];
			}
			else
			{
				$sStoreCode = idealcheckout_getStoreCode();
			}
		}

		$sDatabaseFile1 = dirname(__DIR__) . '/configuration/database.' . strtolower($sStoreCode) . '.php';
		$sDatabaseFile2 = dirname(__DIR__) . '/configuration/database.php';
		$sDatabaseError = 'No configuration file available for database.';

		$aSettings = array();

		// Database Server/Host
		$aSettings['host'] = 'localhost';

		// Database Type
		$aSettings['type'] = 'mysqli';

		// Database Username
		$aSettings['user'] = '';

		// Database Password
		$aSettings['pass'] = '';

		// Database Name
		$aSettings['name'] = '';

		// Database Table Prefix (if any)
		$aSettings['prefix'] = '';

		// iDEAL Checkout Table
		$aSettings['table'] = '';

		if(idealcheckout_isLocalFile($sDatabaseFile1))
		{
			include($sDatabaseFile1);
		}
		elseif(idealcheckout_isLocalFile($sDatabaseFile2))
		{
			include($sDatabaseFile2);
		}
		else
		{
			idealcheckout_die('ERROR: ' . $sDatabaseError . ', FILE #1: ' . $sDatabaseFile1 . ', FILE #2: ' . $sDatabaseFile2, __FILE__, __LINE__, false);
		}



		// iDEAL Checkout Table
		if(empty($aSettings['table']))
		{
			$aSettings['table'] = $aSettings['prefix'] . 'idealcheckout_transactions';
		}

		return $aSettings;
	}


	// Load database settings
	function idealcheckout_getWebsiteSettings($sStoreCode = false)
	{
		global $aIdealCheckout;

		if(empty($sStoreCode))
		{
			if(!empty($aIdealCheckout['record']['store_code']))
			{
				$sStoreCode = $aIdealCheckout['record']['store_code'];
			}
			else
			{
				$sStoreCode = idealcheckout_getStoreCode();
			}
		}

		$sWebsiteFile1 = dirname(__DIR__) . '/configuration/website.' . strtolower($sStoreCode) . '.php';
		$sWebsiteFile2 = dirname(__DIR__) . '/configuration/website.php';
		$sWebsiteError = 'No configuration file available for website.';

		$aSettings = array();

		if(idealcheckout_isLocalFile($sWebsiteFile1))
		{
			include($sWebsiteFile1);
		}
		elseif(idealcheckout_isLocalFile($sWebsiteFile2))
		{
			include($sWebsiteFile2);
		}
		else
		{
			// idealcheckout_die('ERROR: ' . $sWebsiteError, __FILE__, __LINE__, false);
		}

		return $aSettings;
	}

	// Load server settings
	function idealcheckout_getServerSettings($sStoreCode = false)
	{
		global $aIdealCheckout;

		if(empty($sStoreCode))
		{
			if(!empty($aIdealCheckout['record']['store_code']))
			{
				$sStoreCode = $aIdealCheckout['record']['store_code'];
			}
			else
			{
				$sStoreCode = idealcheckout_getStoreCode();
			}
		}

		$sServerFile1 = dirname(__DIR__) . '/configuration/server.' . strtolower($sStoreCode) . '.php';
		$sServerFile2 = dirname(__DIR__) . '/configuration/server.php';
		$sServerError = 'No server configuration file available for this website.';

		$aSettings = array();

		if(idealcheckout_isLocalFile($sServerFile1))
		{
			include($sServerFile1);
		}
		elseif(idealcheckout_isLocalFile($sServerFile2))
		{
			include($sServerFile2);
		}
		else
		{
			// idealcheckout_die('ERROR: ' . $sWebsiteError, __FILE__, __LINE__, false);
		}

		return $aSettings;
	}

	// Load gateway settings
	function idealcheckout_getGatewaySettings($sStoreCode = false, $sGatewayCode = false)
	{
		global $aIdealCheckout;

		if(empty($sStoreCode))
		{
			if(!empty($aIdealCheckout['record']['store_code']))
			{
				$sStoreCode = $aIdealCheckout['record']['store_code'];
			}
			else
			{
				$sStoreCode = idealcheckout_getStoreCode();
			}
		}

		if(empty($sGatewayCode))
		{
			if(!empty($aIdealCheckout['record']['gateway_code']))
			{
				$sGatewayCode = $aIdealCheckout['record']['gateway_code'];
			}
			else
			{
				$sGatewayCode = 'ideal';
			}
		}

		if(!preg_match('/^([a-zA-Z0-9_\-]+)$/', $sGatewayCode))
		{
			idealcheckout_die('INVALID GATEWAY: ' . $sGatewayCode, __FILE__, __LINE__, false);
		}
		elseif(!preg_match('/^([a-zA-Z0-9_\-]+)$/', $sStoreCode))
		{
			idealcheckout_die('INVALID STORE CODE: ' . $sStoreCode, __FILE__, __LINE__, false);
		}

		$sConfigurationPath = dirname(__DIR__) . '/configuration/';
		$sConfigFile1 = $sConfigurationPath . strtolower($sGatewayCode) . '.' . strtolower($sStoreCode) . '.php';
		$sConfigFile2 = $sConfigurationPath . strtolower($sGatewayCode) . '.php';
		$sConfigError = 'No configuration file available for ' . $sGatewayCode . '.';

		$aSettings = array();

		if(idealcheckout_isLocalFile($sConfigFile1))
		{
			include($sConfigFile1);
		}
		elseif(idealcheckout_isLocalFile($sConfigFile2))
		{
			include($sConfigFile2);
		}
		else
		{
			idealcheckout_die('ERROR: ' . $sConfigError, __FILE__, __LINE__, false);
		}

		if(empty($aSettings['TEST_MODE']))
		{
			$aSettings['TEST_MODE'] = false;
		}

		// Fix temp path
		if(empty($aSettings['TEMP_PATH']))
		{
			$aSettings['TEMP_PATH'] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
		}

		if(empty($aSettings['CACHE_PATH']))
		{
			$aSettings['CACHE_PATH'] = $aSettings['TEMP_PATH'] . 'cache' . DIRECTORY_SEPARATOR;
		}


		// Fix certificate path
		if(empty($aSettings['CERTIFICATE_PATH']))
		{
			$aSettings['CERTIFICATE_PATH'] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR;
		}


		// Fix gateway path
		if(!empty($aSettings['GATEWAY_METHOD']))
		{
			$aSettings['GATEWAY_FILE'] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'gateways' . DIRECTORY_SEPARATOR . $aSettings['GATEWAY_METHOD'] . DIRECTORY_SEPARATOR . 'gateway.cls.php';
		}
		elseif(strcasecmp(substr($aSettings['GATEWAY_FILE'], 0, 10), DIRECTORY_SEPARATOR . 'gateways' . DIRECTORY_SEPARATOR) === 0)
		{
			$aSettings['GATEWAY_FILE'] = dirname(__DIR__) . $aSettings['GATEWAY_FILE'];
		}
		elseif(strcasecmp(substr($aSettings['GATEWAY_FILE'], 0, 9), 'gateways' . DIRECTORY_SEPARATOR) === 0)
		{
			$aSettings['GATEWAY_FILE'] = dirname(__DIR__) . DIRECTORY_SEPARATOR . $aSettings['GATEWAY_FILE'];
		}

		return $aSettings;
	}

	function idealcheckout_die($sError, $sFile = false, $iLine = false, $sGatewayCode = 'ideal')
	{
		if(idealcheckout_log($sError, $sFile, $iLine, false))
		{
			idealcheckout_output('<p>A fatal error has occured. Please check your log files.</p>', false);
		}
		else
		{
			if(!is_string($sError))
			{
				$sErrorHtml = '<p>' . var_dump($sError, true) . '</p><hr size="1"><p>FILE: ' . $sFile . '<br>LINE: ' . $iLine . '</p>';
			}
			else
			{
				$sErrorHtml = '<p>' . htmlentities($sError) . '</p><hr size="1"><p>FILE: ' . $sFile . '<br>LINE: ' . $iLine . '</p>';
			}

			idealcheckout_output($sErrorHtml, false);
		}
	}

	function idealcheckout_log($sText, $sFile = false, $iLine = false, $bDebugCheck = true)
	{
		if(!$bDebugCheck || idealcheckout_getDebugMode())
		{
			if(is_array($sText) || is_object($sText))
			{
				$sText = var_export($sText, true);
			}

			// Reformat text
			$sText = str_replace("\n", "\n      ", trim($sText));

			$sLog = "\n" . 'TEXT: ' . $sText . "\n";

			if($sFile !== false)
			{
				$sLog .= 'FILE: ' . $sFile . "\n";
			}

			if($sFile !== false)
			{
				$sLog .= 'LINE: ' . $iLine . "\n";
			}

			$sLog .= "\n";


			$sLogFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . date('Ymd.His') . '.log';

			if(@file_put_contents($sLogFile, $sLog, FILE_APPEND))
			{
				chmod($sLogFile, 0777);
				return true;
			}
		}

		return false;
	}


	function idealcheckout_mb_splitAddress($sAddress)
	{
		// Get everything up to the first number with a regex
		$bHasMatch = preg_match('/^[^0-9]*/', $sAddress, $aMatch);

		// If no matching is possible, return the supplied string as the street
		if(!$bHasMatch)
		{
			return array($sAddress, '', '');
		}

		// Remove the street from the sAddress.
		$sAddress = str_replace($aMatch[0], '', $sAddress);
		$sStreetname = trim($aMatch[0]);

		// Nothing left to split, return the streetname alone
		if(strlen($sAddress == 0))
		{
			return array($sStreetname, '', '');
		}

		// Explode sAddress to an array using a multiple explode function
		$aAddress = idealcheckout_mb_multiExplodeArray(array(' ', '-', '|', '&', '/', '_', '\\'), $sAddress);

		// Shift the first element off the array, that is the house number
		$iHousenumber = array_shift($aAddress);

		// If the array is empty now, there is no extension.
		if(count($aAddress) == 0)
		{
			return array($sStreetname, $iHousenumber, '');
		}

		// Join together the remaining pieces as the extension.
		$sExtension = substr(implode(' ', $aAddress), 0, 4);

		return array($sStreetname, $iHousenumber, $sExtension);
	}

	function idealcheckout_mb_multiExplodeArray($aDelimiter, $sString)
	{
		$sInput = str_replace($aDelimiter, $aDelimiter[0], $sString);
		$aArray = explode($aDelimiter[0], $sInput);

		return $aArray;
	}



	// Streetname 1a => array('Streetname', '1a')
	function idealcheckout_splitAddress($sAddress)
	{
		$sAddress = trim($sAddress);

		$a = preg_split('/([0-9]+)/', $sAddress, 2, PREG_SPLIT_DELIM_CAPTURE);
		$sStreetName = trim(array_shift($a));
		$sStreetNumber = trim(implode('', $a));

		if(empty($sStreetName)) // American address notation
		{
			$a = preg_split('/([a-zA-Z]{2,})/', $sAddress, 2, PREG_SPLIT_DELIM_CAPTURE);

			$sStreetNumber = trim(implode('', $a));
			$sStreetName = trim(array_shift($a));
		}

		return array($sStreetName, $sStreetNumber);
	}

	function idealcheckout_database_setup($oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if(empty($aIdealCheckout['database']['connection']))
		{
			// Find database configuration
			$aIdealCheckout['database'] = idealcheckout_getDatabaseSettings();

			// Connect to database
			$aIdealCheckout['database']['connection'] = idealcheckout_database_connect($aIdealCheckout['database']['host'], $aIdealCheckout['database']['user'], $aIdealCheckout['database']['pass']) or idealcheckout_die('ERROR: Cannot connect to ' . $aIdealCheckout['database']['type'] . ' server. Error in hostname, username and/or password.', __FILE__, __LINE__, false);
			idealcheckout_database_select_db($aIdealCheckout['database']['connection'], $aIdealCheckout['database']['name']) or idealcheckout_die('ERROR: Cannot find database `' . $aIdealCheckout['database']['name'] . '` on ' . $aIdealCheckout['database']['host'] . '.', __FILE__, __LINE__, false);
		}

		return $aIdealCheckout['database']['connection'];
	}


	function idealcheckout_database_query($sQuery, $oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_query($oDatabaseConnection, $sQuery);
		}
		else
		{
			return mysql_query($sQuery, $oDatabaseConnection);
		}
	}


	function idealcheckout_database_isRecord($sQuery, $oDatabaseConnection = false)
	{
		$aRecords = idealcheckout_database_getRecords($sQuery, $oDatabaseConnection);

		if(sizeof($aRecords) > 0)
		{
			return true;
		}

		return false;
	}


	function idealcheckout_database_getRecord($sQuery, $oDatabaseConnection = false)
	{
		$aRecords = idealcheckout_database_getRecords($sQuery, $oDatabaseConnection);

		if(sizeof($aRecords) > 0)
		{
			return $aRecords[0];
		}

		return false;
	}


	function idealcheckout_database_getRecords($sQuery, $oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		$aRecords = array();

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			if($oRecordset = mysqli_query($oDatabaseConnection, $sQuery))
			{
				while($aRecord = mysqli_fetch_assoc($oRecordset))
				{
					$aRecords[] = $aRecord;
				}

				mysqli_free_result($oRecordset);
			}
		}
		else
		{
			if($oRecordset = mysql_query($sQuery, $oDatabaseConnection))
			{
				while($aRecord = mysql_fetch_assoc($oRecordset))
				{
					$aRecords[] = $aRecord;
				}

				mysql_free_result($oRecordset);
			}
		}

		return $aRecords;
	}

	function idealcheckout_database_getValue($sQuery)
	{
		$sColumn = false;


		$rs = idealcheckout_database_getRecord($sQuery);

		if(is_array($rs) && sizeof($rs))
		{
			if($sColumn === false) // Detect first column name
			{
				foreach($rs as $k => $v)
				{
					$sColumn = $k;
					break;
				}
			}

			return $rs[$sColumn];
		}

		return false;

	}


	function idealcheckout_database_execute($sQuery, $oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_query($oDatabaseConnection, $sQuery);
		}
		else
		{
			return mysql_query($sQuery, $oDatabaseConnection);
		}
	}


	function idealcheckout_database_error($oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return @mysqli_error($oDatabaseConnection);
		}
		else
		{
			return @mysql_error($oDatabaseConnection);
		}
	}


	function idealcheckout_database_fetch_assoc($oRecordSet)
	{
		global $aIdealCheckout;

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_fetch_assoc($oRecordSet);
		}
		else
		{
			return mysql_fetch_assoc($oRecordSet);
		}
	}


	function idealcheckout_database_connect($oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_connect($aIdealCheckout['database']['host'], $aIdealCheckout['database']['user'], $aIdealCheckout['database']['pass']);
		}
		else
		{
			return mysql_connect($aIdealCheckout['database']['host'], $aIdealCheckout['database']['user'], $aIdealCheckout['database']['pass']);
		}
	}


	function idealcheckout_database_select_db($oDatabaseConnection = false, $sDatabaseName = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_select_db($oDatabaseConnection, $sDatabaseName);
		}
		else
		{
			return mysql_select_db($sDatabaseName, $oDatabaseConnection);
		}
	}


	function idealcheckout_database_num_rows($oRecordSet)
	{
		global $aIdealCheckout;

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_num_rows($oRecordSet);
		}
		else
		{
			return mysql_num_rows($oRecordSet);
		}
	}


	function idealcheckout_database_insert_id($oDatabaseConnection = false)
	{
		global $aIdealCheckout;

		if($oDatabaseConnection === false)
		{
			$oDatabaseConnection = idealcheckout_database_setup();
		}

		if(!empty($aIdealCheckout['database']['type']) && (strcmp($aIdealCheckout['database']['type'], 'mysqli') === 0))
		{
			return mysqli_insert_id($oDatabaseConnection);
		}
		else
		{
			return mysql_insert_id($oDatabaseConnection);
		}
	}

	function idealcheckout_getPaymentButton($aParams, $sSubmitButton = 'Afrekenen', $sFormUrl = 'idealcheckout/checkout.php')
	{
		$sHtml = '<form action="' . htmlspecialchars($sFormUrl) . '" method="post">';

		foreach($aParams as $k => $v)
		{
			$sHtml .= '<input name="' . htmlspecialchars($k) . '" type="hidden" value="' . htmlspecialchars($v) . '">';
		}

		if(strpos($sSubmitButton, '://') !== false)
		{
			$sHtml .= '<input type="image" src="' . htmlspecialchars($sSubmitButton) . '">';
		}
		elseif(strpos($sSubmitButton, '<input') !== false)
		{
			$sHtml .= $sSubmitButton;
		}
		else
		{
			$sHtml .= '<input type="submit" value="' . htmlspecialchars($sSubmitButton) . '">';
		}

		$sHtml .= '</form>';


		return $sHtml;
	}

	function idealcheckout_php_execute($_____CODE, $_____PARAMS = array())
	{
		foreach($_____PARAMS as $k => $v)
		{
			${$k} = $v;
		}

		$_____CODE = trim($_____CODE);

		if(strcasecmp(substr($_____CODE, 0, 5), '<' . '?' . 'php') === 0)
		{
			$_____CODE = substr($_____CODE, 5);
		}
		elseif(strcasecmp(substr($_____CODE, 0, 2), '<' . '?') === 0)
		{
			$_____CODE = substr($_____CODE, 2);
		}

		if(strcasecmp(substr($_____CODE, -2, 2), '?' . '>') === 0)
		{
			$_____CODE = substr($_____CODE, 0, -2);
		}

		$_____CODE = trim($_____CODE);

		eval($_____CODE);
	}

	function idealcheckout_sendMail($aRecord)
	{
		$aGatewaySettings = idealcheckout_getGatewaySettings($aRecord['store_code'], $aRecord['gateway_code']);
		$sWebsiteUrl = idealcheckout_getRootUrl(1);

		if(!empty($aGatewaySettings['TRANSACTION_UPDATE_EMAILS']))
		{
			if(strpos($aGatewaySettings['TRANSACTION_UPDATE_EMAILS'], ',') !== false)
			{
				$aEmails = explode(',', $aGatewaySettings['TRANSACTION_UPDATE_EMAILS']);
			}
			elseif(strpos($aGatewaySettings['TRANSACTION_UPDATE_EMAILS'], ';') !== false)
			{
				$aEmails = explode(';', $aGatewaySettings['TRANSACTION_UPDATE_EMAILS']);
			}
			else
			{
				$aEmails = array($aGatewaySettings['TRANSACTION_UPDATE_EMAILS']);
			}

			foreach($aEmails as $k => $sEmail)
			{
				$sMailTo = trim($sEmail);

				if(preg_match('/^([a-z0-9\-_\.]+)@([a-z0-9\-_\.]+)\.[a-z]{2,6}$/i', $sMailTo)) // Validate e-mail address
				{
					$sMailSubject = 'Transaction Update: ' . $aRecord['transaction_description'];
					$sMailHeaders = 'From: "' . $sWebsiteUrl . '" <' . $sMailTo . '>';
					$sMailMessage = 'TRANSACTION UPDATE

Order:         ' . $aRecord['order_id'] . '
Bedrag:        ' . $aRecord['transaction_amount'] . '
Omschrijving:  ' . $aRecord['transaction_description'] . '

Transactie:    ' . $aRecord['transaction_id'] . '
Status:        ' . $aRecord['transaction_status'] . '

Controleer de definitieve status van transacties ALTIJD via uw Dashboard of bankafschrift.




Deze e-mail is gegenereerd door ' . $sWebsiteUrl . ' op ' . date('d-m-Y, H:i') . '.
';

					if(@mail($sMailTo, $sMailSubject, $sMailMessage, $sMailHeaders))
					{
						idealcheckout_log('Transaction update send to: ' . $sMailTo, __FILE__, __LINE__);
					}
					else
					{
						idealcheckout_log('Error while sending e-mail to: ' . $sMailTo, __FILE__, __LINE__);
					}
				}
				else
				{
					idealcheckout_log('Invalid e-mail address: ' . $sMailTo, __FILE__, __LINE__);
				}
			}
		}
	}

	function idealcheckout_arrayToText($aArray, $iWhiteSpace = 0)
	{
		$sData = '';

		if(is_array($aArray) && sizeof($aArray))
		{
			foreach($aArray as $k1 => $v1)
			{
				if(strlen($sData))
				{
					$sData .= "\n";
				}

				$sData .= str_repeat(' ', $iWhiteSpace) . $k1 . ': ';

				if(is_object($v1))
				{
					$sData .= '[' . get_class($v1) . ' object], ';
				}
				elseif(is_array($v1))
				{
					$sData .= "\n" . idealcheckout_arrayToText($v1, $iWhiteSpace + strlen($k1) + 2) . ', ';
				}
				elseif($v1 === true)
				{
					$sData .= 'TRUE, ';
				}
				elseif($v1 === false)
				{
					$sData .= 'FALSE, ';
				}
				elseif($v1 === null)
				{
					$sData .= 'NULL, ';
				}
				else
				{
					$sData .= $v1 . ', ';
				}
			}

			$sData = substr($sData, 0, -2); // Remove last comma-space
		}

		return $sData;
	}


?>