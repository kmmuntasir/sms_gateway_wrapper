<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends UNAUTH_REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function unAuthMethod() {
		$this->restResponse("This method isn't secured by authorization");
	}
}
