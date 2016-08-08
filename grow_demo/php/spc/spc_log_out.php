<?php

	$_SESSION['loggedIn'] = false;
	session_destroy(); 
	header( "Location: $lnk_splash" );
	exit;

?>