<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
 
<form action="<?php echo url_for('@edit_stat_post') ?>" method="POST">
  <fieldset>
    <legend>Select your .edit file</legend>
    <dl>
      <?php echo $form ?>
    </dl>
  </fieldset>
</form>

