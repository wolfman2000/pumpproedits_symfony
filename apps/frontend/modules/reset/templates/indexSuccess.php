<?php slot('title', 'Reset your password — Pump Pro Edits');
slot('h2', "<h2>Reset your Password</h2>"); ?>
<p>
  Use the form below to reset your password.
  Use the confirmation code that was emailed
  to you to confirm that you are you.
</p>
<?php include_partial('reset/form', array('form' => $form)) ?>
