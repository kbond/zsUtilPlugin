<?php

require_once dirname(__FILE__).'/../../../../test/bootstrap/unit.php';
$t = new lime_test(11, new lime_output_color());

$doc = new zsDocument('Hello World');

$t->comment('zsDocument General');

$t->is($doc->render(), 'Hello World', 'simple render');
$t->is($doc->truncate(4)->render(), 'H...', 'truncate text');
$doc->reset('Hello World');
$t->is($doc->truncate()->render(), 'Hello World', 'truncated is shorter than length');

$doc->reset('<span>Hello</span> World');

$t->is($doc->stripTags()->render(), 'Hello World', 'strip tags');

$t->comment('zsDocument->preview()');
$doc->reset('<p>Hello World</p><p><!-- pagebreak --></p><p>How Are you</p>');

$t->is($doc->preview()->render(), '<p>Hello World</p>', '<p><!-- pagebreak --></p>');

$doc->reset('<h1>Hello World<!-- pagebreak --></h1><p></p><p>How Are you</p>');

$t->is($doc->preview()->render(), '<h1>Hello World</h1>', '...<!-- pagebreak --></p>');

$doc->reset('<p>Hello World</p><p><!-- pagebreak -->How Are you</p>');

$t->is($doc->preview()->render(), '<p>Hello World</p>', '<p><!-- pagebreak -->...');

$t->comment('zsDocument->hasBreak()');
$t->is($doc->hasBreak(), true);
$doc->reset('<p>Hello World</p>');
$t->is($doc->hasBreak(), false);

$t->comment('zsDocument chaining');
$doc->reset('<p>Hello World</p><p><!-- pagebreak -->How Are you</p>');
$t->is($doc->preview()->stripTags()->truncate(5, '')->render(), 'Hello');
$t->is($doc->reset()->stripTags()->render(), 'Hello WorldHow Are you', 'reset');