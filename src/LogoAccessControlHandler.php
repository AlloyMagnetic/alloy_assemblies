<?php

namespace Drupal\alloy_assemblies;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Logo entity.
 *
 * @see \Drupal\alloy_assemblies\Entity\Logo.
 */
class LogoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\alloy_assemblies\Entity\LogoInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished logo entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published logo entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit logo entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete logo entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add logo entities');
  }

}
