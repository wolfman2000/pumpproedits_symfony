<table id="edits" summary="<?php echo html_entity_decode($summary, ENT_COMPAT, "UTF-8") ?>">
<caption><?php echo html_entity_decode($caption, ENT_COMPAT, "UTF-8") ?></caption>
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
<td><?php
if ($z->user_id == 2):
$route = "@edit_official";
elseif ($z->user_id == 95):
$route = "@edit_unknown";
else:
$route = "@edit_cuser?id=$z->user_id";
endif;
echo link_to($z->uname, $route) ?></td>
<?php endif;
if (isset($showsong)): ?>
<td><?php echo link_to($z->sname, "@edit_csong?id=$z->song_id") ?></td>
<?php endif; ?>
<td><?php echo $z->title ?></td>
<td>
<dl>
<dt>Style</dt><dd><?php echo substr(ucfirst($z->style), 0, 1) . $z->diff ?></dd>
<dt>Steps</dt><dd><?php echo $z->ysteps ?></dd>
<?php if ($z->yjumps): ?>
<dt>Jumps</dt><dd><?php echo $z->yjumps ?></dd>
<?php endif;
if ($z->yholds): ?>
<dt>Holds</dt><dd><?php echo $z->yholds ?></dd>
<?php endif;
if ($z->ymines): ?>
<dt>Mines</dt><dd><?php echo $z->ymines ?></dd>
<?php endif;
if ($z->ytrips): ?>
<dt>Trips</dt><dd><?php echo $z->ytrips ?></dd>
<?php endif;
if ($z->yrolls): ?>
<dt>Rolls</dt><dd><?php echo $z->yrolls ?></dd>
<?php endif;
if ($z->ylifts): ?>
<dt>Lifts</dt><dd><?php echo $z->ylifts ?></dd>
<?php endif;
if ($z->yfakes): ?>
<dt>Fakes</dt><dd><?php echo $z->yfakes ?></dd>
<?php endif;
if ($z->num_votes): ?>
<dt>Avg Score</dt><dd><?php echo $z->tot_votes / $z->num_votes ?></dd>
<?php endif; ?>
</dl>
</td>
<td><ul>
<li><?php echo link_to("Download", "@edit_download?id=$z->id") ?></li>
<?php if ($z->num_votes): ?>
<li><?php echo link_to("View Ratings", "@ratings?eid=$z->id") ?></li>
<?php endif; ?>
<li><?php echo link_to("Classic Chart", "@chart_quick?id={$z->id}&kind=classic") ?></li>
<li><?php echo link_to("Rhythm Chart", "@chart_quick?id={$z->id}&kind=rhythm") ?></li>
</ul></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>