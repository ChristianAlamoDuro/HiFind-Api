--
-- Base de datos: `HiFind_api`
--

-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Volcado de datos para la tabla `users`
--


--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_movie` tinyint(1) DEFAULT NULL,
  `is_special_movie` tinyint(1) DEFAULT NULL,
  `is_special_game` tinyint(1) DEFAULT NULL,
  `is_game` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `categories`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Volcado de datos para la tabla `categories`
--

--
-- Estructura de tabla para la tabla `games`
--

CREATE TABLE `games` (
  `id` int(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `out_date` varchar(255) DEFAULT NULL,
  `public_directed` varchar(3) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `sinopsis` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;


CREATE TABLE `categories_games` (
  `id` int(255) NOT NULL,
  `game_id` int(255) NOT NULL,
  `categorie_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `categories_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_game_id_categories_games` (`game_id`),
  ADD KEY `fk_categorie_id_categories_games` (`categorie_id`);
ALTER TABLE `categories_games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `categories_games`
  ADD CONSTRAINT `fk_categorie_id_categories_games` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_game_id_categories_games` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
--

CREATE TABLE `marks_users_games` (
  `id` int(255) NOT NULL,
  `game_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `mark` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `marks_users_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_game_id_mark_game` (`game_id`),
  ADD KEY `fk_user_id_mark_game` (`user_id`);
ALTER TABLE `marks_users_games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
  ALTER TABLE `marks_users_games`
  ADD CONSTRAINT `fk_game_id_mark_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `fk_user_id_mark_game` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Volcado de datos para la tabla `marks_users_games`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'admin', 'ROLE_ADMIN', 'admin@admin.com', 'admin1234', NULL, NULL, NULL, NULL),
(2, 'Chrisatm13', 'ROLE_USER', 'chrisatm13@chrisatm13.com', 'Secret1234', NULL, NULL, NULL, NULL),
(3, 'john', 'ROLE_USER', 'pepito@gmail.com', 'c8cdf720db5562a039be5d81c51a07c5120eaf0bf142b2144f1a1eb7a95678d3', NULL, '2019-05-23 07:49:08', '2019-05-23 07:49:08', NULL);

INSERT INTO `games` (`id`, `name`, `out_date`, `public_directed`, `duration`, `sinopsis`, `image`) VALUES
(1, 'Red dead redemption', '2019-05-30 00:00:00', '+18', '1.5', 'Juego de matar a caballo oeste gromenaguer', NULL),
(2, 'forza horizon 3', '2019-10-14 00:00:00', '+16', '0', 'juego de carreras to guapo', 'not found'),
(3, 'red hot chillippeper the game', '2019-05-30 00:00:00', '+18', '2', 'asafaf', 'not found'),
(8, 'forza horizon 4', '2019-10-14 00:00:00', '+16', '0', 'juego de carreras to guapo', 'not found'),
(39, 'Kingdom hearts 3', '2019-02-10 00:00:00', 'TP', '30', 'Juego de square enix ambientadp en el universo disney', 'not found');


INSERT INTO `categories` (`id`, `name`, `is_movie`, `is_special_movie`, `is_special_game`, `is_game`) VALUES
(1, 'Aventuras', 0, NULL, 0, 1),
(2, 'Accion', 0, NULL, 0, 1),
(3, 'infantil', 0, NULL, 0, 0),
(4, 'tiros', 0, NULL, 0, 1);


INSERT INTO `categories_games` (`id`, `game_id`, `categorie_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 3),
(4, 8, 1),
(5, 8, 2),
(6, 39, 1),
(7, 39, 3);


INSERT INTO `marks_users_games` (`id`, `game_id`, `user_id`, `mark`) VALUES
(1, 1, 2, 10),
(2, 1, 2, 10),
(3, 1, 3, 7);

COMMIT;