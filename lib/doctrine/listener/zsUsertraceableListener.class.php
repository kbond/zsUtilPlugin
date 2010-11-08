<?php
class zsUsertraceableListener extends Doctrine_Record_Listener
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

  /**
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preInsert(Doctrine_Event $event)
  {
    /*if(!sfContext::hasInstance())
      return;*/

    try
    {
      $guard_user = sfContext::getInstance()->getUser()->getGuardUser();
    } catch (Exception $e)
    {
      return;
    }

    if ( ! $this->_options['created']['disabled'])
    {
      $createdName = $event->getInvoker()->getTable()
              ->getFieldName($this->_options['created']['name']);

      $modified = $event->getInvoker()->getModified();

      if ( ! isset($modified[$createdName]))
      {
        $event->getInvoker()->$createdName = $guard_user;
      }
    }

    if ( ! $this->_options['updated']['disabled'])
    {
      $updatedName = $event->getInvoker()->getTable()
              ->getFieldName($this->_options['updated']['name']);

      $modified = $event->getInvoker()->getModified();

      if ( ! isset($modified[$updatedName]))
      {
        $event->getInvoker()->$updatedName = $guard_user;
      }
    }
  }

  /**
   *
   * @param Doctrine_Event $evet
   * @return void
   */
  public function preUpdate(Doctrine_Event $event)
  {
    try
    {
      $guard_user = sfContext::getInstance()->getUser()->getGuardUser();
    } catch (Exception $e)
    {
      return;
    }

    if ( ! $this->_options['updated']['disabled'])
    {
      $updatedName = $event->getInvoker()->getTable()
              ->getFieldName($this->_options['updated']['name']);

      $modified = $event->getInvoker()->getModified();

      if ( ! isset($modified[$updatedName]))
      {
        $event->getInvoker()->$updatedName = $guard_user;
      }
    }
  }

  /**
   * Set the updated field for dql update queries
   *
   * @param Doctrine_Event $evet
   * @return void
   */
  public function preDqlUpdate(Doctrine_Event $event)
  {
    try
    {
      $guard_user = sfContext::getInstance()->getUser()->getGuardUser();
    } catch (Exception $e)
    {
      return;
    }

    if ( ! $this->_options['updated']['disabled'])
    {
      $params = $event->getParams();
      $updatedName = $event->getInvoker()->getTable()
              ->getFieldName($this->_options['updated']['name']);

      $field = $params['alias'] . '.' . $updatedName;

      $query = $event->getQuery();

      if ( ! $query->contains($field))
      {
        $query->set($field, '?', $guard_user);
      }
    }
  }

}
