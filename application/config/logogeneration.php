<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
// main svg file
// normal, subtext
$config['sourceFile'] = array(
	'thumb' => array('logo-thumb.svg', 	'logo-thumb.svg'),
	'small' => array('logo-small.svg', 	'logo-small-subtext.svg'),
	'medium' => array('logo-medium.svg','logo-medium-subtext.svg'),
	'large' => array('logo-large.svg', 	'logo-large-subtext.svg')
);

// Default text available space per logo size (local name, subtext)
// Default font-size per logo size (local name, subtext)
$config['srcTextDefaults'] = array(
	'thumb' => array( 'width' => array(50, 	0), 'fsize' => array(9, 0)),
	'small' => array( 'width' => array(210, 575), 'fsize' => array(38, 21)), // 21
	'medium' => array( 'width' => array(420,1150), 'fsize' => array(77, 42)), // 42
	'large' => array( 'width' => array(840, 2300), 'fsize' => array(155, 85)) // 85

);
	
// front colour, background colour
$config['srcColours'] = array(
	'blue'  => array('#1468b3', 'none'),
	'black' => array('#000000', 'none'),
	'white' => array('#ffffff', '#000000')
);

// front 
$config['srcFonts'] = array(
	'set1' => array(
		//'Helvetica'  => 'fonts/Helvetica.svg', //#Helvetica
		'Helvetica Neue LT Pro 55 roman'  => array(
			'svg'=> 'fonts/Helvetica.svg',
			'woff'=> 'fonts/Helvetica.woff'
		), //#Helvetica
		//'HelveticaCond' => 'fonts/HelveticaCond.svg'//#HelveticaCond
		'Helvetica Neue LT Pro' => array(
			'svg' => 'fonts/HelveticaCond.svg', //#HelveticaCond
			'woff' => 'fonts/HelveticaCond.woff'
		)
	), 
	'set2' => array(
		'Open Sans' => array(
			'svg' => 'fonts/OpenSans.svg',
			'woff' => 'fonts/OpenSans.woff' // #OpenSans
		)
	)
);	

// font area anchor
$config['textanchor'] = array(
	'thumb' => array(
		'local' => array('x' => 130.22, 'y1' => 66.886, 'y2' => 66.886), // y2 klopt niet..
		'subtext' => array('x' => 0, 'y1' => 0, 'y2' => 0)
	),
	'small' => array(
		'local' => array('x' => 537.473, 'y1' => 279.1387, 'y2' => 317.1387),
		'subtext' => array('x' => 317.377, 'y1' => 447.0918, 'y2' => 477.0918)
	),
	'medium' =>  array(
		'local' => array('x' => 1076.838, 'y1' => 560.2295, 'y2' => 635.2295),
		'subtext' => array('x' => 633.999, 'y1' => 892.4951, 'y2' => 942.4951)
	),
	'large' =>  array(
		'local' => array('x' => 2154.029, 'y1' => 1123.000, 'y2' => 1263.000),
		'subtext' => array('x' => 1266.3623, 'y1' => 1899.8213, 'y2' => 1799.8213)
	)
);

$config['extraFileDir'] = APPPATH.'/downloads/logos/AEGEE-Europe/';
// extra files to include in zip download
$config['extraFiles'] = array(
	//'none' => array(''),
	'europe1' => array( // 4.5MB
		array($config['extraFileDir'].'_zipped/Logos AEGEE-Europe basic.zip',	'Europe', 'medium', 'blue', 'zip')
	),
	'europe2' => array(
		array($config['extraFileDir'].'_zipped/Logos AEGEE-Europe extended.zip',	'Europe', 'extended', 'Europe', 'zip')
	),
	'europe3' => array(
		array($config['extraFileDir'].'_zipped/Logos AEGEE-Europe advanced.zip',	'Europe', 'advanced', 'Europe', 'zip')	
	)
);