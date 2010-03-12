<?php
include_stylesheets_for_form($form);
include_javascripts_for_form($form);
echo form_tag($route, "multipart=false"); ?>
  <fieldset>
    <legend>Select the song to preview.</legend>
    <?php if ($form->hasGlobalErrors()):
    echo $form->renderGlobalErrors();
    endif; ?>
    <section id="edit">
      <p><?php echo $form['edits']->renderLabel() ?></p>
      <p><?php echo $form['edits'] ?></p>
    </section>
    <section>
    <p><?php echo $form['style']->renderLabel() ?></p>
    <p><?php echo $form['style'] ?></p>
    <p><?php echo $form['diff']->renderLabel() ?></p>
    <p><?php echo $form['diff'] ?></p>
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
