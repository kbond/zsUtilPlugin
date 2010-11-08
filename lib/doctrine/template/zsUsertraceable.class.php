<?php

class zsUsertraceable extends Doctrine_Template
{
  /* Array of Usertraceable options
   */
  protected $_options = array(
    'created' =>  array(
      'name' => 'created_by',
      'type' => 'integer',
      'disabled' => false,
      'options' => array()),
    'updated' =>  array(
      'name' => 'updated_by',
      'type' => 'integer',
      'disabled' => false,
      'options' => array())
  );

  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  public function setTableDefinition()
  {
    if( ! $this->_options['created']['disabled'])
    {
      $this->hasColumn(
        $this->_options['created']['name'],
        $this->_options['created']['type'],
        $this->_options['created']['options']
      );
    }

    if( ! $this->_options['updated']['disabled'])
    {
      $this->hasColumn(
        $this->_options['updated']['name'],
        $this->_options['updated']['type'],
        $this->_options['updated']['options']);
    }

    $this->addListener(new zsUsertraceableListener($this->_options));
  }

  public function setUp()
  {
    
    if( ! $this->_options['created']['disabled'])
    {
      $this->hasOne('sfGuardUser as CreatedBy', array(
          'local' => $this->_options['created']['name'],
          'foreign' => 'id')
      );
    }
    
    if( ! $this->_options['updated']['disabled'])
    {
      $this->hasOne('sfGuardUser as UpdatedBy', array(
          'local' => $this->_options['updated']['name'],
          'foreign' => 'id')
      );
    }
  }
}