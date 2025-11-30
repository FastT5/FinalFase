-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-11-2025 a las 19:55:36
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
-- Base de datos: `dentalife_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `ID` int(11) NOT NULL,
  `FechaHora` datetime DEFAULT NULL,
  `EstatusCita` varchar(50) DEFAULT NULL,
  `MontoPagado` decimal(10,2) DEFAULT NULL,
  `PacienteID` int(11) DEFAULT NULL,
  `EmpleadoID` int(11) DEFAULT NULL,
  `ServicioID` int(11) DEFAULT NULL,
  `SucursalID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleadosucursal`
--

CREATE TABLE `empleadosucursal` (
  `EmpleadoID` int(11) DEFAULT NULL,
  `SucursalID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `ID` int(11) NOT NULL,
  `Titulo` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `FotoAntes` varchar(255) DEFAULT NULL,
  `FotoDespues` varchar(255) DEFAULT NULL,
  `Fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`ID`, `Titulo`, `Descripcion`, `FotoAntes`, `FotoDespues`, `Fecha`) VALUES
(1, 'Carla ', NULL, 'carillas-02-antes.webp', 'carillas-02-despues.webp', '2025-11-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion`
--

CREATE TABLE `informacion` (
  `ID` int(11) NOT NULL,
  `Historia` text DEFAULT NULL,
  `Mision` text DEFAULT NULL,
  `Vision` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informacion`
--

INSERT INTO `informacion` (`ID`, `Historia`, `Mision`, `Vision`) VALUES
(1, 'La clínica nació en el año 1970 con la visión de transformar la salud dental y crear sonrisas saludables en nuestra comunidad. Comenzamos como un modesto consultorio con una sola unidad dental y una inmensa pasión por el arte de la odontología y el cuidado del paciente.\n\nCon el paso del tiempo, gracias a la confianza depositada por nuestros pacientes, crecimos hasta convertirnos en una clínica integral, equipada con tecnología de última generación y un equipo de dentistas especializados en diferentes áreas.\n\nHoy en día, ofrecemos una amplia variedad de servicios, desde odontología preventiva y general hasta tratamientos estéticos y de ortodoncia, siempre con un enfoque en la calidad, la ética profesional y la satisfacción total del paciente.', 'Nuestra misión es ofrecer servicios odontológicos de la más alta calidad, brindando a nuestros pacientes una salud bucal óptima y sonrisas de las que puedan sentirse orgullosos a través de tratamientos de primer nivel.\n\nNos enfocamos en la prevención y el cuidado integral de cada paciente, combinando profesionalismo, tecnología avanzada y un servicio excepcional, cálido y personalizado.', 'Ser la clínica dental líder en la región, reconocida por nuestra excelencia en el servicio, la innovación constante en nuestros tratamientos y por brindar una experiencia positiva e inigualable a nuestros pacientes.\n\nQueremos seguir evolucionando e incorporando los últimos avances de la odontología moderna, sin perder jamás nuestra esencia de trato humano y compromiso con la salud de quienes nos confían su sonrisa.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `ID` int(11) NOT NULL,
  `Comentario` text DEFAULT NULL,
  `FechaResena` date DEFAULT NULL,
  `EsVisible` tinyint(1) DEFAULT NULL,
  `PacienteID` int(11) DEFAULT NULL,
  `CitaID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `ID` int(11) NOT NULL,
  `NombreRol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`ID`, `NombreRol`) VALUES
(1, 'Administrador'),
(2, 'Empleado'),
(3, 'Paciente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `ID` int(11) NOT NULL,
  `NombreServicio` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `TipoServicio` varchar(50) DEFAULT NULL,
  `Costo` decimal(10,2) DEFAULT NULL,
  `Estatus` varchar(20) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT 'general.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`ID`, `NombreServicio`, `Descripcion`, `TipoServicio`, `Costo`, `Estatus`, `Foto`) VALUES
(1, 'Limpieza Dental', 'Limpieza profunda con ultrasonido y pulido.', 'Odontología General', 500.00, 'Habilitada', 'limpieza.webp'),
(2, 'Blanqueamiento Láser', 'Aclara tus dientes hasta 3 tonos en una sesión.', 'Odontología Estética', 2500.00, 'Habilitada', 'blanqueamiento.webp'),
(3, 'Brackets Metálicos', 'Pago inicial para tratamiento de ortodoncia.', 'Especialidades', 3500.00, 'Habilitada', 'brackets.webp'),
(4, 'Extracción Simple', 'Extracción de pieza dental sin cirugía.', 'Odontología General', 800.00, 'Habilitada', 'extraccion.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `ID` int(11) NOT NULL,
  `PacienteID` int(11) NOT NULL,
  `ServicioID` int(11) NOT NULL,
  `TelefonoContacto` varchar(20) NOT NULL,
  `FechaSolicitada` datetime NOT NULL,
  `Estatus` varchar(20) DEFAULT 'Pendiente',
  `FechaSolicitud` datetime DEFAULT current_timestamp(),
  `SucursalID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `ID` int(11) NOT NULL,
  `NombreSucursal` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `TelefonoSucursal` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`ID`, `NombreSucursal`, `Direccion`, `TelefonoSucursal`) VALUES
(1, 'Sucursal Cadereyta', 'Gonzalitos 100 Oriente, entre Mutualismo y 20 de Noviembre, 67480 Cadereyta Jiménez, N.L.', '828-284-0000'),
(2, 'Sucursal San Nicolás', 'S. Cristóbal, Villas de San Cristobal 2do Sector, 66478 San Nicolás de los Garza, N.L.', '811-123-4567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `RolID` int(11) DEFAULT NULL,
  `Especialidad` varchar(100) DEFAULT NULL,
  `Biografia` text DEFAULT NULL,
  `Foto` varchar(255) DEFAULT 'doctor1.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nombre`, `Correo`, `Contrasena`, `Telefono`, `RolID`, `Especialidad`, `Biografia`, `Foto`) VALUES
(1, 'Admin Principal', 'admin@dentalife.com', 'admin123', '8110000000', 1, NULL, NULL, 'doctor1.jpg'),
(2, 'Carlos Mendoza', 'carlos@dentalife.com', 'doctor123', '8112223333', 2, 'Odontólogo General', 'Director de la clínica con 15 años de experiencia.', '2.webp'),
(3, 'Juan Pérez', 'juan@gmail.com', 'paciente123', '8114445555', 3, NULL, NULL, 'doctor1.jpg'),
(4, 'Edgardo Ortega', 'edgardo-gael12@hotmail.com', '12345678', NULL, 3, NULL, NULL, 'doctor1.jpg'),
(5, 'Reyna Rodriguez', 'Reinardz@gmail.com', 'reina123', NULL, 2, 'Maxilofacial ', NULL, 'Reyna.webp');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PacienteID` (`PacienteID`),
  ADD KEY `EmpleadoID` (`EmpleadoID`),
  ADD KEY `ServicioID` (`ServicioID`),
  ADD KEY `SucursalID` (`SucursalID`);

--
-- Indices de la tabla `empleadosucursal`
--
ALTER TABLE `empleadosucursal`
  ADD KEY `EmpleadoID` (`EmpleadoID`),
  ADD KEY `SucursalID` (`SucursalID`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `informacion`
--
ALTER TABLE `informacion`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PacienteID` (`PacienteID`),
  ADD KEY `CitaID` (`CitaID`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PacienteID` (`PacienteID`),
  ADD KEY `ServicioID` (`ServicioID`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD KEY `RolID` (`RolID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`PacienteID`) REFERENCES `usuarios` (`ID`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`EmpleadoID`) REFERENCES `usuarios` (`ID`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`ServicioID`) REFERENCES `servicios` (`ID`),
  ADD CONSTRAINT `citas_ibfk_4` FOREIGN KEY (`SucursalID`) REFERENCES `sucursales` (`ID`);

--
-- Filtros para la tabla `empleadosucursal`
--
ALTER TABLE `empleadosucursal`
  ADD CONSTRAINT `empleadosucursal_ibfk_1` FOREIGN KEY (`EmpleadoID`) REFERENCES `usuarios` (`ID`),
  ADD CONSTRAINT `empleadosucursal_ibfk_2` FOREIGN KEY (`SucursalID`) REFERENCES `sucursales` (`ID`);

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`PacienteID`) REFERENCES `usuarios` (`ID`),
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`CitaID`) REFERENCES `citas` (`ID`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`PacienteID`) REFERENCES `usuarios` (`ID`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`ServicioID`) REFERENCES `servicios` (`ID`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`RolID`) REFERENCES `roles` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
