<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tile entity.
 *
 * @see \Drupal\alloy_assemblies\Entity\Tile.
 */
class TileAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\alloy_assemblies\Entity\TileInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished tile entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published tile entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit tile entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete tile entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add tile entities');
  }

}
