<?php
	header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$email = $_POST['email'];


	$query = "SELECT COUNT(id_usuario) AS count FROM usuarios  WHERE email=? LIMIT 1";

	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('s', $email);

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