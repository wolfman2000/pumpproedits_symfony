<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
 
<?php echo form_tag_for($form, '@job') ?>
  <?php echo $form ?>
</form>
