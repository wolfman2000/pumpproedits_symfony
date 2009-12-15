<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
 
<?php echo form_tag(include_slot('route')), 'multipart=' . include_slot('mpart')) ?>
  <fieldset>
    <legend><?php include_slot('legend', 'Fill in all of the fields.') ?></legend>
    <dl>
      <?php echo $form ?>
    </dl>
  <p><button name="submit" id="submit" type="submit" value="submit">Submit!</button></p>
  </fieldset>
</form>
