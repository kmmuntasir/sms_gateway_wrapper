<?php

class Greenweb
{
	protected $CI;
	protected $apiType;
	protected $tokenKey;
	protected $apiEndpoint;

	public function __construct($token=NULL)
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		$this->apiType = $token['apiType'];
		$this->tokenKey = $token['provider_token_key'];
		$this->apiEndpoint = $token['api_endpoint'];
	}

	public function send($sms)
	{
		if($this->apiType == API_TYPE_SYNC)
			return $this->sendSingleSms($sms);
		else if($this->apiType == API_TYPE_ASYNC)
			return $this->sendSingleSms($sms);

	}

	public function sendSingleSms($sms) {
		$sms = (array) $sms;
		$sms['token']   = $this->tokenKey;

		$response = $this->CI->myCurl($this->apiEndpoint, $sms);

		$errors = substr_count($response, "Error");

		return ($errors == 0) ? true : false;
	}

}
