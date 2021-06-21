<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['roles'] = array(); // add a 'wrapper' array to avoid config name collisions
$config['roles']['admin_groups'] = array('NLTL', 'Business Office', 'MS Office');
$config['roles']['have_access_groups']  = array('Faculty', 'Staff');