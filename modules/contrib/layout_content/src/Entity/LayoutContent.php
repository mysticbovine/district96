<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity;

use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\layout_content\Entity\LayoutContentInterface;

/**
 * Defines the custom layout entity class.
 *
 * @ContentEntityType(
 *   id = "layout_content",
 *   label = @Translation("Custom layout"),
 *   label_collection = @Translation("Custom layouts"),
 *   label_singular = @Translation("custom layout"),
 *   label_plural = @Translation("custom layouts"),
 *   label_count = @PluralTranslation(
 *     singular = "@count custom layout",
 *     plural = "@count custom layouts",
 *   ),
 *   bundle_label = @Translation("Custom layout type"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *     "view_builder" = "Drupal\layout_content\Entity\ViewBuilder\LayoutContentViewBuilder"
 *   },
 *   admin_permission = "administer layouts",
 *   base_table = "layout_content",
 *   revision_table = "layout_content_revision",
 *   data_table = "layout_content_field_data",
 *   revision_data_table = "layout_content_field_revision",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "type",
 *     "label" = "info",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "published" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log"
 *   },
 *   bundle_entity_type = "layout_content_type",
 *   field_ui_base_route = "entity.layout_content_type.edit_form",
 * )
 */
class LayoutContent extends EditorialContentEntityBase implements LayoutContentInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id']->setLabel(t('Custom layout ID'))
      ->setDescription(t('The custom layout ID.'));

    $fields['uuid']->setDescription(t('The custom layout UUID.'));

    $fields['revision_id']->setDescription(t('The revision ID.'));

    $fields['langcode']->setDescription(t('The custom layout language code.'));

    $fields['type']->setLabel(t('layout type'))
      ->setDescription(t('The layout type.'));

    $fields['revision_log']->setDescription(
      t('The log entry explaining the changes in this revision.')
    );

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the custom layout was last edited.'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE);

    return $fields;
  }

}
