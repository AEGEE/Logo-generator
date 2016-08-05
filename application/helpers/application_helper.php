<?php

/*
 * link creation
 */

function l($path, $text, $args = NULL)
{
	$CI =& get_instance();

	$title = isset($args['title']) ? $args['title'] : $text; // defaulting title to text
	$class = isset($args['class']) ? $args['class'] : ''; // default class
	$active = isset($args['active']) ? $args['active'] : 'active'; // class if path equals current page

	if($path == $CI->uri->uri_string() || $CI->uri->segment(1) == $path)
	{
		$class .= ' '.$active;
	}

	$classStr = (strlen($class) > 0) ? ' class="'.$class.'"' : '';

	
	if(!stristr($path, $CI->config->item('base_url') ))
	{
		$path = $CI->config->item('base_url').$path;
	}	

	return '<a href="'.$path.'" '.$classStr.' title="'.$title.'">'.$text.'</a>';
}