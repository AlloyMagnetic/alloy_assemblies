<?php

/**
 * @file
 * Contains tile.page.inc.
 *
 * Page callback for Tile entities.
 */

use Drupal\Core\Render\Element;
use Drupal\crop\Entity\Crop;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Prepares variables for Tile templates.
 *
 * Default template: tile.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_tile(array &$variables) {
  // Fetch Tile Entity Object.
  $tile = $variables['elements']['#tile'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

  $variables['focal_point'] = alloy_assemblies_get_focal_point($tile, 'field_image');

  $variables['tile_link'] = FALSE;
  $link = $tile->get('field_link')->getValue();
  if (is_array($link) && !empty($link)) {
    $variables['tile_link'] = Url::fromUri($link[0]['uri'])->toString();
  }
}
