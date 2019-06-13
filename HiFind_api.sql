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
  ADD CONSTRAINT `fk_categorie_id_categories_games` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_game_id_categories_games` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
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
  ADD CONSTRAINT `fk_game_id_mark_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id_mark_game` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Volcado de datos para la tabla `marks_users_games`
--

--
-- Estructura de tabla para la tabla `movies`
--

CREATE TABLE directors(
    id                  int(255) auto_increment not null,
    name	              varchar(255) not null,
    surname             varchar(255),
    birthday            varchar(255) DEFAULT NULL,
    biography           varchar(255),
    image               varchar(255),
    CONSTRAINT pk_directors PRIMARY KEY (id)
)ENGINE=InnoDb;


CREATE TABLE actors(
    id                  int(255) auto_increment not null,
    name	              varchar(255) not null,
    surname             varchar(255),
    birthday            varchar(255) DEFAULT NULL,
    biography           varchar(255),
    image               varchar(255),
    CONSTRAINT pk_actors PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE movies(
    id                  int(255) auto_increment not null,
    title	              varchar(255) not null,
    out_date            varchar(20) DEFAULT NULL,
    public_directed     varchar(3) DEFAULT NULL,
    film_producer       varchar(255) DEFAULT NULL,
    duration            float DEFAULT NULL,
    sinopsis            varchar(255) DEFAULT NULL,
    image               varchar(255) DEFAULT NULL,
    CONSTRAINT pk_movies PRIMARY KEY (id)
)ENGINE=InnoDb;


CREATE TABLE actors_movies(
    id                  int(255) auto_increment not null,
    actor_id            int(255) not null,
    movie_id            int(255) not null,
    
    CONSTRAINT pk_actors_movies PRIMARY KEY (id),
    CONSTRAINT fk_actor FOREIGN KEY (actor_id) REFERENCES actors (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_movie FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDb;


CREATE TABLE directors_movies(
    id                  int(255) auto_increment not null,
    director_id         int(255) not null,
    movie_id            int(255) not null,
    
    CONSTRAINT pk_directors_movies PRIMARY KEY (id),
    CONSTRAINT fk_director_id_directors_movies FOREIGN KEY (director_id) REFERENCES directors (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_movie_director_ids_movies FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDb;


CREATE TABLE categories_movies (
    id 			            int(255) auto_increment not null,
    movie_id 		        int(255) not null,
    category_id 	      int(255) not null,

    CONSTRAINT pk_categories_movies PRIMARY KEY (id),
    CONSTRAINT fk_category_id_categories_movies FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_movie_id_categories_movies FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE marks_users_movies (
  id                    int(255) auto_increment not null,
  movie_id              int(255) NOT NULL,
  user_id               int(255) NOT NULL,
  mark                  int(5) NOT NULL,
  CONSTRAINT pk_marks_users_movies PRIMARY KEY (id),
  CONSTRAINT fk_movie_id_marks_users_movies FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_user_id_marks_users_movies FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
-- --
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'admin', 'ROLE_ADMIN', 'admin@admin.com', '2bb80d537b1da3e38bd30361aa855686bde0eacd7162fef6a25fe97bf527a25b', NULL, NULL, NULL, NULL);

-- INSERT INTO `games` (`id`, `name`, `out_date`, `public_directed`, `duration`, `sinopsis`, `image`) VALUES
-- (1, 'Red dead redemption', '2019-05-30 00:00:00', '+18', '1.5', 'Juego de matar a caballo oeste gromenaguer', NULL),
-- (2, 'forza horizon 3', '2019-10-14 00:00:00', '+16', '0', 'juego de carreras to guapo', 'not found'),
-- (3, 'red hot chillippeper the game', '2019-05-30 00:00:00', '+18', '2', 'asafaf', 'not found'),
-- (8, 'forza horizon 4', '2019-10-14 00:00:00', '+16', '0', 'juego de carreras to guapo', 'not found'),
-- (39, 'Kingdom hearts 3', '2019-02-10 00:00:00', 'TP', '30', 'Juego de square enix ambientadp en el universo disney', 'not found');


-- INSERT INTO `categories` (`id`, `name`, `is_movie`, `is_special_movie`, `is_special_game`, `is_game`) VALUES
-- (1, 'Aventuras', 0, NULL, 0, 1),
-- (2, 'Accion', 0, NULL, 0, 1),
-- (3, 'infantil', 0, NULL, 0, 0),
-- (4, 'tiros', 0, NULL, 0, 1);


-- INSERT INTO `categories_games` (`id`, `game_id`, `categorie_id`) VALUES
-- (1, 1, 1),
-- (2, 1, 2),
-- (3, 3, 3),
-- (4, 8, 1),
-- (5, 8, 2),
-- (6, 39, 1),
-- (7, 39, 3);


-- INSERT INTO `marks_users_games` (`id`, `game_id`, `user_id`, `mark`) VALUES
-- (1, 1, 2, 10),
-- (2, 1, 2, 10),
-- (3, 1, 3, 7);

-- INSERT INTO `actors` (`id`, `name`, `surname`, `birthday`, `biography`, `image`) VALUES
-- (4, 'johny', 'melavo', '2019-10-14', 'Hero of the middle earth.', 'frodo.png'),
-- (5, 'juan', 'pene flacido', '2019-10-15', 'Savior of Frodo. Hero of the middle earth.', 'sam.png');

-- INSERT INTO `directors` (`id`, `name`, `surname`, `birthday`, `biography`, `image`) VALUES 
-- (1, 'Martin', 'Scorsese', '2019-10-16', 'Director of the wold of Wall Street and others.', 'Scorsese.png'), 
-- (2, 'Steven', 'Soderbergh', '2019-05-14 00:00:00', 'Director of Oceans eleven and others.', 'Steven.png');

-- INSERT INTO `movies` (`id`, `title`, `out_date`, `public_directed`, `film_producer`, `duration`, `sinopsis`, `image`) VALUES 
-- ('1', 'the lord of the ring the fellowship of the ring', '2019-03-03 00:00:00', '12', 'no clue', '228', 'The Lord of the Rings: The Fellowship of the Ring is a 2001 epic fantasy adventure film directed by Peter Jackson based on the first volume of J. R. R. Tolkiens The Lord of the Rings.', 'lotr.png'), 
-- ('2', 'Best movie', '2019-10-17', '7', 'besrt producer', '120', 'best movie sinopsis', 'best.png');

-- INSERT INTO `actors_movies` (`id`, `actor_id`, `movie_id`) VALUES 
-- ('1', '1', '1'), 
-- ('2', '2', '2');

-- INSERT INTO `directors_movies` (`id`, `director_id`, `movie_id`) VALUES 
-- ('1', '1', '2'), 
-- ('2', '2', '1');

-- INSERT INTO `categories_movies` (`id`, `movie_id`, `category_id`) VALUES 
-- ('1', '1', '2'), 
-- ('2', '2', '3');

-- INSERT INTO `marks_users_movies` (`id`, `movie_id`, `user_id`, `mark`) VALUES 
-- ('1', '2', '1', '9'), 
-- ('2', '1', '2', '10');

COMMIT;