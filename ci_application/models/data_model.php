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

	// ------------- METHODS FOR CLAIM VIEW -------------
	public function getClaim($claimID) {
		$sql = "SELECT *
				FROM Claim cl
				LEFT JOIN Company co
				ON cl.CompanyID = co.CompanyID
				WHERE cl.ClaimID = $claimID";
		return $this->db->query($sql)->result_array();
	
		/* $query = $this->db->get_where('Claim', array('ClaimID' => $claimID));
		//TODO: handle case where row isn't found
		return $query->row(); */
	}
	
	public function getClaimTags($claimID) {
		$sql = "SELECT DISTINCT t.Name, COUNT(ct.Claim_ClaimID) as votes
				FROM Tags t
				LEFT JOIN Claim_has_Tags ct
				ON t.TagsID = Tags_TagsID
				LEFT JOIN Claim c
				ON c.ClaimID = Claim_ClaimID
				WHERE t.Type = 'Claim Tag'
				AND c.ClaimID = $claimID
				GROUP BY t.Name";
		return $this->db->query($sql)->result_array();
	}
	
	// ------------- METHODS FOR COMPANY VIEW -------------	
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
	
	// ------------- METHODS FOR TAG VIEW -------------
	public function getTags($tagID) {
		$query = $this->db->get_where('Tags', array('TagsID' => $tagID));
		//TODO: handle case where row isn't found
		return $query->row();
	}
}