<h1>Ppe user users List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Is confirmed</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_user_users as $ppe_user_user): ?>
    <tr>
      <td><a href="<?php echo url_for('user/show?id='.$ppe_user_user->getId()) ?>"><?php echo $ppe_user_user->getId() ?></a></td>
      <td><?php echo $ppe_user_user->getName() ?></td>
      <td><?php echo $ppe_user_user->getEmail() ?></td>
      <td><?php echo $ppe_user_user->getRoleId() ?></td>
      <td><?php echo $ppe_user_user->getIsConfirmed() ?></td>
      <td><?php echo $ppe_user_user->getCreatedAt() ?></td>
      <td><?php echo $ppe_user_user->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('user/new') ?>">New</a>
