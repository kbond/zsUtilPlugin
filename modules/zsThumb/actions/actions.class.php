<?php

/**
 * @package zsUtilPlugin
 * @subpackage zsThumb
 * @author kbond
 */
class zsThumbActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $params = $this->getRoute()->getParameters();

    $image = new zsThumb($params);

    if (!$image->isCached())
      $image->cache();
    
    $this->redirect($image->getWebPath());
  }
}
