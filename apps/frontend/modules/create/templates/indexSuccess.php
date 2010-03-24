<?php slot('xhtml', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n<?xml-stylesheet href=\"/css/_svg.css\" type=\"text/css\"?>\r\n");
slot('title', 'Edit Creater — Pump Pro Edits');
slot('h2', "<h2>Edit Creater</h2>"); ?>
<p>Welcome to the edit creater. Here, you have a complete
web interface to make your own edit. Use the drop down
menus to select your various options, and have fun!</p>

<svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
<g id="notes">
<g id="svgMeas" />
<g id="svgSync" />
<g id="svgNote" transform="scale(3)" />
<rect id="shadow" x="0" y="0" width="32" height="32" />
</g>
</svg>

<nav id="svg_nav">
<?php $authin = $sf_user->isAuthenticated() ? "in" : "out"; # Will I need this? ?>
<p id="intro">Javascript required!</p>
<form id="svg_nav_form">
<dl>
<dt class="choose"><label for="songlist">Select your song!</label></dt>
<dd class="choose"><select id="songlist">
<option value="" selected="selected">Choose</option>
<?php foreach ($songs as $s): ?>
<option value="<?php echo $s->id ?>"><?php echo $s->name ?></option>
<?php endforeach; ?></select></dd>
<dt class="choose"><label for="stylelist">Select your style!</label></dt>
<dd class="choose"><select id="stylelist">
<option value="" selected="selected">Choose</option>
<option value="single">pump-single</option>
<option value="double">pump-double</option>
<option value="halfdouble">pump-halfdouble</option>
<option value="routine">pump-routine</option>
</select></dd>
<dt class="edit"><label for="editName">Name your edit!</label></dt>
<dd class="edit"><input type="text" id="editName" maxlength="12" /></dd>
<dt class="edit"><label for="editDiff">How hard is your edit?</label></dt>
<dd class="edit"><input type="text" id="editDiff" maxlength="2" /></dd>
<dt class="edit"><label for="quanlist">Select your sync!</label></dt>
<dd class="edit"><select id="quanlist">
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
<dt class="edit"><label for="typelist">Select your note type!</label></dt>
<dd class="edit"><select id="typelist">
<option value="1" selected="selected">Tap</option>
<option value="2">Hold Head</option>
<option value="3">Hold/Roll End</option>
<option value="4">Roll Head</option>
<option value="M">Mine</option>
<option value="L">Lift</option>
<option value="F">Fake</option>
</select></dd>
<dt class="edit routine">Select your player!</dt>
<dd class="edit routine"><label>Player 1 <input type="radio" name="player" id="p1" value="1" checked="checked" /></label>
<label>Player 2 <input type="radio" name="player" id="p2" value="2" /></label></dd>
<dt class="edit">Present Location:</dt>
<dd class="edit"><ul>
<li>Measure <span id="mCheck">???</span></li>
<li>Beat <span id="yCheck">???</span> / 192</li>
</ul></dd>
<dt class="edit">Step Stats</dt>
<dd class="edit"><ul>
<li>Steps: <span id="statS">0</span></li>
<li>Jumps: <span id="statJ">0</span></li>
<li>Holds: <span id="statH">0</span></li>
<li>Mines: <span id="statM">0</span></li>
<li>Trips: <span id="statT">0</span></li>
<li>Rolls: <span id="statR">0</span></li>
<li>Lifts: <span id="statL">0</span></li>
<li>Fakes: <span id="statF">0</span></li>
</ul></dd>
</dl>
</form>
</nav>