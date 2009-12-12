<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_user_role->getId() ?></td>
    </tr>
    <tr>
      <th>Role:</th>
      <td><?php echo $ppe_user_role->getRole() ?></td>
    </tr>
    <tr>
      <th>Value:</th>
      <td><?php echo $ppe_user_role->getValue() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_user_role->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_user_role->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('role/edit?id='.$ppe_user_role->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('role/index') ?>">List</a>
