<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "post";
$route['404_override'] = '';

$route['about'] = 'about/index';
$route['new-post'] = 'post/create';
$route['post/(:num)'] = 'post/show/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */