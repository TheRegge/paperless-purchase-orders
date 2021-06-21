<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'App', 'appModel' );
		$this->load->model('purchase_orders', 'poModel' );
		$this->load->model( 'Departments' );
	}

	public function index()
	{
		$this->load->view('main');
	}

	public function departments()
	{
		$departments = $this->appModel->getDepartments();
		//$departments = $this->Departments->getAll();
		$this->output
		->set_content_type( 'application/json' )
		->set_output( json_encode( $departments ) );
	}

	public function budgets()
	{
		$budgets = $this->appModel->getBudgets();
		//@todo: implement Budgets model
		//$budgets = $this->Budgets->getAll();
		$this->output
		->set_content_type( 'application/json' )
		->set_output( json_encode( $budgets ) );
	}

	public function shiptolocations()
	{
		$locations = $this->poModel->getShipTolocations();
		$this->output
		->set_content_type( 'application/json' )
		->set_output( json_encode( $locations ) );
	}

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */