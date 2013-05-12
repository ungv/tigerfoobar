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

	//get all tag types
	public function basicProfileInfo($userID) {
		$query = $this->db->get_where('User', array('UserID' => $userID));
		return $query->result_array();
	}

}