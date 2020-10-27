<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends SMS_REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('token_m');
		$this->load->model('sms_m');
	}

	public function index() {

		$this->restResponse(null, MESSAGE_UNAUTHORIZED, STATUS_FAILED, HTTP_UNAUTHORIZED);
	}

	public function single() {
		$this->send(SMS_SINGLE);
	}

	public function multiple() {
		$this->send(SMS_MULTIPLE);
	}

	public function broadcast() {
		$this->send(SMS_BROADCAST);
	}

	public function send($mode=SMS_SINGLE) {
		$numberOfSms = 1;
		$request = $this->post();

//		validate the request for particular sms mode
		$this->validateRequestModelForSmsMode($request, $mode);
		if($mode == SMS_BROADCAST) $numberOfSms = count($request->to);
		else if($mode == SMS_MULTIPLE) $numberOfSms = count($request->smsData);

//		remove duplicate numbers from number list if the sms mode is broadcast
		if($mode == SMS_BROADCAST) $this->sanitizeBroadcastNumberList($request);

//		detect or set the request method (sync or async) and get api type id
		if(!($mode == SMS_SINGLE && isset($request->method))) $request->method = API_TYPE_ASYNC;
		$apiTypeId = $this->getValidApiTypeId($request->method, true);

//		retrieve the token data
		$token = $this->token_m->retrieveToken($request->token, $apiTypeId);

//		validate the token status
		$this->validateTokenStatus($token);
		$this->validateTokenStatus($token, true);

//		validate the token balance (consider the number of sms if not single)
		$this->validateTokenBalance($token, $numberOfSms);
		$this->validateTokenBalance($token, $numberOfSms, true);

//		validate the token expiry
		$this->validateTokenExpiry($token);
		$this->validateTokenExpiry($token, true);

//		prepare the library initializer data
		$token->smsMode = $mode;
//		$this->printer($token);
		$initializerData = $this->getInitializerForProviderLibrary($token);
//		$this->printer($initializerData);

//		load provider library with provider and token settings with sms mode
		$this->load->library($token->provider_library, $initializerData, 'provider');

//		lock the client token
//		$this->sms_m->lockToken($token->token_id);

//		sanitize the request data
		unset($request->token);
		if(isset($request->method)) unset($request->method);

//		call the send method of provider library and pass the $request as argument
		$response = $this->provider->send($request);

		if($response->success > 0) {
//		update the client and provider token balance with the new balance, which is (balance - rate * succeeded_sms)
			$this->sms_m->executePostSuccessOperations($response->success, $token);
		}

//		unlock the client token, no matter what
		$this->sms_m->unlockToken($token->token_id);

		if($response->success > 0) {
//		insert succeeded sms list in database
			$allSms = [];
			foreach ($response->sent as $sms) {
				$dbSms = array();
				$dbSms['sms_phone'] = $sms->to;
				$dbSms['sms_text'] = $sms->message;
				$dbSms['sms_datetime'] = $this->now();
				$dbSms['sms_status_id'] = SMS_STATUS_SENT;
				$dbSms['client_id'] = $token->client_id;
				$dbSms['token_id'] = $token->token_id;
				$dbSms['token_rate'] = $token->token_rate;
				$dbSms['provider_token_id'] = $token->provider_token_id;
				$dbSms['provider_token_rate'] = $token->provider_token_rate;

				$allSms[] = $dbSms;
			}

			if(count($allSms) > 0) $this->sms_m->insertAllSms($allSms);
		}

		$success = $response->success;
		$fail = $response->fail;

		if($mode == SMS_SINGLE) {
			unset($response->sent);
			unset($response->success);
			unset($response->failed);
			unset($response->fail);
		}
		else if($mode == SMS_MULTIPLE) {
			unset($response->sent);
			unset($response->failed);
		}
		else if($mode == SMS_BROADCAST) {
			unset($response->sent);
			unset($response->failed);
		}

		// if number of succeeded message is 0, then display failed message
		if($success == 0) {

			$this->restResponse($response, MESSAGE_SEND_FAILED, STATUS_FAILED, HTTP_FORBIDDEN);
		}
		// number of succeeded message is greater than 0, now if number of failed message is 0, then display success message
		else if($fail == 0) {

			$this->restResponse($response, MESSAGE_SEND_SUCCESS);
		}
		// Success and Fail both are non-zero values, so partial success message should be displayed
		else {

			$this->restResponse($response, MESSAGE_SEND_PARTIAL_SUCCESS, STATUS_PARTIAL_SUCCESS, HTTP_OK);
		}

	}
}
