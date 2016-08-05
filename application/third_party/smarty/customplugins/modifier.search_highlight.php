<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty search_highlight modifier plugin
 *
 * Type:     modifier
 * Name:     search_highlight
 * Purpose:  highlight keywords in text
 * 
 *          search_highlight (DoToKnow)
 * @author   Maurits Korse
 * @param string
 * @param array
 * @param booelan
 */
function smarty_modifier_search_highlight($haystack, $needles, $teaser = true)
{
	//prevent new paragraphs from being stitched to each other
	$haystack = preg_replace ( '/(<\/p>|<\/li>)/i', ' ' , $haystack );
	
	$haystack = strip_tags($haystack);

	$highlightPrepend = '<mark>';
	$highlightAppend = '</mark>'; 
	$available = false; // flag that is set to true when needles are found
	$locations = array(); // array that saves the positions of the needles in haystack
	$lookahead = 60; // how many characters to look ahead to combine chunks into one
	
	$chunks = array(); // the seperate chunks and their needle positions they include
	$chunkStr = array(); // the final chunck strings
	
	// initial check and indexer
	foreach($needles as $needle)
	{
		// get position of the needle in the haystack
		$pos = _dotoknow_stripos_recursive($haystack, $needle);

		// if a position is found, then set flag
		if( count($pos) > 0 )
		{
			//add position of new found needle to the array, so we can highlight them all
			$locations = array_merge($locations, $pos);
			
			// set flag to true
			$available = true;
		}
	}
	
	// if a match is available, let's highlight each including 
	if($available)
	{
		// sort locations asc
		sort($locations);
	
		// make chunks
		// if more needles are within ($lookahead + 100%) make one chunk
		$count = count($locations);
		
		for($i = 0; $i < $count; $i++)
		{
			$chunks[$i][] = $locations[$i];
			$k = $i;
			
			for($j = $i+1; $j < $count; $j++)
			{
				if( ($locations[$j] - $locations[$i] ) < round($lookahead * 2) )
				{
					$chunks[$i][] = $locations[$j];
					$k++;
				}
			}
			$i = $k;
		}
		
		// if more than two chunks available sort to priority.
		// where priority is based on number of needles in the chunk, and smallest with the
		if(count($chunks) > 2)
		{
			$sizes = array();
			$indeces = array();
			
			foreach($chunks as $i => $chunk)
			{
				if(count($sizes) == 2)
				{	
					array_multisort($sizes, $indeces);
					if(count($chunk) > $sizes[1])
					{
						$sizes[0] = count($chunk);
						$indeces[0] = $i;
					}
					if(count($chunk) > $sizes[1])
					{
						$sizes[0] = count($chunk);
						$indeces[0] = $i;
					}
				}
				else
				{
					$sizes[] = count($chunk);
					$indeces[] = $i;
				}
			}
			sort($indeces);
			$chunks = array(
				$chunks[$indeces[0]],
				$chunks[$indeces[1]]
			);
		}
		
		// create chunks strings
		foreach($chunks as $chunk)
		{
			$count = count($chunk);
			$min = min($chunk);
			$max = max($chunk);
			$circumference = round($lookahead * (1/$count));
			$startEllipsis = '';
			$endEllipsis = '';
			
			// start of chunk
			if($min - $circumference > 0)
			{
				$start = $min - $circumference;
				$startEllipsis = '...';
			}
			else
			{
				$start = 0;
			}
			
			// end of chunk
			if($max + $circumference < strlen($haystack) ) 
			{
				$end = $max + $circumference;
				$endEllipsis = '...';
			}
			else
			{
				$end = strlen($haystack) ;
			}
			$length = $end - $start;
			
			$tempChunk = substr($haystack, $start, $length);
			
			
			// let's highlight the chunks
			$pattern = implode("|",$needles);
			$pattern = '('.quotemeta($pattern).')';
			
			preg_match_all("/$pattern/i", $tempChunk, $matches); 
			if (is_array($matches[0]) && count( $matches[0]) >= 1 ) 
			{ 
				foreach ($matches[0] as $match) 
				{ 
					$tempChunk = str_ireplace($match, $highlightPrepend.$match.$highlightAppend, $tempChunk); 
				} 
			} 
			
			// append ellipsis if neccessary
			$chunkStr[] = $startEllipsis.$tempChunk.$endEllipsis;

		}
		
		return implode(' ', $chunkStr);
	}
	else
	{
		// no needle found in haystack
		// let's return the $haystack as it is.
		// if teaser enabled, show teaser text of haystack
		if($teaser)
		{
			$words = str_word_count($haystack, 2);
			$pos = array_keys($words);
			return substr($haystack, 0, $pos[19]) . '...';
		}
	}
	// if nothing return haystack;
	return $haystack;
}


function _dotoknow_stripos_recursive($haystack, $needle, $offset = 0, &$results = array()) 
{                
     $offset = strpos($haystack, $needle, $offset);
     if($offset === false) 
	 {
         return $results;            
     } 
	 else 
	 {
         $results[] = $offset;
         return _dotoknow_stripos_recursive($haystack, $needle, ($offset + 1), $results);
     }
 }

?>