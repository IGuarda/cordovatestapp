

<?php

$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

$campos = array("email", "nombre", "apellido", "nacimiento", "enfermedad", "caracteristicasenfermedad");

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
	for($x = 0; $x < count($campos); $x++) {
	 	if ($_GET["dato"]==$campos[$x]){
	   		$y=$x;
	   	}
	}
    if ($y!=-1){
		$stmt = $conn->prepare("UPDATE pacientes SET ".$campos[$y]." = ? WHERE id=?");
		$stmt->bind_param("ss" , $_GET["valor"], $ids);
		if (!$stmt->execute()) {
	    	echo "Execute failed: " . $stmt->error;
		}else{echo "<head><meta http-equiv=\"refresh\" content=\"0; URL='getdata.php?token=".$_GET["token"]."'\" /></head>";}
	}else{
		echo "error de parametro";
	}
}

$conn->close();
?>
