CREATE DATABASE IF NOT EXISTS HiFind_api;
USE HiFind_api;
CREATE TABLE users(
    id                  int(255) auto_increment not null,
    username            varchar(50) not null,
    role                varchar(20),
    email               varchar(255) not null,
    password            varchar(255) not null,
    image               varchar(255),
    created_at          datetime DEFAULT NULL,
    updated_at          datetime DEFAULT NULL,
    remember_token      varchar(255),
    CONSTRAINT pk_users PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE categories (
  id int(255) NOT NULL,
  name varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE categories_games (
  id int(255) NOT NULL,
  game_id int(255) NOT NULL,
  categorie_id int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE games (
  id int(255) NOT NULL,
  name varchar(100) DEFAULT NULL,
  out_date datetime DEFAULT NULL,
  public_directed varchar(3) DEFAULT NULL,
  duration float DEFAULT NULL,
  sinopsis varchar(255) DEFAULT NULL,
  image varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE marks_users_games (
  id int(255) NOT NULL,
  game_id int(255) NOT NULL,
  user_id int(255) NOT NULL,
  mark int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `categories_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_game_id_categories_games` (`game_id`),
  ADD KEY `fk_categorie_id_categories_games` (`categorie_id`);
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `marks_users_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_game_id_mark_game` (`game_id`),
  ADD KEY `fk_user_id_mark_game` (`user_id`);
ALTER TABLE `categories`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `categories_games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `marks_users_games`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `categories_games`
  ADD CONSTRAINT `fk_categorie_id_categories_games` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_game_id_categories_games` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
ALTER TABLE `marks_users_games`
  ADD CONSTRAINT `fk_game_id_mark_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `fk_user_id_mark_game` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

CREATE TABLE directors(
    id                  int(255) auto_increment not null,
    name	              varchar(20) not null,
    surname             varchar(100),
    birthday            datetime DEFAULT NULL,
    biography           varchar(255),
    image               varchar(255),
    CONSTRAINT pk_directors PRIMARY KEY (id)
)ENGINE=InnoDb;


CREATE TABLE actors(
    id                  int(255) auto_increment not null,
    name	              varchar(20) not null,
    surname             varchar(100),
    birthday            datetime DEFAULT NULL,
    biography           varchar(255),
    image               varchar(255),
    CONSTRAINT pk_actors PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE movies(
    id                  int(255) auto_increment not null,
    title	              varchar(20) not null,
    out_date            datetime DEFAULT NULL,
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
    CONSTRAINT fk_actor FOREIGN KEY (actor_id) REFERENCES actors (id),
    CONSTRAINT fk_movie FOREIGN KEY (movie_id) REFERENCES movies (id)
)ENGINE=InnoDb;


CREATE TABLE directors_movies(
    id                  int(255) auto_increment not null,
    director_id         int(255) not null,
    movie_id            int(255) not null,
    
    CONSTRAINT pk_directors_movies PRIMARY KEY (id),
    CONSTRAINT fk_director_id_directors_movies FOREIGN KEY (director_id) REFERENCES directors (id),
    CONSTRAINT fk_movie_director_ids_movies FOREIGN KEY (movie_id) REFERENCES movies (id)
)ENGINE=InnoDb;


CREATE TABLE categories_movies (
    id 			            int(255) auto_increment not null,
    movie_id 		        int(255) not null,
    category_id 	      int(255) not null,

    CONSTRAINT pk_categories_movies PRIMARY KEY (id),
    CONSTRAINT fk_category_id_categories_movies FOREIGN KEY (category_id) REFERENCES categories (id),
    CONSTRAINT fk_movie_id_categories_movies FOREIGN KEY (movie_id) REFERENCES movies (id)
) ENGINE=InnoDB;

CREATE TABLE marks_users_movies (
  id                    int(255) auto_increment not null,
  movie_id              int(255) NOT NULL,
  user_id               int(255) NOT NULL,
  mark                  int(5) NOT NULL,
  CONSTRAINT pk_marks_users_movies PRIMARY KEY (id),
  CONSTRAINT fk_movie_id_marks_users_movies FOREIGN KEY (movie_id) REFERENCES movies (id),
  CONSTRAINT fk_user_id_marks_users_movies FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB;


# RELLENO 
# CREACION DE USUARIOS
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES (NULL, 'admin', 'ROLE_ADMIN', 'admin@admin.com', '2bb80d537b1da3e38bd30361aa855686bde0eacd7162fef6a25fe97bf527a25b', NULL, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES (NULL, 'Chrisatm13', 'ROLE_USER', 'chrisatm13@chrisatm13.com', '2bb80d537b1da3e38bd30361aa855686bde0eacd7162fef6a25fe97bf527a25b', NULL, NULL, NULL, NULL);