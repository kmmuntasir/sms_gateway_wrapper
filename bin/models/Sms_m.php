<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_m extends MY_Model {

	public function lockToken($token_id) {
		$this->db->trans_start();
		$this->db->where('token_id', $token_id);
		$this->db->update('token', array('status_id' => STATUS_LOCKED));
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function unlockToken($token_id) {
		$this->db->trans_start();
		$this->db->where('token_id', $token_id);
		$this->db->update('token', array('status_id' => STATUS_ACTIVE));
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function executePostSuccessOperations($count, $token) {
		$this->db->trans_start();
		$clientToken = $this->getToken($token->token_id);
		$providerToken = $this->getToken($token->provider_token_id, true);

		$updatedClientToken = array();
		$updatedClientToken['token_balance'] = $clientToken->token_balance - ($clientToken->token_rate * $count);
		$this->db->where('token_id', $token['token_id'])->update('token', $updatedClientToken);

		$updatedProviderToken = array();
		$updatedProviderToken['provider_token_balance'] = $providerToken->provider_token_balance - ($providerToken->provider_token_rate * $count);
		$this->db->where('provider_token_id', $token['provider_token_id'])->update('provider_token', $updatedProviderToken);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function insertAllSms($allSms) {
		$this->db->trans_start();
		$this->db->insert_batch($allSms);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function getToken($token_id, $provider=false) {
		$table = ($provider) ? 'provider_token' : 'token';
		$field = ($provider) ? 'provider_token_id' : 'token_id';

		return $this->db->where($field, $token_id)->get($table)->row();
	}

}
