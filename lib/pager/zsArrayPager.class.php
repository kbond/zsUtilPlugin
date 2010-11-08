<?php

/**
 *
 * @author Kevin
 */
class zsArrayPager extends sfPager
{
  /**
   * Constructor.
   *
   * @param array   $array      The model class
   * @param integer $maxPerPage Number of records to display per page
   */
  public function __construct($array, $maxPerPage = 10)
  {
    $this->objects = $array;
    $this->setMaxPerPage($maxPerPage);
    $this->parameterHolder = new sfParameterHolder();
  }

  public function init()
  {
    $this->setNbResults(count($this->objects));

    if (($this->getPage() == 0 || $this->getMaxPerPage() == 0))
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
    }
  }

  public function retrieveObject($offset)
  {
    return $this->objects[$offset];
  }
  
  public function getResults()
  {
    return array_slice($this->objects, ($this->getPage() - 1) * $this->getMaxPerPage(), $this->maxPerPage);
  }


}
