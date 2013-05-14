<?php
class Root_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //Helpers
		$this->load->helper('url');
		$this->load->library('session');
    }

    public function is_logged_in()
    {
        $user = $this->session->userdata('userid');
        //shoudln't have to check this much, 
        //make sure users can clear session array on session end
        return isset($user) && $user != '';
    }
}