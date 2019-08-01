

<?php

$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

$tipos = array("Medicación", "Curas", "Respirador", "Ingesta", "Eliminación", "Movidlidad", "Otros");

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
    if(isset($_GET["nombre"])&&isset($_GET["tipo"])){
    	$stmt = $conn->prepare("INSERT INTO NECESIDAD (idpaciente, nombre, tipo, descripcion, diasemana, hora1, hora2, hora3, hora4, hora5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    	$stmt->bind_param("ssssssssss", $ids, $_GET["nombre"], $_GET["tipo"], $_GET["descripcion"], $diasem, $_GET["hora1"], $_GET["hora2"],$_GET["hora3"], $_GET["hora4"],$_GET["hora5"]);
        $diasem = implode(',',$_GET["diasem"]);
    	if (!$stmt->execute()) {
        	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    	}else{
            echo "<head><meta http-equiv=\"refresh\" content=\"0; URL='main.php?token=".$_GET["token"]."'\" /></head>";
        }
    }else{
        echo "<h1>Añadir Necesidad</h1><form action=\"Necesidad.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><br>Nombre <input type=\"text\" name=\"nombre\"><br>Tipo <select name=\"tipo\">";
        for($x = 0; $x < count($tipos); $x++) {
            echo" <option value=\"".$tipos[$x]."\">".$tipos[$x]."</option>";
        }
        echo "</select><br>Descripción <textarea name=\"descripcion\"></textarea><br>Días: <br><input type=\"checkbox\" name=\"diasem[]\" value=\"L\">L  <input type=\"checkbox\" name=\"diasem[]\" value=\"M\">M  <input type=\"checkbox\" name=\"diasem[]\" value=\"X\">X  <input type=\"checkbox\" name=\"diasem[]\" value=\"J\">J  <input type=\"checkbox\" name=\"diasem[]\" value=\"V\">V  <input type=\"checkbox\" name=\"diasem[]\" value=\"S\">S  <input type=\"checkbox\" name=\"diasem[]\" value=\"D\">D<br>Hora <input type=\"text\" name=\"hora1\"><br><!--Hora2 <input type=\"text\" name=\"hora2\"><br>Hora3 <input type=\"text\" name=\"hora3\"><br>Hora4 <input type=\"text\" name=\"hora4\"><br>Hora5 <input type=\"text\" name=\"hora5\"><br>--><input type=\"submit\">";
    }
}

$conn->close();
?>
