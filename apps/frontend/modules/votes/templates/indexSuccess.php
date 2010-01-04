<?php slot('title', 'Ratings on Edit — Pump Pro Edits');
slot('h2', '<h2>Ratings on Edit</h2>'); ?>
<p>Listed below are all of the ratings made for the chosen
edit. All ratings are from 0 - 10, with an optional description.</p>

<table id="rating" summary="View all ratings and comments for <?php echo $sname ?>.">
<caption>Ratings for <?php echo $sname ?></caption>
<thead><tr>
<th>Voter</th>
<th>Rating</th>
<th>Comments</th>
</tr></thead>
<tbody>
<?php foreach ($votes as $v): ?>
<tr>
<td><?php echo link_to($v->PPE_User_User->name, "@edit_cuser?id=$v->user_id") ?></td>
<td><?php echo $v->rating ?></td>
<td><?php echo $v->reason ? $v->reason : "No comment" ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>