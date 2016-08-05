<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends MY_Controller {


	public function index()
	{
		parent::view('docs/index');
	}

	
	public function visual_identity_manual()
	{
		parent::view('docs/visual-identity-manual');
	}
	
	public function change_log()
	{
		parent::view('docs/change-log');
	}
	
	public function info()
	{
		parent::view('docs/info');
	}
	

	public function faq()
	{
		parent::view('docs/faq');
	}
	
	public function tool()
	{
		parent::view('docs/tool');
	}
	
	public function inquiries()
	{
		parent::view('docs/inquiries');
	}
	
}
