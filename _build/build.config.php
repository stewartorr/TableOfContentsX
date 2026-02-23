<?php
/**
 * Define the MODX path constants necessary for installation
 *
 * @package tableofcontentsx
 * @subpackage build
 */

/* define version */
define('PKG_NAME','TableOfContentsX');
define('PKG_NAMESPACE', 'tableofcontentsx');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));
define('PKG_VERSION','1.3');
define('PKG_RELEASE','pl');

$root = dirname(dirname(__FILE__)).'/';
$sources = array (
    'root' => $root,
    'build' => $root .'_build/',
    'packages' => $root . '_packages/',
    'events' => $root . '_build/events/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'data' => $root . '_build/data/',
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
    'source_assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'plugins' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/plugins/',
    'snippets' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
);
