

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
    echo "<form action=\"getdata.php\" method=\"get\">";
    echo "<input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\">";
    echo "<input type=\"submit\" value=\"información del paciente\"></form>";
    echo "<form action=\"test.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"submit\" value=\"realizar test\"></form>";
    echo "<form action=\"delsesion.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"submit\" value=\"cerrar sesión\"></form><br>";
    echo"<H2>Necesidades</H2>";
	$stmt = $conn->prepare("SELECT nombre, tipo, descripcion, diasemana, hora1, hora2, hora3, hora4, hora5 FROM NECESIDAD WHERE idpaciente=?");
	$stmt->bind_param("s", $ids);
    if (!$stmt->execute()) {
        echo "Execute failed: " . $stmt->error;
    }else{
        $stmt->store_result();    
        $stmt->bind_result($nombre, $tipo, $descripcion, $diasemana, $hora1, $hora2, $hora3, $hora4, $hora5); 
        if($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                echo $nombre.", ".$tipo.", ".$descripcion.", ".$diasemana.", ".$hora1." ".$hora2." ".$hora3." ".$hora4." ".$hora5."<form action=\"delNecesidad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"hidden\" name=\"nombre\" value=\"".$nombre."\"><input type=\"submit\" value=\"eliminar\"></form><br>";
            }
        }
        echo "<form action=\"necesidad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"submit\" value=\"nuevo\"></form><br>";
    }
    echo"<H2>Actividades</H2>";
    $stmt = $conn->prepare("SELECT nombre, diasemana, horainicio, horafin, fin FROM actividades WHERE idpaciente=?");
    $stmt->bind_param("s", $ids);
    if (!$stmt->execute()) {
        echo "Execute failed: " . $stmt->error;
    }else{
        $stmt->store_result();    
        $stmt->bind_result($nombre, $diasemana, $horainicio, $horafin, $fin); 
        if($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                echo $nombre.", ".$diasemana.", ".$horainicio.", ".$horafin.", ".$fin."<form action=\"delactividad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"hidden\" name=\"nombre\" value=\"".$nombre."\"><input type=\"submit\" value=\"eliminar\"></form><br>";
            }
        }
        echo "<form action=\"crearactividad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><input type=\"submit\" value=\"nuevo\"></form><br>";
    }
}
$conn->close();
?>
