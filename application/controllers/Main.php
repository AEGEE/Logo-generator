<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {


	public function index()
	{
		$this->home();
	}	

	public function home()
	{
		$this->view('home');
	}
	
}
