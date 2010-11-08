<?php

class zsSearchable extends Doctrine_Template
{
  /* Array of Usertraceable options
   */
  protected $_options = array(
    'index' =>  null
  );

  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  public function setTableDefinition()
  {
    $this->addListener(new zsSearchableListener($this->_options));
  }
}