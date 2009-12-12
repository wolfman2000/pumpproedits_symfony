<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ppe_user_condiment->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ppe_user_condiment->getUserId() ?></td>
    </tr>
    <tr>
      <th>Oregano:</th>
      <td><?php echo $ppe_user_condiment->getOregano() ?></td>
    </tr>
    <tr>
      <th>Salt:</th>
      <td><?php echo $ppe_user_condiment->getSalt() ?></td>
    </tr>
    <tr>
      <th>Pepper:</th>
      <td><?php echo $ppe_user_condiment->getPepper() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ppe_user_condiment->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ppe_user_condiment->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('condiment/edit?id='.$ppe_user_condiment->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('condiment/index') ?>">List</a>
