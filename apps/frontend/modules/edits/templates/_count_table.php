<table id="count_table" summary="Number of edits by $what">
  <caption>Edit count by <?php echo $what ?></caption>
  <thead><tr>
    <th><?php echo $what ?></th>
    <th>Edit Count</th>
  </tr></thead>
  <tbody>
    <?php foreach ($query as $b): ?>
    <tr>
      <td><?php echo $b->core ?></td>
      <td><?php echo $b->num_edits ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>