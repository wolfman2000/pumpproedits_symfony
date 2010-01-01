<?php slot('title', "Edits of $song — Pump Pro Edits");
slot('h2', "<h2>Edits of $song</h2>"); ?>

<p>All of the edits of the chosen song are listed below.
Feel free to preview, download, play, and rate.</p>

<table id="edits">
<caption>Edits of <?php echo $song ?></caption>
<thead><tr>
<th>User</th>
<th>Stats</th>
<th>Actions</th>
</tr></thead>
<tbody>
<?php foreach ($songs as $z): ?>
<tr>
<td><?php echo $z->user_id ?></td>
<td>Steps: <?php echo $z->steps ?></td>
<td>Download</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>