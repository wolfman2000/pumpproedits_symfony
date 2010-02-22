<?php slot('title', 'Chart Gen Failure — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Chart Gen Failure!</h2>');
if (isset($data)): ?>
<p>There was an error while parsing the song file.</p>
<p class="error_list"><?php echo $data ?></p>
<p>Please fix the error and try again.</p>
<?php else: ?>
<p>Are you sure you selected a song? Double check and try again.</p>
<?php endif;
include_partial('chart/official', array('form' => $form, 'route' => '@chart_off_post', 'legend' => 'Select the song to preview.')) ?>
