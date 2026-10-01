/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: GiantDisc
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-5ubuntu0.1 from Ubuntu

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `album` (
  `artist` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `composer` varchar(255) DEFAULT NULL,
  `cddbid` varchar(20) NOT NULL DEFAULT '',
  `coverimg` varchar(255) DEFAULT NULL,
  `covertxt` mediumtext DEFAULT NULL,
  `modified` date DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`cddbid`),
  KEY `artist` (`artist`(10)),
  KEY `title` (`title`(10)),
  KEY `genre` (`genre`),
  KEY `modified` (`modified`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `album_fav`
--

DROP TABLE IF EXISTS `album_fav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `album_fav` (
  `cddbid` varchar(20) NOT NULL DEFAULT '',
  `evaluation` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_fav`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `album_fav` WRITE;
/*!40000 ALTER TABLE `album_fav` DISABLE KEYS */;
/*!40000 ALTER TABLE `album_fav` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `fav_temp`
--

DROP TABLE IF EXISTS `fav_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fav_temp` (
  `artist` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `composer` varchar(255) DEFAULT NULL,
  `cddbid` varchar(20) NOT NULL,
  `coverimg` varchar(255) DEFAULT NULL,
  `covertxt` mediumtext DEFAULT NULL,
  `modified` date DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fav_temp`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `fav_temp` WRITE;
/*!40000 ALTER TABLE `fav_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `fav_temp` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `genre`
--

DROP TABLE IF EXISTS `genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `genre` (
  `id` varchar(10) NOT NULL DEFAULT '',
  `id3genre` smallint(6) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `freq` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genre`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `genre` WRITE;
/*!40000 ALTER TABLE `genre` DISABLE KEYS */;
INSERT INTO `genre` VALUES
('b',NULL,'Alternative',NULL),
('ba',NULL,'Alternative General',NULL),
('bb',NULL,'Art Rock',NULL),
('bc',NULL,'Avant Rock',NULL),
('be',NULL,'Experimental',0),
('bh',NULL,'Grunge',NULL),
('bi',NULL,'Indie',NULL),
('bm',NULL,'Unclassifiable',NULL),
('bn',NULL,'Crossover',NULL),
('c',NULL,'Books & Spoken',0),
('ca',NULL,'Short Stories',0),
('cb',57,'Comedy',0),
('cc',77,'Musicals/Broadway',0),
('cd',NULL,'Poetry',0),
('ce',NULL,'Cabaret / Satire',0),
('cf',NULL,'Religion',0),
('cg',101,'Spoken Word',0),
('ch',NULL,'Stories/Fairytales',0),
('ci',NULL,'Radio Play',0),
('cia',NULL,'Literary Radio Play',0),
('cib',NULL,'Thriller',0),
('e',32,'Classical',77),
('ea',104,'Chamber Music',0),
('eaa',105,'Sonata',0),
('eb',NULL,'Classical General',0),
('ec',NULL,'Contemporary',0),
('eca',NULL,'Contemp. Crossover',0),
('ecb',NULL,'Electronic Classical',0),
('ecc',NULL,'Experimental Classical',0),
('ecd',NULL,'Minimal Music',0),
('ed',NULL,'Film Music',0),
('ee',33,'Instrumental',19),
('ef',NULL,'Period Music',0),
('efa',NULL,'Baroque',0),
('efb',NULL,'Medieval',0),
('efc',NULL,'Renaissance',0),
('efd',NULL,'Romantic',0),
('efda',NULL,'19th Century',0),
('eg',NULL,'Solo Instruments',0),
('ega',NULL,'Guitar',0),
('egb',NULL,'Percussion',0),
('egc',NULL,'Piano',0),
('eh',106,'Symphonic',0),
('ei',28,'Classic Vocal',35),
('eia',97,'Choral',0),
('eib',NULL,'Ensembles',0),
('eic',103,'Opera',0),
('ej',NULL,'Baroque',0),
('f',2,'Country',0),
('fa',NULL,'Alternative Country',0),
('fb',89,'Bluegrass',0),
('fd',NULL,'Country Blues',0),
('fe',NULL,'Country General',0),
('fg',NULL,'Country and Western',0),
('fh',80,'Folk',0),
('fha',NULL,'Irish Folk',0),
('fi',NULL,'Rockabilly',0),
('g',98,'Easy Listening',0),
('gb',NULL,'Lounge',0),
('gc',NULL,'Love Songs',0),
('gca',116,'Ballads',0),
('gd',NULL,'Mood Music',0),
('ge',10,'New Age',0),
('gf',NULL,'Soft Rock',0),
('gfa',NULL,'Acoustic Rock',0),
('gg',NULL,'Schlager',0),
('gh',NULL,'Soft Pop',0),
('gi',45,'Meditative',0),
('gj',NULL,'Celtic',0),
('h',102,'Songs/Chansons',0),
('ha',NULL,'Singer-Songwriter',0),
('hb',65,'Children\'s Music',0),
('i',52,'Electronic',0),
('ia',34,'Acid',0),
('ib',26,'Ambient',0),
('ic',NULL,'Breakbeat/Breaks',0),
('ica',NULL,'Breakbeat',0),
('icb',NULL,'Darkside',0),
('icc',63,'Jungle',0),
('icd',NULL,'Ragga',0),
('ice',27,'Trip Hop',0),
('id',3,'Dance',0),
('ie',NULL,'Drum n\' Bass',0),
('if',NULL,'Electronica',0),
('ig',NULL,'Envir. Soundscapes',0),
('ih',NULL,'Experimental Elect.',0),
('iha',NULL,'Minimal Experimental',0),
('ihb',39,'Noise',0),
('ii',37,'Game Soundtracks',0),
('ij',35,'House',0),
('ija',NULL,'Acid House',0),
('ijb',NULL,'Funk House',0),
('ijc',NULL,'Hard House',0),
('ijd',NULL,'Progressive House',0),
('ik',19,'Industrial',0),
('il',18,'Techno',0),
('ilb',NULL,'Dub',0),
('ild',NULL,'Goa',0),
('ile',NULL,'Hardcore Techno',0),
('ilf',NULL,'Illbient',0),
('ilg',NULL,'Minimal',0),
('ilh',25,'Old Skool Techno',0),
('ili',68,'Rave',0),
('ilj',31,'Trance',0),
('im',44,'Space Music',0),
('j',NULL,'Hip Hop/Rap',0),
('ja',7,'Hip Hop',0),
('jb',15,'Rap',0),
('jbb',61,'Christian Rap',0),
('jbd',NULL,'Hardcore Rap',0),
('jbf',59,'Gangsta',0),
('k',NULL,'Blues/R&B',0),
('ka',0,'Blues',4),
('kaa',NULL,'Acoustic Blues',0),
('kab',NULL,'Blues Rock',0),
('kac',NULL,'Blues Vocalist',0),
('kae',NULL,'Electric Blues',0),
('kag',NULL,'Jazz Blues',0),
('kb',38,'Gospel',0),
('kc',NULL,'Improvised',0),
('kd',14,'R&B',0),
('ke',42,'Soul',0),
('kea',NULL,'Sweet Soul',0),
('l',8,'Jazz',0),
('la',73,'Acid Jazz',0),
('lb',85,'Bebop',0),
('lc',NULL,'Dancefloor Jazz',0),
('lf',30,'Jazz Fusion',0),
('lh',NULL,'Jazz Vocals',0),
('lj',NULL,'Ragtime',0),
('lk',NULL,'Smooth Jazz',0),
('ll',83,'Swing',0),
('lla',96,'Big Band',0),
('llb',76,'Retro-Swing',0),
('lm',NULL,'Cool Jazz',0),
('ln',NULL,'Ethno Jazz',0),
('lna',NULL,'African Ethno Jazz',0),
('lnb',NULL,'Arab Ethno Jazz',0),
('lnc',NULL,'Cuban Jazz',0),
('lnd',NULL,'Latin Jazz',0),
('lne',NULL,'Far East Jazz',0),
('lo',NULL,'Modern Jazz',0),
('lp',NULL,'New Orleans Brass',0),
('m',NULL,'Pop & Rock',0),
('ma',NULL,'Country Rock',0),
('mb',5,'Funk',0),
('mba',NULL,'Acid Funk',0),
('mc',9,'Metal',0),
('mcc',22,'Death Metal',0),
('mcd',NULL,'Doom Metal',0),
('mcf',NULL,'Hard Core Metal',0),
('mcg',NULL,'Heavy Metal',0),
('mck',NULL,'Thrash/Speed Metal',0),
('md',13,'Pop',0),
('mda',99,'Acoustic',0),
('mdb',NULL,'Synthesizer Pop',0),
('mdd',NULL,'Latin Pop',0),
('mdg',123,'A capella/Pop Vocals',0),
('mdh',NULL,'Neue Deutsche Welle',0),
('mdi',NULL,'Disco',0),
('mdj',NULL,'Dance Pop',0),
('mdja',NULL,'Twist',0),
('mdk',NULL,'Doo-Wop',0),
('me',43,'Punk',0),
('mea',121,'Hardcore/Punk Rock',0),
('meb',71,'Lo-Fi/Garage',0),
('mec',NULL,'Old School Punk',0),
('med',21,'Ska',0),
('mf',17,'Rock',0),
('mfb',NULL,'Acid Rock',0),
('mfe',81,'Folk Rock',0),
('mfg',NULL,'Groove Rock',0),
('mfh',NULL,'Guitar Rock',0),
('mfha',1,'Classic Rock',0),
('mfhb',NULL,'Improv Rock',0),
('mfhc',47,'Instrumental Rock',0),
('mfhf',NULL,'Surf Rock',0),
('mfj',66,'New Wave',0),
('mfk',67,'Psychedelic',0),
('mfl',78,'Rock & Roll',0),
('mfm',94,'Symphonic Rock',0),
('mg',NULL,'Rock En Espanol',0),
('n',NULL,'World',0),
('na',16,'Reggae',0),
('nb',NULL,'Steel Drums',0),
('nc',NULL,'World Popular',0),
('nca',NULL,'African Pop',0),
('ncb',NULL,'Oriental Pop',0),
('ncc',NULL,'Scandinavian Ethnopop',0),
('ncd',NULL,'Asian Pop',0),
('nce',NULL,'Arabian Pop',0),
('ncf',NULL,'European Ethnopop',0),
('ncg',NULL,'Latin Pop',0),
('nch',NULL,'Caribbean Pop',0),
('nd',82,'World Traditions',0),
('nda',NULL,'African',0),
('ndaa',NULL,'Mali Blues',0),
('ndb',NULL,'Arabic',0),
('ndc',NULL,'Asian',0),
('ndd',NULL,'Bossa Nova',0),
('nde',NULL,'Caribbean',0),
('ndf',88,'Celtic',0),
('ndg',53,'European Folk/Pop',0),
('ndga',NULL,'Jodel',0),
('ndh',NULL,'France',0),
('ndi',NULL,'Germany',0),
('ndj',NULL,'India',0),
('ndk',NULL,'Ireland',0),
('ndl',NULL,'Latin',NULL),
('ndlb',NULL,'Flamenco',NULL),
('ndlc',NULL,'Mambo',NULL),
('ndld',NULL,'Mariachi',NULL),
('ndle',NULL,'Meringue',NULL),
('ndlg',NULL,'Salsa',NULL),
('ndlh',NULL,'Samba',NULL),
('ndm',NULL,'Native American',NULL),
('ndn',NULL,'Quebecois',NULL),
('ndo',NULL,'Russian',NULL),
('ndp',NULL,'South/Cent. American',NULL),
('ndq',NULL,'Spain',NULL),
('ndr',NULL,'Tango',NULL),
('t',NULL,'Tanzsport',NULL),
('ta',NULL,'Langsamer Walzer',NULL),
('tb',NULL,'Wiener Walzer',NULL),
('tc',NULL,'Tango',NULL),
('td',NULL,'Foxtrott',NULL),
('te',NULL,'Quickstep',NULL),
('tf',NULL,'Slow Fox',NULL),
('tg',NULL,'Samba',NULL),
('th',NULL,'Rumba',NULL),
('ti',NULL,'ChaChaCha',NULL),
('tj',NULL,'Paso Doble',NULL),
('tk',NULL,'Jive',NULL),
('tl',NULL,'Disco Fox',NULL),
('tm',NULL,'Square Dance',NULL),
('tn',NULL,'Veleta',NULL),
('to',NULL,'Salsa',NULL),
('tp',NULL,'Mambo',NULL),
('tq',NULL,'West-Coast-Swing',NULL),
('x',NULL,'X-MAS',NULL),
('y',NULL,'Gesangs&uuml;bungen',NULL);
/*!40000 ALTER TABLE `genre` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `language` (
  `id` varchar(4) NOT NULL DEFAULT '',
  `language` varchar(40) DEFAULT NULL,
  `freq` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` VALUES
('-','Instrumental',NULL),
('CHde','Swiss German',NULL),
('af','Afrikaans',NULL),
('ar','Arabic',NULL),
('bg','Bulgarian',NULL),
('bn','Bengali; Bangla',NULL),
('bo','Tibetan',NULL),
('cs','Czech',NULL),
('da','Danish',NULL),
('de','German',NULL),
('el','Greek',NULL),
('en','English',NULL),
('eo','Esperanto',NULL),
('es','Spanish',NULL),
('fi','Finnish',NULL),
('fr','French',NULL),
('hi','Hindi',NULL),
('hu','Hungarian',NULL),
('is','Icelandic',NULL),
('it','Italian',NULL),
('iw','Hebrew',NULL),
('ja','Japanese',NULL),
('ku','Kurdish',NULL),
('la','Latin',NULL),
('lt','Lithuanian',NULL),
('lv','Latvian, Lettish',NULL),
('nl','Dutch',NULL),
('no','Norwegian',NULL),
('pl','Polish',NULL),
('pt','Portuguese',NULL),
('rm','Rhaeto-Romance',NULL),
('ro','Romanian',NULL),
('ru','Russian',NULL),
('sh','Serbo-Croatian',NULL),
('sk','Slovak',NULL),
('sl','Slovenian',NULL),
('sq','Albanian',NULL),
('sr','Serbian',NULL),
('sv','Swedish',NULL),
('ta','Tamil',NULL),
('th','Thai',NULL),
('tr','Turkish',NULL),
('vi','Vietnamese',NULL),
('zh','Chinese',NULL),
('zu','African',NULL);
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `musictype`
--

DROP TABLE IF EXISTS `musictype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `musictype` (
  `musictype` varchar(40) DEFAULT NULL,
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `musictype`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `musictype` WRITE;
/*!40000 ALTER TABLE `musictype` DISABLE KEYS */;
INSERT INTO `musictype` VALUES
('soft/slow',1),
('medium',2),
('groovy',3),
('hard',4);
/*!40000 ALTER TABLE `musictype` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `parameter`
--

DROP TABLE IF EXISTS `parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parameter` (
  `playerid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`playerid`,`name`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `parameter` WRITE;
/*!40000 ALTER TABLE `parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `parameter` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `player` (
  `ipaddr` varchar(255) NOT NULL DEFAULT '',
  `uichannel` varchar(255) NOT NULL DEFAULT '',
  `resumetracknb` int(11) DEFAULT NULL,
  `resumeplaytime` int(11) DEFAULT NULL,
  `resumeshufflepar` varchar(255) DEFAULT NULL,
  `resumeshufflestat` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `playerstate`
--

DROP TABLE IF EXISTS `playerstate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `playerstate` (
  `playerid` int(11) NOT NULL DEFAULT 0,
  `audiochannel` int(11) NOT NULL DEFAULT 0,
  `processid` int(11) DEFAULT NULL,
  `playertype` int(11) DEFAULT NULL,
  `snddevice` varchar(255) DEFAULT NULL,
  `currtracknb` int(11) DEFAULT NULL,
  `state` varchar(4) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `shufflepar` varchar(255) DEFAULT NULL,
  `shufflestat` varchar(255) DEFAULT NULL,
  `framesplayed` int(11) DEFAULT NULL,
  `framestotal` int(11) DEFAULT NULL,
  PRIMARY KEY (`playerid`,`audiochannel`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playerstate`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `playerstate` WRITE;
/*!40000 ALTER TABLE `playerstate` DISABLE KEYS */;
/*!40000 ALTER TABLE `playerstate` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `playlist`
--

DROP TABLE IF EXISTS `playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `playlist` (
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlist`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `playlist` WRITE;
/*!40000 ALTER TABLE `playlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `playlist` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `playlistitem`
--

DROP TABLE IF EXISTS `playlistitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `playlistitem` (
  `playlist` int(11) NOT NULL DEFAULT 0,
  `tracknumber` mediumint(9) NOT NULL DEFAULT 0,
  `trackid` int(11) DEFAULT NULL,
  PRIMARY KEY (`playlist`,`tracknumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlistitem`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `playlistitem` WRITE;
/*!40000 ALTER TABLE `playlistitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `playlistitem` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `playlog`
--

DROP TABLE IF EXISTS `playlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `playlog` (
  `trackid` int(11) DEFAULT NULL,
  `played` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `trackid` (`trackid`),
  KEY `played` (`played`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlog`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `playlog` WRITE;
/*!40000 ALTER TABLE `playlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `playlog` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `recordingitem`
--

DROP TABLE IF EXISTS `recordingitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recordingitem` (
  `trackid` int(11) DEFAULT NULL,
  `recdate` date DEFAULT NULL,
  `rectime` time DEFAULT NULL,
  `reclength` int(11) DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `endtime` time DEFAULT NULL,
  `repeating` varchar(10) DEFAULT NULL,
  `initcmd` varchar(255) DEFAULT NULL,
  `parameters` varchar(255) DEFAULT NULL,
  `atqjob` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recordingitem`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recordingitem` WRITE;
/*!40000 ALTER TABLE `recordingitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `recordingitem` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `source`
--

DROP TABLE IF EXISTS `source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `source` (
  `source` varchar(40) DEFAULT NULL,
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `source` WRITE;
/*!40000 ALTER TABLE `source` DISABLE KEYS */;
INSERT INTO `source` VALUES
('cd',1),
('radio',2),
('vinyl',3),
('tape',4),
('tv',5),
('video',6);
/*!40000 ALTER TABLE `source` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tracklistitem`
--

DROP TABLE IF EXISTS `tracklistitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracklistitem` (
  `playerid` int(11) NOT NULL DEFAULT 0,
  `listtype` smallint(6) NOT NULL DEFAULT 0,
  `tracknb` int(11) NOT NULL DEFAULT 0,
  `trackid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`playerid`,`listtype`,`tracknb`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracklistitem`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tracklistitem` WRITE;
/*!40000 ALTER TABLE `tracklistitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracklistitem` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracks` (
  `artist` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `composer` varchar(255) DEFAULT NULL,
  `genre1` varchar(10) DEFAULT NULL,
  `genre2` varchar(10) DEFAULT NULL,
  `year` smallint(5) unsigned DEFAULT NULL,
  `lang` varchar(4) DEFAULT NULL,
  `type` tinyint(3) unsigned DEFAULT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `length` smallint(5) unsigned DEFAULT NULL,
  `source` tinyint(3) unsigned DEFAULT NULL,
  `sourceid` varchar(20) DEFAULT NULL,
  `tracknb` tinyint(3) unsigned DEFAULT NULL,
  `mp3file` varchar(255) DEFAULT NULL,
  `quality` tinyint(3) DEFAULT NULL,
  `voladjust` smallint(6) DEFAULT 0,
  `lengthfrm` mediumint(9) DEFAULT 0,
  `startfrm` mediumint(9) DEFAULT 0,
  `bpm` smallint(6) DEFAULT 0,
  `lyrics` mediumtext DEFAULT NULL,
  `moreinfo` mediumtext DEFAULT NULL,
  `bitrate` varchar(10) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `modified` date DEFAULT NULL,
  `backup` tinyint(3) unsigned DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `title` (`title`(10)),
  KEY `mp3file` (`mp3file`(10)),
  KEY `genre1` (`genre1`),
  KEY `genre2` (`genre2`),
  KEY `year` (`year`),
  KEY `lang` (`lang`),
  KEY `type` (`type`),
  KEY `rating` (`rating`),
  KEY `sourceid` (`sourceid`),
  KEY `artist` (`artist`(10))
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `webint`
--

DROP TABLE IF EXISTS `webint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `webint` (
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webint`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `webint` WRITE;
/*!40000 ALTER TABLE `webint` DISABLE KEYS */;
/*!40000 ALTER TABLE `webint` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-09-18 11:29:45
