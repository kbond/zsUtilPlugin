<?php

/**
 *
 * @author kbond
 */
class zsTools
{
  static function urlize($text)
  {
    // Remove all non url friendly characters with the unaccent function
    $text = Doctrine_Inflector::unaccent($text);

    $text = str_replace("'", "", $text);

    // Remove all none word characters
    $text = preg_replace('/\W/', ' ', $text);

    // More stripping. Replace spaces with dashes
    $text = preg_replace('/[^A-Z^a-z^0-9^\/]+/', '-',
            preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
            preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
            preg_replace('/::/', '/', $text))));

    return trim($text, '-');
  }

  static function urlizeLowercase($text)
  {
    $text = self::urlize($text);

    if (function_exists('mb_strtolower'))
    {
      $text = mb_strtolower($text);
    } else
    {
      $text = strtolower($text);
    }

    return $text;
  }
}
