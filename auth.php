<?php


$username = ldap_escape($_POST['username'],"",LDAP_ESCAPE_FILTER);
$password = ldap_escape($_POST['password'],"",LDAP_ESCAPE_FILTER);

include "/srv/www/htdocs/tareas/token.php";
include "/srv/www/htdocs/tareas/ldap_con.php";

$dn="uid=".$username.",".$ldapconfig['usersdn'].",".$ldapconfig['basedn'];

if(isset($_POST['username'])){
        if ($bind=ldap_bind($ds, $dn, $password)) {
                echo generar_token($username);
        }
}

?>