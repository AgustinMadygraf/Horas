-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-12-2023 a las 15:55:46
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `horas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_asociados`
--

CREATE TABLE `informacion_asociados` (
  `id_asociado` int(11) NOT NULL,
  `legajo` varchar(4) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellido` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `informacion_asociados`
--

INSERT INTO `informacion_asociados` (`id_asociado`, `legajo`, `nombre`, `apellido`) VALUES
(1, '107', 'Antonio', 'Lopez'),
(2, '238', 'Oreste Mariano', 'Montenegro'),
(3, '240', 'Julian', 'Ortiz'),
(4, '298', 'Jose Andres', 'Ponce'),
(5, '493', 'Eduardo', 'Ayala'),
(6, '532', 'Hugo', 'Vera'),
(7, '574', 'Daniel', 'Arriondo'),
(8, '591', 'Julio', 'Hidalgo'),
(9, '666', 'Gustavo', 'Medrano'),
(10, '835', 'Cristian German', 'Pena'),
(11, '852', 'Ramon', 'Zalazar'),
(12, '853', 'Pablo', 'Paz'),
(13, '857', 'Oscar', 'Bentancourt'),
(14, '932', 'Martin', 'Killing'),
(15, '962', 'Hector Javier', 'Ballesteros'),
(16, '970', 'Miguel Angel', 'Gomez'),
(17, '971', 'Fernando Ariel', 'Utrera'),
(18, '974', 'Facundo Matias', 'Gomez'),
(19, '986', 'Ariel Gustavo', 'Fernandez'),
(20, '1032', 'Rolando Hector', 'Falcon'),
(21, '1035', 'Luis Fernando', 'Serrano'),
(22, '1035', 'Luis Fernando', 'Serrano'),
(23, '1038', 'Cristian Fabian', 'Ferreyra'),
(24, '1046', 'Silverio', 'Sanchez'),
(25, '1047', 'Angel Diego Jose', 'Galeano'),
(26, '1050', 'Gerardo Gaston', 'Leguizamon'),
(27, '1056', 'Matias Osvaldo', 'Hug'),
(28, '1072', 'Damian Emilio', 'Conti'),
(29, '1081', 'Cristian Gabriel', 'Cañete'),
(30, '1083', 'Jorge Gabriel', 'Medina'),
(31, '1118', 'Sebastian Anibal', 'Arrascaeta'),
(32, '1122', 'Mauro Maximiliano', 'Zuccarotto'),
(33, '1129', 'Alcira Amalia', 'Landeira'),
(34, '1137', 'Enrique Emiliano', 'Diaz'),
(35, '1153', 'Sandro Ariel', 'Salazar'),
(36, '1163', 'Gustavo David', 'Brito'),
(37, '1189', 'Martin Andres', 'Arari'),
(38, '1202', 'Rodolfo Walter', 'Osuna'),
(39, '1216', 'Ruben Martin', 'Dirroco'),
(40, '1228', 'Adrian Israel', 'Mancilla'),
(41, '1236', 'Oscar', 'Velazco'),
(42, '1241', 'Jonathan Ezequiel', 'Ledesma'),
(43, '1244', 'Marcelo Adrian Elise', 'Almada'),
(44, '1245', 'Jonatan', 'Gue'),
(45, '1291', 'Jose Antonio', 'Zarate'),
(46, '1310', 'Abel Alejandro', 'Silva'),
(47, '1315', 'Armando Federico', 'Pignataro'),
(48, '1317', 'Emanuel Omar', 'Garzia'),
(49, '1320', 'Carlos Eduardo', 'Cardozo'),
(50, '1325', 'Rodrigo Cristian', 'Rosales Arias'),
(51, '1340', 'David Leonardo', 'Valera Vasquez'),
(52, '1346', 'Julio Dario', 'Almaraz'),
(53, '1347', 'Carlos Alberto', 'Lescano'),
(54, '1352', 'Ricardo German', 'Miño'),
(55, '1388', 'German Diego', 'Gassibe'),
(56, '1395', 'Marcelo Omar', 'Ortega'),
(57, '1397', 'Martin Ezequiel', 'Chaile'),
(58, '1404', 'Agustin Leonardo', 'Bustos'),
(59, '1406', 'Jimena', 'Caruso'),
(60, '1407', 'Vanina', 'Mancuso'),
(61, '1412', 'Maria del Carmen', 'Vallejos'),
(62, '1413', 'Juana Rosa', 'Laime'),
(63, '1414', 'Leandro', 'Quinzano'),
(64, '1415', 'Monica Patricia', 'Butiler'),
(65, '1417', 'Ingrid Geraldina', 'Alarcon'),
(66, '1418', 'Lucrecia Viviana', 'Borge'),
(67, '1420', 'Maria Celeste', 'Paz'),
(68, '1421', 'Anahi Daiana', 'Almada'),
(69, '1422', 'Erica Victoria', 'Gramajo'),
(70, '1423', 'Rocio Noemi', 'Fernandez'),
(71, '1424', 'Monica', 'Salazar'),
(72, '1425', 'Maria de los Angeles', 'Plett'),
(73, '1426', 'Maria Alejandra Leon', 'Cortellcubi'),
(74, '1428', 'Emiliana Hilda', 'Andrade'),
(75, '1430', 'Eliana Edith', 'Villanueva'),
(76, '1431', 'Norma Beatriz', 'Barrientos'),
(77, '1432', 'Maria Vanina', 'Reboredo'),
(78, '1441', 'Gabriela del Carmen', 'Vera'),
(79, '1442', 'Juan Domingo', 'Peralta'),
(80, '1446', 'Nicolas', 'Almarante'),
(81, '1456', 'Mariana Soledad', 'Hogas'),
(82, '1460', 'Roberto Cesar', 'Amador'),
(83, '1461', 'Hugo Ricardo', 'Santillan'),
(84, '1466', 'Martin', 'Gonzalez Rojas'),
(85, '1469', 'Jonatan Eduardo', 'Guereñu'),
(86, '1471', 'Homero Miguel', 'Agüero'),
(87, '1474', 'Noelia Anahi', 'Oviedo'),
(88, '1478', 'Jorge Claudio Jesus', 'Gomez'),
(89, '1481', 'Roberto Antonio', 'Torres'),
(90, '1482', 'Gustavo Orlando', 'Frias'),
(91, '1484', 'Arnaldo Ramon', 'Sanchez'),
(92, '1485', 'Lucas Gaston', 'Vera'),
(93, '1487', 'Franco Gabriel', 'Urquiza'),
(94, '1488', 'Maria Belen', 'Medina'),
(95, '1490', 'Mercedes Liliana', 'Fretes'),
(96, '1491', 'Claudia Ester', 'D´elelessis'),
(97, '1492', 'Cintia Mariela', 'Chaves'),
(98, '1495', 'Silvina Valeria', 'Castro'),
(99, '1496', 'Agustin', 'Frers'),
(100, '1497', 'Eymy', 'Najarro');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  ADD PRIMARY KEY (`id_asociado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  MODIFY `id_asociado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
