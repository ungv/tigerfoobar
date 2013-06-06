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
		
		$data['csFiles'] = array('general','ccStyles',/*'addClaim',*/ 'tooltipster', 'homepage');
		//load welcome css headers
		$signedIn = $this->is_logged_in();
		if(!$signedIn) {
			array_push($data['csFiles'],'welcome');
		} else {
			$data['signedIn'] = true;
		}
		$data['jsFiles'] = array('general','score',/*'addClaim',*/'welcome');
		$data['treemapJSON'] = $this->data_model->getTopCompaniesWithClaimsJSON();

		//Views
		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		// $this->load->view('pages/addClaim', $data);
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
		$data['csFiles'] = array('general','ccStyles','addClaim','addclaimPage','tooltipster');
		$data['jsFiles'] = array('general','score','addClaim','ccScripts');

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
		$claimID = intval($claimID);
		$data['claimInfo'] = $this->data_model->getClaim($claimID);

		// if id can not be found or no id given, display top claims
		if($claimID == -1 || !$data['claimInfo']) {
			$data['headerTitle'] = 'View Top Claims - PatchWork';
			$data['csFiles'] = array('general', 'tooltipster', 'homepage');
			$data['treemapJSON'] = $this->data_model->getJSON("claimsInRange", null);
			$data['pageType'] = 'claimBrowse';
			
			$this->load->view('templates/header', $data);
			if ($claimID != -1) {
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
				$this->load->view('pages/mainBottom', $data);
			}
			$this->load->view('pages/treemap', $data);
			$this->load->view('templates/footer');
		}else { // else display the claim
			if ($this->is_logged_in()) {
				$data['signedIn'] = true;
			}
			$data['headerTitle'] = 'View Claim - PatchWork';
			$data['pageType'] = 'claim';

			//files needed
			$data['csFiles'] = array('general','ccStyles', 'tooltipster');
			$data['jsFiles'] = array('general','ccScripts','addClaim','score');

			$data['claimID'] = $claimID;
			$data['claimTags'] = $this->data_model->getClaimTags($claimID, $this->userid);
		
			$resultsArr = array();
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
			$this->load->view('pages/discussion', $data);
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//company page
	public function company($companyID = -1) {
		$companyID = intval($companyID);
		if ($this->is_logged_in()) {
			$data['signedIn'] = true;
		}			
		if($companyID == -1 || !$this->data_model->getCompany($companyID)) {
			$data['headerTitle'] = 'View Top Companies - PatchWork';
			$data['pageType'] = 'companyBrowse';

			$data['csFiles'] = array('general', 'tooltipster', 'homepage');
			$data['treemapJSON'] = $this->data_model->getJSON("companiesInRange", null);
			
			$this->load->view('templates/header', $data);
			if ($companyID != -1) {
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
				$this->load->view('pages/mainBottom', $data);
			}			
			$this->load->view('pages/treemap', $data);
			$this->load->view('templates/footer');
		}else {
			$data['headerTitle'] = 'View Company - PatchWork';
			$data['pageType'] = 'company';

			$data['csFiles'] = array('general','ccStyles','toggleview','tooltipster');
			$data['jsFiles'] = array('general','ccScripts','score','toggleview');

			$data['companyInfo'] = get_object_vars($this->data_model->getCompany($companyID));
			$data['companyClaims'] = $this->data_model->getCompanyClaims($companyID);
			$data['companyClaimsPos'] = $this->data_model->getCompanyClaimsPos($companyID);
			$data['companyClaimsNeg'] = $this->data_model->getCompanyClaimsNeg($companyID);
			$data['companyTags'] = $this->data_model->getCompanyTags($companyID, $this->userid);
			$data['scoreHistory'] = $this->data_model->getCompanyScoreHistoryJSON($companyID);
			$data['treemapJSON'] = $this->data_model->getJSON("companyTopClaims", $companyID);
			
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
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//tag page
	public function tag($tagID = -1) {
		$tagID = intval($tagID);
		$data['listofclaims'] = $this->data_model->getClaimsWithTag($tagID);

		$data['headerTitle'] = 'View Tag - PatchWork';
		$data['pageType'] = 'tag';

		$data['csFiles'] = array('general','tag','toggleview', 'tooltipster');
		$data['jsFiles'] = array('general','tag','addClaim','toggleview');

		if($tagID == -1 || !$data['listofclaims']) {
			/*
				load data top tags treemap and header here
			*/
			if ($tagID != -1) {
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
				$this->load->view('pages/mainBottom', $data);
				$this->load->view('templates/footer');
			}
			/*
				call treemap and footer here
			*/
		}else {
			if ($this->is_logged_in()) {
				$data['signedIn'] = true;
			}

			$data['tagName'] = $this->data_model->getTag($tagID);
			//$data['treemapJSON'] = $this->data_model->getTopClaimsWithTagJSON($tagID);
			$data['treemapJSON'] = $this->data_model->getJSON("claimsWithTag",$tagID);
			
			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/tagTitle', $data);
			$this->load->view('pages/toggleview', $data);
			$this->load->view('pages/tag', $data);
			$this->load->view('pages/treemap', $data);
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//profile page
	public function profile($userID = -1) {
		$userID = intval($userID);
		//get userdata to check if user is logged in
		$data['userdata'] = $this->session->all_userdata();
		$data['headerTitle'] = 'View profile';
		$data['pageType'] = 'profile';
		
		//files needed
		$data['csFiles'] = array('general','profile','toggleview', 'tooltipster','tag');
		$data['jsFiles'] = array('general','profile','addClaim','toggleview','tag');

		//handle case when no parameter is passed
		if ($userID == -1 || !$this->data_model->getUser($userID)) {
			if ($this->is_logged_in()) {
				//user is logged in, set variable as the userid in session to redirect to their own profile
				$userID = $data['userdata']['userid'];
			} else {
				//if user is not logged in, show not found
				$this->load->view('templates/header', $data);
				$this->load->view('pages/mainTop', $data);
				$this->load->view('pages/notfound', $data);
				$this->load->view('pages/mainBottom', $data);
				$this->load->view('templates/footer');
				return;
			}
		}

		//grab basic data
		$data['curProfile'] = $userID;
		$data['userInfo'] = $this->data_model->getUser($userID);
		$data['listofclaims'] = $this->data_model->getUserClaims($userID);
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
		$this->load->view('pages/tag', $data);
		$this->load->view('pages/profile', $data);
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	public function about() {
		$data['headerTitle'] = 'About - PatchWork';
		$data['pageType'] = 'About';

		$data['csFiles'] = array('general','ccStyles', 'about');
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
