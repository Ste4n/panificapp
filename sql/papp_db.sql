-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-12-2024 a las 19:29:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `papp_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costos_pan`
--

CREATE TABLE `costos_pan` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `materia_prima` decimal(10,2) NOT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `otros_costos` decimal(10,2) NOT NULL,
  `cantidad_pan` decimal(10,2) NOT NULL,
  `costo_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `costos_pan`
--

INSERT INTO `costos_pan` (`id`, `fecha`, `materia_prima`, `sueldo`, `otros_costos`, `cantidad_pan`, `costo_total`) VALUES
(1, '2024-07-12', 700.00, 350000.00, 1000.00, 200.00, 1758.50),
(2, '2024-07-13', 650.00, 340000.00, 9000.00, 200.00, 1748.25),
(3, '2024-07-13', 650.00, 340000.00, 9000.00, 200.00, 1748.25),
(4, '2024-07-14', 850.00, 370000.00, 6000.00, 200.00, 1884.25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `turno` varchar(8) NOT NULL,
  `cproducir` int(4) NOT NULL,
  `cagendada` int(4) NOT NULL,
  `estadop` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `fecha`, `turno`, `cproducir`, `cagendada`, `estadop`) VALUES
(8, '2024-11-29', 'Mañana', 200, 34, 'Abierto'),
(9, '2024-11-29', 'Once', 200, 0, 'Cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `proveedor` varchar(50) NOT NULL,
  `precio` float(10,2) NOT NULL,
  `cantidad` float NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `unidades` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `nombre`, `categoria`, `proveedor`, `precio`, `cantidad`, `fecha_ingreso`, `fecha_vencimiento`, `unidades`) VALUES
(29, 'Sal Monte', 'sal', 'Harinas Collico', 750.00, 580, '2024-11-26', '2025-09-26', 2),
(30, 'Mejorador de Pan', 'mejorador', 'Lider CL', 1700.00, 836, '2024-11-26', '2025-02-26', 4),
(31, 'Manteca Buenavista', 'manteca', 'Lider CL', 900.00, 320, '2024-11-26', '2024-12-10', 2),
(32, 'Levadura Alta', 'levadura', 'Molinos Kunstmann', 800.00, 739, '2024-11-26', '2024-12-26', 3),
(33, 'Aceite Girasol', 'aceite', 'Lider CL', 1200.00, 545, '2024-11-26', '2025-01-26', 3),
(34, 'Harina Morena', 'harina_integral', 'Harinas Collico', 9000.00, 10000, '2024-11-26', '2024-12-24', 1),
(37, 'Harina Collico', 'harina_blanca', 'Harinas Collico', 8000.00, 5000, '2024-11-28', '2024-12-20', 1),
(38, 'Harina Perla', 'harina_blanca', 'Harinas Collico', 8000.00, 10000, '2024-12-08', '2024-12-22', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mis_productos`
--

CREATE TABLE `mis_productos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` float(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mis_productos`
--

INSERT INTO `mis_productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
(17, 'Dobladitas', 'Ricas dobladitas ', 1120.00, '../uploads/sotck5.png'),
(18, 'Pan de Completo', 'Harina de trigo, Vitamina d3 vegetal, Agua, Levadura, Aceite de maravilla (tocoferoles), Gluten, Sal, Harina de trigo fermentada, Ácido láctico de origen natural, Ácido ascórbico de origen natural, Ácido sórbico, Mono y diglicéridos de ácidos grasos, Ácido cítrico, Cmc, Propionato de calcio, Suero de leche, Leche.', 1650.00, '../uploads/sotck7.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `precio_total` float(10,2) NOT NULL,
  `horario_id` int(10) NOT NULL,
  `estado` enum('pendiente','aceptado','cancelado','listo','entregado') DEFAULT 'pendiente',
  `fecha` date NOT NULL DEFAULT curdate(),
  `hora` time NOT NULL DEFAULT curtime(),
  `estado_pago` enum('pendiente','pagado','rechazado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`id`, `cliente_id`, `precio_total`, `horario_id`, `estado`, `fecha`, `hora`, `estado_pago`) VALUES
(52, 3, 1120.00, 0, 'aceptado', '2024-11-28', '16:37:06', 'pagado'),
(54, 2, 11200.00, 8, 'aceptado', '2024-11-28', '16:47:50', 'pendiente'),
(56, 8, 8250.00, 8, 'aceptado', '2024-12-04', '17:19:20', 'pendiente'),
(57, 8, 2770.00, 8, 'aceptado', '2024-12-04', '17:21:51', 'pendiente'),
(58, 8, 1650.00, 8, 'aceptado', '2024-12-04', '17:23:24', 'pendiente'),
(59, 3, 1650.00, 8, 'cancelado', '2024-12-06', '18:14:51', 'pendiente'),
(60, 3, 4950.00, 0, 'cancelado', '2024-12-06', '18:22:11', 'pendiente'),
(61, 3, 1650.00, 0, 'pendiente', '2024-12-06', '18:22:26', 'pendiente'),
(62, 3, 4950.00, 8, 'aceptado', '2024-12-06', '18:48:22', 'pendiente'),
(63, 3, 1650.00, 8, 'pendiente', '2024-12-06', '18:49:41', 'pendiente'),
(64, 3, 1650.00, 8, 'cancelado', '2024-12-06', '18:50:20', 'pendiente'),
(65, 8, 1120.00, 8, 'pendiente', '2024-12-06', '20:16:23', 'pendiente'),
(66, 8, 1650.00, 8, 'aceptado', '2024-12-08', '15:15:56', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_articulos`
--

CREATE TABLE `orden_articulos` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `orden_articulos`
--

INSERT INTO `orden_articulos` (`id`, `orden_id`, `producto_id`, `cantidad`) VALUES
(57, 52, 17, 1),
(59, 54, 17, 10),
(62, 56, 18, 5),
(63, 57, 17, 1),
(64, 57, 18, 1),
(65, 58, 18, 1),
(66, 59, 18, 1),
(67, 60, 18, 3),
(68, 61, 18, 1),
(69, 62, 18, 3),
(70, 63, 18, 1),
(71, 64, 18, 1),
(72, 65, 17, 1),
(73, 66, 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `numero_tarjeta` varchar(20) NOT NULL,
  `rut` varchar(12) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_pago` enum('Pendiente','Completado','Fallido') DEFAULT 'Pendiente',
  `metodo_pago` enum('WebPay','Transferencia','Efectivo') DEFAULT 'WebPay'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `cliente_id`, `numero_tarjeta`, `rut`, `monto`, `fecha_pago`, `estado_pago`, `metodo_pago`) VALUES
(1, 3, '34253453455', '24510130-9', 1120.00, '2024-11-28 17:10:45', 'Pendiente', 'WebPay'),
(2, 2, '34253453455', '23534458-1', 1120.00, '2024-11-28 17:44:47', 'Pendiente', 'WebPay'),
(3, 2, '34253453455', '24510130-9', 1120.00, '2024-11-28 18:48:15', 'Pendiente', 'WebPay'),
(4, 2, '34253453455', '24510130-9', 1120.00, '2024-11-28 18:53:32', 'Pendiente', 'WebPay'),
(5, 2, '34253453455', '24510130-9', 1120.00, '2024-11-28 19:03:51', 'Pendiente', 'WebPay'),
(6, 2, '34253453455', '24510130-9', 11200.00, '2024-11-28 19:46:56', 'Pendiente', 'WebPay'),
(7, 8, '34253453455', '24510130-9', 7190.00, '2024-12-04 20:17:15', 'Pendiente', 'WebPay'),
(8, 8, '34253453455', '24510130-9', 1650.00, '2024-12-04 20:23:18', 'Pendiente', 'WebPay'),
(9, 3, '34253453455', '24510098-1', 1650.00, '2024-12-06 21:22:24', 'Pendiente', 'WebPay');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `harina_blanca` float NOT NULL,
  `harina_integral` float NOT NULL,
  `sal` float NOT NULL,
  `mejorador` float NOT NULL,
  `manteca` float NOT NULL,
  `levadura` float NOT NULL,
  `agua` float NOT NULL,
  `aceite_vegetal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `producto_id`, `harina_blanca`, `harina_integral`, `sal`, `mejorador`, `manteca`, `levadura`, `agua`, `aceite_vegetal`) VALUES
(1, 17, 1000, 0, 12, 5, 40, 4, 450, 10),
(2, 18, 1000, 0, 12, 4, 40, 15, 450, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `telefono` int(9) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL,
  `id_rol` int(1) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `direccion`, `telefono`, `usuario`, `password`, `id_rol`) VALUES
(1, 'Esteban Pérez', 'esteban.perez.rtpo@gmail.com', 'calle 418', 950971087, 'eperezr', 'papp123', 1),
(2, 'Samuel Perez', 'samuel@correo.cl', 'cotapos 80', 950971086, 'sperezr', 'samuel123', 2),
(3, 'Juan Mesa', 'juan@correo.cl', 'calle 12', 912345678, 'jmesa', 'juan123', 2),
(4, 'Alexis', 'a@correo.cl', 'calle 321', 998765432, 'alex', '123', 1),
(5, 'Sol', 'sol@mail.cl', 'carrera 123', 940471077, 'sol', 'sol123', 2),
(6, 'Pedro', 'pedro@correo.cl', 'pedro aguirre cerda', 950971117, 'pedro', 'pedro123', 2),
(7, 'Esteban Perez', 'esteban@correo.inacap.cl', 'Ana Cotapos 80', 950271087, 'eperez', 'perez123', 2),
(8, 'Carlos Duarte', 'carlos.d@gmail.com', 'Ana Maria Cotapos 80', 950953025, 'cduarte', 'carlos123', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `costos_pan`
--
ALTER TABLE `costos_pan`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idx_categoria` (`categoria`);

--
-- Indices de la tabla `mis_productos`
--
ALTER TABLE `mis_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden`
--
ALTER TABLE `orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `horario_id` (`horario_id`);

--
-- Indices de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden_id_2` (`orden_id`,`producto_id`),
  ADD KEY `orden_id` (`orden_id`,`producto_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `costos_pan`
--
ALTER TABLE `costos_pan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `mis_productos`
--
ALTER TABLE `mis_productos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `orden_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  ADD CONSTRAINT `orden_articulos_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `mis_productos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
