<?php
/**
 *
 */
function smarty_block_append_message( $params, $content, &$smarty, &$repeat )
{   
    if($params['type'] == "success")
      $group = "success";
    elseif($params['type'] == "error")
      $group = "error";
    else
      $group = "notice";
    
    // We gebruiken hier append in plaats van assign omdat er meerdere messages kunnen zijn
    $smarty->assignGlobal('systemMessager_'.$group, $content);
    
    return;
}