<?php slot('title', 'Official Chart Generator — Pump Pro Edits');
slot('h2', "<h2>Official Chart Generator</h2>"); ?>
<p>
Select the song and difficulty you want to see a chart of.*
</p>
<p>*: This uses <abbr title="Scalar Vector Graphics">SVG</abbr>.
Internet Explorer users may either require a plugin or a different
web browser to view the content.</p>

<?php include_partial('chart/official', array('form' => $form, 'route' => '@chart_off_post', 'legend' => 'Select the song to preview.')) ?>
