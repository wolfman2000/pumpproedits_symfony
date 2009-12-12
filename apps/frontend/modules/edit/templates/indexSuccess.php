<h1>Ppe edit edits List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Song</th>
      <th>Title</th>
      <th>Is single</th>
      <th>Diff</th>
      <th>Steps</th>
      <th>Jumps</th>
      <th>Holds</th>
      <th>Mines</th>
      <th>Trips</th>
      <th>Rolls</th>
      <th>Lifts</th>
      <th>Fakes</th>
      <th>Is problem</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_edit_edits as $ppe_edit_edit): ?>
    <tr>
      <td><a href="<?php echo url_for('edit/show?id='.$ppe_edit_edit->getId()) ?>"><?php echo $ppe_edit_edit->getId() ?></a></td>
      <td><?php echo $ppe_edit_edit->getUserId() ?></td>
      <td><?php echo $ppe_edit_edit->getSongId() ?></td>
      <td><?php echo $ppe_edit_edit->getTitle() ?></td>
      <td><?php echo $ppe_edit_edit->getIsSingle() ?></td>
      <td><?php echo $ppe_edit_edit->getDiff() ?></td>
      <td><?php echo $ppe_edit_edit->getSteps() ?></td>
      <td><?php echo $ppe_edit_edit->getJumps() ?></td>
      <td><?php echo $ppe_edit_edit->getHolds() ?></td>
      <td><?php echo $ppe_edit_edit->getMines() ?></td>
      <td><?php echo $ppe_edit_edit->getTrips() ?></td>
      <td><?php echo $ppe_edit_edit->getRolls() ?></td>
      <td><?php echo $ppe_edit_edit->getLifts() ?></td>
      <td><?php echo $ppe_edit_edit->getFakes() ?></td>
      <td><?php echo $ppe_edit_edit->getIsProblem() ?></td>
      <td><?php echo $ppe_edit_edit->getCreatedAt() ?></td>
      <td><?php echo $ppe_edit_edit->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('edit/new') ?>">New</a>
