USE `juego_puzzle`;

CREATE TABLE `usuario` (
    `id_usuario` varchar(20) PRIMARY KEY NOT NULL,
    `contrasenia` varchar(20) NOT NULL,
    `alias` varchar(20) NOT NULL,
    `email` varchar(20));

ALTER TABLE `usuario` ADD INDEX(`id_usuario`);


