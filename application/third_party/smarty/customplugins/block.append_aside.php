<?php
/**
 *
 */
function smarty_block_append_aside( $params, $content, &$smarty, &$repeat )
{
    $smarty->assignGlobal( 'aside', $content );
    
    return;
}