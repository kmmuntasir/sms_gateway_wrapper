<?php

class Greenweb
{
	protected $CI;
	protected $tokenKey;
	protected $apiEndpoint;
	protected $mode;

	public function __construct($initializerData=NULL)
	{
		// Assign the CodeIgniter super-object
		$this->CI 			=& get_instance();
		$this->tokenKey 	= $initializerData['tokenKey'];
		$this->apiEndpoint 	= $initializerData['apiEndpoint'];
		$this->mode 		= $initializerData['mode'];
	}

	public function send($request)
	{
		if($this->mode == SMS_SINGLE) {
			$this->sendSingleSms($request);
		}
		else if($this->mode == SMS_MULTIPLE) {
			$this->sendSingleSms($request);
		}
		else if($this->mode == SMS_BROADCAST) {
			$this->sendBroadcastSms($request);
		}

	}

	public function providerResponse($status, $sent, $failed, $success, $fail) {
		$response = new stdClass();
		$response->status = $status;
		$response->sent = $sent;
		$response->failed = $failed;
		$response->success = $success;
		$response->fail = $fail;
		return $response;
	}

	public function sendSingleSms($request) {
		$sms = array(
			'to' 		=> $request->to,
			'message' 	=> $request->message,
			'token' 	=> $this->tokenKey
		);

		$apiResponse = $this->CI->myCurl($this->apiEndpoint, $sms);

		$errors = substr_count($apiResponse, "Error");

		$status = MESSAGE_SEND_FAILED;
		$sent = [];
		$failed = [];
		$success = 0;
		$fail = 0;

		if($errors == 0) {
			$status = MESSAGE_SEND_SUCCESS;
			$sent[] = $request;
			$success = 1;
		} else {
			$failed[] = $request;
			$fail = 1;
		}

		return $this->providerResponse(
			$status,
			$sent,
			$failed,
			$success,
			$fail
		);
	}

	public function sendBroadcastSms($request) {
		$to = $delimiter = "";
		foreach ($request->to as $number) {
			$to .= $delimiter.$number;
			$delimiter = ",";
		}
		$sms = array(
			'to' 		=> $to,
			'message' 	=> $request->message,
			'token' 	=> $this->tokenKey
		);

		$apiResponse = $this->CI->myCurl($this->apiEndpoint, $sms);

		$sanitizedResponse = $this->getSanitizedResponseForBroadcastSms($apiResponse);

		$status = MESSAGE_SEND_FAILED;

		if(count($sanitizedResponse->sent) == 0) {
			$status = MESSAGE_SEND_FAILED;
		} else if(count($sanitizedResponse->failed) == 0) {
			$status = MESSAGE_SEND_SUCCESS;
		}
		else {
			$status = MESSAGE_SEND_PARTIAL_SUCCESS;
		}

		return $this->providerResponse(
			$status,
			$sanitizedResponse->sent,
			$sanitizedResponse->failed,
			count($sanitizedResponse->sent),
			count($sanitizedResponse->failed)
		);
	}

	public function getSanitizedResponseForBroadcastSms($apiResponse) {

		$apiResponse = str_replace("\n", "", $apiResponse);
		$apiResponse = str_replace(":", "", $apiResponse);
		$apiResponse = str_replace("Invalid Number", "", $apiResponse);
		$apiResponse = str_replace("SMS Added for Sending Successfully", "", $apiResponse);

		$sent = explode("!", $apiResponse);

		$failed = [];

		foreach ($sent as $key => $result) {
			if($result == "") unset($sent[$key]);
			else if(strpos($result, "E") === 0) {
				$result = str_replace("Error", "", $result);
				$result = trim($result);
				$failed[] = $result;
				unset($sent[$key]);
			}
			else $sent[$key] = trim($sent[$key]);
		}
		$sent = array_values($sent);
		$failed = array_values($failed);

		$sanitizedResponse = new stdClass();
		$sanitizedResponse->sent = $sent;
		$sanitizedResponse->failed = $failed;

		return $sanitizedResponse;
	}

}
