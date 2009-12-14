<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
 
<?php echo form_tag('@edit_stat_post', 'multipart=true') ?>
  <fieldset>
    <legend>Select your .edit file</legend>
    <dl>
      <?php echo $form ?>
    </dl>
  <p><button name="submit" id="submit" type="submit" value="submit">Submit!</button></p>
  </fieldset>
</form>

