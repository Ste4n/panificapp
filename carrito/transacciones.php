<?php
// Incluir el archivo de configuración de la base de datos
include 'Configuracion.php';

// Inicializar la clase del carrito de compras
include 'La-carta.php';
$cart = new Cart;

// Redirigir a inicio si el carrito está vacío
if ($cart->total_items() <= 0) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['usuario'] ?? null;

if ($user) {
    $sql = "SELECT id, nombre, email, direccion, telefono, usuario, password FROM usuarios WHERE usuario=:usuario";
    $resultado = $db->prepare($sql);
    $resultado->execute([':usuario' => $user]);

    $data = $resultado->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        $id = $data['id'];
        $nombre = $data['nombre'];
        $email = $data['email'];
        $direccion = $data['direccion'];
        $telefono = $data['telefono'];
        $usuario = $data['usuario'];
        $password = $data['password'];

        // Asignar el ID del usuario de la sesión actual a la variable de sesión 'sessCustomerID'
        $_SESSION['sessCustomerID'] = $id;
    } else {
        echo "Usuario no encontrado.";
        exit;
    }
} else {
    // Manejar el caso donde no hay usuario autenticado
    echo "No hay usuario autenticado.";
    exit;
}

// Obtener los detalles del cliente por el ID del cliente de la sesión
$query = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
$query->execute([':id' => $_SESSION['sessCustomerID']]);
$custRow = $query->fetch(PDO::FETCH_ASSOC);

// Procesar el formulario de pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $rut = $_POST['rut'];

    // Validar RUT (lógica simplificada, puedes mejorarla)
    if (!preg_match("/^[0-9]+-[0-9kK]{1}$/", $rut)) {
        $error_rut = "RUT inválido";
    } else {
        // Guardar datos de pago en la base de datos
        $sql_insert = "INSERT INTO pagos (cliente_id, numero_tarjeta, rut, monto)
                       VALUES (:cliente_id, :numero_tarjeta, :rut, :monto)";
        $stmt_insert = $db->prepare($sql_insert);
        $stmt_insert->execute([
            'cliente_id' => $_SESSION['sessCustomerID'],
            'numero_tarjeta' => $numero_tarjeta,
            'rut' => $rut,
            'monto' => $cart->total()
        ]);

        echo '<script>alert("Pago realizado con éxito"); window.location = "AccionCarta.php?action=placeOrder";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pagos</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            padding: 20px;
        }

        .table {
            width: 65%;
            float: left;
        }

        .shipAddr {
            width: 30%;
            float: left;
            margin-left: 30px;
        }

        .footBtn {
            width: 95%;
            float: left;
        }

        .orderBtn {
            float: right;
        }

        .webpay-logo {
            width: 150px;
            margin-top: 20px;
        }

        .panel-default {
            border-color: #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .panel-body {
            padding: 25px;
        }

        .btn-primary {
            background-color: #005baa;
            border-color: #005baa;
        }

        .btn-primary:hover {
            background-color: #004a8b;
            border-color: #004a8b;
        }

        footer {
            background-color: #005baa;
            color: white;
            padding: 10px 0;
        }

        footer .text-center {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1>Vista previa de la Orden</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Sub total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($cart->total_items() > 0) {
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) { ?>
                                <tr>
                                    <td><?php echo $item["nombre"]; ?></td>
                                    <td><?php echo '$' . $item["precio"] . ' CLP'; ?></td>
                                    <td><?php echo $item["qty"]; ?></td>
                                    <td><?php echo '$' . $item["subtotal"] . ' CLP'; ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4">
                                    <p>No hay artículos en tu carrito......</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <?php if ($cart->total_items() > 0) { ?>
                                <td class="text-center"><strong>Total <?php echo '$' . $cart->total() . ' CLP'; ?></strong></td>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
                <div class="shipAddr">
                    <h4>Detalles de envío</h4>
                    <p><?php echo $custRow['nombre']; ?></p>
                    <p><?php echo $custRow['email']; ?></p>
                    <p><?php echo $custRow['telefono']; ?></p>
                    <p><?php echo $custRow['direccion']; ?></p>
                </div>
                <div class="footBtn">
                    <a href="index.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continuar Comprando</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Información de Pago</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="numero_tarjeta">Número de Tarjeta</label>
                        <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required>
                    </div>
                    <div class="form-group">
                        <label for="rut">RUT</label>
                        <input type="text" class="form-control" id="rut" name="rut" required>
                        <?php if (isset($error_rut)) { echo '<div class="alert alert-danger">' . $error_rut . '</div>'; } ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Completar Orden</button>
                </form>
                <div class="text-center">
                    <h4>Pago seguro con WebPay</h4>
                    <img src="../img/webpay.png" alt="WebPay Logo" class="webpay-logo">
                </div>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="text-center">
        <div class="text-center p-3">
            © Copyright 2024 | WebPay
        </div>
    </footer>
</body>

</html>