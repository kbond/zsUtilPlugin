<?php slot('search_query', $q) ?>

<h1>Search results for "<?php echo $q ?>"</h1>
<h2><?php echo $pager->count() ?> results (page <?php echo $pager->getPage() ?> of <?php echo $pager->getLastPage() ?>)</h2>

<div id="search-results-list">
  <?php foreach ($pager->getResults() as $result): ?>
  <div class="search-result">
      <p class="search-link"><?php echo link_to($result->_title, $result->_url) ?></p>
      <p class="search-description"><?php echo $result->_description ?></p>
      <p class="search-url"><?php echo 'http://'.$sf_request->getHost().$result->_url ?></p>
  </div>
  <?php endforeach; ?>
</div>

<?php include_partial('pagination', array('pager' => $pager, 'routeName' => 'search', 'params' => array('q' => $q))) ?>
