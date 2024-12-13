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
            margin-bottom: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-nav {
            margin-left: auto;
        }

        .textos-header {
            margin-top: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .form-container {
            max-width: 500px;
        }

        footer {
            background-color: #ff9800;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Encabezado con navegación -->
    <header>
        <nav>
            <a href="index.html">Inicio</a>
            <a href="contacto.html">Contacto</a>
            <a href="acceso.php">Acceso</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header text-center my-4" style="margin-top: -50px;">
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
        <!-- Sección de registro -->
        <div class="form-container">
            <h2 class="mb-4 text-center">Registrarse</h2>
            <form action="php/registro_usuario_be.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">*Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">*Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Ejemplo@gmail.com"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">*Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Dirección" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">*Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" placeholder="988817316" required>
                        </div>
                        <div class="form-group">
                            <label for="usuario">*Usuario</label>
                            <input type="text" class="form-control" name="usuario" placeholder="Tu Usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="password">*Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="min 6 digitos"
                                required>
                        </div>
                    </div>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="terminos" required>
                    <label class="form-check-label" for="terminos">Acepto los términos <a href="#"
                            data-toggle="modal" data-target="#terminosModal">Terms of Use</a> y <a href="#"
                            data-toggle="modal" data-target="#privacidadModal">Privacy</a></label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Registrar</button>
            </form>
        </div>
    </div>

    <!-- Modal de Términos de Uso -->
    <div class="modal fade" id="terminosModal" tabindex="-1" role="dialog" aria-labelledby="terminosModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminosModalLabel">Términos de Uso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí coloca los términos de uso -->
                    <p>Estos términos de uso ("Términos") rigen el acceso y uso de nuestro servicio PanificApp
                        ("Servicio"), ofrecido por [Tu Empresa] ("nosotros", "nuestro" o "nos"). Al acceder o utilizar
                        el Servicio, usted acepta estar sujeto a estos Términos.</p>
                    <h2>Uso del Servicio</h2>
                    <p>El Servicio está destinado a usuarios en Chile y debe ser utilizado conforme a las leyes
                        chilenas. Usted es responsable de su uso del Servicio y de cualquier consecuencia que se derive
                        de dicho uso. No debe utilizar el Servicio para actividades ilícitas o para violar los derechos
                        de otros.</p>
                    <h2>Propiedad Intelectual</h2>
                    <p>Todo el contenido disponible en el Servicio, incluyendo textos, gráficos, logotipos, imágenes,
                        compilaciones de datos y software, es propiedad de [Tu Empresa] y está protegido por las leyes
                        de propiedad intelectual chilenas e internacionales.</p>
                    <h2>Limitación de Responsabilidad</h2>
                    <p>El Servicio se proporciona "tal cual" y "según disponibilidad", sin garantías de ningún tipo, ya
                        sean expresas o implícitas. No garantizamos que el Servicio sea ininterrumpido, seguro o esté
                        libre de errores.</p>
                    <h2>Modificaciones</h2>
                    <p>Nos reservamos el derecho de modificar estos Términos en cualquier momento. Cualquier cambio será
                        efectivo inmediatamente después de su publicación en el Servicio. Es su responsabilidad revisar
                        los Términos periódicamente.</p>
                    <h2>Jurisdicción y Ley Aplicable</h2>
                    <p>Estos Términos se rigen por las leyes de Chile. Cualquier disputa que surja en relación con estos
                        Términos se resolverá exclusivamente en los tribunales de Chile.</p>
                    <p>Si tiene alguna pregunta sobre estos Términos, por favor contacte a [correo electrónico de
                        contacto].</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Política de Privacidad -->
    <div class="modal fade" id="privacidadModal" tabindex="-1" role="dialog" aria-labelledby="privacidadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacidadModalLabel">Política de Privacidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí coloca la política de privacidad -->
                    <p>En [Tu Empresa], valoramos su privacidad y estamos comprometidos a proteger su información
                        personal. Esta política de privacidad explica cómo recopilamos, utilizamos, compartimos y
                        protegemos su información cuando utiliza nuestro servicio PanificApp ("Servicio").</p>
                    <h2>Información que Recopilamos</h2>
                    <p>Podemos recopilar la siguiente información:</p>
                    <ul>
                        <li>Información de contacto, como nombre, correo electrónico y número de teléfono.</li>
                        <li>Información de ubicación, si ha autorizado la recopilación de la misma.</li>
                        <li>Datos de uso, que incluyen cómo utiliza el Servicio.</li>
                    </ul>
                    <h2>Uso de la Información</h2>
                    <p>Utilizamos la información recopilada para los siguientes propósitos:</p>
                    <ul>
                        <li>Proporcionar y mejorar el Servicio.</li>
                        <li>Comunicarnos con usted, responder a sus consultas y proporcionarle actualizaciones del
                            Servicio.</li>
                        <li>Cumplir con obligaciones legales.</li>
                    </ul>
                    <h2>Compartición de Información</h2>
                    <p>No compartimos su información personal con terceros, excepto según lo requerido por la ley o con
                        su consentimiento explícito.</p>
                    <h2>Seguridad de la Información</h2>
                    <p>Tomamos medidas razonables para proteger su información personal contra acceso no autorizado,
                        pérdida, uso indebido o alteración.</p>
                    <h2>Derechos del Usuario</h2>
                    <p>De acuerdo con la ley chilena de protección de datos, usted tiene derecho a acceder, corregir o
                        eliminar su información personal. Para ejercer estos derechos, por favor contacte a [correo
                        electrónico de contacto].</p>
                    <h2>Cambios a esta Política</h2>
                    <p>Podemos actualizar esta política de privacidad de vez en cuando. Cualquier cambio será publicado
                        en esta página, y es su responsabilidad revisar esta política periódicamente.</p>
                    <p>Si tiene alguna pregunta sobre esta política de privacidad, por favor contacte a [correo
                        electrónico de contacto].</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome para iconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>
