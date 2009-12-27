<?php

class EditCharter
{
  function __construct($params)
  {
    $single = sfConfig::get('app_chart_single_cols');
    $double = sfConfig::get('app_chart_double_cols');
    if (!in_array($params['cols'], array($single, $double)))
    {
      $e = "There must be either $single or $double columns in the chart!";
      throw new sfParseException($e);
    }
    else
    {
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
    $arrwidth = sfConfig::get('app_chart_arrow_width');
    $breather = sfConfig::get('app_chart_column_sep');
    $bpm = sfConfig::get('app_chart_beat_p_measure');
    $width = ($arrwidth * $this->cols + $breather) * $numcols + $breather;
    $svg->setAttribute('width', $width);
    
    // Calculate the height of the outer svg.
    $beatheight = sfConfig::get('app_chart_beat_height');
    //$bpm = sfConfig::get('app_beat_p_measure');
    
    assert($beatheight == 16);
    assert($this->speedmod == 2)/ # not changing default
    
    $height = $beatheight * $bpm * $this->speedmod * $this->mpcol;
    $height += $this->headheight + $this->footheight;
    $svg->setAttribute('height', $height);
    
    $this->xml->appendChild($svg);
    $this->svg = $svg; # Will be used for arrow placements.
  }
  
  private function genMeasures($measures)
  {
    $numcols = ceil($measures / $this->mpcol); // mpcol is measures per column
    $beatheight = sfConfig::get('app_chart_beat_height'); // default beat height
    $spd = $this->speedmod; // speed mod: also affects columns.
    $bpm = sfConfig::get('app_chart_beat_p_measure');
    $arrwidth = sfConfig::get('app_chart_arrow_width');
    $breather = sfConfig::get('app_chart_column_sep'); // breather room
    $id = "measure";
    for ($i = 0; $i < $numcols; $i++)
    {
      $x = ($arrwidth * $this->cols + $breather) * $i + $breather;
      $sx = 1;
      $sx = $this->cols;
      for ($j = 0; $j < $this->mpcol * $this->speedmod; $j++)
      {
        $y = $beatheight * $j * $bpm + $this->headheight;
        $use = $this->genSVGNode($x, $y, $id, '', $sx);
        $this->svg->appendChild($use);
        //break 1;
      }
    }
  }
  
  private function genEditHeader($nd)
  {
    $text = $this->xml->createElement('text');
    $text->setAttribute('x', sfConfig::get('app_chart_column_sep'));
    $text->setAttribute('y', 16);
    $song = Doctrine::getTable('PPE_Song_Song')->getSongByID($nd['id']);
    $st = sprintf("%s Edit for %s: %s - %s",
      ucfirst(substr($nd['style'], 5)), $song, $nd['title'], $nd['diff']);
    $text->appendChild($this->xml->createTextNode($st));
    $this->svg->appendChild($text);
  }
  
  public function genChart($notedata, $kind = "classic")
  {
    $measures = count($notedata['notes']);
    $this->genXMLHeader($measures);
    $this->genEditHeader($notedata);
    $this->genMeasures($measures);
    //$chart = $this->load_base($notedata['style'], $measures);
    
    return $this->xml;
  }
}