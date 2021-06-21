<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departments extends CI_Model {

	/**
	 * Gets all departments from Senior Systems
	 * @return array simple array of departments names
	 */
		public function getAll()
		{
			$sql = "SELECT DISTINCT
			CASE
			WHEN crs.ID LIKE 'GOA%' THEN 'GOA'
			ELSE dep.NAME
			END AS \"department\"
			FROM RG_COURSE_SC crs
			JOIN DEPARTMENT dep ON crs.DEPARTMENT_ID = dep.ID
			WHERE crs.SCHOOL_ID = 'Upper'
			AND crs.COURSE_STATUS = 'active'
		AND crs.ID NOT IN ('3960', '3990') -- Filter out House and Preceptorial
		ORDER BY \"department\"";

		$query = $this->oracle->query( $sql );
		return $query->result_array();
	}


}

/* End of file Departments.php */
/* Location: ./application/models/Departments.php */