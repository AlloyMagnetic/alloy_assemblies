<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface TileStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Tile revision IDs for a specific Tile.
   *
   * @param \Drupal\alloy_assemblies\Entity\TileInterface $entity
   *   The Tile entity.
   *
   * @return int[]
   *   Tile revision IDs (in ascending order).
   */
  public function revisionIds(TileInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Tile author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Tile revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\alloy_assemblies\Entity\TileInterface $entity
   *   The Tile entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(TileInterface $entity);

  /**
   * Unsets the language for all Tile with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
