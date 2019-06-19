-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2019 a las 00:31:28
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 5.6.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `resto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `calMesa` int(2) NOT NULL,
  `calResto` int(2) NOT NULL,
  `calMozo` int(2) NOT NULL,
  `calCocinero` int(2) NOT NULL,
  `opinion` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`ID`, `codigo`, `calMesa`, `calResto`, `calMozo`, `calCocinero`, `opinion`) VALUES
(1, '7PT71', 8, 9, 10, 10, 'Muy buena comida, mala ubicacion de la mesa '),
(2, '4V8AP', 7, 7, 7, 7, 'Buena atencion'),
(3, '2QVVZ', 1, 2, 1, 1, 'mal lugar'),
(27, '2QVy', 10, 9, 9, 10, 'muy buena comida y atencion'),
(28, '2QVt', 10, 9, 9, 10, 'muy buena comida y atencion'),
(30, '2QVkl', 10, 9, 9, 10, 'muy buena comida y atencion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `ID` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `precio` int(11) NOT NULL,
  `sector` enum('Cerveceria','Cocina','Bartender','Candybar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`ID`, `nombre`, `precio`, `sector`) VALUES
(1, 'empanada', 25, 'Cocina'),
(2, 'spaguettis con salsa blanca', 120, 'Cocina'),
(3, 'lomo a la pimienta con papas', 220, 'Cocina'),
(4, 'milanesa napolitana', 125, 'Cocina'),
(5, 'calzone vegetariano', 145, 'Cocina'),
(6, 'creppe de espinaca', 200, 'Cocina'),
(7, 'matambre de cerdo a la pizza', 320, 'Cocina'),
(8, 'salmon grille', 235, 'Cocina'),
(9, 'tortilla española', 90, 'Cocina'),
(10, 'hamburguesa completa', 200, 'Cocina'),
(11, 'selva negra', 75, 'Candybar'),
(12, 'tiramisu', 100, 'Candybar'),
(13, 'mousse de chocolate', 120, 'Candybar'),
(14, 'torta mil hojas', 125, 'Candybar'),
(15, 'frutillas con crema', 150, 'Candybar'),
(16, 'capelina dos sabores', 200, 'Candybar'),
(17, 'budin con frutas', 60, 'Candybar'),
(18, 'chesse cake', 235, 'Candybar'),
(19, 'apple cake', 105, 'Candybar'),
(20, 'flan casero', 150, 'Candybar'),
(21, 'coca-cola', 50, 'Bartender'),
(22, 'agua saborizada', 40, 'Bartender'),
(23, 'agua', 35, 'Bartender'),
(24, 'whisky Jack Daniels', 160, 'Bartender'),
(25, 'Whisky Chivas', 150, 'Bartender'),
(26, 'Cerveza Brahma', 100, 'Cerveceria'),
(27, 'cerveza negra brahma', 120, 'Cerveceria'),
(28, 'Cerveza roja', 120, 'Cerveceria'),
(29, 'cerveza Heiniken', 150, 'Cerveceria'),
(30, 'coca-cola light', 50, 'Bartender'),
(31, 'fanta', 50, 'Bartender'),
(32, 'pepsi', 45, 'Bartender'),
(33, 'sprite', 45, 'Bartender'),
(34, 'vino blanco', 100, 'Bartender'),
(35, 'vino tinto', 130, 'Bartender'),
(36, 'chandon', 300, 'Bartender');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `estado` enum('Con clientes esperando pedido','Con clientes comiendo','Con clientes Pagando','Cerrada') NOT NULL,
  `limpia` enum('true','false') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `codigo`, `estado`, `limpia`) VALUES
(1, 'SA001', 'Con clientes comiendo', 'true'),
(2, 'SA002', 'Con clientes comiendo', 'true'),
(3, 'SA003', 'Cerrada', 'true'),
(4, 'SA004', 'Cerrada', 'true'),
(5, 'SA005', 'Cerrada', 'true'),
(6, 'SA006', 'Cerrada', 'true'),
(7, 'SA007', 'Cerrada', 'true'),
(8, 'SA008', 'Cerrada', 'true'),
(9, 'SA009', 'Cerrada', 'true'),
(10, 'SA010', 'Con clientes esperando pedido', 'true');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mozo`
--

CREATE TABLE `mozo` (
  `ID` int(11) NOT NULL,
  `codPedido` varchar(5) NOT NULL,
  `codVenta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  `mesa` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `estado` enum('pendiente','en preparacion','listo para servir','cancelado','entregado') NOT NULL,
  `total` int(11) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `demora` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`ID`, `codigo`, `cliente`, `mesa`, `mozo`, `estado`, `total`, `horaInicio`, `horaFin`, `demora`, `fecha`) VALUES
(1, '4V8AP', 'Raul', 2, 20, 'entregado', 510, '16:11:44', '17:01:44', 50, '2018-12-09'),
(2, '7PT71', 'Raul', 1, 20, 'pendiente', 500, '23:26:21', '00:00:00', 0, '2019-02-12'),
(4, '2QVVZ', 'Ricardo', 2, 5, 'en preparacion', 250, '21:51:59', '22:01:59', 10, '2019-02-27'),
(5, '2QVV', 'Ricardo', 2, 5, 'en preparacion', 1125, '00:07:30', '00:00:00', 0, '2019-02-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `ID` int(11) NOT NULL,
  `usuario` varchar(30) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `pass` varchar(60) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `puesto` enum('Mozo','Bartender','Cocinero','Cervecero','Socio','') NOT NULL,
  `estado` enum('Activo','Suspendido','Inactivo','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`ID`, `usuario`, `pass`, `nombre`, `apellido`, `puesto`, `estado`) VALUES
(1, 'ricardo93', '$2y$12$hAI47bnmVTzitO15iilqe.cVfN6IZe/r7wQkM9tGux5ZOlZSDaT0K', 'Ricardo', 'Maldonado', 'Mozo', 'Activo'),
(2, 'raul79', '$2y$12$L45M11/u5wOh7e4vnCje7.AxaIDgWdEE3LeLirJFx0bj4RClg91eW', 'Raul', 'Dominguez', 'Mozo', 'Activo'),
(3, 'mariamarta', '$2y$12$1Yd8zOYDm2VSwvKKBuL8oOzjU0FyZjBmEoA87hD6Y/.W410HSGQ5G', 'Maria', 'Marta', 'Bartender', 'Activo'),
(4, 'admin', '$2y$12$bZRHCrov.szngEm5.tAH8ezYTKqiRDJ2eL4ZPNWs3Z7Whu9oHtf3K', 'Marcos', 'Marin', 'Socio', 'Activo'),
(5, 'micaalbani', '$2y$12$eil69I4DSDkIcpvVs47kjOdyHZKVR6wZHNRK/xNYHotmgLuNyR0zC', 'Micaela', 'Albani', 'Mozo', 'Inactivo'),
(20, 'hernan', '$2y$12$fbxsl9bzQ8QD70htw.KRpOHumpj1muANR.NXx.tJl/sgWrZE4mmNK', 'Hernan', 'Dieguez', 'Mozo', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productopedido`
--

CREATE TABLE `productopedido` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('pendiente','en preparacion','listo para servir','cancelado','entregado') NOT NULL,
  `total` int(11) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `demora` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productopedido`
--

INSERT INTO `productopedido` (`ID`, `codigo`, `idProducto`, `cantidad`, `estado`, `total`, `horaInicio`, `horaFin`, `demora`, `fecha`) VALUES
(7, '4V8AP', 3, 1, 'entregado', 220, '16:11:44', '17:01:44', 50, '2018-12-09'),
(8, '4V8AP', 10, 1, 'cancelado', 200, '16:11:44', '16:36:44', 25, '2018-12-09'),
(9, '4V8AP', 9, 1, 'cancelado', 90, '16:11:44', '16:21:44', 10, '2018-12-09'),
(10, '7PT71', 3, 1, 'pendiente', 220, '23:26:21', '00:00:00', 0, '2019-02-12'),
(11, '7PT71', 10, 1, 'pendiente', 200, '23:26:21', '00:00:00', 0, '2019-02-12'),
(12, '7PT71', 9, 1, 'pendiente', 90, '23:26:21', '00:00:00', 0, '2019-02-12'),
(13, '2QVVZ', 2, 1, 'entregado', 120, '21:51:59', '22:01:59', 10, '2019-02-27'),
(14, '2QVVZ', 35, 1, 'entregado', 130, '21:51:59', '00:00:00', 0, '2019-02-27'),
(15, '8QPI9', 2, 1, 'pendiente', 120, '22:55:38', '00:00:00', 0, '2019-02-27'),
(16, '2QVVZ', 4, 1, 'entregado', 125, '00:01:09', '00:00:00', 0, '2019-02-28'),
(17, '2QVVZ', 4, 1, 'entregado', 125, '00:04:10', '00:00:00', 0, '2019-02-28'),
(18, '2QVVZ', 4, 1, 'entregado', 125, '00:04:40', '00:00:00', 0, '2019-02-28'),
(19, '2QVVZ', 4, 1, 'cancelado', 125, '00:06:04', '00:00:00', 0, '2019-02-28'),
(20, '2QVVZ', 4, 1, 'entregado', 125, '00:06:30', '00:00:00', 0, '2019-02-28'),
(21, '2QVVZ', 4, 1, 'entregado', 125, '00:06:57', '00:00:00', 0, '2019-02-28'),
(22, '2QVVZ', 4, 1, 'entregado', 125, '00:07:30', '00:00:00', 0, '2019-02-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `ID` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `metodo` varchar(10) NOT NULL,
  `ruta` varchar(30) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`ID`, `usuario`, `metodo`, `ruta`, `fecha`) VALUES
(11, 'admin', 'GET', 'api/empleado', '2019-02-18'),
(12, 'admin', 'POST', 'api/login', '2019-02-18'),
(13, 'admin', 'POST', 'api/empleado', '2019-02-18'),
(14, 'micaalbani', 'POST', 'api/login', '2019-02-18'),
(15, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(16, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(17, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(18, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(19, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(20, 'micaalbani', 'POST', 'api/login', '2019-02-18'),
(21, 'micaalbani', 'POST', 'api/login', '2019-02-18'),
(22, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(23, 'micaalbani', 'POST', 'api/empleado', '2019-02-18'),
(24, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(25, 'micaalbani', 'POST', 'api/login', '2019-02-19'),
(26, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(27, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(28, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(29, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(30, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(31, 'micaalbani', 'POST', 'api/login', '2019-02-19'),
(32, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(33, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(34, 'micaalbani', 'POST', 'api/login', '2019-02-19'),
(35, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(36, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(37, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(38, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(39, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(40, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(41, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(42, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(43, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(44, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(45, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(46, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(47, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(48, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(49, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(50, 'micaalbani', 'DELETE', 'api/empleado/', '2019-02-19'),
(51, 'micaalbani', 'PUT', 'api/empleado/cambiarEstado/', '2019-02-19'),
(52, 'micaalbani', 'PUT', 'api/empleado/cambiarPuesto', '2019-02-19'),
(53, 'micaalbani', 'PUT', 'api/empleado/suspender', '2019-02-19'),
(54, 'micaalbani', 'PUT', 'api/empleado/', '2019-02-19'),
(55, 'micaalbani', 'PUT', 'api/empleado/', '2019-02-19'),
(56, 'admin', 'GET', 'api/empleado', '2019-02-19'),
(57, 'micaalbani', 'GET', 'api/empleado', '2019-02-19'),
(58, 'micaalbani', 'POST', 'api/empleado', '2019-02-19'),
(59, 'micaalbani', 'POST', 'api/pedido', '2019-02-19'),
(60, 'micaalbani', 'POST', 'api/pedido', '2019-02-19'),
(61, 'micaalbani', 'POST', 'api/pedido', '2019-02-19'),
(62, 'micaalbani', 'POST', 'api/pedido', '2019-02-19'),
(63, 'micaalbani', 'POST', 'api/pedido', '2019-02-19'),
(64, 'admin', 'POST', 'api/login', '2019-02-20'),
(65, 'admin', 'GET', 'api/pedido/', '2019-02-20'),
(66, 'admin', 'POST', 'api/login', '2019-02-27'),
(67, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(68, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(69, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(70, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(71, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(72, 'admin', 'POST', 'api/login', '2019-02-27'),
(73, 'admin', 'GET', 'api/mesa/listado', '2019-02-27'),
(74, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(75, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(76, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(77, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(78, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(79, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(80, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(81, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(82, 'admin', 'PUT', 'api/pedido', '2019-02-27'),
(83, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-27'),
(84, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-27'),
(85, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-27'),
(86, 'admin', 'PUT', 'api/pedido/servir', '2019-02-27'),
(87, 'admin', 'POST', 'api/login', '2019-02-27'),
(88, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(89, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(90, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(91, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(92, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(93, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(94, 'admin', 'POST', 'api/pedido', '2019-02-27'),
(95, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-27'),
(96, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-27'),
(97, 'admin', 'POST', 'api/login', '2019-02-27'),
(98, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-27'),
(99, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-27'),
(100, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(101, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(102, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(103, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(104, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(105, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(106, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(107, 'admin', 'PUT', 'api/pedido/agregar', '2019-02-28'),
(108, 'admin', 'POST', 'api/login', '2019-02-28'),
(109, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(110, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(111, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(112, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(113, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(114, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(115, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(116, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(117, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(118, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(119, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(120, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(121, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(122, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(123, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(124, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(125, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(126, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(127, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(128, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(129, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(130, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(131, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(132, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(133, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(134, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(135, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(136, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(137, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(138, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(139, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(140, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(141, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(142, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(143, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(144, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(145, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(146, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(147, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(148, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(149, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(150, 'admin', 'POST', 'api/login', '2019-02-28'),
(151, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(152, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(153, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(154, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(155, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(156, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(157, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(158, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(159, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(160, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(161, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(162, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(163, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(164, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(165, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(166, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(167, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(168, 'admin', 'GET', 'api/pedido/', '2019-02-28'),
(169, 'admin', 'GET', 'api/pedido/sector/cocina', '2019-02-28'),
(170, 'admin', 'GET', 'api/pedido/sector/bartender', '2019-02-28'),
(171, 'admin', 'GET', 'api/pedido/sector/a', '2019-02-28'),
(172, 'admin', 'GET', 'api/pedido/sector/bartender', '2019-02-28'),
(173, 'admin', 'GET', 'api/pedido/sector/cerveceria', '2019-02-28'),
(174, 'admin', 'GET', 'api/pedido/estado/enPreparacio', '2019-02-28'),
(175, 'admin', 'GET', 'api/pedido/estado/enPreparacio', '2019-02-28'),
(176, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(177, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(178, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(179, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(180, 'admin', 'POST', 'api/login', '2019-02-28'),
(181, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(182, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(183, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(184, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(185, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(186, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(187, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(188, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(189, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(190, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(191, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(192, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(193, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(194, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(195, 'admin', 'POST', 'api/login', '2019-02-28'),
(196, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(197, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(198, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(199, 'admin', 'DELETE', 'api/pedido/entregado', '2019-02-28'),
(200, 'admin', 'POST', 'api/login', '2019-04-10'),
(201, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(202, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(203, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(204, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(205, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(206, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(207, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(208, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(209, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(210, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(211, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(212, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(213, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(214, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(215, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(216, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(217, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(218, 'admin', 'GET', 'api/mesa/listado/mayorFacturac', '2019-04-10'),
(219, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(220, 'admin', 'GET', 'api/mesa/listado/menorFacturac', '2019-04-10'),
(221, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-10'),
(222, 'admin', 'GET', 'api/mesa/listado/menorFacturac', '2019-04-10'),
(223, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-10'),
(224, 'admin', 'GET', 'api/mesa/listado/masUsada', '2019-04-10'),
(225, 'admin', 'GET', 'api/mesa/listado/masUsada', '2019-04-10'),
(226, 'admin', 'GET', 'api/mesa/listado/masUsada', '2019-04-10'),
(227, 'admin', 'GET', 'api/mesa/listado/menosUsada', '2019-04-10'),
(228, 'admin', 'GET', 'api/mesa/listado/masUsada', '2019-04-10'),
(229, 'admin', 'GET', 'api/mesa/listado/menosUsada', '2019-04-10'),
(230, 'admin', 'GET', 'api/mesa/listado/menosUsada', '2019-04-10'),
(231, 'admin', 'POST', 'api/login', '2019-04-11'),
(232, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(233, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(234, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(235, 'admin', 'GET', 'api/mesa/listado/menorCalifica', '2019-04-11'),
(236, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(237, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(238, 'admin', 'GET', 'api/mesa/listado/mayorCalifica', '2019-04-11'),
(239, 'admin', 'GET', 'api/mesa/listado/menorCalifica', '2019-04-11'),
(240, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-23'),
(241, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-23'),
(242, 'admin', 'GET', 'api/mesa/listado/mayorImporte', '2019-04-23'),
(243, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(244, 'admin', 'POST', 'api/login', '2019-04-23'),
(245, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(246, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(247, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(248, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(249, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(250, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(251, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(252, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(253, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(254, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(255, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(256, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(257, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(258, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(259, 'ricardomaldonado2', 'POST', 'api/login', '2019-04-23'),
(260, 'ricardomaldonado2', 'POST', 'api/login', '2019-04-23'),
(261, 'ricardomaldonado24', 'POST', 'api/login', '2019-04-23'),
(262, 'ricardomaldonado24', 'POST', 'api/login', '2019-04-23'),
(263, 'admin', 'POST', 'api/login', '2019-04-23'),
(264, 'ricardomaldonado24', 'POST', 'api/login', '2019-04-23'),
(265, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(266, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(267, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(268, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(269, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(270, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(271, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(272, 'asdasd', 'POST', 'api/login', '2019-04-23'),
(273, 'ricardomaldonado', 'POST', 'api/login', '2019-04-23'),
(274, 'ricardom', 'POST', 'api/login', '2019-04-23'),
(275, 'ricardo', 'POST', 'api/login', '2019-04-23'),
(276, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(277, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(278, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(279, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(280, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(281, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(282, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(283, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(284, 'ricardo932', 'POST', 'api/login', '2019-04-23'),
(285, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(286, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(287, 'ricardo93', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(288, 'ricardo93', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(289, 'admin', 'POST', 'api/login', '2019-04-23'),
(290, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(291, 'ricardo93', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(292, 'ricardo93', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(293, 'admin', 'POST', 'api/login', '2019-04-23'),
(294, 'admin', 'POST', 'api/login', '2019-04-23'),
(295, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(296, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(297, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(298, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(299, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(300, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(301, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(302, 'admin', 'GET', 'api/mesa/listado/menorImporte', '2019-04-23'),
(303, 'admin', 'POST', 'api/login', '2019-04-23'),
(304, 'admin', 'POST', 'api/login', '2019-04-23'),
(305, 'ricardo93', 'POST', 'api/login', '2019-04-23'),
(306, 'admin', 'POST', 'api/login', '2019-05-23'),
(307, 'admin', 'POST', 'api/login', '2019-05-23'),
(308, '', 'POST', 'api/login', '2019-05-23'),
(309, 'admin', 'POST', 'api/login', '2019-05-23'),
(310, 'admin', 'POST', 'api/login', '2019-05-23'),
(311, 'asd', 'POST', 'api/login', '2019-05-23'),
(312, 'admin', 'POST', 'api/login', '2019-05-23'),
(313, 'admin', 'POST', 'api/login', '2019-05-23'),
(314, 'admin', 'POST', 'api/login', '2019-05-23'),
(315, 'admin', 'POST', 'api/login', '2019-05-23'),
(316, 'admin', 'POST', 'api/login', '2019-05-23'),
(317, '', 'POST', 'api/login', '2019-05-23'),
(318, '', 'POST', 'api/login', '2019-05-23'),
(319, 'admin', 'POST', 'api/login', '2019-05-23'),
(320, 'admin', 'POST', 'api/login', '2019-05-23'),
(321, 'admin', 'POST', 'api/login', '2019-05-23'),
(322, 'admin', 'POST', 'api/login', '2019-05-23'),
(323, 'admin', 'POST', 'api/login', '2019-05-23'),
(324, 'admin', 'POST', 'api/login', '2019-05-23'),
(325, 'admin', 'POST', 'api/login', '2019-05-23'),
(326, 'admin', 'POST', 'api/login', '2019-05-23'),
(327, 'admin', 'POST', 'api/login', '2019-05-23'),
(328, 'admin', 'POST', 'api/login', '2019-05-23'),
(329, 'admin', 'POST', 'api/login', '2019-05-23'),
(330, 'admin', 'POST', 'api/login', '2019-05-23'),
(331, 'admin', 'POST', 'api/login', '2019-05-23'),
(332, 'admin', 'POST', 'api/login', '2019-05-23'),
(333, 'admin', 'POST', 'api/login', '2019-05-23'),
(334, 'admin', 'POST', 'api/login', '2019-05-23'),
(335, 'admin', 'POST', 'api/login', '2019-05-23'),
(336, 'admin', 'POST', 'api/login', '2019-05-23'),
(337, 'admin', 'POST', 'api/login', '2019-05-23'),
(338, 'admin', 'POST', 'api/login', '2019-05-23'),
(339, 'admin', 'POST', 'api/login', '2019-05-23'),
(340, 'admin', 'POST', 'api/login', '2019-05-23'),
(341, 'admin', 'POST', 'api/login', '2019-05-23'),
(342, 'admin', 'POST', 'api/login', '2019-05-23'),
(343, 'admin', 'POST', 'api/login', '2019-05-23'),
(344, 'admin', 'POST', 'api/login', '2019-05-23'),
(345, 'admin', 'POST', 'api/login', '2019-05-23'),
(346, 'admin', 'POST', 'api/login', '2019-05-23'),
(347, 'admin', 'POST', 'api/login', '2019-05-23'),
(348, 'admin', 'POST', 'api/login', '2019-05-23'),
(349, 'admin', 'POST', 'api/login', '2019-05-23'),
(350, 'admin', 'POST', 'api/login', '2019-05-23'),
(351, 'admin', 'POST', 'api/login', '2019-05-23'),
(352, 'admin', 'POST', 'api/login', '2019-05-23'),
(353, 'admin', 'POST', 'api/login', '2019-05-23'),
(354, 'admin', 'POST', 'api/login', '2019-05-23'),
(355, 'admin', 'POST', 'api/login', '2019-05-23'),
(356, 'admin', 'POST', 'api/login', '2019-05-23'),
(357, 'admin', 'POST', 'api/login', '2019-05-23'),
(358, 'admin', 'POST', 'api/login', '2019-05-23'),
(359, 'admin', 'POST', 'api/login', '2019-05-23'),
(360, 'admin', 'POST', 'api/login', '2019-05-23'),
(361, 'admin', 'POST', 'api/login', '2019-05-23'),
(362, 'admin', 'POST', 'api/login', '2019-05-23'),
(363, 'admin', 'POST', 'api/login', '2019-05-23'),
(364, 'admin', 'POST', 'api/login', '2019-05-23'),
(365, 'admin', 'POST', 'api/login', '2019-05-23'),
(366, 'admin', 'POST', 'api/login', '2019-05-23'),
(367, 'admin', 'POST', 'api/login', '2019-05-23'),
(368, 'admin', 'POST', 'api/login', '2019-05-23'),
(369, 'admin', 'POST', 'api/login', '2019-05-23'),
(370, 'admin', 'POST', 'api/login', '2019-05-23'),
(371, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(372, 'admin', 'POST', 'api/login', '2019-05-23'),
(373, 'admin', 'POST', 'api/login', '2019-05-23'),
(374, 'admin', 'POST', 'api/login', '2019-05-23'),
(375, 'admin', 'POST', 'api/login', '2019-05-23'),
(376, 'admin', 'POST', 'api/login', '2019-05-23'),
(377, 'admin', 'POST', 'api/login', '2019-05-23'),
(378, 'admin', 'POST', 'api/login', '2019-05-23'),
(379, 'admin', 'POST', 'api/login', '2019-05-23'),
(380, 'admin', 'POST', 'api/login', '2019-05-23'),
(381, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(382, 'admin', 'POST', 'api/login', '2019-05-23'),
(383, 'admin', 'POST', 'api/login', '2019-05-23'),
(384, 'admin', 'POST', 'api/login', '2019-05-23'),
(385, 'admin', 'POST', 'api/login', '2019-05-23'),
(386, 'admin', 'POST', 'api/login', '2019-05-23'),
(387, 'admin', 'POST', 'api/login', '2019-05-23'),
(388, '', 'POST', 'api/login', '2019-05-23'),
(389, 'admin', 'POST', 'api/login', '2019-05-23'),
(390, 'admin', 'POST', 'api/login', '2019-05-23'),
(391, 'admin', 'POST', 'api/login', '2019-05-23'),
(392, 'admin', 'POST', 'api/login', '2019-05-23'),
(393, '', 'POST', 'api/login', '2019-05-23'),
(394, 'admin', 'POST', 'api/login', '2019-05-23'),
(395, 'admin', 'POST', 'api/login', '2019-05-23'),
(396, 'admin', 'POST', 'api/login', '2019-05-23'),
(397, 'admin', 'POST', 'api/login', '2019-05-23'),
(398, 'admin', 'POST', 'api/login', '2019-05-23'),
(399, 'admin', 'POST', 'api/login', '2019-05-23'),
(400, 'admin', 'POST', 'api/login', '2019-05-23'),
(401, 'admin', 'POST', 'api/login', '2019-05-23'),
(402, 'admin', 'POST', 'api/login', '2019-05-23'),
(403, 'admin', 'POST', 'api/login', '2019-05-23'),
(404, 'admin', 'POST', 'api/login', '2019-05-23'),
(405, 'admin', 'POST', 'api/login', '2019-05-23'),
(406, 'admin', 'POST', 'api/login', '2019-05-23'),
(407, 'admin', 'POST', 'api/login', '2019-05-23'),
(408, 'admin', 'POST', 'api/login', '2019-05-23'),
(409, 'admin', 'POST', 'api/login', '2019-05-23'),
(410, 'admin', 'POST', 'api/login', '2019-05-23'),
(411, 'admin', 'POST', 'api/login', '2019-05-23'),
(412, 'admin', 'POST', 'api/login', '2019-05-23'),
(413, 'admin', 'POST', 'api/login', '2019-05-23'),
(414, 'admin', 'POST', 'api/login', '2019-05-23'),
(415, 'admin', 'POST', 'api/login', '2019-05-23'),
(416, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(417, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(418, 'admin', 'POST', 'api/login', '2019-05-23'),
(419, 'admin', 'POST', 'api/login', '2019-05-23'),
(420, 'admin', 'POST', 'api/login', '2019-05-23'),
(421, 'admin', 'POST', 'api/login', '2019-05-23'),
(422, 'admin', 'POST', 'api/login', '2019-05-23'),
(423, 'admin', 'POST', 'api/login', '2019-05-23'),
(424, 'admin', 'POST', 'api/login', '2019-05-23'),
(425, 'admin', 'POST', 'api/login', '2019-05-23'),
(426, 'admin', 'POST', 'api/login', '2019-05-23'),
(427, 'admin', 'POST', 'api/login', '2019-05-23'),
(428, 'admin', 'POST', 'api/login', '2019-05-23'),
(429, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(430, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(431, 'admin', 'POST', 'api/login', '2019-05-23'),
(432, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(433, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(434, 'ricardo93', 'POST', 'api/login', '2019-05-23'),
(435, 'admin', 'POST', 'api/login', '2019-05-23'),
(436, 'admin', 'POST', 'api/login', '2019-05-23'),
(437, 'admin', 'POST', 'api/login', '2019-05-23'),
(438, 'admin', 'POST', 'api/login', '2019-05-23'),
(439, 'admin', 'POST', 'api/login', '2019-05-23'),
(440, 'admin', 'POST', 'api/login', '2019-05-23'),
(441, 'admin', 'POST', 'api/login', '2019-05-23'),
(442, 'admin', 'POST', 'api/login', '2019-05-23'),
(443, 'admin', 'POST', 'api/login', '2019-05-23'),
(444, 'admin', 'POST', 'api/login', '2019-05-23'),
(445, 'admin', 'POST', 'api/login', '2019-05-23'),
(446, 'admin', 'POST', 'api/login', '2019-05-23'),
(447, 'admin', 'POST', 'api/login', '2019-05-23'),
(448, 'admin', 'POST', 'api/login', '2019-05-23'),
(449, 'admin', 'POST', 'api/login', '2019-05-23'),
(450, 'admin', 'POST', 'api/login', '2019-05-23'),
(451, 'admin', 'POST', 'api/login', '2019-05-23'),
(452, 'admin', 'POST', 'api/login', '2019-05-23'),
(453, 'admin', 'POST', 'api/login', '2019-05-23'),
(454, 'admin', 'POST', 'api/login', '2019-05-23'),
(455, 'admin', 'POST', 'api/login', '2019-05-23'),
(456, 'admin', 'POST', 'api/login', '2019-05-23'),
(457, 'admin', 'POST', 'api/login', '2019-05-23'),
(458, 'admin', 'POST', 'api/login', '2019-05-23'),
(459, 'admin', 'POST', 'api/login', '2019-05-23'),
(460, 'admin', 'POST', 'api/login', '2019-05-23'),
(461, 'admin', 'POST', 'api/login', '2019-05-23'),
(462, 'admin', 'POST', 'api/login', '2019-05-23'),
(463, 'admin', 'POST', 'api/login', '2019-05-23'),
(464, 'admin', 'POST', 'api/login', '2019-05-23'),
(465, 'admin', 'POST', 'api/login', '2019-05-23'),
(466, 'admin', 'POST', 'api/login', '2019-05-23'),
(467, 'admin', 'POST', 'api/login', '2019-05-23'),
(468, 'admin', 'POST', 'api/login', '2019-05-23'),
(469, 'admin', 'POST', 'api/login', '2019-05-23'),
(470, 'admin', 'POST', 'api/login', '2019-05-23'),
(471, 'admin', 'POST', 'api/login', '2019-05-23'),
(472, 'ricardo93', 'POST', 'api/login', '2019-05-24'),
(473, 'admin', 'POST', 'api/login', '2019-05-24'),
(474, 'admin', 'POST', 'api/login', '2019-05-25'),
(475, 'zzxc', 'POST', 'api/login', '2019-05-25'),
(476, 'asd', 'POST', 'api/login', '2019-05-25'),
(477, 'asd', 'POST', 'api/login', '2019-05-25'),
(478, 'asd', 'POST', 'api/login', '2019-05-25'),
(479, 'asdasd12', 'POST', 'api/login', '2019-05-25'),
(480, 'asd', 'POST', 'api/login', '2019-05-25'),
(481, 'hitachy93', 'POST', 'api/login', '2019-05-25'),
(482, 'ricardo93', 'POST', 'api/login', '2019-05-25'),
(483, 'admin', 'POST', 'api/login', '2019-05-25'),
(484, 'admin', 'POST', 'api/login', '2019-05-25'),
(485, 'admin3', 'POST', 'api/login', '2019-05-25'),
(486, 'admin', 'POST', 'api/login', '2019-05-25'),
(487, 'admin', 'POST', 'api/login', '2019-05-25'),
(488, 'admin1', 'POST', 'api/login', '2019-05-25'),
(489, 'admin', 'POST', 'api/login', '2019-05-25'),
(490, 'admin', 'POST', 'api/login', '2019-05-25'),
(491, 'assss', 'POST', 'api/login', '2019-05-25'),
(492, 'adminn', 'POST', 'api/login', '2019-05-25'),
(493, '123', 'POST', 'api/login', '2019-05-25'),
(494, 'admin', 'POST', 'api/login', '2019-05-25'),
(495, 'asd', 'POST', 'api/login', '2019-05-25'),
(496, 'as', 'POST', 'api/login', '2019-05-25'),
(497, 'ad', 'POST', 'api/login', '2019-05-25'),
(498, '123', 'POST', 'api/login', '2019-05-25'),
(499, '123', 'POST', 'api/login', '2019-05-25'),
(500, '123', 'POST', 'api/login', '2019-05-25'),
(501, '123', 'POST', 'api/login', '2019-05-25'),
(502, '123', 'POST', 'api/login', '2019-05-25'),
(503, '1234', 'POST', 'api/login', '2019-05-25'),
(504, '123', 'POST', 'api/login', '2019-05-25'),
(505, '1234', 'POST', 'api/login', '2019-05-25'),
(506, '123', 'POST', 'api/login', '2019-05-25'),
(507, 'asdsd', 'POST', 'api/login', '2019-05-25'),
(508, 'asd', 'POST', 'api/login', '2019-05-25'),
(509, 'asdasdasd', 'POST', 'api/login', '2019-05-25'),
(510, 'asdasdas', 'POST', 'api/login', '2019-05-25'),
(511, 'asdsad', 'POST', 'api/login', '2019-05-25'),
(512, 'asdsad', 'POST', 'api/login', '2019-05-25'),
(513, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(514, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(515, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(516, 'adsad', 'POST', 'api/login', '2019-05-25'),
(517, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(518, 'asd', 'POST', 'api/login', '2019-05-25'),
(519, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(520, 'asd', 'POST', 'api/login', '2019-05-25'),
(521, 'asdasd', 'POST', 'api/login', '2019-05-25'),
(522, '', 'POST', 'api/login', '2019-05-25'),
(523, '', 'POST', 'api/login', '2019-05-25'),
(524, 'admin', 'POST', 'api/login', '2019-05-25'),
(525, 'admin', 'POST', 'api/login', '2019-05-25'),
(526, 'admin', 'POST', 'api/login', '2019-05-28'),
(527, 'admin', 'POST', 'api/login', '2019-05-28'),
(528, 'admin', 'POST', 'api/login', '2019-05-28'),
(529, 'admin', 'POST', 'api/login', '2019-05-28'),
(530, 'admin', 'POST', 'api/login', '2019-05-28'),
(531, 'admin', 'POST', 'api/login', '2019-05-28'),
(532, 'admin', 'POST', 'api/login', '2019-05-28'),
(533, 'admin', 'POST', 'api/login', '2019-05-28'),
(534, 'admin', 'POST', 'api/login', '2019-05-28'),
(535, 'as', 'POST', 'api/login', '2019-05-28'),
(536, 'as', 'POST', 'api/login', '2019-05-28'),
(537, 'admin', 'POST', 'api/login', '2019-05-28'),
(538, 'admin', 'POST', 'api/login', '2019-05-28'),
(539, 'admin', 'POST', 'api/login', '2019-06-05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=540;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
