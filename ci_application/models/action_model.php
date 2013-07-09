<?php
class Action_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();
        $this->load->library('session');
        $this->load->helper('security');
	}

	//sends a login request to the DB object
	public function login() {
        //1. get data from post array
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = do_hash($this->security->xss_clean($this->input->post('password')));
        
        //2. Check for user in db
        $query = $this->db->get_where('User', array('Name' => $username , 'Password' => $password));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then create session data
            $row = $query->row();
            $data = array(
                    'userid' => $row->UserID,
                    'username' => $row->Name,
                    'email' => $row->Email
                    );
            $this->session->set_userdata($data);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
	}

    //Creates a new tag with name defined by the user, and the tagtype
    //according to the page it was passed from
    public function createTag($name, $tagtype) {
        if(strcmp($tagtype,"industry") == 0) {  //creating new industry tag
            $tagtype = "Industry";
        }else { //creating new claim tag
            $tagtype = "Claim Tag";
        }
        $tagExists = $this->db->get_where('Tags', array('Name' => $name , 'Type' => $tagtype));
        if($tagExists->num_rows() == 0) { //add new tag to system
            $table = 'Tags';
            $data = array(
               'Name' => $name ,
               'Type' => $tagtype
            );
            $this->db->insert($table, $data); 
            return $this->db->get_where('Tags', array('Name' => $name , 'Type' => $tagtype));
        }else {
            return 0;
        }
    }

    //Upvotes the relationship between the passed tag and company using
    //the user id who sent it, or deletes the vote the user previously made 
    //depending on the boolean value post (voted)
    //Returns: false if the tag id is corrupt or if the query failed, 
    //true otherwise
    public function upvoteTag($userid) {
        $tagid = $this->security->xss_clean($this->input->post('industryID'));
        $objectid = $this->security->xss_clean($this->input->post('objectID'));
        $tagtype = $this->security->xss_clean($this->input->post('tagType'));
        $voted = $this->security->xss_clean($this->input->post('voted'));

        //check tagid is for existing tag
        $isVotable = $this->db->get_where('Tags', array('TagsID' => $tagid , 'Type' => $tagtype));
        if(!$isVotable) {  //if not, adding new tag
            return false;
        }else {             //else, execute query
            //choose tags or industries
            $data;
            $table;
            if($tagtype == 'Industry') {
                $table = 'Company_has_Tags';
                $data = array(
                   'Company_CompanyID' => $objectid ,
                   'Tags_TagsID' => $tagid ,
                   'User_ID' => $userid
                );
            }else {
                $table = 'Claim_has_Tags';
                $data = array(
                   'Claim_ClaimID' => $objectid ,
                   'Tags_TagsID' => $tagid ,
                   'User_ID' => $userid
                );
            }
            if(!$voted) {    //user hasnt voted, insert row
                $result = $this->db->insert($table, $data); 
            }else {         //user has voted, remove row
                $result = $this->db->delete($table, $data);
            }
            return $result;
        }
    }

    // flag stuff
    public function flagContent($userid) {
        $targetID = $this->security->xss_clean($this->input->post('targetID'));
        $targetType = $this->security->xss_clean($this->input->post('targetType'));
        $flagType = $this->security->xss_clean($this->input->post('flagType'));
        
        // check if user already voted on this comment
        $hasVoted = $this->db->get_where('Flags', array('userID' => $userid , 'target_id' => $targetID, 'target_type' => $targetType));
        
        $data = array(
            'userID' => $userid, 
            'target_id' => $targetID,
            'target_type' => $targetType,
            'flag_type' => $flagType,
        );
        
        //user hasnt voted, insert or update row
        if ($hasVoted->num_rows == 0) { 
            $this->db->insert('Flags', $data); 
        } else {
            $row = $hasVoted->row();

            // if the same row, delete
            if (strcmp($row->flag_type, $flagType) == 0) { 
                $this->db->delete('Flags', $data);
            
            // else update row
            } else { 
                $data = array(
                    'flag_type' => $flagType
                );
                $where = array(
                    'userID' => $userid, 
                    'target_id' => $targetID,
                    'target_type' => $targetType
                );
                $this->db->update('Flags', $data, $where);     
            }            
        }

        return true;
    }

    //Sends comment vote to server, 
    //adds a new row if casting new vote, 
    //update row if already exists,
    //delete row if unvoting
    public function voteComment($userid) {
        $ClaimID = $this->db->escape_str($this->security->xss_clean($this->input->post('ClaimID')));
        $CommentID = $this->db->escape_str($this->security->xss_clean($this->input->post('CommentID')));
        $voted = $this->security->xss_clean($this->input->post('voted'));
        $value = $this->security->xss_clean($this->input->post('value'));
        $nowTime = date('Y-m-d H:i:s', time()-21600);

        // check if user already voted on this comment
        $hasVoted = $this->db->get_where('Vote', array('UserID' => $userid , 'CommentID' => $CommentID));
            $data = array(
               'ClaimID' => $ClaimID,
               'Value' => $value,
               'CommentID' => $CommentID,
               'UserID' => $userid,
               'Time' => $nowTime
            );
            // determine which column to update
            if ($value == 1) {
                $col = 'upvotes';
            } else {
                $col = 'downvotes';
            }
            if (!$voted) {    //user hasnt voted on this specific vote, insert or update row
                if ($hasVoted->num_rows == 0) {    //user has not voted on this comment, insert new row
                    $result = $this->db->insert('Vote', $data); 
                    $updateVoteCount = "UPDATE Discussion
                                        SET $col = $col+1
                                        WHERE ClaimID = $ClaimID
                                        AND CommentID = $CommentID";
                } else {    //user has voted on something, update their vote value
                    $data = array(
                        'Value' => $value,
                        'Time' => $nowTime
                    );
                    $where = array(
                        'UserID' => $userid,
                        'ClaimID' => $ClaimID,
                        'CommentID' => $CommentID
                    );
                    $result = $this->db->update('Vote', $data, $where);
                    if ($value == 1) {                    
                        $updateVoteCount = "UPDATE Discussion
                                            SET upvotes = upvotes + 1,
                                            downvotes = downvotes - 1
                                            WHERE ClaimID = $ClaimID
                                            AND CommentID = $CommentID";
                    } else {
                        $updateVoteCount = "UPDATE Discussion
                                            SET upvotes = upvotes - 1,
                                            downvotes = downvotes + 1
                                            WHERE ClaimID = $ClaimID
                                            AND CommentID = $CommentID";
                    }
                }
                $this->db->query($updateVoteCount);
                      
            } else {         //user is unvoting their current vote
                $data = array(
                   'ClaimID' => $ClaimID,
                   'Value' => $value,
                   'CommentID' => $CommentID,
                   'UserID' => $userid
                );                
                $result = $this->db->delete('Vote', $data);

                // Subtract 1 from vote count on this comment
                $updateVoteCount = "UPDATE Discussion
                                    SET $col = $col-1
                                    WHERE ClaimID = $ClaimID
                                    AND CommentID = $CommentID";
                $this->db->query($updateVoteCount);
            }
        return $result;
    }

    //adds user to the system
    public function addUser() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = do_hash($this->security->xss_clean($this->input->post('password')));
        $email = $this->security->xss_clean($this->input->post('email'));

        // Check to see if username already exists
        $query = $this->db->get_where('User', array('Name' => $username));
        // If username is not already taken, add user
        if ($query->num_rows() == 0) {
            $data = array(
                'Name' => $username,
                'Password' => $password,
                'Email' => $email
                );
            $result = $this->db->insert('User', $data);
            return true;
        }
        // else return false, username already exists
        return false;
    }

    //Updates the user profile with new information
    public function updateProfile($userid) {
        $col = $this->security->xss_clean($this->input->post('col'));
        $newInfo = $this->security->xss_clean($this->input->post('newInfo'));
        if ($col == 'Password')
            $newInfo = do_hash($newInfo);

        if ($col == 'Name')
            $this->session->set_userdata(array('username' => $newInfo));
        else if ($col == 'Email')
            $this->session->set_userdata(array('email' => $newInfo));

        //2. Check for user in db
        $query = $this->db->get_where('User', array('UserID' => $userid));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then update
            $data = array(
                    $col => $newInfo
                    );
            $where = "UserID = " . $userid;
            $result = $this->db->update('User', $data, $where);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
    }

    //Checks old password before updating new one
    public function passCheck($userid) {
        $password = do_hash($this->security->xss_clean($this->input->post('password')));

        //2. Check for user in db
        $query = $this->db->get_where('User', array('UserID' => $userid));
        //3. Verify row exists
        if($query->num_rows() == 1 && $query->row()->Password == $password)
        {
            // If user exists and entered password matches stored password
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
    }

    //Drops the user profile from database
    public function dropAccount($userid) {
        $password = do_hash($this->security->xss_clean($this->input->post('password')));

        //2. Check for user in db and for correct password
        $query = $this->db->get_where('User', array('UserID' => $userid, 'Password' => $password));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then delete
            $data = array(
                    'UserID' => $userid,
                    'Password' => $password
                    );
            $result = $this->db->delete('User', $data);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
    }

    // Adds a claim to the database
    // Clean input values for security
    public function addClaim($userid) {
        $url = $this->security->xss_clean($this->input->post('url'));
        $title = $this->security->xss_clean($this->input->post('title'));
        $desc = $this->security->xss_clean($this->input->post('desc'));
        $company = $this->db->escape_like_str($this->security->xss_clean($this->input->post('company')));
        $rating = $this->security->xss_clean($this->input->post('rating'));
        $tags = $this->security->xss_clean($this->input->post('tags'));
        $nowTime = date('Y-m-d H:i:s', time()-21600);

        if ($url == '' || $title == '' || $company == '' || $rating == '') {
            return false;
        }

        // Convert company name to its id
        // If happen to find more than 1 result, just get the company with
        // the most claims so far
        $query = "SELECT CompanyID
                FROM Company
                WHERE Name LIKE '%$company%'
                ORDER BY numClaims
                LIMIT 1";
        $result = $this->db->query($query);
        // If no companies similar to this name were found, add as new row
        if ($result->num_rows() == 0) {
            $data = array(
                'Name' => ucwords($company),
                'numClaims' => 0
                );
            $this->db->insert('Company', $data);
        }
        $companyID = $this->db->query($query)->row()->CompanyID;

        // Submit the claim without tags first
        $data = array(
            'Title' => $title,
            'Link' => $url,
            'Description' => $desc,
            'Score' => $rating,
            'UserID' => $userid,
            'CompanyID' => $companyID,
            'numScores' => 1
            );
        $this->db->insert('Claim', $data);

        /*----Recalculate average score of company----*/
        // first get company's current score and the number of claims for that company
        $query = $this->db->get_where('Company', array('CompanyID' => $companyID));
        $curScore = $query->row()->Score;
        $curNumClaims = $query->row()->numClaims;

        $newScore = ($curScore * $curNumClaims + $rating) / ($curNumClaims+1);

        // add new score to company's score history for graph
        $data = array(
            'Score' => $newScore,
            'CompanyID' => $companyID
            );
        $this->db->insert('CompanyRatings', $data);

        // Update company score with this rating and increase counter for number of claims for company
        $updateNumClaims = "UPDATE Company
                    SET Score = $newScore,
                    numClaims = numClaims+1
                    WHERE CompanyID = $companyID";
        $this->db->query($updateNumClaims);

        // Get claimID of their new claim
        $getClaim = $this->db->get_where(
            'Claim', array(
                'Title' => $title, 
                'Link' => $url, 
                'Description' => $desc, 
                'Score' => $rating,
                'UserID' => $userid
                )
            );
        $claimID = $getClaim->row()->ClaimID;

        // Submit rating
        $score = array(
            'Value' => $rating,
            'UserID' => $userid,
            'ClaimID' => $claimID,
            'Time' => $nowTime
            );
        $this->db->insert('Rating', $score);

        // Insert each new tag as a new row
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                //Check if this tag already exists in database
                $getTags = $this->db->get_where('Tags', array('Name' => $tag));
                //Insert if no results found
                if($getTags->num_rows() == 0) {
                    $newTag = array(
                        'Name' => $tag,
                        'Type' => 'Claim Tag',
                        'is_Seed' => 0
                        );
                    $this->db->insert('Tags', $newTag);
                }

                // Need this in case tag was recently entered and need to retrieve its new tagID
                $getTags = $this->db->get_where('Tags', array('Name' => $tag));
                
                // Get tagsID of tags entered and link tags to the claim
                $tagsID = $getTags->row()->TagsID;
                $claimTags = array(
                   'Claim_ClaimID' => $claimID,
                   'Tags_TagsID' => $tagsID,
                   'User_ID' => $userid
                    );
                $this->db->insert('Claim_has_Tags', $claimTags);
            }
        }
        return $claimID;
    }

    // Update database with users' rating for claims
    public function sendRating($userid) {
        $rating = $this->security->xss_clean($this->input->post('rating'));
        $claimID = $this->db->escape_str($this->security->xss_clean($this->input->post('claimID')));
        $nowTime = date('Y-m-d H:i:s', time());

        // Keep track of the claim's old and new scores
        $newScore = 0;
        $claimOldScore = 0;

        $hasRating = $this->db->get_where('Rating', array('UserID' => $userid, 'ClaimID' => $claimID));
        // Insert new rating if no results found and updating number of ratings this claim has
        if($hasRating->num_rows() == 0) {
            $score = array(
                'Value' => $rating,
                'UserID' => $userid,
                'ClaimID' => $claimID,
                'Time' => $nowTime
                );
            $this->db->insert('Rating', $score);

            // Get the current claim score before updating to check difference
            $oldScoreQuery = $this->db->get_where('Claim', array('ClaimID' => $claimID));
            $claimOldScore = $oldScoreQuery->row()->Score;
            $curNumScores = $oldScoreQuery->row()->numScores;

            $newScore = ($claimOldScore * $curNumScores + $rating) / ($curNumScores+1);

            // Add new rating to claim average and update number of ratings
            $addRating = "UPDATE Claim
                        SET Score = $newScore,
                        numScores = numScores+1
                        WHERE ClaimID = $claimID";
            $this->db->query($addRating);

        } else {
            // Update if they've already submitted a rating for this claim
            $userNewRating = array(
                'Value' => $rating,
                'Time' => $nowTime
                );
            $where = array(
                'UserID' => $userid,
                'ClaimID' => $claimID,
                );
            $this->db->update('Rating', $userNewRating, $where);

            /*----Recalculate average score of claim----*/
            // Because the user already submitted a rating, we have to figure out the difference
            // between their old rating and new rating in order to recalculate the new average

            // first get claim's current score and the number of ratings for that claim
            $claimScoreQuery = $this->db->get_where('Claim', array('ClaimID' => $claimID));
            $claimOldScore = $claimScoreQuery->row()->Score;
            $numScores = $claimScoreQuery->row()->numScores;

            // find the difference between the user's new rating and old rating
            $oldUserRating = $hasRating->row()->Value;
            $diff = $rating - $oldUserRating;

            $newScore = ($claimOldScore * $numScores + $diff) / $numScores;

            // update this claim's score with the new score
            $recalculated = array(
                'Score' => $newScore
                );
            $where = array(
                'ClaimID' => $claimID,
                );
            $this->db->update('Claim', $recalculated, $where);
        }

        /*----Recalculate average score of company----*/
        // first get the company attached to this claim
        $query = $this->db->get_where('Claim', array('ClaimID' => $claimID));
        $company = $query->row()->CompanyID;

        // find the difference between the claim's new score and its old score
        $diff = $newScore - $claimOldScore;

        // get company's current score and the number of claims for that company
        $query = $this->db->get_where('Company', array('CompanyID' => $company));
        $curScore = $query->row()->Score;
        $curNumClaims = $query->row()->numClaims;

        $newScore = ($curScore * $curNumClaims + $diff) / $curNumClaims;

        // update this company's score with the new score
        $recalculated = array(
            'Score' => $newScore
            );
        $where = array(
            'CompanyID' => $company
            );
        $this->db->update('Company', $recalculated, $where);
        return true;
    }

    // adds a comment to discussions on claim pages
    public function addComment($userid) {
        $claimID = $this->security->xss_clean($this->input->post('claimID'));
        $comment = $this->security->xss_clean($this->input->post('comment'));
        $parentCommentID = $this->security->xss_clean($this->input->post('parentCommentID'));
        $level = $this->security->xss_clean($this->input->post('level'));

        $commentData = array(
                'ClaimID' => $claimID,
                'UserID' => $userid,
                'Comment' => $comment,
                'ParentCommentID' => $parentCommentID,
                'level' => $level
                );
        $this->db->insert('Discussion', $commentData);
        return true;
    }

    // updates text on claim pages
    public function updateEdit($userid) {
        $table = $this->security->xss_clean($this->input->post('table'));
        $col = $this->security->xss_clean($this->input->post('col'));
        $forid = $this->security->xss_clean($this->input->post('forid'));
        $newText = $this->security->xss_clean($this->input->post('newText'));
        $withid = $this->security->xss_clean($this->input->post('withid'));

        $data = array(
                $col => $newText
                );
        $where = array(
                $forid => $withid
                );
        $this->db->update($table, $data, $where);
        return true;
    }

    // checks to see if this url has already been submitted
    public function urlFound($userid) {
        $url = $this->security->xss_clean($this->input->post('url'));

        $query = $this->db->get_where('Claim', array('Link' => $url));

        if($query->num_rows() != 0) {
            return $query->row()->ClaimID;
        }
        return false;
    }
}