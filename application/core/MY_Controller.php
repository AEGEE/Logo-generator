<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Variable declaration towards view
 */


class MY_Controller extends CI_Controller
{

	protected $global = array();
	protected $page = array();
	protected $menu = array();
	protected $user = array();
	protected $tpldata = array('code' => 200, 'message' => 'success');
	protected $aside = array();
	
	function __construct()
	{
		//Construct CI_Controller
		parent::__construct();

		// check if website is installed
		
		// Run the Session routine. If a session doesn't exist we'll
		// create a new one.  If it does, we'll update it.
		/*
		if( !$this->session->sess_read() )
		{
			$this->session->sess_create();
		}
		else
		{
			$this->session->sess_update();
		}
		*/
		$this->loadDefaults();

	}

	// assign variables to the template system
	protected function assign( $var, $object = null )
	{
		//print 'tpldata BEFORE assign: ' . print_r($this->tpldata, true) .';';
		
		// prepare
		if($object == null && (is_array($var) || is_object($var)))
		{
			$data = $var;
		}
		else
		{
			$data = array($var => $object);
		}
		
		
		if ($var == 'page')
		{
			$this->page = array_merge($this->page, $data);
		}
		else
		{
			$this->tpldata = array_merge($this->tpldata, $data );
		}
		
		//print 'tpldata AFTER assign: ' . print_r($this->tpldata, true) .';';
	}
	

	protected function exists( $file )
	{
		if(strstr($file, '.tpl') === false)
		{
			$file .= '.tpl';
		}

		$templateDir  = APPPATH . '/views/';
		if(!file_exists($templateDir.$file))
		{
			$file = 'default.tpl';
		}

		//return $this->smartylib->exists( $file );
		return $file;

	}

	
	// compile and view the content
	// either through html templating or as ajax
	protected function view( $file, $raw = false, $storePrevious = true )
	{
	
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			/* special ajax here */
			//print 'tpldata = '.print_r($this->tpldata, true);
			
			$code = $this->tpldata['code'];
			$message = $this->tpldata['message'];
			
			$this->tpldata['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);
			
			
			$this->output->set_status_header($code, $message);
			print json_encode($this->tpldata );
		}
		else
		{
			// add some extra variables
			$this->constructPageID($file);
			
			$this->smartylib->assign( 'global', $this->global );
			$this->smartylib->assign( 'page', $this->page );
			$this->smartylib->assign( 'user', $this->user );
			$this->smartylib->assign( 'menu', $this->menu);
			$this->smartylib->assign( 'data', $this->tpldata );
			$this->smartylib->assign( 'aside', $this->aside );
			// if file does not exist use default.php template
			$file = $this->exists($file);
			// Show template
			if( $raw )
			{
				$this->smartylib->viewraw($file, $data);
			}
			else
			{
				$this->smartylib->view( $file );
			}
		}
	}

	/**
	 * Load defaults of the page
	 * 
	 */
	protected function loadDefaults()
	{

		$this->global['base_url'] = $this->config->item('base_url');	

		$this->global['sitename'] = $this->config->item('site_name');
		$this->global['copyright'] = '';
		$this->global['version'] = $this->config->item('site_version');
		
		
		$this->global['ENVIRONMENT'] = ENVIRONMENT;
		$this->global['APPPATH'] = APPPATH;
		$this->global['BASEPATH'] = BASEPATH;
		$this->global['assets'] = '/assets/' ;
		$this->global['jsPath'] = '/assets/js/' ;
		$this->global['cssPath'] = '/assets/css/' ;
		
		$global['ENV'] = $_ENV;
		$global['GET'] = $_GET;
		$global['POST'] = $_POST;
		$this->global['keywords'] = 'AEGEE,Europe,Logo';
	
		
		// prev URI
		$prevURI = $this->session->userdata( 'prevURI' );
		if( isset( $prevURI ) )
		{
			$this->global['referer'] = $prevURI;
		}

		
	}
	
	protected function constructPageID($template)
	{
		// Retrieve two first URI segments and assign: controller/function
		$this->page['templateController'] = $this->uri->segment(0);
		$this->page['templateName'] = $this->uri->segment(1);
		$this->page['template'] = $template;
		$this->page['pageCssIdentifier'] = 'page-'.$template;
	}

	/**
	 * Show the error page
	 *
	 * @internal
	*/
	protected function error( $title, $message )
	{
		MY_Controller::assign( 'title', $title );
		MY_Controller::assign( 'message', $message );
		MY_Controller::view( 'error', false, false );
	}
	
	/************************************************************************/
	/* Error handling                                                       */
	/************************************************************************/
	/**
	 * Error: 404 Not Found
	 * 
	 * @internal
	*/
	protected function error_404()
	{
		$this->output->set_status_header('404');
		$this->error( 'Not found', 'File could not be found.' );
	}
	
}
