<?php slot('title', "Stats on your {$result['title']} edit — Pump Pro Edits"); ?>

<h2>Successful Parse!</h2>

<p>Your edit was parsed successfully. Its stats are as follows:</p>

<dl>
<dt>Title</dt><dd><?php echo $result['title'] ?></dd>
<dt>Style</dt><dd><?php echo $result['style'] ?></dd>
<dt>Difficulty</dt><dd><?php echo $result['diff'] ?></dd>
<dt>Steps</dt><dd><?php echo $result['steps'] ?></dd>
<dt>Jumps</dt><dd><?php echo $result['jumps'] ?></dd>
<dt>Holds</dt><dd><?php echo $result['holds'] ?></dd>
<dt>Mines</dt><dd><?php echo $result['mines'] ?></dd>
<dt>Trips</dt><dd><?php echo $result['trips'] ?></dd>
<dt>Rolls</dt><dd><?php echo $result['rolls'] ?></dd>
</dl>

<p>Feel free to submit another edit to get its stats too!</p>
