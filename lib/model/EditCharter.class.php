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

  private function genXMLHeader()
  {
    $svg = $this->xml->createElement('svg');
    $svg->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    $svg->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $svg->setAttribute('version', '1.1');
  }
  
  protected function genCharts($notedata, $kind)
  {
    $measures = array_count($notedata['notes']);
    $chart = $this->load_base($notedata['style'], $measures);
  }
}