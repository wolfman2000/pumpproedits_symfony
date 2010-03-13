<?php slot('title', "Edits of $song — ITG Edits");
slot('h2', "<h2>Edits of $song</h2>"); ?>

<p>All of the edits of the chosen song are listed below.
Feel free to preview, download, play, and rate.</p>

<?php $data = array('showuser' => 1, 'query' => $songs,
  'caption' => "Edits of $song",
  'summary' => "All of the edits for the song $song");
include_partial('edits/edit_table', $data);
