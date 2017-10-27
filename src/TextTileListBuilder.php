<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Text tile entities.
 *
 * @ingroup alloy_assemblies
 */
class TextTileListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Text tile ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\alloy_assemblies\Entity\TextTile */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.text_tile.edit_form',
      ['text_tile' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
