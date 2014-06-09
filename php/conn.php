<?php
	$mysqli = new mysqli('127.0.0.1', 'vygoowbi_asdel', 'asdelday27', 'vygoowbi_appasionate');

	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	$mysqli->query("SET NAMES 'utf8'");
?>
