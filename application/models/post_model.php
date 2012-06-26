<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Jeff Risberg
 * @since 2012
 */
class Post_model extends CI_Model {

	function get($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('posts');
	  if ($query->num_rows() !== 0) {
	  	return $query->result();
	  }
	  else {
	  	return FALSE;
	  }
	}
	
	function get_all() {		
		$this->db->order_by('date_created', 'desc');
		$query = $this->db->get('posts');
		return $query->result();
	}
	
	function insert() {
		$date = date('Y-m-d H:i:s');
		$data = array(
			'title' => $_POST['title'],
			'body' => $_POST['body'],
			'date_created' => $date,
		  'last_updated' => $date
		);
		$this->db->insert('posts', $data);
	}
	
  public function update() {
		$this->title = $_POST['title'];
		$this->body = $_POST['body'];
		$this->last_updated = date('Y-m-d H:i:s');

		$this->db->update('posts', $this, array('id' => $_POST['id']));
	}

	public function delete() {
		$this->db->delete('posts', array('id' => $_POST['id']));
	}
	
	function get_comments($post_id)	{
		$this->db->where('post_id', $post_id);
		$query = $this->db->get('comments');
		return $query->result();
	}
	
	function total_comments($id) {
		$this->db->like('post_id', $id);
		$this->db->from('comments');
		return $this->db->count_all_results();
	}
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */