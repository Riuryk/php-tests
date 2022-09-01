<?php

include "/srv/www/htdocs/tareas/datos_mysql.php";

$con = mysqli_connect($db['servidor'], $db['usuario'], $db['password'], $db['db']);

if ($con->connect_error) {
          die("Conexión fallida: " . $con->connect_error);
}

$jwt = file_get_contents('php://input');

include "/srv/www/htdocs/tareas/validar_token.php";


if(is_jwt_valid($jwt)){

$tokenParts = explode('.', $jwt);
$payload = json_decode(base64_decode($tokenParts[1]),true);
var_dump($payload);

$router = mysqli_real_escape_string($con,$payload['datos']['router']);


foreach($payload['datos']['macs'] as $value){

        $mac = mysqli_real_escape_string($con,$value);

        $sql = "INSERT INTO macs(router, mac) VALUES ('$router','$mac')";


if ($con->query($sql) === TRUE) {
          echo "Nueva entrada ingresada con éxito\n";
        } else {
                echo "Error: " . $sql . "<br>" . $con->error;
        }
}

}
$con->close();

?>