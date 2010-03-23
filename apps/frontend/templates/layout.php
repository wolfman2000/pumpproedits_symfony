<?php $xhtml = get_slot('xhtml', ''); echo $xhtml; ?>
<!DOCTYPE html>
<?php
function is_naked_day($d) {
  $start = date('U', mktime(-12, 0, 0, 04, $d, date('Y')));
  $end = date('U', mktime(36, 0, 0, 04, $d, date('Y')));
  $z = date('Z') * -1;
  $now = time() + $z; 
  if ( $now >= $start && $now <= $end ) {
    return true;
  }
  return false;
}
$naked = 9;
?><html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<?php include_http_metas(); ?>
<meta charset="UTF-8" />
<?php include_metas();
include(sfConfig::get('sf_lib_dir') . "/browser_detect.php"); ?>
<title><?php include_slot('title', 'Pump Pro Edits') ?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<?php if (!is_naked_day($naked)) { include_stylesheets(); }
if (browser_detection(7) == "ie"): ?>
<script type="text/javascript" src="js/IE8.js"></script>
<script type="text/javascript" src="js/ie_html5.js"></script>
<?php endif;
include_javascripts(); ?>
</head>
<body>
<header><h1><?php echo link_to('Pump Pro Edits', '@homepage') ?></h1></header>
<article>
<?php include_slot('h2', '<h2>Welcome!</h2>');
if (is_naked_day($naked)): ?>
<h3>Not seeing any styles?</h3>
<p>This is intentional! This is CSS Naked Day! Visit
<a href="http://naked.dustindiaz.com" title="Web Standards Naked Day Host Website">the
official website</a> for more information.</p>
<?php endif;
echo $sf_content ?>
</article>
<?php $nav = (strlen($xhtml) === 0 ? "norm" : "chrt");
include_partial("global/nav_$nav", array()) ?>
<footer>This website is ©2009-2010 <a href="mailto:jafelds@gmail.com">Jason “Wolfman2000” Felds</a>.<br />
This website works best in <a href="http://www.firefox.com">Firefox</a>.</footer>
</body>
</html>
