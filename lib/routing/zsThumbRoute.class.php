<?php

/**
 * zsThumbRoute class
 *
 * @package zsUtilPlugin
 * @subpackage zsThumb
 * @author kbond
 */
class zsThumbRoute extends sfRoute
{
  public function generate($params, $context = array(), $absolute = false)
  {
    return '/thumb/' . implode('/', $params);
  }

  public function matchesUrl($url, $context = array())
  {
    preg_match('/\/thumb\/([\w-]+)\/([\w-\/\.]+)/', $url, $matches);

    if (empty ($matches))
      return false;

    $parameters = array_merge($this->defaults, array(
            'size' => $matches[1],
            'path' => $matches[2]));

    return $parameters;
  }

}
