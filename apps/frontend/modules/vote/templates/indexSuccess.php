<h1>Ppe vote votes List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Edit</th>
      <th>Rating</th>
      <th>Reason</th>
      <th>Is problem</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_vote_votes as $ppe_vote_vote): ?>
    <tr>
      <td><a href="<?php echo url_for('vote/show?id='.$ppe_vote_vote->getId()) ?>"><?php echo $ppe_vote_vote->getId() ?></a></td>
      <td><?php echo $ppe_vote_vote->getUserId() ?></td>
      <td><?php echo $ppe_vote_vote->getEditId() ?></td>
      <td><?php echo $ppe_vote_vote->getRating() ?></td>
      <td><?php echo $ppe_vote_vote->getReason() ?></td>
      <td><?php echo $ppe_vote_vote->getIsProblem() ?></td>
      <td><?php echo $ppe_vote_vote->getCreatedAt() ?></td>
      <td><?php echo $ppe_vote_vote->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('vote/new') ?>">New</a>
