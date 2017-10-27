<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\alloy_assemblies\Entity\TextTileInterface;

/**
 * Defines the storage handler class for Text tile entities.
 *
 * This extends the base storage class, adding required special handling for
 * Text tile entities.
 *
 * @ingroup alloy_assemblies
 */
class TextTileStorage extends SqlContentEntityStorage implements TextTileStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TextTileInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {text_tile_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {text_tile_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(TextTileInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {text_tile_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('text_tile_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
