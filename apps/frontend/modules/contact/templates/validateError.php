<?php slot('title', 'Contact Failure — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Contact Error!</h2>');
if (isset($data)): ?>
<p>There was a problem with processing the data.</p>
<ul class="error_list">
<?php foreach ($data as $d): ?>
<li><?php echo $d ?></li>
<?php endforeach; ?>
</ul>
<?php if(isset($noshow)): ?>
<p>You will have to send a manual email. To make it easier
for you, just copy and paste the subject and message body
once you <a href="mailto:jafelds@gmail.com">click the email
link</a>.</p>
<pre>
<?php echo $subj ?>



<?php echo $body ?>
</pre>
<?php else: ?>
<p>Please check your data for typos and try again.</p>
<?php include_partial("contact/form", array('form' => $form));
endif;
else: ?>
<p>The errors are listed inside the form.</p>
<p>Please check the form and try again.</p>
<?php include_partial("contact/form", array('form' => $form));
endif;
