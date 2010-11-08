<?php

/**
 * Returns an <img> image tag for the image as a thumbnail as defined by the
 * size setting in your thumbnails.yml
 *
 * @param string $path The path to the image (relative to web dir)
 * @param string $size The size as defined in your thumbnails.yml file
 * @param array $options Same options as for image_tag
 */
function thumb_tag($path, $size, $options = array())
{
  return image_tag(thumb_path($path, $size), $options);
}

/**
 * Returns the path to an image thumbnail
 *
 * @param string $path The path to the image (relative to web dir)
 * @param string $size The size as defined in your thumbnails.yml file
 * @param boolean $absolute return absolute path?
 */
function thumb_path($path, $size, $absolute = false)
{
  $path = ltrim($path, '/');

  return url_for('zsThumb', array('size' => $size, 'path' => $path), $absolute);
}

/**
 * Returns a thumbnail link to originial image
 * Useful for a lightbox
 *
 * @param string $path The path to the image (relative to web dir)
 * @param string $size The size as defined in your thumbnails.yml file
 * @param  array $link_options Same options as for link_to
 * @param  array $image_options Same options as for image_tag
 *
 */
function thumb_link($path, $size, $link_options = array(), $image_options = array())
{
  //make sure path is absolute
  if (substr($path, 0, 1) != '/')
    $path = '/'.$path;

  return link_to(thumb_tag($path, $size, $image_options), $path, $link_options);
}