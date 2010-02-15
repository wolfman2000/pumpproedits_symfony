<?php slot('title', "Edit Uploaded — Pump Pro Edits");
slot('h2', '<h2 class="success_list">Edit Uploaded!</h2>'); ?>

<p>
  Your edit has now been uploaded to the system!
  More edits can be uploaded below.
</p>

<?php include_partial("upload/form", array('form' => $form)); ?>
