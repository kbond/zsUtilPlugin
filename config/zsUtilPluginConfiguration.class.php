<?php

/**
 * Description of zsUtilPluginConfiguration class
 *
 * @package zsUtilPlugin
 * @author kbond
 */
class zsUtilPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('task.cache.clear', array('zsUtilPluginConfiguration', 'listenToTaskCacheClearEvent'));
    $this->dispatcher->connect('routing.load_configuration', array('zsUtilPluginConfiguration', 'listenToRoutingLoadConfigurationEvent'));

  }

  /**
   * Loads zsThumb route if enabled
   *
   * @param sfEvent $event
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    if (!self::getProperty('zsThumb', 'enabled'))
      return;

    /* @var $routing sfRouting */
    $routing = $event->getSubject();

    $routing->prependRoute('zsThumb', new zsThumbRoute('/thumb/:size/:path', array('module' => 'zsThumb', 'action' => 'show')));
  }

  /**
   * Clears thumb cache if delete_with_cc is set in app.yml
   *
   * @param sfEvent $event
   */
  static public function listenToTaskCacheClearEvent(sfEvent $event)
  {
    if (!self::getProperty('zsThumb', 'delete_with_cc'))
      return;

    /* @var $task sfTask */
    $task = $event->getSubject();

    //$task->runTask('thumb:clear');
    $thumb_task = new zsThumbClearTask(ProjectConfiguration::getActive()->getEventDispatcher(), new sfFormatter());
    $thumb_task->run();
    $task->logSection('zsThumb', 'clearing...');
  }

  /**
   * Return property defined in app.yml
   *
   * @param string $property A section of the zsUtil config
   */
  static function getProperty($section, $value = null, $default = null)
  {
    $properties = sfConfig::get('app_zsUtilPlugin_'.$section);

    if ($value && $properties)
      if (array_key_exists($value, $properties))
        return $properties[$value];
      else
        return $default;
    else
      return $default;

    return $properties;
  }
}

