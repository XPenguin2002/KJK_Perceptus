-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: projekt
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.22.04.2

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
-- Table structure for table `dodaj`
--

DROP TABLE IF EXISTS `dodaj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dodaj` (
  `email` varchar(50) NOT NULL,
  `kod` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dodaj`
--

LOCK TABLES `dodaj` WRITE;
/*!40000 ALTER TABLE `dodaj` DISABLE KEYS */;
/*!40000 ALTER TABLE `dodaj` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip`
--

DROP TABLE IF EXISTS `ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ip` (
  `adres_ip` char(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip`
--

LOCK TABLES `ip` WRITE;
/*!40000 ALTER TABLE `ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip2`
--

DROP TABLE IF EXISTS `ip2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ip2` (
  `adres_ip` char(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip2`
--

LOCK TABLES `ip2` WRITE;
/*!40000 ALTER TABLE `ip2` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pliki`
--

DROP TABLE IF EXISTS `pliki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pliki` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `plik` longblob NOT NULL,
  `typ` varchar(100) DEFAULT NULL,
  `rozmiar` int NOT NULL,
  `siec` int DEFAULT NULL,
  `kosz` int DEFAULT NULL,
  `kod` int DEFAULT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pliki`
--

LOCK TABLES `pliki` WRITE;
/*!40000 ALTER TABLE `pliki` DISABLE KEYS */;
INSERT INTO `pliki` VALUES (42,'uzytkownik1@kjkenterprise.pl',_binary 'mysql1.txt','text/plain',269,0,0,NULL,'2023-03-05'),(43,'uzytkownik1@kjkenterprise.pl',_binary 'definer (4).txt','text/plain',430,1,0,NULL,'2023-03-05'),(45,'uzytkownik1@kjkenterprise.pl',_binary 'PaintBall_19.1.0.zip','application/x-zip-compressed',1857106,0,0,NULL,'2023-03-05');
/*!40000 ALTER TABLE `pliki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resethasla`
--

DROP TABLE IF EXISTS `resethasla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resethasla` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kod` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resethasla`
--

LOCK TABLES `resethasla` WRITE;
/*!40000 ALTER TABLE `resethasla` DISABLE KEYS */;
/*!40000 ALTER TABLE `resethasla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesja`
--

DROP TABLE IF EXISTS `sesja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesja` (
  `ile` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesja`
--

LOCK TABLES `sesja` WRITE;
/*!40000 ALTER TABLE `sesja` DISABLE KEYS */;
INSERT INTO `sesja` VALUES (5);
/*!40000 ALTER TABLE `sesja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulubione`
--

DROP TABLE IF EXISTS `ulubione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ulubione` (
  `id` int NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulubione`
--

LOCK TABLES `ulubione` WRITE;
/*!40000 ALTER TABLE `ulubione` DISABLE KEYS */;
INSERT INTO `ulubione` VALUES (38,'uzytkownik1@kjkenterprise.pl'),(41,'uzytkownik1@kjkenterprise.pl'),(42,'uzytkownik1@kjkenterprise.pl');
/*!40000 ALTER TABLE `ulubione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uzytkownicy`
--

DROP TABLE IF EXISTS `uzytkownicy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uzytkownicy` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `haslo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `typ` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `autoryzacja` int NOT NULL,
  `secret` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kod` int DEFAULT NULL,
  `kosz` int DEFAULT NULL,
  `mb` int DEFAULT NULL,
  `imie` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nazwisko` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uzytkownicy`
--

LOCK TABLES `uzytkownicy` WRITE;
/*!40000 ALTER TABLE `uzytkownicy` DISABLE KEYS */;
INSERT INTO `uzytkownicy` VALUES (1,'jakub@kjkenterprise.pl','1d2e3c607bf37f587ffb95a663c04a234f07d8e661979535f46cc21fff3412f3c9c24d005c1ca628d4ba490266a6c31322beeb743633ce763ac1295170ae93fd','admin',0,'QR4IGJ7DUZLPWNNI',NULL,NULL,NULL,NULL,NULL),(2,'pawko@kjkenterprise.pl','f34ad4b3ae1e2cf33092e2abb60dc0444781c15d0e2e9ecdb37e4b14176a0164027b05900e09fa0f61a1882e0b89fbfa5dcfcc9765dd2ca4377e2c794837e091','uzytkownik',0,'PZWW5ZDGG457WTZL',NULL,NULL,16777216,'Paweł','Łaskarzewski'),(3,'uzytkownik1@kjkenterprise.pl','f34ad4b3ae1e2cf33092e2abb60dc0444781c15d0e2e9ecdb37e4b14176a0164027b05900e09fa0f61a1882e0b89fbfa5dcfcc9765dd2ca4377e2c794837e091','uzytkownik',0,'OXOMDZADSKJEJVZX',NULL,30,16777216,'uzy','gbur');
/*!40000 ALTER TABLE `uzytkownicy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zgloszenie`
--

DROP TABLE IF EXISTS `zgloszenie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zgloszenie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `wiadomosc` varchar(1000) DEFAULT NULL,
  `zamkniecie` int DEFAULT NULL,
  `notatki` varchar(1000) DEFAULT NULL,
  `tytul` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zgloszenie`
--

LOCK TABLES `zgloszenie` WRITE;
/*!40000 ALTER TABLE `zgloszenie` DISABLE KEYS */;
INSERT INTO `zgloszenie` VALUES (2,'pawko@kjkenterprise.pl','upo ten tego 321',1,'123','Pomoc techniczna');
/*!40000 ALTER TABLE `zgloszenie` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-14 18:01:44
