<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends SMS_REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('token_m');
	}

	public function index() {

		$request = $this->post();

		$this->validateRequestModelForInfo($request);

		$token = $this->token_m->getTokenInfo($request->token);

		if($token == NULL) {
			$this->restResponse(MESSAGE_NOT_FOUND, MESSAGE_INVALID_TOKEN, STATUS_FAILED, HTTP_NOT_FOUND);
		}

		$tokenInfoResponse = $this->getTokenInfoResponse($token);

		$this->restResponse($tokenInfoResponse, MESSAGE_TOKEN_INFO_CURRENCY_DISCLAIMER, STATUS_SUCCESS, HTTP_OK);
	}

	public function getTokenInfoResponse($token) {
		$tokenInfoResponse = new stdClass();
		$tokenInfoResponse->rate 	= $token->token_rate;
		$tokenInfoResponse->balance = $token->token_balance;
		$tokenInfoResponse->expiry 	= $token->token_expiry;

		return $tokenInfoResponse;
	}
}
