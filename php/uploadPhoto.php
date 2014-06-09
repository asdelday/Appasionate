<?php
	header('Access-Control-Allow-Origin: *');

	$imgName = $_POST['name'];
	$marker = $_POST['marker'];

	if (empty($imgName) || empty($marker)) {
		echo 0;
	}

	else {
		move_uploaded_file($_FILES["file"]["tmp_name"], "./images/".$imgName);

		// INSERTAR EN LA TABLA
		require("conn.php");

		$pathImg = 'http://rodorte.com/appasionate/images/'.$imgName;

		$query = "INSERT INTO imagenes(path_imagen, marcador) VALUES (?, ?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('si', $pathImg, $marker);
		$stmt->execute();

		/* commit transaction */
		if ($mysqli->commit()) {
			echo 1;
		} else {
			echo 0;
		}

		$mysqli->close();
	}

?>