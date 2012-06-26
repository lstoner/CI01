<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Jeff Risberg
 * @since 2012
 */
class Comment extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->model('post_model');
		$this->load->model('comment_model');
	}
}

/* End of file comment.php */
/* Location: ./application/controllers/comment.php */