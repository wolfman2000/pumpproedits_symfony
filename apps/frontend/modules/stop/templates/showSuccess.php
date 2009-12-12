<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_song_stop->getId() ?></td>
    </tr>
    <tr>
      <th>Song:</th>
      <td><?php echo $ppe_song_stop->getSongId() ?></td>
    </tr>
    <tr>
      <th>Beat:</th>
      <td><?php echo $ppe_song_stop->getBeat() ?></td>
    </tr>
    <tr>
      <th>Break:</th>
      <td><?php echo $ppe_song_stop->getBreak() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_song_stop->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_song_stop->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('stop/edit?id='.$ppe_song_stop->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('stop/index') ?>">List</a>
