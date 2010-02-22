<?php
include_stylesheets_for_form($form);
include_javascripts_for_form($form);
echo form_tag($route, "multipart=true"); ?>
  <fieldset>
    <legend>Select the edit to preview.</legend>
    <?php if ($form->hasGlobalErrors()):
    echo $form->renderGlobalErrors();
    endif; ?>
    <section id="edit">
      <p><?php echo $form['edits']->renderLabel() ?></p>
      <p><?php echo $form['edits'] ?></p>
      <p><?php echo $form['file']->renderLabel() ?></p>
      <p>
        <?php echo $form['file'] ?>
        <button id="validate_rm_file" value="Nevermind" name="validate[rm_file]" type="button">Nevermind</button>
      </p>
    </section>
    <section>
    <p><?php echo $form['kind']->renderLabel() ?></p>
    <p><?php echo $form['kind'] ?></p>
    <p><?php echo $form['red4']->renderLabel() ?></p>
    <p><?php echo $form['red4'] ?></p>
    <p><?php echo $form['speed']->renderLabel() ?></p>
    <p><?php echo $form['speed'] ?></p>
    <p><?php echo $form['mpcol']->renderLabel() ?></p>
    <p><?php echo $form['mpcol'] ?></p>
    <p><?php echo $form['scale']->renderLabel() ?></p>
    <p><?php echo $form['scale'] ?></p>
    </section>
  <p>
    <?php echo $form['_csrf_token'] ?>
    <button name="submit" id="submit" type="submit" value="submit">Submit!</button>
  </p>
  </fieldset>
</form>
