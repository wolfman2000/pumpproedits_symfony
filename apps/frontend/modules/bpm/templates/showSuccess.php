<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_song_bpm->getId() ?></td>
    </tr>
    <tr>
      <th>Song:</th>
      <td><?php echo $ppe_song_bpm->getSongId() ?></td>
    </tr>
    <tr>
      <th>Beat:</th>
      <td><?php echo $ppe_song_bpm->getBeat() ?></td>
    </tr>
    <tr>
      <th>Bpm:</th>
      <td><?php echo $ppe_song_bpm->getBpm() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_song_bpm->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_song_bpm->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('bpm/edit?id='.$ppe_song_bpm->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('bpm/index') ?>">List</a>
