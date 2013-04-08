<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog_model extends CI_Model {

	function get($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('blogs');
		return $query->row_array();
	}
	
	function get_all() {
		$this->db->order_by('date_created','desc');
		$query = $this->db->get('blogs');
		return $query->result_array();
	}
	
	function insert ($title) {
		$date = date('Y-m-d H:i:s');
		$data = array(
				'title' => $title,
				'date_created' => $date,
				'last_updated' => $date
		);
		$this->db->insert('blogs', $data);
	}
	
	public function update() {
		$this->title = $_POST['title'];
		$this->body = $_POST['body'];
		$this->last_updated = date('Y-m-d H:i:s');
			
		$this->db->update('blogs', $this, array('id' => $_POST['id']));
	}
	
	public function delete() {
		$this->db->delete('blogs', array('id' => $_POST['id']));
	}
	
}
	/* End of file blog_model.php */
/* Location: ./application/models/blog_model.php */
