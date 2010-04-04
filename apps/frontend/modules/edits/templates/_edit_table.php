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
<?php $l = substr(ucfirst($z->style), 0, 1); ?>
<dt>Style</dt><dd><?php echo $l . $z->diff ?></dd>
<dt>Steps</dt><dd><?php echo $z->ysteps . ($l === "R" ? "/$z->msteps" : "") ?></dd>
<?php if ($z->yjumps or $z->mjumps): ?>
<dt>Jumps</dt><dd><?php echo $z->yjumps . ($l === "R" ? "/$z->mjumps" : "") ?></dd>
<?php endif;
if ($z->yholds or $z->mholds): ?>
<dt>Holds</dt><dd><?php echo $z->yholds . ($l === "R" ? "/$z->mholds" : "") ?></dd>
<?php endif;
if ($z->ymines or $z->mmines): ?>
<dt>Mines</dt><dd><?php echo $z->ymines . ($l === "R" ? "/$z->mmines" : "") ?></dd>
<?php endif;
if ($z->ytrips or $z->mtrips): ?>
<dt>Trips</dt><dd><?php echo $z->ytrips . ($l === "R" ? "/$z->mtrips" : "") ?></dd>
<?php endif;
if ($z->yrolls or $z->mrolls): ?>
<dt>Rolls</dt><dd><?php echo $z->yrolls . ($l === "R" ? "/$z->mrolls" : "") ?></dd>
<?php endif;
if ($z->ylifts or $z->mlifts): ?>
<dt>Lifts</dt><dd><?php echo $z->ylifts . ($l === "R" ? "/$z->mlifts" : "") ?></dd>
<?php endif;
if ($z->yfakes or $z->mfakes): ?>
<dt>Fakes</dt><dd><?php echo $z->yfakes . ($l === "R" ? "/$z->mfakes" : "") ?></dd>
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
<?php endif;
if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") === false): ?>
<li><?php echo link_to("Classic Chart", "@chart_quick?id={$z->id}&kind=classic"); ?></li>
<li><?php echo link_to("Rhythm Chart", "@chart_quick?id={$z->id}&kind=rhythm"); ?></li>
<?php endif; ?>
</ul></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
