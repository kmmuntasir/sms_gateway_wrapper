<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends MY_Model {

//	public $_table = 'users';

//	public function __construct() {
//		parent::__construct();
//	}

	public function insert_user($user) {
		$this->db->trans_start();
		$this->db->insert('user', $user);
		$this->db->trans_complete();
		return ($this->db->trans_status()) ? $this->db->insert_id : null;
	}


}
