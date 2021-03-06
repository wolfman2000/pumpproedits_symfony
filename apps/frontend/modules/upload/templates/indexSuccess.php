<?php slot('title', "Upload an Edit — Pump Pro Edits");
slot('h2', "<h2>Upload an Edit</h2>"); ?>

<p>
  Uploading an edit is relatively simple. Just find the file
  on your hard drive and hit the submit button. Please, only
  submit edits that you made.
</p>
<p>
  This same form is used for uploading new and updated edits.
  As a general rule, you cannot have two edits of the same
  song and style (single or double) have the same edit title,
  even if the step content is different. If you do this, the old
  edit will be lost forever.
</p>
<?php include_partial("upload/form", array('form' => $form));