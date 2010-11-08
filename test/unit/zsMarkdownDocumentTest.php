<?php

require_once dirname(__FILE__).'/../../../../test/bootstrap/unit.php';
$t = new lime_test(3, new lime_output_color());

$doc = new zsMarkdownDocument('# Hello World');

$doc->toMarkdown();

$t->is(preg_match('#<h1>#', $doc->render()), true, '->toMarkdown()');

$input = <<< EOF
## [header]header 2

paragraph

* list 1
  * elist 2
* list 2

[note]para2

> cool dude
this is awesome

# header

    <?php echo "neat!" ?>

another para

    [php]
    <?php echo "cool!" ?>

EOF;

$output = $doc->reset($input)->toMarkdown()->parseClasses()->render();

$t->is(preg_match('#<span class="kw2">&lt;#', $output), true, '->toMarkdown geshi');
$t->is(preg_match('#<p class="note"#', $output), true, '->parseClasses()');