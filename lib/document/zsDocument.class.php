<?php

/**
 *
 * @author Kevin
 */
class zsDocument
{
  protected $origContent;
  protected $newContent;

  public function  __construct($content)
  {
    $this->reset($content);
  }

  public function  __toString()
  {
    return (string)$this->render();
  }

  public function hasContent()
  {
    return (bool) strlen($this->newContent);
  }

  public function render()
  {
    return $this->newContent;
  }

  public function reset($content = null)
  {
    if ($content)
      $this->origContent = $content;

    $this->newContent = $this->origContent;

    return $this;
  }

  public function truncate($length = 250, $truncate_string = '...')
  {
    //dont truncate if length is longer than string
    if (strlen($this->newContent) < $length)
      return $this;

    $text = substr($this->newContent, 0, $length - strlen($truncate_string));
    $text .= $truncate_string;
    $this->newContent = $text;

    return $this;
  }

  public function stripTags($allowable_tags = null)
  {
    $text = strip_tags($this->newContent, $allowable_tags);

    $this->newContent = $text;

    return $this;
  }

  public function hasBreak($seperator = '<!-- pagebreak -->')
  {
    return preg_match('/'.$seperator.'/', $this->origContent);
  }

  public function preview($seperator = '<!-- pagebreak -->')
  {
    if (!$this->hasBreak($seperator))
      return $this;

    // ...<p><!-- pagebreak --></p>...
    if (preg_match('/(.+)<p>'.$seperator.'<\/p>/s', $this->newContent, $matches))
      return $this->setNewContent($matches[1]);

    // ...<!-- pagebreak --></p>...
    if (preg_match('/(.+)'.$seperator.'(<\/[^<]+?>)/s', $this->newContent, $matches))
      return $this->setNewContent($matches[1].$matches[2]);

    // ...<p><!-- pagebreak -->...
    if (preg_match('/(.+)<[^\/<]+?>'.$seperator.'/s', $this->newContent, $matches))
      return $this->setNewContent($matches[1]);

    return $this;
  }

  public function xmlEscape()
  {
    $text = $this->newContent;
    $text = str_replace('&nbsp;', ' ', $text);
    $this->newContent = $text;

    return $this;
  }

  protected function setNewContent($content)
  {
    $this->newContent = $content;
    return $this;
  }
}
