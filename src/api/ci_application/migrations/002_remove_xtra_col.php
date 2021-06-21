<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Remove_xtra_col extends CI_Migration {

	public function __construct()
	{
		$this->load->database();
	}

	public function up() {
		$this->db->query( "ALTER TABLE purchaseOrders DROP budgetNumber" );
	}

	public function down() {
		$this->db->query( "ALTER TABLE purchaseOrders VARCHAR(50) NOT NULL" );
	}

}

/* End of file 002_remove_xtra_col.php */
/* Location: ./application/migrations/002_remove_xtra_col.php */