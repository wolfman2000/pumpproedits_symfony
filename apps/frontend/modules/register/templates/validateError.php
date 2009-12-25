<?php slot('title', 'Registration Failure — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Registration Error!</h2>');
if (isset($data)): ?>
<p>There was a problem with processing the data.</p>
<ul class="error_list">
<?php foreach ($data as $d): ?>
<li><?php echo $d ?></li>
<?php endforeach; ?>
</ul>
<?php if (!isset($noshow)): ?>
<p>Please check your data for typos and try again.</p>
<?php
include_partial("register/form", array('form' => $form));
else: ?>
<p>Please <?php echo link_to('Contact', '@contact_get'); ?> the webmaster
for more information.</p>
<?php
endif;
else: ?>
<p>The errors are listed inside the form.</p>
<p>Please check the form and try again.</p>
<?php include_partial("register/form", array('form' => $form));
endif;
