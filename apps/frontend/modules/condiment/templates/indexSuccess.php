<h1>Ppe user condiments List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Oregano</th>
      <th>Salt</th>
      <th>Pepper</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ppe_user_condiments as $ppe_user_condiment): ?>
    <tr>
      <td><a href="<?php echo url_for('condiment/show?id='.$ppe_user_condiment->getId()) ?>"><?php echo $ppe_user_condiment->getId() ?></a></td>
      <td><?php echo $ppe_user_condiment->getUserId() ?></td>
      <td><?php echo $ppe_user_condiment->getOregano() ?></td>
      <td><?php echo $ppe_user_condiment->getSalt() ?></td>
      <td><?php echo $ppe_user_condiment->getPepper() ?></td>
      <td><?php echo $ppe_user_condiment->getCreatedAt() ?></td>
      <td><?php echo $ppe_user_condiment->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('condiment/new') ?>">New</a>
