<?php

// confirmar sesion

session_start();

include('../php/conexion_be.php');

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
    <title>Invetario</title>
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
    <header>
        <!-- Navigation bar -->
        <nav>
            <a href="adminDash.php">Perfil</a>
            <a href="produccion.php">Producción</a>
            <a href="adminPedidos.php">Pedidos</a>
            <a href="clientes.php">Clientes</a>
            <a href="agregarProducto.php">Agregar Producto</a>
            <a href="horarios.php">Horarios</a>
            <a href="../php/cerrar_sesion.php">Cerrar Session</a>
        </nav>
        <!-- Header section -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Inventario</h1>
        </section>
        <!-- Decorative wave -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>
    <div class="main">

        <div class="product-container">

            <div class="product-header mb-2">
                <h3>Lista de Productos</h3>

                <!-- Botón para agregar -->
                <div class="button-group ml-auto">
                    <button type="button" class="btn btn-dark" data-toggle="modal"
                        data-target="#addProductModal">Agregar Producto</button>
                </div>
            </div>

            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProduct" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content mt-5">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProduct">Agregar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../procesos/agregaInv.php" method="POST">
                                <div class="form-group">
                                    <label for="nombreP">Nombre de Producto:</label>
                                    <input type="text" class="form-control" id="nombreP" name="nombre">
                                </div>
                                <div class="form-group">
                                    <label for="categoriaP">Categoria del Producto:</label>
                                    <select class="form-control" id="categoriaP" name="categoria">
                                        <option value="harina_blanca">Harina Blanca</option>
                                        <option value="harina_integral">Harina Integral</option>
                                        <option value="sal">Sal</option>
                                        <option value="mejorador">Mejorador</option>
                                        <option value="manteca">Manteca</option>
                                        <option value="levadura">Levadura</option>
                                        <option value="aceite">Aceite</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="proveedor">Proveedor:</label>
                                    <select class="form-control" id="proveedor" name="proveedor">
                                        <option value="Harinas Collico">Harinas Collico</option>
                                        <option value="Lider CL">Lider CL</option>
                                        <option value="Molinos Kunstmann">Molinos Kunstmann</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="precio">Precio:</label>
                                            <input type="text" class="form-control" id="precio" name="precio">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="unidades">Unidades:</label>
                                            <input type="text" class="form-control" id="unidades" name="unidades">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="cantidad">Cantidad:</label>
                                            <input type="text" class="form-control" id="cantidad" name="cantidad">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="fecha_ingreso">Fecha de Ingreso:</label>
                                            <input type="date" class="form-control" id="fecha_ingreso"
                                                name="fecha_ingreso">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                                            <input type="date" class="form-control" id="fecha_vencimiento"
                                                name="fecha_vencimiento">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary form-control mt-1 mb-1">Guardar
                                    Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Product Modal -->
            <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProduct"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content mt-5">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateProduct">Actualizar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../procesos/actualizaInv.php" method="POST">
                                <div class="form-group" hidden>
                                    <label for="updateProductID">ID de Producto</label>
                                    <input type="text" class="form-control" id="updateProductID" name="id">
                                </div>
                                <div class="form-group">
                                    <label for="updatenombreP">Nombre de Producto:</label>
                                    <input type="text" class="form-control" id="updatenombreP" name="nombre">
                                </div>
                                <div class="form-group">
                                    <label for="updatecategoriaP">Categoria del Producto:</label>
                                    <select class="form-control" id="updatecategoriaP" name="categoria">
                                        <option value="harina_blanca">Harina Blanca</option>
                                        <option value="harina_integral">Harina Integral</option>
                                        <option value="sal">Sal</option>
                                        <option value="mejorador">Mejorador</option>
                                        <option value="manteca">Manteca</option>
                                        <option value="levadura">Levadura</option>
                                        <option value="aceite">Aceite</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="updateproveedor">Proveedor:</label>
                                    <select class="form-control" id="updateproveedor" name="proveedor">
                                        <option value="Harinas Collico">Harinas Collico</option>
                                        <option value="Lider CL">Lider CL</option>
                                        <option value="Molinos Kunstmann">Molinos Kunstmann</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="updateprecio">Precio:</label>
                                            <input type="text" class="form-control" id="updateprecio" name="precio">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="updateunidades">Unidades:</label>
                                            <input type="text" class="form-control" id="updateunidades" name="unidades">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="updatecantidad">Cantidad:</label>
                                            <input type="text" class="form-control" id="updatecantidad" name="cantidad">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="updatefecha_ingreso">Fecha de Ingreso:</label>
                                            <input type="date" class="form-control" id="updatefecha_ingreso"
                                                name="fecha_ingreso">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="updatefecha_vencimiento">Fecha de Vencimiento:</label>
                                            <input type="date" class="form-control" id="updatefecha_vencimiento"
                                                name="fecha_vencimiento">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary form-control mt-1 mb-1">Guardar
                                    Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-hover product-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre de Producto</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Precio (CLP)</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Cantidad (g/ml)</th>
                        <th scope="col">Fecha de Ingreso</th>
                        <th scope="col">Fecha de vencimiento</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    $stmt = $conexion->prepare("SELECT * FROM inventario");

                    $stmt->execute();

                    $result = $stmt->fetchAll();

                    foreach ($result as $row) {
                        $id = $row['id'];
                        $nombre = $row['nombre'];
                        $categoria = $row['categoria'];
                        $proveedor = $row['proveedor'];
                        $precio = $row['precio'];
                        $unidades = $row['unidades'];
                        $cantidad = $row['cantidad'];
                        $fecha_ingreso = $row['fecha_ingreso'];
                        $fecha_vencimiento = $row['fecha_vencimiento'];
                        ?>

                        <tr>
                            <th scope="row" id="productoID-<?= $id ?>"><?= $id ?></th>
                            <td id="nombre-<?= $productoID ?>"><?= $nombre ?></td>
                            <td id="categoria-<?= $id ?>"><?= $categoria ?></td>
                            <td id="proveedor-<?= $productoID ?>"><?= $proveedor ?></td>
                            <td id="precio-<?= $id ?>"><?= $precio ?></td>
                            <td id="unidades-<?= $id ?>"><?= $unidades ?></td>
                            <td id="cantidad-<?= $id ?>"><?= $cantidad ?></td>
                            <td id="fecha_ingreso-<?= $id ?>"><?= $fecha_ingreso ?></td>
                            <td id="fecha_vencimiento-<?= $id ?>"><?= $fecha_vencimiento ?></td>
                            <td>
                                <button type="button" class="btn btn-primary" style="font-size: 12px;"
                                    onclick="updateProduct(<?= $id ?>)">Editar</button>
                                <button type="button" class="btn btn-danger" style="font-size: 12px;"
                                    onclick="deleteProduct(<?= $id ?>)">Eliminar</button>
                            </td>
                        </tr>

                        <?php
                    }

                    ?>

                </tbody>
            </table>

        </div>
    </div>

    <!-- Boostrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>


    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>


    <script>
        $(document).ready(function () {
            $('.inventario').DataTable();
        });

        function updateProduct(id) {
            $("#updateProductModal").modal("show");

            let updateProductID = $("#productoID-" + id).text();
            let updatenombreP = $("#nombre-" + id).text();
            let updatecategoriaP = $("#categoria-" + id).text();
            let updateproveedor = $("#proveedor-" + id).text();
            let updateprecio = $("#precio-" + id).text();
            let updateunidades = $("#unidades-" + id).text();
            let updatecantidad = $("#cantidad-" + id).text();
            let updatefecha_ingreso = $("#fecha_ingreso-" + id).text();
            let updatefecha_vencimiento = $("#fecha_vencimiento-" + id).text();

            $("#updateProductID").val(updateProductID);
            $("#updatenombreP").val(updatenombreP);
            $("#updatecategoriaP").val(updatecategoriaP);
            $("#updateproveedor").val(updateproveedor);
            $("#updateprecio").val(updateprecio);
            $("#updateunidades").val(updateunidades);
            $("#updatecantidad").val(updatecantidad);
            $("#updatefecha_ingreso").val(updatefecha_ingreso);
            $("#updatefecha_vencimiento").val(updatefecha_vencimiento);
        }

        function deleteProduct(id) {
            if (confirm('Deseas eliminar este producto?')) {
                window.location.href = "../procesos/borraInv.php?producto=" + id;
            }
        }
    </script>

</body>

<!-- Pie de página -->
<footer class="text-center text-white p-3" style="background-color: orange;">
    <div class="text-center">
        © Copyright 2024 | PanificApp
    </div>
</footer>

</html>