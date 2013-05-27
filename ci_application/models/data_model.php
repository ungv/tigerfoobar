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

	// ------------- SEARCH METHODS -------------

	public function companiesByName($root) {
		$sql = "SELECT * from Company
				WHERE Company.Name like '%$root%'
				ORDER BY Score DESC
				LIMIT 5";
		return $this->db->query($sql)->result_array();
		//$this->rowsByName($root,"Company");
	}

	public function claimsByName($root) {
		$sql = "SELECT * from Claim
				WHERE Claim.Title like '%$root%'
				ORDER BY Score DESC
				LIMIT 5";
		return $this->db->query($sql)->result_array();
		$this->rowsByName($root , "Claim");
	}

	//Returns the rows containing the root term in the given table.
	//Orders results by votes
	public function rowsByName($root, $table) {
		/*
		$sql = "SELECT * from ".$table."
				WHERE Name like '%$root%'
				ORDER BY Score DESC
				LIMIT 5";
		return $this->db->query($sql)->result_array();
		*/
	}

	// ------------- METHODS FOR GETTING THE SCORE INFORMATION -------------
	public function getClaimScores($claimID) {
		$sql = "SELECT *, COUNT(ClaimID) AS noRatings, 
					(SELECT COUNT(ClaimID) 
					FROM Rating
					WHERE ClaimID = $claimID) as Total
				FROM Rating
				WHERE ClaimID = $claimID
				GROUP BY Value
				ORDER BY Value";
		return $this->db->query($sql)->result_array();
	}

	// ------------- METHODS FOR CLAIM VIEW -------------

	//Returns all the relavent information about the claim and it's company,
	//as well as the user who created it.
	public function getClaim($claimID) {
		$sql = "SELECT cl.ClaimID, cl.Link, cl.Title AS ClaimTitle, cl.Description, cl.Score AS ClaimScore, cl.UserID, cl.CompanyID, cl.Time AS ClaimTime, co.Name AS CoName, u.Name AS UserName
				FROM Claim cl
				LEFT JOIN Company co
				ON cl.CompanyID = co.CompanyID
				LEFT JOIN User u
				ON cl.UserID = u.UserID
				WHERE cl.ClaimID = $claimID";
		return $this->db->query($sql)->row();
	}
	
	// Get all tags assocated with a specific claim
	public function getClaimTags($claimID, $userID) {
		if(!isset($userID)) {
			$userID = -1;
		}
		$sql = "SELECT DISTINCT t.Name, t.TagsID, COUNT(c.Claim_ClaimID) as votes, 
				sum(case when c.User_ID = $userID then 1 else 0 end) as uservoted
				FROM Tags t
				JOIN Claim_has_Tags c
				ON c.Tags_TagsID = t.TagsID
				WHERE c.Claim_ClaimID = $claimID
				GROUP BY t.Name, t.TagsID
				ORDER BY COUNT(c.Claim_ClaimID) DESC";
		return $this->db->query($sql)->result_array();
	}

	// Get user's rating on a specific claim
	public function getRatingOnClaim($claimID, $userid) {
		if(!isset($userID)) {
			$userID = -1;
		}		
		$sql = "SELECT Value
				FROM Rating
				WHERE ClaimID = $claimID
				AND UserID = $userid";
		return $this->db->query($sql)->row();
	}
		
	// ------------- METHODS FOR COMPANY VIEW -------------	
	//Retrieves the basic data for a company
	public function getCompany($companyID) {
		$sql = "SELECT *
				FROM Company
				WHERE CompanyID = $companyID";
		return $this->db->query($sql)->row();
	}

	//Retrieves the top claims for a given company
	public function getCompanyClaims($companyID) {
		$sql = "SELECT cl.*, cl.numScores AS noRatings, co.numClaims AS Total, co.Name
				FROM Claim cl
				LEFT JOIN Company co
				ON co.CompanyID = cl.CompanyID
				WHERE co.CompanyID = $companyID
				GROUP BY cl.Score";
		return $this->db->query($sql)->result_array();
	}
	
	//Retrieves the industry tags associated with
	//a given $companyID , as well as if the current users
	//have voted on a given industry-tag combination
	public function getCompanyTags($companyID, $userID) {
		if(!isset($userID)) {
			$userID = -1;
		}
		$sql = "SELECT DISTINCT t.Name, t.TagsID, COUNT(c.Company_CompanyID) as votes , 
				sum(case when c.User_id = $userID then 1 else 0 end) as uservoted
				FROM Tags t
				JOIN Company_has_Tags c
				ON c.Tags_TagsID = t.TagsID
				WHERE c.Company_CompanyID = $companyID
				GROUP BY t.Name, t.TagsID
				ORDER BY COUNT(c.Company_CompanyID) DESC";
		return $this->db->query($sql)->result_array();
	}

	//Returns SQL from the Tags table, used for fetching autocomplete data
	public function tagList($root) {
		$tagtype = $this->security->xss_clean($this->input->get('tagtype'));
		$sql = "SELECT * from Tags
				WHERE Tags.Name like '$root%'
				AND Tags.Type like '$tagtype' 
				LIMIT 10";
		return $this->db->query($sql)->result_array();
	}
		
	// ------------- METHODS FOR TAG VIEW ---------------

	// Get all claims associated with a specific tag
	public function getClaimsWithTag($tagID) {
		$sql = "SELECT DISTINCT t.Name, ct.Claim_ClaimID, c.Title, c.Score AS ClScore, c.numScores, co.CompanyID, co.Name AS CoName, co.Score AS CoScore
				FROM Tags t
				LEFT JOIN Claim_has_Tags ct
				ON t.TagsID = ct.Tags_TagsID
				LEFT JOIN Claim c
				ON ct.Claim_ClaimID = c.ClaimID
                LEFT JOIN Company co
                ON c.CompanyID = co.CompanyID
				WHERE t.tagsID = $tagID";
		return $this->db->query($sql)->result_array();
	}

	// ------------- METHODS FOR DISCUSSION VIEW --------

	// Get all comments and their children associated with a specific claim and rank them based on voting ratio
	public function getDiscussion($claimID, $parentID, $level, $resultsArr, $userID) {
		if(!isset($userID)) {
			$userID = -1;
		}
		$sql = "SELECT d.ClaimID, d.CommentID, d.Comment, d.UserID, u.Name, d.votes, d.level, d.Time, r.Value, 		
					COUNT(IF(v.Value = 1, 1, NULL)) AS Ups, 
					COUNT(IF(v.Value = 0, 1, NULL)) AS Downs,
					COUNT(IF(v.Value = 1, 1, NULL)) - COUNT(IF(v.Value = 0, 1, NULL)) as Diff,
					sum(case when v.UserID = $userID and v.Value = 1 then 1 else 0 end) as userVotedUp, 
					sum(case when v.UserID = $userID and v.Value = 0 then 1 else 0 end) as userVotedDown
				FROM Discussion d
				LEFT JOIN User u ON u.UserID = d.UserID
				LEFT JOIN Rating r ON u.UserID = r.UserID
				AND r.ClaimID = d.ClaimID
				LEFT JOIN Vote v 
				ON v.CommentID = d.CommentID
				WHERE d.ClaimID = $claimID
				AND d.ParentCommentID = $parentID
				GROUP BY d.CommentID
				ORDER BY Diff DESC";
		$results = $this->db->query($sql)->result_array();
		foreach ($results as $result) {
			array_push($resultsArr, $result);
			$resultsArr = $this->getDiscussion($claimID, $result['CommentID'], $level+1, $resultsArr, $userID);
		}
		return $resultsArr;
	}

	public function getUniqueUsers($claimID) {
		$sql = "SELECT DISTINCT UserID
				FROM Discussion
				WHERE ClaimID = $claimID";
		return $this->db->query($sql)->num_rows();
	}
	
	// ------------- METHODS FOR PROFILE VIEW ---------------
	
	// Get user information
	public function getUser($userID) {
		$query = $this->db->get_where('User', array('UserID' => $userID));
		return $query->row();
	}
	
	// Get user's submitted claims
	public function getUserClaims($userID) {
		$sql = "SELECT c.ClaimID, c.Title, r.Value
				FROM User u
				LEFT JOIN Claim c
				ON c.UserID = u.UserID
				LEFT JOIN Rating r
				ON c.ClaimID = r.ClaimID
				WHERE u.UserID = $userID";
		return $this->db->query($sql)->result_array();
	}	
	
	// Get user's submitted comments
	public function getUserComments($userID) {
		$sql = "SELECT u.Name, c.ClaimID, d.CommentID, d.Comment, c.Title, c.CompanyID, co.Name AS CoName, r.Value
				FROM User u
				LEFT JOIN Discussion d
				ON u.UserID = d.UserID
				LEFT JOIN Claim c
				ON d.ClaimID = c.ClaimID
				LEFT JOIN Rating r
				ON u.UserID = r.UserID
				AND d.ClaimID = r.ClaimID
				LEFT JOIN Company co
				ON co.CompanyID = c.CompanyID
				WHERE u.UserID = $userID";
		return $this->db->query($sql)->result_array();
	}	
	
	// Get user's votes on comments
	public function getUserVotes($userID) {
		$sql = "SELECT u.Name, v.Value, v.CommentID, d.Comment, v.Time, c.ClaimID, c.Title, co.CompanyID, co.Name AS CoName
				FROM User u
				LEFT JOIN Vote v
				ON u.UserID = v.UserID
				LEFT JOIN Discussion d
				ON v.CommentID = d.CommentID
				LEFT JOIN Claim c
				ON c.ClaimID = d.ClaimID
				LEFT JOIN Company co
				ON co.CompanyID = c.CompanyID
				WHERE u.UserID = $userID";
		return $this->db->query($sql)->result_array();
	}
	
	//------------- METHODS FOR TREEMAP VIEW ------------
	
	public function getTopCompaniesWithClaims() {
		$N = 10;
		$M = 10;
		
		$sql = "Select cl.ClaimID as ClaimID, cl.Title, cl.Score as claimScore, cl.numScores, topCompanies.numClaims, topCompanies.Name, topCompanies.Score as companyScore
			From Claim cl
			Join
				(Select * 
				from Company co
				GROUP BY co.CompanyID
				Order by co.numClaims DESC
				Limit 0, $N) topCompanies
			On cl.companyID = topCompanies.companyID";
		return $this->db->query($sql)->result_array();
		/*TODO: Make sure this is returning the correct data; something's funky with scores
		*/
	}
	
	//Gets data for the top companies along with their claims and formats them as JSON to be used in a treemap view
	public function getTopCompaniesWithClaimsJSON() {

		$topCompanies = $this->getTopCompaniesWithClaims();
		$jsonDataObj = '"name": "Top companies with claims", "children": [';
		
		//Builds JSON out of the data in the $data array
		$companiesWithClaims = '';
		$currCompany = "";
		
		for ($i = 0; $i < count($topCompanies); $i++) {
		
			
			//foreach($topClaims as $topClaim) {

			if ($topCompanies[$i]["Name"] != $currCompany) {
				$currCompany = $topCompanies[$i]["Name"];
				$companiesWithClaims .= '{"name": "' . $topCompanies[$i]["Name"] . '", "children": [';
			}
			
			$claims = '';
			$rating = $topCompanies[$i]["companyScore"];			
			while (($i < count($topCompanies)) && $topCompanies[$i]["Name"] == $currCompany) {
				$title = str_replace("'","", $topCompanies[$i]["Title"]);
				$size = str_replace("'","", $topCompanies[$i]["numScores"]);
				$score = $topCompanies[$i]["claimScore"];
				$claimID = $topCompanies[$i]["ClaimID"];
				
				
				$claims .= '{"name" : "' . $title . '", "claimID" : "' . $claimID . '", "score" : "' . $score .'", "size" : ' . $size . '},';
				$i++;
			} 
			$claims = rtrim($claims, ",");
			
			$companiesWithClaims .= $claims;
			$companiesWithClaims .= '], "rating": "' . $rating . '"},';
			
			$i--;
		}
		
		$companiesWithClaims = rtrim($companiesWithClaims, ",");
		$jsonDataObj .= $companiesWithClaims . ']';
		
		return $jsonDataObj;
		
		/* TODO: Clean up the ugly fencepost shit going on here
		*/
	}
	
	//Gets claims for the given company
	public function getClaimsForCompanyJSON($companyID) {
		$topClaimsForCompany = $this->getCompanyClaims($companyID);
		//TODO: handle the case where no claims on company
		$companyName = $topClaimsForCompany[0]["Title"];
		$jsonDataObj = '"name": "Top claims for '.$companyName.'", "children": [';
		
		//Builds JSON out of the data in the $data array
		$companiesWithClaims = '';
		$claims = "";
		
		for ($i = 0; $i < count($topClaimsForCompany); $i++) {
				$title = str_replace("'","", $topClaimsForCompany[$i]["Title"]);
				$claimID = $topClaimsForCompany[$i]["ClaimID"];
				$score = $topClaimsForCompany[$i]["Score"];
				$size = str_replace("'","", $topClaimsForCompany[$i]["numScores"]);	
				
				$claims .= '{"name" : "' . $title . '", "claimID" : "' . $claimID . '", "score" : "' . $score .'", "size" : ' . $size . '},';

		}
		
		$claims = rtrim($claims, ",");
		$jsonDataObj .= $claims. ']';
		
		return $jsonDataObj;
	}
	
	//Gets claims for the given tag
	public function getTopClaimsWithTagJSON($tagID) {
		$topClaimsWithTag = $this->getClaimsWithTag($tagID);
		$tagName = $topClaimsWithTag[0]["Title"];
		$jsonDataObj = '"name": "Top claims for '.$tagName.'", "children": [';
		
		//Builds JSON out of the data in the $data array
		$companiesWithClaims = '';
		$claims = "";
		
		for ($i = 0; $i < count($topClaimsWithTag); $i++) {
				$title = str_replace("'","", $topClaimsWithTag[$i]["Title"]);
				$claimID = $topClaimsWithTag[$i]["Claim_ClaimID"];
				$score = $topClaimsWithTag[$i]["ClScore"];
				$size = str_replace("'","", $topClaimsWithTag[$i]["numScores"]);	
				
				$claims .= '{"name" : "' . $title . '", "claimID" : "' . $claimID . '", "score" : "' . $score .'", "size" : ' . $size . '},';

		}
		
		$claims = rtrim($claims, ",");
		$jsonDataObj .= $claims. ']';
		
		return $jsonDataObj;
	}
}