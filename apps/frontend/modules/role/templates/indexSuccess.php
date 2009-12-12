<h1>Ppe user roles List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Role</th>
      <th>Value</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_user_roles as $ppe_user_role): ?>
    <tr>
      <td><a href="<?php echo url_for('role/show?id='.$ppe_user_role->getId()) ?>"><?php echo $ppe_user_role->getId() ?></a></td>
      <td><?php echo $ppe_user_role->getRole() ?></td>
      <td><?php echo $ppe_user_role->getValue() ?></td>
      <td><?php echo $ppe_user_role->getCreatedAt() ?></td>
      <td><?php echo $ppe_user_role->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('role/new') ?>">New</a>
