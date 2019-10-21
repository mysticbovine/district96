<?php

namespace Drupal\range\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * An interface for range field types.
 */
interface RangeItemInterface {

  /**
   * Helper function. Returns Schema API column specification.
   *
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return array
   *   Schema API column specification.
   */
  static function getColumnSpecification(FieldStorageDefinitionInterface $field_definition);

}
