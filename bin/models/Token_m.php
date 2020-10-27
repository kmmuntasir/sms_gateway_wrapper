<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_m extends MY_Model {

	public function getTokenInfo($token_key) {
		$this->db->where('token_key', $token_key);
		return $this->db->get('token')->row();
	}

	public function retrieveToken($token_key, $api_type_id) {
		$select = "
			token.client_id,
			token.token_id,
			token.token_key,
			token.token_rate,
			token.token_balance,
			token.token_expiry,
			token.status_id as token_status_id,
			provider_token.provider_token_id,
			provider_token.provider_token_key,
			provider_token.provider_token_rate,
			provider_token.provider_token_balance,
			provider_token.provider_token_expiry,
			provider_token.status_id as provider_token_status_id,
			provider.provider_name,
			provider.provider_library,
			api.api_endpoint
		";
		$this->db->select($select);
		$this->db->where('token_key', $token_key);
		$this->db->join('provider_token', 'token.provider_token_id = provider_token.provider_token_id');
		$this->db->join('provider', 'provider_token.provider_id = provider.provider_id');
		$this->db->where('api_type_id', $api_type_id);
		$this->db->join('api', 'api.provider_id = provider.provider_id');
		return $this->db->get('token')->row();
	}

}
