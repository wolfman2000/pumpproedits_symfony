<?php
include_stylesheets_for_form($form);
include_javascripts_for_form($form);
if (!isset($mpart)) { $mpart = false; }
echo form_tag($route, "multipart=$mpart"); ?>
  <fieldset>
    <legend><?php include_slot('legend', 'Fill in all of the fields.') ?></legend>
    <dl>
      <?php echo $form ?>
    </dl>
  <p><button name="submit" id="submit" type="submit" value="submit">Submit!</button></p>
  </fieldset>
</form>
