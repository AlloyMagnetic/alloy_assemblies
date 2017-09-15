<?php

namespace Drupal\alloy_assemblies\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\alloy_assemblies\Entity\TileInterface;

/**
 * Class TileController.
 *
 *  Returns responses for Tile routes.
 */
class TileController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Tile  revision.
   *
   * @param int $tile_revision
   *   The Tile  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($tile_revision) {
    $tile = $this->entityManager()->getStorage('tile')->loadRevision($tile_revision);
    $view_builder = $this->entityManager()->getViewBuilder('tile');

    return $view_builder->view($tile);
  }

  /**
   * Page title callback for a Tile  revision.
   *
   * @param int $tile_revision
   *   The Tile  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($tile_revision) {
    $tile = $this->entityManager()->getStorage('tile')->loadRevision($tile_revision);
    return $this->t('Revision of %title from %date', ['%title' => $tile->label(), '%date' => format_date($tile->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Tile .
   *
   * @param \Drupal\alloy_assemblies\Entity\TileInterface $tile
   *   A Tile  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(TileInterface $tile) {
    $account = $this->currentUser();
    $langcode = $tile->language()->getId();
    $langname = $tile->language()->getName();
    $languages = $tile->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $tile_storage = $this->entityManager()->getStorage('tile');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $tile->label()]) : $this->t('Revisions for %title', ['%title' => $tile->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all tile revisions") || $account->hasPermission('administer tile entities')));
    $delete_permission = (($account->hasPermission("delete all tile revisions") || $account->hasPermission('administer tile entities')));

    $rows = [];

    $vids = $tile_storage->revisionIds($tile);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\alloy_assemblies\TileInterface $revision */
      $revision = $tile_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $tile->getRevisionId()) {
          $link = $this->l($date, new Url('entity.tile.revision', ['tile' => $tile->id(), 'tile_revision' => $vid]));
        }
        else {
          $link = $tile->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.tile.translation_revert', ['tile' => $tile->id(), 'tile_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.tile.revision_revert', ['tile' => $tile->id(), 'tile_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.tile.revision_delete', ['tile' => $tile->id(), 'tile_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['tile_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
