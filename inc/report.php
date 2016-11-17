<?php

// For reference: This code uses http://simplehtmldom.sourceforge.net/manual.htm
require('simplehtmldom_1_5/simple_html_dom.php');


function init() {
  $sites = file('inc/sites.txt');
  foreach ($sites as $site) {
    runReport($site);
  }
}


/* ---
 * function getSitemap()
 *   Gets the XML sitemap of a given site.
 *   Returns an object.
 */

function getSitemap($site) {
  $url = 'http://' . trim($site) . '.georgetown.edu/';
  $sitemap = @file_get_contents($url . 'sitemap.xml');
  return simplexml_load_string($sitemap);
}


/* ---
 * function runReport()
 *   Runs the report (finds code) for a given site.
 *   Prints out the report if it exists.
 */

function runReport($site) {
  $xml = getSitemap($site);
  if (!$xml) { return false; }

  $printedSiteName = false;

  foreach ($xml->url as $url) {
    $html = file_get_html($url->loc);

    if ($html) {
      $scripts = findCode($html, 'script', 'Scripts');
      $iframes = findCode($html, 'iframe', 'iFrames');
      $objects = findCode($html, 'object', 'Objects');
      $forms   = findCode($html, 'form', 'Forms');

      if ($scripts || $iframes || $objects || $forms) {
        if (!$printedSiteName) {
          print '<h2>' . $site . '</h2>';
          $printedSiteName = true;
        }

        print '<h3><a href="' . $url->loc . '" target="_blank">' . $url->loc . '</a></h3>';
        print $scripts . $iframes . $objects . $forms;
      }
    }
  }
}


/* ---
 * function findCode()
 *   Finds the code for a specific given selector on a given page.
 *   Returns HTML of all instances of that code, if it exists.
 */

function findCode($html, $tag, $title) {
  $items = '';
  $selector = 'main ' . $tag;

  foreach ($html->find($selector) as $element) {
    $items .= '<li>' . htmlspecialchars($element) . '</li>';
  }

  if ($items) {
    return '<h4>' . $title . '</h4><ol>' . $items . '</ol>';
  } else {
    return '';
  }
}


// Run this thing!
init();

?>
