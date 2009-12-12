<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_edit_edit->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ppe_edit_edit->getUserId() ?></td>
    </tr>
    <tr>
      <th>Song:</th>
      <td><?php echo $ppe_edit_edit->getSongId() ?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $ppe_edit_edit->getTitle() ?></td>
    </tr>
    <tr>
      <th>Is single:</th>
      <td><?php echo $ppe_edit_edit->getIsSingle() ?></td>
    </tr>
    <tr>
      <th>Diff:</th>
      <td><?php echo $ppe_edit_edit->getDiff() ?></td>
    </tr>
    <tr>
      <th>Steps:</th>
      <td><?php echo $ppe_edit_edit->getSteps() ?></td>
    </tr>
    <tr>
      <th>Jumps:</th>
      <td><?php echo $ppe_edit_edit->getJumps() ?></td>
    </tr>
    <tr>
      <th>Holds:</th>
      <td><?php echo $ppe_edit_edit->getHolds() ?></td>
    </tr>
    <tr>
      <th>Mines:</th>
      <td><?php echo $ppe_edit_edit->getMines() ?></td>
    </tr>
    <tr>
      <th>Trips:</th>
      <td><?php echo $ppe_edit_edit->getTrips() ?></td>
    </tr>
    <tr>
      <th>Rolls:</th>
      <td><?php echo $ppe_edit_edit->getRolls() ?></td>
    </tr>
    <tr>
      <th>Lifts:</th>
      <td><?php echo $ppe_edit_edit->getLifts() ?></td>
    </tr>
    <tr>
      <th>Fakes:</th>
      <td><?php echo $ppe_edit_edit->getFakes() ?></td>
    </tr>
    <tr>
      <th>Is problem:</th>
      <td><?php echo $ppe_edit_edit->getIsProblem() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_edit_edit->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_edit_edit->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('edit/edit?id='.$ppe_edit_edit->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('edit/index') ?>">List</a>
