<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	$email=$_POST["email"];
	$pass=$_POST['pass'];
	$passRep=$_POST['passRep'];
	$nombre=$_POST['nombre'];
	$apellidos=$_POST['apellidos'];
	$cod=$_POST['cod'];

	// EMPTY
	if (empty($email) || empty($pass) || empty($passRep) || empty($nombre) || empty($apellidos) || empty($cod)) {
		echo json_encode(false);
	}

	else {

		// EMAIL VALIDO
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

			// EMAIL EXISTENTE
			$queryEmail = "SELECT COUNT(id_usuario) AS count FROM usuarios  WHERE email=? LIMIT 1";
			$stmtEmail = $mysqli->stmt_init();
			if ($stmtEmail = $mysqli->prepare($queryEmail)) {

			    $stmtEmail->bind_param('s', $email);

			    $stmtEmail->execute();
			    $stmtEmail->bind_result($res_count_email);
			    $stmtEmail->fetch();

			    if ($res_count_email > 0) {
			    	echo json_encode(false);
			    	$stmtEmail->close();
			    }

			    // EMAIL NO EXISTE
			    else {

			    	// CONTRASEÑA INCORRECTA - CORTA
					if (strlen($pass)<6 || ($pass != $passRep)) {
					  	echo json_encode(false);
					}

					// CONTRASEÑA CORRECTA
					else {
						$stmtEmail->close();

						// CODIGO EXISTENTE
						$queryCod = "SELECT id_clase FROM clases WHERE cod=? LIMIT 1";
						if ($stmtCod = $mysqli->prepare($queryCod)) {

			    			$stmtCod->bind_param('s', $cod);

			    			$stmtCod->execute();
			    			$stmtCod->bind_result($res_id_clase);

			    			$res = $stmtCod->fetch();
			    			if (!$res) {
			    				echo json_encode(false);
			    				$stmtCod->close();
			    			}

			    			// CODIGO EXISTENTE
			    			else {
			    				$clase = $res_id_clase;
			    				$passCod = md5(md5($pass));
			    				$stmtCod->close();

			    				/* set autocommit to off */
								$mysqli->autocommit(FALSE);

								// INSERTAR USUARIO
								$queryInsertUsr = "INSERT INTO usuarios(id_usuario, email, nombre, apellidos, password, rol)
												VALUES (NULL, ?, ?, ?, ?, 2)";
								$stmtInsertUsr = $mysqli->prepare($queryInsertUsr);
							    $stmtInsertUsr->bind_param('ssss', $email, $nombre, $apellidos, $passCod);
							    $stmtInsertUsr->execute();

								$usrId = $mysqli->insert_id;

								// INSERTAR USUARIO_CLASE
								$queryInsertUsrClass = "INSERT INTO usuarios_clases(usuario, clase)
														VALUES (?, ?)";
								$stmtInsertUsrClass = $mysqli->prepare($queryInsertUsrClass);
							    $stmtInsertUsrClass->bind_param('ii', $usrId, $clase);
							    $stmtInsertUsrClass->execute();

								/* commit transaction */
								if ($mysqli->commit()) {
									echo json_encode(true);
								} else {
									echo json_encode(false);
								}
			    			}

			    		// COD STMT INCORRECTA
			    		} else {
			    			echo json_encode(false);
			    		}
			    	}
			    }

			// EMAIL STMT INCORRECTA
			} else {
				echo json_encode(false);
			}


		//EMAIL NO VALIDO
		} else {
			echo json_encode(false);
		}
	}

	$mysqli->close();

?>