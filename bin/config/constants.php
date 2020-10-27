<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
|--------------------------------------------------------------------------
| HTTP Status Codes
|--------------------------------------------------------------------------
|
*/
defined('HTTP_OK')        			OR define('HTTP_OK', 200);
defined('HTTP_CREATED')				OR define('HTTP_CREATED', 201);
defined('HTTP_BAD_REQUEST')			OR define('HTTP_BAD_REQUEST', 400);
defined('HTTP_UNAUTHORIZED')			OR define('HTTP_UNAUTHORIZED', 401);
defined('HTTP_FORBIDDEN')				OR define('HTTP_FORBIDDEN', 403);
defined('HTTP_NOT_FOUND')				OR define('HTTP_NOT_FOUND', 404);
defined('HTTP_INTERNAL_SERVER_ERROR')	OR define('HTTP_INTERNAL_SERVER_ERROR', 500);

/*
|--------------------------------------------------------------------------
| HTTP Methods
|--------------------------------------------------------------------------
|
*/
defined('GET')        	OR define('GET', "GET");
defined('POST')			OR define('POST', "POST");
defined('PUT')			OR define('PUT', "PUT");
defined('PATCH')			OR define('PATCH', "PATCH");
defined('DELETE')			OR define('DELETE', "DELETE");

/*
|--------------------------------------------------------------------------
| REST Controller Constants
|--------------------------------------------------------------------------
|
*/
define('HELPER_JWT', "jwt");
define('HELPER_AUTHORIZATION', "authorization");

define('HEADER_AUTHORIZATION', "Authorization");

/*
|--------------------------------------------------------------------------
| Business Decision Constants
|--------------------------------------------------------------------------
|
*/
define('MAX_NUMBERS', 500);
define('MAX_MESSAGE_LENGTH', 700);

/*
|--------------------------------------------------------------------------
| Status ID Constants
|--------------------------------------------------------------------------
|
*/
define('STATUS_ACTIVE', 1);
define('STATUS_DEACTIVE', 2);
define('STATUS_DELETED', 3);
define('STATUS_LOCKED', 4);

/*
|--------------------------------------------------------------------------
| API Type ID Constants
|--------------------------------------------------------------------------
|
*/

define('API_TYPE_IDS', array(
	'sync' 	=> 1,
	'async' => 2,
	'info' 	=> 3,
));

define('API_TYPE_SYNC', 'sync');
define('API_TYPE_ASYNC', 'async');
define('API_TYPE_INFO', 'info');

/*
|--------------------------------------------------------------------------
| Response Text Constants
|--------------------------------------------------------------------------
|
*/
define('MESSAGE_UNAUTHORIZED', "Unauthorized");
define('MESSAGE_NOT_FOUND', "NOT FOUND");
define('MESSAGE_INVALID_TOKEN', "Invalid Token");
define('MESSAGE_INACTIVE_TOKEN', "Inactive Token");
define('MESSAGE_LOCKED_TOKEN', "Token is locked right now. It happens when a large number of requests hit the server at the same time. Please try again later.");
define('MESSAGE_EXPIRED_TOKEN', "Token is Expired!!");
define('MESSAGE_INSUFFICIENT_BALANCE', "Insufficient Balance!!");
define('MESSAGE_SUCCESS', "success");
define('MESSAGE_BAD_DATA_FORMAT', "Bad Data Format! Please consult the API Documentation.");
define('MESSAGE_SINGLE_SMS_ONLY', "This API allows only single SMS. If you want to send multiple or broadcast sms, please use case specific API");
define('MESSAGE_SYSTEM_ERROR', "System Error!! Please try again later");
define('MESSAGE_PAYLOAD_TOO_LARGE', "Maximum " . MAX_NUMBERS . " numbers are supported currently");
define('MESSAGE_LENGTH_TOO_LARGE', "Maximum " . MAX_MESSAGE_LENGTH . " characters are supported for message currently");


define('MESSAGE_SEND_SUCCESS', "SMS sent successfully!");
define('MESSAGE_SEND_PARTIAL_SUCCESS', "SMS sending was partially successful");
define('MESSAGE_SEND_FAILED', "Sms failed to send. Please check the number and try again.");

define('STATUS_FAILED', "FAILED");
define('STATUS_SUCCESS', "SUCCESS");
define('STATUS_PARTIAL_SUCCESS', "PARTIAL_SUCCESS");

/*
|--------------------------------------------------------------------------
| REQUEST Format Constants
|--------------------------------------------------------------------------
|
*/
define("REQUEST_SINGLE_SMS", 	array("token", "to", "message"));
define("REQUEST_BROADCAST_SMS", array("token", "to", "message"));
define("REQUEST_MULTIPLE_SMS", 	array("token", "smsData"));
define("REQUEST_SMS_DATA", 		array("to", "message"));
define("REQUEST_TOKEN_INFO", 	array("token"));

/*
|--------------------------------------------------------------------------
| Status ID Constants
|--------------------------------------------------------------------------
|
*/
define('SMS_STATUS_UNSENT', 1);
define('SMS_STATUS_FAILED', 2);
define('SMS_STATUS_SUBMITTED', 3);
define('SMS_STATUS_SENT', 4);
define('SMS_STATUS_DELIVERED', 5);

/*
|--------------------------------------------------------------------------
| SMS Mode Constants
|--------------------------------------------------------------------------
|
*/
define('SMS_SINGLE', 'SMS_SINGLE');
define('SMS_MULTIPLE', 'SMS_MULTIPLE');
define('SMS_BROADCAST', 'SMS_BROADCAST');
