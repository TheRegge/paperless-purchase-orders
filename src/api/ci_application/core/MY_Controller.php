<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $is_admin;
	public $is_logged_in;
	public $roles;

	public function __construct( $protected=true )
	{
		parent::__construct();

		if ( $protected )
		{
			// Block access to non-authorized users
			if ( ! $this->roles->current_user_has_access() )
			{
				die( 'Access not granted.' );
			}
		}
		else
		{
			// Give the child classes info on access status
			$this->access_granted = true;//$this->roles->current_user_has_access();
		}
	}

	protected function api_out( $data )
	{
		$this->output
		->set_content_type( 'application/json' )
		->set_output( json_encode( $data ) );
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */