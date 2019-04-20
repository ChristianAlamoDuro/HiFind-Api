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


# RELLENO 
# CREACION DE USUARIOS
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES (NULL, 'admin', 'ROLE_ADMIN', 'admin@admin.com', 'admin1234', NULL, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `username`, `role`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES (NULL, 'Chrisatm13', 'ROLE_USER', 'chrisatm13@chrisatm13.com', 'Secret1234', NULL, NULL, NULL, NULL);