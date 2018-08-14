<?php

namespace Drupal\alloy_assemblies\Plugin\AssemblyBuild;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\assembly\Plugin\AssemblyBuildBase;

/**
 * @AssemblyBuild(
 *   id = "PhotoGalleryAssemblyBuild",
 *   label = @Translation("Photo Gallery assembly build"),
 *   types = {
 *     "photo_gallery",
 *   },
 * )
 */
class PhotoGalleryAssemblyBuild extends AssemblyBuildBase {
  function build(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    $build['field_images_nav'] = $build['field_images'];
    foreach ($build['field_images_nav'] as $key => $value) {
      if (is_array($value) && isset($value['#theme']) && $value['#theme'] == 'image_formatter') {
        $build['field_images_nav'][$key]['#image_style'] = 'photo_gallery_thumbnail';
      }
    }
  }
}
