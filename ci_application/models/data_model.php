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

	public function getCompany($companyID) {
		$query = $this->db->get_where('Company', array('CompanyID' => $companyID));
		//TODO: handle case where row isn't found
		return $query->row();
	}
	
	public function getCompanyClaims($companyID) {
		$sql = "SELECT cl.*
				FROM Claim cl
				LEFT JOIN Company co
				ON co.CompanyID = cl.CompanyID
				WHERE co.CompanyID = $companyID";
		return $this->db->query($sql)->result_array();
	}
	
	public function getCompanyTags($companyID) {
		$sql = "SELECT DISTINCT t.Name, COUNT(c.Company_CompanyID) as votes
				FROM Tags t
				LEFT JOIN Company_has_Tags c
				ON c.Tags_TagsID = t.TagsID
				WHERE t.Type = 'Industry'
				AND c.Company_CompanyID = $companyID
				GROUP BY t.Name";
		return $this->db->query($sql)->result_array();
	}
}