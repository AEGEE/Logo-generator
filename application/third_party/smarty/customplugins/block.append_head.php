<?php
/**
 *
 */
function smarty_block_append_head( $params, $content, &$smarty, &$repeat )
{
    $smarty->assignGlobal( 'head', $content );
    
    return;
}