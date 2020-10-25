<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_m extends MY_Model {

	public function executePostSingleSuccessOperations($sms, $token) {
		$this->db->trans_start();
		$clientToken = $this->getToken($token['token_id']);
		$providerToken = $this->getToken($token['provider_token_id'], true);

		$updatedClientToken = array();
		$updatedClientToken['token_balance'] = $clientToken->token_balance - $clientToken->token_rate;

		$updatedProviderToken = array();
		$updatedProviderToken['provider_token_balance'] = $providerToken->provider_token_balance - $providerToken->provider_token_rate;

		$this->insert($sms);
		$this->db->where('token_id', $token['token_id'])->update('token', $updatedClientToken);
		$this->db->where('provider_token_id', $token['provider_token_id'])->update('provider_token', $updatedProviderToken);

		$this->db->trans_complete();
		return $this->db->trans_status();

	}

	public function getToken($token_id, $provider=false) {
		$table = ($provider) ? 'provider_token' : 'token';
		$field = ($provider) ? 'provider_token_id' : 'token_id';

		return $this->db->where($field, $token_id)->get($table)->row();

	}

}
