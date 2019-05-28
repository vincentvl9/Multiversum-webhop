<?php
	
	session_start();
	session_destroy();

	if($_GET["resetpassword"])
	{
		header( "Location: forgotpassword.php?resetpassword=" . $_GET["resetpassword"] );
	}else{
		header( "Location: index.php" );
	}
	
?>