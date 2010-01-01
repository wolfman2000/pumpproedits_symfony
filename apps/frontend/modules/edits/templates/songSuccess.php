<?php slot('title', 'Edit List by Song Page $page — Pump Pro Edits');
slot('h2', "<h2>Edits</h2>"); ?>

<p>Proper content to come shortly.</p>

<table>
<thead><tr>
<th>Song</th>
<th>Num Edits</th>
</tr></thead>
<tbody>
<?php foreach($songs as $s): ?>
<tr>
<td><?php echo $s->core ?></td>
<td><?php echo $s->num_edits ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>