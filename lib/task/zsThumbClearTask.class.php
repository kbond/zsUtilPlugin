<?php

/**
 * @package zsUtilPlugin
 * @subpackage zsThumb
 */
class zsThumbClearTask extends sfBaseTask
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
      new sfCommandOption('size', null, sfCommandOption::PARAMETER_REQUIRED, 'The thumbnail sizes to remove', 'all')
    ));

    $this->namespace        = 'thumb';
    $this->name             = 'clear';
    $this->briefDescription = 'Clears the thumb cache (all by default)';
    $this->detailedDescription = <<<EOF
The [thumb:clear|INFO] task does things.
Call it with:

  [php symfony thumb:clear|INFO]

Clears all cache by default.  To choose a specific size add the size parameter:

  [php symfony thumb:clear --size=md|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
    //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $cache_dir = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'thumb';

    $finder = sfFinder::type('file');

    if ($options['size'] == 'all')
      $files = $finder->in($cache_dir);
    else
      $files = $finder->in($cache_dir . DIRECTORY_SEPARATOR . $options['size']);

    foreach ($files as $file)
    {
      unlink($file);
      $this->logSection('file-', $file);
    }
  }
}
