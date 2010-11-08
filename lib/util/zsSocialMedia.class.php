<?php

class zsSocialMedia
{
  protected $_config;

  public function  __construct()
  {
    //load zend
    ProjectConfiguration::registerZend();

    $this->_config = sfConfig::get('app_zsUtilPlugin_social');
  }

  public function sendTweet($text, $shortenURLs = true)
  {
    if (!$this->_config['twitter']['enabled'])
      return false;

    if ($shortenURLs)
    {
      $url_match = '@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)?)@';

      $text = preg_replace_callback($url_match, array($this, 'parseURL'), $text);
    }

    try
    {
      $twitter = new Zend_Service_Twitter($this->_config['twitter']['username'],
              $this->_config['twitter']['password']);

      /* @var $response Zend_Rest_Client_Result */
      $response = $twitter->account->verifyCredentials();
      //die(var_dump($response));
      //if ($response->isError())
      //throw new sfException($response->error);

      $response = $twitter->statusUpdate($text);

      if ($response->isError())
        throw new sfException($response->error);

    } catch (Exception $exc)
    {
      return false;
    }

    return true;
  }

  public function shortenURL($url)
  {
    if (!$this->_config['bitly']['enabled'])
      return false;

    $client = new Zend_Http_Client('http://api.bit.ly/v3/shorten');
    $client->setParameterGet(array(
            'apiKey'  => $this->_config['bitly']['api_key'],
            'login'   => $this->_config['bitly']['login'],
            'uri'     => $url,
            'format'  => 'json'
    ));

    $response = Zend_Json_Decoder::decode($client->request()->getBody());

    if ($response['status_txt'] == 'OK')
      return $response['data']['url'];

    return false;
  }

  public function updateFacebookStatus($text, $attachment = null)
  {
    if (!$this->_config['enabled'])
      return false;

    require_once sfConfig::get('sf_lib_dir').'/vendor/facebook/facebook.php';

    $attachment = json_encode($attachment);

    $facebook = new Facebook($this->_config['facebook']['api_key'], $this->_config['facebook']['secret']);

    try
    {
      $result = $facebook->api_client->stream_publish($text,$attachment,null,null,$this->_config['facebook']['target_id']);

    }catch(Exception $o )
    {
      return false;
    }

    return true;
  }

  /*
   * Extract urls and shorten them
  */
  protected function parseURL($matches)
  {
    return $this->shortenURL($matches[0]);
  }

  public function getLatestTweets($count = 5)
  {
    if (!$this->_config['twitter']['enabled'])
      return false;

    $twitter = new Zend_Http_Client('http://twitter.com/statuses/user_timeline/'.$this->_config['twitter']['username'].'.json?count='.$count);

    $response = json_decode($twitter->request('GET')->getBody(), true);

    return $response;
  }  
}