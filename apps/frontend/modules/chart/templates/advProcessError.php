<?php slot('title', 'Chart Gen Failure — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Chart Gen Failure!</h2>');
if (isset($data)): ?>
<p>There was an error while parsing the edit file.</p>
<p class="error_list"><?php echo $data ?></p>
<p>Please fix the error and try again.</p>
<?php else: ?>
<p>Are you sure you selected an edit file? Double check and try again.</p>
<?php endif;
include_partial('chart/form', array('form' => $form, 'route' => '@chart_adv_post')) ?>
