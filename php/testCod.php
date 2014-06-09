<?php
	header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$cod = $_POST['cod'];


	$query = "SELECT COUNT(cod) AS count FROM clases WHERE cod=? LIMIT 1";

	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('s', $cod);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_count);
	    $stmt->fetch();

	    if ($res_count > 0) {
	    	echo json_encode(true);
	    } else {
	    	echo json_encode(false);
	    }


	    $stmt->close();
	} else {
		echo json_encode(false);;
	}

	$mysqli->close();
?>