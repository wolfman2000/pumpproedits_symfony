<?php slot('title', "Official Edits — Pump Pro Edits");
slot('h2', "<h2>Official Edits</h2>"); ?>

<p>These are all of the charts that Andamiro made for
their primary Pump It Up line. They can be Anothers,
World Max missions, or anything in between.
Feel free to preview, download, play, and rate.</p>

<?php $data = array('showsong' => 1, 'query' => $users,
  'caption' => "Official Edits",
  'summary' => "All of the edits created by Andamiro");
include_partial('edits/edit_table', $data);