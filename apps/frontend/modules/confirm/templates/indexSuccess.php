<?php slot('title', 'Confirm your account — Pump Pro Edits');
slot('h2', "<h2>Confirm your Account</h2>"); ?>
<p>You are almost able to submit edits and contribute to the website!</p>
<p>Just fill in the form below with your password. If you came to this page
through the navigation links instead of your email message, you will have to
enter your confirmation code as well.</p>

<?php include_partial('confirm/form', array('form' => $form)) ?>
