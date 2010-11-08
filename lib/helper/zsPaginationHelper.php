<?php

function page_url($page, $routeName, $params = array(), $absolute = false)
{
  //getRawValue because of output escaper
  return url_for($routeName, array_merge($params->getRawValue(), array('page' => $page)), $absolute);
}