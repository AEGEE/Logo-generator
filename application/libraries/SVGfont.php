<?php

/**
 * This class represents SVG pa
 * @author Lukasz Ledóchowski lukasz@ledochowski.pl
 * @version 0.1
 */
class SVGFont {

    protected $id = '';
    protected $horizAdvX = 0;
    protected $unitsPerEm = 0;
    protected $ascent = 0;
    protected $descent = 0;
    protected $glyphs = array();

    /**
     * Function takes UTF-8 encoded string and returns unicode number for every character.
     * Copied somewhere from internet, thanks.
     */
    function utf8ToUnicode( $str ) {
        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen( $str ); $i++ ) {
            $thisValue = ord( $str[ $i ] );
            if ( $thisValue < 128 ) $unicode[] = $thisValue;
            else {
                if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
                $values[] = $thisValue;
                if ( count( $values ) == $lookingFor ) {
                    $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                        ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;
                }
            }
        }

        return $unicode;
    }

    /**
     * Function takes path to SVG font (local path) and processes its xml
     * to get path representation of every character and additional
     * font parameters
     */
    public function load($filename) {
        $this->glyphs = array();
        $z = new XMLReader;
        $z->open($filename);

        // move to the first <product /> node
        while ($z->read()) {
            $name = $z->name;

            if ($z->nodeType == XMLReader::ELEMENT) {
                if ($name == 'font') {
                    $this->id = $z->getAttribute('id');
                    $this->horizAdvX = $z->getAttribute('horiz-adv-x');
                }

                if ($name == 'font-face') {
                    $this->unitsPerEm = $z->getAttribute('units-per-em');
                    $this->ascent = $z->getAttribute('ascent');
                    $this->descent = $z->getAttribute('descent');
                }

                if ($name == 'glyph') {
                    $unicode = $z->getAttribute('unicode');
                    $unicode = $this->utf8ToUnicode($unicode);
                    $unicode = $unicode[0];

                    $this->glyphs[$unicode] = new stdClass();
                    $this->glyphs[$unicode]->horizAdvX = $z->getAttribute('horiz-adv-x');
                    if (empty($this->glyphs[$unicode]->horizAdvX)) {
                        $this->glyphs[$unicode]->horizAdvX = $this->horizAdvX;
                    }
                    $this->glyphs[$unicode]->d = $z->getAttribute('d');
                }
            }
        }

    }

    /**
     * Function takes UTF-8 encoded string and size, returns xml for SVG paths representing this string.
     * @param string $text UTF-8 encoded text
     * @param int $asize size of requested text
     * @return string xml for text converted into SVG paths
     */
    public function textToPaths($text, $asize) {
        $lines = explode("\n", $text);
        $result = "";
        $horizAdvY = 0;
        foreach($lines as $text) {
            $text = $this->utf8ToUnicode($text);
            $size =  ((float)$asize) / $this->unitsPerEm;
            $result .= "<g transform=\"scale({$size}) translate(0, {$horizAdvY})\">";
            $horizAdvX = 0;
            for($i = 0; $i < count($text); $i++) {
                $letter = $text[$i];
                $result .= "<path transform=\"translate({$horizAdvX},{$horizAdvY}) rotate(180) scale(-1, 1)\" d=\"{$this->glyphs[$letter]->d}\" />";
                $horizAdvX += $this->glyphs[$letter]->horizAdvX;
            }
            $result .= "</g>";
            $horizAdvY += $this->ascent + $this->descent;
        }

        return $result;
    }

	/**
	 *
	 */
	public function getRawTextDimensions($text){
		$lines = explode("\n", $text);
        //$result = "";
        //$horizAdvY = 0;
		$widths = $heights = $lineDims = array();

        foreach($lines as $line => $text) {
			
            $text = $this->utf8ToUnicode($text);
            for($i = 0; $i < count($text); $i++) {
                $letter = $text[$i];
				$dims = $this->getPathDimension( $this->glyphs[$letter]->d );
                //$horizAdvX += $this->glyphs[$letter]->horizAdvX;
				
				$widths[$line][] = $dims['width'];
				$heights[$line][] = $dims['height'];

            }
			$lineDims['width'][] = array_sum($widths[$line]);
			$lineDims['height'][] = array_sum($heights[$line]);
        }

		return array(
			'width' => array_sum($lineDims['width']),
			'height' => array_sum($lineDims['height'])
		);
	}
	
	/**
	 *
	 */
	public function getGlyphDimensions($glyph){
		$glyph = $this->utf8ToUnicode($glyph);
		return $this->getPathDimension( $this->glyphs[$letter]->d );
	}
	
	/**
	 *
	 */
	public function getPathDimension($string){
		$tempCoords = array(); // i => [0 => fn, 1 => [[x,y],[x,y],[x,y]] ]
		$xCoordAr = array();
		$yCoordAr = array();
		$pointer = array('x'=>0,'y'=>0);

		// compose into proper array
		preg_match_all('/([mlvhcsqtaz][^mlvhcsqtaz]*)/i', $string, $output);

		foreach ($output[0] as $key => $pathDef) {

			$command = substr($pathDef, 0, 1); // get first char
			$coordsString = substr($pathDef, 1);
			$numCoords = substr_count($coordsString, ' ');
			
			// make array of all coords
			if($numCoords > 0){
				$coords = explode(" ", $coordsString);	
			} else {
				// only one coord in string
				$coords = array($coordsString);
			}
			
			$ar = array();
			foreach($coords as $coord) {
				list($x, $y) = explode(",", $coord);
				$x = (int) trim($x);
				$y = (int) trim($y);
				$ar[] = array('x'=>$x,'y'=>$y);
			}
			$tempCoords[] = array($command, $ar);
		}

		// make all absolute
		foreach($tempCoords as $key => $val)
		{

			$command = $val[0];
			$coords = $val[1];
			// coord is relative to previous
			if( strtolower($command) == $command ){
				// relative paths
				switch ($command) {
					// moveto, lineto, verticalto, horizontalto
					case 'm' : //moveto
					case 'l' :
					case 'v' :
					case 'h' :
						// only one element in coords
						$absX = $pointerX = $coords[0]['x'] + $pointer['x'];
						$absY = $pointerY = $coords[0]['y'] + $pointer['y'];
						
						$xCoordAr[] = $absX;
						$yCoordAr[] = $absY;	
						
						break;
					// curveto, smooth curveto, quadratic bézier curve, smooth quadratic bézier curveto
					case 'c' : //moveto
					case 's' :
					case 'q' :
					case 't' :
						// get end point of bezier
						$endCoord = $coords[count($coords)-1];
						
						// starting point of bezier
						$pointerTemp = $startCoord = $pointer;
						
						// making the relative coords temporary absolute to calc Bezier
						$absCoords = array_walk($coords, function(&$value, $key, &$pointerTemp){
							$value['x'] = $pointerTemp['x'] = $value['x'] + $pointerTemp['x'];
							$value['y'] = $pointerTemp['y'] = $value['y'] + $pointerTemp['y'];
						});
						$absMaxMinCoords = $this->calcExtremesBezier(array_merge(array($startCoord), $absCoords));
						
						$xCoordAr[] = $absMaxMinCoords['xmin'];
						$xCoordAr[] = $absMaxMinCoords['xmax'];
						$yCoordAr[] = $absMaxMinCoords['ymin'];
						$yCoordAr[] = $absMaxMinCoords['ymax'];
						
						$pointerX = $endCoord['x'];
						$pointerY = $endCoord['y'];
						
						break;
					case 'A' :
						// todo
						break;
					case 'z' :
						break;
				}
			}

			// absolute paths
			else{
				switch ($command) {
					// moveto, lineto, verticalto, horizontalto
					case 'M' : 
					case 'L' :
					case 'V' :
					case 'H' :
						// only one element in coords
						$absX = $pointerX = $coords[0]['x'];
						$absY = $pointerY = $coords[0]['y'];
						
						$xCoordAr[] = $absX;
						$yCoordAr[] = $absY;						
						break;
					// curveto, smooth curveto, quadratic bézier curve, smooth quadratic bézier curveto
					case 'C' : //moveto
					case 'S' :
					case 'Q' :
					case 'T' :
						// end point of bezier
						$endCoord = $coords[count($coords)-1];
						
						// starting point of bezier
						$startCoord = $pointer;

						$absMaxMinCoords = $this->calcExtremesBezier(array_merge(array($startCoord), $coords));
						
						// save extremes
						$xCoordAr[] = $absMaxMinCoords['xmin'];
						$xCoordAr[] = $absMaxMinCoords['xmax'];
						$yCoordAr[] = $absMaxMinCoords['ymin'];
						$yCoordAr[] = $absMaxMinCoords['ymax'];
						
						$pointerX = $endCoord['x'];
						$pointerY = $endCoord['y'];
						
						break;
					case 'A' :
						// todo
						break;
					case 'Z' :
					
						break;
				}
			}
			$pointer = array('x' => $pointerX, 'y' => $pointerY);
		}

		$ret = array(
			'x' => array(
				'min' => min($xCoordAr),
				'max' => max($xCoordAr),
				'all' => $xCoordAr
			),
			'y' => array(
				'min' => min($yCoordAr),
				'max' => max($yCoordAr),
				'all' => $yCoordAr
			),
			'width' => (max($xCoordAr) - min($xCoordAr)),
			'height' => (max($yCoordAr) - min($yCoordAr))
		);
	}
	
	/**
	 * @param p array of coords 
	 * @author Maurits
	 * http://stackoverflow.com/questions/5634460/quadratic-bezier-curve-calculate-point
	 */
	public function calcExtremesBezier($p){

		$resolution = 100; // step size
		$xmin = 0; $xmax = 0; $ymin = 0; $ymax = 0;
		for ($i = 0; $i <= $resolution; $i++){
			$t = $i / $resolution;
			// Quadratic
			if(count($p) == 3){
				$x = (1 - $t) * (1 - $t) * $p[0]['x'] + 2 * (1 - $t) * $t * $p[1]['x'] + $t * $t * $p[2]['x'];
				$y = (1 - $t) * (1 - $t) * $p[0]['y'] + 2 * (1 - $t) * $t * $p[1]['y'] + $t * $t * $p[2]['y'];
			}
			// Qubic
			if(count($p) == 4){
				//B(t) = (1-t)^3 p0 + 3(1 - t)^2 t P1 + 3(1-t)t^2 P2 + t^3 P3
				$x = (1 - $t) * (1 - $t) *( 1 - $t) * $p[0]['x'] + 3 * ( 1 - $t ) * ( 1 - $t ) * $t * $p[1]['x'] + 3 * ( 1 - $t ) * $t * $t * $p[2]['x'] + $t * $t * $t * $p[3]['x'];
				$y = (1 - $t) * (1 - $t) *( 1 - $t) * $p[0]['y'] + 3 * ( 1 - $t ) * ( 1 - $t ) * $t * $p[1]['y'] + 3 * ( 1 - $t ) * $t * $t * $p[2]['y'] + $t * $t * $t * $p[3]['y'];
			}
			
			$xmin = ($x < $xmin) ? $x : $xmin; 
			$xmax = ($x > $xmax) ? $x : $xmax; 
			$ymin = ($y < $ymin) ? $y : $ymin;
			$ymax = ($y > $ymax) ? $y : $ymax;
		}
		
		$ret = array(
			'xmin' => $xmin,
			'xmax' => $xmax,
			'ymin' => $ymin,
			'ymax' => $ymax
		);
		
		return $ret;
	}
	
}