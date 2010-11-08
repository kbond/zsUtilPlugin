<?php

/**
 * BasezsSearch actions.
 *
 * @package    zsUtilPlugin
 * @subpackage zsSearch
 * @author     kbond
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BasezsSearchActions extends sfActions
{
  public function executeResults(sfWebRequest $request)
  {
    $this->q = $request->getParameter('q');

    $this->redirectUnless($this->q, '@homepage');

    $results = zsSearch::search($this->q);
    //$this->url = $this->getRequest()->getUri();
    $this->pager = new zsArrayPager($results, zsUtilPluginConfiguration::getProperty('zsSearch', 'max_results', 10));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }
}
