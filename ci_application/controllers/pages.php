<?php
class Pages extends CI_Controller {
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

		$data['csFiles'] = array('general','homepage');
		$data['jsFiles'] = array('homepage');
		//signed in logic goes here

		$this->load->view('templates/header', $data);
		$this->load->view('pages/home', $data);
		$this->load->view('templates/footer');
	}

	//claim page
	public function claim() {
		$data['headerTitle'] = 'View Claim - PatchWork';
		$data['pageTitle'] = 'Claim title goes here';

		//files needed
		$data['csFiles'] = array('general','claim');
		$data['jsFiles'] = array('claim');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/claim', $data);
		$this->load->view('templates/footer');
	}

	//company page
	public function company() {
		$data['headerTitle'] = 'View Company - PatchWork';
		$data['pageTitle'] = 'Company Name goes here';

		$this->load->view('templates/header', $data);
		$this->load->view('pages/company', $data);
		$this->load->view('templates/footer');
	}

	//tag page
	public function tag() {
		$data['headerTitle'] = 'View Tag - PatchWork';
		$data['pageTitle'] = 'Tag name goes here';

		$this->load->view('templates/header', $data);
		$this->load->view('pages/tag', $data);
		$this->load->view('templates/footer');
	}

	//profile page
	public function profile() {
		$data['headerTitle'] = 'User Profile - Patchwork';
		$data['pageTitle'] = 'User name';

		//files needed
		$data['csFiles'] = array('general','profile');
		$data['jsFiles'] = array('profile');

		$this->load->view('templates/header', $data);
		$this->load->view('pages/profile', $data);
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
