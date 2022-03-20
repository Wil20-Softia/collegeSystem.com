-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2020 a las 19:36:46
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `colegiodrjgh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_grado`
--

CREATE TABLE `categoria_grado` (
  `id` tinyint(1) NOT NULL,
  `nombre` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categoria_grado`
--

INSERT INTO `categoria_grado` (`id`, `nombre`) VALUES
(1, 'PRIMARIA'),
(2, 'SECUNDARIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`
--

CREATE TABLE `categoria_producto` (
  `id` int(3) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `tipo` varchar(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo`) VALUES
(1, 'c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda_antigua`
--

CREATE TABLE `deuda_antigua` (
  `id` int(11) NOT NULL,
  `estudiante_deudor_antiguo` int(11) NOT NULL,
  `mes` tinyint(2) NOT NULL,
  `tipo_deuda_mes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda_meses`
--

CREATE TABLE `deuda_meses` (
  `id` int(11) NOT NULL,
  `meses_periodo` int(11) NOT NULL,
  `momento_estudiante` int(11) NOT NULL,
  `tipo_deuda_mes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pago`
--

CREATE TABLE `estado_pago` (
  `id` tinyint(1) NOT NULL,
  `nombre` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `clases_boton` text COLLATE utf8_unicode_ci NOT NULL,
  `clases_contenedor` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estado_pago`
--

INSERT INTO `estado_pago` (`id`, `nombre`, `clases_boton`, `clases_contenedor`) VALUES
(1, 'PAGO', 'texto-bln-sombra-ngr texto-centrado txt-light-pq pd-btigual-mitad', 'bg-success pd-btigual-mitad'),
(2, 'ABONADO', 'texto-ngr-sombra-bln texto-centrado txt-light-pq pd-btigual-mitad', 'bg-warning pd-btigual-mitad'),
(3, 'DEBIDO', '', 'bg-danger'),
(4, 'DESACTIVADO', '<h1 class=\"posicion-h1-cpp text-light\"><i class=\"far fa-times-circle\"></i></h1>', 'bg-dark');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
  `cedula` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `representante` int(11) NOT NULL,
  `tipo_comprador` int(11) NOT NULL,
  `primer_nombre` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `segundo_nombre` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `segundo_apellido` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `cedulado` tinyint(1) NOT NULL,
  `habilitado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_deudor_antiguo`
--

CREATE TABLE `estudiante_deudor_antiguo` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `tipo_estudiante` int(11) NOT NULL,
  `habilitado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_inscripcion`
--

CREATE TABLE `factura_inscripcion` (
  `id` int(11) NOT NULL,
  `tipo_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_normal`
--

CREATE TABLE `factura_normal` (
  `id` int(11) NOT NULL,
  `mensualidad` int(11) NOT NULL,
  `tipo_factura` int(11) NOT NULL,
  `total_mora` float NOT NULL,
  `subtotal` float NOT NULL,
  `diferencia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_producto`
--

CREATE TABLE `factura_producto` (
  `id` int(11) NOT NULL,
  `tipo_factura` int(11) NOT NULL,
  `cantidad_productos` tinyint(4) NOT NULL,
  `diferencia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `id` tinyint(2) NOT NULL,
  `categoria_grado` tinyint(1) NOT NULL,
  `nombre` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`id`, `categoria_grado`, `nombre`) VALUES
(1, 1, '1ER GRADO'),
(2, 1, '2DO GRADO'),
(3, 1, '3ER GRADO'),
(4, 1, '4TO GRADO'),
(5, 1, '5TO GRADO'),
(6, 1, '6TO GRADO'),
(7, 2, '1ER AÑO'),
(8, 2, '2DO AÑO'),
(9, 2, '3ER AÑO'),
(10, 2, '4TO AÑO'),
(11, 2, '5TO AÑO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_tareas`
--

CREATE TABLE `historial_tareas` (
  `usuario` int(11) NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones_pagas`
--

CREATE TABLE `inscripciones_pagas` (
  `factura_inscripcion` int(11) NOT NULL,
  `tipo_deuda_inscripcion` int(11) NOT NULL,
  `abonado` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensualidad`
--

CREATE TABLE `mensualidad` (
  `id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha_registrado` datetime NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `mensualidad`
--

INSERT INTO `mensualidad` (`id`, `monto`, `fecha_registrado`, `usuario`) VALUES
(1, 0, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mes`
--

CREATE TABLE `mes` (
  `id` tinyint(2) NOT NULL,
  `nombre` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mes`
--

INSERT INTO `mes` (`id`, `nombre`) VALUES
(1, 'SEPTIEMBRE'),
(2, 'OCTUBRE'),
(3, 'NOVIEMBRE'),
(4, 'DICIEMBRE'),
(5, 'ENERO'),
(6, 'FEBRERO'),
(7, 'MARZO'),
(8, 'ABRIL'),
(9, 'MAYO'),
(10, 'JUNIO'),
(11, 'JULIO'),
(12, 'AGOSTO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meses_pagos`
--

CREATE TABLE `meses_pagos` (
  `factura` int(11) NOT NULL,
  `tipo_deuda_mes` int(11) NOT NULL,
  `estado_pago` tinyint(1) NOT NULL,
  `mora` int(11) NOT NULL,
  `abonado` float NOT NULL,
  `diferencia` float NOT NULL,
  `dias_mora` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meses_periodo`
--

CREATE TABLE `meses_periodo` (
  `id` int(11) NOT NULL,
  `mensualidad` int(11) NOT NULL,
  `mes` tinyint(2) NOT NULL,
  `periodo_escolar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `momento_estudiante`
--

CREATE TABLE `momento_estudiante` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `tipo_estudiante` int(11) NOT NULL,
  `seccion_especifica` int(11) NOT NULL,
  `periodo_escolar` int(11) NOT NULL,
  `nuevo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mora`
--

CREATE TABLE `mora` (
  `id` int(11) NOT NULL,
  `porcentaje` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `no_cedulado`
--

CREATE TABLE `no_cedulado` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_factura`
--

CREATE TABLE `pago_factura` (
  `tipo_factura` int(11) NOT NULL,
  `tipo_pago` tinyint(2) NOT NULL,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `monto` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo_escolar`
--

CREATE TABLE `periodo_escolar` (
  `id` int(11) NOT NULL,
  `nombre` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `year_inicia` year(4) NOT NULL,
  `year_termina` year(4) NOT NULL,
  `finalizado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `cedula` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `primer_nombre` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `primer_apellido` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_comprador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precio_producto`
--

CREATE TABLE `precio_producto` (
  `id` int(11) NOT NULL,
  `producto` int(11) NOT NULL,
  `precio_venta` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `sub_categoria_producto` int(3) NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `precio_venta` float NOT NULL,
  `cantidad_existente` int(11) NOT NULL,
  `ultimo_abastecimiento` date NOT NULL,
  `habilitado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_facturas`
--

CREATE TABLE `productos_facturas` (
  `id` int(11) NOT NULL,
  `cantidad` tinyint(4) NOT NULL,
  `producto` int(11) NOT NULL,
  `precio_producto` int(11) NOT NULL,
  `importe` float NOT NULL,
  `factura_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencia_efectivo`
--

CREATE TABLE `referencia_efectivo` (
  `id` int(11) NOT NULL,
  `tipo_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencia_pago_nomina`
--

CREATE TABLE `referencia_pago_nomina` (
  `id` int(11) NOT NULL,
  `tipo_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representante`
--

CREATE TABLE `representante` (
  `id` int(11) NOT NULL,
  `cedula` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_comprador` int(11) NOT NULL,
  `primer_nombre` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `segundo_nombre` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `segundo_apellido` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(18) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `id` tinyint(2) NOT NULL,
  `nombre` varchar(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`id`, `nombre`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F'),
(7, 'G');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion_especifica`
--

CREATE TABLE `seccion_especifica` (
  `id` int(11) NOT NULL,
  `grado` tinyint(2) NOT NULL,
  `seccion` tinyint(2) NOT NULL,
  `fecha_registrado` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_categoria_producto`
--

CREATE TABLE `sub_categoria_producto` (
  `id` int(3) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `categoria_producto` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_comprador`
--

CREATE TABLE `tipo_comprador` (
  `id` int(11) NOT NULL,
  `tipo` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `ultima_modificacion` datetime NOT NULL,
  `cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `tipo_comprador`
--

INSERT INTO `tipo_comprador` (`id`, `tipo`, `ultima_modificacion`, `cliente`) VALUES
(1, 'usuario', '2019-11-07 17:10:54', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_deuda_inscripcion`
--

CREATE TABLE `tipo_deuda_inscripcion` (
  `id` int(11) NOT NULL,
  `tipo_inscripcion` int(11) NOT NULL,
  `momento_estudiante` int(11) NOT NULL,
  `estado_pago` tinyint(1) NOT NULL,
  `diferencia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_deuda_mes`
--

CREATE TABLE `tipo_deuda_mes` (
  `id` int(11) NOT NULL,
  `estado_pago` tinyint(1) NOT NULL,
  `diferencia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_estudiante`
--

CREATE TABLE `tipo_estudiante` (
  `id` int(11) NOT NULL,
  `tipo` enum('estudiante_deudor_antiguo','momento_estudiante') COLLATE utf8_unicode_ci NOT NULL,
  `hora_registro` time NOT NULL,
  `fecha_registro` date NOT NULL,
  `cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_factura`
--

CREATE TABLE `tipo_factura` (
  `id` int(11) NOT NULL,
  `tipo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `monto_total` float NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_inscripcion`
--

CREATE TABLE `tipo_inscripcion` (
  `id` int(11) NOT NULL,
  `tipo` enum('Cupo','Insc') COLLATE utf8_unicode_ci NOT NULL,
  `periodo_escolar` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha_registrado` datetime NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id` tinyint(2) NOT NULL,
  `nombre` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id`, `nombre`) VALUES
(1, 'EFECTIVO'),
(2, 'TRANSFERENCIA'),
(3, 'PUNTO DE VENTA'),
(4, 'CHEQUE'),
(5, 'NOMINA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `cedula` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `primer_nombre` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `primer_apellido` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sexo` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  `ultima_sesion` datetime NOT NULL,
  `tipo_comprador` int(11) NOT NULL,
  `password` longtext COLLATE utf8_unicode_ci NOT NULL,
  `conectado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `cedula`, `correo`, `primer_nombre`, `primer_apellido`, `sexo`, `tipo`, `habilitado`, `ultima_sesion`, `tipo_comprador`, `password`, `conectado`) VALUES
(1, 'V-4313924', 'yvoviedo@gmail.com', 'IVONE', 'OVIEDO', 'FEMENINO', 1, 1, '2020-01-21 17:23:40', 1, '$2y$10$za0KupFRg2gBfIbNq89J7ewd.btCjOXanOuQhHYTAJC1vWN4UURYm', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria_grado`
--
ALTER TABLE `categoria_grado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deuda_antigua`
--
ALTER TABLE `deuda_antigua`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_deudor_antiguo` (`estudiante_deudor_antiguo`),
  ADD KEY `mes` (`mes`),
  ADD KEY `tipo_deuda_mes` (`tipo_deuda_mes`);

--
-- Indices de la tabla `deuda_meses`
--
ALTER TABLE `deuda_meses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meses_periodo` (`meses_periodo`),
  ADD KEY `momento_estudiante` (`momento_estudiante`),
  ADD KEY `tipo_deuda_mes` (`tipo_deuda_mes`);

--
-- Indices de la tabla `estado_pago`
--
ALTER TABLE `estado_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `representante` (`representante`),
  ADD KEY `tipo_comprador` (`tipo_comprador`);

--
-- Indices de la tabla `estudiante_deudor_antiguo`
--
ALTER TABLE `estudiante_deudor_antiguo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `tipo_estudiante` (`tipo_estudiante`);

--
-- Indices de la tabla `factura_inscripcion`
--
ALTER TABLE `factura_inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_factura` (`tipo_factura`);

--
-- Indices de la tabla `factura_normal`
--
ALTER TABLE `factura_normal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_factura` (`tipo_factura`),
  ADD KEY `mensualidad` (`mensualidad`);

--
-- Indices de la tabla `factura_producto`
--
ALTER TABLE `factura_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_factura` (`tipo_factura`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_grado` (`categoria_grado`);

--
-- Indices de la tabla `historial_tareas`
--
ALTER TABLE `historial_tareas`
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `inscripciones_pagas`
--
ALTER TABLE `inscripciones_pagas`
  ADD KEY `deuda_inscripcion` (`tipo_deuda_inscripcion`),
  ADD KEY `factura_inscripcion` (`factura_inscripcion`);

--
-- Indices de la tabla `mensualidad`
--
ALTER TABLE `mensualidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `mes`
--
ALTER TABLE `mes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `meses_pagos`
--
ALTER TABLE `meses_pagos`
  ADD KEY `factura` (`factura`),
  ADD KEY `tipo_deuda_mes` (`tipo_deuda_mes`),
  ADD KEY `estado_pago` (`estado_pago`),
  ADD KEY `mora` (`mora`);

--
-- Indices de la tabla `meses_periodo`
--
ALTER TABLE `meses_periodo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodo_escolar` (`periodo_escolar`),
  ADD KEY `mes` (`mes`),
  ADD KEY `mensualidad` (`mensualidad`);

--
-- Indices de la tabla `momento_estudiante`
--
ALTER TABLE `momento_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `tipo_estudiante` (`tipo_estudiante`),
  ADD KEY `seccion_especifica` (`seccion_especifica`),
  ADD KEY `periodo_escolar` (`periodo_escolar`);

--
-- Indices de la tabla `mora`
--
ALTER TABLE `mora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `no_cedulado`
--
ALTER TABLE `no_cedulado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pago_factura`
--
ALTER TABLE `pago_factura`
  ADD KEY `tipo_pago` (`tipo_pago`),
  ADD KEY `tipo_factura` (`tipo_factura`);

--
-- Indices de la tabla `periodo_escolar`
--
ALTER TABLE `periodo_escolar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_comprador` (`tipo_comprador`);

--
-- Indices de la tabla `precio_producto`
--
ALTER TABLE `precio_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subc_categoria_producto` (`sub_categoria_producto`);

--
-- Indices de la tabla `productos_facturas`
--
ALTER TABLE `productos_facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto` (`producto`),
  ADD KEY `precio_producto` (`precio_producto`),
  ADD KEY `factura_producto` (`factura_producto`);

--
-- Indices de la tabla `referencia_efectivo`
--
ALTER TABLE `referencia_efectivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_factura` (`tipo_factura`);

--
-- Indices de la tabla `referencia_pago_nomina`
--
ALTER TABLE `referencia_pago_nomina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_factura` (`tipo_factura`);

--
-- Indices de la tabla `representante`
--
ALTER TABLE `representante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `tipo_comprador` (`tipo_comprador`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seccion_especifica`
--
ALTER TABLE `seccion_especifica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grado` (`grado`),
  ADD KEY `seccion` (`seccion`);

--
-- Indices de la tabla `sub_categoria_producto`
--
ALTER TABLE `sub_categoria_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_producto` (`categoria_producto`);

--
-- Indices de la tabla `tipo_comprador`
--
ALTER TABLE `tipo_comprador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`cliente`);

--
-- Indices de la tabla `tipo_deuda_inscripcion`
--
ALTER TABLE `tipo_deuda_inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_pago` (`estado_pago`),
  ADD KEY `momento_estudiante` (`momento_estudiante`),
  ADD KEY `tipo_inscripcion` (`tipo_inscripcion`);

--
-- Indices de la tabla `tipo_deuda_mes`
--
ALTER TABLE `tipo_deuda_mes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_pago` (`estado_pago`);

--
-- Indices de la tabla `tipo_estudiante`
--
ALTER TABLE `tipo_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente` (`cliente`);

--
-- Indices de la tabla `tipo_factura`
--
ALTER TABLE `tipo_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_estudiante` (`cliente`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `tipo_inscripcion`
--
ALTER TABLE `tipo_inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `periodo_escolar` (`periodo_escolar`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `tipo_comprador` (`tipo_comprador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria_grado`
--
ALTER TABLE `categoria_grado`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `deuda_antigua`
--
ALTER TABLE `deuda_antigua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `deuda_meses`
--
ALTER TABLE `deuda_meses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `estado_pago`
--
ALTER TABLE `estado_pago`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `estudiante_deudor_antiguo`
--
ALTER TABLE `estudiante_deudor_antiguo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factura_inscripcion`
--
ALTER TABLE `factura_inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factura_normal`
--
ALTER TABLE `factura_normal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factura_producto`
--
ALTER TABLE `factura_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `grado`
--
ALTER TABLE `grado`
  MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `mensualidad`
--
ALTER TABLE `mensualidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `mes`
--
ALTER TABLE `mes`
  MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `meses_periodo`
--
ALTER TABLE `meses_periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `momento_estudiante`
--
ALTER TABLE `momento_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mora`
--
ALTER TABLE `mora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `no_cedulado`
--
ALTER TABLE `no_cedulado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `periodo_escolar`
--
ALTER TABLE `periodo_escolar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `precio_producto`
--
ALTER TABLE `precio_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `productos_facturas`
--
ALTER TABLE `productos_facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `referencia_efectivo`
--
ALTER TABLE `referencia_efectivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `referencia_pago_nomina`
--
ALTER TABLE `referencia_pago_nomina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `representante`
--
ALTER TABLE `representante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `seccion_especifica`
--
ALTER TABLE `seccion_especifica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sub_categoria_producto`
--
ALTER TABLE `sub_categoria_producto`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_comprador`
--
ALTER TABLE `tipo_comprador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipo_deuda_inscripcion`
--
ALTER TABLE `tipo_deuda_inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_deuda_mes`
--
ALTER TABLE `tipo_deuda_mes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_estudiante`
--
ALTER TABLE `tipo_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_factura`
--
ALTER TABLE `tipo_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_inscripcion`
--
ALTER TABLE `tipo_inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `deuda_antigua`
--
ALTER TABLE `deuda_antigua`
  ADD CONSTRAINT `deuda_antigua_ibfk_1` FOREIGN KEY (`estudiante_deudor_antiguo`) REFERENCES `estudiante_deudor_antiguo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deuda_antigua_ibfk_3` FOREIGN KEY (`tipo_deuda_mes`) REFERENCES `tipo_deuda_mes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deuda_antigua_ibfk_5` FOREIGN KEY (`mes`) REFERENCES `mes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `deuda_meses`
--
ALTER TABLE `deuda_meses`
  ADD CONSTRAINT `deuda_meses_ibfk_1` FOREIGN KEY (`meses_periodo`) REFERENCES `meses_periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deuda_meses_ibfk_2` FOREIGN KEY (`momento_estudiante`) REFERENCES `momento_estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deuda_meses_ibfk_4` FOREIGN KEY (`tipo_deuda_mes`) REFERENCES `tipo_deuda_mes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`representante`) REFERENCES `representante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`tipo_comprador`) REFERENCES `tipo_comprador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_deudor_antiguo`
--
ALTER TABLE `estudiante_deudor_antiguo`
  ADD CONSTRAINT `estudiante_deudor_antiguo_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_deudor_antiguo_ibfk_2` FOREIGN KEY (`tipo_estudiante`) REFERENCES `tipo_estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_inscripcion`
--
ALTER TABLE `factura_inscripcion`
  ADD CONSTRAINT `factura_inscripcion_ibfk_1` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_normal`
--
ALTER TABLE `factura_normal`
  ADD CONSTRAINT `factura_normal_ibfk_1` FOREIGN KEY (`mensualidad`) REFERENCES `mensualidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `factura_normal_ibfk_2` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_producto`
--
ALTER TABLE `factura_producto`
  ADD CONSTRAINT `factura_producto_ibfk_1` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `grado`
--
ALTER TABLE `grado`
  ADD CONSTRAINT `grado_ibfk_1` FOREIGN KEY (`categoria_grado`) REFERENCES `categoria_grado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_tareas`
--
ALTER TABLE `historial_tareas`
  ADD CONSTRAINT `historial_tareas_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripciones_pagas`
--
ALTER TABLE `inscripciones_pagas`
  ADD CONSTRAINT `inscripciones_pagas_ibfk_1` FOREIGN KEY (`factura_inscripcion`) REFERENCES `factura_inscripcion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripciones_pagas_ibfk_2` FOREIGN KEY (`tipo_deuda_inscripcion`) REFERENCES `tipo_deuda_inscripcion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensualidad`
--
ALTER TABLE `mensualidad`
  ADD CONSTRAINT `mensualidad_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `meses_pagos`
--
ALTER TABLE `meses_pagos`
  ADD CONSTRAINT `meses_pagos_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `factura_normal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meses_pagos_ibfk_2` FOREIGN KEY (`tipo_deuda_mes`) REFERENCES `tipo_deuda_mes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meses_pagos_ibfk_3` FOREIGN KEY (`estado_pago`) REFERENCES `estado_pago` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meses_pagos_ibfk_4` FOREIGN KEY (`mora`) REFERENCES `mora` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `meses_periodo`
--
ALTER TABLE `meses_periodo`
  ADD CONSTRAINT `meses_periodo_ibfk_1` FOREIGN KEY (`mensualidad`) REFERENCES `mensualidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meses_periodo_ibfk_2` FOREIGN KEY (`mes`) REFERENCES `mes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meses_periodo_ibfk_3` FOREIGN KEY (`periodo_escolar`) REFERENCES `periodo_escolar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `momento_estudiante`
--
ALTER TABLE `momento_estudiante`
  ADD CONSTRAINT `momento_estudiante_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `momento_estudiante_ibfk_2` FOREIGN KEY (`tipo_estudiante`) REFERENCES `tipo_estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `momento_estudiante_ibfk_3` FOREIGN KEY (`seccion_especifica`) REFERENCES `seccion_especifica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `momento_estudiante_ibfk_4` FOREIGN KEY (`periodo_escolar`) REFERENCES `periodo_escolar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago_factura`
--
ALTER TABLE `pago_factura`
  ADD CONSTRAINT `pago_factura_ibfk_1` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_factura_ibfk_2` FOREIGN KEY (`tipo_pago`) REFERENCES `tipo_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`tipo_comprador`) REFERENCES `tipo_comprador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `precio_producto`
--
ALTER TABLE `precio_producto`
  ADD CONSTRAINT `precio_producto_ibfk_1` FOREIGN KEY (`producto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`sub_categoria_producto`) REFERENCES `sub_categoria_producto` (`id`);

--
-- Filtros para la tabla `productos_facturas`
--
ALTER TABLE `productos_facturas`
  ADD CONSTRAINT `productos_facturas_ibfk_1` FOREIGN KEY (`producto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `productos_facturas_ibfk_2` FOREIGN KEY (`precio_producto`) REFERENCES `precio_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_facturas_ibfk_3` FOREIGN KEY (`factura_producto`) REFERENCES `factura_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `referencia_efectivo`
--
ALTER TABLE `referencia_efectivo`
  ADD CONSTRAINT `referencia_efectivo_ibfk_1` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `referencia_pago_nomina`
--
ALTER TABLE `referencia_pago_nomina`
  ADD CONSTRAINT `referencia_pago_nomina_ibfk_1` FOREIGN KEY (`tipo_factura`) REFERENCES `tipo_factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `representante`
--
ALTER TABLE `representante`
  ADD CONSTRAINT `representante_ibfk_1` FOREIGN KEY (`tipo_comprador`) REFERENCES `tipo_comprador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seccion_especifica`
--
ALTER TABLE `seccion_especifica`
  ADD CONSTRAINT `seccion_especifica_ibfk_1` FOREIGN KEY (`grado`) REFERENCES `grado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seccion_especifica_ibfk_2` FOREIGN KEY (`seccion`) REFERENCES `seccion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sub_categoria_producto`
--
ALTER TABLE `sub_categoria_producto`
  ADD CONSTRAINT `sub_categoria_producto_ibfk_1` FOREIGN KEY (`categoria_producto`) REFERENCES `categoria_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_comprador`
--
ALTER TABLE `tipo_comprador`
  ADD CONSTRAINT `tipo_comprador_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_deuda_inscripcion`
--
ALTER TABLE `tipo_deuda_inscripcion`
  ADD CONSTRAINT `tipo_deuda_inscripcion_ibfk_1` FOREIGN KEY (`momento_estudiante`) REFERENCES `momento_estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tipo_deuda_inscripcion_ibfk_2` FOREIGN KEY (`estado_pago`) REFERENCES `estado_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tipo_deuda_inscripcion_ibfk_3` FOREIGN KEY (`tipo_inscripcion`) REFERENCES `tipo_inscripcion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_deuda_mes`
--
ALTER TABLE `tipo_deuda_mes`
  ADD CONSTRAINT `tipo_deuda_mes_ibfk_1` FOREIGN KEY (`estado_pago`) REFERENCES `estado_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_estudiante`
--
ALTER TABLE `tipo_estudiante`
  ADD CONSTRAINT `tipo_estudiante_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_factura`
--
ALTER TABLE `tipo_factura`
  ADD CONSTRAINT `tipo_factura_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tipo_factura_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`tipo_comprador`) REFERENCES `tipo_comprador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
