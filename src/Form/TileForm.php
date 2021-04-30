<?php

namespace Drupal\alloy_assemblies\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Tile edit forms.
 *
 * @ingroup alloy_assemblies
 */
class TileForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\alloy_assemblies\Entity\Tile */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 10,
      ];
    }

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime(REQUEST_TIME);
      $entity->setRevisionUserId(\Drupal::currentUser()->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addStatus($this->t('Created the %label Tile.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addStatus($this->t('Saved the %label Tile.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.tile.canonical', ['tile' => $entity->id()]);
  }

}
