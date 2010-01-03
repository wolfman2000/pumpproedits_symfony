<?php slot('title', 'Ratings Error — Pump Pro Edits');
slot('h2', '<h2 class="error_list">Ratings Error</h2>'); ?>
<p>If you got here, you most likely did one of these things:</p>
<ul>
<li>You came only to /votes instead of choosing an edit as well.</li>
<li>You chose an edit that doesn't exist.</li>
<li>You chose an edit that has no votes.</li>
</ul>
<p>Try going back <?php echo link_to('home', '@homepage') ?> and starting again.</p>