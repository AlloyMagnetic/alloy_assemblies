<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\alloy_assemblies\Entity\LogoInterface;

/**
 * Defines the storage handler class for Logo entities.
 *
 * This extends the base storage class, adding required special handling for
 * Logo entities.
 *
 * @ingroup alloy_assemblies
 */
class LogoStorage extends SqlContentEntityStorage implements LogoStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(LogoInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {logo_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {logo_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(LogoInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {logo_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('logo_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
