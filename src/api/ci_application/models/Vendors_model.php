<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors_model extends CI_Model {

	public function get_user_vendors( $ssid )
	{	
		$sql = "SELECT * FROM vendors WHERE people_ssid = '{$ssid}' OR people_ssid IS NULL ORDER BY vendor";
		$query = $this->db->query( $sql );
		return $query->result_array();
	}

	public function save_user_vendor( $ssid, $vendor )
	{
		// See if it is already there
		$sql = "SELECT COUNT(people_ssid) as num FROM vendors WHERE people_ssid = '{$ssid}' AND vendor = '{$vendor}'";
		$query = $this->db->query( $sql );
		$result = $query->row();
		// return $result;
		if ( ($result->num*1) <= 0 )
		{
			$sql = "INSERT INTO vendors (people_ssid, vendor) VALUES ('{$ssid}', '{$vendor}')";
			$query = $this->db->query ( $sql );
			if ( $this->db->affected_rows() == 1 )
			{
				return array( 'success' => true, 'vendor_id' => $this->db->insert_id() );
			}
			else
			{
				return array( 'success' => false, 'msg' => 'Something went wrong while attempting to save the vendor for this user.' );
			}
		}
		else
		{
			return array ( 'success' => false, 'msg' => 'Vendor already saved for this user.');
		}
	}

}

/* End of file Vendors_model.php */
/* Location: ./application/models/Vendors_model.php */