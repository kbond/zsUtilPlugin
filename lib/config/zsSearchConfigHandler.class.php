<?php

/**
 * @package zsUtilPlugin
 * @subpackage zsSearch
 */
class zsSearchConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    // Parse the yaml
    $config = $this->parseYamls($configFiles);
    $strConfig = var_export($config, true);
    $data = <<<EOF
<?php
// auto-generated by %s
// date: %s
return $strConfig;
EOF;
    $data = sprintf($data, __CLASS__, date('Y/m/d H:i:s'));

    return $data;
  }

  /**
   * Returns an index defined in search.yml
   * @param string $name the name of the index (null for first)
   * @return array (name, config array)
   */
  public static function getIndex($name = null)
  {
    $indexes = include sfContext::getInstance()->getConfigCache()->checkConfig('config/search.yml');

    //use first index if no name set
    if (!$name)
      $name = key($indexes);

    if (!array_key_exists($name, $indexes))
      throw new Exception(sprintf('The index "%s" is not set in search.yml', $name));

    return array($name => $indexes[$name]);
  }
}