

<?php
$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
    echo "<br>";
// Create database
$sql = "CREATE DATABASE ".$dbname;
if ($conn->query($sql) === TRUE) {
	$conn->close();
    echo "Database created successfully";

    echo "<br>";


	//crear tabla en la database


	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	// sql to create table
	$sql = "CREATE TABLE PACIENTES (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
		email VARCHAR(30) NOT NULL,
		pass VARCHAR(50) NOT NULL,
		nombre VARCHAR(30),
		apellido VARCHAR(60),
		nacimiento VARCHAR(30),
	enfermedad TEXT,
		caracteristicasenfermedad TEXT,
	registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Tabla Pacientes creada con exito";
	} else {
	    echo "Error creando tabla: " . $conn->error;
	}

	    echo "<br>";
	//tabla actividades
	$sql = "CREATE TABLE ACTIVIDADES (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	idpaciente INT(6) UNSIGNED ,
	nombre VARCHAR(50),
	fin VARCHAR(30),
	diasemana VARCHAR(50),
	horainicio TIME,
	horafin TIME,
	FOREIGN KEY (idpaciente) REFERENCES PACIENTES(id) ON DELETE CASCADE
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Tabla actividades creada con exito";
	} else {
	    echo "Error creando tabla: " . $conn->error;
	}
    echo "<br>";
	//tabla medicación
	$sql = "CREATE TABLE NECESIDAD (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	idpaciente INT(6) UNSIGNED,
	nombre VARCHAR(50),
	tipo VARCHAR(50),
	descripcion TEXT,
	diasemana VARCHAR(50),
	hora1 TEXT,
	hora2 TIME,
	hora3 TIME,
	hora4 TIME,
	hora5 TIME,
	FOREIGN KEY (idpaciente) REFERENCES PACIENTES(id) ON DELETE CASCADE
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Tabla medicación creada con exito";
	} else {
	    echo "Error creando tabla: " . $conn->error;
	}
	    echo "<br>";
	//tabla token
	$sql = "CREATE TABLE TOKEN (
	token VARCHAR(50) PRIMARY KEY, 
	idpaciente INT(6) UNSIGNED,
	dispositivo VARCHAR(50),
	registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (idpaciente) REFERENCES PACIENTES(id) ON DELETE CASCADE
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Tabla token creada con exito";
	} else {
	    echo "Error creando tabla: " . $conn->error;
	}

    echo "<br>";

    //tabla test
	$sql = "CREATE TABLE TEST (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	idpaciente INT(6) UNSIGNED,
	puntuacion int(6) UNSIGNED,
	registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (idpaciente) REFERENCES PACIENTES(id) ON DELETE CASCADE
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Tabla test creada con exito";
	} else {
	    echo "Error creando tabla: " . $conn->error;
	}

    echo "<br>";

	$conn->close();


} else {
    echo "Error creating database: " . $conn->error;
	$conn->close();
}

?>
