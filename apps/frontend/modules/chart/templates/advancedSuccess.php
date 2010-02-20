<?php slot('title', 'Edit Chart Generator — Pump Pro Edits');
slot('h2', "<h2>Edit Chart Generator</h2>"); ?>
<p>
Select the edit you want to see a chart of.* You can
also select your own edit from your hard drive.
</p>
<p>*: This uses <abbr title="Scalar Vector Graphics">SVG</abbr>.
Internet Explorer users may either require a plugin or a different
web browser to view the content.</p>

<?php include_partial($part, array('form' => $form, 'route' => '@chart_adv_post', 'legend' => 'Select the edit to preview.')) ?>
