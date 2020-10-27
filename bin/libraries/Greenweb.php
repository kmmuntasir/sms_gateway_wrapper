<?php

define("GREENWEB_RESPONSE", 	array("to", "message", "status", "statusmsg"));
define("GREENWEB_SUCCESS_STATUS", "SENT");
define("GREENWEB_FAILED_STATUS", "FAILED");

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
			return $this->sendSingleSms($request);
		}
		else if($this->mode == SMS_MULTIPLE) {
			return $this->sendMultipleSms($request);
		}
		else if($this->mode == SMS_BROADCAST) {
			return $this->sendBroadcastSms($request);
		}

	}

	public function sendSingleSms($request) {
		$sms = array(
			'to' 		=> $request->to,
			'message' 	=> $request->message,
			'token' 	=> $this->tokenKey
		);

		$apiResponse = json_decode($this->CI->myCurl($this->apiEndpoint, $sms));

		if(!(is_array($apiResponse) && $this->CI->__validate($apiResponse[0], GREENWEB_RESPONSE))) {

			$this->CI->restResponse($apiResponse, MESSAGE_SYSTEM_ERROR, STATUS_FAILED, HTTP_INTERNAL_SERVER_ERROR);
		}

		$sent = [];
		$failed = [];
		$success = 0;
		$fail = 0;

		if($apiResponse[0]->status == GREENWEB_SUCCESS_STATUS) {
			$sent[] = $apiResponse[0];
			$success = 1;
		} else {
			$failed[] = $apiResponse[0];
			$fail = 1;
		}

		return $this->CI->providerResponse(
			$sent,
			$failed,
			$success,
			$fail,
			$apiResponse[0]
		);
	}

	public function sendMultipleSms($request) {
		$sms = array(
			'token' 	=> $this->tokenKey,
			'smsdata' 	=> json_encode($request->smsData)
		);

		$apiResponse = json_decode($this->CI->myCurl($this->apiEndpoint, $sms));

		return $this->getSanitizedResponseForSms($apiResponse);
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

		$apiResponse = json_decode($this->CI->myCurl($this->apiEndpoint, $sms));

		return $this->getSanitizedResponseForSms($apiResponse);
	}

	public function getSanitizedResponseForSms($apiResponse) {

		if(!is_array($apiResponse)) {

			$this->CI->restResponse(null, MESSAGE_SYSTEM_ERROR, STATUS_FAILED, HTTP_INTERNAL_SERVER_ERROR);
		}

		$sent = $failed = [];

		foreach ($apiResponse as $key => $response) {
			if($response->status == GREENWEB_SUCCESS_STATUS) {
				$sent[] = $response;
			} else {
				$failed[] = $response;
			}
		}

		return $this->CI->providerResponse(
			$sent,
			$failed,
			count($sent),
			count($failed),
			$apiResponse
		);
	}

}
