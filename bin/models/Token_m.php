<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_m extends MY_Model {

	public function retrieveToken($token_key, $api_type_id) {
		$select = "
			client_id,
			token_id,
			token_key,
			token_rate,
			token_balance,
			token_expiry,
			token.status_id as token_status_id,
			provider_token.provider_token_id,
			provider_token_key,
			provider_token_rate,
			provider_token_balance,
			provider_token_expiry,
			provider_token.status_id as provider_token_status_id,
			provider_name,
			provider_library,
			api_endpoint
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
