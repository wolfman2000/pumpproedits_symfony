<?php
include_stylesheets_for_form($form);
include_javascripts_for_form($form);
echo form_tag($route, "multipart=true"); ?>
  <fieldset>
    <legend>Select the edit to preview.</legend>
    <dl>
      <?php echo $form ?>
    </dl>
  <p><button name="submit" id="submit" type="submit" value="submit">Submit!</button></p>
  </fieldset>
</form>
