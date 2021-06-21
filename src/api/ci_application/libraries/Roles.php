<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles
{
	protected $_CI;

	public function __construct()
	{
        $this->_CI =& get_instance();
        $this->_CI->load->library('Demo_login/demo_login');
        $this->_CI->load->config('roles');
	}

	public function current_user_is_admin ()
	{
		$userGroups   = $this->_get_current_user_groups();
		$roles        = $this->_CI->config->item('roles');
		$adminGroups  = $roles['admin_groups'];
		$intersect    = array_intersect($adminGroups, $userGroups);

		return count($intersect) > 0;
	}

	public function current_user_has_access ()
	{
		$userGroups       = $this->_get_current_user_groups();
		$roles            = $this->_CI->config->item('roles');
		$haveAccessGroups = $roles['have_access_groups'];
		$intersect        = array_intersect($haveAccessGroups, $userGroups);

		return count($intersect) > 0;
	}

	private function _get_current_user_groups ()
	{
		$current_user = $this->_CI->demo_login->get_user_info();
		return $current_user['groups'];
	}

}

/* End of file Roles.php */
/* Location: ./application/libraries/Roles.php */
