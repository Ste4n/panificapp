<?php
// initializ shopping cart class
include 'La-carta.php';
$cart = new Cart;

include 'Configuracion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo '
    <script> 
    alert("Inicia sesión")
    window.location = "../../acceso.php";
    </script>
    ';
    session_destroy();
    die();
}

$user = $_SESSION['usuario'];

$sql = "SELECT id, nombre, email, direccion, telefono, usuario, password FROM usuarios WHERE usuario='$user'";
$resultado = $db->query($sql);

while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {

    $id = $data['id'];
    $nombre = $data['nombre'];
    $email = $data['email'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $usuario = $data['usuario'];
    $password = $data['password'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta etiquetas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Nuestros Productos</title>
    <!-- Icono de la pestaña -->
    <link rel="shortcut icon" href="../img/PUlogow18.png" type="../image/x-icon">
    <!-- Hoja de estilos personalizada -->
    <link rel="stylesheet" href="../css/estilos.css">
    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- jQuery (debes agregar esto) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .wave {
            height: 150px;
            overflow: hidden;
            position: relative;
        }

        .wave svg {
            height: 100%;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        footer {
            background-color: orange;
            color: white;
            padding: 10px 0;
            width: 100%;
            bottom: 0;
        }

        .container {
            padding: 20px;
        }

        input[type="number"] {
            width: 60px;
            text-align: center;
        }

        .table {
            margin-top: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .btn-danger i {
            margin-right: 5px;
        }

        .header-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
    </style>
    <script>
        function updateCartItem(obj, id) {
            $.get("AccionCarta.php", {
                action: "updateCartItem",
                id: id,
                qty: obj.value
            }, function (data) {
                if (data == 'ok') {
                    updateSubtotal(obj);
                } else {
                    alert('Cart update failed, please try again.');
                }
            });
        }

        function updateSubtotal(obj) {
            var price = parseFloat($(obj).closest('tr').find('.item-price').data('price'));
            var qty = parseInt(obj.value);
            var subtotal = price * qty;
            $(obj).closest('tr').find('.item-subtotal').text('$' + subtotal.toFixed(2) + ' CLP');

            var total = 0;
            $('.item-subtotal').each(function () {
                total += parseFloat($(this).text().replace('$', '').replace(' CLP', ''));
            });
            $('#cart-total').text('$' + total.toFixed(2) + ' CLP');
        }
    </script>
</head>

<body>
    <!-- Encabezado -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="../cliente/tuspedidos.php">Historial</a>
            <a href="../cliente/perfilUsuario.php">Mi Perfil</a>
            <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>"Carrito de compras"</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>
    <br>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <td><a href="index.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Volver
                        a la tienda</a></td>
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Sub total</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($cart->total_items() > 0) {
                            // Obtener los elementos del carrito desde la sesión
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) {
                                ?>
                                <tr>
                                    <td><?php echo $item["nombre"]; ?></td>
                                    <td class="item-price" data-price="<?php echo $item["precio"]; ?>">
                                        <?php echo '$' . $item["precio"] . ' CLP'; ?></td>
                                    <td><input type="number" class="form-control text-center"
                                            value="<?php echo $item["qty"]; ?>"
                                            onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                                    <td class="item-subtotal"><?php echo '$' . $item["subtotal"] . ' CLP'; ?></td>
                                    <td>
                                        <a href="AccionCarta.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>"
                                            class="btn btn-danger" onclick="return confirm('Confirma eliminar?')"><i
                                                class="glyphicon glyphicon-trash"></i> Eliminar</a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="5">
                                    <p class="text-center">No has solicitado ningún producto.....</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php if ($cart->total_items() > 0) { ?>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                <td colspan="1" id="cart-total"><?php echo '$' . $cart->total() . ' CLP'; ?></td>
                                <td>
                                    <a href="Pagos.php" class="btn btn-success btn-lg">Pagar <i
                                            class="glyphicon glyphicon-menu-right"></i></a>
                                </td>
                            </tr>
                        </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="text-center">
        <div class="p-3">
            © Copyright 2024 | PanificApp
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>

