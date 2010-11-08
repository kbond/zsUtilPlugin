<?php

class searchOptimizeindexTask extends sfBaseTask
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
    $this->addArgument('index', sfCommandArgument::OPTIONAL, 'The index to optimize as specified in your search.yml', null);

    $this->namespace        = 'search';
    $this->name             = 'optimize-index';
    $this->briefDescription = 'Optimizes the specified index (default if not specified)';
    $this->detailedDescription = <<<EOF
The [search:optimize-index|INFO] task does things.
Call it with:

  [php symfony search:update-index [application] [index (optional)] |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
    //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration($arguments['application'], $options['env'], true));

    $searchIndex = new zsSearchIndex($arguments['index']);

    //optimize to increase speed
    $searchIndex->optimize();
  }
}
