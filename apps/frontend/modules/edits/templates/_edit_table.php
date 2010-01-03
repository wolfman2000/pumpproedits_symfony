<table id="edits" summary="<?php echo $summary ?>">
<caption><?php echo $caption ?></caption>
<?php if (isset($showuser) and isset($showsong)): ?>
<col span="2" />
<?php elseif (isset($showuser) or isset($showsong)): ?>
<col />
<?php endif; ?>
<col />
<col id="statcol" />
<col />
<thead><tr>
<?php if (isset($showuser)): ?><th>User</th><?php endif; ?>
<?php if (isset($showsong)): ?><th>Song</th><?php endif; ?>
<th>Title</th>
<th>Stats</th>
<th>Actions</th>
</tr></thead>
<tbody>
<?php foreach ($query as $z): ?>
<tr>
<?php if (isset($showuser)): ?>
<td><?php echo link_to($z->uname, $z->user_id != 2 ? "@edit_cuser?id=$z->user_id" : "@edit_official") ?></td>
<?php endif;
if (isset($showsong)): ?>
<td><?php echo link_to($z->sname, "@edit_csong?id=$z->song_id") ?></td>
<?php endif; ?>
<td><?php echo $z->title ?></td>
<td>
<dl>
<dt>Style</dt><dd><?php echo ($z->is_single ? "S" : "D") . $z->diff ?></dd>
<dt>Steps</dt><dd><?php echo $z->steps ?></dd>
<?php if ($z->jumps): ?>
<dt>Jumps</dt><dd><?php echo $z->jumps ?></dd>
<?php endif;
if ($z->holds): ?>
<dt>Holds</dt><dd><?php echo $z->holds ?></dd>
<?php endif;
if ($z->mines): ?>
<dt>Mines</dt><dd><?php echo $z->mines ?></dd>
<?php endif;
if ($z->trips): ?>
<dt>Trips</dt><dd><?php echo $z->trips ?></dd>
<?php endif;
if ($z->rolls): ?>
<dt>Rolls</dt><dd><?php echo $z->rolls ?></dd>
<?php endif;
if ($z->lifts): ?>
<dt>Lifts</dt><dd><?php echo $z->lifts ?></dd>
<?php endif;
if ($z->fakes): ?>
<dt>Fakes</dt><dd><?php echo $z->fakes ?></dd>
<?php endif; ?>
</dl>
</td>
<td><ul>
<li><?php echo link_to("Download", "@edit_download?id=$z->id") ?></li>
<li>View Ratings</li>
<li><?php echo link_to("Classic Chart", "@chart_quick?id={$z->id}&kind=classic") ?></li>
<li><?php echo link_to("Rhythm Chart", "@chart_quick?id={$z->id}&kind=rhythm") ?></li>
</ul></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>