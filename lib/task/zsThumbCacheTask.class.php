<?php

/**
 * @package zsUtilPlugin
 * @subpackage zsThumb
 */
class zsThumbCacheTask extends sfBaseTask
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

    $this->addArgument('application', sfCommandArgument::REQUIRED, 'The application where your thumbnails.yml is defined');
    $this->addArgument('size', sfCommandArgument::REQUIRED, 'Size as defined in your thumbnails.yml');
    $this->addArgument('path', sfCommandArgument::IS_ARRAY|sfCommandArgument::REQUIRED, 'Path to the directory you want to cache');

    $this->namespace        = 'thumb';
    $this->name             = 'cache';
    $this->briefDescription = 'Thumbnail caches a given directory of images';
    $this->detailedDescription = <<<EOF
The [thumb:cache|INFO] task does things.
Call it with:

  [php symfony thumb:cache [application] [size] [path] |INFO]

Example:

  [php symfony thumb:cache frontend md uploads/images |INFO]

You can set multiple paths:

  [php symfony thumb:cache frontend md uploads/assets/images uploads/users/profile/images |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
    //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration($arguments['application'], $options['env'], true));
    $web_dir = sfConfig::get('sf_web_dir');
    $size = $arguments['size'];

    foreach ($arguments['path'] as $path)
    {
      $path = trim($path, '/');

      $files = sfFinder::type('file')
              ->name('/\.(jpg|jpeg|png|gif)/')
              ->relative()
              ->in($web_dir.DIRECTORY_SEPARATOR.$path);
      
      foreach ($files as $file)
      {
        $image = new zsThumb(array('path' => $path.DIRECTORY_SEPARATOR.$file, 'size' => $size));
        $image->cache();
        $this->logSection('file+', $image->getCachedPath());
      }

    }

  }
}
