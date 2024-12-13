<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso</title>
    <!-- Favicon del sitio web -->
    <link rel="shortcut icon" href="img/PUlogow18.png" type="image/x-icon">
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Optimización para la carga de fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin: 0 auto; /* Centramos el formulario */
            max-width: 400px; /* Ancho máximo del formulario */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-register {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px; /* Ajustamos el tamaño del logo */
            margin-right: 10px; /* Espacio entre el logo y el texto */
        }

        .navbar-nav {
            margin-left: auto;
        }

        .textos-header {
            margin-top: 20px; /* Ajuste para subir el título y el logo */
        }
    </style>
</head>

<body>
    <!-- Encabezado con navegación -->
    <header>
        <nav>
            <a href="index.html">Inicio</a>
            <a href="contacto.html">Contacto</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header text-center my-4" style="margin-top: -50px;"> <!-- Ajuste de margen -->
            <img src="img/Logo_PU-removebg-preview.png" alt="Logo" class="imagen-titulo">
            <h1>PanificApp</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <div class="form-container">
            <h2 class="mb-4 text-center">Iniciar sesión</h2>
            <form action="php/login_usuario_be.php" method="post">
                <div class="form-group">
                    <label for="user_login">Usuario</label>
                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <label for="password_login">Contraseña</label>
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="recordarme">
                    <label class="form-check-label" for="recordarme">Recordarme</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                <a href="registro.php" class="btn btn-register btn-block mt-3">¿No tienes cuenta? Regístrate</a>
            </form>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="text-center mt-5" style="background-color:#ff9800;">
        <h2 class="titulo-final">&copy; Copyright 2024 | PanificApp</h2>
    </footer>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- FontAwesome para iconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
