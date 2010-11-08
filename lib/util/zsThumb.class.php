<?php

/**
 * @package zsUtilPlugin
 * @subpackage zsThumb
 * @author kbond
 */
class zsThumb
{
  protected $cached_path;
  protected $orig_path;
  protected $web_path;
  protected $size;

  /**
   * params:
   *  - 'path' - path to the original image (relative to web dir)
   *  - 'size' - size as defined in thumbnails.yml
   *
   * @param array $params
   */
  public function __construct($params)
  {
    $path = str_replace('/', DIRECTORY_SEPARATOR, $params['path']);

    $this->size = $params['size'];
    $this->web_path = '/thumb/'.$params['size'].'/'.$params['path'];
    $this->cached_path = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.
            'thumb'.DIRECTORY_SEPARATOR.$this->size.DIRECTORY_SEPARATOR.
            $path;
    $this->orig_path = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.
            $path;
  }

  public function getWebPath()
  {
    return $this->web_path;
  }

  public function getCachedPath()
  {
    return $this->cached_path;
  }

  public function isCached()
  {
    if (file_exists($this->cached_path))
            return true;

    return false;
  }


  /**
   * Cache the image based on the size set in thumbnails.yml
   */
  public function cache()
  {
    $cache_dir = pathinfo($this->cached_path, PATHINFO_DIRNAME);

    //create cache dir if it doesn't exist
    if (!file_exists($cache_dir))
      mkdir($cache_dir, 0777, true);

    $size = zsThumbConfigHandler::getSize($this->size);

    //do image transformations
    $new_image = new sfImage($this->orig_path);
    $new_image->thumbnail($size['width'], $size['height'],
            array_key_exists('method', $size) ? $size['method'] : 'fit');

    if (array_key_exists('quality', $size))
      $new_image->setQuality($size['quality']);

    $new_image->saveAs($this->cached_path);
  }
}
