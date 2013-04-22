<?php
class News_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();
	}

	//
	public function get_news($slug = FALSE) {
		//get all items
		if ($slug === FALSE) {
			$query = $this->db->get('news');
			return $query->result_array();
		}
		
		//get items by their slugs
		$query = $this->db->get_where('news', array('slug' => $slug));
		return $query->row_array();
	}

	//creates a news entity
	public function set_news() {
		//loads the helper to make uris
		$this->load->helper('url');
		
		//turns the title into a slug, replacing spaces with dashes
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		
		//array to be passed to the insert function
		//grabs info from post array
		$data = array(
			'title' => $this->input->post('title'),
			'slug' => $slug,
			'text' => $this->input->post('text')
		);
		
		//return result from the insert method
		return $this->db->insert('news', $data);
	}
	

}