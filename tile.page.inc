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
  // Get file object
  $image_value = $tile->get('field_image')->getValue();
  if ($image_value) {
    $file_id = $image_value[0]['target_id'];
    $file = File::load($file_id);
    $variables['focal_point'] = FALSE;

    // Get focal point
    $width = $image_value[0]['width'];
    $height = $image_value[0]['height'];
    if ($file) {
      $crop_type = \Drupal::config('focal_point.settings')->get('crop_type');
      $crop = Crop::findCrop($file->getFileUri(), $crop_type);
      if ($crop) {
        $anchor = \Drupal::service('focal_point.manager')
          ->absoluteToRelative($crop->x->value, $crop->y->value, $width, $height);
        $variables['focal_point'] = $anchor;
      }
    }
  }

  $variables['tile_link'] = FALSE;
  $link = $tile->get('field_link')->getValue();
  if (is_array($link) && !empty($link)) {
    $variables['tile_link'] = Url::fromUri($link[0]['uri'])->toString();
  }
}
