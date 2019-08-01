

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
    
    if(isset($_GET["nombre"])){
    	$stmt = $conn->prepare("INSERT INTO actividades (idpaciente, nombre, diasemana, horainicio, horafin) VALUES (?, ?, ?, ?, ?)");
    	$stmt->bind_param("sssss", $ids, $_GET["nombre"], $diasem, $_GET["horaini"],$_GET["horafin"]);
        $diasem = implode(',',$_GET["diasem"]);
    	if (!$stmt->execute()) {
        	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    	}else{
            echo "<head><meta http-equiv=\"refresh\" content=\"0; URL='main.php?token=".$_GET["token"]."'\" /></head>";
        }
    }else{
        echo "<H1>Crear actividad</H1>
<form action=\"crearactividad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value =\"".$_GET["token"]."\"><br>
nombre <input type=\"text\" name=\"nombre\"><br>
<input type=\"checkbox\" name=\"diasem[]\" value=\"L\">L <input type=\"checkbox\" name=\"diasem[]\" value=\"M\">M <input type=\"checkbox\" name=\"diasem[]\" value=\"X\">X <input type=\"checkbox\" name=\"diasem[]\" value=\"J\">J <input type=\"checkbox\" name=\"diasem[]\" value=\"V\">V <input type=\"checkbox\" name=\"diasem[]\" value=\"S\">S <input type=\"checkbox\" name=\"diasem[]\" value=\"D\">D <br>
<!--fecha fin <input type=\"text\" name=\"fin\"><br>-->
Hora inicio <input type=\"text\" name=\"horaini\"><br>
Hora fin <input type=\"text\" name=\"horafin\"><br>
<input type=\"submit\">
</form>";
    }
}

$conn->close();
?>
