<?php

/**
 *
 * @author kbond
 */
class zsFlickrImage
{
  protected $_flickr;
  protected $_sizes = array();

  protected
          $id,
          $name,
          $description;

  public function __construct($id, zsFlickr $flickr)
  {
    $this->_flickr = $flickr;
    $this->id = $id;

    $sizes = $flickr->callMethod('flickr.photos.getSizes', array('photo_id' => $id, 'secret' => '79fd9cfcb2faa3f6'));

    foreach ($sizes->sizes->size as $size)
      $this->_sizes[(string)$size['label']] = (string)$size['source'];    

    $info = $flickr->callMethod('flickr.photos.getInfo', array('photo_id' => $id));

    $this->description = $info->photo->description;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getLarge()
  {
    //only very large image have a large size
    if (array_key_exists('Large', $this->_sizes))
      return $this->_sizes['Large'];

    return $this->_sizes['Medium'];
  }

  public function getThumbnail()
  {
    return $this->getSize('Thumbnail');
  }

  public function getSquare()
  {
    return $this->getSize('Square');
  }

  public function getSmall()
  {
    return $this->getSize('Small');
  }

  public function getMedium()
  {
    return $this->getSize('Medium');
  }

  public function getSize($size = 'Thumbnail')
  {
    return $this->_sizes[$size];
  }
}
