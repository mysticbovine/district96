<?php

namespace Drupal\Tests\range\Kernel\Migrate\d7;

use Drupal\node\Entity\Node;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;

/**
 * Tests Drupal 7 range fields migration.
 *
 * @group range
 */
class MigrateRangeFieldTest extends MigrateDrupal7TestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'node',
    'range',
    'text',
    'system',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->migrateUsers(FALSE);
    $this->migrateContentTypes();
    $this->executeMigrations([
      'd7_field',
      'd7_field_instance',
      'd7_field_instance_widget_settings',
      'd7_view_modes',
      'd7_field_formatter_settings',
      'd7_node',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../../../fixtures/drupal7.php';
  }

  /**
   * Tests migration of D7 range fields to field_storage_config entities.
   *
   * @dataProvider fieldMigrationDataProvider
   */
  public function testFieldMigration($id, $type, $settings) {
    /** @var \Drupal\field\FieldStorageConfigInterface $field */
    $field = FieldStorageConfig::load($id);
    $this->assertInstanceOf(FieldStorageConfigInterface::class, $field);
    $this->assertSame($type, $field->getType());
    $this->assertSame($settings, $field->getSettings());
  }

  /**
   * Data provider for testFieldMigration.
   */
  public function fieldMigrationDataProvider() {
    return [
      'range_decimal' => [
        'node.field_decimal',
        'range_decimal',
        [
          'precision' => 12,
          'scale' => 4,
        ],
      ],
      'range_float' => [
        'node.field_float',
        'range_float',
        [],
      ],
      'range_integer' => [
        'node.field_integer',
        'range_integer',
        [],
      ],
    ];
  }

  /**
   * Tests migration of D7 range instances to field_config entities.
   *
   * @dataProvider fieldInstanceMigrationDataProvider
   */
  public function testFieldInstanceMigration($id, $default_value, $settings) {
    /** @var \Drupal\Core\Field\FieldConfigInterface $field */
    $field = FieldConfig::load($id);
    $this->assertSame($default_value, $field->getDefaultValueLiteral());
    $this->assertSame($settings, $field->getSettings());
  }

  /**
   * Data provider for testFieldInstanceMigration.
   */
  public function fieldInstanceMigrationDataProvider() {
    return [
      'range_decimal' => [
        'node.page.field_decimal',
        [],
        [
          'min' => NULL,
          'max' => NULL,
          'from' => ['prefix' => '', 'suffix' => ''],
          'to' => ['prefix' => '', 'suffix' => ''],
          'field' => ['prefix' => '', 'suffix' => ''],
          'combined' => ['prefix' => '', 'suffix' => ''],
          // FieldConfigBase::getSettings() is merging field settings with
          // field storage settings; so let's add them here.
          // @see \Drupal\Core\Field\FieldConfigBase::getSettings()
          'precision' => 12,
          'scale' => 4,
        ],
      ],
      'range_float' => [
        'node.page.field_float',
        [
          ['from' => 2.5, 'to' => 4.5],
        ],
        [
          'min' => -10.5,
          'max' => 10.5,
          'from' => ['prefix' => '', 'suffix' => ''],
          'to' => ['prefix' => '', 'suffix' => ''],
          'field' => ['prefix' => '', 'suffix' => ''],
          'combined' => ['prefix' => '', 'suffix' => ''],
        ],
      ],
      'range_integer' => [
        'node.page.field_integer',
        [],
        [
          'min' => NULL,
          'max' => NULL,
          'from' => ['prefix' => 'FROM Prefix', 'suffix' => 'FROM Suffix'],
          'to' => ['prefix' => 'TO PREFIX', 'suffix' => 'TO SUFFIX'],
          'field' => ['prefix' => 'FIELD PREFIX', 'suffix' => 'FIELD SUFFIX'],
          'combined' => ['prefix' => 'COMBINED PREFIX', 'suffix' => 'COMBINED SUFFIX'],
        ],
      ],
    ];
  }

  /**
   * Tests migration of D7 range field widget and its settings.
   *
   * @dataProvider fieldWidgetMigrationDataProvider
   */
  public function testFieldWidgetMigration($display_id, $component_id, $type, $settings) {
    $component = EntityFormDisplay::load($display_id)->getComponent($component_id);
    $this->assertSame($type, $component['type']);
    $this->assertSame($settings, $component['settings']);
  }

  /**
   * Data provider for testFieldWidgetMigration.
   */
  public function fieldWidgetMigrationDataProvider() {
    return [
      'range_decimal' => [
        'node.page.default',
        'field_decimal',
        'range',
        [
          'label' => ['from' => 'From', 'to' => 'to'],
          'placeholder' => ['from' => '', 'to' => ''],
        ],
      ],
      'range_float' => [
        'node.page.default',
        'field_float',
        'range',
        [
          'label' => ['from' => 'FROM', 'to' => 'TO'],
          'placeholder' => ['from' => '', 'to' => ''],
        ],
      ],
      'range_integer' => [
        'node.page.default',
        'field_integer',
        'range',
        [
          'label' => ['from' => 'From', 'to' => 'to'],
          'placeholder' => ['from' => '', 'to' => ''],
        ],
      ],
    ];
  }

  /**
   * Tests migration of D7 range field formatters & their settings.
   *
   * @dataProvider fieldFormatterMigrationDataProvider
   */
  public function testFieldFormatterMigration($display_id, $component_id, $type, $settings) {
    $component = EntityViewDisplay::load($display_id)->getComponent($component_id);
    $this->assertIsArray($component);
    $this->assertSame($type, $component['type']);
    $this->assertSame($settings, $component['settings']);
  }

  /**
   * Data provider for testFieldFormatterMigration.
   */
  public function fieldFormatterMigrationDataProvider() {
    return [
      'range_decimal' => [
        'node.page.default',
        'field_decimal',
        'range_decimal',
        [
          'thousand_separator' => '.',
          'decimal_separator' => ', ',
          'scale' => 3,
          'range_separator' => ' - ',
          'range_combine' => TRUE,
          'from_prefix_suffix' => TRUE,
          'to_prefix_suffix' => TRUE,
          'field_prefix_suffix' => TRUE,
          'combined_prefix_suffix' => TRUE,
        ],
      ],
      'range_decimal_sprintf' => [
        'node.page.teaser',
        'field_decimal',
        'range_decimal_sprintf',
        [
          'format_string' => '%.0f',
          'range_separator' => '--',
          'range_combine' => FALSE,
          'from_prefix_suffix' => FALSE,
          'to_prefix_suffix' => FALSE,
          'field_prefix_suffix' => FALSE,
          'combined_prefix_suffix' => FALSE,
        ],
      ],
      'range_integer' => [
        'node.page.default',
        'field_integer',
        'range_integer',
        [
          'range_separator' => '-',
          'range_combine' => TRUE,
          'from_prefix_suffix' => FALSE,
          'to_prefix_suffix' => TRUE,
          'field_prefix_suffix' => FALSE,
          'combined_prefix_suffix' => FALSE,
          'thousand_separator' => ' ',
        ],
      ],
      'range_integer_sprintf' => [
        'node.page.teaser',
        'field_integer',
        'range_integer_sprintf',
        [
          'format_string' => '%x',
          'range_separator' => '|',
          'range_combine' => TRUE,
          'from_prefix_suffix' => FALSE,
          'to_prefix_suffix' => FALSE,
          'field_prefix_suffix' => FALSE,
          'combined_prefix_suffix' => FALSE,
        ],
      ],
      'range_unformatted' => [
        'node.page.default',
        'field_float',
        'range_unformatted',
        [
          'range_separator' => '-',
          'range_combine' => TRUE,
          'from_prefix_suffix' => FALSE,
          'to_prefix_suffix' => FALSE,
          'field_prefix_suffix' => FALSE,
          'combined_prefix_suffix' => TRUE,
        ],
      ],
    ];
  }

  /**
   * Tests migration of D7 range fields data.
   *
   * @dataProvider fieldDataMigrationDataProvider
   */
  public function testFieldDataMigration($field_name, $expected) {
    $node = Node::load(1);
    $this->assertSame($expected, $node->{$field_name}->getValue());
  }

  /**
   * Data provider for testFieldDataMigration.
   */
  public function fieldDataMigrationDataProvider() {
    return [
      'range_decimal' => [
        'field_decimal',
        [
          [
            'from' => '12.0000',
            'to' => '18.0000',
          ],
          [
            'from' => '-44.3300',
            'to' => '66.7700',
          ],
        ],
      ],
      'range_float' => [
        'field_float',
        [
          [
            'from' => '2.5',
            'to' => '4.5',
          ],
        ],
      ],
      'range_integer' => [
        'field_integer',
        [
          [
            'from' => '8',
            'to' => '111111',
          ],
        ],
      ],
    ];
  }

}
