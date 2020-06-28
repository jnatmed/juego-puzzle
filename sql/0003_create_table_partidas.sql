USE `juego_puzzle`;

CREATE TABLE `partida` (
    `id_partida` int(11) PRIMARY KEY AUTO_INCREMENT,
    `id_usuario` varchar(20) NOT NULL,
    `imagen` mediumblob,
    `tags_imagen` text,
    `estados_del_juego` text);

ALTER TABLE `partida` ADD INDEX(`id_usuario`);

ALTER TABLE `partida` ADD CONSTRAINT `USUARIO_PARTIDA` FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
