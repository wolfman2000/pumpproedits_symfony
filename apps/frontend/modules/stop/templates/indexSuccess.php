<h1>Ppe song stops List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Song</th>
      <th>Beat</th>
      <th>Break</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_song_stops as $ppe_song_stop): ?>
    <tr>
      <td><a href="<?php echo url_for('stop/show?id='.$ppe_song_stop->getId()) ?>"><?php echo $ppe_song_stop->getId() ?></a></td>
      <td><?php echo $ppe_song_stop->getSongId() ?></td>
      <td><?php echo $ppe_song_stop->getBeat() ?></td>
      <td><?php echo $ppe_song_stop->getBreak() ?></td>
      <td><?php echo $ppe_song_stop->getCreatedAt() ?></td>
      <td><?php echo $ppe_song_stop->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('stop/new') ?>">New</a>
