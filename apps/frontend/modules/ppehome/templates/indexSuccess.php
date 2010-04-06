<p>Welcome to the Pump Pro Edit database. Inside here, you will find
many edits that dance players such as yourself have created, along
with official charts that Pump creator Andamiro made themselves.</p>
<?php if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== false): ?>
<p>Due to your choice of web browser, the entire website will not be
available to you. Please consider switching web browsers to get the
full experience this website has to offer. In the meantime,
<a href="/java/PPEdits.zip">the old Java Editor</a> is available for
you to help create edits for any song on Pro 1.
<?php else: ?>
<p>Want to make an edit yourself? There is a new
<?php echo link_to("Edit Creator", "@edit_creator"); ?> tool
available for you to use. As an added bonus, if you
<?php echo link_to("Log In", "@login_get"); ?> before visiting,
you will be able to upload your work directly to your account!</p>
<?php endif; ?>
<p>If you want instant access on when edits are contributed or
when there is an internal website update,
<a href="http://www.twitter.com/pumpproedits">follow pumpproedits
on Twitter</a>.</p>
<p>Please pardon any mess inside: Pump it up Pro 2 is coming, and
I'm getting everything ready as soon as possible.</p>
