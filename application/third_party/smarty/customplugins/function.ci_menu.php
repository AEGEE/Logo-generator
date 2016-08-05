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
function smarty_function_ci_menu( $params, &$smarty )
{
	$CI =& get_instance();
	
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
            
			$classStr = '';
			$titleStr = '';
			$textStr = 'link';
			$relStr = '';
			$idStr = '';
			
			$isCurrentUrl = false;
			$url = $function->invokeArgs((array) $params['segments'] );
			$currentUrl = $CI->config->site_url($CI->uri->uri_string());
			
			if($currentUrl == $url)
			{
				$classStr .= ' current-url active ';
				$isCurrentUrl = true;
			}
			if(isset($params['type']))
			{
				if($params['type'] == 'parent' && stristr($currentUrl, $url) !== false)
				{
					$classStr .= ' parent-url active ';
					$isCurrentUrl = true;
				}
			}
			
			if(isset($params['class']))
			{	
				$classStr = $params['class'];
			}
			
			if(isset($params['class']) || $isCurrentUrl)
			{
				$classStr = ' class="'.$classStr.'" ';
			}

			if(isset($params['title']))
			{
				$titleStr = ' title="'.$params['title'].'" ';
			}

			if(isset($params['text']))
			{
				$textStr = $params['text'];
			}
			
			if(isset($params['rel']))
			{
				$relStr = ' rel="'.$params['rel'].'" ';
			}
			
			if(isset($params['id']))
			{
				$idStr = ' id="'.$params['id'].'" ';
			}

			
			
            return '<a href="'.$url.'" '.$classStr.$titleStr.$relStr.$idStr.'>'.$textStr.'</a>';
        }
    }
    
    return false;
}