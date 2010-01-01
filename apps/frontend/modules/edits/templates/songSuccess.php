<?php slot('title', 'Edit List by Song — Pump Pro Edits');
slot('h2', "<h2>Edits by Song</h2>"); ?>

<p>Below are all of the songs that have edits available.
If a song you like doesn't have an edit, feel free to 
contribute one yourself.</p>

<?php $data = array('query' => $songs, 'what' => 'Song');
include_partial('edits/count_table', $data) ?>