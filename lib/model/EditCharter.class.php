<?php

class EditCharter
{
  function __construct($params)
  {
    $this->single = sfConfig::get('app_chart_single_cols');
    $this->double = sfConfig::get('app_chart_double_cols');
    $this->halfdouble = 6;
    
    if (!in_array($params['cols'], array($this->single, $this->double, $this->halfdouble)))
    {
      $e = "There must be either $this->single, $this->halfdouble, or $this->double columns in the chart!";
      throw new sfParseException($e);
    }
    if (!in_array($params['kind'], array("classic", "rhythm")))
    {
      $e = "The notetype chosen is not valid!";
      throw new sfParseException($e);
    }
    $this->lb = sfConfig::get('app_chart_column_left_buffer');
    $this->rb = sfConfig::get('app_chart_column_right_buffer');
    $this->aw = sfConfig::get('app_chart_arrow_width');
    $this->bm = sfConfig::get('app_chart_beat_p_measure');
    $this->kind = $params['kind'];
    
    # Have the rhythm skin use red as the quarter note.
    if (array_key_exists('red4', $params) and $params['red4'])
    {
      $this->red4 = 1;
    }
    if (array_key_exists('nobpm', $params) and $params['nobpm'])
    {
      $this->showbpm = 0;
    }
    else
    {
      $this->showbpm = 1;
    }
    if (array_key_exists('nostop', $params) and $params['nostop'])
    {
      $this->showstop = 0;
    }
    else
    {
      $this->showstop = 1;
    }
    
    # Is the header supposed to be arcade style?
    
    if (array_key_exists('arcade', $params) and $params['arcade'])
    {
      $this->arcade = 1;
    }
    else
    {
      $this->arcade = 0;
    }
    
    # How much of a zoom is there for the chart?
    if (array_key_exists('scale', $params) and $params['scale'])
    {
      $this->scale = $params['scale'];
    }
    else
    {
      $this->scale = 1;
    }
    
    $this->headheight = sfConfig::get('app_chart_header_height');
    $this->footheight = sfConfig::get('app_chart_footer_height');
    if (array_key_exists('footer_height', $params))
    {
      $this->footheight = $params['footer_height'];
    }
    
    $this->speedmod = sfConfig::get('app_chart_speed_mod');;
    if (array_key_exists('speed_mod', $params))
    {
      $this->speedmod = $params['speed_mod'];
    }
    
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

  private function genUseNode($x, $y, $id, $class = '', $sx = 1, $sy = 1)
  {
    $base = sfConfig::get('app_chart_def_file');
    $use = $this->xml->createElement('use');
    if ($x > 0) $use->setAttribute('x', $x);
    if ($y > 0) $use->setAttribute('y', $y);
    $use->setAttribute('xlink:href', "$base#$id");
    if (strlen($class) > 1) $use->setAttribute('class', "$class");
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
    $svg->setAttribute('width', $width * $this->scale);
    
    // Calculate the height of the outer svg.
    $beatheight = sfConfig::get('app_chart_beat_height');
        
    $height = $beatheight * $this->bm * $this->speedmod * $this->mpcol;
    $height += $this->headheight + $this->footheight;
    $svg->setAttribute('height', $height * $this->scale);
    $this->svgheight = $height;
    
    $this->xml->appendChild($svg);
    
    $g = $this->xml->createElement('g');
    $g->setAttribute('transform', "scale($this->scale)");
    $svg->appendChild($g);
    
    $this->svg = $g; # Will be used for arrow placements.
    
  }
  
  private function genMeasures($measures)
  {
    $numcols = ceil($measures / $this->mpcol); // mpcol is measures per column
    $beatheight = sfConfig::get('app_chart_beat_height'); // default beat height
    $spd = $this->speedmod; // speed mod: also affects columns.
    $breather = $this->lb + $this->rb;
    for ($i = 0; $i < $numcols; $i++)
    {
      $x = ($this->aw * $this->cols + $breather) * $i + $this->lb;
      $sx = $this->cols;
      for ($j = 0; $j < $this->mpcol * $spd; $j++)
      {
        $y = $beatheight * $j * $this->bm + $this->headheight;
        $use = $this->genSVGNode($x, $y, "measure", '', $sx);
        $this->svg->appendChild($use);
      }
    }
  }
  
  private function genTxtNode($x, $y, $st, $class = '')
  {
    $txt = $this->xml->createElement('text');
    $txt->setAttribute('x', $x);
    $txt->setAttribute('y', $y);
    if (strlen($class) > 1) $txt->setAttribute('class', $class);
    $txt->appendChild($this->xml->createTextNode($st));
    $this->svg->appendChild($txt);
  }
  
  private function genEditHeader($nd)
  {
    $lbuff = $this->lb;
    
    if ($this->arcade)
    {
      $this->genTxtNode($lbuff, 16, sprintf("%s %s - %d",
        $nd['song'], $nd['title'], $nd['diff']));
    }
    else
    {
      $this->genTxtNode($lbuff, 16, sprintf("%s %s Edit: %s - %d",
        $nd['song'], ucfirst(substr($nd['style'], 5)), $nd['title'], $nd['diff']));
    }
    $this->genTxtNode($lbuff, 32, $nd['author']);
    /*
    $this->genTxtNode($lbuff, 16, sprintf("%s Edit for %s: %s - %s",
      ucfirst(substr($nd['style'], 5)),
      $nd['song'],
      $nd['title'], $nd['diff'])
    );
    */
    
    $this->genTxtNode($lbuff, 64, "Steps: " . $nd['steps']);
    $this->genTxtNode($lbuff, 80, "Jumps: " . $nd['jumps']);
    
    $w = $this->cw + $lbuff + $this->rb;
    
    $this->genTxtNode($lbuff + $w * 1, 64, "Holds: " .$nd['holds']);
    $this->genTxtNode($lbuff + $w * 1, 80, "Mines: " .$nd['mines']);
    $this->genTxtNode($lbuff + $w * 2, 64, "Trips: " .$nd['trips']);
    $this->genTxtNode($lbuff + $w * 2, 80, "Rolls: " .$nd['rolls']);
    $this->genTxtNode($lbuff + $w * 3, 64, "Lifts: " .$nd['lifts']);
    $this->genTxtNode($lbuff + $w * 3, 80, "Fakes: " .$nd['fakes']);
  }
  
  private function genBPM($id)
  {
    $buff = $this->lb + $this->rb;
    $draw = $this->cols * $this->aw / 2;
    $m = $this->aw * $this->bm * $this->speedmod;
    foreach (Doctrine::getTable('PPE_Song_BPM')->getBPMsBySongID($id) as $b)
    {
      $beat = $b->beat;
      $bpm = $b->bpm;
      $measure = $beat / $this->bm;
      $mpcol = $this->mpcol; # How many measures are in a column?
      $col = floor(floor($measure) / $mpcol); # Find the right column.
      $down = $measure % $mpcol + $measure - floor($measure); # Find the specific measure.
      
      $lx = ($buff + ($this->cols * $this->aw)) * $col + $this->lb;
      $ly = $down * $m + $this->headheight;
      
      $line = $this->xml->createElement('line');
      $line->setAttribute('x1', $lx + $draw);
      $line->setAttribute('y1', $ly + 0.2);
      $line->setAttribute('x2', $lx + $draw + $draw);
      $line->setAttribute('y2', $ly + 0.2);
      $line->setAttribute('class', 'bpm');
      $this->svg->appendChild($line);
      
      if (isset($bpm))
      {
        $pos = strpos($bpm, ".");
        if ($pos !== false)
        {
          $bpm = trim(trim($bpm, '0'), '.');
        }
        $this->genTxtNode($lx + $draw + $draw, $ly + $this->bm, $bpm, 'bpm');
      }
    }
  }
  
  private function genStop($id)
  {
    $buff = $this->lb + $this->rb;
    $draw = $this->cols * $this->aw / 2;
    $m = $this->aw * $this->bm * $this->speedmod;
    foreach (Doctrine::getTable('PPE_Song_Stop')->getStopsBySongID($id) as $b)
    {
      $beat = $b->beat;
      $break = $b->break;
      $measure = $beat / $this->bm;
      $mpcol = $this->mpcol; # How many measures are in a column?
      $col = floor(floor($measure) / $mpcol); # Find the right column.
      $down = $measure % $mpcol + $measure - floor($measure); # Find the specific measure.
      
      $lx = ($buff + ($this->cols * $this->aw)) * $col + $this->lb;
      $ly = $down * $m + $this->headheight;
      
      $line = $this->xml->createElement('line');
      $line->setAttribute('x1', $lx);
      $line->setAttribute('y1', $ly + 0.2);
      $line->setAttribute('x2', $lx + $draw);
      $line->setAttribute('y2', $ly + 0.2);
      $line->setAttribute('class', 'stop');
      $this->svg->appendChild($line);
      
      if (isset($break))
      {
        $break = rtrim(rtrim($break, '0'), '.') . "B";
        $break = ltrim($break, '0');
        $this->genTxtNode($lx - $this->aw, $ly + $this->bm, $break, 'stop');
      }
    }
  }
  
  private function prepArrows()
  {
    if ($this->kind == "classic")
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
      elseif ($this->cols == $this->halfdouble)
      {
        $ret = array($cn, $ur, $dr, $dl, $ul, $cn);
      }
      return $ret;
    }
    if ($this->kind == "rhythm")
    {
      $ret = array();
      $div = array('4th', '8th', '12th', '16th',
        '24th', '32nd', '48th', '64th');
      foreach ($div as $d)
      {
        if (array_key_exists('red4', $this))
        {
          if (intval($d) == 4) $g = 'note_008';
          elseif (intval($d) == 8) $g = 'note_004';
          else $g = sprintf('note_%03d', intval($d));
        }
        else $g = sprintf('note_%03d', intval($d));
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
        elseif ($this->cols == $this->halfdouble)
        {
          $ret[$d] = array($cn, $ur, $dr, $dl, $ul, $cn);
        }
      }
      return $ret;
    }
  }
  
  private function getBeat($beat)
  {
    switch ($beat % 48)
    {
      case 0: return '4th';
      case 24: return '8th';
      case 16: case 32: return '12th';
      case 12: case 36: return '16th';
      case 8: case 40: return '24th';
      case 6: case 18: case 30: case 42: return '32nd';
      case 4: case 20: case 28: case 44: return '48th';
      case 3: case 9: case 15: case 21:
      case 27: case 33: case 39: case 45: return '64th';
      default: return '192nd';
    }
  }
  
  private function genArrows($notes)
  {
    $arrows = $this->prepArrows();
    for ($i = 0; $i < $this->cols; $i++)
    {
      $holds[] = array('on' => false, 'hold' => true, 'x' => 0, 'y' => 0, 'beat' => 0);
    }
    $w = $this->cw + $this->lb + $this->rb; # width + buffers.
    $m = $this->aw * $this->bm * $this->speedmod; # height of measure block
    
    $mcounter = 0;    
    foreach ($notes as $measure):
    
    $rcounter = 0;
    foreach ($measure as $row):
    
    $curbeat = intval(round($m * $rcounter / count($measure)));
      
    $arow = $this->kind == "classic" ? $arrows :
      $arrows[$this->getBeat(192 * $rcounter / count($measure))];
    
    $pcounter = 0;
    foreach (str_split($row) as $let): # For each note in the row
    
    $nx = (intval($mcounter / $this->mpcol) * $w) + $pcounter * $this->aw + $this->lb;
    $ny = $this->headheight + ($mcounter % $this->mpcol) * $m + $curbeat;
    
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
            $this->svg->appendChild($this->genUseNode($ox, $oy, $a['a'] . "arrow", $a['c']));
            
            $ox += $w;
            $hy = $this->headheight;
            while ($ox < $nx)
            {
              $range = $bot - $hy;
              $sy = $range / $this->aw;
              $this->svg->appendChild($this->genSVGNode($ox, $hy, $bod, '', 1, $sy));
              $ox += $w;
            }
            # Now we're on the same column as the tail.
            $bot = $ny + $this->aw / 2;
            $range = $bot - $hy;
            $sy = $range / $this->aw;
            $this->svg->appendChild($this->genSVGNode($nx, $hy, $bod, '', 1, $sy));
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
              $this->svg->appendChild($this->genSVGNode($nx, $hy, $bod, '', 1, $sy));
            }
            # Tail next
            $this->svg->appendChild($this->genUseNode($nx, $ny, $end));
            # Tap note last.
            $this->svg->appendChild($this->genUseNode($ox, $oy, $a['a'] . "arrow", $a['c']));
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
  
  public function genChart($notedata)
  {
    $measures = count($notedata['notes']);
    $this->genXMLHeader($measures);
    $this->genEditHeader($notedata);
    $this->genMeasures($measures);
    if ($this->showbpm) $this->genBPM($notedata['id']);
    if ($this->showstop) $this->genStop($notedata['id']);
    $this->genArrows($notedata['notes']);
    return $this->xml;
  }
}
