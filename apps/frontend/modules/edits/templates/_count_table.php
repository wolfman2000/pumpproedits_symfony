<section id="multiCol">
<?php $what = strtolower($what); foreach ($query as $b): ?>
<p><span><?php echo link_to($b->core, "@edit_c$what?id=$b->id") ?></span>
<?php echo $b->num_edits ?></p>
<?php endforeach; ?>
</section>