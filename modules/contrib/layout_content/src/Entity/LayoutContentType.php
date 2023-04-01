<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\layout_content\Entity\LayoutContentTypeInterface;

/**
 * Defines the custom layout type entity.
 *
 * @ConfigEntityType(
 *   id = "layout_content_type",
 *   label = @Translation("Custom layout type"),
 *   label_collection = @Translation("Custom layout types"),
 *   label_singular = @Translation("custom layout type"),
 *   label_plural = @Translation("custom layout types"),
 *   label_count = @PluralTranslation(
 *     singular = @Translation("@count custom layout type"),
 *     plural = @Translation("@count custom layout types"),
 *   ),
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\layout_content\Form\LayoutContentTypeForm",
 *       "add" = "Drupal\layout_content\Form\LayoutContentTypeForm",
 *       "edit" = "Drupal\layout_content\Form\LayoutContentTypeForm",
 *       "delete" = "Drupal\layout_content\Form\LayoutContentTypeDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     },
 *     "list_builder" = "Drupal\layout_content\Entity\LayoutContentTypeListBuilder"
 *   },
 *   admin_permission = "administer layouts",
 *   config_prefix = "type",
 *   bundle_of = "layout_content",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/layout-content/types/add",
 *     "delete-form" = "/admin/structure/layout-content/types/{layout_content_type}/delete",
 *     "edit-form" = "/admin/structure/layout-content/types/{layout_content_type}",
 *     "collection" = "/admin/structure/layout-content/types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "revision",
 *     "layout",
 *   }
 * )
 */
class LayoutContentType extends ConfigEntityBundleBase implements LayoutContentTypeInterface {

  /**
   * The custom block type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The custom block type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The default revision setting for custom blocks of this type.
   *
   * @var bool
   */
  protected $revision = TRUE;

  protected $layout = [
    ['first' => 'First'],
  ];

  /**
   * {@inheritdoc}
   */
  public function shouldCreateNewRevision(): bool {
    return (bool) $this->revision;
  }

  public function getLayout(): array {
    return $this->layout;
  }

  public function setLayout(array $layout): LayoutContentTypeInterface {
    $this->layout = $layout;
    return $this;
  }

  public function getLayoutRegions(): array {
    $layout_regions = [];
    foreach ($this->getLayout() as $regions) {
      foreach ($regions as $id => $label) {
        $layout_regions[$id] = ['label' => $label];
      }
    }

    return $layout_regions;
  }

  public function getIconMap(): array {
    $icon_map = [];
    foreach ($this->getLayout() as $regions) {
      $icon_map[] = array_keys($regions);
    }

    return $icon_map;
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void {
    parent::postSave($storage, $update);

    \Drupal::service('plugin.manager.core.layout')->clearCachedDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities): void {
    \Drupal::service('plugin.manager.core.layout')->clearCachedDefinitions();
  }

}
