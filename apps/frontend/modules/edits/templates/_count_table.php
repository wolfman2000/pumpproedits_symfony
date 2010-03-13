<section id="multiCol">
<?php $what = strtolower($what); foreach ($query as $b): ?>
<p><span><?php echo link_to($b->core, "@edit_c$what?id=$b->id") ?></span>
<?php echo $b->num_edits ?></p>
<?php endforeach; ?>

</section>
<!--
<table id="count_table" summary="Number of edits by $what">
  <caption>Edit count by <?php echo $what ?></caption>
  <thead><tr>
    <th><?php echo $what ?></th>
    <th>Edit Count</th>
  </tr></thead>
  <?php $what = strtolower($what) ?>
  <tbody>
    <?php # foreach ($query as $b): ?>
    <tr>
      <td><?php # echo link_to($b->core, "@edit_c$what?id=$b->id") ?></td>
      <td><?php # echo $b->num_edits ?></td>
    </tr>
    <?php # endforeach; ?>
  </tbody>
</table>
-->