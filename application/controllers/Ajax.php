<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logo extends MY_Controller 
{

	public function index()
	{
		$forceAjaxOutput = true;
		
		$return['code'] = 201;
		$return['message'] = 'I am alive!';
		
		parent::assign($return);
	}

	/**
	 * Show the customisation form
	 * load locals and available subtexts for customisation
	 */
	public function get_csrf()
	{
		$forceAjaxOutput = true;
		
		$return['code'] = 201;
		$return['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		parent::assign($return);
	}
}
