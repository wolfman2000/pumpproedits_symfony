<h1>Ppe song songs List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Abbr</th>
      <th>Measures</th>
      <th>Is problem</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_song_songs as $ppe_song_song): ?>
    <tr>
      <td><a href="<?php echo url_for('song/show?id='.$ppe_song_song->getId()) ?>"><?php echo $ppe_song_song->getId() ?></a></td>
      <td><?php echo $ppe_song_song->getName() ?></td>
      <td><?php echo $ppe_song_song->getAbbr() ?></td>
      <td><?php echo $ppe_song_song->getMeasures() ?></td>
      <td><?php echo $ppe_song_song->getIsProblem() ?></td>
      <td><?php echo $ppe_song_song->getCreatedAt() ?></td>
      <td><?php echo $ppe_song_song->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('song/new') ?>">New</a>
