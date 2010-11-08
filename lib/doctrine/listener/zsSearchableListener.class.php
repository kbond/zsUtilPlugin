<?php
class zsSearchableListener extends Doctrine_Record_Listener
{

  protected $_options = array();

  /**
   * __construct
   *
   * @param array $options
   * @return void
   */
  public function __construct(array $options)
  {
    $this->_options = $options;
  }

  public function postSave(Doctrine_Event $event)
  {
    //if called from task do nothing
    if (!sfContext::hasInstance())
      return;

    //update index
    $searchIndex = new zsSearchIndex($this->_options['index']);
    $searchIndex->updateIndex($event->getInvoker());
  }

  public function postDelete(Doctrine_Event $event)
  {
    //if called from task do nothing
    if (!sfContext::hasInstance())
      return;

    //delete from index
    $searchIndex = new zsSearchIndex($this->_options['index']);
    $searchIndex->updateIndex($event->getInvoker(), true);
  }

}
