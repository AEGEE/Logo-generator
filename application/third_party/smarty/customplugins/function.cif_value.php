<?php
/**
 * Smarty CodeIgniter Form set_value function
 * Developed for Membership management tool AEGEE-Enschede
 * www.aegee-enschede.nl
 *
 * @param  Array  Params 
 * 			field : HTML name of the field
 *
 * @param  Smarty Smarty Instance of Smarty.
 * @return string        Returns submitted value or none if n/a.
 *
 * @author Maurits Korse
 */
function smarty_function_cif_value( $params = array(), &$smarty )
{
	$CI =& get_instance();
	
	$CI->load->library('form_validation');
	$CI->load->helper('form');

	if(isset($params['field']))
	{
		$value = htmlentities(set_value($params['field']));
	}
	else
	{
		$value = '';
	}
    return $value;
}