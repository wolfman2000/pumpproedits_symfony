<?php slot('title', 'Edit List by User — ITG Edits');
slot('h2', "<h2>Edits by User</h2>"); ?>

<p>Below are all of the users that have edits available.</p>

<?php $data = array('query' => $users, 'what' => 'User');
include_partial('edits/count_table', $data) ?>
