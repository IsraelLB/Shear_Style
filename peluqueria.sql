-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-03-2024 a las 12:01:49
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
-- Base de datos: `peluqueria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `ID_PELU` int(11) NOT NULL,
  `ID_SERV` int(11) NOT NULL,
  `ID_CLI` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `hora` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Telefono` varchar(20) NOT NULL,
  `Contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `ID_CLI` int(11) NOT NULL,
  `ID_CIT` int(11) NOT NULL,
  `ID_SERV` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquero`
--

CREATE TABLE `peluquero` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Foto` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(255) NOT NULL,
  `Descripcion` text NOT NULL,
  `Duracion` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  
  ADD KEY `ID_PELU` (`ID_PELU`),
  ADD KEY `ID_SERV` (`ID_SERV`),
  ADD KEY `ID_CLI` (`ID_CLI`);


--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD KEY `ID_SERV` (`ID_SERV`),
  ADD KEY `ID_CLI` (`ID_CLI`),
  ADD KEY `ID_CIT` (`ID_CIT`);

--
-- Indices de la tabla `peluquero`
--
ALTER TABLE `peluquero`
 
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indices de la tabla `servicio`
--

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`ID_PELU`) REFERENCES `peluquero` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`ID_SERV`) REFERENCES `servicio` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`ID_CLI`) REFERENCES `cliente` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`ID_SERV`) REFERENCES `servicio` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`ID_CLI`) REFERENCES `cliente` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `pago_ibfk_3` FOREIGN KEY (`ID_CIT`) REFERENCES `cita` (`ID`) ON DELETE CASCADE;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
