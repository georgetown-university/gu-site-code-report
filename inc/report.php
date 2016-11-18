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
  $context = stream_context_create(array('http' => array( 'timeout' => 1200, )));

  foreach ($xml->url as $url) {
    $html = file_get_html($url->loc, false, $context);

    if ($html) {
      $code = findCode($html);

      if ($code) {
        if (!$printedSiteName) {
          print '<h2>' . $site . '</h2>';
          $printedSiteName = true;
        }

        print '<h3><a href="' . $url->loc . '" target="_blank">' . $url->loc . '</a></h3>';
        print '<table>' . $code . '</table>';
      }
    }
  }
}


/* ---
 * function findCode()
 *   Finds the code for a specific given selector on a given page.
 *   Returns HTML of all instances of that code, if it exists.
 */

function findCode($html) {
  $code = '';

  foreach ($html->find('main iframe, main script, main object, main form') as $element) {
    $code .= '<tr>';
    $code .= '<td>' . $element->tag . '</td>';
    $code .= '<td><code>' . htmlspecialchars($element) . '</code></td>';
    $code .= '</tr>';
  }

  return $code;
}


// Run this thing!
init();

?>
