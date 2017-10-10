<?php

namespace Drupal\alloy_assemblies\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Logo entities.
 *
 * @ingroup alloy_assemblies
 */
interface LogoInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Logo name.
   *
   * @return string
   *   Name of the Logo.
   */
  public function getName();

  /**
   * Sets the Logo name.
   *
   * @param string $name
   *   The Logo name.
   *
   * @return \Drupal\alloy_assemblies\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setName($name);

  /**
   * Gets the Logo creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Logo.
   */
  public function getCreatedTime();

  /**
   * Sets the Logo creation timestamp.
   *
   * @param int $timestamp
   *   The Logo creation timestamp.
   *
   * @return \Drupal\alloy_assemblies\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Logo published status indicator.
   *
   * Unpublished Logo are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Logo is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Logo.
   *
   * @param bool $published
   *   TRUE to set this Logo to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\alloy_assemblies\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setPublished($published);

  /**
   * Gets the Logo revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Logo revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\alloy_assemblies\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Logo revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Logo revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\alloy_assemblies\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setRevisionUserId($uid);

}
