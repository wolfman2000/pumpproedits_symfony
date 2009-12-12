<?php
  slot('title', 'Base Edit Files Page ?? — Pump Pro Edits');
?> 
<h2>Base Edit Files</h2> 
<p>
	Edit files are made with a .edit extension, and placed in
	the appropriate folder on your USB drive when you go play
	Pump It Up Pro.
</p>
<p>
	At times you may want to edit a song's steps, but don't
	exactly know what measure they start on or how long
	the song lasts.  The base edit files below are
	provided as a convenience for edit makers to have
	a place to start.
</p>
<p>
	These are so useful, even some of
	the developers of the game use these files.  Surely
	they will work for you!  Just download the single
	and/or double steps of the files you want.
</p>

<table id="base" summary="Base edit files for users to download.">
  <caption>Download the Base Edit Files</caption>
  <thead><tr>
    <th>Song Name</th>
    <th>pump-single</th>
    <th>pump-double</th>
  </tr></thead>
  <tbody>
    <?php foreach ($base_songs as $b): ?>
    <tr>
      <td><?php echo $b->getName() ?></td>
      <td><a href="<?php echo url_for('base/download/' . $b->getId() . '/single') ?>"><?php echo $b->getAbbr() ?> Single</a></td>
      <td><a href="<?php echo url_for('base/download/' . $b->getId() . '/double') ?>"><?php echo $b->getAbbr() ?> Double</a></td>
    </tr>
    <?php endforeach; ?>    
  </tbody>
</table>
