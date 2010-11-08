<?php

/**
 * Manages a Zend_Lucene search index
 *
 * @package zsUtilPlugin
 * @subpackage zsSearch
 * @author kbond
 */
class zsSearchIndex
{
  protected $name;
  protected $config;

  public function __construct($name = null)
  {
    $config = zsSearchConfigHandler::getIndex($name);

    $this->name = key($config);
    $this->config = $config[$this->name];
  }

  /**
   * Updates the index for an object
   *
   * @param Doctrine_Record $object
   */
  public function updateIndex(Doctrine_Record $object, $delete = false)
  {
    /* error checking */
    if (!array_key_exists('models', $this->config) || empty ($this->config['models']))
      throw new Exception(sprintf('No models set in search.yml', $name));
    if (!array_key_exists($model = get_class($object), $this->config['models']))
      throw new Exception(sprintf('Model "%s" not defined in "%s" index in your search.yml', $model, $this->name));

    $id = $this->generateId($object->getId(), $model);
    $config = $this->config['models'][$model];

    //delete existing entries
    foreach ($this->search('_id:"'.$id.'"') as $hit)
      $this->getIndex()->delete($hit->id);

    if ($delete)
      return;

    //only add to search if canSearch method on model returns true (search if no method exists)
    if (method_exists($object, 'canSearch'))
      if (!call_user_func(array($object, 'canSearch')))
        return;

    $doc = new Zend_Search_Lucene_Document();

    // store a key for deleting in future
    $doc->addField(Zend_Search_Lucene_Field::Keyword('_id', $id));

    // store job primary key and model name to identify it in the search results
    $doc->addField(Zend_Search_Lucene_Field::Keyword('_pk', $object->getId()));
    $doc->addField(Zend_Search_Lucene_Field::Keyword('_model', $model));

    // store title - used for search result title
    if (!array_key_exists('title', $config))
      throw new Exception(sprintf('A title must be set for model "%s" in search.yml', $model));

    $doc->addField(Zend_Search_Lucene_Field::unIndexed('_title',
            call_user_func(array($object, 'get'.sfInflector::camelize($config['title'])))));

    // store description - used for search result description
    if (!array_key_exists('description', $config))
      throw new Exception(sprintf('A description must be set for model "%s" in search.yml', $model));

    $doc->addField(Zend_Search_Lucene_Field::unIndexed('_description',
            call_user_func(array($object, 'get'.sfInflector::camelize($config['description'])))));

    // store url - @todo add more routing options
    if (!array_key_exists('route', $config))
      throw new Exception(sprintf('A route must be set for model "%s" in search.yml', $model));

    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    $url = url_for($config['route'], $object);

    $doc->addField(Zend_Search_Lucene_Field::unIndexed('_url', $url));

    //store fields
    if (array_key_exists('fields', $config))
      foreach ($config['fields'] as $field => $config)
      {
        $doc->addField(Zend_Search_Lucene_Field::UnStored($field,
                call_user_func(array($object, 'get'.sfInflector::camelize($field))), 'utf-8'));
      }

    //save index
    $this->getIndex()->addDocument($doc);
    $this->getIndex()->commit();
  }

  /**
   * Optimizes this index
   */
  public function optimize()
  {
    $this->getIndex()->optimize();
  }

  /**
   * Performs a search of this index
   *
   * @param string $query the search query
   * @return array Zend_Search_Lucene_Search_QueryHit
   */
  public function search($query)
  {
    $results = $this->getIndex()->find($query);

    return $results;
  }

  /**
   * Provides a list of models for this index
   *
   * @return array of models defined in search.yml for this index
   */
  public function getModels()
  {
    return $this->config['models'];
  }

  public function getIndex()
  {
    $file = sfConfig::get('sf_data_dir').'/'.sfInflector::underscore($this->name) .
            '.'.sfConfig::get('sf_environment').'.index';

    ProjectConfiguration::registerZend();

    if (file_exists($file))
    {
      return Zend_Search_Lucene::open($file);
    }

    return Zend_Search_Lucene::create($file);
  }

  protected function generateId($id, $model)
  {
    return sha1($id.$model);
  }
}
