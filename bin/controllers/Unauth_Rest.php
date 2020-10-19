<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unauth_Rest extends UNAUTH_REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function generateRandomToken() {
		$token = $this->__generateToken(rand(10000, 300000));

		$this->restResponse($token);
	}

	public function unAuthMethod() {
		$this->restResponse("This method isn't secured by authorization");
	}
}
