<!doctype html>
<html>
<head>
<title>Editor Help</title>
</head>
<?php $back = "<h3><a href=\"#top\">Back to Top</a></h3>\r\n</section>"; ?>
<body>
<h1>Edit Creator Help</h1>
<ul id="top">
<li><a href="#core">Core Buttons</a></li>
<li><a href="#song">Song Mode</a></li>
<li><a href="#edit">Edit Mode</a></li>
<li><a href="#keyb">Keyboard Shortcuts</a></li>
</ul>
<section id="core">
<h2>Core Buttons</h2>
<dl>
<dt>New</dt>
<dd>Start a new edit. If you haven't saved/validated your edit recently,
you will be asked if you want to do so first.</dd>
<dt>Help</dt>
<dd>Display this window.</dd>
<dt>Load</dt>
<dd>Load an edit. If you haven't saved/validated your edit recently,
you will be asked if you want to do so first.</dd>
<dt>Validate</dt>
<dd>Validate the current edit. Validation is only possible if an edit
rating and difficulty are both given, and the edit was recently changed.
Validation of the edit is required before submitting the edit.</dd>
<dt>Save</dt>
<dd>Save the current edit to your hard drive. Saving is only possible
once an edit is validated and there are no errors.</dd>
<dt>Submit</dt>
<dd>Submit the current edit to your Pump Pro Edits account.
Submitting is only possible once an edit is validated and there are no errors.</dd>
</dl>
<?php echo $back; ?>
<section id="song">
<h2>Song Mode</h2>
<?php echo $back; ?>
<section id="edit">
<h2>Edit Mode</h2>
<?php echo $back; ?>
<section id="keyb">
<h2>Keyboard Shortcuts</h2>
<p>This online editor works best when using both the keyboard and mouse.
The list of the keyboard controls is below. You can assume
that letter casing doesn't matter unless otherwise specified.</p>
<dl>
<?php $sync = "Change the sync to use %s notes.";
$type = "Change the arrow type to %s notes.";
$keys = array('1' => sprintf($sync, '4th'),
'2' => sprintf($sync, '8th'),
'3' => sprintf($sync, '12th'),
'4' => sprintf($sync, '16th'),
'5' => sprintf($sync, '24th'),
'6' => sprintf($sync, '32nd'),
'7' => sprintf($sync, '48th'),
'8' => sprintf($sync, '64th'),
'9' => sprintf($sync, '192nd'),
'T' => sprintf($type, "Tap"),
'H' => sprintf($type, 'Hold'),
'E' => sprintf($type, 'Hold/Roll End'),
'R' => sprintf($type, 'Roll'),
'M' => sprintf($type, 'Mine'),
'L' => sprintf($type, 'Lift'),
'F' => sprintf($type, 'Fake'),
);
foreach ($keys as $k => $v): ?>
<dt><?php echo $k; ?></dt>
<dd><?php echo $v; ?></dd>
<?php endforeach; ?>
</dl>
<?php echo $back; ?>
</body>
</html>