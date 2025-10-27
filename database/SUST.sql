CREATE DATABASE  IF NOT EXISTS `sustena` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sustena`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: sustena
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `badges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rule` json NOT NULL,
  `points_reward` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `badges_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `badges`
--

LOCK TABLES `badges` WRITE;
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
INSERT INTO `badges` VALUES (1,'carbon-under-100','Carbon Under 100','✅','carbon','{\"op\": \"<\", \"fact\": \"weekly_kg\", \"type\": \"threshold\", \"value\": 100}',100,'2025-10-21 22:17:38','2025-10-21 22:17:38'),(2,'waste-reducer-silver','Waste Reducer (Silver)','?️','waste','{\"op\": \"<=\", \"fact\": \"waste_kg_week\", \"type\": \"threshold\", \"value\": 3}',120,'2025-10-21 22:17:38','2025-10-21 22:17:38'),(3,'level-10','Eco Level 10','?','meta','{\"op\": \">=\", \"fact\": \"level\", \"type\": \"threshold\", \"value\": 10}',200,'2025-10-21 22:17:38','2025-10-21 22:17:38');
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_post_id_foreign` (`post_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,23,1,'is this a test?','2025-10-22 09:00:38','2025-10-22 09:00:38'),(2,24,1,'my classmate?','2025-10-22 09:30:04','2025-10-22 09:30:04');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footprint_category_totals`
--

DROP TABLE IF EXISTS `footprint_category_totals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footprint_category_totals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attempt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_score` decimal(8,2) NOT NULL DEFAULT '0.00',
  `answers_count` int unsigned NOT NULL DEFAULT '0',
  `basis` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `timeframe` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `is_official` tinyint(1) NOT NULL DEFAULT '0',
  `kg_per_week` decimal(10,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pct_of_total` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_attempt_category` (`user_id`,`attempt_id`,`category`),
  KEY `footprint_category_totals_user_id_attempt_id_index` (`user_id`,`attempt_id`),
  KEY `footprint_category_totals_user_id_category_index` (`user_id`,`category`),
  KEY `footprint_category_totals_user_id_attempt_id_is_official_index` (`user_id`,`attempt_id`,`is_official`),
  CONSTRAINT `footprint_category_totals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footprint_category_totals`
--

LOCK TABLES `footprint_category_totals` WRITE;
/*!40000 ALTER TABLE `footprint_category_totals` DISABLE KEYS */;
INSERT INTO `footprint_category_totals` VALUES (1,1,'0a5242f9-a534-4e5e-97ba-707298fd7ad8','Food',90.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:44:51','2025-10-06 00:44:51',NULL),(2,1,'0a5242f9-a534-4e5e-97ba-707298fd7ad8','Transportation',262.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:44:51','2025-10-06 00:44:51',NULL),(3,1,'0a5242f9-a534-4e5e-97ba-707298fd7ad8','Energy',25.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:44:51','2025-10-06 00:44:51',NULL),(4,1,'0a5242f9-a534-4e5e-97ba-707298fd7ad8','Water Usage',30.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:44:51','2025-10-06 00:44:51',NULL),(5,1,'0a5242f9-a534-4e5e-97ba-707298fd7ad8','Waste Management',15.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:44:51','2025-10-06 00:44:51',NULL),(6,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02',NULL),(7,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02',NULL),(8,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02',NULL),(9,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02',NULL),(10,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02',NULL),(11,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17',NULL),(12,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17',NULL),(13,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17',NULL),(14,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17',NULL),(15,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17',NULL),(16,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5','Food',77.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04',NULL),(17,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5','Transportation',99.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04',NULL),(18,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5','Energy',25.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04',NULL),(19,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5','Water Usage',21.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04',NULL),(20,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5','Waste Management',7.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04',NULL),(21,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03',NULL),(22,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f','Transportation',447.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03',NULL),(23,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f','Energy',110.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03',NULL),(24,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f','Water Usage',65.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03',NULL),(25,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f','Waste Management',60.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03',NULL),(26,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07',NULL),(27,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07',NULL),(28,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07',NULL),(29,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07',NULL),(30,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07',NULL),(31,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18',NULL),(32,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18',NULL),(33,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18',NULL),(34,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18',NULL),(35,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18',NULL),(36,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798','Food',29.20,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21',NULL),(37,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798','Transportation',155.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21',NULL),(38,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798','Energy',82.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21',NULL),(39,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798','Water Usage',49.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21',NULL),(40,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21',NULL),(41,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3','Food',47.80,10,'weekly','weekly',NULL,NULL,1,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29',NULL),(42,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3','Transportation',627.00,10,'weekly','weekly',NULL,NULL,1,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29',NULL),(43,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3','Energy',105.00,10,'weekly','weekly',NULL,NULL,1,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29',NULL),(44,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3','Water Usage',97.00,11,'weekly','weekly',NULL,NULL,1,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29',NULL),(45,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3','Waste Management',53.00,10,'weekly','weekly',NULL,NULL,1,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29',NULL),(46,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05',NULL),(47,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca','Transportation',267.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05',NULL),(48,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05',NULL),(49,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05',NULL),(50,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05',NULL),(51,1,'af3b79a6-3e01-433d-925a-37c44df3686e','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18',NULL),(52,1,'af3b79a6-3e01-433d-925a-37c44df3686e','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18',NULL),(53,1,'af3b79a6-3e01-433d-925a-37c44df3686e','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18',NULL),(54,1,'af3b79a6-3e01-433d-925a-37c44df3686e','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18',NULL),(55,1,'af3b79a6-3e01-433d-925a-37c44df3686e','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18',NULL),(56,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50',NULL),(57,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7','Transportation',627.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50',NULL),(58,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7','Energy',110.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50',NULL),(59,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7','Water Usage',84.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50',NULL),(60,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7','Waste Management',49.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50',NULL),(61,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10',NULL),(62,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10',NULL),(63,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10',NULL),(64,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10',NULL),(65,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10',NULL),(66,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b','Food',38.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08',NULL),(67,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b','Transportation',123.50,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08',NULL),(68,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b','Energy',70.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08',NULL),(69,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b','Water Usage',47.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08',NULL),(70,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b','Waste Management',38.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08',NULL),(71,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04',NULL),(72,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585','Transportation',347.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04',NULL),(73,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585','Energy',75.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04',NULL),(74,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585','Water Usage',62.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04',NULL),(75,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585','Waste Management',34.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04',NULL),(76,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17','Food',94.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31',NULL),(77,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17','Transportation',242.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31',NULL),(78,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17','Energy',65.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31',NULL),(79,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17','Water Usage',42.00,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31',NULL),(80,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17','Waste Management',32.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31',NULL),(81,1,'da953e08-2f92-4e64-86fa-471886d3d5fa','Food',18.64,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20',NULL),(82,1,'da953e08-2f92-4e64-86fa-471886d3d5fa','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20',NULL),(83,1,'da953e08-2f92-4e64-86fa-471886d3d5fa','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20',NULL),(84,1,'da953e08-2f92-4e64-86fa-471886d3d5fa','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20',NULL),(85,1,'da953e08-2f92-4e64-86fa-471886d3d5fa','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20',NULL),(86,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac','Food',1.99,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11',NULL),(87,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac','Transportation',17.21,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11',NULL),(88,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac','Energy',1.47,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11',NULL),(89,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac','Water Usage',0.99,11,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11',NULL),(90,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11',NULL),(91,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 21:09:06','2025-10-09 21:09:06',NULL),(92,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 21:09:06','2025-10-09 21:09:06',NULL),(93,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 21:09:06','2025-10-09 21:09:06',NULL),(94,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c','Water Usage',4.25,11,'weekly','weekly',NULL,NULL,0,4.250,'2025-10-09 21:09:06','2025-10-09 21:09:06',NULL),(95,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 21:09:06','2025-10-09 21:09:06',NULL),(96,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45','Food',12.86,10,'weekly','weekly',NULL,NULL,0,12.855,'2025-10-09 21:35:25','2025-10-09 21:35:25',NULL),(97,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45','Transportation',13.62,10,'weekly','weekly',NULL,NULL,0,13.623,'2025-10-09 21:35:25','2025-10-09 21:35:25',NULL),(98,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-09 21:35:25','2025-10-09 21:35:25',NULL),(99,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45','Water Usage',2.30,11,'weekly','weekly',NULL,NULL,0,2.300,'2025-10-09 21:35:25','2025-10-09 21:35:25',NULL),(100,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 21:35:25','2025-10-09 21:35:25',NULL),(101,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c','Food',12.39,10,'weekly','weekly',NULL,NULL,0,12.388,'2025-10-09 21:57:13','2025-10-09 21:57:13',NULL),(102,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c','Transportation',41.50,10,'weekly','weekly',NULL,NULL,0,41.501,'2025-10-09 21:57:13','2025-10-09 21:57:13',NULL),(103,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-09 21:57:13','2025-10-09 21:57:13',NULL),(104,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c','Water Usage',4.25,11,'weekly','weekly',NULL,NULL,0,4.250,'2025-10-09 21:57:13','2025-10-09 21:57:13',NULL),(105,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 21:57:13','2025-10-09 21:57:13',NULL),(106,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:15:58','2025-10-09 22:15:58',NULL),(107,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 22:15:58','2025-10-09 22:15:58',NULL),(108,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:15:58','2025-10-09 22:15:58',NULL),(109,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 22:15:58','2025-10-09 22:15:58',NULL),(110,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:15:58','2025-10-09 22:15:58',NULL),(111,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:23:50','2025-10-09 22:23:50',NULL),(112,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521','Transportation',62.90,10,'weekly','weekly',NULL,NULL,0,62.899,'2025-10-09 22:23:50','2025-10-09 22:23:50',NULL),(113,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-09 22:23:50','2025-10-09 22:23:50',NULL),(114,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521','Water Usage',7.25,11,'weekly','weekly',NULL,NULL,0,7.250,'2025-10-09 22:23:50','2025-10-09 22:23:50',NULL),(115,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:23:50','2025-10-09 22:23:50',NULL),(116,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:32:35','2025-10-09 22:32:35',NULL),(117,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 22:32:35','2025-10-09 22:32:35',NULL),(118,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:32:35','2025-10-09 22:32:35',NULL),(119,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 22:32:35','2025-10-09 22:32:35',NULL),(120,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:32:35','2025-10-09 22:32:35',NULL),(121,1,'62479cc4-3a32-4939-990f-8febef21e3df','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:50:58','2025-10-09 22:50:58',NULL),(122,1,'62479cc4-3a32-4939-990f-8febef21e3df','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 22:50:58','2025-10-09 22:50:58',NULL),(123,1,'62479cc4-3a32-4939-990f-8febef21e3df','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:50:58','2025-10-09 22:50:58',NULL),(124,1,'62479cc4-3a32-4939-990f-8febef21e3df','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 22:50:58','2025-10-09 22:50:58',NULL),(125,1,'62479cc4-3a32-4939-990f-8febef21e3df','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:50:58','2025-10-09 22:50:58',NULL),(126,1,'425897d8-882a-4091-8624-1bcff7e39a3c','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:51:26','2025-10-09 22:51:26',NULL),(127,1,'425897d8-882a-4091-8624-1bcff7e39a3c','Transportation',119.44,10,'weekly','weekly',NULL,NULL,0,119.439,'2025-10-09 22:51:26','2025-10-09 22:51:26',NULL),(128,1,'425897d8-882a-4091-8624-1bcff7e39a3c','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:51:26','2025-10-09 22:51:26',NULL),(129,1,'425897d8-882a-4091-8624-1bcff7e39a3c','Water Usage',9.90,11,'weekly','weekly',NULL,NULL,0,9.900,'2025-10-09 22:51:26','2025-10-09 22:51:26',NULL),(130,1,'425897d8-882a-4091-8624-1bcff7e39a3c','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:51:26','2025-10-09 22:51:26',NULL),(131,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:52:20','2025-10-09 22:52:20',NULL),(132,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 22:52:20','2025-10-09 22:52:20',NULL),(133,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:52:20','2025-10-09 22:52:20',NULL),(134,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 22:52:20','2025-10-09 22:52:20',NULL),(135,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:52:20','2025-10-09 22:52:20',NULL),(136,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 22:57:37','2025-10-09 22:57:37',NULL),(137,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 22:57:37','2025-10-09 22:57:37',NULL),(138,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 22:57:37','2025-10-09 22:57:37',NULL),(139,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 22:57:37','2025-10-09 22:57:37',NULL),(140,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 22:57:37','2025-10-09 22:57:37',NULL),(141,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:02:29','2025-10-09 23:02:29',NULL),(142,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:02:29','2025-10-09 23:02:29',NULL),(143,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:02:29','2025-10-09 23:02:29',NULL),(144,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2','Water Usage',5.30,11,'weekly','weekly',NULL,NULL,0,5.300,'2025-10-09 23:02:29','2025-10-09 23:02:29',NULL),(145,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:02:29','2025-10-09 23:02:29',NULL),(146,1,'b71c5f44-f2a4-4071-9727-44316c344e87','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:04:07','2025-10-09 23:04:07',NULL),(147,1,'b71c5f44-f2a4-4071-9727-44316c344e87','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:04:07','2025-10-09 23:04:07',NULL),(148,1,'b71c5f44-f2a4-4071-9727-44316c344e87','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:04:07','2025-10-09 23:04:07',NULL),(149,1,'b71c5f44-f2a4-4071-9727-44316c344e87','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:04:07','2025-10-09 23:04:07',NULL),(150,1,'b71c5f44-f2a4-4071-9727-44316c344e87','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:04:07','2025-10-09 23:04:07',NULL),(151,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:04:32','2025-10-09 23:04:32',NULL),(152,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:04:32','2025-10-09 23:04:32',NULL),(153,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:04:32','2025-10-09 23:04:32',NULL),(154,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb','Water Usage',3.50,11,'weekly','weekly',NULL,NULL,0,3.500,'2025-10-09 23:04:32','2025-10-09 23:04:32',NULL),(155,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:04:32','2025-10-09 23:04:32',NULL),(156,1,'f2495970-cb78-477d-b26f-4f5cd60ed074','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:06:30','2025-10-09 23:06:30',NULL),(157,1,'f2495970-cb78-477d-b26f-4f5cd60ed074','Transportation',120.49,10,'weekly','weekly',NULL,NULL,0,120.492,'2025-10-09 23:06:30','2025-10-09 23:06:30',NULL),(158,1,'f2495970-cb78-477d-b26f-4f5cd60ed074','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:06:30','2025-10-09 23:06:30',NULL),(159,1,'f2495970-cb78-477d-b26f-4f5cd60ed074','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:06:30','2025-10-09 23:06:30',NULL),(160,1,'f2495970-cb78-477d-b26f-4f5cd60ed074','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:06:30','2025-10-09 23:06:30',NULL),(161,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:14:44','2025-10-09 23:14:44',NULL),(162,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:14:44','2025-10-09 23:14:44',NULL),(163,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:14:44','2025-10-09 23:14:44',NULL),(164,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:14:44','2025-10-09 23:14:44',NULL),(165,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:14:44','2025-10-09 23:14:44',NULL),(166,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:22:46','2025-10-09 23:22:46',NULL),(167,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6','Transportation',46.62,10,'weekly','weekly',NULL,NULL,0,46.621,'2025-10-09 23:22:46','2025-10-09 23:22:46',NULL),(168,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-09 23:22:46','2025-10-09 23:22:46',NULL),(169,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-09 23:22:46','2025-10-09 23:22:46',NULL),(170,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:22:46','2025-10-09 23:22:46',NULL),(171,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:28:09','2025-10-09 23:28:09',NULL),(172,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:28:09','2025-10-09 23:28:09',NULL),(173,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-09 23:28:09','2025-10-09 23:28:09',NULL),(174,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','Water Usage',3.50,11,'weekly','weekly',NULL,NULL,0,3.500,'2025-10-09 23:28:09','2025-10-09 23:28:09',NULL),(175,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:28:09','2025-10-09 23:28:09',NULL),(176,1,'b780336b-5759-445b-afbb-c110d5ef1358','Food',6.99,10,'weekly','weekly',NULL,NULL,0,6.990,'2025-10-09 23:29:12','2025-10-09 23:29:12',NULL),(177,1,'b780336b-5759-445b-afbb-c110d5ef1358','Transportation',169.98,10,'weekly','weekly',NULL,NULL,0,169.979,'2025-10-09 23:29:12','2025-10-09 23:29:12',NULL),(178,1,'b780336b-5759-445b-afbb-c110d5ef1358','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-09 23:29:12','2025-10-09 23:29:12',NULL),(179,1,'b780336b-5759-445b-afbb-c110d5ef1358','Water Usage',4.25,11,'weekly','weekly',NULL,NULL,0,4.250,'2025-10-09 23:29:12','2025-10-09 23:29:12',NULL),(180,1,'b780336b-5759-445b-afbb-c110d5ef1358','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:29:12','2025-10-09 23:29:12',NULL),(181,1,'afa4741b-09df-4acf-936d-2f658909e5ae','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:29:47','2025-10-09 23:29:47',NULL),(182,1,'afa4741b-09df-4acf-936d-2f658909e5ae','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:29:47','2025-10-09 23:29:47',NULL),(183,1,'afa4741b-09df-4acf-936d-2f658909e5ae','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:29:47','2025-10-09 23:29:47',NULL),(184,1,'afa4741b-09df-4acf-936d-2f658909e5ae','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:29:47','2025-10-09 23:29:47',NULL),(185,1,'afa4741b-09df-4acf-936d-2f658909e5ae','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:29:47','2025-10-09 23:29:47',NULL),(186,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:30:01','2025-10-09 23:30:01',NULL),(187,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','Transportation',137.59,10,'weekly','weekly',NULL,NULL,0,137.592,'2025-10-09 23:30:01','2025-10-09 23:30:01',NULL),(188,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:30:01','2025-10-09 23:30:01',NULL),(189,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','Water Usage',8.00,11,'weekly','weekly',NULL,NULL,0,8.000,'2025-10-09 23:30:01','2025-10-09 23:30:01',NULL),(190,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:30:01','2025-10-09 23:30:01',NULL),(191,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:31:00','2025-10-09 23:31:00',NULL),(192,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:31:00','2025-10-09 23:31:00',NULL),(193,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-09 23:31:00','2025-10-09 23:31:00',NULL),(194,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','Water Usage',4.25,11,'weekly','weekly',NULL,NULL,0,4.250,'2025-10-09 23:31:00','2025-10-09 23:31:00',NULL),(195,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:31:00','2025-10-09 23:31:00',NULL),(196,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:31:13','2025-10-09 23:31:13',NULL),(197,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:31:13','2025-10-09 23:31:13',NULL),(198,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:31:13','2025-10-09 23:31:13',NULL),(199,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:31:13','2025-10-09 23:31:13',NULL),(200,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:31:13','2025-10-09 23:31:13',NULL),(201,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:31:29','2025-10-09 23:31:29',NULL),(202,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','Transportation',120.49,10,'weekly','weekly',NULL,NULL,0,120.492,'2025-10-09 23:31:29','2025-10-09 23:31:29',NULL),(203,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:31:29','2025-10-09 23:31:29',NULL),(204,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:31:29','2025-10-09 23:31:29',NULL),(205,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:31:29','2025-10-09 23:31:29',NULL),(206,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-09 23:37:52','2025-10-09 23:37:52',NULL),(207,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-09 23:37:52','2025-10-09 23:37:52',NULL),(208,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-09 23:37:52','2025-10-09 23:37:52',NULL),(209,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-09 23:37:52','2025-10-09 23:37:52',NULL),(210,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-09 23:37:52','2025-10-09 23:37:52',NULL),(211,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 00:01:27','2025-10-10 00:01:27',NULL),(212,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-10 00:01:27','2025-10-10 00:01:27',NULL),(213,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-10 00:01:27','2025-10-10 00:01:27',NULL),(214,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-10 00:01:27','2025-10-10 00:01:27',NULL),(215,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:01:27','2025-10-10 00:01:27',NULL),(216,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 00:04:17','2025-10-10 00:04:17',NULL),(217,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-10 00:04:17','2025-10-10 00:04:17',NULL),(218,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-10 00:04:17','2025-10-10 00:04:17',NULL),(219,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-10 00:04:17','2025-10-10 00:04:17',NULL),(220,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:04:17','2025-10-10 00:04:17',NULL),(221,1,'6f58781b-5b58-471e-9760-22562a72a7a6','Food',38.84,10,'weekly','weekly',NULL,NULL,0,38.839,'2025-10-10 00:04:49','2025-10-10 00:04:49',NULL),(222,1,'6f58781b-5b58-471e-9760-22562a72a7a6','Transportation',95.25,10,'weekly','weekly',NULL,NULL,0,95.253,'2025-10-10 00:04:49','2025-10-10 00:04:49',NULL),(223,1,'6f58781b-5b58-471e-9760-22562a72a7a6','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-10 00:04:49','2025-10-10 00:04:49',NULL),(224,1,'6f58781b-5b58-471e-9760-22562a72a7a6','Water Usage',9.90,11,'weekly','weekly',NULL,NULL,0,9.900,'2025-10-10 00:04:49','2025-10-10 00:04:49',NULL),(225,1,'6f58781b-5b58-471e-9760-22562a72a7a6','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:04:49','2025-10-10 00:04:49',NULL),(226,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 00:05:32','2025-10-10 00:05:32',NULL),(227,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','Transportation',137.59,10,'weekly','weekly',NULL,NULL,0,137.592,'2025-10-10 00:05:32','2025-10-10 00:05:32',NULL),(228,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-10 00:05:32','2025-10-10 00:05:32',NULL),(229,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','Water Usage',10.90,11,'weekly','weekly',NULL,NULL,0,10.900,'2025-10-10 00:05:32','2025-10-10 00:05:32',NULL),(230,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:05:32','2025-10-10 00:05:32',NULL),(231,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','Food',85.83,10,'weekly','weekly',NULL,NULL,0,85.832,'2025-10-10 00:15:38','2025-10-10 00:15:38',NULL),(232,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','Transportation',6.16,10,'weekly','weekly',NULL,NULL,0,6.160,'2025-10-10 00:15:38','2025-10-10 00:15:38',NULL),(233,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-10 00:15:38','2025-10-10 00:15:38',NULL),(234,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-10 00:15:38','2025-10-10 00:15:38',NULL),(235,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:15:38','2025-10-10 00:15:38',NULL),(236,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 00:20:49','2025-10-10 00:20:49',NULL),(237,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','Transportation',51.75,10,'weekly','weekly',NULL,NULL,0,51.751,'2025-10-10 00:20:49','2025-10-10 00:20:49',NULL),(238,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-10 00:20:49','2025-10-10 00:20:49',NULL),(239,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','Water Usage',3.50,11,'weekly','weekly',NULL,NULL,0,3.500,'2025-10-10 00:20:49','2025-10-10 00:20:49',NULL),(240,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:20:49','2025-10-10 00:20:49',NULL),(241,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 00:59:35','2025-10-10 00:59:35',NULL),(242,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b','Transportation',51.75,10,'weekly','weekly',NULL,NULL,0,51.751,'2025-10-10 00:59:35','2025-10-10 00:59:35',NULL),(243,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-10 00:59:35','2025-10-10 00:59:35',NULL),(244,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b','Water Usage',5.30,11,'weekly','weekly',NULL,NULL,0,5.300,'2025-10-10 00:59:35','2025-10-10 00:59:35',NULL),(245,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 00:59:35','2025-10-10 00:59:35',NULL),(246,1,'df684e37-2876-4d10-a30b-e16f6fd76e57','Food',11.97,10,'weekly','weekly',NULL,NULL,0,11.974,'2025-10-10 01:04:23','2025-10-10 01:04:23',NULL),(247,1,'df684e37-2876-4d10-a30b-e16f6fd76e57','Transportation',151.90,10,'weekly','weekly',NULL,NULL,0,151.897,'2025-10-10 01:04:23','2025-10-10 01:04:23',NULL),(248,1,'df684e37-2876-4d10-a30b-e16f6fd76e57','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-10 01:04:23','2025-10-10 01:04:23',NULL),(249,1,'df684e37-2876-4d10-a30b-e16f6fd76e57','Water Usage',7.85,11,'weekly','weekly',NULL,NULL,0,7.850,'2025-10-10 01:04:23','2025-10-10 01:04:23',NULL),(250,1,'df684e37-2876-4d10-a30b-e16f6fd76e57','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 01:04:23','2025-10-10 01:04:23',NULL),(251,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc','Food',14.00,10,'weekly','weekly',NULL,NULL,0,13.995,'2025-10-10 01:12:07','2025-10-10 01:12:07',NULL),(252,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc','Transportation',56.49,10,'weekly','weekly',NULL,NULL,0,56.486,'2025-10-10 01:12:07','2025-10-10 01:12:07',NULL),(253,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-10 01:12:07','2025-10-10 01:12:07',NULL),(254,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-10 01:12:07','2025-10-10 01:12:07',NULL),(255,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 01:12:07','2025-10-10 01:12:07',NULL),(256,1,'e5063d70-453b-4520-9272-16db245d8e82','Food',44.63,10,'weekly','weekly',NULL,NULL,0,44.627,'2025-10-10 01:17:24','2025-10-10 01:17:24',NULL),(257,1,'e5063d70-453b-4520-9272-16db245d8e82','Transportation',97.10,10,'weekly','weekly',NULL,NULL,0,97.099,'2025-10-10 01:17:24','2025-10-10 01:17:24',NULL),(258,1,'e5063d70-453b-4520-9272-16db245d8e82','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-10 01:17:24','2025-10-10 01:17:24',NULL),(259,1,'e5063d70-453b-4520-9272-16db245d8e82','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-10 01:17:24','2025-10-10 01:17:24',NULL),(260,1,'e5063d70-453b-4520-9272-16db245d8e82','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 01:17:24','2025-10-10 01:17:24',NULL),(261,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-10 02:00:22','2025-10-10 02:00:22',NULL),(262,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-10 02:00:22','2025-10-10 02:00:22',NULL),(263,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-10 02:00:22','2025-10-10 02:00:22',NULL),(264,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-10 02:00:22','2025-10-10 02:00:22',NULL),(265,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-10 02:00:22','2025-10-10 02:00:22',NULL),(266,1,'90791887-d6de-402e-94f9-48c4fe817e23','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-11 05:35:45','2025-10-11 05:35:45',NULL),(267,1,'90791887-d6de-402e-94f9-48c4fe817e23','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-11 05:35:45','2025-10-11 05:35:45',NULL),(268,1,'90791887-d6de-402e-94f9-48c4fe817e23','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-11 05:35:45','2025-10-11 05:35:45',NULL),(269,1,'90791887-d6de-402e-94f9-48c4fe817e23','Water Usage',6.05,11,'weekly','weekly',NULL,NULL,0,6.050,'2025-10-11 05:35:45','2025-10-11 05:35:45',NULL),(270,1,'90791887-d6de-402e-94f9-48c4fe817e23','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 05:35:45','2025-10-11 05:35:45',NULL),(271,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','Food',45.39,10,'weekly','weekly',NULL,NULL,0,45.385,'2025-10-11 06:10:04','2025-10-11 06:10:04',NULL),(272,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','Transportation',55.43,10,'weekly','weekly',NULL,NULL,0,55.433,'2025-10-11 06:10:04','2025-10-11 06:10:04',NULL),(273,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-11 06:10:04','2025-10-11 06:10:04',NULL),(274,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','Water Usage',6.05,11,'weekly','weekly',NULL,NULL,0,6.050,'2025-10-11 06:10:04','2025-10-11 06:10:04',NULL),(275,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 06:10:04','2025-10-11 06:10:04',NULL),(276,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-11 06:11:12','2025-10-11 06:11:12',NULL),(277,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-11 06:11:12','2025-10-11 06:11:12',NULL),(278,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-11 06:11:12','2025-10-11 06:11:12',NULL),(279,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-11 06:11:12','2025-10-11 06:11:12',NULL),(280,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 06:11:12','2025-10-11 06:11:12',NULL),(281,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-11 06:14:22','2025-10-11 06:14:22',NULL),(282,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','Transportation',51.75,10,'weekly','weekly',NULL,NULL,0,51.751,'2025-10-11 06:14:22','2025-10-11 06:14:22',NULL),(283,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-11 06:14:22','2025-10-11 06:14:22',NULL),(284,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-11 06:14:22','2025-10-11 06:14:22',NULL),(285,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 06:14:22','2025-10-11 06:14:22',NULL),(286,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-11 06:15:28','2025-10-11 06:15:28',NULL),(287,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-11 06:15:28','2025-10-11 06:15:28',NULL),(288,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-11 06:15:28','2025-10-11 06:15:28',NULL),(289,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','Water Usage',6.05,11,'weekly','weekly',NULL,NULL,0,6.050,'2025-10-11 06:15:28','2025-10-11 06:15:28',NULL),(290,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 06:15:28','2025-10-11 06:15:28',NULL),(291,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-11 06:38:37','2025-10-11 06:38:37',NULL),(292,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','Transportation',104.67,10,'weekly','weekly',NULL,NULL,0,104.675,'2025-10-11 06:38:37','2025-10-11 06:38:37',NULL),(293,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-11 06:38:37','2025-10-11 06:38:37',NULL),(294,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','Water Usage',4.85,11,'weekly','weekly',NULL,NULL,0,4.850,'2025-10-11 06:38:37','2025-10-11 06:38:37',NULL),(295,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','Waste Management',0.00,10,'weekly','weekly',NULL,NULL,0,0.000,'2025-10-11 06:38:37','2025-10-11 06:38:37',NULL),(296,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','Food',55.11,10,'weekly','weekly',NULL,NULL,0,55.106,'2025-10-11 06:56:38','2025-10-11 06:56:38',NULL),(297,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','Transportation',69.31,10,'weekly','weekly',NULL,NULL,0,69.311,'2025-10-11 06:56:38','2025-10-11 06:56:38',NULL),(298,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-11 06:56:38','2025-10-11 06:56:38',NULL),(299,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-11 06:56:38','2025-10-11 06:56:38',NULL),(300,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','Waste Management',9.98,10,'weekly','weekly',NULL,NULL,0,9.984,'2025-10-11 06:56:38','2025-10-11 06:56:38',NULL),(301,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','Food',76.42,10,'weekly','weekly',NULL,NULL,1,76.424,'2025-10-21 22:19:42','2025-10-21 22:19:42',NULL),(302,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','Transportation',13.62,10,'weekly','weekly',NULL,NULL,1,13.623,'2025-10-21 22:19:42','2025-10-21 22:19:42',NULL),(303,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','Energy',1.88,10,'weekly','weekly',NULL,NULL,1,1.876,'2025-10-21 22:19:42','2025-10-21 22:19:42',NULL),(304,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,1,1.250,'2025-10-21 22:19:42','2025-10-21 22:19:42',NULL),(305,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','Waste Management',-0.75,10,'weekly','weekly',NULL,NULL,1,-0.749,'2025-10-21 22:19:42','2025-10-21 22:19:42',NULL),(306,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-21 22:25:14','2025-10-21 22:25:14',NULL),(307,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-21 22:25:14','2025-10-21 22:25:14',NULL),(308,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-21 22:25:14','2025-10-21 22:25:14',NULL),(309,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-21 22:25:14','2025-10-21 22:25:14',NULL),(310,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','Waste Management',12.15,10,'weekly','weekly',NULL,NULL,0,12.146,'2025-10-21 22:25:14','2025-10-21 22:25:14',NULL),(311,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','Food',48.71,10,'weekly','weekly',NULL,NULL,0,48.715,'2025-10-21 22:26:17','2025-10-21 22:26:17',NULL),(312,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-21 22:26:17','2025-10-21 22:26:17',NULL),(313,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','Energy',14.07,10,'weekly','weekly',NULL,NULL,0,14.070,'2025-10-21 22:26:17','2025-10-21 22:26:17',NULL),(314,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','Water Usage',4.25,11,'weekly','weekly',NULL,NULL,0,4.250,'2025-10-21 22:26:17','2025-10-21 22:26:17',NULL),(315,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','Waste Management',12.15,10,'weekly','weekly',NULL,NULL,0,12.146,'2025-10-21 22:26:17','2025-10-21 22:26:17',NULL),(316,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','Food',97.43,10,'weekly','weekly',NULL,NULL,0,97.430,'2025-10-21 22:56:05','2025-10-21 22:56:05',NULL),(317,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','Transportation',13.62,10,'weekly','weekly',NULL,NULL,0,13.623,'2025-10-21 22:56:05','2025-10-21 22:56:05',NULL),(318,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-21 22:56:05','2025-10-21 22:56:05',NULL),(319,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-21 22:56:05','2025-10-21 22:56:05',NULL),(320,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','Waste Management',-0.75,10,'weekly','weekly',NULL,NULL,0,-0.749,'2025-10-21 22:56:05','2025-10-21 22:56:05',NULL),(321,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','Food',15.72,10,'weekly','weekly',NULL,NULL,0,15.719,'2025-10-21 23:13:23','2025-10-21 23:13:23',NULL),(322,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','Transportation',13.62,10,'weekly','weekly',NULL,NULL,0,13.623,'2025-10-21 23:13:23','2025-10-21 23:13:23',NULL),(323,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-21 23:13:23','2025-10-21 23:13:23',NULL),(324,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-21 23:13:23','2025-10-21 23:13:23',NULL),(325,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','Waste Management',-2.15,10,'weekly','weekly',NULL,NULL,0,-2.149,'2025-10-21 23:13:23','2025-10-21 23:13:23',NULL),(326,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','Food',4.71,10,'weekly','weekly',NULL,NULL,0,4.708,'2025-10-21 23:18:03','2025-10-21 23:18:03',NULL),(327,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','Transportation',10.36,10,'weekly','weekly',NULL,NULL,0,10.360,'2025-10-21 23:18:03','2025-10-21 23:18:03',NULL),(328,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','Energy',6.57,10,'weekly','weekly',NULL,NULL,0,6.566,'2025-10-21 23:18:03','2025-10-21 23:18:03',NULL),(329,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','Water Usage',3.50,11,'weekly','weekly',NULL,NULL,0,3.500,'2025-10-21 23:18:03','2025-10-21 23:18:03',NULL),(330,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','Waste Management',-2.10,10,'weekly','weekly',NULL,NULL,0,-2.099,'2025-10-21 23:18:03','2025-10-21 23:18:03',NULL),(331,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','Food',14.85,10,'weekly','weekly',NULL,NULL,0,14.848,'2025-10-21 23:20:14','2025-10-21 23:20:14',NULL),(332,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','Transportation',13.62,10,'weekly','weekly',NULL,NULL,0,13.623,'2025-10-21 23:20:14','2025-10-21 23:20:14',NULL),(333,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-21 23:20:14','2025-10-21 23:20:14',NULL),(334,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','Water Usage',6.05,11,'weekly','weekly',NULL,NULL,0,6.050,'2025-10-21 23:20:14','2025-10-21 23:20:14',NULL),(335,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','Waste Management',4.00,10,'weekly','weekly',NULL,NULL,0,3.996,'2025-10-21 23:20:14','2025-10-21 23:20:14',NULL),(336,1,'32109e95-b44b-48fa-825c-e068e45e93b3','Food',5.44,10,'weekly','weekly',NULL,NULL,0,5.435,'2025-10-21 23:28:24','2025-10-21 23:28:24',NULL),(337,1,'32109e95-b44b-48fa-825c-e068e45e93b3','Transportation',16.62,10,'weekly','weekly',NULL,NULL,0,16.615,'2025-10-21 23:28:24','2025-10-21 23:28:24',NULL),(338,1,'32109e95-b44b-48fa-825c-e068e45e93b3','Energy',1.88,10,'weekly','weekly',NULL,NULL,0,1.876,'2025-10-21 23:28:24','2025-10-21 23:28:24',NULL),(339,1,'32109e95-b44b-48fa-825c-e068e45e93b3','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,0,1.250,'2025-10-21 23:28:24','2025-10-21 23:28:24',NULL),(340,1,'32109e95-b44b-48fa-825c-e068e45e93b3','Waste Management',12.15,10,'weekly','weekly',NULL,NULL,0,12.146,'2025-10-21 23:28:24','2025-10-21 23:28:24',NULL),(341,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-21 23:59:36','2025-10-21 23:59:36',NULL),(342,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-21 23:59:36','2025-10-21 23:59:36',NULL),(343,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-21 23:59:36','2025-10-21 23:59:36',NULL),(344,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-21 23:59:36','2025-10-21 23:59:36',NULL),(345,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','Waste Management',-2.15,10,'weekly','weekly',NULL,NULL,0,-2.149,'2025-10-21 23:59:36','2025-10-21 23:59:36',NULL),(346,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-22 10:06:02','2025-10-22 10:06:02',NULL),(347,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-22 10:06:02','2025-10-22 10:06:02',NULL),(348,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-22 10:06:02','2025-10-22 10:06:02',NULL),(349,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-22 10:06:02','2025-10-22 10:06:02',NULL),(350,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','Waste Management',12.15,10,'weekly','weekly',NULL,NULL,0,12.146,'2025-10-22 10:06:02','2025-10-22 10:06:02',NULL),(351,13,'ab43e539-aebd-4d29-b437-8d66425de632','Food',5.23,10,'weekly','weekly',NULL,NULL,1,5.234,'2025-10-26 00:42:57','2025-10-26 00:42:57',NULL),(352,13,'ab43e539-aebd-4d29-b437-8d66425de632','Transportation',13.62,10,'weekly','weekly',NULL,NULL,1,13.623,'2025-10-26 00:42:57','2025-10-26 00:42:57',NULL),(353,13,'ab43e539-aebd-4d29-b437-8d66425de632','Energy',1.88,10,'weekly','weekly',NULL,NULL,1,1.876,'2025-10-26 00:42:57','2025-10-26 00:42:57',NULL),(354,13,'ab43e539-aebd-4d29-b437-8d66425de632','Water Usage',1.25,11,'weekly','weekly',NULL,NULL,1,1.250,'2025-10-26 00:42:57','2025-10-26 00:42:57',NULL),(355,13,'ab43e539-aebd-4d29-b437-8d66425de632','Waste Management',-2.15,10,'weekly','weekly',NULL,NULL,1,-2.149,'2025-10-26 00:42:57','2025-10-26 00:42:57',NULL),(356,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','Food',13.92,10,'weekly','weekly',NULL,NULL,0,13.919,'2025-10-26 00:43:53','2025-10-26 00:43:53',NULL),(357,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','Transportation',82.14,10,'weekly','weekly',NULL,NULL,0,82.136,'2025-10-26 00:43:53','2025-10-26 00:43:53',NULL),(358,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','Energy',10.32,10,'weekly','weekly',NULL,NULL,0,10.318,'2025-10-26 00:43:53','2025-10-26 00:43:53',NULL),(359,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','Water Usage',6.95,11,'weekly','weekly',NULL,NULL,0,6.950,'2025-10-26 00:43:53','2025-10-26 00:43:53',NULL),(360,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','Waste Management',3.28,10,'weekly','weekly',NULL,NULL,0,3.284,'2025-10-26 00:43:53','2025-10-26 00:43:53',NULL);
/*!40000 ALTER TABLE `footprint_category_totals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footprint_scores`
--

DROP TABLE IF EXISTS `footprint_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footprint_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attempt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_score` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_official` tinyint(1) NOT NULL DEFAULT '0',
  `basis` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `timeframe` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `kg_per_week` decimal(10,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_attempt` (`user_id`,`attempt_id`),
  KEY `footprint_scores_user_id_index` (`user_id`),
  KEY `footprint_scores_user_id_is_official_created_at_index` (`user_id`,`is_official`,`created_at`),
  KEY `footprint_scores_user_id_basis_index` (`user_id`,`basis`),
  KEY `footprint_scores_user_id_timeframe_index` (`user_id`,`timeframe`),
  KEY `footprint_scores_user_id_period_start_period_end_index` (`user_id`,`period_start`,`period_end`),
  KEY `footprint_scores_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `footprint_scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footprint_scores`
--

LOCK TABLES `footprint_scores` WRITE;
/*!40000 ALTER TABLE `footprint_scores` DISABLE KEYS */;
INSERT INTO `footprint_scores` VALUES (1,1,'a3e0b0f8-bff9-481e-9244-3352d0dca127',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 00:48:02','2025-10-06 00:48:02'),(2,1,'af40245a-6eb2-438e-9a20-1155ba19d1a2',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 01:18:17','2025-10-06 01:18:17'),(3,1,'b2a19018-458d-400b-811b-5c15a0b6b0f5',229.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 01:35:04','2025-10-06 01:35:04'),(4,1,'e97fb79d-9a4e-4f5e-8c36-3fa19d7e3c3f',776.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 01:59:03','2025-10-06 01:59:03'),(5,1,'6619d17d-55b0-4d73-941e-791cbdd44cbb',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:17:07','2025-10-06 02:17:07'),(6,1,'91a8b08b-c4a1-44b9-93a3-696f45f00c19',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:25:18','2025-10-06 02:25:18'),(7,1,'4ff4b32c-c8f0-4478-83c4-26d09c8b8798',347.20,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:28:21','2025-10-06 02:28:21'),(8,1,'ec911342-f23b-46e8-8ae2-a1b4fb385aa3',929.80,1,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:35:29','2025-10-06 02:35:29'),(9,1,'4ed8d2f0-1185-489c-b277-33190e9bb7ca',500.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:36:05','2025-10-06 02:36:05'),(10,1,'af3b79a6-3e01-433d-925a-37c44df3686e',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:54:18','2025-10-06 02:54:18'),(11,1,'dd6c203a-4636-4877-87d1-f808f8f9d9b7',964.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:57:50','2025-10-06 02:57:50'),(12,1,'1b7306e7-6b2e-476b-93f0-77b536e2853f',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-06 02:58:10','2025-10-06 02:58:10'),(13,1,'bafb71b3-8a7a-4a19-a1dc-6539dd935d8b',316.50,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-09 20:19:08','2025-10-09 20:19:08'),(14,1,'7ffcd919-ef74-4ae7-a905-3ee12a460585',612.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-09 20:21:04','2025-10-09 20:21:04'),(15,1,'21ad0e03-3d85-4458-8ff6-51fa9b9fff17',475.00,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-09 20:31:31','2025-10-09 20:31:31'),(16,1,'da953e08-2f92-4e64-86fa-471886d3d5fa',118.05,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-09 20:48:20','2025-10-09 20:48:20'),(17,1,'2d787e04-8ad6-4c65-9aa6-035ff86917ac',21.67,0,'weekly','weekly',NULL,NULL,0.000,'2025-10-09 20:51:11','2025-10-09 20:51:11'),(18,1,'0db6ad67-29e8-43be-aaa7-b46dae70463c',110.62,0,'weekly','weekly',NULL,NULL,110.623,'2025-10-09 21:09:06','2025-10-09 21:09:06'),(19,1,'ddd807a5-2b9d-4312-82fb-227009e1cd45',35.34,0,'weekly','weekly',NULL,NULL,35.344,'2025-10-09 21:35:25','2025-10-09 21:35:25'),(20,1,'2e3aaa2b-ae95-4df0-ad49-be31a4df9d3c',64.70,0,'weekly','weekly',NULL,NULL,64.705,'2025-10-09 21:57:13','2025-10-09 21:57:13'),(21,1,'6a592baf-3eb7-40b3-9c60-1f2ef87cec69',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 22:15:58','2025-10-09 22:15:58'),(22,1,'ebad2a5a-8a6b-4ce4-841b-f6bdff672521',90.63,0,'weekly','weekly',NULL,NULL,90.633,'2025-10-09 22:23:50','2025-10-09 22:23:50'),(23,1,'ed9ba331-bc39-4b2e-888f-515fbfdedd20',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 22:32:35','2025-10-09 22:32:35'),(24,1,'62479cc4-3a32-4939-990f-8febef21e3df',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 22:50:58','2025-10-09 22:50:58'),(25,1,'425897d8-882a-4091-8624-1bcff7e39a3c',153.58,0,'weekly','weekly',NULL,NULL,153.576,'2025-10-09 22:51:26','2025-10-09 22:51:26'),(26,1,'4d1b2bf3-8ce9-45cd-a36d-4a23a82a0210',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 22:52:20','2025-10-09 22:52:20'),(27,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 22:57:37','2025-10-09 22:57:37'),(28,1,'48a7af2f-a0ae-45a8-9c41-34a67b18fab2',111.67,0,'weekly','weekly',NULL,NULL,111.673,'2025-10-09 23:02:29','2025-10-09 23:02:29'),(29,1,'b71c5f44-f2a4-4071-9727-44316c344e87',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 23:04:07','2025-10-09 23:04:07'),(30,1,'c067daf7-3cee-4a0a-bff6-2e889ff565eb',109.87,0,'weekly','weekly',NULL,NULL,109.873,'2025-10-09 23:04:32','2025-10-09 23:04:32'),(31,1,'f2495970-cb78-477d-b26f-4f5cd60ed074',151.68,0,'weekly','weekly',NULL,NULL,151.679,'2025-10-09 23:06:30','2025-10-09 23:06:30'),(32,1,'f72b2114-aea9-4b5b-96d8-1b7bf02d1d21',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 23:14:44','2025-10-09 23:14:44'),(33,1,'788437a2-26d0-4fdf-9290-3f5c7e9744c6',63.67,0,'weekly','weekly',NULL,NULL,63.666,'2025-10-09 23:22:46','2025-10-09 23:22:46'),(34,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87',101.43,0,'weekly','weekly',NULL,NULL,101.431,'2025-10-09 23:28:09','2025-10-09 23:28:09'),(35,1,'b780336b-5759-445b-afbb-c110d5ef1358',195.29,0,'weekly','weekly',NULL,NULL,195.289,'2025-10-09 23:29:12','2025-10-09 23:29:12'),(36,1,'afa4741b-09df-4acf-936d-2f658909e5ae',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 23:29:47','2025-10-09 23:29:47'),(37,1,'6d616064-3256-4fcc-a76a-38cd54e5765e',169.83,0,'weekly','weekly',NULL,NULL,169.829,'2025-10-09 23:30:01','2025-10-09 23:30:01'),(38,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202',106.87,0,'weekly','weekly',NULL,NULL,106.871,'2025-10-09 23:31:00','2025-10-09 23:31:00'),(39,1,'794deaa8-b114-4fde-8c3d-04c622250eb6',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 23:31:13','2025-10-09 23:31:13'),(40,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed',151.68,0,'weekly','weekly',NULL,NULL,151.679,'2025-10-09 23:31:29','2025-10-09 23:31:29'),(41,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-09 23:37:52','2025-10-09 23:37:52'),(42,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-10 00:01:27','2025-10-10 00:01:27'),(43,1,'8e34d25b-f676-441e-9d39-5ccaadff95da',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-10 00:04:17','2025-10-10 00:04:17'),(44,1,'6f58781b-5b58-471e-9760-22562a72a7a6',158.06,0,'weekly','weekly',NULL,NULL,158.061,'2025-10-10 00:04:49','2025-10-10 00:04:49'),(45,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7',176.48,0,'weekly','weekly',NULL,NULL,176.481,'2025-10-10 00:05:32','2025-10-10 00:05:32'),(46,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6',95.12,0,'weekly','weekly',NULL,NULL,95.118,'2025-10-10 00:15:38','2025-10-10 00:15:38'),(47,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73',75.74,0,'weekly','weekly',NULL,NULL,75.736,'2025-10-10 00:20:49','2025-10-10 00:20:49'),(48,1,'235c40bc-c50e-427f-a84e-1d3a9383f02b',77.54,0,'weekly','weekly',NULL,NULL,77.536,'2025-10-10 00:59:35','2025-10-10 00:59:35'),(49,1,'df684e37-2876-4d10-a30b-e16f6fd76e57',185.79,0,'weekly','weekly',NULL,NULL,185.790,'2025-10-10 01:04:23','2025-10-10 01:04:23'),(50,1,'3ea7dd9f-8eb7-4771-aff3-827b1e7c56fc',87.75,0,'weekly','weekly',NULL,NULL,87.750,'2025-10-10 01:12:07','2025-10-10 01:12:07'),(51,1,'e5063d70-453b-4520-9272-16db245d8e82',162.75,0,'weekly','weekly',NULL,NULL,162.746,'2025-10-10 01:17:24','2025-10-10 01:17:24'),(52,1,'43e12d71-e099-4b21-bd32-dc20560ff4f0',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-10 02:00:22','2025-10-10 02:00:22'),(53,1,'90791887-d6de-402e-94f9-48c4fe817e23',112.42,0,'weekly','weekly',NULL,NULL,112.423,'2025-10-11 05:35:45','2025-10-11 05:35:45'),(54,1,'c1970522-adbf-4ccf-b271-490f441c8d7f',117.19,0,'weekly','weekly',NULL,NULL,117.186,'2025-10-11 06:10:04','2025-10-11 06:10:04'),(55,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c',113.32,0,'weekly','weekly',NULL,NULL,113.323,'2025-10-11 06:11:12','2025-10-11 06:11:12'),(56,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc',68.80,0,'weekly','weekly',NULL,NULL,68.796,'2025-10-11 06:14:22','2025-10-11 06:14:22'),(57,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb',112.42,0,'weekly','weekly',NULL,NULL,112.423,'2025-10-11 06:15:28','2025-10-11 06:15:28'),(58,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472',125.32,0,'weekly','weekly',NULL,NULL,125.319,'2025-10-11 06:38:37','2025-10-11 06:38:37'),(59,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96',151.67,0,'weekly','weekly',NULL,NULL,151.668,'2025-10-11 06:56:38','2025-10-11 06:56:38'),(60,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac',92.42,1,'weekly','weekly',NULL,NULL,92.423,'2025-10-21 22:19:42','2025-10-21 22:19:42'),(61,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c',125.47,0,'weekly','weekly',NULL,NULL,125.469,'2025-10-21 22:25:14','2025-10-21 22:25:14'),(62,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393',161.32,0,'weekly','weekly',NULL,NULL,161.317,'2025-10-21 22:26:17','2025-10-21 22:26:17'),(63,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34',113.43,0,'weekly','weekly',NULL,NULL,113.429,'2025-10-21 22:56:05','2025-10-21 22:56:05'),(64,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a',30.32,0,'weekly','weekly',NULL,NULL,30.318,'2025-10-21 23:13:23','2025-10-21 23:13:23'),(65,1,'52728032-e8c0-4b27-bcb9-3d07d490200f',23.03,0,'weekly','weekly',NULL,NULL,23.034,'2025-10-21 23:18:03','2025-10-21 23:18:03'),(66,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127',40.39,0,'weekly','weekly',NULL,NULL,40.392,'2025-10-21 23:20:14','2025-10-21 23:20:14'),(67,1,'32109e95-b44b-48fa-825c-e068e45e93b3',37.32,0,'weekly','weekly',NULL,NULL,37.322,'2025-10-21 23:28:24','2025-10-21 23:28:24'),(68,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2',111.17,0,'weekly','weekly',NULL,NULL,111.173,'2025-10-21 23:59:36','2025-10-21 23:59:36'),(69,1,'e272b036-cdbc-4fdb-9476-7b82fed42798',125.47,0,'weekly','weekly',NULL,NULL,125.469,'2025-10-22 10:06:02','2025-10-22 10:06:02'),(70,13,'ab43e539-aebd-4d29-b437-8d66425de632',19.83,1,'weekly','weekly',NULL,NULL,19.833,'2025-10-26 00:42:57','2025-10-26 00:42:57'),(71,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6',116.61,0,'weekly','weekly',NULL,NULL,116.606,'2025-10-26 00:43:53','2025-10-26 00:43:53');
/*!40000 ALTER TABLE `footprint_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `likes_post_id_user_id_unique` (`post_id`,`user_id`),
  KEY `likes_user_id_foreign` (`user_id`),
  CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (1,16,1,'2025-10-22 08:49:25','2025-10-22 08:49:25'),(3,23,1,'2025-10-22 08:49:39','2025-10-22 08:49:39'),(4,22,1,'2025-10-22 08:49:45','2025-10-22 08:49:45'),(5,7,1,'2025-10-22 09:29:51','2025-10-22 09:29:51'),(6,24,1,'2025-10-22 09:29:56','2025-10-22 09:29:56');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'2025_07_17_172914_create_password_resets_table',2),(5,'0001_01_01_000000_create_users_table',3),(6,'0001_01_01_000002_create_jobs_table',3),(7,'2025_07_17_180756_add_remember_token_to_users_table',4),(9,'2025_10_06_082416_create_footprint_category_totals_table',5),(10,'2025_10_06_082449_create_footprint_scores_table',6),(11,'2025_10_06_094846_add_is_official_to_footprint_scores',7),(12,'2025_10_06_094914_add_is_official_to_footprint_category_totals',8),(13,'2025_10_10_045652_alter_footprint_scores_for_timeframes',9),(14,'2025_10_10_050712_patch_footprint_category_totals_timeframes_safe',10),(15,'2025_10_10_054625_add_xp_to_users',11),(16,'2025_10_10_054651_create_xp_events',12),(17,'2025_10_11_135542_add_points_total_to_users',13),(18,'2025_10_11_135609_create_point_events_table',14),(19,'2025_10_22_061159_create_badges_table',15),(20,'2025_10_22_061209_create_user_badges_table',16),(21,'2025_10_22_155839_create_posts_table',17),(22,'2025_10_22_155839_create_comments_table',18),(23,'2025_10_22_155840_create_likes_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('karl111@gmail.com','$2y$12$TK2TcZM72R.UmquYUXLN3uziNY/iAUbx94x6iMOPynoFUXM7zSh3a','2025-07-21 00:50:12'),('zzzz@gmail.com','$2y$12$gb9zaFiMT1NS9qgfKKytyumb2W4qh45l6V9KYu5UMCvLt0joFAjgO','2025-07-21 01:01:34');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_events`
--

DROP TABLE IF EXISTS `point_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attempt_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `point_events_user_id_attempt_id_type_unique` (`user_id`,`attempt_id`,`type`),
  KEY `point_events_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `point_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_events`
--

LOCK TABLES `point_events` WRITE;
/*!40000 ALTER TABLE `point_events` DISABLE KEYS */;
INSERT INTO `point_events` VALUES (3,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": null, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 68.796}','2025-10-11 06:14:22','2025-10-11 06:14:22'),(4,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": null, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 112.423}','2025-10-11 06:15:28','2025-10-11 06:15:28'),(5,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": null, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 125.319}','2025-10-11 06:38:37','2025-10-11 06:38:37'),(6,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": null, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 151.668}','2025-10-11 06:56:38','2025-10-11 06:56:38'),(7,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": null, \"official\": true, \"timeframe\": \"weekly\", \"weekly_kg\": 92.423}','2025-10-21 22:19:43','2025-10-21 22:19:43'),(8,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 125.469}','2025-10-21 22:25:14','2025-10-21 22:25:14'),(9,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 161.317}','2025-10-21 22:26:17','2025-10-21 22:26:17'),(10,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 113.429}','2025-10-21 22:56:05','2025-10-21 22:56:05'),(11,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": true, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 30.318}','2025-10-21 23:13:23','2025-10-21 23:13:23'),(12,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": true, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 23.034}','2025-10-21 23:18:03','2025-10-21 23:18:03'),(13,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": true, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 40.392}','2025-10-21 23:20:14','2025-10-21 23:20:14'),(14,1,'32109e95-b44b-48fa-825c-e068e45e93b3','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": true, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 37.322}','2025-10-21 23:28:24','2025-10-21 23:28:24'),(15,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 111.173}','2025-10-21 23:59:36','2025-10-21 23:59:36'),(16,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','footprint_quiz',0,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 125.469}','2025-10-22 10:06:02','2025-10-22 10:06:02'),(17,13,'ab43e539-aebd-4d29-b437-8d66425de632','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": null, \"official\": true, \"timeframe\": \"weekly\", \"weekly_kg\": 19.833}','2025-10-26 00:42:58','2025-10-26 00:42:58'),(18,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','footprint_quiz',100,'{\"basis\": \"weekly\", \"improved\": false, \"official\": false, \"timeframe\": \"weekly\", \"weekly_kg\": 116.606}','2025-10-26 00:43:53','2025-10-26 00:43:53');
/*!40000 ALTER TABLE `point_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:20:54','2025-10-22 08:20:54'),(2,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:20:58','2025-10-22 08:20:58'),(3,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:00','2025-10-22 08:21:00'),(4,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:00','2025-10-22 08:21:00'),(5,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:00','2025-10-22 08:21:00'),(6,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:00','2025-10-22 08:21:00'),(7,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:00','2025-10-22 08:21:00'),(8,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:01','2025-10-22 08:21:01'),(9,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:01','2025-10-22 08:21:01'),(10,1,'SUSTENA','What is this website all about? Can someone enlighten me pls.','2025-10-22 08:21:01','2025-10-22 08:21:01'),(11,1,'SUSTENA','Example post','2025-10-22 08:21:12','2025-10-22 08:21:12'),(12,1,'SUSTENA','Example post','2025-10-22 08:21:12','2025-10-22 08:21:12'),(13,1,'SUSTENA','Example post','2025-10-22 08:21:13','2025-10-22 08:21:13'),(14,1,'SUSTENA','Example post','2025-10-22 08:21:13','2025-10-22 08:21:13'),(15,1,'SUSTENA','Example post','2025-10-22 08:21:13','2025-10-22 08:21:13'),(16,1,'SUSTENA','Example post','2025-10-22 08:21:13','2025-10-22 08:21:13'),(17,1,'SUSTENA','Example post','2025-10-22 08:21:14','2025-10-22 08:21:14'),(18,1,'SUSTENA','Example post','2025-10-22 08:21:14','2025-10-22 08:21:14'),(19,1,'SUSTENA','Example post','2025-10-22 08:21:14','2025-10-22 08:21:14'),(20,1,'SUSTENA','Example post','2025-10-22 08:21:14','2025-10-22 08:21:14'),(21,1,'SUSTENA','POST TEST','2025-10-22 08:21:49','2025-10-22 08:21:49'),(22,1,'test','123','2025-10-22 08:30:09','2025-10-22 08:30:09'),(23,1,'test','test1','2025-10-22 08:30:19','2025-10-22 08:30:19'),(24,1,'MALBERT','Who is malbert colarina?','2025-10-22 09:29:42','2025-10-22 09:29:42');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_badges`
--

DROP TABLE IF EXISTS `user_badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_badges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `badge_id` bigint unsigned NOT NULL,
  `awarded_at` timestamp NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_badges_user_id_badge_id_unique` (`user_id`,`badge_id`),
  KEY `user_badges_user_id_index` (`user_id`),
  KEY `user_badges_badge_id_index` (`badge_id`),
  CONSTRAINT `user_badges_badge_id_foreign` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_badges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_badges`
--

LOCK TABLES `user_badges` WRITE;
/*!40000 ALTER TABLE `user_badges` DISABLE KEYS */;
INSERT INTO `user_badges` VALUES (7,1,1,'2025-10-21 23:37:22','{\"weekly\": 37.322, \"attempt_id\": \"32109e95-b44b-48fa-825c-e068e45e93b3\"}','2025-10-21 23:37:22','2025-10-21 23:37:22'),(8,1,2,'2025-10-21 23:59:36','{\"now\": -2.149, \"prev\": 12.146, \"drop_pct\": 117.7, \"attempt_id\": \"b594901d-f2e0-4c08-a9f5-e23f9790d1a2\"}','2025-10-21 23:59:36','2025-10-21 23:59:36'),(9,13,1,'2025-10-26 00:42:58','{\"weekly\": 19.833, \"attempt_id\": \"ab43e539-aebd-4d29-b437-8d66425de632\"}','2025-10-26 00:42:58','2025-10-26 00:42:58');
/*!40000 ALTER TABLE `user_badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_registration` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xp_total` bigint unsigned NOT NULL DEFAULT '0',
  `points_total` bigint unsigned NOT NULL DEFAULT '0',
  `level` int unsigned NOT NULL DEFAULT '1',
  `streak_weeks` int unsigned NOT NULL DEFAULT '0',
  `last_official_week` date DEFAULT NULL,
  `last_xp_awarded_at` timestamp NULL DEFAULT NULL,
  `xp_today` int unsigned NOT NULL DEFAULT '0',
  `xp_this_week` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'karl0z','karl0z@mail.com','$2y$12$il5icLkVQQuB3DDiAYNEjeg9exeDYl.R0Gkdw2i6FXWjrWuCuKcge','2025-07-17 10:06:28','lCFcN1qO0Y2vitj8kcNVxSBcTs4OBYMN0dCXKJY6GrAc9kkmhqDHhtPZVjLF',489,720,3,0,NULL,'2025-10-22 10:06:02',190,190),(2,'driboz','driboz@mail.com','$2y$12$xJMS8LvJvbJSp7hzGOOLqu5JdcNULVCobgyR6isRMUuj.b1XsNANa','2025-07-17 10:21:10','3EIK3F9T2aSz5OGrF85PulzinamYsYgVnuAxCD8HZon8wA55Sr8yp0iqlfRS',0,0,1,0,NULL,NULL,0,0),(3,'carlos0z','carlos0z@gay.com','$2y$12$dNu7Y0tvifETQK/PycAxaOraMsV7.YbDyF5QC6jwLFs09AM5ZKnk.','2025-07-17 10:24:51','3dBz6Csq1b9bu00upweCyOzOxuaD9WzttzOoxEEz7KeGTlAclFEIJuYnEOmU',0,0,1,0,NULL,NULL,0,0),(4,'karl111','karl@gmail.com','$2y$12$9BzOt29RoEI.4KL6DDquzu88rr7AioH5JDwW4Ow9WDPQ1wT2EHy6G','2025-07-21 00:36:36',NULL,0,0,1,0,NULL,NULL,0,0),(5,'karl111','karl111@gmail.com','$2y$12$OaQU0s4l//5AMfFOdwiUtO50ZBguNpqXtbxOilQHHhbnlgWO1ZHri','2025-07-21 00:36:54',NULL,0,0,1,0,NULL,NULL,0,0),(6,'tester1','tester@gmail.com','$2y$12$8ZCzlL6Ll7CfgOX4l6JGi..1w3Ac6nMpAl8oYwjvEkl8Vs.1KAGP2','2025-07-21 00:52:42',NULL,0,0,1,0,NULL,NULL,0,0),(7,'tester12','tester12@gmail.com','$2y$12$8ZkrImVOLGNW7Kmls47BeODVHUIgiZijF92cKynDfcKFito/939ty','2025-07-21 00:57:57',NULL,0,0,1,0,NULL,NULL,0,0),(8,'test','test@gmail.com','$2y$12$yKFb6kP4d.KbAvI/bx8BEO3f2FjfWDO/6fJxK6BLcQ4/0t1az6Kku','2025-07-21 00:58:39',NULL,0,0,1,0,NULL,NULL,0,0),(9,'zzzz','zzzz@gmail.com','$2y$12$ymro0gDiqwUvkUyhejLDyeDxRQ956dQeYR7YesfypFNXaK2VG7kFm','2025-07-21 01:00:16',NULL,0,0,1,0,NULL,NULL,0,0),(10,'Test1','Test1@gmail.com','$2y$12$4QeK8SN6lCZCn5FW/ZWKEeQc1.VViWiqsjznPm9b.pXwreKSOLArG','2025-07-21 01:08:29','lTq6VwOlH1HGSfgtaVJB6Rw7qDodOMNZFU73RCzs7OY92ugJBnYGVsNo6c1O',0,0,1,0,NULL,NULL,0,0),(11,'carlll','carlll@gmail.com','$2y$12$ts9JeMNXaJ7gL736y8i3ieq/jRCD8rJCI/C7t3dOGTZK21VfwSoKa','2025-07-21 01:13:37',NULL,0,0,1,0,NULL,NULL,0,0),(12,'karlll','karlll@gmail.com','$2y$12$DSHuIIAWOD6d8uMPzYBgNuTOhTW6isTL0fms7gfSFZpkHKYaHepV6','2025-09-07 02:45:15',NULL,0,0,1,0,NULL,NULL,0,0),(13,'karl0zz','karl123@gmail.com','$2y$12$MP5pXPkB6PyTL.h.XS27YOpNFkohOgARiTaQeNLrklhvYETqIWZ66','2025-10-26 00:40:30',NULL,74,300,1,0,NULL,'2025-10-26 00:43:53',74,74);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xp_events`
--

DROP TABLE IF EXISTS `xp_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `xp_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attempt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `xp` int NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_attempt_type` (`user_id`,`attempt_id`,`type`),
  KEY `xp_events_user_id_index` (`user_id`),
  KEY `xp_events_attempt_id_index` (`attempt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xp_events`
--

LOCK TABLES `xp_events` WRITE;
/*!40000 ALTER TABLE `xp_events` DISABLE KEYS */;
INSERT INTO `xp_events` VALUES (7,1,'1b090a7b-b86f-4d69-8d72-7211db8fc3ca','practice_small',14,'[]','2025-10-09 22:57:37','2025-10-09 22:57:37'),(8,1,'1e7e0e61-22d1-4867-a2c3-7cb94e549a87','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:28:09','2025-10-09 23:28:09'),(9,1,'b780336b-5759-445b-afbb-c110d5ef1358','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:29:12','2025-10-09 23:29:12'),(10,1,'afa4741b-09df-4acf-936d-2f658909e5ae','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:29:47','2025-10-09 23:29:47'),(11,1,'6d616064-3256-4fcc-a76a-38cd54e5765e','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:30:01','2025-10-09 23:30:01'),(12,1,'d6d964cb-bcaf-4f8a-9aa9-a52a482a1202','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:31:00','2025-10-09 23:31:00'),(13,1,'794deaa8-b114-4fde-8c3d-04c622250eb6','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:31:13','2025-10-09 23:31:13'),(14,1,'b2f8ba4b-5751-456c-8842-ca1cbd18c0ed','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:31:29','2025-10-09 23:31:29'),(15,1,'b1bf82fd-9bae-43c7-a209-15a038b70e3c','practice_small',14,'{\"multi_per_day\": true}','2025-10-09 23:37:52','2025-10-09 23:37:52'),(16,1,'967a1d8a-d2f3-4f70-b541-f711d8c9b95e','practice_small',14,'{\"multi_per_day\": true}','2025-10-10 00:01:27','2025-10-10 00:01:27'),(17,1,'8e34d25b-f676-441e-9d39-5ccaadff95da','practice_small',14,'{\"multi_per_day\": true}','2025-10-10 00:04:17','2025-10-10 00:04:17'),(18,1,'6f58781b-5b58-471e-9760-22562a72a7a6','practice_small',14,'{\"multi_per_day\": true}','2025-10-10 00:04:49','2025-10-10 00:04:49'),(19,1,'6ee82da1-0227-4f9f-8443-c76c9bb287d7','practice_small',14,'{\"multi_per_day\": true}','2025-10-10 00:05:32','2025-10-10 00:05:32'),(20,1,'124439b5-93e8-4f42-b0b6-5970f454f3a6','practice_small',15,'{\"multi_per_day\": true}','2025-10-10 00:15:38','2025-10-10 00:15:38'),(21,1,'bcbd184f-a48f-45e8-b1e5-0b5b33b27c73','practice_small',3,'{\"multi_per_day\": true}','2025-10-10 00:20:49','2025-10-10 00:20:49'),(22,1,'90791887-d6de-402e-94f9-48c4fe817e23','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 05:35:45','2025-10-11 05:35:45'),(23,1,'c1970522-adbf-4ccf-b271-490f441c8d7f','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 06:10:04','2025-10-11 06:10:04'),(24,1,'2c6d925f-4569-4e18-953d-04b7c9f7f62c','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 06:11:12','2025-10-11 06:11:12'),(25,1,'d8654151-4e8c-4b23-a0a5-2b2d69117ffc','practice_small',15,'{\"multi_per_day\": true}','2025-10-11 06:14:22','2025-10-11 06:14:22'),(26,1,'26054a21-59f1-4a7a-a49b-0aac2486b3cb','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 06:15:28','2025-10-11 06:15:28'),(27,1,'3cc3d0cf-ebee-4db0-bc61-cdaf79681472','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 06:38:37','2025-10-11 06:38:37'),(28,1,'96a070d9-ac8f-4ec4-b6ec-00bcd1d96b96','practice_small',14,'{\"multi_per_day\": true}','2025-10-11 06:56:38','2025-10-11 06:56:38'),(29,1,'f065f36f-4477-4cd0-82bc-0f418695d3ac','attempt_base',60,'[]','2025-10-21 22:19:42','2025-10-21 22:19:42'),(30,1,'c0d1391a-db06-4313-88aa-ea6b70ddc63c','practice_small',14,'{\"multi_per_day\": true}','2025-10-21 22:25:14','2025-10-21 22:25:14'),(31,1,'3eddfb2d-5d7d-4bfa-9467-9f90457bc393','practice_small',14,'{\"multi_per_day\": true}','2025-10-21 22:26:17','2025-10-21 22:26:17'),(32,1,'eda76a92-9fa8-4a3e-b62c-968a1dba6d34','practice_small',14,'{\"multi_per_day\": true}','2025-10-21 22:56:05','2025-10-21 22:56:05'),(33,1,'95ba7e74-0459-4944-bbd9-dfb518984a2a','practice_small',15,'{\"multi_per_day\": true}','2025-10-21 23:13:23','2025-10-21 23:13:23'),(34,1,'52728032-e8c0-4b27-bcb9-3d07d490200f','practice_small',15,'{\"multi_per_day\": true}','2025-10-21 23:18:03','2025-10-21 23:18:03'),(35,1,'c6c9016f-55ec-4b3f-b546-2878cdf62127','practice_small',15,'{\"multi_per_day\": true}','2025-10-21 23:20:14','2025-10-21 23:20:14'),(36,1,'32109e95-b44b-48fa-825c-e068e45e93b3','practice_small',15,'{\"multi_per_day\": true}','2025-10-21 23:28:24','2025-10-21 23:28:24'),(37,1,'b594901d-f2e0-4c08-a9f5-e23f9790d1a2','practice_small',14,'{\"multi_per_day\": true}','2025-10-21 23:59:36','2025-10-21 23:59:36'),(38,1,'e272b036-cdbc-4fdb-9476-7b82fed42798','practice_small',14,'{\"multi_per_day\": true}','2025-10-22 10:06:02','2025-10-22 10:06:02'),(39,13,'ab43e539-aebd-4d29-b437-8d66425de632','attempt_base',60,'[]','2025-10-26 00:42:57','2025-10-26 00:42:57'),(40,13,'03399cea-b1d1-41d7-a02a-65f3617b20a6','practice_small',14,'{\"multi_per_day\": true}','2025-10-26 00:43:53','2025-10-26 00:43:53');
/*!40000 ALTER TABLE `xp_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'sustena'
--

--
-- Dumping routines for database 'sustena'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-26 16:45:26
