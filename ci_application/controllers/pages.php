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

		$data['csFiles'] = array('general','ccStyles','addClaim','treemap');
		$data['jsFiles'] = array('general','ccScripts','addClaim');
		$data['topCompaniesWithClaimsJSON'] = $this->data_model->getTopCompaniesWithClaimsJSON();
		//signed in logic goes here

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/addClaim', $data);
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('pages/treemap', $data);
		$this->load->view('templates/footer');
	}

	//claim page
	public function claim($claimID = -1) {
		if($claimID == -1) {
			$this->homepage(); //!! change to TreemapSearch later
		}else {
			$data['headerTitle'] = 'View Claim - PatchWork';
			$data['pageType'] = 'claim';

			$data['claimInfo'] = get_object_vars($this->data_model->getClaim($claimID));
			$data['claimTags'] = $this->data_model->getClaimTags($claimID, $this->userid);

			$resultsArr = [];
			$data['comments'] = $this->data_model->getDiscussion($claimID, 0, 0, $resultsArr, $this->userid);
			$data['scores'] = $this->data_model->getClaimScores($claimID);
			
			//files needed
			$data['csFiles'] = array('general','ccStyles', 'tooltipster');
			$data['jsFiles'] = array('general','ccScripts');

			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/ccTop', $data);
			$this->load->view('pages/evidence', $data);
			$this->load->view('pages/scoreTop', $data);
			$this->load->view('pages/score', $data);
			$this->load->view('pages/scoreBottom', $data);
			$this->load->view('pages/discussion', $data);
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//company page
	public function company($companyID = -1) {
		if($companyID == -1) {
			$this->homepage();
		}else {
			//grab basic data
			$data['companyInfo'] = get_object_vars($this->data_model->getCompany($companyID));
			$data['companyClaims'] = $this->data_model->getCompanyClaims($companyID);
			$data['companyTags'] = $this->data_model->getCompanyTags($companyID, $this->userid);
			$data['topClaimsForCompanyJSON'] = $this->data_model->getClaimsForCompanyJSON($companyID);
			
			$data['headerTitle'] = 'View Company - PatchWork';
			$data['pageType'] = 'company';

			$data['csFiles'] = array('general','ccStyles','treemap');
			$data['jsFiles'] = array('general','ccScripts');
			
			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/ccTop', $data);
			$this->load->view('pages/scoreTop', $data);
			$this->load->view('pages/score', $data);
			$this->load->view('pages/scoreBottom', $data);
			$this->load->view('pages/highlowClaims', $data);
			$this->load->view('pages/treemap', $data);
			$this->load->view('pages/mainBottom', $data);
			$this->load->view('templates/footer');
		}
	}

	//tag page
	public function tag($tagID = -1) {
		if($tagID == -1) {
			$this->homepage(); //!! change to TreemapSearch later
		}else {

			$data['headerTitle'] = 'View Tag - PatchWork';
			$data['pageType'] = 'tag';
			
			$data['tagInfo'] = $this->data_model->getClaimsWithTag($tagID);
			$data['topClaimsWithTagJSON'] = $this->data_model->getTopClaimsWithTagJSON($tagID);
			
			$data['csFiles'] = array('general','tag');
			$data['jsFiles'] = array('general','tag');

			$this->load->view('templates/header', $data);
			$this->load->view('pages/mainTop', $data);
			$this->load->view('pages/ccTop', $data);
			$this->load->view('pages/tag', $data);
			$this->load->view('pages/treemap', $data);
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
			if (!isset($data['userdata']['userid'])) {
				//if user is not logged in, redirect
				$this->homepage();
				return;
			} else {
				//user is logged in, set variable as the userid in session and continue as normal
				$userID = $data['userdata']['userid'];
			}
		}

		//grab basic data
		$data['curProfile'] = $userID;
		$data['userInfo'] = get_object_vars($this->data_model->getUser($userID));
		$data['userClaims'] = $this->data_model->getUserClaims($userID);
		$data['userComments'] = $this->data_model->getUserComments($userID);
		$data['userVotes'] = $this->data_model->getUserVotes($userID);

		$data['headerTitle'] = 'User Profile - Patchwork';

		//files needed
		$data['csFiles'] = array('general','profile');
		$data['jsFiles'] = array('general','profile');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/profile', $data);
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');		
	}

	public function about() {
		$data['headerTitle'] = 'About - PatchWork';
		$data['pageType'] = 'About';

		$data['csFiles'] = array('general','ccStyles');
		$data['jsFiles'] = array('general','ccScripts');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/about');
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	public function team() {
		$data['headerTitle'] = 'Team - PatchWork';
		$data['pageType'] = 'Team';

		$data['csFiles'] = array('general','ccStyles', 'team');
		$data['jsFiles'] = array('general','ccScripts');

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
		$data['jsFiles'] = array('general','ccScripts');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/mainTop', $data);
		$this->load->view('pages/faq');
		$this->load->view('pages/mainBottom', $data);
		$this->load->view('templates/footer');
	}

	/*
	public function view($page = 'home') {
		if ( ! file_exists('ci_application/views/pages/'.$page.'.php')) {
			// Whoops, we don't have a page for that!
			show_404();
		}
		
		//url helper
		$this->load->helper('url');

		// Capitalize the first letter
		$data['headerTitle'] = ucfirst($page) . ' - patchwork';
		$data['pageTitle'] = ucfirst($page);
		
		//load views
		$this->load->view('templates/header', $data);
		$this->load->view('pages/'. $page, $data);
		$this->load->view('templates/footer', $data);
	}
	*/
}
