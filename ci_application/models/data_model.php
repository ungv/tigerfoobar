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
		return $this->rowsByName($root,"Company","Name", array("CompanyID","Name","Score"));
	}

	public function claimsByName($root) {
		return $this->rowsByName($root , "Claim", "Title", array("ClaimID","Title","Score"));
	}

	public function tagsByName($root) {
		return $this->rowsByName($root , "Tags", "Name", array("TagsID","Name","Type"));
	}

	//Returns the rows containing the root term in the given table.
	//Orders results by votes
	public function rowsByName($root, $table,$comparefield, $fields) {
		$sql = "SELECT $fields[0] as id , $fields[1] as name , $fields[2] as score from $table
				WHERE $comparefield like '%$root%'
				ORDER BY Score DESC
				LIMIT 5";
		return $this->db->query($sql)->result_array();
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
	public function getRatingOnClaim($claimID, $userID) {
		if(!isset($userID)) {
			$userID = -1;
		}
		$sql = "SELECT Value
				FROM Rating
				WHERE ClaimID = $claimID
				AND UserID = $userID";
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

	//Retrieves the all claims for a given company
	public function getCompanyClaims($companyID) {
		$sql = "SELECT cl.*, cl.Description, cl.numScores AS noRatings, co.numClaims AS Total, co.Name, u.Name as userName, cl.UserID
				FROM Claim cl
			    JOIN Company co
				ON co.CompanyID = cl.CompanyID
				Join User u
				On cl.UserID = u.UserID
				WHERE co.CompanyID = $companyID
				ORDER BY cl.Score";
		return $this->db->query($sql)->result_array();
	}

	//Retrieves the top $n positive claims for a given company
	public function getCompanyClaimsPos($companyID) {
		$sql = "SELECT cl.*, cl.numScores AS noRatings, co.numClaims AS Total, co.Name
				FROM Claim cl
			    JOIN Company co
				ON co.CompanyID = cl.CompanyID
				WHERE co.CompanyID = $companyID
				AND cl.Score > 0
				ORDER BY cl.Score DESC";
		return $this->db->query($sql)->result_array();
	}

	//Retrieves the top $n negative claims for a given company
	public function getCompanyClaimsNeg($companyID) {
		$sql = "SELECT cl.*, cl.numScores AS noRatings, co.numClaims AS Total, co.Name
				FROM Claim cl
			    JOIN Company co
				ON co.CompanyID = cl.CompanyID
				WHERE co.CompanyID = $companyID
				AND cl.Score < 0
				ORDER BY cl.Score ASC";
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
		$sql = "SELECT DISTINCT t.Name, ct.Claim_ClaimID as ClaimID, c.Title, c.Description, c.Score AS Score, c.numScores, co.CompanyID, co.Name AS CoName, co.Score AS CoScore, u.Name as userName, u.UserID
				FROM Tags t
				LEFT JOIN Claim_has_Tags ct
				ON t.TagsID = ct.Tags_TagsID
				LEFT JOIN Claim c
				ON ct.Claim_ClaimID = c.ClaimID
                LEFT JOIN Company co
                ON c.CompanyID = co.CompanyID
				JOIN User u
				On c.UserID = u.UserID
				WHERE t.tagsID = $tagID";
		return $this->db->query($sql)->result_array();
	}

	// ------------- METHODS FOR DISCUSSION VIEW --------

	// Get all comments and their children associated with a specific claim and rank them based on voting ratio
	public function getDiscussion($claimID, $parentID, $level, $resultsArr, $userID) {
		if(!isset($userID)) {
			$userID = -1;
		}
		$sql = "SELECT d.ClaimID, d.CommentID, d.Comment, d.UserID, u.Name, d.level, d.Time, r.Value, 		
					d.upvotes AS Ups, d.downvotes AS Downs,
					d.upvotes - d.downvotes as Diff,
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
		$sql = "SELECT u.Name as userName, u.UserID, c.Description, c.ClaimID, c.Score, c.Title, c.numScores
				FROM User u
				LEFT JOIN Claim c
				ON c.UserID = u.UserID
				WHERE u.UserID = $userID";
		return $this->db->query($sql)->result_array();
	}	
	
	// Get user's submitted comments
	public function getUserComments($userID) {
		$sql = "SELECT u.Name, c.ClaimID, d.CommentID, d.Comment, c.Title, c.Description, c.CompanyID, co.Name AS CoName, r.Value, d.Time
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
				WHERE u.UserID = $userID
				ORDER BY d.Time DESC";
		return $this->db->query($sql)->result_array();
	}	
	
	// Get user's votes on comments
	public function getUserVotes($userID) {
		$sql = "SELECT u.Name, v.Value, v.CommentID, d.Comment, v.Time, c.Description, c.ClaimID, c.Title, co.CompanyID, co.Name AS CoName, v.Time
				FROM User u
				LEFT JOIN Vote v
				ON u.UserID = v.UserID
				LEFT JOIN Discussion d
				ON v.CommentID = d.CommentID
				LEFT JOIN Claim c
				ON c.ClaimID = d.ClaimID
				LEFT JOIN Company co
				ON co.CompanyID = c.CompanyID
				WHERE u.UserID = $userID
				ORDER BY v.Time DESC";
		return $this->db->query($sql)->result_array();
	}
	
	//------------- METHODS FOR TREEMAP VIEW ------------
	
	public function getTopCompaniesWithClaims() {
		$N = 10;
		$M = 10;
		
		$sql = "Select cl.ClaimID as ClaimID, cl.Title, cl.Score as claimScore, cl.Description, cl.numScores, cl.description, cl.UserID, u.Name as userName, topCompanies.numClaims, topCompanies.Name as companyName, topCompanies.companyID, topCompanies.Score as companyScore
			From Claim cl
			Join
				(Select * 
				from Company co
				GROUP BY co.CompanyID
				Order by co.numClaims DESC
				Limit 0, $N) topCompanies
			On cl.companyID = topCompanies.companyID
			Join User u
			On cl.UserID = u.UserID";
		return $this->db->query($sql)->result_array();
		/*TODO: Make sure this is returning the correct data; something's funky with scores
		*/
	}
	
	public function getClaimsWithTagForTreemap($tagID) {
		$sql = "SELECT DISTINCT t.Name, ct.Claim_ClaimID as ClaimID, c.Title, c.Description, c.Score AS claimScore, c.numScores, co.CompanyID, co.Name AS companyName, co.Score AS companyScore, u.Name as userName, u.UserID
				FROM Tags t
				LEFT JOIN Claim_has_Tags ct
				ON t.TagsID = ct.Tags_TagsID
				LEFT JOIN Claim c
				ON ct.Claim_ClaimID = c.ClaimID
                LEFT JOIN Company co
                ON c.CompanyID = co.CompanyID
				JOIN User u
				On c.UserID = u.UserID
				WHERE t.tagsID = $tagID";
		return $this->db->query($sql)->result_array();
	}
	
	public function getUserClaimsForTreemap($userID) {
		$sql = "SELECT u.Name as userName, u.UserID, c.Description, c.ClaimID, c.Score as claimScore, c.Title, c.numScores
				FROM User u
				LEFT JOIN Claim c
				ON c.UserID = u.UserID
				WHERE u.UserID = $userID";
		return $this->db->query($sql)->result_array();
	}
	
	//Retrieves the all claims for a given company
	public function getCompanyTopClaims($companyID) {
		$N = 5;
		$sql = "SELECT cl.Title, cl.Score as claimScore, cl.ClaimID, cl.Description, cl.numScores, co.numClaims AS Total, cl.Description, co.Name AS companyName, u.Name as userName, u.UserID
				FROM Claim cl
			    JOIN Company co
				ON co.CompanyID = cl.CompanyID
				Join User u
				On cl.UserID = u.UserID
				WHERE co.CompanyID = $companyID
				ORDER BY cl.Score DESC
				Limit 0, $N";
		return $this->db->query($sql)->result_array();
	}
	
	public function getClaimsInRange($rangeStart, $rangeEnd) {
		$sql = "SELECT cl.Title, cl.Score as claimScore, cl.ClaimID, cl.Description, cl.UserID, cl.numScores, u.Name as userName, co.numClaims AS Total, co.Name AS companyName, co.companyID, co.Score AS companyScore
				FROM Claim cl
				JOIN User u
				ON cl.UserID = u.UserID
				JOIN Company co
				On cl.CompanyID = co.CompanyID
				ORDER BY cl.numScores DESC
				Limit $rangeStart, $rangeEnd";
		return $this->db->query($sql)->result_array();
	}
	
	public function getCompaniesInRange($rangeStart, $rangeEnd) {
		$sql = "SELECT co.numClaims, co.Name AS companyName, co.companyID, co.Score AS companyScore
				FROM Company co
				ORDER BY co.numClaims DESC
				Limit $rangeStart, $rangeEnd";
		return $this->db->query($sql)->result_array();
	}
	
	public function getTagsInRange($rangeStart, $rangeEnd) {
		$sql = "SELECT co.numClaims, co.Name AS companyName, co.companyID, co.Score AS companyScore
				FROM Company co
				ORDER BY co.numClaims DESC
				Limit $rangeStart, $rangeEnd";
		return $this->db->query($sql)->result_array();
	}
	
	//Gets data for the top companies along with their claims and formats them as JSON to be used in a treemap view
	public function getTopCompaniesWithClaimsJSON() {

		$topCompanies = $this->getTopCompaniesWithClaims();
		$jsonDataObj = '"name": "Top companies with claims", "children": [';
		
		//Builds JSON out of the data in the $data array
		$companiesWithClaims = '';
		$currCompany = "";
		$companyID = "";
		
		for ($i = 0; $i < count($topCompanies); $i++) {
			if ($topCompanies[$i]["companyName"] != $currCompany) {
				$currCompany = $topCompanies[$i]["companyName"];
				$companyID = $topCompanies[$i]["companyID"];
				$companiesWithClaims .= '{"name": "' . $topCompanies[$i]["companyName"] . '", "children": [';
			}
			
			$claims = '';
			$rating = $topCompanies[$i]["companyScore"];			
			while (($i < count($topCompanies)) && $topCompanies[$i]["companyName"] == $currCompany) {
				$title = str_replace("'","", $topCompanies[$i]["Title"]);
				$size = str_replace("'","", $topCompanies[$i]["numScores"]);
				$score = $topCompanies[$i]["claimScore"];
				$claimID = $topCompanies[$i]["ClaimID"];
				$userName = $topCompanies[$i]["userName"];
				$userID = $topCompanies[$i]["UserID"];
				$claimDescription = str_replace('"', "", $topCompanies[$i]["Description"]);
				
				$claims .= '{"name" : "' . $title . '", "claimID" : "' . $claimID . '", "description" : "' . $claimDescription . '", "score" : "' . $score .'", "size" : ' . $size . ', "userName": "'. $userName .'", "userID" : "'. $userID .'", "company" : "'. $currCompany .'", "companyID" : "'. $companyID .'"},';
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
	public function getJSON($type, $entityID) { 
		$rawData = null;
		$name = "";
		
		//For each type of treemap, initialize variables a little bit differently
		if ($type == "companyClaims") {
			$rawData = $this->getCompanyClaims($entityID);
			
			if (isset($rawData[0])) {
				$name = $rawData[0]["Title"];
			}
		} else if ($type == "claimsWithTag") {
			$rawData = $this->getClaimsWithTagForTreemap($entityID);
			
			if (isset($rawData[0])) {
				$name = $rawData[0]["Name"];
			}
		} else if ($type == "userClaims") {
			$rawData = $this->getUserClaimsForTreemap($entityID);
			
			if (isset($rawData[0])) {
				$name = $rawData[0]["userName"];
			}
		} else if ($type == "companyTopClaims") {
			$rawData = $this->getCompanyTopClaims($entityID);
			
			if (isset($rawData[0])) {
				$name = $rawData[0]["companyName"];
			}
		} else if ($type == "claimsInRange") {
			$rawData = $this->getClaimsInRange(0, 100);
			
			if (isset($rawData[0])) {
				$name = "top claims";
			}
		} else if ($type == "companiesInRange") {
			$rawData = $this->getCompaniesInRange(0, 100);
			
			if (isset($rawData[0])) {
				$name = "top companies";
			}
		} else if ($type == "tagsInRange") {
			$rawData = $this->getTagsInRange(0, 100);
			
			if (isset($rawData[0])) {
				$name = "top tags";
			}
		}
		
		$jsonDataObj = '"name": "Top claims for '.$name.'", "children": [';
		//Builds JSON out of the data in the $data array
		$companiesWithClaims = '';
		$claims = "";
		
		for ($i = 0; $i < count($rawData); $i++) {
				if ($type == "companiesInRange") {
					$companyName = $rawData[$i]["companyName"];
					$companyID = $rawData[$i]["companyID"];
					$score = $rawData[$i]["companyScore"];
					$size = $rawData[$i]["numClaims"];
					
					$claims .= '{"name" : "' . $companyName . '", "company" : "' . $companyName . '", "companyID" : "'. $companyID .'", "score" : "' . $score .'", "size" : ' . $size . '},';
				} else {
					$title = str_replace("'","", $rawData[$i]["Title"]);
					$claimID = $rawData[$i]["ClaimID"];
					$score = $rawData[$i]["claimScore"];
					$userName = $rawData[$i]["userName"];
					$userID = $rawData[$i]["UserID"];
					$size = $rawData[$i]["numScores"];
					
					$claimDescription = str_replace('"', "", $rawData[$i]["Description"]);
					$claims .= '{"name" : "' . $title . '", "claimID" : "' . $claimID . '", "description" : "' . $claimDescription . '", "score" : "' . $score .'", "size" : ' . $size . ', "userName": "'. $userName .'", "userID" : "'. $userID .'"},';
				}
		}
		
		$claims = rtrim($claims, ",");
		$jsonDataObj .= $claims. ']';
		
		return $jsonDataObj;
	}


	//------------- METHODS FOR HIGHCHARTS VIEW ------------
	// Get the history of all changes to this claim's score
	public function getClaimScoreHistoryJSON($claimID) {
		$sql = "SELECT SUM(Value)/COUNT(Value) as Avg, DATE(Time) as DateOnly
				FROM Rating
				WHERE ClaimID = $claimID
				GROUP BY DateOnly
				ORDER BY DateOnly ASC";
		$rawData = $this->db->query($sql)->result_array();
		$jsonDataObj = '"scores": [';
		$values = '';
		for ($i = 0; $i < count($rawData); $i++) {
			$values .= '{"date" : "' . $rawData[$i]['DateOnly'] . '", "value" : ' . $rawData[$i]['Avg'] . '},';
		}
		$values = rtrim($values, ',');
		$jsonDataObj .= $values . ']';
		return $jsonDataObj;
	}

	// Get the history of all changes to this company's score
	public function getCompanyScoreHistoryJSON($companyID) {
		$sql = "SELECT SUM(Score)/COUNT(Score) as Avg, DATE(Time) as DateOnly
				FROM CompanyRatings
				WHERE CompanyID = $companyID
				GROUP BY DateOnly
				ORDER BY DateOnly ASC";
		$rawData = $this->db->query($sql)->result_array();
		$jsonDataObj = '"scores": [';
		$values = '';
		for ($i = 0; $i < count($rawData); $i++) {
			$values .= '{"date" : "' . $rawData[$i]['DateOnly'] . '", "value" : ' . $rawData[$i]['Avg'] . '},';
		}
		$values = rtrim($values, ',');
		$jsonDataObj .= $values . ']';
		return $jsonDataObj;
	}

}