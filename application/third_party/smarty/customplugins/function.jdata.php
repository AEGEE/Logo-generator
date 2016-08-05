<?php
/**
 *
 * Array for json string
 */
function smarty_function_jData( $params = array(), &$smarty)
{   
    $jsonArray = $smarty->getTemplateVars('jsonData');
	
    if($jsonArray == NULL){
		$jsonArray = array();
	}
	else{
		$jsonArray = json_decode($jsonArray, true);
	}
	
	$pushArray = array();
	foreach($params as $key => $value)
	{
		$pushArray[$key] = $value;
	}
	
	$jsonArray = array_merge($jsonArray, $pushArray);
	//$smarty->clear_assign('jsonData');
    
	$smarty->assignGlobal('jsonData', json_encode($jsonArray));
    
    return;
}
