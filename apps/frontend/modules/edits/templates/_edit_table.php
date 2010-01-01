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
<th>Title</th>
<th>Stats</th>
<th>Actions</th>
</tr></thead>
<tbody>
<?php foreach ($query as $z): ?>
<tr>
<?php if (isset($showuser)): ?>
<td><?php echo link_to($z->uname, "@edit_cuser?id=" . $z->user_id) ?></td>
<?php endif; ?>
<td><?php echo $z->title ?></td>
<td>
<dl>
<dt>Style</dt><dd><?php echo ($z->is_single ? "S" : "D") . $z->diff ?></dd>
<dt>Steps</dt><dd><?php echo $z->steps ?></dd>
<dt>Jumps</dt><dd><?php echo $z->jumps ?></dd>
<dt>Holds</dt><dd><?php echo $z->holds ?></dd>
<dt>Mines</dt><dd><?php echo $z->mines ?></dd>
<dt>Trips</dt><dd><?php echo $z->trips ?></dd>
<dt>Rolls</dt><dd><?php echo $z->rolls ?></dd>
</dl>
</td>
<td>Download</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>