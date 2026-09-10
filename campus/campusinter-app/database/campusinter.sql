/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: campusinter
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academic_years`
--

DROP TABLE IF EXISTS `academic_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_years` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT 'Ex: 2025-2026',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_academic_year_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_years`
--

LOCK TABLES `academic_years` WRITE;
/*!40000 ALTER TABLE `academic_years` DISABLE KEYS */;
INSERT INTO `academic_years` VALUES
(1,'2025-2026',0,'2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,'2026-2027',1,'2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,'2027-2028',0,'2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `academic_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_admin_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
(1,'Administrateur','admin@campusinter.com','$2y$12$q.f490/dLwGKw44tzqlW9OWEd8gyhCXK4vhqfCQfGCwnwwFU.XjUm','active','2026-09-10 14:50:14','2026-09-10 13:44:24','2026-09-10 14:50:14');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL COMMENT 'Ex: CI-2026-000001',
  `academic_year_id` int(10) unsigned NOT NULL,
  `program_id` int(10) unsigned NOT NULL,
  `campus_id` int(10) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(180) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `last_diploma` varchar(150) DEFAULT NULL,
  `last_diploma_institution` varchar(200) DEFAULT NULL,
  `last_diploma_year` year(4) DEFAULT NULL,
  `bac_year` year(4) DEFAULT NULL,
  `bac_series` varchar(50) DEFAULT NULL,
  `bac_average` decimal(4,2) DEFAULT NULL,
  `last_diploma_average` decimal(4,2) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','processing','accepted','rejected','archived') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_application_reference` (`reference`),
  KEY `fk_app_academic_year` (`academic_year_id`),
  KEY `fk_app_program` (`program_id`),
  KEY `fk_app_campus` (`campus_id`),
  KEY `idx_app_status` (`status`),
  KEY `idx_app_created` (`created_at`),
  KEY `idx_app_email` (`email`),
  CONSTRAINT `fk_app_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_app_campus` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_app_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES
(1,'CI-2026-000001',2,11,5,'Roméo Carlos','Doe','romeo.afanvi@gmail.com','+228 96 79 49 42','2003-09-26','Licence en genie logiciel','ESIG GLOBAL SUCCES',2026,2022,'D',12.00,11.83,'lqmskjfl qfjlsqkjfapoizf qdkjvlsdnqlskdjmqlsjf','archived','2026-09-10 14:54:58','2026-09-10 14:57:10'),
(2,'CI-2026-000002',2,11,5,'John doh','Doe','romeo.afanvi@gmail.com','+228 96 79 49 42','2026-09-26','Licence en genie logiciel','ESIG GLOBAL SUCCES',2018,2016,'C',13.50,13.92,'mllksdqjflk sfzaoflksqdjsqdufiomlsqkdjfmqsl jfoa flmsqjlksqjflmksj','new','2026-09-10 15:00:28','2026-09-10 15:00:28'),
(3,'CI-2026-000003',2,11,5,'Roméo Carlos','Doe','romeo.afanvi@gmail.com','+228 96 79 49 42','2005-09-27','Licence en genie logiciel','ESIG GLOBAL SUCCES',2024,2021,'D',12.11,14.00,'qsldfmjsqldkf sqfqsfjpozae mfsqglsdfs','accepted','2026-09-10 15:41:37','2026-09-10 15:53:03');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campuses`
--

DROP TABLE IF EXISTS `campuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `campuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `institution_id` int(10) unsigned NOT NULL,
  `city_id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_campus_name_institution` (`institution_id`,`name`),
  KEY `fk_campus_city` (`city_id`),
  CONSTRAINT `fk_campus_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_campus_institution` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campuses`
--

LOCK TABLES `campuses` WRITE;
/*!40000 ALTER TABLE `campuses` DISABLE KEYS */;
INSERT INTO `campuses` VALUES
(1,1,1,'Campus Universitaire de Lomé','Boulevard du 13 Januar, Lomé','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,1,1,'Campus Nord','Zone Universitaire, Lomé','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,2,2,'Campus UAC Centre','Abomey-Calavi, Cotonou','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,2,2,'Campus UAC Porto-Novo','Porto-Novo, Bénin','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,3,3,'Campus de Cocody','Cocody, Abidjan','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,3,3,'Campus de Yopougon','Yopougon, Abidjan','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,4,1,'IAI Lomé','Boulevard du 13 Januar, Lomé','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(8,4,3,'IAI Abidjan','Plateau, Abidjan','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(9,5,1,'ESG Lomé','Agbalepedogan, Lomé','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(10,5,2,'ESG Cotonou','Haie-Vive, Cotonou','active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `campuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'Togo',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_city_name_country` (`name`,`country`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES
(1,'Lomé','Togo','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,'Cotonou','Bénin','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,'Abidjan','Côte d\'Ivoire','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,'Dakar','Sénégal','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,'Ouagadougou','Burkina Faso','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,'Niamey','Niger','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,'Bamako','Mali','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(8,'Conakry','Guinée','active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_domain_name` (`name`),
  UNIQUE KEY `uk_domain_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` VALUES
(1,'Informatique','informatique','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,'Gestion','gestion','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,'Droit','droit','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,'Sciences et Technologie','sciences-et-technologie','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,'Santé','sante','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,'Éducation','education','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,'Autre','autre','active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institutions`
--

DROP TABLE IF EXISTS `institutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `institutions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_institution_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institutions`
--

LOCK TABLES `institutions` WRITE;
/*!40000 ALTER TABLE `institutions` DISABLE KEYS */;
INSERT INTO `institutions` VALUES
(1,'Université de Lomé','Principale université du Togo',NULL,'https://www.univ-lome.tg','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,'Université d\'Abomey-Calavi','Université publique du Bénin',NULL,'https://www.uac.bj','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,'Université Félix Houphouët-Boigny','Université de Cocody, Abidjan',NULL,'https://www.univ-fhb.ci','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,'Institut Africain d\'Informatique','École supérieure informatique',NULL,'https://www.iai-informatique.com','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,'École Supérieure de Gestion','ESG - Formation en gestion',NULL,NULL,'active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `institutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `level` enum('info','warning','error','critical') NOT NULL DEFAULT 'info',
  `category` varchar(50) NOT NULL COMMENT 'email, import, payment, system, security',
  `message` text NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_log_level` (`level`),
  KEY `idx_log_category` (`category`),
  KEY `idx_log_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES
(1,'info','security','Admin connecté: admin@campusinter.com',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 14:50:14'),
(2,'info','system','Candidature créée: CI-2026-000001','{\"program\":\"Doctorat Médecine\",\"campus\":\"Campus de Cocody\"}','127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 14:54:58'),
(3,'info','system','Statut candidature mis à jour: ID 1 → archived',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 14:57:10'),
(4,'info','system','Candidature créée: CI-2026-000002','{\"program\":\"Doctorat Médecine\",\"campus\":\"Campus de Cocody\"}','127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 15:00:28'),
(5,'info','system','Candidature créée: CI-2026-000003','{\"program\":\"Doctorat Médecine\",\"campus\":\"Campus de Cocody\"}','127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 15:41:37'),
(6,'error','email','Erreur envoi email à admission@rabatam.ci.com: SMTP Error: Could not authenticate.',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 15:41:40'),
(7,'error','email','Erreur envoi email à romeo.afanvi@gmail.com: SMTP Error: Could not authenticate.',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 15:41:42'),
(8,'info','system','Statut candidature mis à jour: ID 3 → accepted',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','2026-09-10 15:53:03');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'FCFA',
  `provider` varchar(50) DEFAULT NULL COMMENT 'Ex: orange_money, mtn_mobile',
  `transaction_reference` varchar(100) DEFAULT NULL,
  `status` enum('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_payment_application` (`application_id`),
  KEY `idx_payment_status` (`status`),
  CONSTRAINT `fk_payment_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_campuses`
--

DROP TABLE IF EXISTS `program_campuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_campuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` int(10) unsigned NOT NULL,
  `campus_id` int(10) unsigned NOT NULL,
  `academic_year_id` int(10) unsigned NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_program_campus_year` (`program_id`,`campus_id`,`academic_year_id`),
  KEY `fk_pc_campus` (`campus_id`),
  KEY `fk_pc_academic_year` (`academic_year_id`),
  CONSTRAINT `fk_pc_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pc_campus` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pc_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_campuses`
--

LOCK TABLES `program_campuses` WRITE;
/*!40000 ALTER TABLE `program_campuses` DISABLE KEYS */;
INSERT INTO `program_campuses` VALUES
(1,1,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,1,7,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,1,5,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,2,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,2,7,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,3,5,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,3,8,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(8,4,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(9,4,5,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(10,5,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(11,6,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(12,6,9,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(13,6,3,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(14,7,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(15,7,10,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(16,8,9,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(17,8,10,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(18,9,1,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(19,9,3,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(20,10,5,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(21,10,6,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(22,11,5,2,'active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `program_campuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `academic_year_id` int(10) unsigned NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `specialty_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `level` enum('Bac','Bac+1','Bac+2','Bac+3','Bac+4','Bac+5','Doctorat','Autre') NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL COMMENT 'Ex: 3 ans, 2 semestres',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_program_academic_year` (`academic_year_id`),
  KEY `fk_program_domain` (`domain_id`),
  KEY `fk_program_specialty` (`specialty_id`),
  KEY `idx_program_level` (`level`),
  KEY `idx_program_status` (`status`),
  CONSTRAINT `fk_program_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_program_domain` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_program_specialty` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES
(1,2,1,1,'Master Génie Logiciel','Bac+5','Formation approfondie en développement logiciel, architecture et gestion de projets IT.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,2,1,2,'Master Réseaux et Systèmes','Bac+5','Spécialisation en administration réseau, systèmes distribués et cloud computing.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,2,1,3,'Master Cybersécurité','Bac+5','Protection des systèmes d\'information, audit de sécurité et réponse aux incidents.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,2,1,4,'Master Intelligence Artificielle','Bac+5','Apprentissage automatique, deep learning et systèmes intelligents.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,2,1,5,'Master Data Science','Bac+5','Analyse de données, statistiques avancées et visualisation.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,2,2,6,'Licence Management','Bac+3','Fondamentaux du management et de l\'organisation.','3 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,2,2,7,'Master Finance','Bac+5','Finance d\'entreprise, marchés financiers et gestion de portefeuille.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(8,2,2,8,'Master Marketing Digital','Bac+5','Stratégies marketing, communication digitale et e-commerce.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(9,2,3,10,'Licence Droit Privé','Bac+3','Droit civil, droit commercial et procédures judiciaires.','3 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(10,2,4,13,'Master Génie Civil','Bac+5','Construction, structures, matériaux et genie parasismique.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(11,2,5,16,'Doctorat Médecine','Doctorat','Formation médicale complète sur 6 ans.','6 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(12,1,1,1,'Master Génie Logiciel','Bac+5','Formation en développement logiciel.','2 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(13,1,2,6,'Licence Management','Bac+3','Fondamentaux du management.','3 ans','active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rate_limits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `endpoint` varchar(100) NOT NULL,
  `attempts` int(10) unsigned NOT NULL DEFAULT 1,
  `first_attempt_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_attempt_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rate_limit` (`ip_address`,`endpoint`),
  KEY `idx_rate_limit_cleanup` (`first_attempt_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
INSERT INTO `rate_limits` VALUES
(3,'127.0.0.1','application',1,'2026-09-10 15:41:37','2026-09-10 15:41:37');
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialties`
--

DROP TABLE IF EXISTS `specialties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_specialty_name_domain` (`domain_id`,`name`),
  UNIQUE KEY `uk_specialty_slug` (`slug`),
  CONSTRAINT `fk_specialty_domain` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialties`
--

LOCK TABLES `specialties` WRITE;
/*!40000 ALTER TABLE `specialties` DISABLE KEYS */;
INSERT INTO `specialties` VALUES
(1,1,'Génie Logiciel','genie-logiciel','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(2,1,'Réseaux et Systèmes','reseaux-et-systemes','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(3,1,'Cybersécurité','cybersecurite','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(4,1,'Intelligence Artificielle','intelligence-artificielle','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(5,1,'Data Science','data-science','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(6,2,'Management','management','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(7,2,'Finance','finance','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(8,2,'Marketing','marketing','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(9,2,'Comptabilité','comptabilite','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(10,3,'Droit Privé','droit-prive','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(11,3,'Droit Public','droit-public','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(12,3,'Droit des Affaires','droit-des-affaires','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(13,4,'Génie Civil','genie-civil','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(14,4,'Énergie','energie','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(15,4,'Électrotechnique','electrotechnique','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(16,5,'Médecine','medecine','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(17,5,'Pharmacie','pharmacie','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(18,5,'Odontologie','odontologie','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(19,6,'Sciences de l\'Éducation','sciences-de-education','active','2026-09-10 13:44:24','2026-09-10 13:44:24'),
(20,6,'Formation des Enseignants','formation-des-enseignants','active','2026-09-10 13:44:24','2026-09-10 13:44:24');
/*!40000 ALTER TABLE `specialties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'campusinter'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-10 16:00:27
