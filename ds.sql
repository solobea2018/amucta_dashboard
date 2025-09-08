-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dashboard
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `access_tokens`
--

DROP TABLE IF EXISTS `access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_tokens` (
  `token` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_tokens`
--

LOCK TABLES `access_tokens` WRITE;
/*!40000 ALTER TABLE `access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `related_to` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
INSERT INTO `attachments` VALUES (2,'trans request form','/attachments/files/trans_request_form_1756846594.pdf','type1','rre',72,'2025-09-02 20:56:34'),(3,'Request of Printing/Reprinting Transcript/Certificate','/assets/files/PRINTING_REPRINTIG_TRANSCRIPT_CERTIFICATE.pdf','document','general',1,'2025-09-04 15:27:26'),(4,'Certificate Request form','/assets/files/Certificate_Request_Form.pdf','document','general',1,'2025-09-04 15:27:26'),(5,'Transcript Request form','/assets/files/Transcript_Request_Form.pdf','document','general',1,'2025-09-04 15:27:26'),(6,'Press Release','/assets/files/press_release.pdf','document','general',1,'2025-09-04 15:27:26'),(7,'Almanac','/assets/files/almanac_2024_2025.pdf','document','general',1,'2025-09-04 15:27:26'),(8,'Fee Structure','/assets/files/fee_structure.pdf','document','finance',1,'2025-09-04 15:27:26'),(9,'Registration Form','/assets/files/reg_form.pdf','document','admission',1,'2025-09-04 15:27:26'),(10,'Prospectus','/assets/files/prospectus.pdf','document','admission',1,'2025-09-04 15:27:26');
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read_status` enum('read','unread') DEFAULT 'unread',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deleted_users_history`
--

DROP TABLE IF EXISTS `deleted_users_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deleted_users_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `deleted_users_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deleted_users_history`
--

LOCK TABLES `deleted_users_history` WRITE;
/*!40000 ALTER TABLE `deleted_users_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `deleted_users_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `department_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'Business administration','busines',2,'2025-09-02 19:54:22',72),(2,'Science and Engineering','sciences',2,'2025-09-02 19:54:57',72);
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `qualification` text DEFAULT NULL,
  `entry_year` varchar(6) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_employee_department` (`department_id`),
  CONSTRAINT `fk_employee_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (4,'Sr.Dr. Sophia Mbihije','Lecturer',NULL,NULL,'../../../../assets/staff/mbihije.jpg','PhD (Education in Curriculum & Instruction): Loyola University, Chicago (USA); MSc (Analytical Chemistry): Loyola University, Chicago (USA); BSc (Chemistry): Madonna University, Michigan (USA)',NULL,1,'2025-09-04 16:15:33',1,'../../../../assets/files/sophia_mbihije_cv.pdf',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(5,'Rev. Dr. Apolinary K. Ndasi','Lecturer',NULL,NULL,'../../../../assets/staff/kaswala.jpg','PhD (Educational Administration & Planning): Mwenge Catholic University; Master of Educational Administration: CUEA; PGDE: CUEA; Bachelor of Sacred Theology: Pontifical Urbanian University (Italy)',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(6,'Dr. Daniel Dogbe','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Special Education): Ball State University; MPhil (Special Needs - VI): University of Cape Coast; BED (Special Needs): University of Education, Winneba, Ghana',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(7,'Dr Samuel Amoako','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Special Education): UEW (Ghana); MED (Special Educational Needs): University of Leeds; Master (Hearing Impairment): University of Manchester; Diploma (Special Education): University of Cape Coast',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(8,'Dr Ephraim Kalanje','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Philosophy): Åbo Akademi University; MED (Special Educational Needs): Åbo Akademi University; BED (Special Needs): Åbo Akademi University',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(9,'Dr. Eugen Philip','Lecturer',NULL,NULL,'../../../../assets/staff/eugen.jpg','PhD Philosophy: University of Dodoma; MED (Open & Distance Learning): Open University of Tanzania; Bachelor of Education: University of Dar es Salaam',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(10,'Dr. Ildephonce Mkama','Senior Lecturer',NULL,NULL,'../../../../assets/staff/mkama.jpeg','PhD Philosophy: University of the Witwatersrand; MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(11,'Dr. Julius Nyaombo','Lecturer',NULL,NULL,'../../../../assets/staff/nyaombo.jpg','PhD (Natural Resource Assessment & Management): UDSM; MSc (NRA&M): UDSM; BAED (Geography): UDSM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)GRAPHY'),(12,'Dr. Luther I. Kawiche','Lecturer',NULL,NULL,'../../../../assets/staff/kawiche.jpg','PhD (Demography): UDSM; MA (Demography): UDSM; BA (Geography & Environmental Studies): UDSM',NULL,1,'2025-09-04 16:15:33',1,'',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(13,'Dr. Alfred Ong\'ang\'a','Lecturer',NULL,NULL,'../../../../assets/staff/alfred.jpg','PhD (Literature): Kenyatta University; MA (Literature): Kenyatta University; BA (Education): Kenyatta University',NULL,1,'2025-09-04 16:15:33',1,'',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(14,'Grace Mkosamali','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/mkosamali.jpeg','MA (Counselling Psychology): Bishop Magambo (Uganda); Bachelor (Counselling Psychology): University of Kisubi (Uganda)',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(15,'Koboli Milobo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/koboli.jpg','MED (Management & Planning): Stella Maris University College; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(16,'Renata C. Nyelo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/nyero.jpg','MED (Administration & Planning): CUEA (Kenya); BED (Geography & History): CUEA (Kenya)',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(17,'Anna Ahmed','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/anna.jpg','Master of Geography & Environmental Management: UDSM; BA (Education): UDSM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / GEOGRAPHY'),(18,'Einhard Mgaya','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (History): SAUT; Bachelor of Education: CUEA (Kenya)',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / HISTORY'),(19,'Clavery Kayugumi','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/kayugumi.jpg','MA (History): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / HISTORY'),(20,'Triphonius Lissu','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/lissu.jpg','MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(21,'Rainer Likongo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/likongo.jpg','MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(22,'Gwakisa Kaswaga','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/kaswaga.jpg','MA (Literature): UDSM; Bachelor of Education: Teofili Kisanji University',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(23,'Andrew Kifua','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MBA: SAUT; BBA (Accounts): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(24,'Harriet Malichi','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): University of Zambia',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(25,'Fraterinus O. Mutatembwa','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/osward.jpg','MED (Special Education - HI): SEKOMU; BED (Special Needs): SEKOMU',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(26,'Gideon D. Kaziri','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/gideon.jpeg','MED (Special Education - HI): SEKOMU; BED (Special Needs): SEKOMU',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(27,'Elijah Kokse','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/koske.jpg','MED (Special Needs): Brunel University; BED (Special Needs): Kenyatta University',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(28,'Filbert Zomba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/zomba.jpg','MBA: SAUT; Advanced Diploma (Accountancy): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(29,'Placid Komba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/komba.jpg','Master of Business Management: MCoU; BA (Marketing & Entrepreneurship): MCoU',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(30,'Patrick Chekwe','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA GEM: UDSM; BA GEN: UDSM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(31,'Saikon J. Nokoren','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/justin.jpeg','PDLP: The Law School of Tanzania; LLM: RUCU; LLB: Tumaini University',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(32,'Faustine Rwechungura','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','Master of Theology: Radboud University; Bachelor of Theology: CUEA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(33,'Edgar Pastory','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/mushumbushi.jpg','MA (Sociology): SAUT; BA (Sociology): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(34,'Upendo Ulaya','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (History): Gotland University; BAED: SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(35,'Shukuru Mukama','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Kiswahili - Fasihi): UDSM; BA (Education): OUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(36,'Denice Salapion','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Kiswahili - Isimu): UDSM; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(37,'Msei Nyagani','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Linguistics): SAUT; BAED: MUM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(38,'Karuta Musa','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Early Childhood): UDOM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(39,'Gaga Nidwa','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(40,'Cronel Diogenes','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(41,'Sr Martha Herman','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(42,'Jackson Manase','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(43,'Magreth Nkuba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(44,'Ansila Nyaki','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(45,'Nasfath Rugimbana','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/nsfat.jpg','Bachelor of Early Childhood Education: UDSM',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(46,'Rev.Josephat Mande','Chaplain',NULL,NULL,'../../../../assets/staff/mande.jpg','MA (Sacred Theology): St. Thomas Aquinas University, Rome; Diploma in Theology: St Paul Major Seminary, Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(47,'Judith Mwanga','Human Resources Management Officer',NULL,NULL,'../../../../assets/staff/mwanga.jpg','MBA (Human Resource): AMUCTA; BSc (Tourism Management): SUA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(48,'Pascalina Joseph','Assistant Accountant',NULL,NULL,'../../../../assets/staff/paskalina.jpeg','MBA: AMUCTA; Advanced Diploma in Accountancy: SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(49,'Isaya Bruno Anyingisye','Clinical Officer',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Clinical Medicine: Bugando; Diploma in Clinical Medicine: Songea MTC',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(50,'Sr Liberatus Mhema','Assistant Accountant',NULL,NULL,'../../../../assets/staff/mhema.jpg','MBA: SAUT; BBA: SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(51,'Gaudensia Mtaki','Cashier',NULL,NULL,'../../../../assets/staff/mtaki.jpeg','Certificate: National Business Examination',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(52,'Amatyus Mugonzibwa','Loan Officer',NULL,NULL,'../../../../assets/staff/mugo.jpg','Bachelor of Sociology: SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(53,'Sr Ester Kapfizi','Admission Officer',NULL,NULL,'../../../../assets/staff/kapfizi.jpg','MEMP: AMUCTA; BA (Education): CUEA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(54,'Martha Lumambo','Administrative Secretary',NULL,NULL,'../../../../assets/staff/lumambo.jpeg','Diploma: Tanzania Public Service College; Certificate: TPS College',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(55,'Alois D Kyando','IT Officer',NULL,NULL,'../../../../assets/staff/alois.jpg','BSc (Computer Science & IS): RUCo; Diploma in Computer Science: RUCo',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(56,'Daudi Mabula','Assistant IT Officer',NULL,NULL,'../../../../assets/staff/default.gif','BSc (Computer Engineering): MUST',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(57,'Sr Victoria Mamiro','Assistant Medical Officer',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Clinical Medicine: Bugando; Diploma: Songea MTC',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(58,'John Lukonge','Technical Assistant',NULL,NULL,'../../../../assets/staff/lukonge.jpg','BED (Special Needs): AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(59,'Emmanuel Sitta','Technical Assistant',NULL,NULL,'../../../../assets/staff/sitta.jpg','BED (Special Needs): AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(60,'Erick Massamaki','Electrician',NULL,NULL,'../../../../assets/staff/masamaki.jpg','Certificate: VETA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(61,'Elizabeth Sospeter','Librarian',NULL,NULL,'../../../../assets/staff/mlona.jpg','BSc Library & Info Management: MU; Diploma & Certificate (Librarian & Records): AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(62,'Naisiriri Memruth','Nurse',NULL,NULL,'../../../../assets/staff/niceriri.jpg','Certificate in Nursing: Regional Government Hospital - Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(63,'Julian Romanus Dalika','Fundraiser',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Community Development: CDTI Tengeru',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(64,'Justina Marwa','Estate Manager',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Journalism: SAUT; MA (Sociology): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(65,'Emmanuel Simon','Assistant Librarian',NULL,NULL,'../../../../assets/staff/imma.jpg','Certificate in Librarianship & Records: AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(66,'Richard Ngeze','Assistant Librarian',NULL,NULL,'../../../../assets/staff/ngeze.jpeg','Diploma in Procurement & Supply Chain: AMUCTA; Certificate (Librarianship & Records): RUCo',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(67,'Walter Silayo','Assistant Librarian',NULL,NULL,'../../../../assets/staff/silayo.jpg','Diploma in Procurement & Supply Chain: AMUCTA; Certificate (Librarianship & Records): Chuo cha Ukutubi na Uhifadhi',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(68,'Mathias Louis','Assistant Estate Manager',NULL,NULL,'../../../../assets/staff/luis.jpg','Bachelor of Law: SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(69,'Paschal Charles','Public Relations Officer',NULL,NULL,'../../../../assets/staff/nimilwa.jpg','BA (Public Relations & Marketing): SAUT',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(70,'Winfrida Ngoloke','Laboratory',NULL,NULL,'../../../../assets/staff/ngoloke.jpg','Diploma (Medical Laboratory): Nkinga Institute; Certificate (Health Laboratory): Bhakita HTI',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(71,'Sr Catherine Masanibwa','Nurse',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Health: PATH',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(72,'Rehema Mlimuka','Nurse',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Nursing: Regional Government Hospital - Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(73,'Moses Kabeya','Data Clerk',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Computer',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(74,'Elia Elia','Assistant Accountant',NULL,NULL,'../../../../assets/staff/default.gif','Diploma in Accounts: Institute of Accountancy Arusha',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(75,'His Eminence Protase Cardinal, RUGAMBWA','Chairman',NULL,NULL,'../../../../assets/staff/default.gif','Archbishop of Tabora',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(76,'Rt Rev. Mapunda, EDWARD','Member',NULL,NULL,'../../../../assets/staff/default.gif','Bishop of Singida',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(77,'Amb. Prof Mahalu, COSTA RICKY','Member',NULL,NULL,'../../../../assets/staff/default.gif','SAUT Vice Chancellor',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(78,'Rev. Fr Kitima, CHARLES','Member',NULL,NULL,'../../../../assets/staff/default.gif','TEC Secretary General',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(79,'Prof. Rugarabamu, PASCHALIS','Member',NULL,NULL,'../../../../assets/staff/default.gif','V.C CUHAS',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(80,'Mr. Sanga, CLEMENT','Member',NULL,NULL,'../../../../assets/staff/default.gif','Representative of MoEST',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(81,'Mr Mtaki, REVOCATUS','Member',NULL,NULL,'../../../../assets/staff/default.gif','Advocate',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(82,'Rev.Prof. Asantemungu, JUVENALIS','Principal AMUCTA',NULL,NULL,'../../../../assets/staff/asantemungu.jpg','Principal AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(83,'Rev. Prof. Emmanuel Wabanhu','Member',NULL,NULL,'../../../../assets/staff/wabanhu.JPG','Deputy Principal for Administration and Finance, AMUCTA',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(84,'AMUCTASO President','Member',NULL,NULL,'../../../../assets/staff/default.gif','Students’ Government Representative',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(85,'Prof. Erasmus, KOMGISHA','Member',NULL,NULL,'../../../../assets/staff/default.gif','SAUT Representative',NULL,1,'2025-09-04 16:15:33',1,NULL,NULL,NULL),(86,'Sr.Dr. Sophia Mbihije','Lecturer',NULL,NULL,'../../../../assets/staff/mbihije.jpg','PhD (Education in Curriculum & Instruction): Loyola University, Chicago (USA); MSc (Analytical Chemistry): Loyola University, Chicago (USA); BSc (Chemistry): Madonna University, Michigan (USA)',NULL,1,'2025-09-04 16:15:37',1,'../../../../assets/files/sophia_mbihije_cv.pdf',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(87,'Rev. Dr. Apolinary K. Ndasi','Lecturer',NULL,NULL,'../../../../assets/staff/kaswala.jpg','PhD (Educational Administration & Planning): Mwenge Catholic University; Master of Educational Administration: CUEA; PGDE: CUEA; Bachelor of Sacred Theology: Pontifical Urbanian University (Italy)',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(88,'Dr. Daniel Dogbe','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Special Education): Ball State University; MPhil (Special Needs - VI): University of Cape Coast; BED (Special Needs): University of Education, Winneba, Ghana',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(89,'Dr Samuel Amoako','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Special Education): UEW (Ghana); MED (Special Educational Needs): University of Leeds; Master (Hearing Impairment): University of Manchester; Diploma (Special Education): University of Cape Coast',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(90,'Dr Ephraim Kalanje','Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','PhD (Philosophy): Åbo Akademi University; MED (Special Educational Needs): Åbo Akademi University; BED (Special Needs): Åbo Akademi University',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(91,'Dr. Eugen Philip','Lecturer',NULL,NULL,'../../../../assets/staff/eugen.jpg','PhD Philosophy: University of Dodoma; MED (Open & Distance Learning): Open University of Tanzania; Bachelor of Education: University of Dar es Salaam',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(92,'Dr. Ildephonce Mkama','Senior Lecturer',NULL,NULL,'../../../../assets/staff/mkama.jpeg','PhD Philosophy: University of the Witwatersrand; MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(93,'Dr. Julius Nyaombo','Lecturer',NULL,NULL,'../../../../assets/staff/nyaombo.jpg','PhD (Natural Resource Assessment & Management): UDSM; MSc (NRA&M): UDSM; BAED (Geography): UDSM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)GRAPHY'),(94,'Dr. Luther I. Kawiche','Lecturer',NULL,NULL,'../../../../assets/staff/kawiche.jpg','PhD (Demography): UDSM; MA (Demography): UDSM; BA (Geography & Environmental Studies): UDSM',NULL,1,'2025-09-04 16:15:37',1,'',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(95,'Dr. Alfred Ong\'ang\'a','Lecturer',NULL,NULL,'../../../../assets/staff/alfred.jpg','PhD (Literature): Kenyatta University; MA (Literature): Kenyatta University; BA (Education): Kenyatta University',NULL,1,'2025-09-04 16:15:37',1,'',NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(96,'Grace Mkosamali','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/mkosamali.jpeg','MA (Counselling Psychology): Bishop Magambo (Uganda); Bachelor (Counselling Psychology): University of Kisubi (Uganda)',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(97,'Koboli Milobo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/koboli.jpg','MED (Management & Planning): Stella Maris University College; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(98,'Renata C. Nyelo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/nyero.jpg','MED (Administration & Planning): CUEA (Kenya); BED (Geography & History): CUEA (Kenya)',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED)'),(99,'Anna Ahmed','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/anna.jpg','Master of Geography & Environmental Management: UDSM; BA (Education): UDSM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / GEOGRAPHY'),(100,'Einhard Mgaya','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (History): SAUT; Bachelor of Education: CUEA (Kenya)',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / HISTORY'),(101,'Clavery Kayugumi','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/kayugumi.jpg','MA (History): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / HISTORY'),(102,'Triphonius Lissu','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/lissu.jpg','MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(103,'Rainer Likongo','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/likongo.jpg','MA (Linguistics): SAUT; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(104,'Gwakisa Kaswaga','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/kaswaga.jpg','MA (Literature): UDSM; Bachelor of Education: Teofili Kisanji University',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF ARTS WITH EDUCATION (BAED) / ENGLISH-LINGUISTICS-LITERATURE'),(105,'Andrew Kifua','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MBA: SAUT; BBA (Accounts): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(106,'Harriet Malichi','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): University of Zambia',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(107,'Fraterinus O. Mutatembwa','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/osward.jpg','MED (Special Education - HI): SEKOMU; BED (Special Needs): SEKOMU',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(108,'Gideon D. Kaziri','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/gideon.jpeg','MED (Special Education - HI): SEKOMU; BED (Special Needs): SEKOMU',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(109,'Elijah Kokse','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/koske.jpg','MED (Special Needs): Brunel University; BED (Special Needs): Kenyatta University',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(110,'Filbert Zomba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/zomba.jpg','MBA: SAUT; Advanced Diploma (Accountancy): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(111,'Placid Komba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/komba.jpg','Master of Business Management: MCoU; BA (Marketing & Entrepreneurship): MCoU',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(112,'Patrick Chekwe','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA GEM: UDSM; BA GEN: UDSM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(113,'Saikon J. Nokoren','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/justin.jpeg','PDLP: The Law School of Tanzania; LLM: RUCU; LLB: Tumaini University',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(114,'Faustine Rwechungura','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','Master of Theology: Radboud University; Bachelor of Theology: CUEA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(115,'Edgar Pastory','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/mushumbushi.jpg','MA (Sociology): SAUT; BA (Sociology): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF BUSINESS ADMINISTRATION (BBA)'),(116,'Upendo Ulaya','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (History): Gotland University; BAED: SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(117,'Shukuru Mukama','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Kiswahili - Fasihi): UDSM; BA (Education): OUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(118,'Denice Salapion','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Kiswahili - Isimu): UDSM; BA (Education): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(119,'Msei Nyagani','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MA (Linguistics): SAUT; BAED: MUM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(120,'Karuta Musa','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Early Childhood): UDOM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(121,'Gaga Nidwa','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(122,'Cronel Diogenes','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(123,'Sr Martha Herman','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/default.gif','BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(124,'Jackson Manase','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(125,'Magreth Nkuba','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(126,'Ansila Nyaki','Assistant Lecturer',NULL,NULL,'../../../../assets/staff/default.gif','MED (Special Education): University of Zambia; BED (Special Needs): Archbishop Mihayo College of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(127,'Nasfath Rugimbana','Tutorial Assistant',NULL,NULL,'../../../../assets/staff/nsfat.jpg','Bachelor of Early Childhood Education: UDSM',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,'BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)'),(128,'Rev.Josephat Mande','Chaplain',NULL,NULL,'../../../../assets/staff/mande.jpg','MA (Sacred Theology): St. Thomas Aquinas University, Rome; Diploma in Theology: St Paul Major Seminary, Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(129,'Judith Mwanga','Human Resources Management Officer',NULL,NULL,'../../../../assets/staff/mwanga.jpg','MBA (Human Resource): AMUCTA; BSc (Tourism Management): SUA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(130,'Pascalina Joseph','Assistant Accountant',NULL,NULL,'../../../../assets/staff/paskalina.jpeg','MBA: AMUCTA; Advanced Diploma in Accountancy: SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(131,'Isaya Bruno Anyingisye','Clinical Officer',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Clinical Medicine: Bugando; Diploma in Clinical Medicine: Songea MTC',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(132,'Sr Liberatus Mhema','Assistant Accountant',NULL,NULL,'../../../../assets/staff/mhema.jpg','MBA: SAUT; BBA: SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(133,'Gaudensia Mtaki','Cashier',NULL,NULL,'../../../../assets/staff/mtaki.jpeg','Certificate: National Business Examination',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(134,'Amatyus Mugonzibwa','Loan Officer',NULL,NULL,'../../../../assets/staff/mugo.jpg','Bachelor of Sociology: SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(135,'Sr Ester Kapfizi','Admission Officer',NULL,NULL,'../../../../assets/staff/kapfizi.jpg','MEMP: AMUCTA; BA (Education): CUEA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(136,'Martha Lumambo','Administrative Secretary',NULL,NULL,'../../../../assets/staff/lumambo.jpeg','Diploma: Tanzania Public Service College; Certificate: TPS College',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(137,'Alois D Kyando','IT Officer',NULL,NULL,'../../../../assets/staff/alois.jpg','BSc (Computer Science & IS): RUCo; Diploma in Computer Science: RUCo',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(138,'Daudi Mabula','Assistant IT Officer',NULL,NULL,'../../../../assets/staff/default.gif','BSc (Computer Engineering): MUST',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(139,'Sr Victoria Mamiro','Assistant Medical Officer',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Clinical Medicine: Bugando; Diploma: Songea MTC',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(140,'John Lukonge','Technical Assistant',NULL,NULL,'../../../../assets/staff/lukonge.jpg','BED (Special Needs): AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(141,'Emmanuel Sitta','Technical Assistant',NULL,NULL,'../../../../assets/staff/sitta.jpg','BED (Special Needs): AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(142,'Erick Massamaki','Electrician',NULL,NULL,'../../../../assets/staff/masamaki.jpg','Certificate: VETA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(143,'Elizabeth Sospeter','Librarian',NULL,NULL,'../../../../assets/staff/mlona.jpg','BSc Library & Info Management: MU; Diploma & Certificate (Librarian & Records): AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(144,'Naisiriri Memruth','Nurse',NULL,NULL,'../../../../assets/staff/niceriri.jpg','Certificate in Nursing: Regional Government Hospital - Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(145,'Julian Romanus Dalika','Fundraiser',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Community Development: CDTI Tengeru',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(146,'Justina Marwa','Estate Manager',NULL,NULL,'../../../../assets/staff/default.gif','Advanced Diploma in Journalism: SAUT; MA (Sociology): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(147,'Emmanuel Simon','Assistant Librarian',NULL,NULL,'../../../../assets/staff/imma.jpg','Certificate in Librarianship & Records: AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(148,'Richard Ngeze','Assistant Librarian',NULL,NULL,'../../../../assets/staff/ngeze.jpeg','Diploma in Procurement & Supply Chain: AMUCTA; Certificate (Librarianship & Records): RUCo',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(149,'Walter Silayo','Assistant Librarian',NULL,NULL,'../../../../assets/staff/silayo.jpg','Diploma in Procurement & Supply Chain: AMUCTA; Certificate (Librarianship & Records): Chuo cha Ukutubi na Uhifadhi',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(150,'Mathias Louis','Assistant Estate Manager',NULL,NULL,'../../../../assets/staff/luis.jpg','Bachelor of Law: SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(151,'Paschal Charles','Public Relations Officer',NULL,NULL,'../../../../assets/staff/nimilwa.jpg','BA (Public Relations & Marketing): SAUT',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(152,'Winfrida Ngoloke','Laboratory',NULL,NULL,'../../../../assets/staff/ngoloke.jpg','Diploma (Medical Laboratory): Nkinga Institute; Certificate (Health Laboratory): Bhakita HTI',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(153,'Sr Catherine Masanibwa','Nurse',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Health: PATH',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(154,'Rehema Mlimuka','Nurse',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Nursing: Regional Government Hospital - Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(155,'Moses Kabeya','Data Clerk',NULL,NULL,'../../../../assets/staff/default.gif','Certificate in Computer',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(156,'Elia Elia','Assistant Accountant',NULL,NULL,'../../../../assets/staff/default.gif','Diploma in Accounts: Institute of Accountancy Arusha',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(157,'His Eminence Protase Cardinal, RUGAMBWA','Chairman',NULL,NULL,'../../../../assets/staff/default.gif','Archbishop of Tabora',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(158,'Rt Rev. Mapunda, EDWARD','Member',NULL,NULL,'../../../../assets/staff/default.gif','Bishop of Singida',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(159,'Amb. Prof Mahalu, COSTA RICKY','Member',NULL,NULL,'../../../../assets/staff/default.gif','SAUT Vice Chancellor',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(160,'Rev. Fr Kitima, CHARLES','Member',NULL,NULL,'../../../../assets/staff/default.gif','TEC Secretary General',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(161,'Prof. Rugarabamu, PASCHALIS','Member',NULL,NULL,'../../../../assets/staff/default.gif','V.C CUHAS',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(162,'Mr. Sanga, CLEMENT','Member',NULL,NULL,'../../../../assets/staff/default.gif','Representative of MoEST',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(163,'Mr Mtaki, REVOCATUS','Member',NULL,NULL,'../../../../assets/staff/default.gif','Advocate',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(164,'Rev.Prof. Asantemungu, JUVENALIS','Principal AMUCTA',NULL,NULL,'../../../../assets/staff/asantemungu.jpg','Principal AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(165,'Rev. Prof. Emmanuel Wabanhu','Member',NULL,NULL,'../../../../assets/staff/wabanhu.JPG','Deputy Principal for Administration and Finance, AMUCTA',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(166,'AMUCTASO President','Member',NULL,NULL,'../../../../assets/staff/default.gif','Students’ Government Representative',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL),(167,'Prof. Erasmus, KOMGISHA','Member',NULL,NULL,'../../../../assets/staff/default.gif','SAUT Representative',NULL,1,'2025-09-04 16:15:37',1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_role`
--

DROP TABLE IF EXISTS `employee_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `role_title` varchar(255) NOT NULL,
  `role_group` enum('academic','administrative','hod','board','committee') NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `employee_role_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`),
  CONSTRAINT `employee_role_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_role`
--

LOCK TABLES `employee_role` WRITE;
/*!40000 ALTER TABLE `employee_role` DISABLE KEYS */;
INSERT INTO `employee_role` VALUES (1,15,'Quality Assurance','hod','Quality Assurance',NULL,NULL,NULL,1,'2025-09-04 16:16:52'),(2,10,'Dean of Faculty of Education and Social Sciences','hod','Faculty of Education and Social Sciences',NULL,NULL,NULL,1,'2025-09-04 16:16:52'),(3,5,'Director of Postgraduate Studies, Research and Consultancy','hod','Postgraduate, Research & Consultancy',NULL,NULL,NULL,1,'2025-09-04 16:16:53'),(4,11,'Examination Officer','hod','Examinations Office',NULL,NULL,NULL,1,'2025-09-04 16:16:53'),(5,14,'Counseling','hod','Counseling',NULL,NULL,NULL,1,'2025-09-04 16:16:53'),(6,12,'Head of Department of Education Foundation','hod','Education Foundation',NULL,NULL,NULL,1,'2025-09-04 16:16:53'),(7,20,'Head of Unit of Business Administration and Social Sciences','hod','Business Administration and Social Sciences',NULL,NULL,NULL,1,'2025-09-04 16:16:53'),(8,22,'Coordinator of Teaching Practice','hod','Teaching Practice',NULL,NULL,NULL,1,'2025-09-04 16:16:54'),(9,9,'Chief Librarian','hod','Library',NULL,NULL,NULL,1,'2025-09-04 16:16:54'),(10,25,'Head of Department of Special Needs Education and Coordinator of Center of Inclusive Education','hod','Special Needs Education / Center of Inclusive Education',NULL,NULL,NULL,1,'2025-09-04 16:16:54'),(11,19,'Dean of Students','hod','Students Affairs',NULL,NULL,NULL,1,'2025-09-04 16:16:54'),(12,46,'Chaplain','hod','Chaplaincy',NULL,NULL,NULL,1,'2025-09-04 16:16:54'),(13,47,'Human Resource Management Officer','hod','Human Resources',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(14,52,'Loan Officer','hod','Loans',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(15,53,'Admission Officer','hod','Admissions',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(16,55,'IT Officer','hod','ICT',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(17,49,'Dispensary In charge','hod','Health Services',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(18,69,'Public Relation Officer','hod','Public Relations',NULL,NULL,NULL,1,'2025-09-04 16:16:55'),(19,31,'Legal Officer','hod','Legal Office',NULL,NULL,NULL,1,'2025-09-04 16:16:56'),(20,75,'Chairman','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:30'),(21,76,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:30'),(22,77,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(23,78,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(24,79,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(25,48,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(26,80,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(27,81,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:31'),(28,82,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:32'),(29,83,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:32'),(30,84,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:32'),(31,84,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:39'),(32,75,'Chairman','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:41'),(33,76,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:41'),(34,77,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(35,78,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(36,79,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(37,48,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(38,80,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(39,81,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:42'),(40,82,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:43'),(41,83,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:43'),(42,84,'Member','board','Governing Board',NULL,NULL,NULL,1,'2025-09-04 16:18:43'),(43,82,'Chairman','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:13'),(44,12,'Dean of Faculty of Arts and Social Sciences','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:13'),(45,85,'SAUT Representative','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:13'),(46,9,'Examination Officer','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:13'),(47,5,'HoD Special Needs, Languages and Linguistics','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14'),(48,20,'Quality Assurance Officer','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14'),(49,33,'Dean of Students','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14'),(50,18,'Representative of Academic Staff','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14'),(51,53,'Admission Officer','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14'),(52,84,'Students\' Government','committee','Academic Committee',NULL,NULL,NULL,1,'2025-09-04 16:19:14');
/*!40000 ALTER TABLE `employee_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `errors`
--

DROP TABLE IF EXISTS `errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) DEFAULT 'unresolved',
  `cause_url` varchar(255) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4400 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `errors`
--

LOCK TABLES `errors` WRITE;
/*!40000 ALTER TABLE `errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `feature_image` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Event 1','SeSion','2025-09-18','2025-09-20','Amucta','/images/events/Event_1_1756843591.webp','/files/events/Event_1_1756843591.pdf','category1',72,'2025-09-02 20:06:31'),(2,'Event 2','hello','2025-09-05','2025-10-03','Amucta','/images/events/Event_2_1756843628.webp','','catgeory',72,'2025-09-02 20:07:08'),(3,'Applicants with Multiple Application',NULL,'2025-09-03',NULL,NULL,NULL,'/assets/files/multiple_selection_round_1.pdf','new',1,'2025-09-04 15:44:07'),(4,'Applicants with Single Application',NULL,'2025-09-03',NULL,NULL,NULL,'/assets/files/single_selection_2025.pdf','new',1,'2025-09-04 15:44:07'),(5,'Confirm Application here',NULL,'2025-09-03',NULL,NULL,NULL,'https://oas.amucta.ac.tz','new',1,'2025-09-04 15:44:07'),(6,'UNIVERSITY EXAMINATION',NULL,'2025-07-17',NULL,NULL,NULL,'#','general',1,'2025-09-04 15:44:07');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (2,'Faculty of Education and Social Science','&amp;lt;div class=&amp;quot;mt-2 text-justify&amp;quot;&amp;gt;\r\n&amp;lt;div class=&amp;quot;font-thin italic text-amucta-blue tension-question&amp;quot;&amp;gt;Why studying teacher education?&amp;lt;/div&amp;gt;\r\n&amp;lt;p class=&amp;quot;mt-4&amp;quot;&amp;gt;Education is a broad discipline that includes learning how to teach others and understanding how people learn during different stages of their lives. The focus of what you study will depend on your interests: Is early childhood education a passion, or would you like to teach older learners? Are you interested in lifelong learning, education policy or curriculum design? Whatever your choice, you have a chance to impact the lives of others.&amp;lt;/p&amp;gt;\r\n&amp;lt;p class=&amp;quot;mt-4&amp;quot;&amp;gt;We offer high-quality teaching and thoughtfully developed undergraduate and postgraduate programmes. Further to this, we offer teacher development in diverse subject areas for pre- and in-service teachers. Our research interests are varied, ranging from Business Studies, Literacy and Multilingual Education to Early Childhood Development. Furthermore, the Faculty is committed to promoting our understanding of all aspects of society to improve democracy and the quality of life for a more inclusive society. In addition to its focus on research and teaching, FESS is deeply committed to playing a pivotal role in shaping public discourse and contributing to impactful public policies.&amp;lt;/p&amp;gt;\r\n&amp;lt;/div&amp;gt;',1,'2025-09-02 11:32:07',72);
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `category` enum('gallery','slides','news','logo','icon','profile') DEFAULT NULL,
  `url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,'slide1','slides','images/gallery/slides_68b7600d7a24a.webp','2025-09-02 21:22:21',72);
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level`
--

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;
INSERT INTO `level` VALUES (1,'Undergraduate','certificate, diploma and bachelor','2025-09-02 20:00:59',72),(2,'Postgraduate','Masters to Higher','2025-09-02 20:01:20',72),(3,'Non-degree','','2025-09-04 15:32:22',72);
/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feature_image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expire` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'/images/news/news_1_1756843462.webp','news 1','hello word','2025-09-02 20:04:22','2025-09-11 21:00:00','category1','/attachments/news/news_1_1756843462.pdf',72),(2,'/assets/images/social-ads/credit-combination.jpeg','APPLICATIONS FOR ADMISSION TO DEGREE PROGRAMMES FOR THE 2025/2026 ACADEMIC YEAR ARE NOW OPEN','Deadline for the second round of applications: September 10, 2025...','2025-09-04 15:38:06','2025-09-20 21:00:00','admission','https://oas.amucta.ac.tz',1),(3,'/assets/images/social-ads/memsie.jpeg','APPLICANTS ARE INVITED FOR ADMISSION TO A NEWLY ACCREDITED PROGRAMME','Master of Educational Management in Special and Inclusive Education...','2025-09-04 15:38:06','2025-05-28 21:00:00','admission','/academics?page=memsie',1),(4,'/assets/images/social-ads/bagenarm.jpeg','APPLICANTS ARE INVITED FOR ADMISSION TO A NEWLY ACCREDITED PROGRAMME','Bachelor of Arts in Geography, Environment and Natural Resources Management...','2025-09-04 15:38:06','2025-05-28 21:00:00','admission','/academics?page=bagen',1),(5,'/assets/images/social-ads/dip-sign.jpeg','APPLICANTS ARE INVITED FOR ADMISSION TO A NEWLY ACCREDITED PROGRAMME','Diploma in Sign Language Interpretation...','2025-09-04 15:38:06','2025-05-28 21:00:00','admission','/academics?page=dsli',1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program`
--

DROP TABLE IF EXISTS `program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `short_name` varchar(25) DEFAULT NULL,
  `intakes` int(11) DEFAULT NULL,
  `duration` varchar(25) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `capacity` double DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `accreditation_year` varchar(8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `program_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`),
  CONSTRAINT `program_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program`
--

LOCK TABLES `program` WRITE;
/*!40000 ALTER TABLE `program` DISABLE KEYS */;
INSERT INTO `program` VALUES (1,'Bachelor of Arts in Geography, Environment and Natural Resources Management','BAGENARM',1,'3','he','je',1,120,2,2,'2023','2025-09-02 21:59:13',0);
/*!40000 ALTER TABLE `program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_logins`
--

DROP TABLE IF EXISTS `user_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_logins`
--

LOCK TABLES `user_logins` WRITE;
/*!40000 ALTER TABLE `user_logins` DISABLE KEYS */;
INSERT INTO `user_logins` VALUES (172,72,'2025-09-02 09:23:18','::1'),(173,72,'2025-09-02 19:53:53','::1'),(174,72,'2025-09-04 15:32:14','::1');
/*!40000 ALTER TABLE `user_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_url` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` enum('manager','admin','user') NOT NULL DEFAULT 'user',
  `active` tinyint(1) DEFAULT 1,
  `verified` tinyint(1) DEFAULT 0,
  `burned` tinyint(1) DEFAULT 0,
  `password` varchar(256) NOT NULL,
  `recovery_question` varchar(255) NOT NULL,
  `recovery_answer_hash` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (72,'DAUDI MABULA JUMA','mabula','daudmabula8@gmail.com','/images/daudi_mabula_juma_1756796724.webp','0629077526','manager',1,0,0,'$2y$10$uYlWalZp497lLh1q51OxzeUIVbTVgaALCDuUOuyGzRm0d7JPlP6nW','jkrtr','jkj','2025-09-02 07:05:24','2025-09-02 07:05:24');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_tokens`
--

DROP TABLE IF EXISTS `verification_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verification_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expire_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_tokens`
--

LOCK TABLES `verification_tokens` WRITE;
/*!40000 ALTER TABLE `verification_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `verification_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `ip_type` enum('IPv4','IPv6') NOT NULL,
  `continent` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `isp` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_registered` tinyint(1) DEFAULT 0,
  `visitor_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23027 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-07 16:55:38
