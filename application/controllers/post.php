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
		
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = 'gif|jpg|png|txt|pdf|ppt|pptx';
		$config['max_size']	= '3000';
		$config['max_width'] = '3000';
		$config['max_height'] = '3000';
		
		$this->load->library('upload', $config);
		
		$this->load->library('image_lib');
	}
		
	// show a list of posts
	public function index() {
		$this->load->library(array('form_validation', 'session'));
		
		$data['title'] = "Home";
		
		$data['posts'] = $this->post_model->get_all();
		
		$this->load->view('post/index', $data);
	}
	
	// view one specific post and its comments
	public function view($id)	{
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
	
		$data['query'] = $this->post_model->get($id);
		$data['comments'] = $this->post_model->get_comments($id);
		$data['post_id'] = $id;
		$data['total_comments'] = $this->post_model->total_comments($id);
		
		$this->load->view('post/view', $data);
	}
	
	public function saveComment($post_id)	{
		$this->load->helper('form');
		$this->load->library(array('form_validation','session'));
	
		//set validation rules
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('body', 'Comment', 'required');
	
		if ($this->post_model->get($post_id)) {
			foreach ($this->post_model->get($post_id) as $row)	{
				//set page title
				$data['title'] = $row->title;
			}
				
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
		$data['title'] = "Add new post";
		
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
		
	  $this->load->view('post/create', $data);
	}
	
	public function save()	{
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
	
		$this->form_validation->set_rules('title', 'Title', 'required|max_length[200]');
		$this->form_validation->set_rules('body', 'Body', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$this->create();
		}
		else {
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
	}
	
	public function edit($id)	{
		$data['post'] = $this->post_model->get($id);
		
		if (empty($data['post'])) {
			show_404();
		}

		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
			
		$data['title'] = "Edit post";
	
		$this->load->view('post/edit', $data);
	}
	
	public function update()	{
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'session'));
	
		$this->form_validation->set_rules('title', 'Title', 'required|max_length[200]');
		$this->form_validation->set_rules('body', 'Body', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$this->edit($_POST['id']);
		}
		else {
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
				
			$this->post_model->update($image);
			$this->session->set_flashdata('message', 'Post updated');
			redirect('post/index');
		}
	}
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */