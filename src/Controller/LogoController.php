<?php

namespace Drupal\alloy_assemblies\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\alloy_assemblies\Entity\LogoInterface;

/**
 * Class LogoController.
 *
 *  Returns responses for Logo routes.
 */
class LogoController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Logo  revision.
   *
   * @param int $logo_revision
   *   The Logo  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($logo_revision) {
    $logo = $this->entityManager()->getStorage('logo')->loadRevision($logo_revision);
    $view_builder = $this->entityManager()->getViewBuilder('logo');

    return $view_builder->view($logo);
  }

  /**
   * Page title callback for a Logo  revision.
   *
   * @param int $logo_revision
   *   The Logo  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($logo_revision) {
    $logo = $this->entityManager()->getStorage('logo')->loadRevision($logo_revision);
    return $this->t('Revision of %title from %date', ['%title' => $logo->label(), '%date' => format_date($logo->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Logo .
   *
   * @param \Drupal\alloy_assemblies\Entity\LogoInterface $logo
   *   A Logo  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(LogoInterface $logo) {
    $account = $this->currentUser();
    $langcode = $logo->language()->getId();
    $langname = $logo->language()->getName();
    $languages = $logo->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $logo_storage = $this->entityManager()->getStorage('logo');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $logo->label()]) : $this->t('Revisions for %title', ['%title' => $logo->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all logo revisions") || $account->hasPermission('administer logo entities')));
    $delete_permission = (($account->hasPermission("delete all logo revisions") || $account->hasPermission('administer logo entities')));

    $rows = [];

    $vids = $logo_storage->revisionIds($logo);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\alloy_assemblies\LogoInterface $revision */
      $revision = $logo_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $logo->getRevisionId()) {
          $link = $this->l($date, new Url('entity.logo.revision', ['logo' => $logo->id(), 'logo_revision' => $vid]));
        }
        else {
          $link = $logo->link($date);
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
              Url::fromRoute('entity.logo.translation_revert', ['logo' => $logo->id(), 'logo_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.logo.revision_revert', ['logo' => $logo->id(), 'logo_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.logo.revision_delete', ['logo' => $logo->id(), 'logo_revision' => $vid]),
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

    $build['logo_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
