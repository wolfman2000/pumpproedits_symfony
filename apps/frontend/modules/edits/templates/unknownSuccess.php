<?php slot('title', "Unknown Edits — Pump Pro Edits");
slot('h2', "<h2>Unknown Edits</h2>"); ?>

<p>These are all of the charts that do not seem to have
a proper author. The vast majority of these edits were
previously assigned as edits that Andamiro made: based
on the content, that is thrown into doubt. Either way,
enjoy these unsung edits.</p>

<?php $data = array('showsong' => 1, 'query' => $users,
  'caption' => "Unknown Edits",
  'summary' => "All of the edits without a proper author");
include_partial('edits/edit_table', $data);