<?php
class Data_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();
	}

	//get all tag types
	public function testDB() {
		$query = $this->db->get('User');
		return $query->result_array();		
	}

}