<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
 
<?php echo form_tag_for($form, '@edit_stat_post') ?>
  <?php echo $form ?>
</form>
