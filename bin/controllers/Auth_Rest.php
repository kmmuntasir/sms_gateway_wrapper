<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Rest extends AUTH_REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$payload = array("foo" => "bar", "hello" => "jello");


//		$successMessage = "This is a success message";
		$dangerMessage = "This is a danger message";
//		$unauthMessage = "This is an unauth message";

//		$this->restResponse($payload);
		$this->restResponse($payload, $dangerMessage, "failed");
//		$this->restResponse($payload, $unauthMessage, "unauthorized", HTTP_UNAUTHORIZED);
	}

	public function methodTest() {

//		$this->patch("email", "user@sample.com");
//		$this->restResponse($this->patch(array('email', 'password')), "success", "success", HTTP_OK);
		$this->restResponse($this->post(), "success", "success", HTTP_OK);
	}

	public function tokenTest() {

		$tokenData = $this->__authorizeToken();

		$this->restResponse($tokenData, "success", "success", HTTP_OK);
	}

	public function refreshToken() {
		$token = $this->__refreshToken();
		$this->restResponse($token);
	}
}
