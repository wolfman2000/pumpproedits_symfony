<table id="base" summary="Base edit files for users to download.">
  <caption>Download the Base Edit Files</caption>
  <thead><tr>
    <th>Song Name</th>
    <th>pump-single</th>
    <th>pump-double</th>
  </tr></thead>
  <tbody>
    <?php foreach ($base_songs as $b): ?>
    <tr>
      <td><?php echo $b->getName() ?></td>
      <?php $s = '@download_base_edit?id=%d&type=%s'; ?>
      <td><a href="<?php echo url_for(sprintf($s, $b->getId(), 'single')) ?>"><?php echo $b->getAbbr() ?> Single</a></td>
      <td><a href="<?php echo url_for(sprintf($s, $b->getId(), 'double')) ?>"><?php echo $b->getAbbr() ?> Double</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
