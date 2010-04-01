<!doctype html>
<html>
<head>
<title>Editor Help</title>
</head>
<?php $back = "<h4><a href=\"#top\">Back to Top</a></h4>\r\n</section>"; ?>
<body>
<h1>Edit Creator Help</h1>
<ul id="top">
<li><a href="#core">Core Buttons</a></li>
<li><a href="#song">Song Mode</a></li>
<li><a href="#edit">Edit Mode</a>
<ul>
<li><a href="#menu">Menu Options</a></li>
<li><a href="#chrt">Chart Controls</a></li>
</ul></li>
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
Submitting is only possible once an edit is validated and there are no errors.
You have to be logged in presently in order to submit an edit.</dd>
</dl>
<?php echo $back; ?>
<section id="song">
<h2>Song Mode</h2>
<p>Song Mode is very simple. All that you have to do to start editing
is to select both a song and a style using the drop down menus.
You have to choose a song before you choose a style. Once you choose
a style, it changes to Edit Mode. If you want to change the mode, you
will have to either start a new edit or load an old edit.</p>
<?php echo $back; ?>
<section id="edit">
<h2>Edit Mode</h2>
<p>Edit Mode is where you actually create your own edit. Use
the menus, keyboard, and mouse to select your options and
place your arrows.</p>
<?php echo $back; ?>
<section id="menu">
<h3>Menu Options</h3>
<p>All of the options to control your edit are available here.</p>
<?php $keys = array('Edit Name' => 'Enter the name of the edit. '
  . 'This must be provided to allow validating/saving your edit.',
'Diff. Rating' => 'Enter the numeric difficulty of the edit. '
  . 'This must be provided to allow validating/saving your edit.',
'Note Sync' => 'Decide what sync of notes you will be placing in your edit. '
  . 'Most edits will need no more than 16th notes available at once.',
'Note Type' => 'Decide what type of notes you will be placing in your edit. '
  . 'More information about the types will be available later.',
'Chart Zoom' => 'Decide how big or small the chart itself will look. '
  . 'It is recommended to user higher zooms when working with more exact notes.',
'Routine Player' => 'Decide which player\'s notes are in use. '
  . 'This is only available when creating/editing Routine style edits.',
); ?>
<dl>
<?php foreach ($keys as $k => $v): ?>
<dt><?php echo $k; ?></dt>
<dd><?php echo $v; ?></dd>
<?php endforeach; ?>
</dl>
<p>In addition to the above, there are non interactive portions that
may prove useful to you.</p>
<?php $phr = "Shows the number of %s in the edit.";
$keys = array('Measure' => "Indicates which measure your mouse is over. "
  . "If your mouse is over two measures, the higher one takes precedence.",
'Beat' => "Indicates which beat inside the measure your mouse is over. "
  . "If the mouse was clicked at this position, an arrow would be placed at "
  . "the corresponding measure and beat. Beats are 0-index based.",
'Steps' => sprintf($phr, "steps"),
'Jumps' => sprintf($phr, "jumps"),
'Holds' => sprintf($phr, "holds"),
'Mines' => sprintf($phr, "mines"),
'Trips' => sprintf($phr, "hands/trips/three+ panel hits"),
'Rolls' => sprintf($phr, "rolls"),
'Lifts' => sprintf($phr, "lifts"),
'Fakes' => sprintf($phr, "fakes"),
);
?>
<dl>
<?php foreach ($keys as $k => $v): ?>
<dt><?php echo $k; ?></dt>
<dd><?php echo $v; ?></dd>
<?php endforeach; ?>
</dl>
<?php echo $back; ?>
<section id="chrt">
<h3>Chart Controls</h3>
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
'+' => "Enlarges the chart to the next available zoom.",
'-' => "Shrinks the chart to the next available zoom.",
'P' => "Change which player's notes to place (Routine only).",
);
foreach ($keys as $k => $v): ?>
<dt><?php echo $k; ?></dt>
<dd><?php echo $v; ?></dd>
<?php endforeach; ?>
</dl>
<?php echo $back; ?>
</body>
</html>