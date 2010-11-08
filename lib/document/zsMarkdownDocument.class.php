<?php

require_once dirname(__FILE__).'./../vendor/markdown/markdown.php';
require_once dirname(__FILE__).'./../vendor/geshi/geshi.php';

/**
 *
 * @author kbond
 */
class zsMarkdownDocument extends zsDocument
{
  public function toMarkdown($highlight = true)
  {
    $this->newContent = Markdown($this->newContent);

    if ($highlight)
      $this->newContent = preg_replace_callback(
              '#<pre><code>(.+?)</code></pre>#s',
              array($this, 'highlight'),
              $this->newContent
      );

    return $this;
  }

  public function parseClasses($pattern = '#<(.+?)>\s*\[(.+?)\]#', $replacement = '<$1 class="$2">')
  {
    $this->newContent = preg_replace(
            $pattern,
            $replacement,
            $this->newContent
    );

    return $this;
  }

  protected function highlight($matches, $default_language = '')
  {
    if (preg_match('/^\[(.+?)\]\s*(.+)$/s', $matches[1], $match))
    {
      return $this->callGeshi($match[2], $match[1]);
    }
    else
    {
      if ($default_language)
        return $this->callGeshi($matches[1], $default_language);

      return "<pre>".$matches[1]."</pre>";
    }
  }

  protected function callGeshi($text, $language)
  {
    if ($language == 'html')
      $language = 'html4strict';

    $text = html_entity_decode($text);

    $geshi = new GeSHi($text, $language);
    $geshi->enable_classes();

    // disable links on PHP functions, HTML tags, ...
    $geshi->enable_keyword_links(false);

    return @$geshi->parse_code();
  }
}
