<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends MY_Controller {


	public function index()
	{
		parent::view('downloads/index');
	}

	
	public function download_templates()
	{
		parent::view('downloads/download-templates');
	}
	
	public function download_elements()
	{
		parent::view('downloads/download-elements');
	}
	
	
	public function download_advanced()
	{
		parent::view('downloads/download-advanced');
	}
	
	public function download_materials()
	{
		parent::view('downloads/download-materials');
	}
	
}
