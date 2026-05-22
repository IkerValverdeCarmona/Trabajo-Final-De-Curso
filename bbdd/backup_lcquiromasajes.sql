-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: LcQuiromasajes
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Administrador`
--

DROP TABLE IF EXISTS `Administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Administrador` (
  `id_perfil` int NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefono` int NOT NULL,
  PRIMARY KEY (`id_perfil`),
  CONSTRAINT `Administrador_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Administrador`
--

LOCK TABLES `Administrador` WRITE;
/*!40000 ALTER TABLE `Administrador` DISABLE KEYS */;
INSERT INTO `Administrador` VALUES (30,'Iker','Valverde Carmona',684167952);
/*!40000 ALTER TABLE `Administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Carrito`
--

DROP TABLE IF EXISTS `Carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Carrito` (
  `id_perfil` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id_perfil`,`id_producto`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `Carrito_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE,
  CONSTRAINT `Carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `Producto` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Carrito`
--

LOCK TABLES `Carrito` WRITE;
/*!40000 ALTER TABLE `Carrito` DISABLE KEYS */;
/*!40000 ALTER TABLE `Carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Citas`
--

DROP TABLE IF EXISTS `Citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Citas` (
  `id_cita` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int NOT NULL,
  `id_servicio` int NOT NULL,
  `id_trabajador` int DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('Pendiente','Completado','Cancelado','No asistido') DEFAULT 'Pendiente',
  `descuento_aplicado` decimal(5,2) DEFAULT '0.00',
  `precio_final` decimal(10,2) NOT NULL,
  `notas_cliente` text,
  PRIMARY KEY (`id_cita`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_servicio` (`id_servicio`),
  KEY `id_trabajador` (`id_trabajador`),
  CONSTRAINT `Citas_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE,
  CONSTRAINT `Citas_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `Servicios` (`id_servicio`),
  CONSTRAINT `Citas_ibfk_3` FOREIGN KEY (`id_trabajador`) REFERENCES `Trabajadores` (`id_trabajador`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Citas`
--

LOCK TABLES `Citas` WRITE;
/*!40000 ALTER TABLE `Citas` DISABLE KEYS */;
INSERT INTO `Citas` VALUES (13,23,4,NULL,'2026-05-09 09:00:00','2026-05-08 09:33:32','Cancelado',0.00,45.00,NULL),(14,23,3,7,'2026-05-08 18:30:00','2026-05-08 11:22:38','Completado',0.00,40.00,NULL),(15,34,4,7,'2026-05-08 09:00:00','2026-05-08 12:03:40','Cancelado',0.00,45.00,NULL),(16,30,2,7,'2026-05-11 09:00:00','2026-05-11 09:28:58','Cancelado',0.00,50.00,NULL),(17,31,1,8,'2026-05-11 09:00:00','2026-05-11 09:48:08','Completado',0.00,45.00,NULL),(18,34,2,8,'2026-05-11 09:55:00','2026-05-11 10:07:56','Completado',0.00,50.00,NULL),(19,33,3,7,'2026-05-11 18:30:00','2026-05-11 12:21:57','Completado',0.00,40.00,NULL),(20,23,3,7,'2026-05-12 18:30:00','2026-05-12 10:37:33','Pendiente',0.00,40.00,NULL),(21,30,7,7,'2026-05-12 09:00:00','2026-05-12 10:56:40','Pendiente',0.00,50.00,NULL),(22,23,3,7,'2026-05-13 09:00:00','2026-05-13 12:17:18','Completado',0.00,40.00,'Tengo Mal la cabeza');
/*!40000 ALTER TABLE `Citas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Opera`
--

DROP TABLE IF EXISTS `Opera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Opera` (
  `id_opera` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int NOT NULL,
  `id_producto` int NOT NULL,
  `fecha_compra` datetime DEFAULT CURRENT_TIMESTAMP,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario_venta` decimal(10,2) NOT NULL,
  `estado` enum('Pendiente','Entregado','Cancelado') DEFAULT 'Pendiente',
  PRIMARY KEY (`id_opera`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `Opera_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE,
  CONSTRAINT `Opera_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `Producto` (`id_producto`) ON DELETE CASCADE,
  CONSTRAINT `Opera_chk_1` CHECK ((`cantidad` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Opera`
--

LOCK TABLES `Opera` WRITE;
/*!40000 ALTER TABLE `Opera` DISABLE KEYS */;
/*!40000 ALTER TABLE `Opera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Opiniones`
--

DROP TABLE IF EXISTS `Opiniones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Opiniones` (
  `id_opinion` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int NOT NULL,
  `id_servicio` int DEFAULT NULL,
  `puntuacion` int DEFAULT NULL,
  `comentario` text,
  `fecha_publicacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `visible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_opinion`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `Opiniones_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE,
  CONSTRAINT `Opiniones_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `Servicios` (`id_servicio`) ON DELETE SET NULL,
  CONSTRAINT `Opiniones_chk_1` CHECK ((`puntuacion` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Opiniones`
--

LOCK TABLES `Opiniones` WRITE;
/*!40000 ALTER TABLE `Opiniones` DISABLE KEYS */;
/*!40000 ALTER TABLE `Opiniones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Perfil`
--

DROP TABLE IF EXISTS `Perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `permiso` enum('admin','trabajador','usuario') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Perfil`
--

LOCK TABLES `Perfil` WRITE;
/*!40000 ALTER TABLE `Perfil` DISABLE KEYS */;
INSERT INTO `Perfil` VALUES (23,'luis@luis.com','$2y$10$pi5iqp1AkksNfrHfwZr7guoPbuvwHclj/FCgYmhBPmrjkT5YEZdlq','usuario'),(24,'maria@gmail.com','$2y$10$qAQkLGlb7dAhLv.o.NQ1qOuH58BbsFFnZH.7Mqk.4Uhpc3yb/ZPZq','usuario'),(30,'iker@lcquiromasajes.com','$2y$10$1XXEqL26cZ1E1JE8MDJFNOJJB92GugCjeUVPRQ38N2Bog2//ZrM0.','admin'),(31,'lidia@lcquiromasajes.com','$2y$10$A2aP3Npe/ao8QllcycETHeEtFVPswEhpfvtNPQirFbQymErfajHmy','trabajador'),(32,'marta@lcquiromasajes.com','$2y$10$fB5K2C6l1oAUvHN.DJUXJeMBEIaJMyU/IRODNraydOj74O6DtXjWC','trabajador'),(33,'iker.valverde2006@gmail.com','$2y$10$VWvsHJGjzpND.sUicgtNOO4LNTfZBil9R1vmjJB1cO2VTa62e.MhW','usuario'),(34,'carmonavalverdeiker@gmail.com','$2y$10$oHAQMiK3zZXQ8eMeRbafe.7bvPSh/YAich3VNPNzvIMV/zC9/eOE2','usuario'),(35,'pepe@pepe.es','$2y$10$9ZDKO7jQ9TjxZKLa8TG2f.hjzdbo8XpRZVRpuIUPt.slkldCJlJiW','trabajador');
/*!40000 ALTER TABLE `Perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Producto`
--

DROP TABLE IF EXISTS `Producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Producto` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `precio_actual` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  PRIMARY KEY (`id_producto`),
  CONSTRAINT `Producto_chk_1` CHECK ((`precio_actual` >= 0)),
  CONSTRAINT `Producto_chk_2` CHECK ((`stock` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Producto`
--

LOCK TABLES `Producto` WRITE;
/*!40000 ALTER TABLE `Producto` DISABLE KEYS */;
INSERT INTO `Producto` VALUES (3,'Incienso de Vainia','Olor a Vainia inolvidable ',5.00,100);
/*!40000 ALTER TABLE `Producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Servicios`
--

DROP TABLE IF EXISTS `Servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Servicios` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `duracion_minutos` int NOT NULL,
  `precio_actual` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_servicio`),
  CONSTRAINT `Servicios_chk_1` CHECK ((`duracion_minutos` > 0)),
  CONSTRAINT `Servicios_chk_2` CHECK ((`precio_actual` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Servicios`
--

LOCK TABLES `Servicios` WRITE;
/*!40000 ALTER TABLE `Servicios` DISABLE KEYS */;
INSERT INTO `Servicios` VALUES (1,'Masaje Relajante','Masaje suave con aceites esenciales para reducir el estrés.',60,45.00,1),(2,'Quiromasaje Deportivo','Terapia intensa para recuperación muscular post-entrenamiento.',50,50.00,1),(3,'Drenaje Linfático','Técnica manual para mejorar la circulación y retención de líquidos.',45,40.00,1),(4,'Masaje Relajante','Masaje suave con aceites esenciales para reducir el estrés.',60,45.00,1),(5,'Quiromasaje Deportivo','Terapia intensa para recuperación muscular post-entrenamiento.',50,50.00,1),(6,'Drenaje Linfático','Técnica manual para mejorar la circulación y retención de líquidos.',45,40.00,1),(7,'Masaje de Pies','Un Masaje de Pies Inolvidable',75,50.00,1);
/*!40000 ALTER TABLE `Servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Trabajadores`
--

DROP TABLE IF EXISTS `Trabajadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Trabajadores` (
  `id_trabajador` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `dni` varchar(9) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_trabajador`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `id_perfil` (`id_perfil`),
  UNIQUE KEY `dni` (`dni`),
  CONSTRAINT `Trabajadores_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Trabajadores`
--

LOCK TABLES `Trabajadores` WRITE;
/*!40000 ALTER TABLE `Trabajadores` DISABLE KEYS */;
INSERT INTO `Trabajadores` VALUES (7,31,'lidia@lcquiromasajes.com','Lidia','Carmona Rodriguez','Quiromasajista','615487598',NULL,1),(8,32,'marta@lcquiromasajes.com','Marta','Valverde Carmona','Osteópata','66666666',NULL,1);
/*!40000 ALTER TABLE `Trabajadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Usuario` (
  `id_perfil` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(150) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_perfil`),
  CONSTRAINT `Usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `Perfil` (`id_perfil`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
INSERT INTO `Usuario` VALUES (23,'Luis','Martinez','66666666','2026-05-08 09:33:03'),(24,'María',NULL,'77777777','2026-05-08 09:36:47'),(30,'Iker','Valverde Carmona','684167952','2026-05-08 10:12:21'),(33,'iker','Valverde Carmona',NULL,'2026-05-08 11:41:57'),(34,'iker','Valverde',NULL,'2026-05-08 11:44:52'),(35,'pepe','pepe','111111111','2026-05-11 10:44:51');
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-18  9:00:13
