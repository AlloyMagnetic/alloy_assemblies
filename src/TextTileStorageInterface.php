<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface TextTileStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Text tile revision IDs for a specific Text tile.
   *
   * @param \Drupal\alloy_assemblies\Entity\TextTileInterface $entity
   *   The Text tile entity.
   *
   * @return int[]
   *   Text tile revision IDs (in ascending order).
   */
  public function revisionIds(TextTileInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Text tile author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Text tile revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\alloy_assemblies\Entity\TextTileInterface $entity
   *   The Text tile entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(TextTileInterface $entity);

  /**
   * Unsets the language for all Text tile with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
