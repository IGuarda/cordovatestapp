

<?php
$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

if(isset($_GET["email"]) && isset($_GET["email"])){


	$passhash = sha1 ( "1f5e84a6f3314bde".$_GET["pass"]);

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	if (isset($_GET["email"]))
	$stmt = $conn->prepare("SELECT id FROM PACIENTES WHERE email = ?");
	$stmt->bind_param("s", $_GET["email"]);
	$stmt->execute();
	$stmt->store_result();    
	/*$stmt->bind_result($userid);  // number of arguments must match columns in SELECT
	if($stmt->num_rows > 0) {
	    while ($stmt->fetch()) {
	        echo $userid;  
	    }
	}*/
	if ($stmt->num_rows > 0) {

		echo "La dirección ".htmlspecialchars($_GET["email"])." ya está registrada";

		$stmt->close();

	}else if(strlen ( $_GET["pass"])<4){

		echo "<H1>Registro</H1><br>la contraseña es demasiado corta";
		echo "<form action=\"registro.php\" method=\"get\">nombre: <input type=\"text\" name=\"nombre\" value=\"". $_GET["nombre"]."\"><br>apellidos: <input type=\"text\" name=\"apellidos\" value=\"".$_GET["apellidos"]."\"><br>";
		echo "E-mail: <input type=\"text\" name=\"email\" value=\"".$_GET["email"] ."\"><br>contraseña: <input type=\"password\" name=\"pass\"><br><input type=\"submit\"></form>";
	}else{

		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO PACIENTES (nombre, apellido, email, pass) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $_GET["nombre"], $_GET["apellidos"], $_GET["email"], $passhash);
		if (!$stmt->execute()) {
	    	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		echo "usuario ".htmlspecialchars($_GET["email"])." creado con éxito";
		echo "<br><br><H1>Iniciar sesión</H1><form action=\"login.php\" method=\"get\">E-mail: <input type=\"text\" name=\"email\" value=\"".$_GET["email"]."\"><br>contraseña: <input type=\"password\" name=\"pass\"><br><input type=\"submit\"></form>";
	}

	$conn->close();
}else{
	echo "<H1>Registro</H1><form action=\"registro.php\" method=\"get\">nombre: <input type=\"text\" name=\"nombre\"><br>apellidos: <input type=\"text\" name=\"apellidos\"><br>";
	echo "E-mail: <input type=\"text\" name=\"email\"><br>contraseña: <input type=\"password\" name=\"pass\"><br><input type=\"submit\"></form>";
}
?>
