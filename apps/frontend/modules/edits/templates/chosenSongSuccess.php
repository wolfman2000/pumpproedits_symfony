<?php slot('title', "Edits of $song — Pump Pro Edits");
slot('h2', "<h2>Edits of $song</h2>"); ?>

<p>All of the edits of the chosen song are listed below.
Feel free to preview, download, play, and rate.</p>

<table id="edits">
<caption>Edits of <?php echo $song ?></caption>
<thead><tr>
<th>User</th>
<th>Title</th>
<th>Stats</th>
<th>Actions</th>
</tr></thead>
<tbody>
<?php foreach ($songs as $z): ?>
<tr>
<td><?php echo link_to($z->uname, "@edit_cuser?id=$z->user_id") ?></td>
<td><?php echo $z->title ?></td>
<td>
<dl>
<dt>Style</dt><dd><?php echo $z->is_single ? "S" : "D" . $z->diff ?></dd>
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