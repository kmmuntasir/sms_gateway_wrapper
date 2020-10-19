<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['jwt_expiration'] = true; // Change it to FALSE to set JWT to never expire
$config['jwt_key'] = 'some_awesome_secret_key_here';
$config['jwt_timeout'] = 1; // 1 minute timeout
