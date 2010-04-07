<!doctype html>
<html>
<head>
<title>Editor Help</title>
</head>
<?php $back = "<h4><a href=\"#top\">Back to Top</a></h4>"; ?>
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
<li><a href="#thnk">Thanks</a></li>
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
</section>
<section id="song">
<h2>Song Mode</h2>
<p>Song Mode is very simple. All that you have to do to start editing
is to select both a song and a style using the drop down menus.
You have to choose a song before you choose a style. Once you choose
a style, it changes to Edit Mode. If you want to change the mode, you
will have to either start a new edit or load an old edit.</p>
<?php echo $back; ?>
</section>
<section id="edit">
<h2>Edit Mode</h2>
<p>Edit Mode is where you actually create your own edit. Use
the menus, keyboard, and mouse to select your options and
place your arrows.</p>
<?php echo $back; ?>
</section>
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
'Cursor Mode' => 'Determine what happens when you click on the chart. '
  . 'Insertion mode is the traditional "click-to-place-arrows" mode. '
  . 'Selection mode is used to select rows of arrows and transform them. '
  . 'More details about the transformations are covered later.',
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
</section>
<section id="chrt">
<h3>Chart Controls</h3>
<p>The chart controls themselves are primarily mouse driven.
When you move your mouse over the chart, a blue square hovers
over where an arrow could be placed. As you move the mouse over
the measures, the Measure and Beat numbers update accordingly.
By default, the square only highlights over quarter notes (4th notes).
To place other synced arrows, use the Note Sync drop down menu.</p>
<p>To place an arrow, click on the measures. An arrow will be placed
exactly where the blue square is located. You can click on the same
space to remove the arrow. If you change note types and click on an
occupied location, the new arrow replaces the old.</p>
<p>In selection mode, the controls work differently. Instead of placing
arrows, you are selecting whole rows of arrows to transform as needed.
The keyboard is required for most of these transformations at this time.
The transformations are as follows:</p>
<ul>
<li>Cycle the arrows a column to the left or right: use A or D.</li>
<li>Move the arrows up or down based on Note Sync: use W or S.</li>
<li>Mirror the arrows across the center of the chart: use I.</li>
<li>Copy a set of arrows to the clipboard: use C.</li>
<li>Cut/Move a set of arrows to the clipboard: use X.</li>
<li>Paste the arrows in the clipboard and wipe it clean: highlight the starting row, then use either the mouse or V.</li>
</ul>
<?php echo $back; ?>
</section>
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
'O' => "Change the cursor mode from insertion mode to selection mode and vice~versa.",
'P' => "Change which player's notes to place (Routine only).",
'A' => "Rotate the selected rows of arrows one column to the left (selection mode only).",
'D' => "Rotate the selected rows of arrows one column to the right (selection mode only).",
'W' => "Move the selected rows of arrows up based on the selected Note Sync (selection mode only).",
'S' => "Move the selected rows of arrows down based on the selected Note Sync (selection mode only).",
'I' => "Mirror the selected rows of arrows across the middle of the chart (selection mode only).",
'X' => "Cut/move the selected rows of arrows to the clipboard (selection mode only).",
'C' => "Copy the selected rows of arrows to the clipboard (selection mode only).",
'V' => "Paste the clipboarded arrows in the given position and wipe the clipboard clean (selection mode only).",
);
foreach ($keys as $k => $v): ?>
<dt><?php echo $k; ?></dt>
<dd><?php echo $v; ?></dd>
<?php endforeach; ?>
</dl>
<?php echo $back; ?>
</section>
<section id="thnk">
<h2>Thanks</h2>
<p>Many need to be thanked just for this editor alone. In random order, they are:</p>
<dl>
<dt>Andamiro</dt>
<dd>They made the original Pump It Up series, and their famous StepEdit editor.
Parts of the design of this editor was inspired by that editing program.</dd>
<dt>Fun In Motion</dt>
<dd>They made the present Pump It Up Pro line. Without that arcade game, this
website and editor would not exist.</dd>
<dt>StepMania</dt>
<dd>This is the engine that powers Pump It Up Pro. Its inclusion here should be obvious.</dd>
</dl>
<?php echo $back; ?>
</section>
</body>
</html>