

<?php

$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";


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
    
	$stmt = $conn->prepare("SELECT nombre, diasemana, horainicio, horafin, fin FROM actividades WHERE idpaciente=?");
	$stmt->bind_param("s", $ids);
    if (!$stmt->execute()) {
        echo "Execute failed: " . $stmt->error;
    }else{
        $stmt->store_result();    
        $stmt->bind_result($nombre, $diasemana, $horainicio, $horafin, $fin); 
        if($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                echo $nombre.", ".$diasemana.", ".$horainicio.", ".$horafin.", ".$fin."<br>";
            }
        }
    }
}
$conn->close();
?>
