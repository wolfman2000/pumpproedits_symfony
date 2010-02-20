<?php slot('title', 'Chart Generator — Pump Pro Edits');
slot('h2', "<h2>Chart Generator</h2>"); ?>
<p>Want to see what your edit looks like in a graph before
you submit it? That's not a problem at all!* Use the form
below to preview your creation.</p>
<p>*: This uses <abbr title="Scalar Vector Graphics">SVG</abbr>.
Internet Explorer users may either require a plugin or a different
web browser to view the content.</p>

<?php include_partial($part, array('form' => $form, 'route' => '@chart_gen_post', 'legend' => 'Select your .edit file.')) ?>
