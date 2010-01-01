<?php slot('title', "Edits of $song — Pump Pro Edits");
slot('h2', "<h2>Edits of $song</h2>"); ?>

<p>All of the edits of the chosen song are listed below.
Feel free to preview, download, play, and rate.</p>

<?php $data = array('query' => $songs, 'what' => 'Song');
//include_partial('edits/edit_table', $data) ?>
