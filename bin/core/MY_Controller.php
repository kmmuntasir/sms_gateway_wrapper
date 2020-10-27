<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()  {
		parent::__construct();

		// Setting Timezone
		date_default_timezone_set("Asia/Dhaka");
	}
}

class REST_Controller extends MY_Controller {

	public $_auth;
	public $_POST_GLOBAL;
	public $_GET_GLOBAL;

	public function __construct() {
		parent::__construct();

		$this->load->helper([HELPER_JWT, HELPER_AUTHORIZATION]);
		$this->prepareMethodData();
		$this->setAuthorization();
	}

	/*
	* Authorization Methods
	*/

	private function setAuthorization() {
		$this->_auth = $this->input->get_request_header(HEADER_AUTHORIZATION);
		$this->_auth = substr($this->_auth, 7);
	}

	public function auth() {
		return $this->_auth;
	}

	public function __generateToken($data) {
		$tokenData = array();
		$tokenData['id'] = $data;
		$tokenData['timestamp'] = time();
		return AUTHORIZATION::generateToken($tokenData);

	}

	public function __validateToken($token) {
		try {
			if($this->config->item('jwt_expiration') === true) {
				$tokenData = AUTHORIZATION::validateTimestamp($token);
			}
			else {
				$tokenData = AUTHORIZATION::validateToken($token);
			}
			if($tokenData) return $tokenData->id;
			return false;
		} catch (Exception $e) {
			return false;
		}
	}

	public function __authorizeToken() {
		$token = $this->auth();
		$tokenData = $this->__validateToken($token);
		if($tokenData) return $tokenData;
		else return $this->restResponse(null, MESSAGE_UNAUTHORIZED, STATUS_FAILED, HTTP_UNAUTHORIZED);
	}

	public function __refreshToken() {
		$token = $this->auth();
		$tokenData = $this->__validateToken($token);
		if($tokenData) return $this->__generateToken($tokenData);
		else return $this->restResponse(null, MESSAGE_UNAUTHORIZED, STATUS_FAILED, HTTP_UNAUTHORIZED);
	}

	/*
	* Data Methods
	*/

	private function prepareMethodData() {
		$this->_POST_GLOBAL = json_decode($this->input->raw_input_stream);
		$this->_GET_GLOBAL = (object) $_GET;
	}

	public function input($method, $key=NULL, $value=NULL) {
		if(($method == GET || $method == DELETE)) {
			$_dataObj = &$this->_GET_GLOBAL;
		}
		else $_dataObj = &$this->_POST_GLOBAL;

		if($key) {
			if(is_array($key)) { // Return multiple key values
				$result = new stdClass();
				foreach ($key as $k) {
					$result->$k = $this->input($method, $k);
				}
				return $result;
			}
			else if(is_object($key)) { // Overwrite object with parameter data
				$_dataObj = $key;
			}
			else if($value) { // Update single key with single value
				$_dataObj->$key = $value;
			}
			else return isset($_dataObj->$key) ? $_dataObj->$key : null; // Return the value of the single key
			return true;
		}
		else return $_dataObj; // Return whole object
	}

	public function get($key=NULL, $value=NULL) {
		return $this->input(GET, $key, $value);
	}

	public function post($key=NULL, $value=NULL) {
		return $this->input(POST, $key, $value);
	}

	public function put($key=NULL, $value=NULL) {
		return $this->input(PUT, $key, $value);
	}

	public function patch($key=NULL, $value=NULL) {
		return $this->input(PATCH, $key, $value);
	}

	public function delete($key=NULL, $value=NULL) {
		return $this->input(DELETE, $key, $value);
	}

	 /*
	 * Response Methods
	 */

	public function toJson($data) {
		return json_encode($data, JSON_PRETTY_PRINT);
	}

	public function respond($data, $httpStatus=HTTP_OK) {
		$this->output
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_content_type('application/json', 'UTF-8')
			->set_status_header($httpStatus)
			->set_output($data)
			->_display();
		exit();
	}

	public function response($payload, $message=NULL, $status=NULL) {
		if(!$status) $status = STATUS_SUCCESS;
		if(!$message) $message = MESSAGE_SUCCESS;

		$response = new stdClass();
		$response->status = $status;
		$response->message = $message;
		$response->payload = $payload;

		return $response;
	}

	public function jsonResponse($payload, $message=NULL, $status=NULL) {
		return $this->toJson($this->response($payload, $message, $status));
	}

	public function restResponse($payload, $message=NULL, $status=NULL, $httpStatus=HTTP_OK) {
		$this->respond($this->jsonResponse($payload, $message, $status), $httpStatus);
	}

	/*
	* Utility Methods
	*/

	public function __validate($obj, $fieldList) {
		/**
			__required() is a custom function that checks if the provided array (param1) has all the fields in it (param2)
			It is used to check whether a post request has the required fields
			Param1 = An array which is to be checked
			Param2 = An array containing the field list
		**/
		foreach ($fieldList as $key => $field) {
			if(!(isset($obj->$field) && !empty($obj->$field))) return false;
		}
		return true;
	}

	public function __uniqueCode($length=8, $keyspace = '0123456789@#$%&abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		/**
		Generate a random string
		@param int $length      How many characters do we want?
		@param string $keyspace A string of all possible characters to select from
		@return string
		 **/
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}

	public function printer($data, $exit_flag = true) {
		$data = $this->toJson($data);
		if($exit_flag) {
			$this->respond($data);
		}
		else echo("\n".$data."\n");
	}

	public function myCurl($url, $data) {
		$ch = curl_init(); // Initialize cURL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec( $ch );
	}

	public function swap(&$x, &$y) {
		$temp = $x;
		$x = $y;
		$y = $temp;
	}

	public function now($dateonly = false, $timeonly = false)
	{
		if ($dateonly) return date('Y-m-d');
		elseif ($timeonly) return date('H:i:s');
		else return date('Y-m-d H:i:s');
	}

	public function isTime($time) {
		$parts = explode(':', $time);
		$h = $parts[0];
		$m = $parts[1];
		$s = $parts[2];

		if(($h >= 0 && $h <= 23) && ($m >= 0 && $m <= 59) && ($s >= 0 && $s <= 59)) return true;
		else return false;
	}

	public function isDate($date) {
		$dt = explode("-", $date);
		if(count($dt) == 3) {
			$y = $dt[0] * 1;
			$m = $dt[1] * 1;
			$d = $dt[2] * 1;
			return checkdate($m, $d, $y);
		}
		else return false;
	}

	public function isExpired($datetime) {
		if(!$this->isDateTime($datetime)) return true;
		$now = strtotime($this->now());
		$candidate = strtotime($datetime);
		if($now > $candidate) return true;
		else return false;

	}

	public function isDateTime($datetime) {
		$parts = explode(" ", $datetime);
		if(count($parts) == 2) {
			$dt = explode("-", $parts[0]);
			if(count($dt) == 3) {
				$y = $dt[0] * 1;
				$m = $dt[1] * 1;
				$d = $dt[2] * 1;
				if(checkdate($m, $d, $y) && $this->isTime($parts[1]) ) return true;
				else return false;
			}
			else return false;
		}
		else return false;
	}
}

class AUTH_REST_Controller extends REST_Controller {

	public function __construct()  {
		parent::__construct();
		$this->__authorizeToken();
	}
}

class UNAUTH_REST_Controller extends REST_Controller {

	public function __construct()  {
		parent::__construct();

	}
}

class SMS_REST_Controller extends REST_Controller {

	public function __construct()  {
		parent::__construct();
	}

	/*
	 * API Related Methods
	 */

	public function providerResponse($sent, $failed, $success, $fail, $apiResponse) {
		$response = new stdClass();
		$response->sent = $sent;
		$response->failed = $failed;
		$response->success = $success;
		$response->fail = $fail;
		$response->apiResponse = $apiResponse;
		return $response;
	}

	public function getApiTypeId($method, $excludeInfo=false) {
		$api_type_ids = API_TYPE_IDS;
		if(isset($api_type_ids[$method])) {
			if($excludeInfo && $method == API_TYPE_INFO) {
				return null;
			}
			return $api_type_ids[$method];
		}
		else return null;
	}

	public function getValidApiTypeId($method, $excludeInfo=false) {
		$apiTypeId = $this->getApiTypeId($method, true);

		if($apiTypeId == NULL) {

			$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
		}
		return $apiTypeId;
	}

	public function convertProviderToken($token) {
		$token->token_status_id = $token->provider_token_status_id;
		$token->token_rate = $token->provider_token_rate;
		$token->token_balance = $token->provider_token_balance;
		$token->token_expiry = $token->provider_token_expiry;
		return $token;
	}

	public function validateTokenStatus($token, $providerToken=false) {
		if($token == NULL) {

			$this->restResponse(null, MESSAGE_INVALID_TOKEN, STATUS_FAILED, HTTP_NOT_FOUND);
		}
		$tempToken = (object)((array)$token);
		if($providerToken) $tempToken = $this->convertProviderToken($tempToken);
		if($tempToken->token_status_id != STATUS_ACTIVE) {
			if($providerToken) {
				$this->restResponse(null, MESSAGE_SYSTEM_ERROR, STATUS_FAILED, HTTP_INTERNAL_SERVER_ERROR);
			}
			else {
				if($tempToken->token_status_id == STATUS_LOCKED) {
					$this->restResponse(null, MESSAGE_LOCKED_TOKEN, STATUS_FAILED, HTTP_UNAUTHORIZED);
				}
				else $this->restResponse(null, MESSAGE_INACTIVE_TOKEN, STATUS_FAILED, HTTP_UNAUTHORIZED);
			}
		}
	}

	public function validateTokenBalance($token, $numberOfSms=1, $providerToken=false) {
		$tempToken = (object)((array)$token);
		if($providerToken) $tempToken = $this->convertProviderToken($tempToken);
		if(($tempToken->token_rate * $numberOfSms) > $tempToken->token_balance) {
			if($providerToken) {
				$this->restResponse(null, MESSAGE_SYSTEM_ERROR, STATUS_FAILED, HTTP_INTERNAL_SERVER_ERROR);
			}
			else $this->restResponse(null, MESSAGE_INSUFFICIENT_BALANCE, STATUS_FAILED, HTTP_UNAUTHORIZED);
		}
	}

	public function validateTokenExpiry($token, $providerToken=false) {
		$tempToken = (object)((array)$token);
		if($providerToken) $tempToken = $this->convertProviderToken($tempToken);
		if($this->isExpired($tempToken->token_expiry)) {
			if($providerToken) {
				$this->restResponse(null, MESSAGE_SYSTEM_ERROR, STATUS_FAILED, HTTP_INTERNAL_SERVER_ERROR);
			}
			else $this->restResponse(null, MESSAGE_EXPIRED_TOKEN, STATUS_FAILED, HTTP_UNAUTHORIZED);
		}
	}

	public function validateSingleNumber($to) {

		return (is_string($to) && strlen($to) > 0 && substr_count($to, ',') == 0) ? true : false;
	}

	public function validateTokenFormat($token) {

		return (is_string($token) && strlen($token) == 32) ? true : false;
	}

	public function validateMessageFormat($message, $ignoreLength=false) {
		if(!(is_string($message) && strlen($message) > 0)) return false;
		if(!$ignoreLength && strlen($message) > MAX_MESSAGE_LENGTH) return false;
		return true;
	}

	public function validateRequestModelForInfo($request) {

		if(!(isset($request->token) && $this->validateTokenFormat($request->token))) {

			$this->restResponse(MESSAGE_NOT_FOUND, MESSAGE_INVALID_TOKEN, STATUS_FAILED, HTTP_NOT_FOUND);
		}
	}

	public function validateRequestModelForSmsMode($request, $mode) {
		if(!(isset($request->token) && $this->validateTokenFormat($request->token))) {

			$this->restResponse(null, MESSAGE_INVALID_TOKEN, STATUS_FAILED, HTTP_NOT_FOUND);
		}
		if($mode == SMS_SINGLE) {
			if(!$this->__validate($request, REQUEST_SINGLE_SMS)) {

				$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
			}
			if(!$this->validateSingleNumber($request->to)) {

				$this->restResponse(null, MESSAGE_SINGLE_SMS_ONLY, STATUS_FAILED, HTTP_BAD_REQUEST);
			}
			if(!$this->validateMessageFormat($request->message)) {

				$this->restResponse(null, MESSAGE_LENGTH_TOO_LARGE, STATUS_FAILED, HTTP_UNAUTHORIZED);
			}
		}
		else if($mode == SMS_MULTIPLE) {
			if(!($this->__validate($request, REQUEST_MULTIPLE_SMS) && is_array($request->smsData))) {

				$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
			}
			if(count($request->smsData) > MAX_NUMBERS) {

				$this->restResponse(null, MESSAGE_PAYLOAD_TOO_LARGE, STATUS_FAILED, HTTP_UNAUTHORIZED);
			}
			foreach ($request->smsData as $sms) {
				if(!($this->__validate($sms, REQUEST_SMS_DATA) && $this->validateSingleNumber($sms->to) && $this->validateMessageFormat($sms->message, true))) {

					$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
				}
//				Currently we will not check for large payload in multiple message, it will automatically fail
//				if($this->validateMessageFormat($sms->message)) {
//
//					$this->restResponse(null, MESSAGE_LENGTH_TOO_LARGE, STATUS_FAILED, HTTP_UNAUTHORIZED);
//				}
			}
		}
		else if($mode == SMS_BROADCAST) {
			if(!($this->__validate($request, REQUEST_BROADCAST_SMS) && is_array($request->to))) {

				$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
			}
			if(count($request->to) > MAX_NUMBERS) {

				$this->restResponse(null, MESSAGE_PAYLOAD_TOO_LARGE, STATUS_FAILED, HTTP_UNAUTHORIZED);
			}
			foreach ($request->to as $number) {
				if(!$this->validateSingleNumber($number)) {

					$this->restResponse(null, MESSAGE_BAD_DATA_FORMAT, STATUS_FAILED, HTTP_BAD_REQUEST);
				}
			}
			if(!$this->validateMessageFormat($request->message)) {

				$this->restResponse(null, MESSAGE_LENGTH_TOO_LARGE, STATUS_FAILED, HTTP_UNAUTHORIZED);
			}
		}
	}

	public function sanitizeBroadcastNumberList($request) {
		$request->to = array_unique($request->to);
		return $request;
	}

	public function getInitializerForProviderLibrary($token) {
		$initializerData = array();
		$initializerData['tokenKey'] = $token->provider_token_key;
		$initializerData['apiEndpoint'] = $token->api_endpoint;
		$initializerData['mode'] = $token->smsMode;

		return $initializerData;
	}
}
