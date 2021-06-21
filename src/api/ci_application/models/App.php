<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Model {

	public function getDepartments()
	{
		$query = $this->db->get('departments');
		return $query->result_array();
	}

	public function getBudgets()
	{
		$sql = "SELECT b.id AS budgetId, b.department AS departmentId, b.number AS budgetNumber, d.name AS departmentName"
		. " FROM dummy_budgets b, departments d"
		. " WHERE b.department = d.id";
		 $query = $this->db->query($sql);
		 return $query->result_array();
	}
}

/* End of file app.php */
/* Location: ./application/models/app.php */