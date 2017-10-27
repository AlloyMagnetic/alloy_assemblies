<?php

namespace Drupal\alloy_assemblies\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\alloy_assemblies\Entity\TextTileInterface;

/**
 * Class TextTileController.
 *
 *  Returns responses for Text tile routes.
 */
class TextTileController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Text tile  revision.
   *
   * @param int $text_tile_revision
   *   The Text tile  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($text_tile_revision) {
    $text_tile = $this->entityManager()->getStorage('text_tile')->loadRevision($text_tile_revision);
    $view_builder = $this->entityManager()->getViewBuilder('text_tile');

    return $view_builder->view($text_tile);
  }

  /**
   * Page title callback for a Text tile  revision.
   *
   * @param int $text_tile_revision
   *   The Text tile  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($text_tile_revision) {
    $text_tile = $this->entityManager()->getStorage('text_tile')->loadRevision($text_tile_revision);
    return $this->t('Revision of %title from %date', ['%title' => $text_tile->label(), '%date' => format_date($text_tile->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Text tile .
   *
   * @param \Drupal\alloy_assemblies\Entity\TextTileInterface $text_tile
   *   A Text tile  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(TextTileInterface $text_tile) {
    $account = $this->currentUser();
    $langcode = $text_tile->language()->getId();
    $langname = $text_tile->language()->getName();
    $languages = $text_tile->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $text_tile_storage = $this->entityManager()->getStorage('text_tile');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $text_tile->label()]) : $this->t('Revisions for %title', ['%title' => $text_tile->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all text tile revisions") || $account->hasPermission('administer text tile entities')));
    $delete_permission = (($account->hasPermission("delete all text tile revisions") || $account->hasPermission('administer text tile entities')));

    $rows = [];

    $vids = $text_tile_storage->revisionIds($text_tile);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\alloy_assemblies\TextTileInterface $revision */
      $revision = $text_tile_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $text_tile->getRevisionId()) {
          $link = $this->l($date, new Url('entity.text_tile.revision', ['text_tile' => $text_tile->id(), 'text_tile_revision' => $vid]));
        }
        else {
          $link = $text_tile->link($date);
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
              Url::fromRoute('entity.text_tile.translation_revert', ['text_tile' => $text_tile->id(), 'text_tile_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.text_tile.revision_revert', ['text_tile' => $text_tile->id(), 'text_tile_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.text_tile.revision_delete', ['text_tile' => $text_tile->id(), 'text_tile_revision' => $vid]),
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

    $build['text_tile_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
