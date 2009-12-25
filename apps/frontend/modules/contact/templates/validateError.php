<?php slot('title', 'Contact Failure — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Contact Error!</h2>');
if (isset($data)): ?>
<p>There was a problem with processing the data.</p>
<ul class="error_list">
<?php foreach ($data as $d): ?>
<li><?php echo $d ?></li>
<?php endforeach; ?>
</ul>
<p>Please check your data for typos and try again.</p>
<?php else: ?>
<p>The errors are listed inside the form.</p>
<p>Please check the form and try again.</p>
<?php endif;
include_partial("contact/form", array('form' => $form));
