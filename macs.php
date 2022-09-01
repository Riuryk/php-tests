<?php
session_start();

if (isset($_SESSION['nombredelusuario'])) {
    $usuarioingresado = $_SESSION['nombredelusuario'];
    echo "<h1>Bienvenido: $usuarioingresado </h1>";
} else {
    header('Location: https://telem0.cure.edu.uy/login.php/');
}

if (isset($_POST['btncerrar'])) {
    session_destroy();
    header('Location: https://telem0.cure.edu.uy/login.php/');
}

?>

<body>
    <form method="POST">
        <button type="submit" name="btncerrar" class="btn btn-primary">Cerrar sesión</button>
        <!--input type="submit" value="Cerrar sesión" name="btncerrar" /-->
    </form>
</body>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Consultar macs</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Busqueda de MACs recolectadas</h4>
                    </div>
                    <div class="card-body">

                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Desde</label>
                                        <input type="date" name="from_date" value="<?php if (isset($_GET['from_date'])) {
                                                                                        echo $_GET['from_date'];
                                                                                    } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Hasta</label>
                                        <input type="date" name="to_date" value="<?php if (isset($_GET['to_date'])) {
                                                                                        echo $_GET['to_date'];
                                                                                    } ?>" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">

                                        <form action="" method="GET">
                                            <div class="input-group mb-3">
                                                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                                                            echo $_GET['search'];
                                                                                        } ?>" class="form-control" placeholder="buscar datos">

                                            </div>
                                        </form>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>



                    <canvas id="myChart" style="position: relative; height: 40vh; width: 80vw;"></canvas>



                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Router</th>
                                            <th>MAC</th>
                                            <th>Fecha y hora</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        include "/srv/www/htdocs/tareas/datos_mysql.php";

                                        $con = mysqli_connect($db['servidor'], $db['usuario'], $db['password'], $db['db']);

                                        if (isset($_GET['search']) && isset($_GET['from_date']) && isset($_GET['to_date'])) {

                                            $from_date = mysqli_real_escape_string($con, $_GET['from_date']);
                                            $to_date = mysqli_real_escape_string($con, $_GET['to_date']);
                                            $filtervalues = mysqli_real_escape_string($con, $_GET['search']);

                                            $query = "SELECT * FROM macs WHERE CONCAT(router,mac) LIKE '%$filtervalues%' AND `time` BETWEEN '$from_date' AND '$to_date' ";
                                            $query_run = mysqli_query($con, $query);

                                            if (mysqli_num_rows($query_run) > 0) {
                                        ?>

                                                <?php

                                                $graph = 1;
                                                $a = array();

                                                foreach ($query_run as $items) {
                                                    array_push($a, $items['mac']);
                                                ?>
                                                    <tr>
                                                        <td><?= $items['id']; ?></td>
                                                        <td><?= $items['router']; ?></td>
                                                        <td><?= $items['mac']; ?></td>
                                                        <td><?= $items['time']; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4">No se encontraron datos</td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <?php
            if($graph == 1){

            sort($a);
            $arr_length = count($a);
            $mac = $a[0];
            $macs = array($mac => 1);

            for ($i = 1; $i < $arr_length; $i++) {

                if ($a[$i] == $mac) {

                    $macs[$mac]++;

                } else {

                    $mac = $a[$i];

                    $aux = array("$mac" => 1);

                    $macs = array_merge($macs, $aux);
                }
            }

            $jsonArr = array();

            foreach($macs as $key => $value){
                $aux = array(array("mac"=>$key, "cant"=>$value));
                $jsonArr = array_merge($jsonArr, $aux);
            }
        }
            ?>

            <script type="text/javascript">
                var macs = <?php echo json_encode($jsonArr); ?>
            </script>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                var ctx = document.getElementById('myChart')
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: '# de entradas encontradas correspondientes a cada MAC',
                            backgroundColor: ['#6bf1ab', '#63d69f', '#438c6c', '#509c7f', '#1f794e', '#34444c', '#90CAF9', '#64B5F6', '#42A5F5', '#2196F3', '#0D47A1'],
                            borderColor: ['black'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })

                const mostrar = (macs) => {
                    macs.forEach(element => {
                        myChart.data['labels'].push(element.mac)
                        myChart.data['datasets'][0].data.push(element.cant)
                        myChart.update()
                    });

                }

                mostrar(macs)

            </script>

            <script src=<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>