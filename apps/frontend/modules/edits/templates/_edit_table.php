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
<?php endif; ?>
</dl>
</td>
<td><ul>
<li><?php echo link_to("Download", "@edit_download?id=$z->id") ?></li>
<li><?php echo link_to("View Chart", "@chart_quick?id={$z->id}&kind=rhythm") ?></li>
</ul></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>