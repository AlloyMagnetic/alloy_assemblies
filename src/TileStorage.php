<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\alloy_assemblies\Entity\TileInterface;

/**
 * Defines the storage handler class for Tile entities.
 *
 * This extends the base storage class, adding required special handling for
 * Tile entities.
 *
 * @ingroup alloy_assemblies
 */
class TileStorage extends SqlContentEntityStorage implements TileStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TileInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {tile_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {tile_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(TileInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {tile_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('tile_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
