<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends UNAUTH_REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->model('user_m');
		$user = $this->post();
		$status = $this->user_m->insert($user);
		$this->restResponse($status);
	}
}
