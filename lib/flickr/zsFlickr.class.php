<?php

/**
 *
 * @author kbond
 */
class zsFlickr
{
  protected $_config;

  public function  __construct()
  {
    //load zend
    ProjectConfiguration::registerZend();

    $this->_config = sfConfig::get('app_zsUtilPlugin_social');
  }

  public function getSetImages($setName)
  {
    $setId = $this->getPhotosetId($setName);

    //set exists?
    if (!$setId)
      return false;

    $photoSet = $this->callMethod('flickr.photosets.getPhotos', array('photoset_id' => $setId));
    
    $photoIds = array();

    foreach ($photoSet->photoset->photo as $photo)
      $photoIds[] = (string) $photo['id'];

    $photos = array();

    foreach ($photoIds as $id)
      $photos[] = new zsFlickrImage($id, $this);

    return $photos;
  }

  protected function getPhotosetId($name)
  {
    $sets = $this->callMethod('flickr.photosets.getList');
    
    //check if photoset exists
    foreach ($sets->photosets->photoset as $photoset)
      if ($photoset->title == $name)
        return (string)$photoset['id'];

    return false;
  }

  public function callMethod($method, $params = array())
  {

    $url = 'http://api.flickr.com/services/rest/';
    $params = array_merge($params, array(
            'method' => $method,
            'api_key' => $this->_config['flickr']['api_key'],
            'user_id' => $this->_config['flickr']['user_id']
    ));

    $request = new Zend_Http_Client($url);
    $request->setParameterGet($params);

    $response = $request->request();

    if ($response->isError())
      throw new Exception("Flickr request failed");

    $xml = new SimpleXMLElement($response->getBody());

    return $xml;
  }
}
