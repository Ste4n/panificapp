<?php

// confirmar sesion

session_start();

include ('../php/conexion_be.php');

if (!isset($_SESSION['usuario'])) {
    echo '
    <script> 
    alert("Inicia sesión")
    window.location = "../acceso.php";
    </script>
    ';
    session_destroy();
    die();
}

$user = $_SESSION['usuario'];

$sql = "SELECT id, nombre, email, direccion, telefono, usuario, id_rol, password FROM usuarios WHERE usuario='$user'";
$resultado = $conexion->query($sql);

while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {

    $id = $data['id'];
    $nombre = $data['nombre'];
    $email = $data['email'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $usuario = $data['usuario'];
    $password = $data['password'];
    $id_rol = $data['id_rol'];
}

if ($id_rol != 1) {
    echo '
    <script> 
    alert("Inicia sesión como administrador")
    window.location = "../acceso.php";
    </script>
    ';
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clientes</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/PUlogow18.png" type="image/x-icon">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../css/estilos.css">
    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif !important;
        }

        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            font-size: 40px;
            font-weight: bold;
            padding: 20px;
            width: 100%;
        }

        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            height: 680px;
            margin: 35px;
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }

        .product-header {
            display: flex;
            align-items: center;
            width: 95%;
            height: 50px;
            margin-top: 30px;
        }

        .product-header h3 {
            font-weight: bold;
        }

        .dataTables_wrapper {
            position: relative;
            width: 95% !important;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            padding: 20px;
            border-radius: 10px;
            height: 83%;
            text-align: center !important;
        }

        .dataTables_info {
            position: absolute;
            bottom: 10px;
            left: 10px;
        }

        .dataTables_paginate {
            position: absolute;
            bottom: 10px;
            right: 0px;
        }

        table.dataTable thead>tr>th.sorting,
        table.dataTable thead>tr>th.sorting_asc,
        table.dataTable thead>tr>th.sorting_desc,
        table.dataTable thead>tr>th.sorting_asc_disabled,
        table.dataTable thead>tr>th.sorting_desc_disabled,
        table.dataTable thead>tr>td.sorting,
        table.dataTable thead>tr>td.sorting_asc,
        table.dataTable thead>tr>td.sorting_desc,
        table.dataTable thead>tr>td.sorting_asc_disabled,
        table.dataTable thead>tr>td.sorting_desc_disabled {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .modal-content {
            margin-top: 100px !important;
        }
    </style>
</head>

<body>
    <!-- Encabezado de la página -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="adminDash.php">Perfil</a>
            <a href="produccion.php">Producción</a>
            <a href="adminPedidos.php">Pedidos</a>
            <a href="clientes.php">Clientes</a>
            <a href="agregarProducto.php">Agregar Producto</a>
            <a href="inventario.php">Inventario</a>
            <a href="../php/cerrar_sesion.php">Cerrar Session</a>
        </nav>

        <!-- Sección de encabezado con texto e imagen -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Horarios de Producción</h1>
        </section>

        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>

        <!-- Estilos personalizados -->
        <style>
            .container {
                max-width: 900px;
            }

            .card-body {
                background-color: #f8f9fa;
            }

            .display-section {
                margin-top: 20px;
            }

            .display-section h4 {
                background-color: #f5b81e;
                color: white;
                padding: 10px;
            }

            .display-section .data-container {
                background-color: #ffffff;
                padding: 20px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }

            .data-container .data-item {
                margin-bottom: 10px;
            }

            .data-container .data-item span {
                font-weight: bold;
            }


            .container {
                max-width: 900px;
            }

            .card-body {
                background-color: #f8f9fa;
            }

            .display-section {
                margin-top: 20px;
            }

            .display-section h4 {
                background-color: #f5b81e;
                color: white;
                padding: 10px;
            }

            .display-section .data-container {
                background-color: #ffffff;
                padding: 20px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }

            .data-container .data-item {
                margin-bottom: 10px;
            }

            .data-container .data-item span {
                font-weight: bold;
            }

            .search-bar {
                margin-bottom: 20px;
                display: flex;
                justify-content: flex-end;
            }

            .search-bar input {
                max-width: 300px;
            }
        </style>
    </header>
    <div class="main">


        <div class="product-container">
            <div class="product-header mb-2">
                <h3>Lista de Horarios</h3>

                <!-- Botón para agregar -->
                <div class="button-group ml-auto">
                    <button type="button" class="btn btn-dark" data-toggle="modal"
                        data-target="#addHorarioModal">Agregar Horario</button>
                </div>
            </div>

            <!-- Añadir Horario Modal -->
            <div class="modal fade" id="addHorarioModal" tabindex="-1" aria-labelledby="addHorario" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content mt-5">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addHorario">Agregar Horario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../procesos/agregaHor.php" method="POST">
                                <div class="form-group">
                                    <label for="fecha">Fecha:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                </div>
                                <div class="form-group">
                                    <label for="turno">Turno:</label>
                                    <select id="turno" name="turno" class="form-control" required>
                                        <option value="Mañana">Mañana</option>
                                        <option value="Once">Once</option>
                                        <option value="Tarde">Tarde</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cproducir">Cantidad a Producir (kg):</label>
                                    <input type="number" class="form-control" id="cproducir" name="cproducir"
                                        step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="cagendada">Cantidad Agendada (kg):</label>
                                    <input type="number" class="form-control" id="cagendada" name="cagendada"
                                        step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="estadop">Estado:</label>
                                    <select id="estadop" name="estadop" class="form-control" required>
                                        <option value="Abierto">Abierto</option>
                                        <option value="Cerrado">Cerrado</option>
                                        <option value="Entregado">Entregado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary form-control mt-1 mb-1">Guardar
                                    Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Horario Modal -->
            <div class="modal fade" id="updateHorarioModal" tabindex="-1" aria-labelledby="updateHorario"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content mt-5">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateHorario">Actualizar Horario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../procesos/actualizaHor.php" method="POST">
                                <div class="form-group" hidden>
                                    <label for="updateHorarioID">ID de Horario</label>
                                    <input type="text" class="form-control" id="updateHorarioID" name="id">
                                </div>
                                <div class="form-group">
                                    <label for="updateFecha">Fecha:</label>
                                    <input type="date" class="form-control" id="updateFecha" name="fecha">
                                </div>
                                <div class="form-group">
                                    <label for="updateTurno">Turno:</label>
                                    <select class="form-control" id="updateTurno" name="turno">
                                        <option value="Mañana">Mañana</option>
                                        <option value="Once">Once</option>
                                        <option value="Trade">Trade</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="updateCProducir">Cantidad a Producir (kg):</label>
                                    <input type="number" class="form-control" id="updateCProducir" name="cproducir">
                                </div>
                                <div class="form-group">
                                    <label for="updateCAgendada">Cantidad Agendada (kg):</label>
                                    <input type="number" class="form-control" id="updateCAgendada" name="cagendada">
                                </div>
                                <div class="form-group">
                                    <label for="updateEstadoP">Estado:</label>
                                    <select class="form-control" id="updateEstadoP" name="estadop">
                                        <option value="Abierto">Abierto</option>
                                        <option value="Cerrado">Cerrado</option>
                                        <option value="Entregado">Entregado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary form-control mt-1 mb-1">Guardar
                                    Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-hover horario-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Turno</th>
                        <th scope="col">Cantidad a Producir (kg)</th>
                        <th scope="col">Cantidad Agendada (kg)</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Cambiar Estado</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $stmt = $conexion->prepare("SELECT * FROM horarios");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    foreach ($result as $row) {
                        $id = $row['id'];
                        $fecha = $row['fecha'];
                        $turno = $row['turno'];
                        $cproducir = $row['cproducir'];
                        $cagendada = $row['cagendada'];
                        $estadop = $row['estadop'];
                        ?>

                        <tr>
                            <th scope="row"><?= $id ?></th>
                            <td><?= $fecha ?></td>
                            <td><?= $turno ?></td>
                            <td><?= $cproducir ?></td>
                            <td><?= $cagendada ?></td>
                            <td><?= $estadop ?></td>
                            <td>
                                <button type="button" class="btn btn-primary" style="font-size: 12px;"
                                    onclick="updateHorario(<?= $id ?>)">Editar</button>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>



<script>

    // Script para actuliazar

    $(document).ready(function () {
        $('.horarios').DataTable();
    });

    function updateHorario(id) {
        $("#updateHorarioModal").modal("show");

        let updateHorarioID = $("#horarioID-" + id).text();
        let updateFecha = $("#fecha-" + id).text();
        let updateTurno = $("#turno-" + id).text();
        let updateCProducir = $("#cproducir-" + id).text();
        let updateCAgendada = $("#cagendada-" + id).text();
        let updateEstadoP = $("#estadop-" + id).text();

        $("#updateHorarioID").val(updateHorarioID);
        $("#updateFecha").val(updateFecha);
        $("#updateTurno").val(updateTurno);
        $("#updateCProducir").val(updateCProducir);
        $("#updateCAgendada").val(updateCAgendada);
        $("#updateEstadoP").val(updateEstadoP);
    }

</script>

<!-- Pie de página -->
<footer class="text-center text-white p-3" style="background-color: orange;">
    <div class="text-center">
        © Copyright 2024 | PanificApp
    </div>
</footer>