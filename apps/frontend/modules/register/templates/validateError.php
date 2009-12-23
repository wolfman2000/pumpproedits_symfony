<?php slot('title', 'Registration Failure — Pump Pro Edits') ?>
<h2>Error!</h2>
<?php if (isset($data)): ?>
<p>There was a problem with processing the data.</p>
<ul>
<?php foreach ($data as $d): ?>
<li><?php echo $d ?></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>The errors are listed inside the form.</p>
<?php endif; ?>
<p>Please check the form and try again.</p>

<?php include_partial("register/form", array('form' => $form));
