--// insertTables
-- Migration SQL that makes the change goes here.


--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha` date NOT NULL,
  `rol` enum('administrador','cliente','respondente') DEFAULT 'cliente',
  `nombres` varchar(255) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_postal` int(11) DEFAULT NULL,
  `edad` tinyint(2) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `correo_alternativo` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `sexo` enum('male','female','null') DEFAULT 'null',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `usuario_respuestas`
--

CREATE TABLE `usuario_respuestas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `respondente_id` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `encuesta_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_respondente` (`respondente_id`),
  CONSTRAINT `fk_respondente` FOREIGN KEY (`respondente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `encuestas`
--

CREATE TABLE `encuestas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `correo_copy` varchar(255) DEFAULT NULL,
  `mensaje_bienvenida` varchar(255) DEFAULT NULL,
  `mensaje_despedida` varchar(255) DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `difusion` enum('activa','inactiva') DEFAULT 'activa',
  `permisos` enum('privada','publica') DEFAULT 'publica',
  PRIMARY KEY (`id`),
  KEY `fk_usuario_encuestas` (`id_usuario`),
  CONSTRAINT `fk_usuario_encuestas` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `grupos`
--


CREATE TABLE `grupos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `encuesta_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_encuesta` (`encuesta_id`),
  CONSTRAINT `fk_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  
--
-- Table structure for table `preguntas`
--

CREATE TABLE `preguntas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `mensaje_ayuda` varchar(255) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `grupo_id` bigint(20) DEFAULT NULL,
  `dimension` enum('simple','array','matriz') DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `tipo_descripcion` varchar(255) DEFAULT NULL,
  `class` varchar(45) DEFAULT NULL,
  `estado` enum('activada','desactivada') DEFAULT 'activada',
  `mensaje_validacion` varchar(255) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_id` (`grupo_id`),
  CONSTRAINT `fk_grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `respuestas`
--

CREATE TABLE `respuestas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cantidad` bigint(20) NOT NULL,
  `resultado` varchar(1000) NOT NULL,
  `pregunta_id` bigint(20) NOT NULL,
  `folio_respuesta` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pregunta_rerpuesta` (`pregunta_id`),
  KEY `fk_folio_respuesta` (`folio_respuesta`),
  CONSTRAINT `fk_folio_respuesta` FOREIGN KEY (`folio_respuesta`) REFERENCES `usuario_respuestas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pregunta_rerpuesta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `sub_labels`
--

CREATE TABLE `sub_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `pregunta_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pregunta_respuestas` (`pregunta_id`),
  CONSTRAINT `fk_pregunta_respuestas` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `sub_preguntas`
--

CREATE TABLE `sub_preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `pregunta_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pregunta_id` (`pregunta_id`),
  CONSTRAINT `fk_pregunta_id` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `sub_respuestas`
--

CREATE TABLE `sub_respuestas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `resultado` varchar(1000) NOT NULL,
  `sub_pregunta_id` int(11) NOT NULL,
  `sub_respuesta_id` int(11) NOT NULL,
  `pregunta_id` bigint(20) DEFAULT NULL,
  `folio_respuesta` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sub_pregunta_id` (`sub_pregunta_id`),
  KEY `idx_sub_respuesta_id` (`sub_respuesta_id`),
  KEY `fk_sr_pregunta_id` (`pregunta_id`),
  KEY `fk_folio_sub_respuesta` (`folio_respuesta`),
  CONSTRAINT `fk_folio_sub_respuesta` FOREIGN KEY (`folio_respuesta`) REFERENCES `usuario_respuestas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sr_pregunta_id` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sub_pregunta_id` FOREIGN KEY (`sub_pregunta_id`) REFERENCES `sub_preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sub_respuesta_id` FOREIGN KEY (`sub_respuesta_id`) REFERENCES `sub_labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `usuario_tokens`
--

CREATE TABLE `usuario_tokens` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `usuario` bigint(20) DEFAULT NULL,
  `encuesta` bigint(20) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `fk_token_encuestas` (`encuesta`),
  KEY `fk_tokens_usuario` (`usuario`),
  CONSTRAINT `fk_tokens_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_token_encuestas` FOREIGN KEY (`encuesta`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


create trigger addGrupo AFTER insert on encuestas
FOR EACH ROW BEGIN
	INSERT INTO grupos (id,titulo,comentario,encuesta_id) value(NEW.id,NEW.titulo, "NULL" ,NEW.id);
END;

--//@UNDO
-- SQL to undo the change goes here.show tables;
drop  trigger addGrupo;


DROP TABLE IF EXISTS `sub_respuestas`;
DROP TABLE IF EXISTS `sub_preguntas`;
DROP TABLE IF EXISTS `sub_labels`;
DROP TABLE IF EXISTS `respuestas`;
DROP TABLE IF EXISTS `preguntas`;
DROP TABLE IF EXISTS `grupos`;
DROP TABLE IF EXISTS `usuario_tokens`;
DROP TABLE IF EXISTS `encuestas`;
DROP TABLE IF EXISTS `usuario_respuestas`;
DROP TABLE IF EXISTS `usuarios`;