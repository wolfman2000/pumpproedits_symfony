<?php
  slot('title', "Base Edit Files Page $page — Pump Pro Edits");
?> 
<h2>Base Edit Files</h2> 
<p>
	Edit files are made with a .edit extension, and placed in
	the appropriate folder on your USB drive when you go play
	Pump It Up Pro.
</p>
<p>
	At times you may want to edit a song's steps, but don't
	exactly know what measure they start on or how long
	the song lasts.  The base edit files below are
	provided as a convenience for edit makers to have
	a place to start.
</p>
<p>
	These are so useful, even some of
	the developers of the game use these files.  Surely
	they will work for you!  Just download the single
	and/or double steps of the files you want.
</p>

<?php include_partial('base/table', array('base_songs' => $pager->getResults())) ?>

<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination">
    <a href="<?php echo url_for('@base_edit') ?>?page=1">«</a>
 
    <a href="<?php echo url_for('@base_edit') ?>?page=<?php echo $pager->getPreviousPage() ?>">&lt;</a>
 
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <?php echo $page ?>
      <?php else: ?>
        <a href="<?php echo url_for('@base_edit') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
      <?php endif; ?>
    <?php endforeach; ?>
 
    <a href="<?php echo url_for('@base_edit') ?>?page=<?php echo $pager->getNextPage() ?>">&gt;</a>
 
    <a href="<?php echo url_for('@base_edit') ?>?page=<?php echo $pager->getLastPage() ?>">»</a>
  </div>
<?php endif; ?>
 
<div class="pagination_desc">
  <strong><?php echo count($pager) ?></strong> jobs in this category
 
  <?php if ($pager->haveToPaginate()): ?>
    - page <strong><?php echo $pager->getPage() ?>/<?php echo $pager->getLastPage() ?></strong>
  <?php endif; ?>
</div>
