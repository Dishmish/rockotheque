-- MySQL dump 10.13  Distrib 8.4.11, for Linux (x86_64)
--
-- Host: localhost    Database: rockotheque
-- ------------------------------------------------------
-- Server version	8.4.11-0ubuntu0.26.04.1

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
-- Table structure for table `access_logs`
--

DROP TABLE IF EXISTS `access_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_access_logs_visited_at` (`visited_at`),
  KEY `fk_access_logs_user` (`user_id`),
  CONSTRAINT `fk_access_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_logs`
--

LOCK TABLES `access_logs` WRITE;
/*!40000 ALTER TABLE `access_logs` DISABLE KEYS */;
INSERT INTO `access_logs` VALUES (1,NULL,'Visiteur','127.0.0.1','/albums','GET','2026-11-09 00:21:36'),(2,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 00:30:58'),(3,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 00:31:06'),(4,1,'admin','127.0.0.1','/albums','GET','2026-11-09 00:31:07'),(5,1,'admin','127.0.0.1','/admin/logs','GET','2026-11-09 00:31:16'),(6,1,'admin','127.0.0.1','/albums','GET','2026-11-09 00:32:07'),(7,1,'admin','127.0.0.1','/logout','POST','2026-11-09 00:32:13'),(8,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 00:32:13'),(9,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 00:32:27'),(10,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 00:33:52'),(11,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 00:34:12'),(12,2,'client','127.0.0.1','/albums','GET','2026-11-09 00:34:12'),(13,2,'client','127.0.0.1','/admin/logs','GET','2026-11-09 00:35:37'),(14,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 17:00:08'),(15,NULL,'Visiteur','127.0.0.1','/favicon.ico','GET','2026-11-09 17:00:09'),(16,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 17:00:10'),(17,2,'client','127.0.0.1','/albums','GET','2026-11-09 17:00:11'),(18,2,'client','127.0.0.1','/albums/3','GET','2026-11-09 17:00:19'),(19,2,'client','127.0.0.1','/albums/3','GET','2026-11-09 17:05:14'),(20,2,'client','127.0.0.1','/favicon.ico','GET','2026-11-09 17:05:14'),(21,2,'client','127.0.0.1','/albums/3/reviews','POST','2026-11-09 17:06:27'),(22,2,'client','127.0.0.1','/albums/3','GET','2026-11-09 17:06:27'),(23,2,'client','127.0.0.1','/albums','GET','2026-11-09 17:08:26'),(24,2,'client','127.0.0.1','/logout','POST','2026-11-09 17:08:28'),(25,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 17:08:28'),(26,NULL,'Visiteur','127.0.0.1','/albums','GET','2026-11-09 17:08:53'),(27,NULL,'Visiteur','127.0.0.1','/albums/3','GET','2026-11-09 17:09:01'),(28,NULL,'Visiteur','127.0.0.1','/admin','GET','2026-11-09 17:15:45'),(29,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 17:15:45'),(30,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 17:16:31'),(31,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 17:16:46'),(32,1,'admin','127.0.0.1','/albums','GET','2026-11-09 17:16:46'),(33,1,'admin','127.0.0.1','/admin','GET','2026-11-09 17:16:52'),(34,1,'admin','127.0.0.1','/admin','GET','2026-11-09 17:18:59'),(35,NULL,'Visiteur','127.0.0.1','/admin.','GET','2026-11-09 17:19:05'),(36,NULL,'Visiteur','127.0.0.1','/admin','GET','2026-11-09 17:20:35'),(37,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 17:20:35'),(38,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 17:21:05'),(39,2,'client','127.0.0.1','/albums','GET','2026-11-09 17:21:05'),(40,2,'client','127.0.0.1','/admin','GET','2026-11-09 17:22:34'),(41,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 17:33:17'),(42,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 17:33:28'),(43,1,'admin','127.0.0.1','/login','GET','2026-11-09 17:34:40'),(44,1,'admin','127.0.0.1','/login','POST','2026-11-09 17:34:46'),(45,1,'admin','127.0.0.1','/albums','GET','2026-11-09 17:34:46'),(46,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 17:36:37'),(47,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 18:01:28'),(48,1,'admin','127.0.0.1','/albums/1/cover','POST','2026-11-09 18:02:51'),(49,1,'admin','127.0.0.1','/albums/1','GET','2026-11-09 18:02:51'),(50,1,'admin','127.0.0.1','/albums','GET','2026-11-09 18:06:13'),(51,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 18:06:28'),(52,1,'admin','127.0.0.1','/albums/1/cover','POST','2026-11-09 18:08:22'),(53,NULL,'Visiteur','127.0.0.1','/admin','GET','2026-11-09 18:09:35'),(54,NULL,'Visiteur','127.0.0.1','/login','GET','2026-11-09 18:09:35'),(55,NULL,'Visiteur','127.0.0.1','/login','POST','2026-11-09 18:09:44'),(56,2,'client','127.0.0.1','/albums','GET','2026-11-09 18:09:44'),(57,2,'client','127.0.0.1','/albums/1/cover','GET','2026-11-09 18:10:01'),(58,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 18:16:57'),(59,1,'admin','127.0.0.1','/login','GET','2026-11-09 18:17:37'),(60,1,'admin','127.0.0.1','/login','POST','2026-11-09 18:17:39'),(61,1,'admin','127.0.0.1','/albums','GET','2026-11-09 18:17:40'),(62,1,'admin','127.0.0.1','/albums/1/cover','GET','2026-11-09 18:18:18'),(63,1,'admin','127.0.0.1','/albums/1/cover','POST','2026-11-09 18:18:37'),(64,1,'admin','127.0.0.1','/albums/1','GET','2026-11-09 18:18:37');
/*!40000 ALTER TABLE `access_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_artists`
--

DROP TABLE IF EXISTS `album_artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `album_artists` (
  `album_id` int unsigned NOT NULL,
  `artist_id` int unsigned NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Artiste principal',
  PRIMARY KEY (`album_id`,`artist_id`),
  KEY `fk_album_artists_artist` (`artist_id`),
  CONSTRAINT `fk_album_artists_album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_album_artists_artist` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_artists`
--

LOCK TABLES `album_artists` WRITE;
/*!40000 ALTER TABLE `album_artists` DISABLE KEYS */;
INSERT INTO `album_artists` VALUES (1,1,'Artiste principal'),(2,2,'Artiste principal'),(3,3,'Artiste principal'),(4,4,'Artiste principal'),(6,4,'Artiste principal');
/*!40000 ALTER TABLE `album_artists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `albums` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `genre_id` int unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_year` smallint unsigned NOT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_albums_genre` (`genre_id`),
  CONSTRAINT `fk_albums_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `albums`
--

LOCK TABLES `albums` WRITE;
/*!40000 ALTER TABLE `albums` DISABLE KEYS */;
INSERT INTO `albums` VALUES (1,1,'Brothers in Arms',1985,'/uploads/covers/54ac1f66dcb599cc5306a883c8b32d18.jpg','Un album emblématique de Dire Straits comprenant la chanson Brothers in Arms. Un de mes albums préférés.','2026-09-21 20:50:09'),(2,2,'Machine Head',1972,NULL,'Un album classique de Deep Purple comprenant Smoke on the Water.','2026-09-21 20:50:09'),(3,3,'In the Court of the Crimson King',1969,NULL,'Un album fondateur du rock progressif.','2026-09-21 20:50:09'),(4,3,'The Dark Side of the Moon',1973,NULL,'Un album conceptuel célèbre de Pink Floyd.','2026-09-21 20:50:09'),(6,3,'Wish You Were Here',1975,NULL,'Un album de Pink Floyd consacré à l’absence et aux souvenirs.','2026-10-02 23:34:03');
/*!40000 ALTER TABLE `albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artists`
--

DROP TABLE IF EXISTS `artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `artists` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formation_year` smallint unsigned DEFAULT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artists`
--

LOCK TABLES `artists` WRITE;
/*!40000 ALTER TABLE `artists` DISABLE KEYS */;
INSERT INTO `artists` VALUES (1,'Dire Straits','Royaume-Uni',1977,'Groupe britannique fondé à Londres par Mark Knopfler.'),(2,'Deep Purple','Royaume-Uni',1968,'Groupe britannique considéré comme un pionnier du hard rock.'),(3,'King Crimson','Royaume-Uni',1968,'Groupe britannique majeur du rock progressif.'),(4,'Pink Floyd','Royaume-Uni',1965,'Groupe britannique reconnu pour ses albums conceptuels.');
/*!40000 ALTER TABLE `artists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Rock','Musique rock classique caractérisée par les guitares électriques.'),(2,'Hard Rock','Style de rock puissant avec des guitares fortement amplifiées.'),(3,'Rock progressif','Genre utilisant des compositions longues et complexes.');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `reviewer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reviews_album` (`album_id`),
  KEY `fk_reviews_user` (`user_id`),
  CONSTRAINT `fk_reviews_album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_reviews_rating` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,NULL,'Dmitriy',5,'Un album profond avec une excellente qualité sonore.','2026-09-21 20:50:09'),(2,2,NULL,'Dmitriy',5,'Un grand classique du hard rock.','2026-09-21 20:50:09'),(3,3,NULL,'Dmitriy',4,'Une œuvre complexe et originale.','2026-09-21 20:50:09'),(4,4,NULL,'Dmitriy',5,'Un album atmosphérique qui reste intemporel.','2026-09-21 20:50:09'),(5,1,NULL,'Dmitriy',5,'Mon album préféré de Dire Straits.','2026-09-23 21:08:29'),(6,1,NULL,'Dmitriy',5,'Une belle découverte dans ma collection rock.','2026-10-02 23:14:30'),(7,3,2,'client',5,'Un excellent album de rock progressif.','2026-11-09 17:06:27');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('client','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@rockotheque.example','$2y$12$jrT48Rm8lgeZvlm.nHPnuuHYkXaZUnLZHPbS9AFbeSPHLgf86yLKW','admin','2026-11-08 22:05:52'),(2,'client','client@rockotheque.example','$2y$12$j9Oi5FvIWTvCxNONeP0XouTItkjqrxTeMOTTFA3xpzVILpACV3NTW','client','2026-11-08 22:16:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-09 13:20:55
