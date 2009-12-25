<?php slot('title', 'Register here — Pump Pro Edits');
slot('h2', '<h2>Register here</h2>'); ?>
<p> To be able to upload edits and access some member only
exclusive portions of the website, you will have to register.
It is easy to do: just fill out the form below, and reply
back to the email that is sent.</p>

<p>Your password will have to be at least 5 characters long,
and your username at least 4. Otherwise, you may not be
able to register.</p>

<?php include_partial("register/form", array('form' => $form)) ?>
