<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends UNAUTH_REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('token_m');
		$this->load->model('sms_m');
	}

	public function index() {

		$request = $this->post();

		if(!$this->__validate($request, REQUEST_SINGLE_SMS)) {

			$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
		}

		if(!isset($request->method)) $request->method = API_TYPE_ASYNC;

		if(!$this->validateSingleNumber($request->to)) {

			$this->restResponse(null, MESSAGE_SINGLE_SMS_ONLY, STATUS_FAILED, HTTP_BAD_REQUEST);
		}

		$apiTypeId = $this->getApiTypeId($request->method, true);

		if($apiTypeId == NULL) {

			$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
		}

		$token = $this->token_m->retrieveToken($request->token, $apiTypeId);
		if($token == NULL) {

			$this->restResponse(null, MESSAGE_INVALID_TOKEN, STATUS_FAILED, HTTP_NOT_FOUND);
		}
		$this->validateTokenStatus($token);
		$this->validateTokenBalance($token);
		$this->validateTokenExpiry($token);
		$this->validateTokenStatus($token, true);
		$this->validateTokenBalance($token, true);
		$this->validateTokenExpiry($token, true);

		$token->apiType = $request->method;
		$token = (array) $token;
//		$this->restResponse($token);

		// load provider library with provider and provider_token settings
		$this->load->library($token['provider_library'], $token, 'provider');

		// prepare sms data
		$sms = new stdClass();
		$sms->to = $request->to;
		$sms->message = $request->message;

		// call the send method of the provider library
		$status = $this->provider->send($sms);

		if($status) {
			$dbSms = array();
			$dbSms['sms_phone'] = $sms->to;
			$dbSms['sms_text'] = $sms->message;
			$dbSms['sms_datetime'] = $this->now();
			$dbSms['sms_status_id'] = SMS_STATUS_SENT;
			$dbSms['client_id'] = $token['client_id'];
			$dbSms['token_id'] = $token['token_id'];
			$dbSms['provider_token_id'] = $token['provider_token_id'];

			// Insert SMS record in database
			// Update both client token and provider token balance
			$this->sms_m->executePostSingleSuccessOperations($dbSms, $token);
			$this->restResponse(null, MESSAGE_SUCCESS);
		}
		else {
			$this->restResponse(null, MESSAGE_SEND_FAILED, STATUS_FAILED, HTTP_FORBIDDEN);
		}
	}
}
