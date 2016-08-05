<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Controller {


	public function index()
	{
		parent::view('pages/index');
	}

	
	public function pictures()
	{
		parent::view('pages/pictures');
	}
	
	public function examples()
	{
		parent::view('pages/examples');
	}
	
	
	public function inspiration()
	{
		parent::view('pages/inspiration');
	}
	
	public function tutorials()
	{
		parent::view('pages/tutorials');
	}
	
	public function writing()
	{
		parent::view('pages/writing');
	}
	
	public function phpinfo()
	{
		phpinfo();
	}
}
