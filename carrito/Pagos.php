<!-- Nuevo Código de Pagos -->
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

// Obtener horarios disponibles
$sql = "SELECT * FROM horarios WHERE estadop = 'Abierto'";
$stmt = $db->prepare($sql);
$stmt->execute();
$horariosDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($horariosDisponibles)) {
    echo "No hay horarios disponibles.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta etiquetas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Su Pedido</title>
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

        .galeria-port .imagen-port {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .galeria-port .imagen-port img {
            width: 100%;
            height: auto;
            display: block;
        }

        .galeria-port .hover-galeria {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .galeria-port .hover-galeria img {
            width: 50px;
            height: 50px;
        }

        .galeria-port .hover-galeria p {
            margin-left: 10px;
            font-size: 1.2em;
        }

        .galeria-port .imagen-port:hover .hover-galeria {
            opacity: 1;
        }
    </style>
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
            <h1>Los Detalles de su Pedido</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
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
        </style>
    </header>


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
                        <?php
                        if ($cart->total_items() > 0) {
                            // obtener ítems del carrito de la sesión
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) {
                                ?>
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
                            <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <?php if ($cart->total_items() > 0) { ?>
                                <td class="text-center"><strong>Total
                                        <?php echo '$' . $cart->total() . ' CLP'; ?></strong>
                                </td>
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

                <!-- Horarios disponibles -->
                <form method="post" action="AccionCarta.php">
                    <div class="form-group">
                        <label for="horario_id">Selecciona un horario:</label>
                        <select name="horario_id" id="horario_id" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $result = $db->query("SELECT * FROM horarios WHERE estadop = 'Abierto'");
                            if ($result->rowCount() > 0) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['fecha'] . ' - ' . $row['turno'] . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay horarios disponibles</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="action" value="placeOrder">
                    <div class="footBtn">
                        <a href="index.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i>
                            Continuar Comprando</a>
                        <input type="submit" name="checkoutSubmit" value="Realizar pedido"
                            class="btn btn-success orderBtn" id="checkoutBtn" >
                    </div>
                </form>
            </div>

        </div>

        <!-- Panel cierra -->
    </div>
</body>
<!-- Pie de página -->
<footer class="text-center text-white" style="background-color: orange;">
    <div class="text-center p-3">
        © Copyright 2024 | PanificApp:
    </div>
</footer>

</html>