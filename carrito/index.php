<?php
include 'Configuracion.php';
session_start();

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
<html lang="es">

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
            <h1>"Selecciona tu producto"</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>
    <!-- Sección de productos -->
    <section class="portafolio">
        <div class="contenedor">
            <!-- boton de ir al carro  -->
            <div class="search-container">
                <a href="VerCarta.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Ir al
                    carro</a>
            </div>
            <!-- Galería de productos -->
            <div class="galeria-port">
                <?php
                // Obtener filas de consulta
                $stmt = $db->query("SELECT * FROM mis_productos ORDER BY id DESC LIMIT 10");
                $result = $stmt->fetchAll();

                if (count($result) > 0) {
                    foreach ($result as $row) {
                        // Verificar si la imagen existe
                        $imagenRuta = $row['imagen'];
                        if (!file_exists($imagenRuta) || empty($imagenRuta)) {
                            $imagenRuta = 'ruta/de/imagen/por_defecto.jpg'; // Cambia esta ruta a una imagen por defecto
                        }
                        ?>
                        <div class="imagen-port">
                            <img src="<?php echo $imagenRuta; ?>" alt="<?php echo $row['nombre']; ?>">
                            <a href="AccionCarta.php?action=addToCart&id=<?php echo $row["id"]; ?>">
                                <div class="hover-galeria">
                                    <img src="../img/mover1.png" alt="">
                                    <p><?php echo $row['nombre']; ?></p>
                                </div>
                            </a>
                        </div>
                    <?php }
                } else { ?>
                    <p>No hay productos disponibles.</p>
                <?php } ?>
            </div>
        </div>
    </section>

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