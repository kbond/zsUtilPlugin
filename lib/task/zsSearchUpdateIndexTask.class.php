<?php

class searchUpdateindexTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
    ));

    $this->addArgument('application', sfCommandArgument::REQUIRED, 'The application where your search.yml is defined');
    $this->addArgument('env', sfCommandArgument::REQUIRED, 'The environment your want to update the index on.');
    $this->addArgument('index', sfCommandArgument::OPTIONAL, 'The index to update as specified in your search.yml', null);

    $this->namespace        = 'search';
    $this->name             = 'update-index';
    $this->briefDescription = 'Updates the specified index (default if not specified)';
    $this->detailedDescription = <<<EOF
The [search:update-index|INFO] task does things.
Call it with:

  [php symfony search:update-index [application] [index (optional)] |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // need a scriptname otherwise it uses the symfony file
    if ($arguments['env'] != 'prod')
      $_SERVER['SCRIPT_NAME'] = '/'.$arguments['application'].'_'.$arguments['env'].'.php';
    else
      $_SERVER['SCRIPT_NAME'] = '/index.php';
    
    sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration($arguments['application'], $arguments['env'], true));
    
    $searchIndex = new zsSearchIndex($arguments['index']);

    //optimize to increase speed
    $searchIndex->optimize();

    //loop thru all models specified in this index and update all entries
    foreach ($searchIndex->getModels() as $model => $config)
    {
      $this->logSection('update', 'model: '.$model);
      foreach (Doctrine::getTable($model)->findAll() as $object)
      {
        $searchIndex->updateIndex($object);
        echo '.';
      }
      echo "\n";
    }

    //re-optimize index
    $searchIndex->optimize();
  }
}
