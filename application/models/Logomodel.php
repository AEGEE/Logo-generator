<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time', 30000);

/**
 * Logo model
 * Written by Maurits Korse
 *
*/

class Logomodel extends CI_Model
{
	// settings array storing all info
	protected $settings = array();

	// from config
	protected $conf = array();
	
	protected $token = null;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		// read session data into class
		$this->config->load('logogeneration');
		$this->conf['sourceFile'] = $this->config->item('sourceFile');
		$this->conf['srcColours'] = $this->config->item('srcColours');
		$this->conf['srcFonts'] = $this->config->item('srcFonts');
		$this->conf['extraFiles'] = $this->config->item('extraFiles');
		$this->conf['srcTextDefaults'] = $this->config->item('srcTextDefaults');
		$this->conf['textanchor'] = $this->config->item('textanchor');
		$this->db->db_select('logo-generator');
		
	}

	/**
	 * init the information for generating the logos
	 */
	public function init($token) 
	{
		$this->settings['initTime'] = $this->microtimeFloat();
		
		$this->load->library('form_validation');
		$this->token = ( $token != null) ? $token : $this->input->post( 'token' , TRUE) ;
		$input['local'] = $this->input->post('local', TRUE);
		
		if(!$this->initProgress($token, $input['local'], 10, 'Initialising')){
			return false;
		}
		
		$input['subtext'] = $this->input->post('subtext', TRUE);
		$input['format'] = $this->input->post('format', TRUE);
		$input['size'] = $this->input->post('size', TRUE);
		$input['colour'] = $this->input->post('colour', TRUE);
		$input['extra'] = $this->input->post('extra', TRUE);
		$input['token'] = $this->input->post('token', TRUE);

		$this->setProgress(14, 'Checked input');
		
		// all  required is correct
		if($input['local'] !== false && $input['format'] !== false && $input['size'] !== false && $input['colour'] !== false && $input['extra'] !== false) // && $input['request'] !== false)
		{
			// get extra variables
			$input['subtext'] = ($input['subtext']  != 'none') ? $input['subtext'] : false ;
			
			$res = $this->getLocalFull($input['local']);
			$this->settings = array_merge((array)$this->settings, (array)$input, (array)$res);
			
		}
		else{
			$this->setProgress(999, 'Failed, not all data was submitted');
			return false;
		}
		$this->setProgress(19, 'Finished initialising');
		
		// cancel script if status has become -1 (cancellation)
		if($this->isCancelled()) return false;
	
		$this->setup();
	}

	/**
	 * start generating folders and perpare the texts
	 */
	public function setup()
	{ 		
		$this->setProgress(20, 'Starting setup', true);

		// do some initial preparations
		if(! $this->prepTexts() )
		{
			$this->setProgress(999, 'Failed, could not prepTetxs', true);
			return false;
		}
		
		// remove old archives
		$this->rmArchives();
		
		// cancel script if status has become -1 (cancellation)
		if($this->isCancelled()) return false;
		
		// create download folder to store the files
		if(! $this->createFolders() )
		{
			$this->setProgress(999, 'Failed, could not create necessary folders', true);
			return false;
		}
			
		// calculate number of totalSteps for progressbar
		$this->settings['totalSteps'] = $this->calcSteps();
		
		$this->setProgress(29, 'Finished setup');	
		
		// cancel script if status has become -1 (cancellation)
		if($this->isCancelled()) return false;
		
		$this->generate();
	}
	
	/**
	 * init the information for generating the logos
	 */
	public function generate()
	{	
		$this->setProgress(30, 'Generating source svg');
		$this->imgCreateSVGSource();
		
		foreach($this->settings['format'] as $format)
		{
			switch($format)
			{
				case 'png':
				// cancel script if status has become -1 (cancellation)
				if($this->isCancelled()) return false;
				$this->setProgress(40, 'Generating png');
				$this->imgCreatePNG();
				break;
				
				case 'jpeg':
				// cancel script if status has become -1 (cancellation)
				if($this->isCancelled()) return false;
				$this->setProgress(50, 'Generating jpg');
				$this->imgCreateJPEG();
				break;
				
				case 'pdf':
				// cancel script if status has become -1 (cancellation)
				if($this->isCancelled()) return false;
				$this->setProgress(60, 'Generating pdf');
				$this->imgCreatePDF();
				break;
				
				case 'eps':
				// cancel script if status has become -1 (cancellation)
				if($this->isCancelled()) return false;
				$this->setProgress(70, 'Generating eps');
				$this->imgCreateEPS();
				break;
			}
		}
		
		$this->setProgress(80, 'Finished generating');
		
		// cancel script if status has become -1 (cancellation)
		if($this->isCancelled()) return false;
		
		$this->package();
	}
	
	/**
	 * Cancelling the generation
	 */
	public function cancel($token)
	{
		//$this->resetSessionInfo();
		// method to cancel
		// set status to -1
		// check at every stage of the script if the status has become -1, if so cancel
		$this->setProgress(-1, 'Cancelling process', $token);
		return true;
	}
	
	/**
	 *
	 */
	public function isCancelled()
	{
		$progress = $this->getProgress();
		if($progress['status'] == -1){
			return true;
		}
		return false;
	}
	
	/**
	 * Get all local information
	 */
	protected function getLocalFull($bodyCode)
	{
		$this->db->db_select('ab');
		$local = $this->db->query('SELECT BodyName, BodyNameAscii FROM bodies WHERE BodyCode = "'.$bodyCode.'"')->result();
		
		// go back to default table
		$this->db->db_select('logo-generator');
		
		$city = $this->rmPrefix($local[0]->BodyName);
		$cityCaps = $this->strtouppertr($city);
		$citySanitized = $this->rmPrefix($local[0]->BodyNameAscii);	
		
		return array(
			'localFull' => $local[0]->BodyName,
			'bodyCode' => $bodyCode,
			'cityName' => $city,
			'cityCaps' => $cityCaps,
			'citySanitized' => $citySanitized,
			'citySafe' => str_replace(" ", "_", $citySanitized)
		);
	}
	
	/**
	 * Calculate the number of logo generation totalSteps
	 */
	protected function calcSteps()
	{
		$totalSteps = 0; // from intializing, text prep, creating folder, and start of generation and zipping

		$nFormats = count( $this->settings['format'] );
		$nSizes = count( $this->settings['size'] );
		$nColours = count( $this->settings['colour'] );
		
		$nInit = 3; // 10
		$nSetup = 4; // 20
		$nGenerating = ($nFormats * $nSizes * $nColours * 2 ) + 1; // 30 - 80
		$nZip = 6; // 90
		$nExtra = ($this->settings['extra'] != false) ? 1 : 0 ;
	
		$totalSteps += $nInit + $nSetup + $nGenerating + $nZip + $nExtra;
		
		return $totalSteps;
	}

	/**
	 * Select right texts to be inserted 
	 * calculate text placement
	 * check for validity
	 * return false if false, otherwise return font, text placement and font-style settings
	 */ 
	protected function prepTexts()
	{
		$this->setProgress(23, 'Prepare text transformations');
	
		$this->settings['localFont'] = $this->determineFont($this->settings['cityCaps'] , 0, $this->conf['srcFonts']['set1'], 2 );

		if($this->settings['subtext'] != false)
		{
			$subtext = $this->db->query('SELECT subtext FROM logo_subtext WHERE language = "'.$this->settings['subtext'].'"')->result();
			$this->settings['subtextFull'] = $subtext[0]->subtext;
			
			$this->settings['subextFont'] = $this->determineFont($this->settings['subtextFull'], 1, $this->conf['srcFonts']['set2'], 2);	
	
			if($this->settings['localFont'] != false && $this->settings['subextFont'] != false)
			{
				return true;
			}
		}
		
		if($this->settings['localFont'] != false)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Determine the font for available space
	 * Also retuns adjusted font size or text if it had to be changed to make it fit
	 * @reduce  accounts for allowing the reduction of letterspacing.
	 */
	protected function determineFont($text, $textIndex, $fonts, $lines = 1, $reduce = true )
	{
		$this->setProgress(24, 'Determine font usage');

		// check foreach font if the text fits,
		// the fonts should be sorted on preference of usage
		// the first font found to be good will be returned
		foreach($fonts as $font => $src)
		{
			// SVG font source file
			$svgsrc = $src['svg'];
			
			// get relative text dimensions
			$relDims = $this->calcRelTextSize($svgsrc, $text);
			
			// get source font em size
			$fontUnitsEm = $this->getFontUnitsEm($svgsrc);
			
			// output defaults
			$outputFont = array('name' => $font, 'svg' => $svgsrc);
			$outputText1 = $text;
			$outputText2 = '';
			$outputSize = array();
			$outputWidth = array();
			$outputHeight = $outputYShfit = array();
			$outputTrans = '';
			
			// check for each size if text fits
			foreach($this->settings['size'] as $size)
			{
				$pass = false; // reset pass variable for this size

				$defaultFontSize = $this->conf['srcTextDefaults'][$size]['fsize'][$textIndex];
				$availableSpace = $this->conf['srcTextDefaults'][$size]['width'][$textIndex];
				
				$width = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims['width']);
				$origHeight = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims['height']);
				
				if( !$pass )
				{
					// no transformation required
					if($availableSpace > $width)
					{
						// set default font size
						$outputSize[$size] = $defaultFontSize;
						$outputWidth[$size][0] = $width;
						$outputWidth[$size][1] = $width;
						$outputYShfit[$size] = 0; // no vertical shifting of the text
						$pass = true;
						continue;  // to next size
					}
				}
				
				// 1 first fitting attempt
				// reduce text size gradually to max 15% and see if it fits
				if( !$pass )
				{
					$factor = 1.0; // initial factor to redue font size with 
					$lowerBound = 0.75; // max lower bound factor
					$result = $this->decreaseFont($defaultFontSize, $availableSpace, $fontUnitsEm, $relDims, $lowerBound, $factor);
					
					if($result)
					{
						$outputSize[$size] = $result['fontsize'];
						$outputWidth[$size][0] = $result['width'];
						$outputWidth[$size][1] = $result['width'];
						$outputYShfit[$size] = $origHeight - ($result['fontsize'] * $origHeight / $fontUnitsEm); // no vertical shifting of the text
						//$outputYShfit[$size] = $origHeight - ($result['fontsize'] * $relDims['height'] / $fontUnitsEm); // no vertical shifting of the text
						$pass = true;
						$factor = $result['factor'];
						continue;  // to next size
					}
				}
				
				// 2 seccond fitting attempt
				// place text over multiple lines - no fontsize reduction (if multiple lines are available: given as param of function)
				// only for names containing hypens or spaces
				if( !$pass && $lines > 1)
				{
					$hyphens = substr_count($text, '-');
					$spaces = substr_count($text, ' ');
					
					// if no spaces and no hypens, don't try to break text over 2 lines but go to next transformation
					if($spaces > 0 || $hyphens > 0)
					{
						// use only the available hyphens
						if($spaces == 0 && $hyphens > 0)
						{
							// works for any number of hypens
							// split from back, walk back to front
							$strings = explode("-", $text);
							for($i = count($strings); $i > 0; $i--)
							{
								$part1 = implode(array_slice($strings, 0, $i)); 
								$part2 = implode(array_slice($strings, $i, count($strings) - $i ));
							
								$relDims1 = $this->calcRelTextSize($svgsrc, $part1);
								$relDims2 = $this->calcRelTextSize($svgsrc, $part2);
								
								$width1 = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims1['width']);
								$width2 = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims2['width']);
								
								if($availableSpace > $width1 && $availableSpace > $width2)
								{
									$outputSize[$size] = $defaultFontSize;
									$outputWidth[$size][0] = $width1;
									$outputWidth[$size][1] = $width2;
									$outputText1 = $part1;
									$outputText2 = $part2;
									$outputYShfit[$size] = 0;
									$pass = true;

									break; 
								}
							}
							continue; // to next size
						}
						
						// use the dashes available
						if($spaces > 0 && !$pass)
						{
							$pos = ceil(strlen($text) / $lines);
							$strings = explode("|", wordwrap($text, $pos, '|'));
							
							for($i = ceil(count($strings)/2); $i > 0; $i++)
							{
								$part1 = implode(array_slice($strings, 0, $i)); 
								$part2 = implode(array_slice($strings, $i, count($strings) - $i ));
								
								$relDims1 = $this->calcRelTextSize($svgsrc, $part1);
								$relDims2 = $this->calcRelTextSize($svgsrc, $part2);
								
								$width1 = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims1['width']);
								$width2 = $this->calcRealFontSize('width', $defaultFontSize, $availableSpace, $fontUnitsEm, $relDims2['width']);
								
								if($availableSpace > $width1 && $availableSpace > $width2)
								{
									$outputSize[$size] = $defaultFontSize;
									$outputWidth[$size][0] = $width1;
									$outputWidth[$size][1] = $width2;
									$outputText1 = $part1;
									$outputText2 = $part2;
									$outputYShfit[$size] = 0;
									$pass = true;
									break;
								}
							}
							continue; // to next size
						}
					}
				}
				
	
				// 1 first fitting attempt
				// reduce letter spacing
				if( !$pass )
				{
					/** 
					 * TODO 
					 * Not yet sure how to properly implement this..
					 */
					$outputTrans = null;
				}
				
				// 1 first fitting attempt
				// reduce text size max 20% and see if it fits
				if( !$pass )
				{
					// using same variables as before ensure we continue from there
					// lower level is now 75% of original font-size
					$lowerBound = 0.75;
					$result = $this->decreaseFont($defaultFontSize, $availableSpace, $fontUnitsEm, $relWidth, $lowerBound, $factor);
					
					if($result)
					{
						$outputSize[$size] = $result['fontsize'];
						$outputWidth[$size][0] = $result['width'];
						$outputWidth[$size][1] = $result['width'];
						//$outputYShfit[$size] = $origHeight - ($result['fontsize'] * $relDims['height'] / $fontUnitsEm); // no vertical shifting of the text
						$outputYShfit[$size] = $origHeight - ($result['fontsize'] * $origHeight / $fontUnitsEm); // no vertical shifting of the text
						$pass = true;
						$factor = $result['factor'];
						
						continue; // to next size
					}
				}
			}
			
			// the chosen transformation passed for each image size with this font
			if( $pass )
			{		
		
				$ar = array(
					'font' => $outputFont,
					'part1' => $outputText1,
					'part2' => $outputText2,
					'size' => $outputSize,
					'width' => $outputWidth,
					'yshift' => $outputYShfit,
					'transformation' => $outputTrans
				);
				return $ar;
			}
			else
			{
				// try another font
				continue;
			}
		}
		
		// if not found return false; 
		return false;
	}	
	
	
	/**
	 * 
	 */
	protected function decreaseFont($defaultFontSize, $availableSpace, $fontUnitsEm, $relWidth, $lowerBound = false, $factor = false)
	{
		$factor = $factor || 1.0; // factor to 	
		$lowerBound = $lowerBound || 0.8; // factor to 	
		$prevTestFontSize = $defaultFontSize;
		
		while( $factor >= $lowerBound )
		{
			// make factor smaller for this interation
			$factor -= 0.01;
			
			// try new values based on new calculated font size
			$testFontSize = round($defaultFontSize * $factor, 0);
			
			// no need for further testing if new font size is same as previous loop due to rounding 
			if($prevTestFontSize == $testFontSize)
			{
				continue;
			}
			
			$prevTestFontSize = $testFontSize;
			$testWidth = $this->calcRealFontSize('width', $testFontSize, $availableSpace, $fontUnitsEm, $relWidth);

			if($availableSpace > $testWidth)
			{						
				return array(
					'fontsize' => $testFontSize,
					'width' => $testWidth,
					'factor' => $factor
				);
			}
		}
		return false;
	}
	
	
	/**
	 *
	 */
	protected function getFontUnitsEm($fontFile)
	{

		if (file_exists(RESOURCEPATH. $fontFile)) 
		{
			$xml = simplexml_load_file(RESOURCEPATH. $fontFile);
			$result = $xml->xpath('//font-face');
			$result = (array)$result[0];
			return $result['@attributes']["units-per-em"];
		}
		else 
		{
			$this->setProgress(999, 'Failed to open font file');
			return false;		
		}
	}
	
	/**
	 * calculate the final font size (em) and width (px) 
	 */	
	protected function calcRealFontSize($which = 'width', $fontSize = 0, $width = 0, $fontUnitsEm = 0, $relWidth = 0)
	{
		// font size
		if($which == 'size')
		{
			$fontSize = $width * $fontUnitsEm / $relWidth;
			return $fontSize;
		}
		
		// dimensions
		if($which == 'width')
		{
			$width = $fontSize * $relWidth / $fontUnitsEm;
			//$height = $fontSize * $relWidth / $fontUnitsEm;
			return $width;
		}
	}	
	
	/**
	 * Calculate the text size (width) for the specified font.
	 * This is the relative size
	 */
	protected function calcRelTextSize($fontFile, $text)
	{
		if (file_exists(RESOURCEPATH. $fontFile)) 
		{		
			$this->load->library('SVGfont');		
			$svgFont = new SVGFont();

			// make textual transformations
			$svgFont->load(RESOURCEPATH . $fontFile);
			$dimensions = $svgFont->getRawTextDimensions($text);
			
			$stringLength = 0;
			$strlen = mb_strlen($text, "UTF-8");
			
			$xml = simplexml_load_file(RESOURCEPATH. $fontFile);
			
			$result = $xml->xpath('//glyph');
			$result = (array)$result; 
			$glyphs = array();
			
			$maxHeight = 0;
			// load glyphs
			foreach($result as $node)
			{
				$node = (array)$node;
				$glyphs[$node['@attributes']['unicode']] = $node['@attributes']['horiz-adv-x'];
			}
			
			// walk through string character by character
			for( $i = 0; $i <= $strlen-1; $i++ ) 
			{
				$char = mb_substr( $text, $i, 1, 'UTF-8' );

				if(key_exists($char, $glyphs))
				{
					$stringLength += $glyphs[$char];
				}
				else
				{
					// some average addition if width could not be found
					$stringLength += 500;
				}
			}
			
			//return $stringLength;	
			return array(
				'width' =>  $stringLength,
				'width2' =>  $dimensions['width'],
				'height' =>  $dimensions['height']
			);
			
		} 
		else 
		{
			$this->setProgress(999, 'Failed to open font file');
			return false;
		}
	}
	
	/**
	 * base ecode
	 * not necessary any more
	
	protected function base64encode_font ($filename=string,$filetype=string) {
		if (file_exists(RESOURCEPATH. $filename))  {
			$binary = fread(fopen(RESOURCEPATH.$filename, "r"), filesize(RESOURCEPATH.$filename));
			
			return 'data:font/' . $filetype . ';charset=utf-8;base64,' . base64_encode($binary);
		}
	}
	*/
	/**
	 * get dimensions of file
	 */
	protected function getSVGDimensions($file)
	{
		$svg = file_get_contents(RESOURCEPATH. $file);
		$xmlget = simplexml_load_string(RESOURCEPATH. $svg);
		$xmlattributes = $xmlget->attributes();
		$width = (string) $xmlattributes->width; 
		$height = (string) $xmlattributes->height;
		
		return array(
			'width' => str_replace('px', '', $width), 
			'height' => str_replace('px', '', $height)
		);
	}
	
	/**
	 * adjust Text
	 */
	protected function imgCreateSVGSource()
	{
		$increase = round ( 9 / ( count($this->settings['colour']) * count($this->settings['size'] ) ), 1 ) ;
		$step = 31;
		$this->load->library('SVGfont');
		$svgFont = new SVGFont();

		foreach($this->settings['size'] as $size)
		{
			$this->setProgress($step, 'Creating source file: '.$size.' for: '.$this->settings['localFont']['part1'].' in size: '.$this->settings['localFont']['size'][$size] );

			$svg = '';
			$sourceFile = ($this->settings['subtext'] != false) ? $this->conf['sourceFile'][$size][1] :  $this->conf['sourceFile'][$size][0];
			// reading file into string

			$svg = file_get_contents(RESOURCEPATH. $sourceFile);
			
			// make textual transformations
			$svgFont->load(RESOURCEPATH . $this->settings['localFont']['font']['svg']);
			
			$LTEXT1 = $svgFont->textToPaths($this->settings['localFont']['part1'], $this->settings['localFont']['size'][$size]);
			$LTEXT2 = $svgFont->textToPaths($this->settings['localFont']['part2'], $this->settings['localFont']['size'][$size]);
			
			// anchor right aligned
			$LANCHOR_X1 = $this->conf['textanchor'][$size]['local']['x'] - $this->settings['localFont']['width'][$size][0];
			$LANCHOR_X2 = $this->conf['textanchor'][$size]['local']['x'] - $this->settings['localFont']['width'][$size][1];
			$LANCHOR_Y1 = $this->conf['textanchor'][$size]['local']['y1'] - $this->settings['localFont']['yshift'][$size];
			$LANCHOR_Y2 = $this->conf['textanchor'][$size]['local']['y2'] - $this->settings['localFont']['yshift'][$size];
			
			$svg = str_replace('{{LTEXT1}}', $LTEXT1, $svg); 
			$svg = str_replace('{{LTEXT2}}', $LTEXT2, $svg); 
			$svg = str_replace('{{LANCHOR_X1}}', $LANCHOR_X1, $svg); 
			$svg = str_replace('{{LANCHOR_X2}}', $LANCHOR_X2, $svg); 
			$svg = str_replace('{{LANCHOR_Y1}}', $LANCHOR_Y1, $svg); 
			$svg = str_replace('{{LANCHOR_Y2}}', $LANCHOR_Y2, $svg); 
			
			if($this->settings['subtext'] != false)
			{
				$svgFont->load(RESOURCEPATH . $this->settings['subextFont']['font']['svg']);

				$STEXT1 = $svgFont->textToPaths($this->settings['subextFont']['part1'], $this->settings['subextFont']['size'][$size]);
				$STEXT2 = $svgFont->textToPaths($this->settings['subextFont']['part2'], $this->settings['subextFont']['size'][$size]);
				
				// anchor in middle
				$SANCHOR_X1 = $this->conf['textanchor'][$size]['subtext']['x'] - ($this->settings['subextFont']['width'][$size][0] / 2);
				$SANCHOR_X2 = $this->conf['textanchor'][$size]['subtext']['x'] - ($this->settings['subextFont']['width'][$size][1] / 2);
				$SANCHOR_Y1 = $this->conf['textanchor'][$size]['subtext']['y1'] - $this->settings['subextFont']['yshift'][$size];
				$SANCHOR_Y2 = $this->conf['textanchor'][$size]['subtext']['y2'] - $this->settings['subextFont']['yshift'][$size];
				
				$svg = str_replace('{{STEXT1}}', $STEXT1, $svg); 
				$svg = str_replace('{{STEXT2}}', $STEXT2, $svg); 
				$svg = str_replace('{{SANCHOR_X1}}', $SANCHOR_X1, $svg); 
				$svg = str_replace('{{SANCHOR_X2}}', $SANCHOR_X2, $svg); 
				$svg = str_replace('{{SANCHOR_Y1}}', $SANCHOR_Y1, $svg); 
				$svg = str_replace('{{SANCHOR_Y2}}', $SANCHOR_Y2, $svg); 
				
			}
			
			// make colour transformations
			// for each colour save it into destination folder
			foreach($this->settings['colour'] as $colour)
			{
				$this->setProgress($step, 'Creating source file: '.$size.','. $colour );
				$step += $increase;
				
				// edit the colours, use original template svg
				$colouredSVG = $svg;

				$colouredSVG = str_replace('{{COLOR}}', $this->conf['srcColours'][$colour][0], $colouredSVG); 
				$colouredSVG = str_replace('{{BGCOLOR}}', $this->conf['srcColours'][$colour][1], $colouredSVG); 

				if($this->settings['subtext'] != false)
				{
					$this->settings['files'][$colour][$size] = 'Logo '.$this->settings['citySafe'].'_caption_'.$colour.'_'.$size;
				}
				else
				{
					$this->settings['files'][$colour][$size] = 'Logo '.$this->settings['citySafe'].'_'.$colour.'_'.$size;
				}
				
				$destinationFile = $this->getFolder().'/source/'. $this->settings['files'][$colour][$size].'.svg';
				file_put_contents ($destinationFile, $colouredSVG);
				
				// zip svg file as well
				if(in_array('svg',$this->settings['format']))
				{
					$this->settings['files']['zip'][] = array($destinationFile, $this->settings['cityName'], $size, $colour, 'svg');
				}
				
				$this->settings['svgSource'][$colour][$size] = $colouredSVG;

			}
			$this->settings['svgSource'][$size] = $svg;

		}
	}	

	/**
	 * Create Transparent PNG using Imagemagick
	 */
	protected function imgCreatePNG($format = 'png')
	{
		
		$increase = round ( 9 / ( count($this->settings['colour']) * count($this->settings['size'] ) ), 1 ) ;
		$step = 41;
		
		foreach($this->settings['colour'] as $colour)
		{
			foreach($this->settings['size'] as $size)
			{
				
				$this->setProgress($step, 'Creating PNG: '.$size.', '.$colour);
				$step += $increase;
				
				try{
					$im = new Imagick();
					
					// force png backgrounds to be transparent
					$im->setBackgroundColor(new ImagickPixel('transparent'));
					
					$imageBlob = str_replace($this->conf['srcColours'][$colour][1], 'none', $this->settings['svgSource'][$colour][$size]); 
					$im->readImageBlob($imageBlob);
					$im->paintTransparentImage( $im->getImagePixelColor(1, 1), 0, 500);

					$im->setImageFormat("png32");
		
					$im->setImageProperty('Exif:Copyright', 'AEGEE-Europe '.date('Y'));
					$im->setImageProperty('Exif:Artist', 'Maurits Korse');
					$im->setImageProperty('Exif:ImageDescription', 'Logo '.$this->settings['localFull']);
					$im->setImageProperty('Exif:DateTime', date('Y:m:d H:i:s'));
					$im->setImageProperty('Exif:Make', 'Automated');
					 
					$filename = $this->getFolder().'/'.$colour.'/'. $this->settings['files'][$colour][$size].'.png';
					
					$im->writeImage($filename); 
					$this->settings['files']['zip'][] = array($filename, $this->settings['cityName'], $size, $colour, 'png');
					
				} catch (Exception $e) {
					$this->setProgress(999, 'Could not create PNG');
					return false;
				}
				
			}
		}
		return true;
	}
	
	/**
	 * Create JPEG using Imagemagick
	 */
	protected function imgCreateJPEG()
	{
		$increase = round ( 9 / ( count($this->settings['colour']) * count($this->settings['size'] ) ), 1 ) ;
		$step = 51;
		
		foreach($this->settings['colour'] as $colour)
		{
			foreach($this->settings['size'] as $size)
			{
				$this->setProgress($step, 'Creating JPEG: '.$size.', '.$colour);
				$step += $increase;

				try{
					$im = new Imagick();

					$im->readImageBlob($this->settings['svgSource'][$colour][$size]);

					$im->setCompression(Imagick::COMPRESSION_JPEG); 
					$im->setCompressionQuality(100); 
					$im->setImageFormat('jpeg'); 
					
					$im->setImageProperty('Copyright', 'AEGEE-Europe '.date('Y'));
					$im->setImageProperty('Artist', 'Maurits Korse');
					$im->setImageProperty('Title', 'Logo '.$this->settings['localFull']);
					$im->setImageProperty('DateTime', date('Y:m:d H:i:s'));
					
					$im->setImageProperty('Exif:Copyright', 'AEGEE-Europe '.date('Y'));
					$im->setImageProperty('Exif:Artist', 'Maurits Korse');
					$im->setImageProperty('Exif:ImageDescription', 'Logo '.$this->settings['localFull']);
					$im->setImageProperty('Exif:DateTime', date('Y:m:d H:i:s'));
					$im->setImageProperty('Exif:Make', 'Automated');			
					
					$filename = $this->getFolder().'/'.$colour.'/'. $this->settings['files'][$colour][$size].'.jpg';

					$im->writeImage($filename); 
					$this->settings['files']['zip'][] = array($filename, $this->settings['cityName'], $size, $colour, 'jpg');
					
				} catch (Exception $e) {
					$this->setProgress(999, 'Could not create JPG');
					return false;
				}
			}
		}
		return true;
	}	
	
	/**
	 * Create PDF using Inkscape
	 */
	protected function imgCreatePDF()
	{
		$increase = round ( 9 / ( count($this->settings['colour']) * count($this->settings['size'] ) ), 1 ) ;
		$step = 61;
		
		foreach($this->settings['colour'] as $colour)
		{
			foreach($this->settings['size'] as $size)
			{
				$this->setProgress($step, 'Creating PDF: '.$size.', '.$colour);
				$step += $increase;
				
				try{
					$sourceFile = $this->getFolder().'/source/'. $this->settings['files'][$colour][$size].'.svg';
					$filename = $this->getFolder().'/'.$colour.'/'. $this->settings['files'][$colour][$size].'.pdf';
					
					$command = 'inkscape -A "'. $filename .'" "'. $sourceFile.'" --without-gui';
					exec($command, $output, $return);
					
					$this->settings['files']['zip'][] = array($filename, $this->settings['cityName'], $size, $colour, 'pdf');
				} catch (Exception $e) {
					$this->setProgress(999, 'Could not create PDF');
					return false;
				}
			}
		}
	
		return true;
	}	

	/**
	 * Create EPS using Inkscape
	 */
	protected function imgCreateEPS()
	{
		$increase = round ( 9 / ( count($this->settings['colour']) * count($this->settings['size'] ) ), 1 ) ;
		$step = 71;
		
		foreach($this->settings['colour'] as $colour)
		{
			foreach($this->settings['size'] as $size)
			{
				$this->setProgress($step, 'Creating EPS: '.$size.', '.$colour);
				$step += $increase;
				
				try{
					$sourceFile = $this->getFolder().'/source/'. $this->settings['files'][$colour][$size].'.svg';
					$filename = $this->getFolder().'/'.$colour.'/'. $this->settings['files'][$colour][$size].'.eps';
					
					$command = 'inkscape -E "'. $filename .'" "'. $sourceFile.'" --without-gui';
					exec($command, $output, $return);
					
					$this->settings['files']['zip'][] = array($filename, $this->settings['cityName'], $size, $colour, 'eps');

				} catch (Exception $e) {
					$this->setProgress(999, 'Could not create EPS');
					return false;
				}
			}
		}
	
		return true;
	}	
	
	/**
	 * Get folders
	 */
	protected function getFolder($citySafe = null)
	{
		$citySafe = empty($citySafe) ? $this->settings['citySafe'] : $citySafe;
		if($citySafe == null){
			$this->setProgress(1000, 'settings not available');
			return false;
		}
		return DOWNLOADPATH . 'logos/' . $citySafe;
	}
	
	/**
	 * Create the folder where the files will be stored
	 */
	protected function createFolders()
	{
		$folders['target'] = $this->getFolder();
		$folders['source'] = $this->getFolder() .'/'. 'source';
		foreach($this->settings['colour'] as $colour)
		{
			$folders[$colour] = $folders['target'] .'/'. $colour;
		}
		
		$this->setProgress(27, 'Create folders');
		
		$ret = true;
		foreach($folders as $name => $folder)
		{
			if (!is_dir( $folder )) 
			{
				mkdir($folder );
				chmod($folder, 0777);
			}
		}
		return true;
	}
	
	/**
	 * Zip all the files
	 * return filename of package
	 */
	protected function package()
	{
		$overwrite = false;
		$this->setProgress(90, 'Zipping files into archive');
		$destination = $this->getFolder() . '/'. $this->settings['token']. '.zip';
		
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) ) { 
			$overwrite = true;
		}
		
		// include AEGEE-Europe logos
		$includeExtra = false;
		if($this->settings['extra'])
		{
			$this->setProgress(91, 'Adding extra files to download');
			if(array_key_exists($this->settings['extra'], $this->conf['extraFiles']))
			{
				$includeExtra = true;
				$extra = $this->conf['extraFiles'][$this->settings['extra']];
				$this->settings['files']['zip'] = array_merge((array)$this->settings['files']['zip'], (array)$extra);
			}
		}
		
		//vars
		$valid_files = array();
		
		//if files were passed in...
		if(is_array($this->settings['files']['zip'])) 
		{
			//cycle through each file
			foreach($this->settings['files']['zip'] as $file) 
			{
	
				//make sure the file exists
				if(file_exists($file[0])) 
				{
					$valid_files[] = $file;
				}
			}
		}
		
		//if we have good files...
		if(count($valid_files)) 
		{
			$this->setProgress(92, 'Creating Zip archive');
			//create the archive
			$zip = new ZipArchive();
			$res = $zip->open($destination, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );

			if($res !== true) 
			{
				$this->setProgress(999, 'Opening Zip archive caused an error ('.$this->zipMessage($res).') '.$destination.'; '.$overwrite.'');
				return false;
			}
			
			$nFiles = count($valid_files);
			//add the files
			$this->setProgress(93, 'Zipping...');
			foreach($valid_files as $file) 
			{
				$fileName = explode("/", $file[0]);
				
				// use local directories
				$subdir = '';
				$local = '';
				if($includeExtra)
				{
					$local = $this->sanitizeSpecialChars($file[1]);
					$subdir = ucwords($local).'/';
				}
			
				// in case of few files or the incluion of the AEGEE-Europe logos keep folder structure simple
				if($nFiles <= 8 || strtolower($local) == 'europe')
				{
					$targetName = $subdir.$fileName[count($fileName)-1];
				}
				elseif($nFiles <= 16 )
				{
					// by colour
					$targetName = $subdir.ucwords($file[3]).'/'.$fileName[count($fileName)-1];
				}
				elseif($nFiles <= 32 )
				{
					// by colour / size
					$targetName = $subdir.ucwords($file[3]).'/'.ucwords($file[2] ).'/'.$fileName[count($fileName)-1];
				}			
				else
				{
					// by colour / size / type
					$targetName = $subdir.ucwords($file[3]).'/'.ucwords($file[2] ).'/'.ucwords($file[4] ).'/'.$fileName[count($fileName)-1];				
				}			
				
				$zip->addFile($file[0], $targetName);
			}
			
			//close the zip -- done!
			$res2 = $zip->close();
			
			$this->setProgress(99, 'Finished zipping files');

			if($res2 !== true)
			{
				$this->setProgress(999, 'Zipping files caused an error');
				return false;
			}
		}
		else
		{
			$this->setProgress(999, 'Files are not listed ('.count($valid_files).' : '.print_r($valid_files, true).')');
			return false;
		}
		$this->setProgress(100, 'Done!');
	}

	/**
	 * get the zip file and create the target name for the download
	 * NOT IN LOGP GENERATION PATH
	 */
	public function getZipFilenames($token)
	{
		$data = $this->getProgress($token);
		$localFull = $this->getLocalFull($data['bodyCode']);
		
		$sourceFileZIP = $this->getFolder($localFull['citySafe']) . '/'.$token. '.zip';
		$virtualNameZIP = 'Logos '. $localFull['citySafe']. '.zip';
		if(file_exists($sourceFileZIP))
		{
			return array('source' => $sourceFileZIP, 'target' => $virtualNameZIP);
		}
		
		// file does not exist so nothing to return
		return false;
	}
	
	/**
	 *
	 */
	protected function initProgress($token, $bodyCode, $status = 0, $message = 'initiating')
	{ 
		$this->settings['step'] = 0;
		$this->settings['totalSteps'] = 100;
		$this->settings['messageLog'] = $message;
		$dbdata = array(
			'token' => $token,
			'status' => $status,
			'message' => $this->settings['messageLog'],
			'messageLog' => $message,
			'step' => $this->settings['step'],
			'totalSteps' => $this->settings['totalSteps'],
			'bodyCode' => $bodyCode
			//'settings' => serialize($this->settings)
		);

		if(!$this->db->insert('generator', $dbdata)){
		
			return false;
		}
		else{
			return $this->db->affected_rows();
		
		}
		
	}
	
	/**
	 * Update Progress in Session for feedback
	 */
	protected function setProgress($status = 0, $message = '', $token = false)
	{
		// error occured in which session data could not be retrieved
		// or external trigger cancels session, hence settings array is not available
		// Only return status and message
		if($status == 1000 || $status == -1){ 
			$dbdata = array(
				'status' => $status,
				'message' => $message
			);
		}
		
		// data from session is still available
		else{
			$this->settings['messageLog'] .= "\n[{$status}] {$message}";
			
			$token = ($token != false) ? $token : $this->token;
			$oldData = $this->getProgress($token);
			$this->settings['step']++;
			
			$totalSteps = ( array_key_exists('totalSteps', $this->settings) ) ? $this->settings['totalSteps'] : 100;
			$bodyCode = ( array_key_exists('bodyCode', $this->settings) ) ? $this->settings['bodyCode'] : '';
			$initTime = ( array_key_exists('initTime', $this->settings) ) ? $this->settings['initTime'] : $this->microtimeFloat() ;
			$curTime = $this->microtimeFloat();
			
			$data = array(
				'status' => $status,
				'step'  => $this->settings['step'],
				'totalSteps' => $totalSteps,
				'message' => $message,
				'messageLog' => $this->settings['messageLog'],
				'bodyCode' => $bodyCode,
				'initTime' => $initTime,
				'curTime' => $curTime
			);
			
			$data = array_merge($oldData, $this->settings, $data);
			
			$dbdata = array(
				'status' => $data['status'],
				'step' => $data['step'],
				'message' => $data['message'],
				'messageLog' => $data['messageLog'],
				'totalSteps' => $data['totalSteps'],
				'bodyCode' => $data['bodyCode'],
				'execTime' => sprintf("%.4f", ($data['curTime'] - $data['initTime']))
			);
		}
		$this->db->where('token', $token)->update('generator', $dbdata);
  
		$affRows = $this->db->affected_rows();
		
		return $affRows;
	}
	
	/**
	 * Get Progress from Session for feedback
	 */
	public function getProgress($token = null)
	{
		$token = ($token !== null) ? $token : $this->token;
		
		$query = $this->db->query('SELECT token, status, step, totalSteps, bodyCode, message, timestamp FROM generator WHERE token = "'.$token.'" LIMIT 1');

		// if current generation request was not initiated yet...
		if($query->num_rows() == 0){
			$totalSteps = ( array_key_exists('totalSteps', $this->settings) ) ? $this->settings['totalSteps'] : 100;

			$data = array(
				'token' => $token,
				'status' => 0,
				'step'  => 0,
				'message' => 'Initiating...',
				'totalSteps' => $totalSteps,
				'bodyCode' => '',
				'messageLog' => '',
				'timestamp' => '',
			);
			
			return $data;
		}
		
		$array = $query->row_array();
		
		return $array;
	}
	
	/**
	 * remove old files
	 */
	protected function rmArchives()
	{
		$dir = $this->getFolder();
		if(file_exists($dir))
		{
			$files = scandir($dir);
			foreach($files as $file)
			{
				if($file === '.' || $file === '..') {continue;} 
				if(is_file($dir.'/'.$file))
				{
					$fileTime = filectime($dir.'/'.$file);
					if( time() - $fileTime > (60*60*1)  )
					{
						if (preg_match('/\.zip$/i', $file)) 
						{
							unlink($dir.'/'.$file);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Remove accents from characters
	 * usage for file names
	 */	
	protected function sanitizeSpecialChars($str)
	{
		$unwanted_array = array(   
			'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
			'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
			'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ,
			'Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u',
			'ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T',
			'Ł'=>'L', 'Ą'=>'A', 'Ń'=>'N', 'Ę'=>'E', 'Ż'=>'Z', 
			'ł'=>'l', 'ą'=>'a', 'ę'=>'e', 'ė'=>'e', 'ź'=>'z', 'ż'=>'z', 'ó'=>'o',
			'Ǭ'=>'O', 'ǭ'=>'o', 'Ų'=>'U', 'ų'=>'u', 'Ą̊'=>'A', 'ą̊'=>'a', 'Į'=>'I', 'į'=>'i', 'Ǫ'=>'O', 'ǫ'=>'o', 'Y̨'=>'Y', 'y̨'=>'y',
			'ə'=>'e'
		);
		$search = array_keys($unwanted_array);
		$replace = array_values($unwanted_array);
		
		return  str_replace($search, $replace, $str);
	}
	
	/**
	 * Safe string to upper function which includes accented characters
	 */	
	protected function strtouppertr($str)
	{ 
		return mb_convert_case($str, MB_CASE_UPPER, "UTF-8"); 
	} 
	
	/**
	 * Remove AEGEE- prefix
	 */	
	protected function rmPrefix($str)
	{ 
		return substr($str, 6);
	} 

	/**
	 * Zip error messages
	 */
	public function zipMessage($code)
	{
		switch ($code)
		{
			case 0:
			return 'No error';
			
			case 1:
			return 'Multi-disk zip archives not supported';
			
			case 2:
			return 'Renaming temporary file failed';
			
			case 3:
			return 'Closing zip archive failed';
			
			case 4:
			return 'Seek error';
			
			case 5:
			return 'Read error';
			
			case 6:
			return 'Write error';
			
			case 7:
			return 'CRC error';
			
			case 8:
			return 'Containing zip archive was closed';
			
			case 9:
			return 'No such file';
			
			case 10:
			return 'File already exists';
			
			case 11:
			return 'Can\'t open file';
			
			case 12:
			return 'Failure to create temporary file';
			
			case 13:
			return 'Zlib error';
			
			case 14:
			return 'Malloc failure';
			
			case 15:
			return 'Entry has been changed';
			
			case 16:
			return 'Compression method not supported';
			
			case 17:
			return 'Premature EOF';
			
			case 18:
			return 'Invalid argument';
			
			case 19:
			return 'Not a zip archive';
			
			case 20:
			return 'Internal error';
			
			case 21:
			return 'Zip archive inconsistent';
			
			case 22:
			return 'Can\'t remove file';
			
			case 23:
			return 'Entry has been deleted';
			
			default:
			return 'An unknown error has occurred('.intval($code).')';
		}                
	}

	private function  microtimeFloat()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
} 