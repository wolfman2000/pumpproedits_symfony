<?php slot('title', "Edits by $user — ITG Edits");
slot('h2', "<h2>Edits by $user</h2>"); ?>

<p>All of the edits created by the chosen person are available below.
Feel free to preview, download, play, and rate.</p>

<?php $data = array('showsong' => 1, 'query' => $users,
  'caption' => "Edits by $user",
  'summary' => "All of the edits created by $user");
include_partial('edits/edit_table', $data);