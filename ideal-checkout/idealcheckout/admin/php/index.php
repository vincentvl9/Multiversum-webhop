<?php


	define('LF', chr(10)); // Line feed '\n'

	$aFiles = array();

	// General PHP libraries
	$aFiles[] = 'csv.cls.php';
	$aFiles[] = 'listbuilder.cls.php';
	$aFiles[] = 'graph.cls.php';
	$aFiles[] = 'idealstatus.cls.php';
	$aFiles[] = 'int.cls.php';
	$aFiles[] = 'profile.cls.php';
	$aFiles[] = 'mail.cls.php';
	
	foreach($aFiles as $sFile)
	{
		require_once($sFile);
	}


?>