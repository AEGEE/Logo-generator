<?php
/**
 *
 */
function smarty_function_create_links( $params, &$smarty )
{
	$CI =& get_instance();
	
	return $CI->pagination->create_links();
}