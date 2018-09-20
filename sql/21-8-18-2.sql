ALTER TABLE `zona` ADD `final` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Este campo indica si al seleccionar esta zona en algun formulario se puede entender que se completó la selección de zonas' AFTER `nivel`;
ALTER TABLE `zona` ADD `denominacion` SMALLINT NOT NULL AFTER `final`;

CREATE TABLE `creoprop`.`zona_denominacion` ( `id` INT NOT NULL AUTO_INCREMENT , `nombre` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `zona_denominacion` (`id`, `nombre`) VALUES (1, 'País'), (2, 'Provincia'), (3, 'Ciudad'), (4, 'Barrio'), (5, 'Zona');

ALTER TABLE `propiedades` ADD `zona_id` INT NOT NULL AFTER `mov_reducida`;
