<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface LogoStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Logo revision IDs for a specific Logo.
   *
   * @param \Drupal\alloy_assemblies\Entity\LogoInterface $entity
   *   The Logo entity.
   *
   * @return int[]
   *   Logo revision IDs (in ascending order).
   */
  public function revisionIds(LogoInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Logo author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Logo revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\alloy_assemblies\Entity\LogoInterface $entity
   *   The Logo entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(LogoInterface $entity);

  /**
   * Unsets the language for all Logo with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
