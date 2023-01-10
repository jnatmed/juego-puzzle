-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-10-2022 a las 08:27:40
-- Versión del servidor: 8.0.25
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `escuela`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `nombre_alumno` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `grado` varchar(3) CHARACTER SET utf8mb4 NOT NULL,
  `nombre_padre` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`nombre_alumno`, `fecha_nacimiento`, `grado`, `nombre_padre`) VALUES
('adel claudio solis vittorello', '2014-04-20', '2', 'vittorello antonela'),
('aguero colque walter joel isaias', '2015-12-31', '1', NULL),
('Aparicio Juan sebastian', '2014-09-22', '2', NULL),
('Aporte vuelquen Ian nicolas', '2015-03-09', '2', NULL),
('Bordón benjamin', '2014-12-10', '2', NULL),
('brisa lena', '2017-02-23', 'jin', NULL),
('chinea arroyo valentina', '2015-04-03', '2', 'arroyo gutierrez abigail'),
('christopher bellorin', '2016-09-04', 'jin', NULL),
('dante natello medina', '2015-03-08', '2', 'juan manuel natello'),
('Figueroa santino esteban', '2013-12-14', '2', NULL),
('giovanna natello medina', '2013-11-09', '3', 'juan manuel natello'),
('Gural juana olivia', '2014-08-27', '2', NULL),
('gutierrez alba thomas santiago', '2015-06-05', '2', 'yesica alejandra alba'),
('Hernandez Navarro Diego aleandro', '2015-05-24', '2', NULL),
('huamani castillo mariano gabriel', '2014-02-25', '2', 'castillo miranda gloria'),
('Lopez Granadillo Sofia', '2014-12-08', '2', NULL),
('luis daniel rivero echandia', '2014-10-10', '2', 'maryuri echandia'),
('mamani paucara dylan samuel', '2015-01-02', '2', 'paucara mamani selena'),
('matias dante garcia lombardo', '2014-09-11', '2', 'nancy beatriz lombardo'),
('Meghan social freites russo ', '2015-04-09', '2', NULL),
('Nicolas benjamin quispe llanos', '2014-11-10', '2', NULL),
('Obispo salazar samanta', '2015-03-25', '2', NULL),
('Quipildor leonel', '2014-09-20', '2', NULL),
('reales jimenez thiago lionel', '2014-12-06', '2', 'malvina soledad jimenez'),
('Ríos martina olivia', '2015-03-10', '2', NULL),
('rivas ponce jordan', '2013-12-24', '2', 'ponce perez sandy'),
('rivero aquino alan', '2014-09-08', '2', 'nancy aquino'),
('roldan canelo sofia belen', '2015-04-14', '2', 'canelo diana maria'),
('Sevilla orta ainhoa neeireth', '2015-02-13', '2', NULL),
('vazquez lescano erick', '2016-04-04', '1', NULL),
('zalazar agustin yoel', '2015-03-27', '2', 'gauto alejandra elda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota_social`
--

CREATE TABLE `cuota_social` (
  `nombre_alumno` varchar(50) NOT NULL,
  `fecha_abono` date NOT NULL,
  `monto_abonado` int NOT NULL,
  `comentario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `nom_imagen` varchar(90) NOT NULL,
  `url_imagen` varchar(90) NOT NULL,
  `desc_imagen` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`nom_imagen`, `url_imagen`, `desc_imagen`) VALUES
('abuela_bety', 'public\\imgs\\', 'abuela paterna juan\r\n'),
('abuela_carmen', 'public\\imgs\\', 'abuela materna, mama patricia'),
('abuelo_gregorio', 'public\\imgs\\', 'abuelo paterno, papa juan\r\n'),
('abuelo_miguel', 'public\\imgs\\', 'abuelo materno, papa patricia'),
('dante', 'public\\imgs', 'hijo dante'),
('giovanna', 'public\\imgs', 'giovanna hija'),
('mama_patricia', 'public\\imgs', 'mama');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padre`
--

CREATE TABLE `padre` (
  `nombre_padre` varchar(50) NOT NULL,
  `nombre_alumno` varchar(50) NOT NULL,
  `dni_padre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `padre`
--

INSERT INTO `padre` (`nombre_padre`, `nombre_alumno`, `dni_padre`) VALUES
('arroyo gutierrez abigail', 'chinea arroyo valentina', 96093161),
('canelo diana maria', 'roldan canelo sofia belen', 28937174),
('castillo miranda gloria', 'huamani castillo mariano gabriel', 94406939),
('colque melisa carolina', 'aguero colque walter joel isaias', 6826543),
('gauto alejandra elda', 'zalazar agustin yoel', 23391775),
('juan manuel natello', 'dante natello medina', 34304947),
('malvina soledad jimenez', 'reales jimenez thiago lionel', 29367977),
('maryuri echandia', 'luis daniel rivero echandia', 95900429),
('nancy aquino', 'rivero aquino alan', 94674390),
('nancy beatriz lombardo', 'matias dante garcia lombardo', 38044654),
('patricia graciela medina', 'dante natello medina', 30082727),
('paucara mamani selena', 'mamani paucara dylan samuel', 95973783),
('ponce perez sandy', 'rivas ponce jordan', 94411369),
('vittorello antonela', 'adel claudio solis vittorello', 38073191),
('yesica alejandra alba', 'gutierrez alba thomas santiago', 95831646);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `nombre_alumno` varchar(50) NOT NULL,
  `grado` varchar(20) NOT NULL,
  `mes_cuota` varchar(20) NOT NULL,
  `nro_recibo` varchar(30) NOT NULL,
  `ruta_archivo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `recibos`
--

INSERT INTO `recibos` (`nombre_alumno`, `grado`, `mes_cuota`, `nro_recibo`, `ruta_archivo`) VALUES
('brisa lena', 'jin', 'mayo', '00002_00000159', 'recibos\\30678792094_015_00002_00000159.pdf'),
('christopher bellorin', 'jin', 'marzo', '00002-00000158', 'recibos\\30678792094_015_00002_00000158.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` varchar(20) NOT NULL,
  `contrasenia` varchar(20) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `email` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `contrasenia`, `alias`, `email`) VALUES
('root', 'Y00s4d14', 'juan', 'juanmannat@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`nombre_alumno`),
  ADD KEY `nombre_padre` (`nombre_padre`),
  ADD KEY `nombre_alumno` (`nombre_alumno`);

--
-- Indices de la tabla `cuota_social`
--
ALTER TABLE `cuota_social`
  ADD PRIMARY KEY (`nombre_alumno`,`fecha_abono`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`nom_imagen`);

--
-- Indices de la tabla `padre`
--
ALTER TABLE `padre`
  ADD PRIMARY KEY (`nombre_padre`,`nombre_alumno`),
  ADD KEY `nombre_alumno` (`nombre_alumno`),
  ADD KEY `nombre_padre` (`nombre_padre`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD PRIMARY KEY (`nombre_alumno`,`nro_recibo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`nombre_padre`) REFERENCES `padre` (`nombre_padre`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuota_social`
--
ALTER TABLE `cuota_social`
  ADD CONSTRAINT `cuota_social_ibfk_1` FOREIGN KEY (`nombre_alumno`) REFERENCES `alumno` (`nombre_alumno`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `padre`
--
ALTER TABLE `padre`
  ADD CONSTRAINT `padre_ibfk_1` FOREIGN KEY (`nombre_alumno`) REFERENCES `alumno` (`nombre_alumno`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
