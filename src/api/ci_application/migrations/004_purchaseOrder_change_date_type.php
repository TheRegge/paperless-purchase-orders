<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PurchaseOrder_change_date_type extends CI_Migration {

	public function __construct()
	{
		$this->load->database();
	}

	public function up() {
		$this->db->query( "ALTER TABLE purchaseOrders MODIFY COLUMN date DATETIME" );
		$this->db->query( "ALTER TABLE purchaseOrders MODIFY COLUMN dateRequired DATETIME" );
		$this->db->query( "UPDATE purchaseOrders set date = date + 040000" );
		$this->db->query( "UPDATE purchaseOrders set dateRequired = dateRequired + 040000" );
	}

	public function down() {
		$this->db->query( "UPDATE purchaseOrders set date = date - 040000" );
		$this->db->query( "UPDATE purchaseOrders set dateRequired = dateRequired - 040000" );
		$this->db->query( "ALTER TABLE purchaseOrders MODIFY COLUMN date DATE" );
		$this->db->query( "ALTER TABLE purchaseOrders MODIFY COLUMN dateRequired DATE" );
	}
}

/* End of file 004_purchaseOrder_change_date_type.php */
/* Location: ./application/migrations/004_purchaseOrder_change_date_type.php */