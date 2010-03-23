<?php slot('xhtml', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n");
slot('title', 'Edit Creater — Pump Pro Edits');
slot('h2', "<h2>Edit Creater</h2>"); ?>
<p>Welcome to the edit creater. Here, you have a complete
web interface to make your own edit. Use the drop down
menus to select your various options, and have fun!</p>

<nav id="svg_nav">
<?php $authin = $sf_user->isAuthenticated() ? "in" : "out"; # Will I need this? ?>
<p>Javascript required!</p>
<form id="svg_nav_form">
<dl>
<dt><label for="songlist">Song</label></dt>
<dd><select id="songlist">
<option value="" selected="selected">Select your song!</option>
<?php foreach ($songs as $s): ?>
<option value="<?php echo $s->id ?>"><?php echo strlen($s->name) > 30 ? substr($s->name, 0, 29) . "…" : $s->name ?></option>
<?php endforeach; ?></select></dd>
</dl>
</form>
</nav>