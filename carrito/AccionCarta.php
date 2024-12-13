<?php
date_default_timezone_set("America/Lima");
// Iniciamos la clase de la carta
include 'La-carta.php';
$cart = new Cart;

// Incluir archivo de configuración de la base de datos
include 'Configuracion.php';

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
        $productID = $_REQUEST['id'];
        // Obtener detalles del producto
        $stmt = $db->prepare("SELECT * FROM mis_productos WHERE id = :id");
        $stmt->execute(['id' => $productID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $itemData = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'qty' => 1
        );

        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem ? 'VerCarta.php' : 'index.php';
        header("Location: " . $redirectLoc);
    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])) {
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;
    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: VerCarta.php");
    } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])) {
        // Verificar si el horario está seleccionado y es válido
        if (!empty($_POST['horario_id'])) {
            $horario_id = $_POST['horario_id'];

            // Obtener el horario seleccionado
            $sql = "SELECT * FROM horarios WHERE id = :horario_id AND estadop = 'Abierto'";
            $stmt = $db->prepare($sql);
            $stmt->execute(['horario_id' => $horario_id]);
            $horario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($horario) {
                // Insertar detalles del pedido en la base de datos
                $stmt = $db->prepare("INSERT INTO orden (cliente_id, precio_total, horario_id) VALUES (:cliente_id, :precio_total, :horario_id)");
                $result = $stmt->execute([
                    'cliente_id' => $_SESSION['sessCustomerID'],
                    'precio_total' => $cart->total(),
                    'horario_id' => $horario_id
                ]);

                if ($result) {
                    $orderID = $db->lastInsertId();
                    // Obtener los elementos del carrito
                    $cartItems = $cart->contents();

                    $stmt = $db->prepare("INSERT INTO orden_articulos (orden_id, producto_id, cantidad) VALUES (:orden_id, :producto_id, :cantidad)");
                    foreach ($cartItems as $item) {
                        $stmt->execute([
                            'orden_id' => $orderID,
                            'producto_id' => $item['id'],
                            'cantidad' => $item['qty']
                        ]);
                    }

                    // Actualizar la tabla de horarios
                    $stmt = $db->prepare("UPDATE horarios SET cagendada = cagendada + :cantidad WHERE id = :horario_id");
                    foreach ($cartItems as $item) {
                        $stmt->execute([
                            'cantidad' => $item['qty'],
                            'horario_id' => $horario_id
                        ]);
                    }

                    // Verificar si se debe cerrar el horario
                    $stmt = $db->prepare("UPDATE horarios SET estadop = 'Cerrado' WHERE id = :horario_id AND cagendada >= cproducir");
                    $stmt->execute(['horario_id' => $horario_id]);

                    $cart->destroy();
                    header("Location: OrdenExito.php?id=$orderID");
                } else {
                    header("Location: Pagos.php?error=No se pudo realizar el pedido");
                }
            } else {
                header("Location: Pagos.php?error=Horario no disponible");
            }
        } else {
            header("Location: Pagos.php?error=Horario no seleccionado");
        }
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}