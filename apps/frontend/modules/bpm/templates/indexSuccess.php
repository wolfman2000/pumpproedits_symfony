<h1>Ppe song bp ms List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Song</th>
      <th>Beat</th>
      <th>Bpm</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_song_bp_ms as $ppe_song_bpm): ?>
    <tr>
      <td><a href="<?php echo url_for('bpm/show?id='.$ppe_song_bpm->getId()) ?>"><?php echo $ppe_song_bpm->getId() ?></a></td>
      <td><?php echo $ppe_song_bpm->getSongId() ?></td>
      <td><?php echo $ppe_song_bpm->getBeat() ?></td>
      <td><?php echo $ppe_song_bpm->getBpm() ?></td>
      <td><?php echo $ppe_song_bpm->getCreatedAt() ?></td>
      <td><?php echo $ppe_song_bpm->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('bpm/new') ?>">New</a>
