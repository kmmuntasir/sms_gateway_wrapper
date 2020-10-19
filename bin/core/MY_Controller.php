<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()  {
		parent::__construct();
	}
}

class REST_Controller extends MY_Controller {

	public $_auth;
	public $_POST_GLOBAL;
	public $_GET_GLOBAL;

	public function __construct() {
		parent::__construct();

		$this->load->helper(['jwt', 'authorization']);
		$this->prepareMethodData();
		$this->setAuthorization();
	}

	/*
	* Authorization Methods
	*/

	private function setAuthorization() {
		$this->_auth = $this->input->get_request_header('Authorization');
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
		else return $this->restResponse(null, "Unauthorized", "failed", HTTP_UNAUTHORIZED);
	}

	public function __refreshToken() {
		$token = $this->auth();
		$tokenData = $this->__validateToken($token);
		if($tokenData) return $this->__generateToken($tokenData);
		else return $this->restResponse(null, "Unauthorized", "failed", HTTP_UNAUTHORIZED);
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

	public function response($payload, $message=NULL, $status=NULL) {
		if(!$status) $status = "success";
		if(!$message) $message = "success";

		$response = new stdClass();
		$response->status = $status;
		$response->message = $message;
		$response->payload = $payload;

		return $response;
	}

	public function jsonResponse($payload, $message=NULL, $status=NULL) {
		return json_encode($this->response($payload, $message, $status), JSON_PRETTY_PRINT);
	}

	public function restResponse($payload, $message=NULL, $status=NULL, $HTTPstatus=NULL) {
		if(!$HTTPstatus) $HTTPstatus = HTTP_OK;

		$this->output
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_content_type('application/json', 'UTF-8')
			->set_status_header($HTTPstatus)
			->set_output($this->jsonResponse($payload, $message, $status))
			->_display();
		exit();
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
