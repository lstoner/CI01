<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends CI_Controller
{
    function __construct()
    {
        parent::__construct();         
        $this->load->model('blog_model');
        $this->load->helper('url');
    }
 
    function index()
    {
        //this function will retrive all blogs in the database
        $data['blogs'] = $this->blog_model->get_all();
        $this->load->view('blog/index',$data);
    }
}
   /* End of file blog.php */
/* Location: ./application/controllers/blog.php */