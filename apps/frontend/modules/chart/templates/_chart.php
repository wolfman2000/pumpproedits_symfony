<?php
include_stylesheets_for_form($form);
include_javascripts_for_form($form);
echo form_tag($route, "multipart=true"); ?>
  <fieldset>
    <legend>Select the edit to preview.</legend>
    <section>
    <dl>
      <dt><?php echo $form['edits']->renderLabel() ?></dt>
      <dd><?php echo $form['edits'] ?></dd>
      <dt><?php echo $form['file']->renderLabel() ?></dt>
      <dd>
        <?php echo $form['file'] ?>
        <button id="validate_rm_file" value="Nevermind" name="validate[rm_file]" type="button">Nevermind</button>
        </dd>
    </dl>
    </section>
    <section>
    <dl>
      <dt><?php echo $form['kind']->renderLabel() ?></dt>
      <dd><?php echo $form['kind'] ?></dd>
      <dt><?php echo $form['red4']->renderLabel() ?></dt>
      <dd><?php echo $form['red4'] ?></dd>
      <dt><?php echo $form['speed']->renderLabel() ?></dt>
      <dd><?php echo $form['speed'] ?></dd>
      <dt><?php echo $form['mpcol']->renderLabel() ?></dt>
      <dd><?php echo $form['mpcol'] ?></dd>
    </dl>
    </section>
  <p><button name="submit" id="submit" type="submit" value="submit">Submit!</button></p>
  </fieldset>
</form>
