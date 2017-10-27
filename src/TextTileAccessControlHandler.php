<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Text tile entity.
 *
 * @see \Drupal\alloy_assemblies\Entity\TextTile.
 */
class TextTileAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\alloy_assemblies\Entity\TextTileInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished text tile entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published text tile entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit text tile entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete text tile entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add text tile entities');
  }

}
