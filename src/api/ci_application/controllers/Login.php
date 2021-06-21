<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct ()
	{
		parent::__construct( FALSE ); // false means class controller access is not restricted
		$this->load->library('Demo_login/demo_login');
		$this->load->model( 'user_model' );
		$this->load->model('audiences_model');
	}

	public function index()
	{
		if ( $this->input->input_stream('username') && $this->input->input_stream('password') )
		{
			$username   = $this->input->input_stream('username');
			$password   = $this->input->input_stream('password');
			$user_login = $this->demo_login->log_in( $username, $password );
			$userInfo   = $this->demo_login->get_user_info();

			$this->api_out( $userInfo);
		}
		else
		{
			$this->api_out(array('msg' => 'Incomplete credentials' ) );
		}
	}


	/**
	 * Get current user info
	 * @return json user info
	 */
	public function get_current_user()
	{
		$data = $this->demo_login->get_user_info();
		$this->api_out( $data );
	}


	public function get_local_user_data( $username )
	{
		return $this->user_model->get_local_user_data( $username );
	}


	/**
	 * Logs user out
	 * @return json json output to browser with logout status
	 */
	public function logout()
	{
		$this->demo_login->log_out();
		$this->api_out( array( 'success' => true ) );
	}

	protected function save_user_to_db( $user )
	{
		// Block access to non-authorized users
		if ( ! $this->access_granted )
		{
			die( 'Access not granted.' );
		}
		return $this->user_model->save_user_to_db( $user );
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */