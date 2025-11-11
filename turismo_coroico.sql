-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-11-2025 a las 15:15:15
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
-- Base de datos: `turismo_coroico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_lugar` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text NOT NULL,
  `fecha_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `id_usuario`, `id_lugar`, `calificacion`, `comentario`, `fecha_comentario`) VALUES
(1, 2, 1, 5, 'Excelente vista panorámica! El amanecer desde este mirador es espectacular. Totalmente recomendado para fotógrafos.', '2025-10-25 12:30:00'),
(2, 3, 2, 4, 'Las cascadas son hermosas y el sendero está bien mantenido. Solo le falta un poco más de señalización en algunos tramos.', '2025-10-26 19:20:00'),
(3, 4, 3, 5, 'Una aventura increíble! La caminata es desafiante pero vale totalmente la pena. La naturaleza es impresionante.', '2025-10-28 16:10:00'),
(4, 5, 4, 4, 'Muy interesante conocer la historia afroboliviana. El museo tiene buena información aunque podría tener más piezas en exhibición.', '2025-10-29 14:45:00'),
(5, 2, 5, 5, 'El mejor lugar para ver el Illimani en días despejados. Llegamos temprano y pudimos ver las montañas nevadas perfectamente.', '2025-11-01 11:15:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares_turisticos`
--

CREATE TABLE `lugares_turisticos` (
  `id_lugar` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` enum('mirador','cascada','aventura','cultural') NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `precio_entrada` decimal(10,2) NOT NULL DEFAULT 0.00,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL,
  `imagen_lugar` varchar(255) DEFAULT 'default.jpg',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugares_turisticos`
--

INSERT INTO `lugares_turisticos` (`id_lugar`, `nombre`, `descripcion`, `categoria`, `direccion`, `precio_entrada`, `horario_apertura`, `horario_cierre`, `imagen_lugar`, `fecha_creacion`) VALUES
(1, 'Mirador El Calvario', 'Vista panorámica espectacular de Coroico y los valles circundantes. Ideal para fotografía al amanecer y atardecer.', 'mirador', 'Calle Principal, subida al cerro', 10.00, '06:00:00', '18:00:00', 'calvario.jpg', '2025-11-05 02:12:36'),
(2, 'Cascadas de Vagantes', 'Hermosas cascadas naturales rodeadas de vegetación tropical. Incluye senderos y áreas para picnic.', 'cascada', 'Comunidad de Vagantes, 8 km de Coroico', 15.00, '08:00:00', '17:00:00', 'vagantes.jpg', '2025-11-05 02:12:36'),
(3, 'Sendero de las Yungas', 'Caminata de aventura por la selva con flora y fauna variada. Nivel de dificultad moderado.', 'aventura', 'Carretera antigua a Caranavi', 20.00, '07:00:00', '16:00:00', 'yungas.jpg', '2025-11-05 02:12:36'),
(4, 'Museo Afroboliviano', 'Museo dedicado a la historia y cultura de la comunidad afroboliviana en la región de los Yungas.', 'cultural', 'Plaza Principal de Coroico', 8.00, '09:00:00', '17:00:00', 'museo.jpg', '2025-11-05 02:12:36'),
(5, 'Mirador Turístico San José', 'Punto panorámico cerca de Coroico para disfrutar de vistas del valle de Los Yungas y el verde entorno-montaña.', 'mirador', 'Cerca de Mercado Modelo San José de Coroico, La Paz;', 12.00, '06:00:00', '18:00:00', 'uchumachi.jpg', '2025-11-05 02:12:36'),
(6, 'Refugio de Vida Silvestre Senda Verde', 'Refugio de fauna donde se rescatan animales silvestres, recorrido por senderos en la selva, entre montaña y vegetación típica de Los Yungas.', 'aventura', 'Comunidad Yolosa, municipio de Coroico, La Paz.', 110.00, '10:00:00', '16:00:00', 'default.jpg', '2025-11-11 02:50:36'),
(7, 'Población de Tocaña', 'Comunidad afro-boliviana, con su historia, tradiciones, museo y entorno rural en Los Yungas.', 'cultural', 'Aproximadamente 45 minutos desde Coroico (altitud ~1.344 m)', 35.00, '10:00:00', '16:00:00', 'default.jpg', '2025-11-11 13:26:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tour` int(11) NOT NULL,
  `fecha_tour` date NOT NULL,
  `cantidad_personas` int(11) NOT NULL DEFAULT 1,
  `precio_total` decimal(10,2) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `id_tour`, `fecha_tour`, `cantidad_personas`, `precio_total`, `fecha_reserva`) VALUES
(1, 2, 1, '2025-11-10', 2, 360.00, '2025-11-01 14:30:00'),
(2, 3, 2, '2025-11-12', 1, 250.00, '2025-11-02 18:20:00'),
(3, 4, 4, '2025-11-08', 3, 360.00, '2025-11-03 13:15:00'),
(4, 5, 3, '2025-11-15', 2, 300.00, '2025-11-03 20:45:00'),
(5, 2, 5, '2025-11-20', 4, 800.00, '2025-11-04 15:00:00'),
(6, 3, 2, '2025-11-07', 3, 750.00, '2025-11-06 02:30:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tours`
--

CREATE TABLE `tours` (
  `id_tour` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cupo_maximo` int(11) NOT NULL DEFAULT 10,
  `imagen_tour` varchar(255) DEFAULT 'default.jpg',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tours`
--

INSERT INTO `tours` (`id_tour`, `nombre`, `descripcion`, `precio`, `cupo_maximo`, `imagen_tour`, `fecha_creacion`) VALUES
(1, 'Tour Completo Coroico', 'Recorrido de día completo visitando los principales atractivos: Mirador El Calvario, Cascadas de Vagantes y Museo Afroboliviano. Incluye transporte, guía y almuerzo.', 180.00, 15, 'default.jpg', '2025-11-05 02:12:36'),
(2, 'Aventura en las Yungas', 'Experiencia de trekking por senderos de selva tropical, visita a cascadas ocultas y observación de aves. Incluye equipo, guía especializado y snacks.', 250.00, 10, 'default.jpg', '2025-11-05 02:12:36'),
(3, 'Tour Cultural y Gastronómico', 'Descubre la cultura afroboliviana, visita al museo, prueba de comida tradicional y taller de danza. Incluye transporte y almuerzo típico.', 150.00, 20, 'default.jpg', '2025-11-05 02:12:36'),
(4, 'Amanecer en los Miradores', 'Salida temprano para observar el amanecer desde los mejores miradores de Coroico. Incluye desayuno campestre y fotografía profesional.', 120.00, 12, 'default.jpg', '2025-11-05 02:12:36'),
(5, 'Cascadas y Naturaleza', 'Tour especializado en las cascadas de la región. Visita a 3 cascadas diferentes con tiempo para nadar. Incluye transporte, guía y box lunch.', 200.00, 15, 'default.jpg', '2025-11-05 02:12:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` enum('admin','turista') NOT NULL DEFAULT 'turista',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `email`, `password`, `rol`, `fecha_registro`) VALUES
(1, 'Andev Escobar', 'admin@coroico.com', 'admin123', 'admin', '2025-11-05 02:12:34'),
(2, 'María López', 'maria.lopez@gmail.com', 'maria123', 'turista', '2025-11-05 02:12:34'),
(3, 'Carlos Mendoza', 'carlos.mendoza@hotmail.com', 'carlos123', 'turista', '2025-11-05 02:12:34'),
(4, 'Ana Quispe', 'ana.quispe@yahoo.com', 'ana123', 'turista', '2025-11-05 02:12:34'),
(5, 'Roberto Mamani', 'roberto.mamani@gmail.com', 'roberto123', 'turista', '2025-11-05 02:12:34'),
(6, 'Andev', 'andev@gmail.com', '123456', 'turista', '2025-11-10 21:56:04');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_comentario_lugar` (`id_lugar`),
  ADD KEY `idx_comentario_calificacion` (`calificacion`);

--
-- Indices de la tabla `lugares_turisticos`
--
ALTER TABLE `lugares_turisticos`
  ADD PRIMARY KEY (`id_lugar`),
  ADD KEY `idx_lugar_categoria` (`categoria`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_tour` (`id_tour`),
  ADD KEY `idx_reserva_fecha` (`fecha_tour`);

--
-- Indices de la tabla `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id_tour`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuario_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `lugares_turisticos`
--
ALTER TABLE `lugares_turisticos`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tours`
--
ALTER TABLE `tours`
  MODIFY `id_tour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_lugar`) REFERENCES `lugares_turisticos` (`id_lugar`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_tour`) REFERENCES `tours` (`id_tour`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
