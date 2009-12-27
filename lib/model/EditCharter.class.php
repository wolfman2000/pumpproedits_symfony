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
      if (isset($params['footer_height']))
      {
        $this->footheight = $params['footer_height'];
      }
      
      // Allow speed mods in play.
      $this->speedmod = sfConfig::get('app_chart_speed_mod');;
      if (isset($params['speed_mod']))
      {
        $this->speedmod = $params['speed_mod'];
      }
      
      // How many measures are shown in a column?
      $this->mpcol = sfConfig::get('app_chart_measures_col');
      if (isset($params['mpcol']))
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
    $use->setAttribute('x', "$x");
    $use->setAttribute('y', "$y");
    $use->setAttribute('xlink:href', "$base#$id");
    $use->setAttribute('class', "$class");
    if (!($sx === 1 and $sy === 1))
    {
      $use->setAttribute('transform', "scale($sx $sy)");
    }  
    $svg->appendChild($use);
    return;
  }

  private function genXMLHeader($measures)
  {
    $svg = $this->xml->createElement('svg');
    $svg->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    $svg->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $svg->setAttribute('version', '1.1');
    
    // Calculate the width of the outer svg.
    $numcols = ceil($measures / $this->mpcol);
    $arrwidth = sfConfig::get('app_chart_arrow_width');
    $breather = sfConfig::get('app_chart_column_sep');
    $width = ($arrwidth * $this->cols + $breather) * $numcols + $breather;
    $svg->setAttribute('width', "$width");
    
    // Calculate the height of the outer svg.
    $arrheight = sfConfig::get('app_chart_arrow_height');
    $beatheight = sfConfig::get('app_chart_beat_height');
    $bpm = sfConfig::get('app_beat_p_measure');
    $height = $beatheight * $bpm * $this->speedmod * $this->mpcol;
    $height += $this->headheight + $this->footheight;
    $svg->setAttribute('height', "$height");
    
    $this->xml->appendChild($svg);
    $this->svg = $svg; # Will be used for arrow placements.
  }
  
  protected function genChart($notedata, $kind = "classic")
  {
    $measures = array_count($notedata['notes']);
    $this->genXMLHeader($measures);
    //$chart = $this->load_base($notedata['style'], $measures);
    
    return $this->xml;
  }
}