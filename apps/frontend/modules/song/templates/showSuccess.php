<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_song_song->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $ppe_song_song->getName() ?></td>
    </tr>
    <tr>
      <th>Abbr:</th>
      <td><?php echo $ppe_song_song->getAbbr() ?></td>
    </tr>
    <tr>
      <th>Measures:</th>
      <td><?php echo $ppe_song_song->getMeasures() ?></td>
    </tr>
    <tr>
      <th>Is problem:</th>
      <td><?php echo $ppe_song_song->getIsProblem() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_song_song->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_song_song->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('song/edit?id='.$ppe_song_song->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('song/index') ?>">List</a>
