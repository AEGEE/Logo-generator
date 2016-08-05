<?php
/**
 * Smarty CodeIgniter URL functions
 *
 * Lazy entry for CI URL functions, found at
 * http://codeigniter.com/user_guide/helpers/url_helper.html
 * parameter 'function' expected as name for the CI URL function
 * to be called, rest of parameters will be passed to the CI function.
 *
 * @param  Array  Params Array of parameters, expecting one entry 'function' with the name of a valid CI URL helper function,
 *                       rest of parameters will be passed to the specified function.
 * @param  Smarty Smarty Instance of Smarty.
 * @return mixed         Returns the return value of the CI URL function.
 *
 * @author Marlon Etheredge
 */
function smarty_function_ci_url( $params, &$smarty )
{
    $ciUrlFunctions = array(
        'site_url',
        'base_url',
        'current_url',
        'uri_string',
        'index_page',
        'anchor',
        'anchor_popup',
        'mailto',
        'safe_mailto',
        'auto_link',
        'url_title',
        'prep_url',
        'redirect'
    );
    
    if( $params['function'] )
    {
        if( in_array( $params['function'], $ciUrlFunctions ) && function_exists( $params['function'] ) )
        {
            $function = new ReflectionFunction( $params['function'] );
            
            unset( $params['function'] );
            
            return $function->invokeArgs( $params );
        }
    }
    
    return false;
}