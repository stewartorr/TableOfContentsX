<?php
/**
 * @package tableofcontentsx
 * @subpackage build
 */

function getSnippetContent($filename = '') {
    $o = file_get_contents($filename);
    $o = str_replace(['<?php', '?>'], '', $o);
    return trim($o);
}

$snippets = [];
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray([
    'name'        => 'TableOfContents',
    'description' => 'This extra takes your MODX content and generates a customisable table of contents list based on the `h1`, `h2`, `h3`, `h4`, `h5`, and `h6` tags.',
    'snippet'     => getSnippetContent($sources['snippets'] . 'snippet.TableOfContentsX.php'),
], '', true, true);
$snippets[] = $snippet;

return $snippets;