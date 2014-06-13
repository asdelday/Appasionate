-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-06-2014 a las 20:02:41
-- Versión del servidor: 5.5.36-cll-lve
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `vygoowbi_appasionate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE IF NOT EXISTS `clases` (
  `id_clase` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `profesor` int(11) NOT NULL,
  `cod` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_clase`),
  KEY `profesor` (`profesor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id_clase`, `nombre`, `profesor`, `cod`) VALUES
(1, '2º Historia', 1, '1q2w3e4r5t'),
(2, '1º Matematicas', 5, 'qawsedrftg'),
(3, 'Informática', 1, '1qa2ws3ed4rf5tg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE IF NOT EXISTS `examenes` (
  `id_examen` int(11) NOT NULL AUTO_INCREMENT,
  `marcador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `abierto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_examen`),
  KEY `marcador` (`marcador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id_examen`, `marcador`, `nombre`, `descripcion`, `abierto`) VALUES
(2, 3, 'Examen Plaza del Charco', 'La plaza del Charco es un sitio de mucha concurrencia social en el Puerto de la Cruz. ¿Que sabes acerca de esta plaza?', 0),
(3, 4, 'Examen Auditorio de Tenerife', 'Aquí podrá poner a prueba sus conocimientos sobre el Auditorio de Tenerife.', 0),
(47, 1, 'Examen Loro Parque', 'Aquí pondrás a prueba tus conocimientos sobre el Loro Parque.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE IF NOT EXISTS `imagenes` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `path_imagen` varchar(150) NOT NULL,
  `marcador` int(11) NOT NULL,
  PRIMARY KEY (`id_imagen`),
  KEY `marcador` (`marcador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id_imagen`, `path_imagen`, `marcador`) VALUES
(1, 'http://rodorte.com/appasionate/images/1_28.408316%2C-16.564861.jpg', 1),
(3, 'http://rodorte.com/appasionate/images/1_28.416706%2C-16.550790.jpg', 3),
(4, 'http://rodorte.com/appasionate/images/5_28.456097%2C-16.251454.jpg', 4),
(5, 'http://rodorte.com/appasionate/images/1_28.420025%2C-16.542990.jpg', 5),
(6, 'http://rodorte.com/appasionate/images/1_28.405582%2C-16.533068.jpg', 6),
(7, 'http://rodorte.com/appasionate/images/1_28.410378%2C-16.548148.jpg', 7),
(9, 'http://rodorte.com/appasionate/images/1_28.413193%2C-16.559159.jpg', 9),
(95, 'http://rodorte.com/appasionate/images/1402422479862.jpeg', 2),
(99, 'http://rodorte.com/appasionate/images/1402422924373.jpeg', 9),
(101, 'http://rodorte.com/appasionate/images/1402422924373.jpeg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcadores`
--

CREATE TABLE IF NOT EXISTS `marcadores` (
  `id_marcador` int(11) NOT NULL AUTO_INCREMENT,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `descripcion` varchar(1000) NOT NULL,
  `creador` int(11) NOT NULL,
  `clase` int(11) NOT NULL,
  PRIMARY KEY (`id_marcador`),
  KEY `creador` (`creador`),
  KEY `clase` (`clase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `marcadores`
--

INSERT INTO `marcadores` (`id_marcador`, `lat`, `lng`, `titulo`, `descripcion`, `creador`, `clase`) VALUES
(1, 28.408316, -16.564861, 'Loro Parque', 'Muy buen sitio. Los shows entretenidos. Los animales increibles. Aunque es loro parque tiene mejores animales acuáticos. Recomiendo la visita guiada. Y comida aparte. Prepara dinero por que es caro. Aun asi merece la pena verlo.', 1, 1),
(2, 28.411351, -16.537931, 'Hotel Botanico & The Oriental Spa Garden', 'Avenida Richard J. Yeoward, 1 38400 Puerto de la Cruz Santa Cruz de Tenerife‎ 922 38 14 00', 1, 1),
(3, 28.416706, -16.55079, 'Plaza Del Charco', 'Es el centro neurálgico, el corazón del Puerto de la Cruz, donde mejor se palpa el alma cosmopolita y bulliciosa de esta ciudad. ', 1, 3),
(4, 28.456097, -16.251454, 'Auditorio de Tenerife', 'Últimamente están realizando una serie de espectáculos más variados y para un público más amplio.', 5, 2),
(5, 28.420025, -16.54299, 'Lago Martiánez', 'El Lago Martiánez es un complejo de ocio situado en Puerto de la Cruz. Se trata de un complejo de unos 100.000 metros cuadrados, formado por un lago central artificial con un conjunto de piscinas', 1, 1),
(6, 28.405582, -16.533068, 'Casa Abaco', 'La Casa. La Mansion Canaria Abaco del siglo XVIII está situada en el corazón de Puerto de la Cruz, casa de la familia directa del Conquistador de Canarias.', 1, 1),
(7, 28.410378, -16.548148, 'Taoro Parque', 'El jardín más antiguo de Tenerife, con más de 220 años', 1, 1),
(9, 28.413193, -16.559159, 'Playa Castillo', 'Castillo San Felipe. En la ciudad turística del Puerto de la Cruz, en Tenerife, y en uno de sus rincones mas visitados por los amantes del sol y de la playa.', 1, 1),
(10, 28.410809, -16.535231, 'Jardín Botánico', 'El Jardín de Aclimatación de La Orotava, también llamado Jardín Botánico, o normalmente como El Botánico, es un Jardín botánico situado en el Puerto de la Cruz en la isla canaria de Tenerife.', 1, 1),
(11, 28.498034, -16.304589, 'Sin imagen', 'Probando sin imagen. Probando sin imagen. Probando sin imagen.Probando sin imagen.Probando sin imagen. Probando sin imagen.Probando sin imagen. Probando sin imagen', 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE IF NOT EXISTS `notas` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `examen` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `nota` float NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_nota`),
  KEY `usuario` (`usuario`),
  KEY `examen` (`examen`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id_nota`, `examen`, `usuario`, `nota`, `fecha`) VALUES
(12, 2, 1, 2, '2014-06-11'),
(23, 47, 7, 3, '2014-06-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE IF NOT EXISTS `opciones` (
  `id_opcion` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` int(11) NOT NULL,
  `opcion` varchar(200) NOT NULL,
  `correcta` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_opcion`),
  KEY `pregunta` (`pregunta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id_opcion`, `pregunta`, `opcion`, `correcta`) VALUES
(19, 6, 'Puerto de la Cruz', 1),
(20, 6, 'La Orotava', 0),
(21, 6, 'La Laguna', 0),
(22, 6, 'Icod de los Vinos', 0),
(23, 7, 'Verdadero', 1),
(24, 7, 'Falso', 0),
(25, 8, 'Verdadero', 0),
(26, 8, 'Falso', 1),
(48, 23, 'Verdadero', 0),
(49, 23, 'Falso', 1),
(50, 24, 'Ingleses', 0),
(51, 24, 'Españoles', 0),
(52, 24, 'Alemanes', 1),
(53, 24, 'Italianos', 0),
(54, 25, 'Delfines', 0),
(55, 25, 'Pingüinos', 0),
(56, 25, 'Perezosos', 0),
(57, 25, 'Elefantes', 1),
(58, 26, 'La Jungla', 1),
(59, 26, 'Orquidearium', 0),
(60, 26, 'El jardín de dragos', 0),
(61, 26, 'El jardín de cactus', 0),
(62, 27, '60s', 0),
(63, 27, '70s', 1),
(64, 27, '80s', 0),
(65, 27, '90s', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE IF NOT EXISTS `preguntas` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `examen` int(11) NOT NULL,
  `pregunta` varchar(200) NOT NULL,
  `mensaje_correcto` varchar(200) NOT NULL,
  `mensaje_error` varchar(200) NOT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `examen` (`examen`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `examen`, `pregunta`, `mensaje_correcto`, `mensaje_error`) VALUES
(6, 2, 'La plaza del charco esta en el municipio:', 'Exacto, esta en el Puerto de la Cruz.', 'Error, la plaza del charco se encuentra en el Puerto de la Cruz.'),
(7, 2, 'La plaza del Charco es el centro cosmopolita de su municipio', 'Exacto, es el centro cosmopolita.', 'Error, la plaza del charco es el centro cosmopolita'),
(8, 2, 'La Plaza del charco NO tiene un pequeño parque infantil', 'Exacto, la plaza del charco SI que tiene un pequeño parque infantil.', 'Error, la plaza del charco Si que tiene un pequeño parque infantil.'),
(23, 47, 'Actualmente, el loro parque solo cuenta con aves:', 'Muy bien, correcto!, el loro parque cuenta con todo tipo de animales en la actualidad.', 'Error!, el loro parque actualmente cuenta con todo tipo de animales'),
(24, 47, 'Los creadores del Loro Parque son:', 'Muy bien, correcto!, los creadores son alemanes.', 'Error!, los creadores del Loro Parque son alemanes.'),
(25, 47, '¿Con que no cuenta el Loro Parque?', 'Muy bien, correcto!, el Loro Parque no cuenta con elefantes.', 'Error, de la lista el Loro Parque cuenta con todos los animales, salvo los elefantes.'),
(26, 47, 'La parte más antigua del parque es:', 'Muy bien, correcto!, la parte de más antigua del parque es La Jungla.', 'Error!, la parte de más antigua del parque es La Jungla.'),
(27, 47, 'El Loro Parque fue fundado en la década de los:', 'Muy bien, correcto!, el Loro  Parque fue fundado en la década de los  70s.', 'Error!, el Loro  Parque fue fundado en la década de los  70s.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(20) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `password` varchar(500) NOT NULL,
  `rol` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `rol_usuario` (`rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `nombre`, `apellidos`, `password`, `rol`) VALUES
(1, 'rodortcue@gmail.com', 'Rodrigo', 'Ortega Cuesta', '14e1b600b1fd579f47433b88e8d85291', 1),
(2, 'rodortcue2@gmail.com', 'Rodri', 'ASDA', '14e1b600b1fd579f47433b88e8d85291', 2),
(3, 'asdel@yahoo.com', 'asddl', 'asdel kai', '14e1b600b1fd579f47433b88e8d85291', 2),
(4, 'assh@jsj.com', 'ggd', 'gdgdgd', '14e1b600b1fd579f47433b88e8d85291', 2),
(5, 'profe@gmail.com', 'Profe', 'profe profe', '14e1b600b1fd579f47433b88e8d85291', 1),
(6, 'alu@gmail.com', 'alu', 'alum alum', '14e1b600b1fd579f47433b88e8d85291', 2),
(7, 'user7@gmail.com', 'seven', 'seven user', '14e1b600b1fd579f47433b88e8d85291', 2),
(8, 'sigfrigd@gmail.com', 'S', 'GD', '14e1b600b1fd579f47433b88e8d85291', 2),
(9, 'rodortcue@gmail.com123', '123', '123', '14e1b600b1fd579f47433b88e8d85291', 2),
(10, 'rodortcue@gmail.com23', 'asd', 'asd', '14e1b600b1fd579f47433b88e8d85291', 2),
(11, 'rodortcue@gmail.com222', 'asd', 'asd', '14e1b600b1fd579f47433b88e8d85291', 2),
(13, 'last@gmail.com', 'last', 'last last', '123456', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_clases`
--

CREATE TABLE IF NOT EXISTS `usuarios_clases` (
  `id_usuarios_clases` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL,
  `clase` int(11) NOT NULL,
  PRIMARY KEY (`id_usuarios_clases`),
  KEY `usuario` (`usuario`),
  KEY `clase` (`clase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `usuarios_clases`
--

INSERT INTO `usuarios_clases` (`id_usuarios_clases`, `usuario`, `clase`) VALUES
(1, 1, 1),
(2, 2, 1),
(6, 6, 2),
(7, 7, 1),
(8, 8, 2),
(9, 9, 2),
(11, 1, 2),
(12, 11, 1),
(13, 1, 3),
(14, 7, 3),
(15, 6, 1),
(16, 5, 2),
(20, 7, 2),
(21, 13, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`profesor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`marcador`) REFERENCES `marcadores` (`id_marcador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`marcador`) REFERENCES `marcadores` (`id_marcador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `marcadores`
--
ALTER TABLE `marcadores`
  ADD CONSTRAINT `marcadores_ibfk_1` FOREIGN KEY (`creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marcadores_ibfk_2` FOREIGN KEY (`clase`) REFERENCES `clases` (`id_clase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`examen`) REFERENCES `examenes` (`id_examen`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `opciones_ibfk_1` FOREIGN KEY (`pregunta`) REFERENCES `preguntas` (`id_pregunta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`examen`) REFERENCES `examenes` (`id_examen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_clases`
--
ALTER TABLE `usuarios_clases`
  ADD CONSTRAINT `usuarios_clases_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_clases_ibfk_2` FOREIGN KEY (`clase`) REFERENCES `clases` (`id_clase`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
