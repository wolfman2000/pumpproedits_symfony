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
<dt><label for="songlist">Select your song!</label></dt>
<dd><select id="songlist">
<option value="" selected="selected">Choose</option>
<?php foreach ($songs as $s): ?>
<option value="<?php echo $s->id ?>"><?php echo strlen($s->name) > 30 ? substr($s->name, 0, 29) . "…" : $s->name ?></option>
<?php endforeach; ?></select></dd>
<dt><label for="stylelist">Select your style!</label></dt>
<dd><select id="stylelist">
<option value="" selected="selected">Choose</option>
<option value="s">pump-single</option>
<option value="d">pump-double</option>
<option value="h">pump-halfdouble</option>
<option value="r">pump-routine</option>
</select></dd>
<dt><label for="quanlist">Select your sync!</label></dt>
<dd><select id="quanlist">
<option value="4" selected="selected">4th</option>
<option value="8">8th</option>
<option value="12">12th</option>
<option value="16">16th</option>
<option value="24">24th</option>
<option value="32">32nd</option>
<option value="48">48th</option>
<option value="64">64th</option>
<option value="192">192nd</option>
</select></dd>
<dt><label for="typelist">Select your note type!</label></dt>
<dd><select id="typelist">
<option value="1" selected="selected">Tap</option>
<option value="2">Hold Head</option>
<option value="3">Hold/Roll End</option>
<option value="4">Roll Head</option>
<option value="M">Mine</option>
<option value="L">Lift</option>
<option value="F">Fake</option>
</select></dd>
<dt>Select your player!</dt>
<dd><label>Player 1 <input type="radio" name="playerL" id="p1" value="1" checked="checked" /></label>
<label>Player 2 <input type="radio" name="playerL" id="p2" value="2" /></label></dd>
</dl>
</form>
</nav>