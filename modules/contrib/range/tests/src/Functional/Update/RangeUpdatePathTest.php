<?php

namespace Drupal\Tests\range\Functional\Update;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Tests range module update path.
 *
 * @group Update
 * @group range
 */
class RangeUpdatePathTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Ensure that range fields are marked as installed. This cannot be done
    // easily in the database dump file.
    $definitions = \Drupal::service('entity.last_installed_schema.repository')
      ->getLastInstalledFieldStorageDefinitions('node');

    $definitions['field_decimal'] = FieldStorageConfig::load('node.field_decimal');
    $definitions['field_float'] = FieldStorageConfig::load('node.field_float');
    $definitions['field_integer'] = FieldStorageConfig::load('node.field_integer');

    \Drupal::service('entity.last_installed_schema.repository')
      ->setLastInstalledFieldStorageDefinitions('node', $definitions);
  }

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      DRUPAL_ROOT . '/core/modules/system/tests/fixtures/update/drupal-8.8.0.bare.standard.php.gz',
      __DIR__ . '/../../../fixtures/update/drupal-8.range-8100.php',
    ];
  }

  /**
   * Tests \range_update_8101().
   *
   * Tests that 'range_combine' settings is set to FALSE for the existing
   * range_unformatted formatters.
   *
   * @see \range_update_8101()
   */
  public function testUpdateHook8101() {
    // Ensure that 'range_combine' settings is not available.
    foreach (EntityViewDisplay::load('node.page.unformatted_formatter')->getComponents() as $component) {
      if (!empty($component['type']) && $component['type'] === 'range_unformatted') {
        $this->assertArrayNotHasKey('range_combine', $component['settings']);
      }
    }

    // Run the updates.
    $this->runUpdates();

    // Ensure that 'range_combine' settings is set to FALSE.
    foreach (EntityViewDisplay::load('node.page.unformatted_formatter')->getComponents() as $component) {
      if (!empty($component['type']) && $component['type'] === 'range_unformatted') {
        $this->assertFalse($component['settings']['range_combine']);
      }
    }
  }

  /**
   * Tests that base features are not broken.
   *
   * This test does not check everything. It is more a smoke test. Note that
   * configuration forms are not being submitted so nothing is being changed
   * so each feature can be tested with as little interference as possible.
   *
   * For the future it worth to test each feature in the own test method.
   *
   * @link https://www.drupal.org/node/3150297
   */
  public function testNoRegressionsAfterUpdates() {

    // Run the updates.
    $this->runUpdates();

    $this->drupalLogin($this->drupalCreateUser([
        'administer node display',
        'administer node form display',
        'create page content',
    ]));

    // Ensure no regressions while editing content.
    $range_fields = [
      'field_decimal',
      'field_float',
      'field_integer',
    ];

    $this->drupalGet('node/add/page');
    foreach ($range_fields as $field_name) {
      $edit = [
        $field_name . '[0][from]' => 0,
        $field_name . '[0][to]' => 5,
      ];
      $this->submitForm($edit, 'Save');
    }

    // Ensure no regressions while editing range fields configuration.
    foreach ($range_fields as $field_name) {
      $this->drupalGet('admin/structure/types/manage/page/fields');
      $this->drupalGet('admin/structure/types/manage/page/fields/node.page.' . $field_name);
      $this->drupalGet('admin/structure/types/manage/page/fields/node.page.' . $field_name);
      $this->drupalGet('admin/structure/types/manage/page/fields/node.page.' . $field_name . '/storage');
    }

    // Ensure no regressions while editing range fields display.
    $view_modes = [
      'default_formatter',
      'unformatted_formatter',
    ];
    foreach ($view_modes as $view_mode) {
      $this->drupalGet('admin/structure/types/manage/page/display/' . $view_mode);
      foreach ($range_fields as $field_name) {
        $this->submitForm([], $field_name . '_settings_edit');
        $this->submitForm([], 'Cancel');
      }
    }

    // Ensure no regressions while editing range fields form display.
    $form_modes = ['default'];
    foreach ($form_modes as $form_mode) {
      $this->drupalGet('admin/structure/types/manage/page/form-display/' . $form_mode);
      foreach ($range_fields as $field_name) {
        $this->submitForm([], $field_name . '_settings_edit');
        $this->submitForm([], 'Cancel');
      }
    }
  }

}
