<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseorders extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Purchase_orders', 'poModel');
		$this->load->library('Demo_login/demo_login');
	}

	/**
	 * Save a Purchase Order
	 * @return JSON output 
	 */
	public function save()
	{

		if ( $po = $this->input->post('po') )
		{
			$po = json_decode( $po );

			$savedPo = $this->poModel->save_po($po);

			$this->api_out( $savedPo );
		}
		else
		{
			$r = array(
				'success' => FALSE,
				'msg' => 'No data received',
				'po' => $po
			);
			$this->api_out( $r );
		}
	}

	public function delete()
	{
		$poNumber = $this->input->post('poNumber');
		if ( $poNumber )
		{
			$result = $this->poModel->delete_po($poNumber);
			$this->api_out( array( 'msg' => 'success', 'deleted' => $result));
		}
		else
		{
			$this->api_out( array( 'msg' => 'error', 'deleted' => false));
		}
	}

	/**
	 * Get one or all Purchase order
	 * @param  [poid] [Optional, PoNumber]
	 * @return  JSON output Array of 1 or many purchase orders
	 */
	public function get()
	{
		$poNumber     = $this->input->post('poid');
		$current_user = $this->demo_login->get_user_info();
		$ssid         = $current_user['ssid'];
		$isAdmin      = $this->roles->current_user_is_admin();

		if ( ! $poNumber )
		{
			$poNumber = -1;
		}
		$po = $this->poModel->get( $ssid, $isAdmin, $poNumber );

		$this->api_out( $po );
	}

	/**
	 * Get Purchase Order(s)
	 * @param  array $params an associative array of query parameters
	 * @return JSON output Array
	 */
	public function getBy()
	{

		$teacher    = $this->input->post('teacher');
		$date       = $this->input->post('date');
		$department = $this->input->post('department');


		$params = array(
			'poNumber'            => $this->input->post('poNumber'),
			'ssid'                => $this->input->post('ssid'),
			'date'                => $this->input->post('date'),
			'dateRequired'        => $this->input->post('dateRequired'),
			'department'          => $this->input->post('department'),
			'vendor'              => $this->input->post('vendor'),
			'shipToLocations_id'  => $this->input->post('shipToLocations_id'),
			'budgetNumber'        => $this->input->post('budgetNumber'),
			'teacher'             => $this->input->post('teacher'),
			'divDirector'         => $this->input->post('divDirector'),
			'remarks'             => $this->input->post('remarks'),
			'shippingAndHandling' => $this->input->post('shippingAndHandling'),
			'totalAmount'         => $this->input->post('totalAmount'),
		);

		$pos = $this->poModel->getBy($params);
		$this->api_out( $pos );
	}

}

/* End of file purchaseOrders.php */
/* Location: ./application/controllers/purchaseOrders.php */