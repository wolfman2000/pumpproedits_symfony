<ul>
<li><?php echo link_to('Upload Edit', '@upload_get') ?></li>
<?php if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") === false): ?>
<li><?php echo link_to('Edit Charter', '@chart_adv_get') ?></li>
<li><?php echo link_to('Official Stepcharts', '@chart_off_get') ?></li>
<?php endif; ?>
<li><?php echo link_to('Log Out', '@logout') ?></li>
</ul>
