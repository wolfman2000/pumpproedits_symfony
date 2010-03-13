<?php slot('title', 'Edit Stat Getter — ITG Edits');
slot('h2', "<h2>Get Edit's Stats</h2>"); ?>
<p>
    Do you need to quickly get stats on an edit without uploading
    the edit file itself?  If so, use the form below.
</p>
<p>
    The general format for edits is below.
    Make sure your edit follows it.
</p>
<pre>
#SONG:<var>Song Name</var>;
#NOTES:
<var>dance-single OR dance-double</var>:
<var>EditNameHere</var>:
Edit:
<var>1-99</var>:
<var>Comma separated list of 5, 11, or 22 numbers on a single line</var>:

0010
0000
0100
0000
,
1001
0000
0110
0000
;
</pre>

<?php include_partial('stats/form', array('form' => $form)) ?>
