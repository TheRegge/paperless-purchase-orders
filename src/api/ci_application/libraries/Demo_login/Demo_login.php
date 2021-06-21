<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Demo Login Class
 *
 * Fake login for demo purposes
 *
 * @category	Libraries
 * @author		Regis Zaleman
 * @version		Demo 1.0
 */
class Demo_login {
	private $_CI;  // Reference to the CodeIgniter object
	private $_userinfo;
	
	/**
	 * Constructor
	 *
	 * Initialize session handling and the userinfo array.
	 *
	 * @return void
	 */
    public function __construct() {

		$this->_CI =& get_instance();
		$this->_CI->load->library('session');
		$this->_userinfo = array(
			"source"      => "Active Directory and Whipple Hill",
			"username"    => "regis",
			"firstname"   => "Jane",
			"lastname"    => "Doe",
			"ssid"        => "JDOE",
			"email"       => "jane_doe@example.com",
			"fullname"    => "Jane Doe",
			"description" => "Administrator",
			"groups" => array(
				"Staff",
			),
			"logged_in" => true,
			"whid"      => 1000,
			"prefix"    => "Ms.",
			"photo"     => array(
				"fullsize"  => "https://randomuser.me/api/portraits/women/92.jpg",
				"thumbnail" => "https://randomuser.me/api/portraits/women/92.jpg"
			),
			"roles" => array(
				"All School",
				"Platform Manager",
				"Page Manager",
				"Faculty and Staff",
				"Content Manager",
				"Impersonate User",
				"Web Services API Manager",
				"Profile Manager",
				"WordPress Manager",
				"Community Group Owner",
				"Community Group Manager",
				"App Access",
				"Grade Book Manager",
				"House Roster Access"
			)
		);
	}
	// --------------------------------------------------------------------


	/**
	 * Log In
	 *
	 * Alias for log_in_ad(). Created to retain backwards compatibility for apps built
	 * against earlier versions of this library, which only queried Active Directory.
	*/
	public function log_in($username, $password) {
		if ($username === 'admin' && $password === 'password')
		{
			$this->_CI->session->set_userdata('logged_in', TRUE);
			$this->_CI->session->set_userdata($this->_userinfo);
			return true;
		}
		else
		{
			$this->_CI->session->set_userdata('logged_in', FALSE);
			return false;
		}
	}



	/**
	 * Log Out
	 *
	 * Destroys the current session.
	 *
	 * @return	void
	 */
	public function log_out() {
		$this->_CI->session->sess_destroy();
	}
	// --------------------------------------------------------------------
	

	/**
	 * Get Login Status
	 *
	 * Returns a boolean indicating whether or not the user is
	 * currently logged in.
	 *
	 * @return	boolean
	 */
	public function get_login_status() {
		return $this->_CI->session->userdata('logged_in');
	}
	// --------------------------------------------------------------------


	/**
	 * Get Login Failure
	 *
	 * Returns a boolean indicating whether or not the user's last login
	 * attempt was successful.
	 *
	 * @return	boolean
	 */
	public function get_login_failure() {
		return $this->_CI->session->flashdata('last_login_failed');
	}
	// --------------------------------------------------------------------


	/**
	 * Get User Info
	 *
	 * Returns the requested user information for the currently logged in user.
	 * If no field is specified, an array of all the session data is returned.
	 *
	 * @param	string (optional)
	 * @return	string|array
	 */
	public function get_user_info($field = FALSE) {
		if ($field) {
			return $this->_CI->session->userdata($field);
		} else {
			return $this->_CI->session->all_userdata();
		}
	}
}

/* End of file Demo_login.php */
/* Location: ./system/application/libraries/Demo_login.php */
