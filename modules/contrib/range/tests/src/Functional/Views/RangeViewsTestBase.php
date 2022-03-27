<?php

namespace Drupal\Tests\range\Functional\Views;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\NodeType;
use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\views\Tests\ViewTestData;

/**
 * Base class for testing range handlers.
 */
abstract class RangeViewsTestBase extends ViewTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['range_test', 'node', 'range'];

  /**
   * Entity type ID we are testing against.
   *
   * @var string
   */
  protected $entityTypeId = 'node';

  /**
   * Bundle we are testing against.
   *
   * @var string
   */
  protected $bundle = 'test_bundle';

  /**
   * Name of the field we are testing against.
   *
   * Note, this is used in the default test view.
   *
   * @var string
   */
  protected $fieldName = 'field_range_integer';

  /**
   * Nodes to test.
   *
   * @var \Drupal\node\NodeInterface[]
   */
  protected $nodes = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE): void {
    parent::setUp();

    // Add a date field to page nodes.
    $node_type = NodeType::create([
      'type' => $this->bundle,
      'name' => $this->bundle,
    ]);
    $node_type->save();
    $fieldStorage = FieldStorageConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => $this->entityTypeId,
      'type' => 'range_integer',
    ]);
    $fieldStorage->save();
    $field = FieldConfig::create([
      'field_storage' => $fieldStorage,
      'bundle' => $this->bundle,
      'required' => TRUE,
    ]);
    $field->save();

    // Views needs to be aware of the new field.
    $this->container->get('views.views_data')->clear();

    // Set column map.
    $this->map = [
      'nid' => 'nid',
    ];

    // Load test views.
    ViewTestData::createTestViews(get_class($this), ['range_test']);
  }

}
