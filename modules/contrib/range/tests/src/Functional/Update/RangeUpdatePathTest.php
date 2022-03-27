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
    foreach (EntityViewDisplay::load('node.page.test')->getComponents() as $component) {
      if (!empty($component['type']) && $component['type'] === 'range_unformatted') {
        $this->assertArrayNotHasKey('range_combine', $component['settings']);
      }
    }

    // Run the updates.
    $this->runUpdates();

    // Ensure that 'range_combine' settings is set to FALSE.
    foreach (EntityViewDisplay::load('node.page.test')->getComponents() as $component) {
      if (!empty($component['type']) && $component['type'] === 'range_unformatted') {
        $this->assertFalse($component['settings']['range_combine']);
      }
    }
  }

}
