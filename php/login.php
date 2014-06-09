<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$email=$_POST["email"];
	$pass=$_POST['pass'];
	$passCod = md5(md5($pass));

	//$email = 'rodortcue@gmail.com';
	//$passCod = '14e1b600b1fd579f47433b88e8d85291';
	//
/*
if (extension_loaded('mysqlnd')) {
echo 'extension mysqlnd is loaded';
} else {
echo 'extension mysqlnd is NOT loaded';
}*/

	$query = "SELECT id_usuario, email, password,roles.rol, nombre, apellidos
				FROM usuarios
				INNER JOIN roles ON usuarios.rol = roles.id_rol
				WHERE email=? AND password=?";

	/* Ejecuto el método prepare y este me va a devolver el objeto */
	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('ss', $email, $passCod);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_id, $res_email, $res_password, $res_rol, $res_nombre, $res_apellidos);

	    $data = array();
	    $json = array();
		while($stmt->fetch()){
			$data['id_usuario'] = $res_id;
			$data['email'] = $res_email;
			$data['password'] = $res_password;
			$data['rol'] = $res_rol;
			$data['nombre'] = $res_nombre;
			$data['apellidos'] = $res_apellidos;

			$json[] = $data;
		}
		echo json_encode($json);

/*
		$res = $stmt->get_result();
		$data = array();
		while ($row = $res->fetch_assoc()) {
		    $data[]=$row;
		}
		echo json_encode($data);
*/
	    /* cierro stmt */
	    $stmt->close();
	} else {
		echo json_encode(array());
	}

	$mysqli->close();

?>