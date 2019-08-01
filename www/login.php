

<?php
$servername = "localhost";
$username = "php";
$password = "12345php";
$dbname = "app1";

function generateRandomString($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$passhash = sha1 ( "1f5e84a6f3314bde".$_GET["pass"]);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$stmt = $conn->prepare("SELECT id,pass FROM PACIENTES WHERE email = ?");
$stmt->bind_param("s", $_GET["email"]);
$stmt->execute();
$stmt->store_result();    
$stmt->bind_result($id, $passserv); 
$ids=-1;
if($stmt->num_rows > 0) {
    while ($stmt->fetch()) {
        if($passserv==$passhash) {
        	$ids=$id;
        }
    }
}
$stmt->close();
if ($ids ==-1 ) {

	echo "usuario o contraseña incorrectos";
	echo "<br><br><H1>Iniciar sesión</H1><form action=\"login.php\" method=\"get\">E-mail: <input type=\"text\" name=\"email\" value=\"".$_GET["email"]."\"><br>contraseña: <input type=\"password\" name=\"pass\"><br><input type=\"submit\"></form>";
}else{
	$stmt = $conn->prepare("INSERT INTO TOKEN (idpaciente, token, dispositivo) VALUES (?, ?, ?)");
	$stmt->bind_param("sss", $ids, $tokennuevo, $_SERVER['REMOTE_ADDR']);
	$tokennuevo=generateRandomString();
	if (!$stmt->execute()) {
    	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	echo "<head><meta http-equiv=\"refresh\" content=\"0; URL='main.php?token=".$tokennuevo."'\" /></head>";
}

$conn->close();
?>
