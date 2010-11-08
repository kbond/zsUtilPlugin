<?php if ($pager->haveToPaginate()): ?>
  <?php use_helper('zsPagination') ?>
<div class="pagination">
    <?php if ($pager->getPage() > 1): ?>
  <a class="page-prev" href="<?php echo page_url($pager->getPreviousPage(), $routeName, $params) ?>">&laquo; Prev</a>
    <?php else: ?>
  <span class="page-prev disabled">&laquo; Prev</span>
    <?php endif; ?>

    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
  <span class="page-current"><?php echo $page ?></span>
      <?php else: ?>
  <a href="<?php echo page_url($page, $routeName, $params) ?>"><?php echo $page ?></a>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($pager->getPage() < $pager->getLastPage()): ?>
  <a class="page-next" href="<?php echo page_url($pager->getNextPage(), $routeName, $params) ?>">Next &raquo;</a>
    <?php else: ?>
  <span class="page-next disabled">Next &raquo;</span>
    <?php endif; ?>

</div>
<?php endif; ?>