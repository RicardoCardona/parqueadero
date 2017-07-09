-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2017 a las 19:27:54
-- Versión del servidor: 5.7.14
-- Versión de PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adminpark`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo`
--

CREATE TABLE `arqueo` (
  `idArqueo` int(11) NOT NULL,
  `fechaInicial` timestamp NULL DEFAULT NULL,
  `fechaFinal` timestamp NULL DEFAULT NULL,
  `saldoInicial` float DEFAULT NULL,
  `saldoFinal` float DEFAULT NULL,
  `descuadre` float DEFAULT NULL,
  `observaciones` text COLLATE utf8_spanish2_ci,
  `idUser` varchar(60) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `idCiudad` int(11) NOT NULL,
  `nombreCiudad` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idPais` int(11) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`idCiudad`, `nombreCiudad`, `idPais`, `createDate`) VALUES
(1, 'Bogotá', 1, '2016-10-31 20:30:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `idParqueadero` int(11) NOT NULL,
  `nombreParqueadero` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nit` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numResolucion` int(11) DEFAULT NULL,
  `fechaInicialResolucion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaFinalResolucion` timestamp NULL DEFAULT NULL,
  `numInicialFactura` varchar(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numeroAvisoFactura` varchar(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `prefijo` varchar(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numFinalFactura` varchar(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `baseInicial` float DEFAULT NULL,
  `resolucion` text COLLATE utf8_spanish2_ci,
  `tipoCompañia` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `redondeo` text COLLATE utf8_spanish2_ci,
  `ofertasAvisos` text COLLATE utf8_spanish2_ci,
  `iva` float DEFAULT NULL,
  `idPais` int(11) DEFAULT NULL,
  `idCiudad` int(11) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`idParqueadero`, `nombreParqueadero`, `nit`, `direccion`, `telefono`, `numResolucion`, `fechaInicialResolucion`, `fechaFinalResolucion`, `numInicialFactura`, `numeroAvisoFactura`, `prefijo`, `numFinalFactura`, `baseInicial`, `resolucion`, `tipoCompañia`, `redondeo`, `ofertasAvisos`, `iva`, `idPais`, `idCiudad`, `createDate`) VALUES
(1, 'UPark', '830.131.993-1', 'Av. Calle 26 #62-47', '036 3452670', 1000, '2016-11-01 05:00:00', '2017-11-01 05:00:00', '0001', '9800', 'A', '9999', 10000, 'Resolución DIAN 320001415221 de 2016-11-01 del 0001 al 9999', 'Régimen Común', 'Decreto 268/09 - Parágrafo 2do. La liquidación del Valor final del servicio se aproxima al múltiplo de cincuenta pesos($50) siguiente.', 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500,', 19, 1, 1, '2016-10-31 20:35:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `idContacto` int(11) NOT NULL,
  `tipoContacto` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombres` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `apellidos` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idTipoID` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idN` char(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombreComercial` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(60) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idPais` int(11) DEFAULT NULL,
  `idCiudad` int(11) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convenios`
--

CREATE TABLE `convenios` (
  `idConvenio` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `idTarifa` int(11) DEFAULT NULL,
  `minutosConvenio` float NOT NULL,
  `dineroConvenio` float DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL,
  `nFactura` varchar(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idFlujoVehiculo` int(11) DEFAULT NULL,
  `idParqueadero` int(11) DEFAULT NULL,
  `resolucion` text COLLATE utf8_spanish2_ci,
  `tipoCompañia` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `redondeo` text COLLATE utf8_spanish2_ci,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flujovehiculo`
--

CREATE TABLE `flujovehiculo` (
  `idFlujoVehiculo` int(11) NOT NULL,
  `movFechaInicial` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `movFechaFinal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `minutosCalculados` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `minutosReales` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idConvenio` int(11) DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish2_ci,
  `valorFlujoVehiculo` float DEFAULT NULL,
  `ivaFlujoVehiculo` float DEFAULT NULL,
  `valorCalculado` float DEFAULT NULL,
  `dineroRecibido` float DEFAULT NULL,
  `cambio` float DEFAULT NULL,
  `idVehiculo` int(11) DEFAULT NULL,
  `idTarifa` int(11) DEFAULT NULL,
  `idCaja` int(11) DEFAULT NULL,
  `idUsuarioEntrada` int(11) DEFAULT NULL,
  `idUsuarioSalida` int(11) DEFAULT NULL,
  `idTipoPago` int(11) DEFAULT NULL,
  `codigoTransacion` varchar(60) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8_spanish2_ci NOT NULL,
  `sincronizado` enum('no','si') COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `idMensaje` int(11) NOT NULL,
  `idRemitente` int(11) DEFAULT NULL,
  `remitenteMensaje` varchar(60) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tituloMensaje` varchar(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `contenidoMensaje` text COLLATE utf8_spanish2_ci,
  `fechaMensaje` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidades`
--

CREATE TABLE `modalidades` (
  `idModalidad` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipoModalidad` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modalidadDesc` text COLLATE utf8_spanish2_ci,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `modalidades`
--

INSERT INTO `modalidades` (`idModalidad`, `nombre`, `tipoModalidad`, `modalidadDesc`, `createDate`) VALUES
(1, 'Minutos', 'Minutos', 'Cobro Por minutos', '2016-10-27 01:02:29'),
(2, 'Horas', 'Horas', 'Cobro por Horas', '2016-10-27 17:21:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientoscaja`
--

CREATE TABLE `movimientoscaja` (
  `idCaja` int(11) NOT NULL,
  `base` float DEFAULT NULL,
  `fechaApertura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaCierre` timestamp NULL DEFAULT NULL,
  `incidencias` text COLLATE utf8_spanish2_ci,
  `valorFinal` float DEFAULT NULL,
  `idUser` varchar(60) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `idPais` int(11) NOT NULL,
  `nombrePais` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`idPais`, `nombrePais`, `createDate`) VALUES
(1, 'Colombia', '2016-10-31 20:30:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idRol` int(11) NOT NULL,
  `tipoRol` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish2_ci,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `tipoRol`, `descripcion`, `createDate`) VALUES
(1, 'administrador', NULL, '2016-10-26 19:45:25'),
(2, 'operario', NULL, '2016-10-26 19:45:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas`
--

CREATE TABLE `tarifas` (
  `idTarifa` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idModalidad` int(11) DEFAULT NULL,
  `idTipoVehiculo` int(11) DEFAULT NULL,
  `tiempoLimite` int(11) DEFAULT NULL,
  `valorTarifa` float NOT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tarifas`
--

INSERT INTO `tarifas` (`idTarifa`, `nombre`, `idModalidad`, `idTipoVehiculo`, `tiempoLimite`, `valorTarifa`, `createDate`) VALUES
(2, 'Horas', 2, 1, 12, 1000, '2017-02-02 06:24:49'),
(1, 'Minutos', 1, 1, 60, 50, '2017-02-02 06:23:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblreseteopass`
--

CREATE TABLE `tblreseteopass` (
  `id` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `username` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `creado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tblreseteopass`
--

INSERT INTO `tblreseteopass` (`id`, `idusuario`, `username`, `token`, `creado`) VALUES
(116, 2, 'dsas', '8d5a547452fcfb57300037feeb48e943fb9ebebb', '2016-09-30 20:18:36'),
(0, 48, 'operador@2park.', '24b482d7f3343008e5b7bfe9f8e96a96ee7582b7', '2016-11-09 22:27:24'),
(0, 48, 'operador@2park.', '9f74f522e096134a9b5b26b236647e5862e4d637', '2016-11-10 00:47:19'),
(0, 48, 'operador@2park.', '6a13587ccafb258e886d8c79ee30174e5430dd4a', '2016-11-11 01:54:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopago`
--

CREATE TABLE `tipopago` (
  `idTipoPago` int(11) NOT NULL,
  `tipoPago` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipopago`
--

INSERT INTO `tipopago` (`idTipoPago`, `tipoPago`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta Débito'),
(3, 'Tarjeta de Crédito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `idTipoVehiculo` int(11) NOT NULL,
  `descripcion` text COLLATE utf8_spanish2_ci,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`idTipoVehiculo`, `descripcion`, `createDate`) VALUES
(1, 'Automóvil', '2016-10-26 20:34:06'),
(2, 'Moto', '2016-10-26 20:34:40'),
(3, 'Bicicleta', '2016-10-26 20:34:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUser` int(60) NOT NULL,
  `fullname` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `username` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `idRol` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUser`, `fullname`, `username`, `password`, `idRol`) VALUES
(48, 'Admin', 'operador@2park.co', 'MTIzNDU=', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariosmensaje`
--

CREATE TABLE `usuariosmensaje` (
  `idUsuariosMensaje` int(11) NOT NULL,
  `idMensaje` int(11) DEFAULT NULL,
  `idUser` int(60) DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fechaHoraLeido` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `idVehiculo` int(11) NOT NULL,
  `placa` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `idTipoVehiculo` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `idContacto` int(11) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  ADD PRIMARY KEY (`idArqueo`),
  ADD KEY `idUser` (`idUser`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`idCiudad`),
  ADD KEY `idPais` (`idPais`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`idParqueadero`),
  ADD KEY `idPais` (`idPais`),
  ADD KEY `idCiudad` (`idCiudad`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`idContacto`),
  ADD KEY `idPais` (`idPais`),
  ADD KEY `idCiudad` (`idCiudad`);

--
-- Indices de la tabla `convenios`
--
ALTER TABLE `convenios`
  ADD PRIMARY KEY (`idConvenio`),
  ADD KEY `idTarifa` (`idTarifa`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `idFlujoVehiculo` (`idFlujoVehiculo`),
  ADD KEY `idParqueadero` (`idParqueadero`);

--
-- Indices de la tabla `flujovehiculo`
--
ALTER TABLE `flujovehiculo`
  ADD PRIMARY KEY (`idFlujoVehiculo`),
  ADD KEY `idConvenio` (`idConvenio`),
  ADD KEY `idVehiculo` (`idVehiculo`),
  ADD KEY `idTarifa` (`idTarifa`),
  ADD KEY `idCaja` (`idCaja`),
  ADD KEY `idUsuarioEntrada` (`idUsuarioEntrada`),
  ADD KEY `idUsuarioSalida` (`idUsuarioSalida`),
  ADD KEY `idTipoPago` (`idTipoPago`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`idMensaje`);

--
-- Indices de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  ADD PRIMARY KEY (`idModalidad`);

--
-- Indices de la tabla `movimientoscaja`
--
ALTER TABLE `movimientoscaja`
  ADD PRIMARY KEY (`idCaja`),
  ADD KEY `idUser` (`idUser`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`idPais`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`idTarifa`),
  ADD KEY `idModalidad` (`idModalidad`),
  ADD KEY `idTipoVehiculo` (`idTipoVehiculo`);

--
-- Indices de la tabla `tipopago`
--
ALTER TABLE `tipopago`
  ADD PRIMARY KEY (`idTipoPago`);

--
-- Indices de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  ADD PRIMARY KEY (`idTipoVehiculo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idRol` (`idRol`);

--
-- Indices de la tabla `usuariosmensaje`
--
ALTER TABLE `usuariosmensaje`
  ADD PRIMARY KEY (`idUsuariosMensaje`),
  ADD KEY `idMensaje` (`idMensaje`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`idVehiculo`),
  ADD KEY `idTipoVehiculo` (`idTipoVehiculo`),
  ADD KEY `idContacto` (`idContacto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  MODIFY `idArqueo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `idCiudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `idParqueadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `idContacto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `convenios`
--
ALTER TABLE `convenios`
  MODIFY `idConvenio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `flujovehiculo`
--
ALTER TABLE `flujovehiculo`
  MODIFY `idFlujoVehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `idMensaje` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  MODIFY `idModalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `movimientoscaja`
--
ALTER TABLE `movimientoscaja`
  MODIFY `idCaja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `idPais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  MODIFY `idTarifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipopago`
--
ALTER TABLE `tipopago`
  MODIFY `idTipoPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `idTipoVehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUser` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT de la tabla `usuariosmensaje`
--
ALTER TABLE `usuariosmensaje`
  MODIFY `idUsuariosMensaje` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `idVehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
