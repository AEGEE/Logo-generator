<?php
/**
 * Smarty CI benchmark function
 *
 * Returns the CI benchmark parameters as a String.
 *
 * @author Marlon Etheredge <>
 */
function smarty_function_get_benchmark( $params, &$smarty )
{
    $CI =& get_instance();

    return 'Total execution time: ' . $CI->benchmark->elapsed_time() . ' Total memory consumption: ' . $CI->benchmark->memory_usage();
}