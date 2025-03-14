-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-03-2025 a las 22:50:28
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `superete`
--
CREATE DATABASE IF NOT EXISTS `superete` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `superete`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajadiaria`
--

DROP TABLE IF EXISTS `cajadiaria`;
CREATE TABLE `cajadiaria` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `saldo_inicial` decimal(10,2) NOT NULL,
  `saldo_final` decimal(10,2) NOT NULL,
  `abierta_por_id` int(11) NOT NULL,
  `cerrada_por_id` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cajadiaria`
--

INSERT INTO `cajadiaria` (`id`, `fecha`, `saldo_inicial`, `saldo_final`, `abierta_por_id`, `cerrada_por_id`, `observaciones`) VALUES
(2, '2025-03-14', 300000.00, 500000.00, 1, 2, 'Todo al día'),
(3, '2025-03-14', 500000.00, 10000.00, 1, 2, 'Se resta dinero para compra de productos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Prueba1'),
(2, 'Prueba2'),
(4, 'Prueba3'),
(5, 'Prueba3'),
(6, 'Prueba3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecaja`
--

DROP TABLE IF EXISTS `detallecaja`;
CREATE TABLE `detallecaja` (
  `id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  `transaccion_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detallecaja`
--

INSERT INTO `detallecaja` (`id`, `caja_id`, `transaccion_id`, `descripcion`, `monto`) VALUES
(1, 2, 2, 'Se venta 2 kilos de arroz', 2000.00),
(4, 2, 2, 'Se venta 2 kilos de café', 2000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `categoria_id`, `precio_compra`, `precio_venta`, `stock`) VALUES
(1, 'Café', 1, 20000.00, 25000.00, 3),
(2, 'Queso', 1, 20000.00, 25000.00, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaccion`
--

DROP TABLE IF EXISTS `transaccion`;
CREATE TABLE `transaccion` (
  `id` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `transaccion`
--

INSERT INTO `transaccion` (`id`, `tipo`, `producto_id`, `cantidad`, `precio_unitario`, `fecha`, `usuario_id`) VALUES
(2, 'Compra', 1, 6, 20000.00, '2025-03-14 18:16:01', 4),
(4, 'Venta', 1, 2, 20000.00, '2025-03-14 18:16:01', 1),
(5, 'Compra', 1, 2, 20000.00, '2025-03-14 18:16:01', 4),
(6, 'Compra', 1, 6, 20000.00, '2025-03-14 18:16:01', 4),
(7, 'Compra', 1, 50, 20000.00, '2025-03-14 18:16:01', 4),
(8, 'salida', 1, 50, 20000.00, '2025-03-14 18:16:01', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(128) NOT NULL,
  `roles` enum('administrador','caja') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `cedula`, `username`, `password`, `roles`) VALUES
(1, '1083896498', 'candresterg', '12345', 'administrador'),
(2, '1234567', 'alaiasterling', '123456', 'caja'),
(4, '1083896498', 'candresterg', '123456', 'administrador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajadiaria`
--
ALTER TABLE `cajadiaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cajadiaria_abierta` (`abierta_por_id`),
  ADD KEY `fk_cajadiaria_cerrada` (`cerrada_por_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detallecaja_caja` (`caja_id`),
  ADD KEY `fk_detallecaja_transaccion` (`transaccion_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_categoria` (`categoria_id`);

--
-- Indices de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaccion_producto` (`producto_id`),
  ADD KEY `fk_transaccion_usuario` (`usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajadiaria`
--
ALTER TABLE `cajadiaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cajadiaria`
--
ALTER TABLE `cajadiaria`
  ADD CONSTRAINT `fk_cajadiaria_abierta` FOREIGN KEY (`abierta_por_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_cajadiaria_cerrada` FOREIGN KEY (`cerrada_por_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  ADD CONSTRAINT `fk_detallecaja_caja` FOREIGN KEY (`caja_id`) REFERENCES `cajadiaria` (`id`),
  ADD CONSTRAINT `fk_detallecaja_transaccion` FOREIGN KEY (`transaccion_id`) REFERENCES `transaccion` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `transaccion`
--
ALTER TABLE `transaccion`
  ADD CONSTRAINT `fk_transaccion_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `fk_transaccion_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
