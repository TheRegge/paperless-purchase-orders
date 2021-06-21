<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function save_user_to_db( $user )
	{
		$username = strtolower( $user['username'] );

		$this->db->where( 'username', $username );
		$query     = $this->db->get( 'people' );
		$row       = $query->row_array();
		$people_id = $row['id'];
		$is_admin  = $row['admin'];

		if ( $query->num_rows() === 0 )
		{
			$this->db->insert( 'people', array(
				'username'  => $username,
				'firstname' => $user['firstname'],
				'lastname'  => $user['lastname'],
				'ssid'      => $user['ssid']
			));
			$is_admin  = false;
			$people_id = $this->db->insert_id();
		}
		return array( 'people_id' => $people_id, 'is_admin' => $is_admin );
	}

	public function get_local_user_data( $username )
	{
		$username = strtolower( $username );
		$query = $this->db->where( 'username', $username )
		->get( 'people' );
		return $query->row_array();
	}

	public function get_user_by_id ( $people_id )
	{
		return $this->db->where( 'id', $people_id )
		->get( 'people' )
		->row_array();
	}

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */