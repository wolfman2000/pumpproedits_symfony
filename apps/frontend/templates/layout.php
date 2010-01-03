<!DOCTYPE html>
<html lang="en">
<head>
<?php include_http_metas();
include_metas();
include(sfConfig::get('sf_lib_dir') . "/browser_detect.php"); ?>
<title><?php include_slot('title', 'Pump Pro Edits') ?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<?php include_stylesheets();
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
echo $sf_content ?>
</article>
<?php $authin = $sf_user->isAuthenticated() ? "in" : "out"; ?>
<nav>
<?php include_partial("global/mess_$authin", array()) ?>
<ul>
<li>
<h4>Members</h4>
<?php include_partial("global/memb_$authin", array()) ?>
</li>
<li>
    <h4>Edits</h4>
    <ul>
    <li><?php echo link_to("Base Edit Files", '@base_edit') ?></li>
    <li><?php echo link_to("Edit Stat Getter", '@edit_stat_get') ?></li>
    <li><?php echo link_to("Edit List by Song", '@edit_song') ?></li>
    <li><?php echo link_to("Edit List by User", '@edit_user') ?></li>
    <li><?php echo link_to("Official Chart Edits", '@edit_official') ?></li>
    </ul>
</li>
<li>
    <h4>Everyone</h4>
    <ul>
    <li><a href="/news">Previous Updates</a></li>
    <li><?php echo link_to("Contact", '@contact_get') ?></li>
    <li><?php echo link_to("Credits/Thanks", '@thanks') ?></li>
    </ul>
</li>
</ul>
</nav>
<footer>This website is © <a href="mailto:jafelds@gmail.com">Jason “Wolfman2000” Felds</a></footer>
</body>
</html>
