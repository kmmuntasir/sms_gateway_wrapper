<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotFound extends REST_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->restResponse(null, "NOT FOUND", "failed", HTTP_NOT_FOUND);
	}
}
