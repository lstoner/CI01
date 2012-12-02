<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Jeff Risberg
 * @since 2012
 */
class Post extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->model('post_model');
		$this->load->model('comment_model');
		
		$this->load->helper('language');
		$this->lang->load('post');
		
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
				
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = 'gif|jpg|png|txt|pdf|ppt|pptx';
		$config['max_size']	= '3000';
		$config['max_width'] = '3000';
		$config['max_height'] = '3000';
		
		$this->load->library('upload', $config);
		
		$this->load->library('image_lib');
		
		$this->post_validation_rules = array(
				array('field' => 'title', 'label' => 'Title', 'rules' => 'required|max_length[200]'),
				array('field' => 'body',  'label' => 'Body',  'rules' => 'required'),
				array('field' => 'image', 'label' => 'Image', 'rules' => 'trim')
		);
		
		$this->comment_validation_rules = array(
				array('field' => 'title', 'label' => 'Title',   'rules' => 'required|max_length[200]'),
				array('field' => 'email', 'label' => 'Body',    'rules' => 'required|valid_email'),
				array('field' => 'body',  'label' => 'Comment', 'rules' => 'required|trim')							
		);		
	}
		
	/**
	 * show a list of posts
	 */
	public function index() {
		$this->data['title'] = "Home";
		
		$this->data['posts'] = $this->post_model->get_all();
		
		$this->load->view('post/index', $this->data);
	}
	
	/**
	 * view one specific post and its comments
	 */
	public function view($id)	{
		
		$this->data['post'] = $this->post_model->get($id);
		$this->data['comments'] = $this->post_model->get_comments($id);
		$this->data['post_id'] = $id;
		$this->data['total_comments'] = $this->post_model->total_comments($id);
		
		$this->data['title'] = "Post Details";
		
		$this->load->view('post/view', $this->data);
	}
	
	public function saveComment($post_id)	{
		
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('body', 'Comment', 'required');
	
		if ($this->post_model->get($post_id)) {
			if ($this->form_validation->run() == FALSE) {
				//if not valid
				$this->view($post_id);
			}
			else {
				//if valid
				$title = $this->input->post('title');
				$email = strtolower($this->input->post('email'));
				$body = $this->input->post('body');
	
				$this->comment_model->insert($post_id, $title, $email, $body);
				$this->session->set_flashdata('message', '1 new comment added!');
				redirect('post/view/' . $post_id);
			}
		}
		else {
			show_404();
		}
	}
		
	public function create()	{
		$this->form_validation->set_rules($this->post_validation_rules);	
			
		if ($this->form_validation->run()) {
			$image = "";
			if (array_key_exists('image', $_FILES) && ($_FILES['image']['name'] != "") && ! $this->upload->do_upload('image')) {
					
				// Uploading failed. $error will hold the error indicators, so show them an error
				$this->data->messages['error'] = $this->upload->display_errors();
				$success = false;
			}
			else {
				if (array_key_exists('image', $_FILES) && $_FILES['image']['name'] != "") {
					$upload_data = $this->upload->data();
						
					$image = $upload_data['file_name'];
						
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/' . $image;
					$config['new_image'] = './uploads/thumbs';
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 75;
					$config['height'] = 50;
		
					$this->image_lib->initialize($config);
		
					if ( ! $this->image_lib->resize()) {
						echo $this->image_lib->display_errors();
					}
				}
				else {
					$image = "";
				}
			}
		
			$this->post_model->insert($image);
			$this->session->set_flashdata('message', '1 new post added!');
			redirect('post/index');
		}

		// Go through all the known fields and get the post values
		$this->data->post = new stdClass;
		foreach ($this->post_validation_rules as $key => $field) {
			$this->data->post->$field['field'] = set_value($field['field']);
		}	
				
		$this->data->title = "Add new post";
		$this->data->method = 'create';
		
	  $this->load->view('post/form', $this->data);
	}
	
	public function edit($id)	{
		$this->form_validation->set_rules('title', 'Title', 'required|max_length[200]');
		$this->form_validation->set_rules('body', 'Body', 'required');
		$this->form_validation->set_rules('image', 'Image', 'trim');
		
		$this->data->post = $this->post_model->get($id);
		
		if ($this->form_validation->run()) {
			$image = "";
			if (array_key_exists('image', $_FILES) && ($_FILES['image']['name'] != "") && ! $this->upload->do_upload('image')) {
					
				// Uploading failed. $error will hold the error indicators, so show them an error
				$this->data->messages['error'] = $this->upload->display_errors();
				$success = false;
			}
			else {
				if (array_key_exists('image', $_FILES) && $_FILES['image']['name'] != "") {
					$upload_data = $this->upload->data();
		
					$image = $upload_data['file_name'];
		
					$config['image_library'] = 'gd2';
					$config['source_image'] = './uploads/' . $image;
					$config['new_image'] = './uploads/thumbs';
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 50;
					$config['height'] = 50;
						
					$this->image_lib->initialize($config);
						
					if ( ! $this->image_lib->resize()) {
						echo $this->image_lib->display_errors();
					}
				}
				else {
					$image = "";
				}
			}
		
			$this->post_model->update($id, $image);
			$this->session->set_flashdata('message', 'Post updated');
			redirect('post/index');
		}
		
		$this->data->title = "Edit post";
		$this->data->method = 'edit';
	
		$this->load->view('post/form', $this->data);
	}
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */