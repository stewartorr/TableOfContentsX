<?php
/**
 * TableOfContentsX
 *
 * This extra takes your MODX content and generates a customisable table of 
 * contents list based on the `h1`, `h2`, `h3`, `h4`, `h5`, and `h6` tags.
 *
 * TableOfContentsX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * TableOfContentsX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author Stewart Orr <stewart.orr@gmail.com>
 * @version 1.3.1
 * 
 * 
 * Usage:
 * -----
 * 
 * Simple example to output of the table of contents as output filter:
 * 
 * [[*content:TableOfContentsX=`content`]]
 * [[*content:TableOfContentsX=`toc`]]
 * 
 * Customise the output of the table of contents:
 * 
 * [[TableOfContentsX? &input=`[[*content]]` &output=`content` ]]
 * [[TableOfContentsX? &input=`[[*content]]` &output=`toc` ]]
 * [[TableOfContentsX? &input=`[[*content]]` &output=`toc` &tpl_outer=`@INLINE <h2>My table of Contents</h2><ol class="toc level-[[+level]]">[[+toc]]</ol>` &tpl_inner=`@INLINE <li class="my-toc"><a href="[[+anchor]]">[[+title]] &gt;</a></li>` &maxlevel=`3` &minlevel=`1` ]]
 * 
 * Options:
 * &output - What should the snippet output? Either 'toc' or 'content' (default: 'toc')
 * &tpl_outer - Customise the outer template of the TOC
 * &tpl_inner - Customise the inner template of the TOC
 * &minlevel - Minimum header level to include in the TOC (default: h1)
 * &maxlevel - Maximum header level to include in the TOC (default: h4)
 * 
 */

// Parameters/options
// What should the snippet output? Either 'toc' or 'content'
if (isset($options) && ($options == 'content' || $options == 'toc')) {
    $output = $options;
} else {
    $output = isset($output) ? $output : 'toc';
}
$minlevel = isset($minlevel) ? $minlevel : 1; // Maximum of <h1>
$maxlevel = isset($maxlevel) ? $maxlevel : 4; // Maximum of <h4>
$tpl_outer = isset($tpl_outer) ? $tpl_outer : '@INLINE <ol class="toc level-[[+level]]">[[+toc]]</ol>';
$tpl_inner = isset($tpl_inner) ? $tpl_inner : '@INLINE <li><a href="[[+anchor]]">[[+title]]</a></li>';

if (!function_exists('parseStringChunk')) {
    function parseStringChunk($string, $placeholders = array()) {
        global $modx;
        if (isset($string) && strpos($string, "@INLINE ") === 0) {
            $string = str_replace("@INLINE ", "", $string);
            $chunk = $modx->newObject('modChunk');
            $chunk->setContent($string);
            return $chunk->process($placeholders);
        } else {
            return $modx->getChunk($string, $placeholders);
        }
    }
}

if (!function_exists('url_slug')) {
    function url_slug($str) {
        // Ensure UTF-8
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        // Replace non letters/numbers with hyphen
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $str);

        // Remove duplicate hyphens
        $str = preg_replace('/-+/', '-', $str);

        // Trim hyphens from ends
        $str = trim($str, '-');

        // Lowercase
        return mb_strtolower($str, 'UTF-8');
    }
}

// Content should be MODX input
$content = $input;
$anchors = [];
$used = [];

// Prepare the content and extract headers ensuring if there are duplicate headers, they get unique anchors
$content = preg_replace_callback(
    '/<h([1-6])([^>]*)>(.*?)<\/h\1>/is',
    function ($match) use ($minlevel, $maxlevel, &$anchors, &$used, $tpl_inner) {
        $i = 1;
        $level = intval($match[1]);
        if ($level < $minlevel || $level > $maxlevel) {
            return $match[0];
        }

        $title = trim(strip_tags($match[3]));
        $anchor = url_slug($title, ['transliterate' => true]);

        while (in_array($anchor, $used)) {
            $anchor .= '-' . $i++;
        }
        $used[] = $anchor;
        $anchors[] = parseStringChunk($tpl_inner, [
            'anchor' => '[[~[[*id]]]]#'.$anchor,
            'title' => $title
        ]);

        return '<h'.$level.' id="'.$anchor.'"'.$match[2].'>'.$match[3].'</h'.$level.'>';
    },
    $content
);

// Finally output the content
if ($output == 'content') {
    echo $content;
} else {
    echo parseStringChunk($tpl_outer, [
        'toc' => implode(PHP_EOL, $anchors),
        'level' => '0'
    ]);
}