

<?php

$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

$tipos = array("Siente que su familiar solicita más ayuda de la que realmente necesita?", "Siente que debido al tiempo que dedica a su familiar ya no dispone de tiempo suficiente para usted?", "Se siente tenso cuando tiene que cuidar a su familiar y atender además otras responsabilidades?", "Se siente avergonzado por la conducta de su familiar?", "Se siente enfadado cuando está cerca de su familiar?", "Cree que la situación actual afecta de manera negativa a su relación con amigos y otros miembros de su familia?", "Siente temor por el futuro que le espera a su familiar?", "Siente que su familiar depende de usted?", "Se siente agobiado cuando tiene que estar junto a su familiar?", "Siente que su salud se ha resentido por cuidar a su familiar? ", "Siente que no tiene la vida privada que desearía debido a su familiar?", "Cree que su vida social se ha visto afectada por tener que cuidar de su familiar?", "Se siente incómodo para invitar amigos a casa, a causa de su familiar?", "Cree que su familiar espera que usted le cuide, como si fuera la única persona con la que puede contar?", "Cree que no dispone de dinero suficiente para cuidar a su familiar además de sus otros gastos?", "Siente que será incapaz de cuidar a su familiar por mucho más tiempo?", "Siente que ha perdido el control sobre su vida desde que la enfermedad de su familiar se manifestó?", "Desearía poder encargar el cuidado de su familiar a otras personas?", "Se siente inseguro acerca de lo que debe hacer con su familiar?", "Siente que debería hacer más de lo que hace por su familiar?", "Cree que podría cuidar de su familiar mejor de lo que lo hace?", "En general: ¿Se siente muy sobrecargado por tener que cuidar de su familiar?");

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
    if(isset($_GET["0"])&&isset($_GET["1"])){
        $sum=0;
        for($x = 0; $x < count($tipos); $x++) {
            $sum+=(int)$_GET[$x.""];
        }
        $sum=$sum."";
    	$stmt = $conn->prepare("INSERT INTO TEST (idpaciente, puntuacion) VALUES (?, ?)");
    	$stmt->bind_param("ss", $ids, $sum);
    	if (!$stmt->execute()) {
        	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    	}else{
            echo " el resultado del test es <B>".$sum."</B><br>
            •   Sin sobrecarga: 22- 46.<br>
            •   Sobrecarga: 47-55.<br>
            •   Sobrecarga intensa: 56-110.<br>";
            echo "<form action=\"main.php\" method=\"get\">";
            echo "<input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\">";
            echo "<input type=\"submit\" value=\"Volver a inicio\"></form><br>";
        }
    }else{
        echo "<h1>Calcular escala de carga</h1><form action=\"test.php\" method=\"get\"><input type=\"hidden\" name=\"token\" value=\"".$_GET["token"]."\"><table>";
        echo"<tr><td></td><td> Nunca </td><td> Casi nunca </td><td> A veces </td><td> Bastantes veces </td></tr>";
        for($x = 0; $x < count($tipos); $x++) {
            echo"<tr><td>".$tipos[$x]."</td><td><input type=\"radio\" name=\"".$x."\" value=\"0\" required></td><td><input type=\"radio\" name=\"".$x."\" value=\"1\"></td><td><input type=\"radio\" name=\"".$x."\" value=\"2\"></td><td><input type=\"radio\" name=\"".$x."\" value=\"3\"></td></tr>";
        }
        echo "</table><input type=\"submit\"></form>";
    }
}

$conn->close();
?>
