<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiences_model extends CI_Model {

	/**
	 * Get all existing audiences
	 * in the database with their associated
	 * audience source (ex: WhippleHill)
	 * 
	 * @return Array The audiences
	 */
	public function get_audiences()
	{
		$sql = "SELECT
			a.id,
			a.name,
			a.audiencesources_id,
			asrc.name AS source_name
		FROM audiences a
		JOIN audiencesources asrc ON a.audiencesources_id = asrc.id
		ORDER BY a.name
			";

		return $this->db->query( $sql )->result_array();
	}

	/**
	 * Return an array of all audiences names
	 * @return array audiences names
	 */
	public function get_audiences_names()
	{
		$this->db->select('name');
		$query   = $this->db->get('audiences');
		$results = array();

		foreach ($query->result() as $row)
		{
			$results[] = $row->name;
		}
		return $results;
	}

}

/* End of file Audiences.php */
/* Location: ./application/models/Audiences.php */