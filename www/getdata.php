

<?php

$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";
$campos = array("email", "nombre", "apellido", "nacimiento", "enfermedad", "caracteristicasenfermedad");
$mostrar = array("email", "nombre", "apellido", " fecha de nacimiento", "enfermedad", "caracteristicas de la enfermedad");

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$stmt = $conn->prepare("SELECT idpaciente FROM TOKEN WHERE token = ?");
$stmt->bind_param("s", $_GET["token"]);
$stmt->execute();
$stmt->store_result();    
$stmt->bind_result($idp); 
$ids=-1;
if($stmt->num_rows > 0) {
    while ($stmt->fetch()) {
        $ids=$idp;
    }
}
$stmt->close();
if ($ids ==-1 ) {

	echo "<head><meta http-equiv=\"refresh\" content=\"0; URL='index.html'\" /></head>";
}else{
	$y=-1;
	if (isset($_GET["dato"])){
	    for($x = 0; $x < count($campos); $x++) {
	    	if ($_GET["dato"]==$campos[$x]){
	    		$y=$x;
	    	}
	    }
	}
    if ($y!=-1){
		$stmt = $conn->prepare("SELECT ".$campos[$y]." FROM pacientes WHERE id=?");
		$stmt->bind_param("s" , $ids);
		if (!$stmt->execute()) {
	    	echo "Execute failed: " . $stmt->error;
		}else{
			$stmt->store_result();    
			$stmt->bind_result($dato); 
			if($stmt->num_rows > 0) {
			    while ($stmt->fetch()) {
			        echo $dato;
			    }
			}
		}
	}else{

		echo "<h1> información del paciente</h1><br>";
		for($x = 0; $x < count($campos); $x++) {
			$stmt = $conn->prepare("SELECT ".$campos[$x]." FROM pacientes WHERE id=?");
			$stmt->bind_param("s" , $ids);
			if (!$stmt->execute()) {
		    	echo "Execute failed: " . $stmt->error;
			}else{
				$stmt->store_result();    
				$stmt->bind_result($dato); 
				if($stmt->num_rows > 0) {
				    while ($stmt->fetch()) {
						if (isset($_GET["edit"])){
				        	echo "<form action=\"updatedata.php\" method=\"get\">";
				        	echo $mostrar[$x].": <input type=\"text\" name=\"valor\" value=\"".$dato."\">";
				        	echo "<input type=\"hidden\" name=\"dato\" value=\"".$campos[$x]."\">";
				        	echo "<input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\">";
				        	echo "<input type=\"submit\"></form><br>";
				        }else{
				        	echo $mostrar[$x].": ".$dato."<br>";
				        }
				    }
				}
			}
		}
		if (!isset($_GET["edit"])){
			echo "<form action=\"getdata.php\" method=\"get\">";
			echo "<input type=\"hidden\" name=\"edit\" value=\"on\">";
			echo "<input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\">";
			echo "<input type=\"submit\" value=\"Editar\"></form><br><br>";
		} 
	}
}

$conn->close();
?>
