<?php
/**
 * Smarty CodeIgniter Form error function
 * Developed for Membership management tool AEGEE-Enschede
 * www.aegee-enschede.nl
 *
 * @param  Array  Params 
 * 			field : HTML name of the field
 *			check : whether to returns true or false (default is true)
 * @param  Smarty Smarty Instance of Smarty.
 * @return mixed         (bool) Returns on CI form errors.
 *						 (string) Returns HTML class for form element 
 *						 (string) Returns HTML error message in case no field was defined
 * @author Maurits Korse
 */
function smarty_function_cif_error( $params = array(), &$smarty )
{
	$CI =& get_instance();
	$CI->load->library('form_validation');
	
	/*
	 * Defaults
	 */
	$nParams = count($params);
	$params['check'] = (!isset($params['check'])) ? true : $params['check'] ;
	
	// simplest case
	// in case only function was request, returnn true if there were any errors at all
	if(!isset($params['field']))
	{
		$error = validation_errors();
		if($params['check'])
		{
			return ($error != null); 
		}
		return $error;
	}
	else
	{
	// in case the field parameter was defined:
	// this should be the case now

		// return boolena
		$error = form_error($params['field']);
		if(isset($params['class']))
		{
			return ($error != null) ? ' class="input-error"' : false ;
		}
		
		if($params['check'])
		{
			return ($error != null);
		}
		return $error;
	
	}

	// unknown params were probably defined, return false;
	return false;
	
}