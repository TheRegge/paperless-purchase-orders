<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('vendors_model');
	}
	
	public function index()
	{
		$vendors = $this->vendors_model->get_user_vendors( $this->demo_login->get_user_info('ssid') );

		if ( count( $vendors ) )
		{
			$this->api_out( $vendors );
		}
	}

	public function save_user_vendor( $ssid=NULL, $vendor=NULL)
	{
		if ( is_null( $ssid ) )
		{ 
			$ssid = $this->input->post('ssid');
		}

		if ( is_null( $vendor ) )
		{
			$vendor = $this->input->post('vendor');
		}

		$result = $this->vendors_model->save_user_vendor( $ssid, $vendor );
		$this->api_out( array($result ));
	}

}

/* End of file Vendors.php */
/* Location: ./application/controllers/Vendors.php */