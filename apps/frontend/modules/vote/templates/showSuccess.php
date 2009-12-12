<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_vote_vote->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ppe_vote_vote->getUserId() ?></td>
    </tr>
    <tr>
      <th>Edit:</th>
      <td><?php echo $ppe_vote_vote->getEditId() ?></td>
    </tr>
    <tr>
      <th>Rating:</th>
      <td><?php echo $ppe_vote_vote->getRating() ?></td>
    </tr>
    <tr>
      <th>Reason:</th>
      <td><?php echo $ppe_vote_vote->getReason() ?></td>
    </tr>
    <tr>
      <th>Is problem:</th>
      <td><?php echo $ppe_vote_vote->getIsProblem() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_vote_vote->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_vote_vote->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('vote/edit?id='.$ppe_vote_vote->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('vote/index') ?>">List</a>
