-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-01-2025 a las 19:55:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

CREATE DATABASE actividad1_backend;
USE actividad1_backend;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `actividad1_backend`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actores`
--

CREATE TABLE `actores` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nacionalidad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actores`
--

INSERT INTO `actores` (`id`, `nombres`, `apellidos`, `fecha_nacimiento`, `nacionalidad`) VALUES
(90, 'Bob', 'Dylanb', '1965-09-12', 'FR'),
(94, 'Michaell', 'Jackson', '2000-09-12', 'ESP'),
(95, 'Bob', 'Marlyxzzx', '1950-09-12', 'JAM'),
(96, 'JIMENA', 'RESTREPO', '2000-09-12', 'COL'),
(97, 'Rafael', 'Novoass', '1980-09-12', 'CAN'),
(99, 'Julian', 'Roman', '2000-09-12', 'IRL'),
(100, 'kjsjsjh', 'losdj', '1888-08-12', 'sj'),
(101, 'Claro', 'Luna', '1876-08-09', 'POL'),
(102, 'gres', 'fdcd', '1234-04-12', 'fg'),
(103, 'Antonio', 'Feliz', '2000-09-12', 'AFG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directores`
--

CREATE TABLE `directores` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nacionalidad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `directores`
--

INSERT INTO `directores` (`id`, `nombres`, `apellidos`, `fecha_nacimiento`, `nacionalidad`) VALUES
(2, 'Carlos', 'Belgrado', '1980-09-12', 'MEX'),
(3, 'Cristopher', 'Anoldo', '1970-08-09', 'ITL'),
(6, 'Gabriel', 'Rodriguez', '1995-09-12', 'HON');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `iso_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `idiomas`
--

INSERT INTO `idiomas` (`id`, `nombre`, `iso_code`) VALUES
(1, 'ESPa', 'EP'),
(2, 'Ingles', 'EN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataformas`
--

CREATE TABLE `plataformas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plataformas`
--

INSERT INTO `plataformas` (`id`, `nombre`) VALUES
(1, 'Netflix'),
(2, 'HBO'),
(3, 'Amazon Prime'),
(4, 'Disney');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `series`
--

CREATE TABLE `series` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `plataforma_id` int(11) DEFAULT NULL,
  `director_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `series`
--

INSERT INTO `series` (`id`, `titulo`, `plataforma_id`, `director_id`) VALUES
(1, 'Dr House', 1, NULL),
(2, 'Caracoles', 3, NULL),
(3, 'Bleach', 1, NULL),
(4, 'Dragon Ball', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `series_actores`
--

CREATE TABLE `series_actores` (
  `id` int(11) NOT NULL,
  `serie_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `series_actores`
--

INSERT INTO `series_actores` (`id`, `serie_id`, `actor_id`) VALUES
(5, 4, 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `series_idiomas`
--

CREATE TABLE `series_idiomas` (
  `id` int(11) NOT NULL,
  `serie_id` int(11) NOT NULL,
  `idioma_id` int(11) NOT NULL,
  `tipo` enum('audio','subtítulo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `series_idiomas`
--

INSERT INTO `series_idiomas` (`id`, `serie_id`, `idioma_id`, `tipo`) VALUES
(1, 1, 1, 'audio'),
(2, 1, 1, 'subtítulo'),
(3, 2, 1, 'audio'),
(4, 2, 1, 'subtítulo'),
(5, 3, 1, 'audio'),
(6, 3, 1, 'subtítulo'),
(7, 4, 1, 'audio'),
(8, 4, 2, 'audio'),
(9, 4, 1, 'subtítulo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actores`
--
ALTER TABLE `actores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `directores`
--
ALTER TABLE `directores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iso_code` (`iso_code`);

--
-- Indices de la tabla `plataformas`
--
ALTER TABLE `plataformas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plataforma_id` (`plataforma_id`),
  ADD KEY `director_id` (`director_id`);

--
-- Indices de la tabla `series_actores`
--
ALTER TABLE `series_actores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serie_id` (`serie_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indices de la tabla `series_idiomas`
--
ALTER TABLE `series_idiomas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serie_id` (`serie_id`),
  ADD KEY `idioma_id` (`idioma_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actores`
--
ALTER TABLE `actores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `directores`
--
ALTER TABLE `directores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `plataformas`
--
ALTER TABLE `plataformas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `series`
--
ALTER TABLE `series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `series_actores`
--
ALTER TABLE `series_actores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `series_idiomas`
--
ALTER TABLE `series_idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `series`
--
ALTER TABLE `series`
  ADD CONSTRAINT `series_ibfk_1` FOREIGN KEY (`plataforma_id`) REFERENCES `plataformas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `series_ibfk_2` FOREIGN KEY (`director_id`) REFERENCES `directores` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `series_actores`
--
ALTER TABLE `series_actores`
  ADD CONSTRAINT `series_actores_ibfk_1` FOREIGN KEY (`serie_id`) REFERENCES `series` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `series_actores_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `actores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `series_idiomas`
--
ALTER TABLE `series_idiomas`
  ADD CONSTRAINT `series_idiomas_ibfk_1` FOREIGN KEY (`serie_id`) REFERENCES `series` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `series_idiomas_ibfk_2` FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
