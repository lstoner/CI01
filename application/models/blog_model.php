<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    function get_all_posts()
    {
        //get all entry
        $query = $this->db->get('entry');
        return $query->result();
    }
 
    function add_new_entry($name,$body)
    {
        $data = array(
            'entry_name' => $name,
            'entry_body' => $body
        );
        $this->db->insert('entry',$data);
    }
}
 
/* End of file blog_model.php */
/* Location: ./application/models/blog_model.php */
