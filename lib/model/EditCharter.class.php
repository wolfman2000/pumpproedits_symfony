<?php

class EditCharter
{
  private $single;
  private $double;
  private $lb; # Left buffer
  private $rb; # Right buffer
  private $cw; # Width of column.
  private $aw; # Arrow width
  private $bm; # Beats per measure
  function __construct($params)
  {
    $this->single = sfConfig::get('app_chart_single_cols');
    $this->double = sfConfig::get('app_chart_double_cols');
    
    if (!in_array($params['cols'], array($this->single, $this->double)))
    {
      $e = "There must be either $single or $double columns in the chart!";
      throw new sfParseException($e);
    }
    else
    {
      $this->lb = sfConfig::get('app_chart_column_left_buffer');
      $this->rb = sfConfig::get('app_chart_column_right_buffer');
      $this->aw = sfConfig::get('app_chart_arrow_width');
      $this->bm = sfConfig::get('app_chart_beat_p_measure');
      if (array_key_exists('nobpm', $params))
      {
        $this->nobpm = 1;
      }
      if (array_key_exists('nostop', $params))
      {
        $this->nostop = 1;
      }
      
      $this->headheight = sfConfig::get('app_chart_header_height');
      $this->footheight = sfConfig::get('app_chart_footer_height');
      if (array_key_exists('footer_height', $params))
      {
        $this->footheight = $params['footer_height'];
      }
      
      // Allow speed mods in play.
      $this->speedmod = sfConfig::get('app_chart_speed_mod');;
      if (array_key_exists('speed_mod', $params))
      {
        $this->speedmod = $params['speed_mod'];
      }
      
      // How many measures are shown in a column?
      $this->mpcol = sfConfig::get('app_chart_measures_col');
      if (array_key_exists('mpcol', $params))
      {
        $this->mpcol = $params['mpcol'];
      }
      
      $this->cols = $params['cols'];
      $this->cw = $this->cols * $this->aw;
            
      $this->xml = new DomDocument("1.0", "UTF-8");
      $this->xml->preserveWhiteSpace = false;
      $this->xml->formatOutput = true; # May change this.
    }
  }

  private function genUseNode($x, $y, $id, $class = '', $sx = 1, $sy = 1)
  {
    $base = sfConfig::get('app_chart_def_file');
    $use = $this->xml->createElement('use');
    if ($x > 0)
    {
      $use->setAttribute('x', $x);
    }
    if ($y > 0)
    {
      $use->setAttribute('y', $y);
    }
    $use->setAttribute('xlink:href', "$base#$id");
    if (strlen($class) > 1)
    {
      $use->setAttribute('class', "$class");
    }
    if (!($sx === 1 and $sy === 1))
    {
      $use->setAttribute('transform', "scale($sx $sy)");
    }  
    return $use;
  }
  
  private function genSVGNode($x, $y, $id, $class = '', $sx = 1, $sy = 1)
  {
    $svg = $this->xml->createElement('svg');
    $svg->setAttribute('x', $x);
    $svg->setAttribute('y', $y);
    $svg->appendChild($this->genUseNode(0, 0, $id, $class, $sx, $sy));
    return $svg;
  }

  private function genXMLHeader($measures)
  {
    $cont = 'type="text/css" href="/css/_svg.css"';
    $css = $this->xml->createProcessingInstruction('xml-stylesheet',$cont);
    $this->xml->appendChild($css);
  
    $svg = $this->xml->createElement('svg');
    $svg->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    $svg->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $svg->setAttribute('version', 1.1);
    
    // Calculate the width of the outer svg.
    $numcols = ceil($measures / $this->mpcol);
    $breather = $this->lb + $this->rb;
    $width = ($this->aw * $this->cols + $breather) * $numcols + $breather;
    $svg->setAttribute('width', $width);
    
    // Calculate the height of the outer svg.
    $beatheight = sfConfig::get('app_chart_beat_height');
    //$bpm = sfConfig::get('app_beat_p_measure');
        
    $height = $beatheight * $this->bm * $this->speedmod * $this->mpcol;
    $height += $this->headheight + $this->footheight;
    $svg->setAttribute('height', $height);
    $this->svgheight = $height;
    
    $this->xml->appendChild($svg);
    $this->svg = $svg; # Will be used for arrow placements.
  }
  
  private function genMeasures($measures)
  {
    $numcols = ceil($measures / $this->mpcol); // mpcol is measures per column
    $beatheight = sfConfig::get('app_chart_beat_height'); // default beat height
    $spd = $this->speedmod; // speed mod: also affects columns.
    $breather = $this->lb + $this->rb;
    $id = "measure";
    for ($i = 0; $i < $numcols; $i++)
    {
      $x = ($this->aw * $this->cols + $breather) * $i + $breather;
      $sx = $this->cols;
      for ($j = 0; $j < $this->mpcol * $this->speedmod; $j++)
      {
        $y = $beatheight * $j * $this->bm + $this->headheight;
        $use = $this->genSVGNode($x, $y, $id, '', $sx);
        $this->svg->appendChild($use);
      }
    }
  }
  
  private function genEditHeader($nd)
  {
    $text = $this->xml->createElement('text');
    $lbuff = $this->lb;
    $text->setAttribute('x', $lbuff);
    $text->setAttribute('y', 16);
    $song = Doctrine::getTable('PPE_Song_Song')->getSongByID($nd['id']);
    $st = sprintf("%s Edit for %s: %s - %s",
      ucfirst(substr($nd['style'], 5)), $song, $nd['title'], $nd['diff']);
    $text->appendChild($this->xml->createTextNode($st));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff);
    $text->setAttribute('y', 48);
    $text->appendChild($this->xml->createTextNode("Steps: " .$nd['steps']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff);
    $text->setAttribute('y', 80);
    $text->appendChild($this->xml->createTextNode("Jumps: " .$nd['jumps']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 1);
    $text->setAttribute('y', 48);
    $text->appendChild($this->xml->createTextNode("Holds: " .$nd['holds']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 1);
    $text->setAttribute('y', 80);
    $text->appendChild($this->xml->createTextNode("Mines: " .$nd['mines']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 2);
    $text->setAttribute('y', 48);
    $text->appendChild($this->xml->createTextNode("Trips: " .$nd['trips']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 2);
    $text->setAttribute('y', 80);
    $text->appendChild($this->xml->createTextNode("Rolls: " .$nd['rolls']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 3);
    $text->setAttribute('y', 48);
    $text->appendChild($this->xml->createTextNode("Lifts: " .$nd['lifts']));
    $this->svg->appendChild($text);
    
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', $lbuff + ($this->cw + $this->lb + $this->rb) * 3);
    $text->setAttribute('y', 80);
    $text->appendChild($this->xml->createTextNode("Fakes: " .$nd['fakes']));
    $this->svg->appendChild($text);
  }
  
  private function genBPM($id)
  {
    $buff = $this->lb + $this->rb;
    $draw = $this->cols * $this->aw / 2;
    foreach (Doctrine::getTable('PPE_Song_BPM')->getBPMsBySongID($id) as $b)
    {
      $beat = $b->beat;
      $bpm = $b->bpm;
      $measure = $beat / $this->bm;
      $mpcol = $this->mpcol; # How many measures are in a column?
      $col = floor(floor($measure) / $mpcol); # Find the right column.
      $down = $measure % $mpcol; # Find the specific measure.
      
      $lx = ($buff + ($this->cols * $this->aw)) * $col + $this->lb;
      $ly = $down * $this->aw * $this->bm * $this->speedmod + $this->headheight;
      
      $line = $this->xml->createElement('line');
      $line->setAttribute('x1', $lx + $draw);
      $line->setAttribute('y1', $ly);
      $line->setAttribute('x2', $lx + $draw + $draw);
      $line->setAttribute('y2', $ly);
      $line->setAttribute('class', 'bpm');
      $this->svg->appendChild($line);
      
      if (isset($bpm))
      {
        $bpm = trim(trim($bpm, '0'), '.');
        $text = $this->xml->createElement('text');
        $text->setAttribute('x', $lx + $draw + $draw );
        $text->setAttribute('y', $ly + $this->bm);
        $text->setAttribute('class', 'bpm');
        $text->appendChild($this->xml->createTextNode($bpm));
        $this->svg->appendChild($text);
      }
    }
  }
  
  private function genStop($id)
  {
  
  }
  
  private function prepArrows($kind)
  {
    if ($kind == "classic")
    {
      $dl = array('a' => 'DL', 'c' => 'note_004');
      $ul = array('a' => 'UL', 'c' => 'note_008');
      $cn = array('a' => 'CN', 'c' => 'note_016');
      $ur = array('a' => 'UR', 'c' => 'note_008');
      $dr = array('a' => 'DR', 'c' => 'note_004');
      $ret = array($dl, $ul, $cn, $ur, $dr);
      if ($this->cols == $this->double)
      {
        array_push($ret, $dl, $ul, $cn, $ur, $dr);
      }
      return $ret;
    }
    if ($kind == "rhythm")
    {
      $ret = array();
      $div = array('4th', '8th', '12th', '16th',
        '24th', '32nd', '48th', '64th');
      foreach ($div as $d)
      {
        $g = sprintf('note_%03d', intval($d));
        $dl = array('a' => 'DL', 'c' => $g);
        $ul = array('a' => 'UL', 'c' => $g);
        $cn = array('a' => 'CN', 'c' => $g);
        $ur = array('a' => 'UR', 'c' => $g);
        $dr = array('a' => 'DR', 'c' => $g);
        $ret[$d] = array($dl, $ul, $cn, $ur, $dr);
        if ($this->cols == $this->double)
        {
          array_push($ret[$d], $dl, $ul, $cn, $ur, $dr);
        }
      }
      return $ret;
    }

    throw new sfParseException("The notetype $kind is invalid.");
  }
  
  private function getBeat($beat)
  {
    switch (round($beat) % 32)
    {
      case 0: return '4th';
      case 16: return '8th';
      case 11: case 21: return '12th';
      case 8: case 24: return '16th';
      case 5: case 27: return '24th';
      case 4: case 12: case 20: case 28: return '32nd';
      case 3: case 13: case 19: case 29: return '48th';
      case 2: case 6: case 10: case 14:
      case 18: case 22: case 26: case 30: return '64th';
      default: return '64th'; # Unsure of keeping this default.
    }
  }
  
  private function genArrows($notes, $kind)
  {
    $arrows = $this->prepArrows($kind);
    for ($i = 0; $i < $this->cols; $i++)
    {
      $holds[] = array('on' => false, 'hold' => true,
        'x' => 0, 'y' => 0, 'beat' => 0);
    }
    $w = $this->cw + $this->lb + $this->rb; # width + buffers.
    
    /* Use colon format here: otherwise, gets too unwieldy. */
    
    $mcounter = 0;    
    foreach ($notes as $measure):
    
    $rcounter = 0;
    foreach ($measure as $row):
    
    $curbeat = intval($this->aw * $this->speedmod
      * $this->bm * $rcounter / count($measure));
      
    $arow = $kind == "classic" ? $arrows : $arrows[$this->getBeat($curbeat)];
    
    $pcounter = 0;    
    foreach (str_split($row) as $let): # For each note in the row
    
    $nx = (intval($mcounter / $this->mpcol) * $w)
      + $pcounter * $this->aw + $this->lb;
    
    $ny = $this->headheight + (($mcounter % $this->mpcol)
      * $this->aw * $this->bm * $this->speedmod) + intval(round($curbeat));
    
    # Stepchart part here.
    
    switch ($let)
    {
      case "1": # Tap note. Just add to the chart.
      {
        $id = $arow[$pcounter]['a'] . "arrow";
        $cl = $arow[$pcounter]['c'];
        $this->svg->appendChild($this->genUseNode($nx, $ny, $id, $cl));
        break;
      }
      case "2": case "4": # Start of hold/roll. Minor differences.
      {
        $holds[$pcounter]['on'] = true;
        $holds[$pcounter]['roll'] = $let == "2" ? false : true;
        $holds[$pcounter]['x'] = $nx;
        $holds[$pcounter]['y'] = $ny;
        $holds[$pcounter]['beat'] = $arow;
        break;
      }
      case "3": # End of hold/roll. VERY complicated!
      {
        if ($holds[$pcounter]['on'])
        {
          $id = $holds[$pcounter]['roll'] ? "roll" : "hold";
          $bod = "{$id}_bdy";
          $end = "{$id}_end";
          $a = $holds[$pcounter]['beat'][$pcounter];
          
          $ox = $holds[$pcounter]['x'];
          $oy = $holds[$pcounter]['y'];
          
          # First: check if tap note was on previous column.
          if ($holds[$pcounter]['x'] < $nx)
          {
            # Body goes first.
            
            # Calculate the scale for the hold.
            $bot = $this->svgheight - $this->aw;
            $hy = $oy + $this->aw / 2;
            $range = $bot - $hy;
            $sy = $range / $this->aw;
            
            $node = $this->genSVGNode($ox, $hy, $bod, '', 1, $sy);
            $this->svg->appendChild($node);
            # Place the tap.
            $node = $this->genUseNode($ox, $oy, $a['a'] . "arrow", $a['c']);
            $this->svg->appendChild($node);
            
            $ox += $w;
            $hy = $this->headheight;
            while ($ox < $nx)
            {
              $range = $bot - $hy;
              $sy = $range / $this->aw;
              $node = $this->genSVGNode($ox, $hy, $bod, '', 1, $sy);
              $this->svg->appendChild($node);
              $ox += $w;
            }
            # Now we're on the same column as the tail.
            $bot = $ny + $this->aw / 2;
            $range = $bot - $hy;
            $sy = $range / $this->aw;
            $node = $this->genSVGNode($nx, $hy, $bod, '', 1, $sy);
            $this->svg->appendChild($node);
            $this->svg->appendChild($this->genUseNode($nx, $ny, $end));
          }
          else
          {
            if ($ny - $oy >= intval($this->aw / 2)) # Make this variable
            {
              $bot = $ny + $this->aw / 2;
              $hy = $oy + $this->aw / 2;
              $range = $bot - $hy;
              $sy = $range / $this->aw;
              $node = $this->genSVGNode($nx, $hy, $bod, '', 1, $sy);
              $this->svg->appendChild($node);
            }
            # Tail next
            $this->svg->appendChild($this->genUseNode($nx, $ny, $end));
            # Tap note last.
            $node = $this->genUseNode($ox, $oy, $a['a'] . "arrow", $a['c']);
            $this->svg->appendChild($node);
          } 
        }
        else # Throw an error at some point.
        {
          $id = $arow[$pcounter]['a'] . "arrow";
          $cl = $arow[$pcounter]['c'];
          $this->svg->appendChild($this->genUseNode($nx, $ny, $id, $cl));
        }
        break;
      }
      case "M": # Mine. Don't step on these!
      {
        $holds[$pcounter]['on'] = false;
        $this->svg->appendChild($this->genUseNode($nx, $ny, "mine"));
        break;
      }
      case "L": # Lift note. Can be placed in chart. No image yet.
      {
        $holds[$pcounter]['on'] = false;
        break;
      }
      case "F": # Fake note. Not yet available.
      {
        $holds[$pcounter]['on'] = false;
        break;
      }
    }
    
    $pcounter++;
    endforeach;
    
    $rcounter++;
    endforeach;
    
    $mcounter++;
    endforeach;
    
  }
  
  public function genChart($notedata, $kind = "classic")
  {
    $measures = count($notedata['notes']);
    $this->genXMLHeader($measures);
    $this->genEditHeader($notedata);
    $this->genMeasures($measures);
    if (!isset($this->nobpm))
    {
      $this->genBPM($notedata['id']);
    }
    if (!isset($this->nostop))
    {
      $this->genStop($notedata['id']);
    }
    $this->genArrows($notedata['notes'], $kind);
    return $this->xml;
  }
}