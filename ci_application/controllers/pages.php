<?php
include('root_controller.php');
class Pages extends Root_Controller {
	/*Constructor
		-Loads Models and helpers
		-Load Generic Data files
	*/
	public function __construct() {
		parent::__construct();
		//url helper: for public urls
		$this->load->helper('url');
		//Other Example Loads
		$this->load->model('data_model');

		//checking login
		$data['userid'] = null;
		$data['isLogged'] = $this->is_logged_in();
		if($data['isLogged']) {
			$data['userid'] = $this->session->userdata('userid');
			$data['username'] = $this->session->userdata('username');
		}
		//basic css and js
		$data['csFiles'] = array();
		$data['jsFiles'] = array();
    	$this->load->vars($data);
	}

	//Default page Query, Redirect to home
	public function index() {
		$this->homepage();
	}

	//site homepage
	public function homepage() {
		$data['headerTitle'] = 'PatchWork - Make a Difference';
		$data['pageTitle'] = 'Home';
		$data['pageType'] = 'home';
		
		$data['csFiles'] = array('general','ccStyles','addClaim', 'tooltipster');
		//load welcome css headers
		$signedIn = $this->is_logged_in();
		if(!$signedIn) {
			array_push($data['csFiles'],'welcome');
		} else {
			$data['signedIn'] = true;			
		}
		$data['jsFiles'] = array('general','score','addClaim');
		$data['treemapJSON'] = $this->data_model->getTopCompaniesWithClaimsJSON();

		//Views
		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/addClaim', $data);
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('pages/treemap', $data);
		//if not logged in, show welcome message
		if(!$signedIn) {
			$this->load->view('pages/welcome', $data);
			$this->load->view('pages/welcomeActions', $data);
		}
		$this->load->view('templates/footer');
	}

	//add claim page
	//this page uses the same code as 'addClaim.php' and modifies elements with 
	//'addclaimPage.js' and 'addclaimPage.css'
	public function addclaim() {
		if ($this->is_logged_in()) {
			$data['signedIn'] = true;
		}
		$data['headerTitle'] = 'Add a Claim - PatchWork';
		$data['pageType'] = 'home';

		//files needed
		$data['csFiles'] = array('general','ccStyles','addClaim','addclaimPage');
		$data['jsFiles'] = array('addclaimPage','general','ccScripts','addClaim','score');

		//Views
		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/addclaimPage', $data);
		$this->load->view('pages/addClaim', $data);
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}


	//claim page
	public function claim($claimID = -1) {
		if($claimID == -1) {
			$this->homepage(); //!! change to TreemapSearch later
		}else {
			if ($this->is_logged_in()) {
				$data['signedIn'] = true;
			}
			$data['headerTitle'] = 'View Claim - PatchWork';
			$data['pageType'] = 'claim';

			//files needed
			$data['csFiles'] = array('general','ccStyles', 'tooltipster');
			$data['jsFiles'] = array('general','ccScripts','addClaim','score');

			$data['claimID'] = $claimID;
			$data['claimInfo'] = $this->data_model->getClaim($claimID);

			// only load full page when this claim has been found
			if ($data['claimInfo'] != null) {
				$data['claimTags'] = $this->data_model->getClaimTags($claimID, $this->userid);
			
				$resultsArr = [];
				$data['comments'] = $this->data_model->getDiscussion($claimID, 0, 0, $resultsArr, $this->userid);
				$data['uniqueUsers'] = $this->data_model->getUniqueUsers($claimID);
				$data['scores'] = $this->data_model->getClaimScores($claimID);
				$data['userRating'] = $this->data_model->getRatingOnClaim($claimID, $this->userid);
				
				$companyID = $data['claimInfo']['CompanyID'];
				$data['scoreHistory'] = $this->data_model->getClaimScoreHistoryJSON($claimID);
				$data['treemapJSON'] = $this->data_model->getJSON("companyTopClaims", $companyID);
				//$data['treemapSize'] = ["full", 500]; //Scott
				
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/ccTop', $data);
				$this->load->view('pages/evidence', $data);
				$this->load->view('pages/scoreTop', $data);
				$this->load->view('pages/score', $data);
				$this->load->view('pages/scoreRight', $data);
				$this->load->view('pages/highcharts', $data);
				$this->load->view('pages/scoreBottom', $data);
				// $this->load->view('pages/treemap', $data);
				$this->load->view('pages/discussion', $data);
			} else {
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
			}
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//company page
	public function company($companyID = -1) {
		if($companyID == -1 || !$this->data_model->getCompany($companyID)) {
			$this->homepage();
		}else {
			if ($this->is_logged_in()) {
				$data['signedIn'] = true;
			}			
			$data['headerTitle'] = 'View Company - PatchWork';
			$data['pageType'] = 'company';

			$data['csFiles'] = array('general','ccStyles','toggleview','tooltipster');
			$data['jsFiles'] = array('general','ccScripts','addClaim','score','toggleview');

			//grab basic data
			$companyData = $this->data_model->getCompany($companyID);
			
			// only load full page when this company has been found
			if ($companyData != null) {
				$data['companyInfo'] = get_object_vars($companyData);

				$data['companyClaims'] = $this->data_model->getCompanyClaims($companyID);
				$data['companyClaimsPos'] = $this->data_model->getCompanyClaimsPos($companyID);
				$data['companyClaimsNeg'] = $this->data_model->getCompanyClaimsNeg($companyID);
				$data['companyTags'] = $this->data_model->getCompanyTags($companyID, $this->userid);
				$data['scoreHistory'] = $this->data_model->getCompanyScoreHistoryJSON($companyID);
				$data['treemapJSON'] = $this->data_model->getJSON("companyClaims",$companyID);
				
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/ccTop', $data);
				$this->load->view('pages/scoreTop', $data);
				$this->load->view('pages/score', $data);
				$this->load->view('pages/scoreRight', $data);
				$this->load->view('pages/highcharts', $data);
				$this->load->view('pages/scoreBottom', $data);
				$this->load->view('pages/toggleview', $data);
				$this->load->view('pages/highlowClaims', $data);
				$this->load->view('pages/treemap', $data);
			} else {
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
			}
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//tag page
	public function tag($tagID = -1) {
		if($tagID == -1) {
			$this->homepage(); //!! change to TreemapSearch later
		}else {
			if ($this->is_logged_in()) {
				$data['signedIn'] = true;
			}
			$data['headerTitle'] = 'View Tag - PatchWork';
			$data['pageType'] = 'tag';

			$data['csFiles'] = array('general','tag','toggleview', 'tooltipster');
			$data['jsFiles'] = array('general','tag','addClaim','toggleview');
			
			$data['tagName'] = $this->data_model->getTag($tagID);
			$data['tagInfo'] = $this->data_model->getClaimsWithTag($tagID);
			//$data['treemapJSON'] = $this->data_model->getTopClaimsWithTagJSON($tagID);
			$data['treemapJSON'] = $this->data_model->getJSON("claimsWithTag",$tagID);
			
			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/tagTitle', $data);

			// only load full page when this tag has been found and there are claims with this tag
			if ($data['tagInfo'] != null) {
				$this->load->view('pages/toggleview', $data);
				$this->load->view('pages/tag', $data);
				$this->load->view('pages/treemap', $data);
			} else if ($data['tagName'] == null) {
				$this->load->view('pages/notfound', $data);
			}
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//profile page
	public function profile($userID = -1) {
		//get userdata to check if user is logged in
		$data['userdata'] = $this->session->all_userdata();

		//handle case when no parameter is passed
		if ($userID == -1) {
			if ($this->is_logged_in()) {
				//user is logged in, set variable as the userid in session and continue as normal
				$userID = $data['userdata']['userid'];
			} else {
				//if user is not logged in, redirect
				$this->homepage();
				return;
			}
		}
		$data['headerTitle'] = 'View profile';
		$data['pageType'] = 'profile';
		
		//files needed
		$data['csFiles'] = array('general','profile','toggleview', 'tooltipster');
		$data['jsFiles'] = array('general','profile','addClaim','toggleview');

		//grab basic data
		$data['curProfile'] = $userID;
		$data['userInfo'] = $this->data_model->getUser($userID);
		
		// only load full page when this user has been found
		if ($data['userInfo'] != null) {
			$data['userClaims'] = $this->data_model->getUserClaims($userID);
			$data['userComments'] = $this->data_model->getUserComments($userID);
			$data['userVotes'] = $this->data_model->getUserVotes($userID);
			$data['headerTitle'] = 'User Profile - Patchwork';
			//$data['treemapJSON'] = $this->data_model->getTopClaimsForUserJSON($userID);
			$data['treemapJSON'] = $this->data_model->getJSON("userClaims",$userID);

			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/profileTitle', $data);
			$this->load->view('pages/toggleview', $data);
			$this->load->view('pages/treemap', $data);
			$this->load->view('pages/profile', $data);
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/notfound', $data);
		}
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	public function about() {
		$data['headerTitle'] = 'About - PatchWork';
		$data['pageType'] = 'About';

		$data['csFiles'] = array('general','ccStyles','welcome');
		$data['jsFiles'] = array('general','ccScripts','addClaim');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/welcomeActions', $data);
		$this->load->view('pages/about');
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	public function team() {
		$data['headerTitle'] = 'Team - PatchWork';
		$data['pageType'] = 'Team';

		$data['csFiles'] = array('general','ccStyles', 'team');
		$data['jsFiles'] = array('general','ccScripts','addClaim');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/team');
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	public function faq() {
		$data['headerTitle'] = 'FAQ - PatchWork';
		$data['pageType'] = 'FAQ';

		$data['csFiles'] = array('general','ccStyles', 'faq');
		$data['jsFiles'] = array('general','ccScripts','addClaim');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/faq');
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}
}
