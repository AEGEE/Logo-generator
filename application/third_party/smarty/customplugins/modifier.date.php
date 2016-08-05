<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_modifier_date($timestamp, $format = "medium") {

  // Wanneer de datum binnen 3 dagen ligt en niet voor html <time> is gebruiken we altijd relative time.
  if(strtotime('now') - $timestamp < 7*24*60*60 && $format != "html" && $format != "tooltip")
    $format = "relative";


  switch ($format) {     
    case "long":
        return strftime("%A, %B %e, %Y - %H:%M",$timestamp);
        break;
    case "medium":
        return strftime("%b %e, %Y - %H:%M",$timestamp);
        break;
    case "short":
        return strftime("%d-%m-%y  - %H:%M",$timestamp);
        break;
    case "date":
        return strftime("%b %e, %Y",$timestamp);
        break;
    case "html":
        return strftime("%Y-%m-%dT%H:%M:%SZ",$timestamp);
        break;
    case "tooltip":   // This is the same as the long format
        return strftime("%A, %B %e, %Y - %H:%M",$timestamp);
        break;
    case "time":
        return strftime("%H:%M",$timestamp);
        break;
        
    case "relative":
      $conversions = array(
        'millisecond' => 1,
        'sec' => 1000,
        'min' => 60,
        'hour' => 60,
        'day' => 24,
        'month' => 30,
        'year' => 12
      );
      
      # Set up params.
      $threshold = 5000; // In milliseconds
      $now = strtotime('now');
      $time = $timestamp;
      $delta = ($now - $time) * 1000;

      if ($delta <= $threshold) {
        return "Just now";
      }
      
      foreach ($conversions as $key => $val) {
        if($delta < $conversions[$key]) {
          break;
        } else {
          $units = $key;
          $delta = $delta / $conversions[$key];
        }
      }
      
      $delta = floor($delta);
      if($delta != 1) { $units .= 's'; }
      return implode(' ', array($delta, $units, 'ago'));
      break;
  }
}
