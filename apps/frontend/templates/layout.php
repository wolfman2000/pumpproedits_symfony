<!DOCTYPE html>
<html lang="en">
<head>
<?php include_http_metas();
include_metas();
include(sfConfig::get('sf_lib_dir') . "/browser_detect.php"); ?>
<title><?php include_slot('title', 'ITG Edits') ?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<?php include_stylesheets();
if (browser_detection(7) == "ie"): ?>
<script type="text/javascript" src="http://ie7-js.googlecode.com/svn/version/2.1(beta3)/IE9.js"></script>
<script type="text/javascript" src="js/ie_html5.js"></script>
<?php endif;
include_javascripts(); ?>
</head>
<body>
<header><h1><?php echo link_to('ITG Edits', '@homepage') ?></h1></header>
<article>
<?php include_slot('h2', '<h2>Welcome!</h2>');
echo $sf_content ?>
</article>
<nav>
<p>Make your selection below and have fun.</p>
<ul>
<li>
    <h4>Edits</h4>
    <ul>
    <li><?php echo link_to("Base Edit Files", '@base_edit') ?></li>
    <li><?php echo link_to("Edit Stat Getter", '@edit_stat_get') ?></li>
    <li><?php echo link_to("Edit List by Song", '@edit_song') ?></li>
    <li><?php echo link_to("Edit List by User", '@edit_user') ?></li>
    </ul>
</li>
<li>
    <h4>Everyone</h4>
    <ul>
    <li><?php echo link_to("Official Stepcharts", '@chart_off_get') ?></li>
    <li><?php echo link_to("Contact", '@contact_get') ?></li>
    <li><?php echo link_to("Credits/Thanks", '@thanks') ?></li>
    </ul>
</li>
</ul>
</nav>
<footer>This website is © 2010 <a href="mailto:jafelds@gmail.com">Jason “Wolfman2000” Felds</a></footer>
</body>
</html>
