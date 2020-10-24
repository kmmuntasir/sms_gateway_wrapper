<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotFound extends REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->restResponse(null, MESSAGE_NOT_FOUND, STATUS_FAILED, HTTP_NOT_FOUND);
	}
}
