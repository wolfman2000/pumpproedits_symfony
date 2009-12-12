<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_user_user->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $ppe_user_user->getName() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $ppe_user_user->getEmail() ?></td>
    </tr>
    <tr>
      <th>Role:</th>
      <td><?php echo $ppe_user_user->getRoleId() ?></td>
    </tr>
    <tr>
      <th>Is confirmed:</th>
      <td><?php echo $ppe_user_user->getIsConfirmed() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_user_user->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_user_user->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('user/edit?id='.$ppe_user_user->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('user/index') ?>">List</a>
