<?php

/**
 * @file
 * Contains text_tile.page.inc.
 *
 * Page callback for Text tile entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Url;

/**
 * Prepares variables for Text tile templates.
 *
 * Default template: text_tile.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_text_tile(array &$variables) {
  // Fetch TextTile Entity Object.
  $text_tile = $variables['elements']['#text_tile'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

  $variables['tile_link'] = FALSE;
  if ($text_tile->hasField('field_text_tile_link')) {
    $link = $text_tile->get('field_text_tile_link')->getValue();
    if (is_array($link) && !empty($link)) {
      $variables['tile_link'] = Url::fromUri($link[0]['uri'])->toString();
    }
  }
}
