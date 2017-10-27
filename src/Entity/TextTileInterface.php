<?php

namespace Drupal\alloy_assemblies\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Text tile entities.
 *
 * @ingroup alloy_assemblies
 */
interface TextTileInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Text tile name.
   *
   * @return string
   *   Name of the Text tile.
   */
  public function getName();

  /**
   * Sets the Text tile name.
   *
   * @param string $name
   *   The Text tile name.
   *
   * @return \Drupal\alloy_assemblies\Entity\TextTileInterface
   *   The called Text tile entity.
   */
  public function setName($name);

  /**
   * Gets the Text tile creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Text tile.
   */
  public function getCreatedTime();

  /**
   * Sets the Text tile creation timestamp.
   *
   * @param int $timestamp
   *   The Text tile creation timestamp.
   *
   * @return \Drupal\alloy_assemblies\Entity\TextTileInterface
   *   The called Text tile entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Text tile published status indicator.
   *
   * Unpublished Text tile are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Text tile is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Text tile.
   *
   * @param bool $published
   *   TRUE to set this Text tile to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\alloy_assemblies\Entity\TextTileInterface
   *   The called Text tile entity.
   */
  public function setPublished($published);

  /**
   * Gets the Text tile revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Text tile revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\alloy_assemblies\Entity\TextTileInterface
   *   The called Text tile entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Text tile revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Text tile revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\alloy_assemblies\Entity\TextTileInterface
   *   The called Text tile entity.
   */
  public function setRevisionUserId($uid);

}
