<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Smartytpl library. Extends default Smarty class
 * for easy integration into CodeIgniter.
 *
 * @author	Eric 'Aken' Roberts <eric@cryode.com> 
 * @link	https://github.com/cryode/CodeIgniter_Smarty
 * @version	1.0.0
 */

// Require the Smarty class from our third_party directory.
require_once APPPATH . 'third_party/smarty/Smarty.class.php';
 
class Smartylib extends Smarty {
	
	private $CI;

	/**
	 * Constructor!
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();

		/*/ Assign CodeIgniter object by reference to CI
		if ( method_exists( $this, 'assignByRef') )	{
			$ci =& get_instance();
			$this->assignByRef("ci", $ci);
		}
		/**/
		$this->CI 	=& get_instance();

			
			
		// Get Smarty config items.
		$this->CI->load->config('smarty');
		
		// Set appropriate paths.
		$this->setTemplateDir($this->CI->config->item('smarty_template_dir'));
		$this->setCompileDir($this->CI->config->item('smarty_compile_dir'));
		$this->addPluginsDir($this->CI->config->item('smarty_plugin_dir'));
		$this->setCacheDir($this->CI->config->item('smarty_cache_dir'));
		//$this->setConfigDir($this->CI->config->item('smarty_config_dir'));
		$this->auto_literal = false; 
		$this->left_delimiter = "{{"; 
		$this->right_delimiter = "}}";
		
 		if(ENVIRONMENT == 'development')
		{
			//$this->testInstall();
			$this->compile_check = true;
			$this->force_compile = true;

		}

	}
	
	function CI_Smarty()
	{
		parent::Smarty();
	}
	
	
	/*private function exists( $template )	{
		return 
	}
	
	private function setExtenstion($template){
	  if( strpos( $template, '.' ) === false ) {
     $template = $template . '.'. $this->CI->config->item('smarty_template_ext');
    }
		return $template;
	}
	*/
	
	private function checkTemplate($template)
	{
		if( strpos( $template, '.' ) === false ) 
		{
			$template = $template . '.'. $this->CI->config->item('smarty_template_ext');
		}
		if(!file_exists( $this->CI->config->item('smarty_template_dir') . $template ))
		{
			$template = 'default.tpl';
		}
	
		return $template;
	}
	
	    /**
     * Smarty display function
     *
     * Returns the parsed template specified,
     * when not extension is appended to the filename, .tpl is assumed.
     *
     * @param  String $resourceName Name of the template
     * @param  int    $cacheId      Cache id, see Smarty->display function
     * @param  bool   $wrap         Wether or not the template should be wrapped (header and footer)
     * @return String               Parsed template
     */
    function view( $template, $cacheId = null, $fetch = false )
	{
		$template = $this->checkTemplate($template);
				
        if( !$fetch )
		{
            $content = $this->fetch( $template );//, $cacheId
            
            $html = $this->view( 'chunks/header', $cacheId, true ) . //
                    $content . 
                    $this->view( 'chunks/footer', $cacheId, true ); // 
			
            $this->CI->output->set_output( $html );
        }
        else
		{
            return $this->fetch( $template ); //, $cacheId
        }
    }

	// return raw without header and footers
	function viewraw( $template )
	{
		$template = $this->checkTemplate($template);
        
		$content = $this->fetch( $template ); //, null
		$this->CI->output->set_output( $content );
	}
	


	// ------------------------------------------------------------------------------
	/**
	 * Takes the data array passed as the second parameter of
	 * CodeIgniter's $this->load->view() function, and assigns
	 * data to Smarty.
	 */
	public function assign_variables($variables = array())
	{
		if (is_array($variables))
		{
			foreach ($variables as $name => $val)
			{
				$this->assign($name, $val);
			}
		}
	}

}