<?php

/**
 * @file
 * Contains logo.page.inc.
 *
 * Page callback for Logo entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Url;

/**
 * Prepares variables for Logo templates.
 *
 * Default template: logo.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_logo(array &$variables) {
  $logo = $variables['elements']['#logo'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

  $variables['logo_link'] = FALSE;
  if ($logo->hasField('field_logo_link')) {
    $link = $logo->get('field_logo_link')->getValue();
    if (is_array($link) && !empty($link)) {
      $variables['logo_link'] = Url::fromUri($link[0]['uri'])->toString();
    }
  }
}
