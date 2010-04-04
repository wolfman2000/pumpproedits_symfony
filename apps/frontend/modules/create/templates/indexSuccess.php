<?php slot('xhtml', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n<?xml-stylesheet href=\"/css/_svg.css\" type=\"text/css\"?>\r\n");
slot('title', 'Edit Creator — Pump Pro Edits');
slot('h2', "<h2>Edit Creator</h2>");
slot('andy', $andy); ?>
<p>Welcome to the edit creator. Use the options on the
left to place arrows below. Have fun!</p>

<svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
<g id="notes">
<g id="svgMeas" />
<g id="svgSync" />
<g id="svgNote" />
<rect id="shadow" x="0" y="0" width="16" height="16" />
<rect id="selTop" x="0" y="0" width="80" height="16" />
<rect id="selBot" x="0" y="0" width="80" height="16" />
</g>
</svg>

<nav id="svg_nav">
<?php if ($sf_user->isAuthenticated()): ?>
<p id="authIntro"><?php echo
link_to('View your edits here!', '@edit_cuser?id=' . $sf_user->getAttribute('id')); ?></p>
<?php endif; ?>
<p id="intro">Javascript required!</p>
<form id="svg_nav_form" method="post" enctype="multipart/form-data" action="<?php echo url_for("@edit_creator_download"); ?>">
<dl>
<dt>
<input type="hidden" id="abbr" name="abbr" value="BOGUS" />
<input type="hidden" id="b64" name="b64" value="longvalue" />
<input type="hidden" id="style" name="style" value="none" />
<input type="hidden" id="diff" name="diff" value="π" />
<input type="hidden" id="title" name="title" value="not empty" />
</dt>
<dd><ul>
<li><button id="but_new" type="button">New</button></li>
<li><button id="but_help" type="button">Help</button></li>
<li><button id="but_load" type="button">Load</button></li>
<li><button id="but_val" type="button">Validate</button></li>
<li class="loadChoose"><button id="cho_file" type="button">Hard Drive</button></li>
<li class="loadChoose"><button id="cho_site" type="button">Web Site</button></li>
<li class="loadWeb"><button id="web_you" type="button">Yours</button></li>
<li class="loadWeb"><button id="web_and" type="button">Andamiro's</button></li>
<li class="loadSite long">Select your edit below.</li>
<li class="loadSite long"><select id="mem_edit"></select></li>
<li class="loadSite reset"><button id="mem_load" type="button">Load Edit</button></li>
<li class="loadSite"><button id="mem_nogo" type="button">Nevermind</button></li>
<li class="loadFile long reset">Paste the edit contents below.</li>
<li class="loadFile long reset"><textarea id="fCont" name="fCont"></textarea></li>
<li class="loadFile reset"><button id="but_file" type="button">Load File</button></li>
<li class="loadFile"><button id="rem_file" type="button">Nevermind</button></li>
<li class="edit"><button id="but_save" type="submit">Save</button></li>
<li class="edit"><button id="but_sub" type="button">Submit</button></li>
</ul></dd>
<dt class="choose"><label for="songlist">Select your song!</label></dt>
<dd class="choose"><select id="songlist">
<option value="" selected="selected">Choose</option>
<optgroup label="Pump it up Pro">
<?php $ind = 1;
foreach ($songs as $s):
if ($s->gid != $ind): ?>
</optgroup>
<optgroup label="Pump it up Pro 2">
<?php $ind = $s->gid; endif; ?>
<option value="<?php echo $s->id ?>"><?php echo $s->name ?></option>
<?php endforeach; ?></optgroup></select></dd>
<dt class="choose"><label for="stylelist">Select your style!</label></dt>
<dd class="choose"><select id="stylelist">
<option value="" selected="selected">Choose</option>
<option value="single">pump-single</option>
<option value="double">pump-double</option>
<option value="halfdouble">pump-halfdouble</option>
<option value="routine">pump-routine</option>
</select></dd>
<dt class="edit"></dt>
<dd class="edit"><ul>
<li class="author"><label for="authorlist">Edit Author:</label></li>
<li class="author"><select id="authorlist">
<option value="0" selected="selected">Yourself</option>
<option value="1">Andamiro</option>
</select></li>
<li><label for="editName">Edit Name:</label></li>
<li><input type="text" id="editName" maxlength="12" /></li>
<li><label for="editDiff">Diff. Rating:</label></li>
<li><input type="text" id="editDiff" maxlength="2" /></li>
<li><label for="quanlist">Note Sync:</label></li>
<li><select id="quanlist">
<option value="4" selected="selected">4th</option>
<option value="8">8th</option>
<option value="12">12th</option>
<option value="16">16th</option>
<option value="24">24th</option>
<option value="32">32nd</option>
<option value="48">48th</option>
<option value="64">64th</option>
<option value="192">192nd</option>
</select></li>
<li><label for="typelist">Note Type:</label></li>
<li><select id="typelist">
<option value="1" selected="selected">Tap</option>
<option value="2">Hold Head</option>
<option value="3">Hold/Roll End</option>
<option value="4">Roll Head</option>
<option value="M">Mine</option>
<option value="L">Lift</option>
<option value="F">Fake</option>
</select></li>
<li><label for="scalelist">Chart Zoom:</label></li>
<li><select id="scalelist">
<option value="1">Tiny</option>
<option value="2">Small</option>
<option value="2.5" selected="selected">Normal</option>
<option value="3">Big</option>
<option value="4">Giant</option>
</select></li>
<li><label for="modelist">Cursor Mode:</label></li>
<li><select id="modelist">
<option value="0" selected="selected">Insert</option>
<option value="1">Select</option>
</select></li>
<li class="routine"><label for="playerlist">Routine Player:</label></li>
<li class="routine"><select id="playerlist">
<option value="0" selected="selected">Player 1</option>
<option value="1">Player 2</option>
</select></li>
</ul></dd>
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
