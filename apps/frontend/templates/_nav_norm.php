<nav>
<?php include_partial("global/mess_$authin", array()) ?>
<ul>
<li>
<h4>Members</h4>
<?php include_partial("global/memb_$authin", array()) ?>
</li>
<li>
    <h4>Edits</h4>
    <ul>
<?php /*
    <li><?php echo link_to("Base Edit Files", '@base_edit') ?></li>
    <li><?php echo link_to("Edit Stat Getter", '@edit_stat_get') ?></li>
*/ ?>
    <li><?php echo link_to("Edit List by Song", '@edit_song') ?></li>
    <li><?php echo link_to("Edit List by User", '@edit_user') ?></li>
    <li><?php echo link_to("Official Chart Edits", '@edit_official') ?></li>
    <li><?php echo link_to("Unknown Author Edits", '@edit_unknown') ?></li>
    </ul>
</li>
<li>
    <h4>Everyone</h4>
    <ul>
    <?php if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== false): ?>
    <li><a href="/java/PPEdits.zip">Edit Maker (Java)</a></li>
    <?php else: ?>
    <li><?php echo link_to("Edit Creator", '@edit_creator') ?></li>
    <?php endif; ?>
    <li><?php echo link_to("Contact", '@contact_get') ?></li>
    <li><?php echo link_to("Credits/Thanks", '@thanks') ?></li>
    </ul>
</li>
</ul>
</nav>
