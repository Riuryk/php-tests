<?php

        include "/srv/www/htdocs/tareas/validar_token.php";
        include "/srv/www/htdocs/tareas/datos_mysql.php";

        $token = $_POST['token'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $search = $_POST['search'];

        if(is_jwt_valid($token)){

        $con = mysqli_connect("localhost","root","Tadmin-redes2021","macs_db");

        $from_date = mysqli_real_escape_string($con,$from_date);
        $to_date = mysqli_real_escape_string($con,$to_date);
        $filtervalues = mysqli_real_escape_string($con,$search);

        $query = "SELECT * FROM macs WHERE CONCAT(router,mac) LIKE '%$filtervalues%' AND `time` BETWEEN '$from_date' AND '$to_date' ";

        $query_run = mysqli_query($con, $query);

        $datos = array();

        if(mysqli_num_rows($query_run) > 0){

                foreach($query_run as $items){

                        $datos[] = $items;
                }
        }
            $result = array();
            $result = $datos;
            echo json_encode(array("result"=>$result));
        }

?>