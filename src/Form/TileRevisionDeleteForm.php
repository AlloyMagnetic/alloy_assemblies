<?php

namespace Drupal\alloy_assemblies\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Tile revision.
 *
 * @ingroup alloy_assemblies
 */
class TileRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Tile revision.
   *
   * @var \Drupal\alloy_assemblies\Entity\TileInterface
   */
  protected $revision;

  /**
   * The Tile storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $TileStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new TileRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->TileStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('tile'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tile_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => format_date($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.tile.version_history', ['tile' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $tile_revision = NULL) {
    $this->revision = $this->TileStorage->loadRevision($tile_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->TileStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Tile: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    \Drupal::messenger()->addStatus(t('Revision from %revision-date of Tile %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.tile.canonical',
       ['tile' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {tile_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.tile.version_history',
         ['tile' => $this->revision->id()]
      );
    }
  }

}
