<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Vendors_table extends CI_Migration {

	public function __construct()
	{
		$this->load->database();
	}

	public function up() {
		$sql = <<<EOF
		# ************************************************************
		# Sequel Pro SQL dump
		# Version 4096
		#
		# http://www.sequelpro.com/
		# http://code.google.com/p/sequel-pro/
		#
		# Host: ubuntu (MySQL 5.5.44-0ubuntu0.14.04.1)
		# Database: paperlesspo
		# Generation Time: 2015-09-21 17:39:30 +0000
		# ************************************************************


		/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
		/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
		/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
		/*!40101 SET NAMES utf8 */;
		/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
		/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
		/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


		# Dump of table vendors
		# ------------------------------------------------------------

		DROP TABLE IF EXISTS `vendors`;

		CREATE TABLE `vendors` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `vendor` text NOT NULL,
		  `people_ssid` varchar(20) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `people_ssid` (`people_ssid`),
		  CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`people_ssid`) REFERENCES `people` (`ssid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		LOCK TABLES `vendors` WRITE;
		/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;

		INSERT INTO `vendors` (`id`, `vendor`, `people_ssid`)
		VALUES
			(1,'Apple Inc.\nP.O. Box 41602\nPhiladelphia, PA 19101',NULL),
			(2,'Quill Corporation\nP.O. Box 37600\nPhiladelphia, PA, 19101-0600',NULL),
			(11,'Apple Inc.\nP.O. Box 281877\nAtlanta GA, 30384-1877',NULL);

		/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
		UNLOCK TABLES;



		/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
		/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
		/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
		/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
		/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
		/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
EOF;
		$query = explode(";\n", $sql );
		$this->db->trans_start();
		foreach ( $query as $statement )
		{
			$this->db->query( $statement );
		}
		$this->db->trans_complete();
	}

	public function down() {
		$this->db->query( "DROP TABLE IF EXISTS vendors");
	}

}

/* End of file 003_vendors_table.php */
/* Location: ./application/migrations/003_vendors_table.php */