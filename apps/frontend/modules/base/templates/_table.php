<?php $style = array('single', 'double', 'halfdouble', 'routine'); ?>
<table id="base" summary="Base edit files for users to download.">
  <caption>Download the Base Edit Files</caption>
  <thead><tr>
    <th>Song Name</th>
    <?php foreach ($style as $st): ?>
    <th>pump-<?php echo $st; ?></th>
    <?php endforeach; ?>
  </tr></thead>
  <tbody>
    <?php foreach ($base_songs as $b): ?>
    <tr>
      <td><?php echo $b->getName() ?></td>
      <?php $s = '@download_base_edit?id=%d&type=%s'; ?>
      <?php foreach ($style as $st):
      $url = url_for(sprintf($s, $b->getId(), $st));
      $txt = $b->getAbbr() . " " . ucfirst($st); ?>
      <td><?php if ($st !== "routine" or $b->tmp): ?><a href="<?php echo $url; ?>"><?php echo $txt; ?></a><?php endif; ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
