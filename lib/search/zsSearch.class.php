<?php

/**
 * Search utility class
 *
 * @package zsUtilPlugin
 * @subpackage zsSearch
 * @author kbond
 */
class zsSearch
{
  /**
   * Performs a search of the specified index (null for default)
   *
   * @param string $query the search query
   * @param string $index the specifed index
   * @return array Zend_Search_Lucene_Search_QueryHit
   */
  public static function search($query, $index = null)
  {
    $search = new zsSearchIndex($index);

    return $search->search($query);
  }
}
