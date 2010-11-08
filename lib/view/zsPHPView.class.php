<?php

/**
 * Adds fallback formats
 *
 * Set fallbacks in module.yml
 */
class zsPHPView extends sfPHPView
{
  public function initialize($context, $moduleName, $actionName, $viewName)
  {
    parent::initialize($context, $moduleName, $actionName, $viewName);

    $format = $context->getRequest()->getRequestFormat();    

    // make sure directory is set
    if (!$this->directory)
      $this->setDirectory($this->context->getConfiguration()->getTemplateDir($this->moduleName, str_replace('.'.$format, '', $this->template)));

    if ($format || ($format != 'html'))
      $this->checkFallback($format);

    return true;
  }

  /**
   *  See if template exists, if not, fall back (defaults to html)
   */
  protected function checkFallback($format)
  { 
    if (!file_exists($this->directory . DIRECTORY_SEPARATOR . $this->template))
    {
      $fallback_format = sfConfig::get('app_fallback_formats_'.$format);

      if ($fallback_format)
      {
        $this->template = str_replace('.'.$format, '.'.$fallback_format, $this->template);
        $this->checkFallback($fallback_format);
        return;
      }

      $this->template = str_replace('.'.$format, '', $this->template);
    }
  }
}