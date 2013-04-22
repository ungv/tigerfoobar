<?php

class Pages extends CI_Controller {

	public function view($page = 'home') {
		if ( ! file_exists('ci_application/views/pages/'.$page.'.php')) {
			// Whoops, we don't have a page for that!
			show_404();
		}
		
		//url helper
		$this->load->helper('url');

		// Capitalize the first letter
		$data['headerTitle'] = ucfirst($page);

		// Capitalize the first letter
		$data['pageTitle'] = ucfirst($page);
		
		//load views
		$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer', $data);

	}
}
