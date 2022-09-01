<?php

session_start();

if(isset($_SESSION['nombredelusuario']))
{
                header('Location: http://telem0.cure.edu.uy/prueba.php/');
}

$username = ldap_escape($_POST['username'],"",LDAP_ESCAPE_FILTER);
$password = ldap_escape($_POST['password'],"",LDAP_ESCAPE_FILTER);

include "/srv/www/htdocs/tareas/ldap_con.php";

$dn="uid=".$username.",".$ldapconfig['usersdn'].",".$ldapconfig['basedn'];

if(isset($_POST['username'])){
        if ($bind=ldap_bind($ds, $dn, $password)) {
                $_SESSION['nombredelusuario']=$username;
                header('Location: http://telem0.cure.edu.uy/macs.php/');
        } else {
                echo '<script language="javascript">';
                echo 'alert("Fallo en el logueo: Por favor chequee su usuario y/o contraseña")';
                echo '</script>';
        }
}
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Inicio de sesión</title>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Inicio de sesión</h4>
                        <form action="" method="post">
                        <input type="text" name="username" placeholder="Usuario">
                        <input type="password" name="password" placeholder="Contraseña">
                        <!--<input type="submit" value="Ingresar">-->
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                        </form>
                    </div>
               </div>
           </div>
      </div>
</div>
</body>
</html>