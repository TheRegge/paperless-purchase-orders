<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Initialize_db extends CI_Migration
{
	private $_sql = <<<EOF
	# ************************************************************
	# Sequel Pro SQL dump
	# Version 4096
	#
	# http://www.sequelpro.com/
	# http://code.google.com/p/sequel-pro/
	#
	# Host: ubuntu (MySQL 5.5.44-0ubuntu0.14.04.1)
	# Database: paperlesspo
	# Generation Time: 2015-09-17 18:23:25 +0000
	# ************************************************************


	/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
	/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
	/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
	/*!40101 SET NAMES utf8 */;
	/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
	/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
	/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


	# Dump of table audiences
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `audiences`;

	CREATE TABLE `audiences` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(20) NOT NULL DEFAULT '',
	  `audiencesources_id` smallint(5) unsigned NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `name` (`name`),
	  KEY `audiencesources_id` (`audiencesources_id`),
	  CONSTRAINT `audiences_ibfk_1` FOREIGN KEY (`audiencesources_id`) REFERENCES `audiencesources` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	LOCK TABLES `audiences` WRITE;
	/*!40000 ALTER TABLE `audiences` DISABLE KEYS */;

	INSERT INTO `audiences` (`id`, `name`, `audiencesources_id`)
	VALUES
		(1,'Faculty',1),
		(2,'Staff',1);

	/*!40000 ALTER TABLE `audiences` ENABLE KEYS */;
	UNLOCK TABLES;


	# Dump of table audiencesources
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `audiencesources`;

	CREATE TABLE `audiencesources` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(20) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	LOCK TABLES `audiencesources` WRITE;
	/*!40000 ALTER TABLE `audiencesources` DISABLE KEYS */;

	INSERT INTO `audiencesources` (`id`, `name`)
	VALUES
		(1,'Active Directory'),
		(2,'WhippleHIll');

	/*!40000 ALTER TABLE `audiencesources` ENABLE KEYS */;
	UNLOCK TABLES;


	# Dump of table departments
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `departments`;

	CREATE TABLE `departments` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(100) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	LOCK TABLES `departments` WRITE;
	/*!40000 ALTER TABLE `departments` DISABLE KEYS */;

	INSERT INTO `departments` (`id`, `name`)
	VALUES
		(1,'New Lab'),
		(2,'Middle School'),
		(3,'High School'),
		(4,'First Program'),
		(5,'Athletics'),
		(6,'Communications'),
		(7,'Business Office'),
		(8,'Library'),
		(9,'Alumni Office'),
		(10,'Admissions'),
		(11,'Building Services'),
		(12,'After School Program'),
		(13,'Summer Camp'),
		(14,'Nurse\'s Office');

	/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
	UNLOCK TABLES;


	# Dump of table items
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `items`;

	CREATE TABLE `items` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `purchaseOrder_poNumber` decimal(8,0) NOT NULL,
	  `quantity` smallint(6) NOT NULL DEFAULT '1',
	  `unitPrice` decimal(7,2) NOT NULL DEFAULT '0.00',
	  `catalogNo` varchar(50) NOT NULL DEFAULT '',
	  `description` text,
	  `budgetNumber` varchar(100) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`),
	  KEY `purchaseOrder_poNumber` (`purchaseOrder_poNumber`),
	  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`purchaseOrder_poNumber`) REFERENCES `purchaseOrders` (`poNumber`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;



	# Dump of table people
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `people`;

	CREATE TABLE `people` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `username` varchar(30) NOT NULL,
	  `ssid` varchar(20) DEFAULT NULL,
	  `firstname` varchar(50) NOT NULL DEFAULT '',
	  `lastname` varchar(50) NOT NULL DEFAULT '',
	  `admin` tinyint(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `username` (`username`),
	  KEY `firstname` (`firstname`),
	  KEY `lastname` (`lastname`),
	  KEY `admin` (`admin`),
	  KEY `ssid` (`ssid`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	LOCK TABLES `people` WRITE;
	/*!40000 ALTER TABLE `people` DISABLE KEYS */;

	INSERT INTO `people` (`id`, `username`, `ssid`, `firstname`, `lastname`, `admin`)
	VALUES
		(1,'regis','ZALR','Régis','Zaleman',0);

	/*!40000 ALTER TABLE `people` ENABLE KEYS */;
	UNLOCK TABLES;


	# Dump of table purchaseOrders
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `purchaseOrders`;

	CREATE TABLE `purchaseOrders` (
	  `poNumber` decimal(8,0) unsigned zerofill NOT NULL,
	  `ssid` varchar(8) NOT NULL DEFAULT '',
	  `date` date NOT NULL,
	  `dateRequired` date DEFAULT NULL,
	  `department_id` int(11) unsigned NOT NULL,
	  `shippingAndHandlingBudget` varchar(100) DEFAULT NULL,
	  `vendor` text NOT NULL,
	  `shipToLocations_id` int(11) unsigned NOT NULL,
	  `budgetNumber` varchar(50) NOT NULL DEFAULT '',
	  `teacher` varchar(100) NOT NULL DEFAULT '',
	  `divDirector` varchar(100) DEFAULT NULL,
	  `remarks` text,
	  `shippingAndHandling` decimal(6,2) NOT NULL DEFAULT '0.00',
	  `totalAmount` decimal(8,2) NOT NULL DEFAULT '0.00',
	  PRIMARY KEY (`poNumber`),
	  KEY `shipToLocations_id` (`shipToLocations_id`),
	  KEY `ssid` (`ssid`),
	  KEY `budgetNumber` (`budgetNumber`),
	  KEY `date` (`date`),
	  KEY `totalAmount` (`totalAmount`),
	  CONSTRAINT `purchaseOrders_ibfk_1` FOREIGN KEY (`shipToLocations_id`) REFERENCES `shipToLocations` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;



	# Dump of table shipToLocations
	# ------------------------------------------------------------

	DROP TABLE IF EXISTS `shipToLocations`;

	CREATE TABLE `shipToLocations` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `label` varchar(30) NOT NULL DEFAULT '',
	  `address` varchar(100) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `label` (`label`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	LOCK TABLES `shipToLocations` WRITE;
	/*!40000 ALTER TABLE `shipToLocations` DISABLE KEYS */;

	INSERT INTO `shipToLocations` (`id`, `label`, `address`)
	VALUES
		(1,'89th Street','108 East 89th Street<br>New York, NY 10128'),
		(2,'First Program','53 East 91st Street<br>New York, NY 10128'),
		(3,'Physical Education Center','200 East 87th Street<br>New York, NY 10128');

	/*!40000 ALTER TABLE `shipToLocations` ENABLE KEYS */;
	UNLOCK TABLES;



	/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
	/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
	/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
	/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
	/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
	/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
EOF;

	public function up() {
		$query = explode(";\n", $this->_sql);
		$this->db->trans_start();
		foreach ($query as $statement)
		{
			$this->db->query($statement);
		}
		$this->db->trans_complete();
	}

	public function down() 
	{
		// Do nothing
	}

}

/* End of file 001_create_db_tables.php */
/* Location: ./application/migrations/001_create_db_tables.php */