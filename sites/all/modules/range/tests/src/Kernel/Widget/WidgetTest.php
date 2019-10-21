<?php

namespace Drupal\Tests\range\Kernel\Widget;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\range\Traits\RangeTestTrait;
use Drupal\entity_test\Entity\EntityTest;

/**
 * Tests field widget.
 *
 * @group range
 */
class WidgetTest extends KernelTestBase {

  use RangeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'field',
    'text',
    'entity_test',
    'user',
    'range',
  ];

  /**
   * Field type to test against.
   *
   * @var string
   */
  protected $fieldType;

  /**
   * Field name to test against.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['system']);
    $this->installConfig(['field']);
    $this->installConfig(['text']);
    $this->installConfig(['range']);
    $this->installEntitySchema('entity_test');
    $this->installEntitySchema('user');
  }

  /**
   * Tests widget.
   */
  public function testFieldWidget() {
    foreach ($this->getRangeFieldTypes() as $this->fieldType) {
      // Create field and set form display.
      $this->fieldName = $this->getFieldName($this->fieldType);
      $this->createField($this->fieldType);
      $this->createFormDisplay();
      $this->setFormDisplayComponent($this->fieldType);
      // Test field widget.
      $entity = EntityTest::create([]);
      $this->renderEntityForm($entity);
      $this->assertFieldByXPath($this->constructRangeFieldXpath($this->fieldName . '[0][from]'));
      $this->assertFieldByXPath($this->constructRangeFieldXpath($this->fieldName . '[0][to]'));
      $settings = $this->getFieldSettings($this->fieldType);
      $this->assertText($settings['from']['prefix']);
      $this->assertText($settings['from']['suffix']);
      $this->assertText($settings['to']['prefix']);
      $this->assertText($settings['to']['suffix']);
      // Delete field.
      $this->deleteField();
    }
  }

  /**
   * Helper: Constructs an XPath for the given range field name.
   *
   * @param string $name
   *   Field name.
   *
   * @return string
   *   XPath for specified value.
   */
  protected function constructRangeFieldXpath($name) {
    $settings = $this->getFieldSettings($this->fieldType);

    $xpath = '//input[@name=:name][@type=:type][@min=:min][@max=:max][@step=:step]';
    return $this->buildXPathQuery($xpath, [
      ':name' => $name,
      ':type' => 'number',
      ':min' => $settings['min'],
      ':max' => $settings['max'],
      ':step' => $this->getExpectedStepValue(),
    ]);
  }

  /**
   * Renders a given entity form.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *   The entity object with attached fields to render.
   */
  protected function renderEntityForm(FieldableEntityInterface $entity) {
    $form = \Drupal::service('entity.form_builder')->getForm($entity, 'default');
    $this->render($form);
  }

  /**
   * Returns expected step value for a number form element.
   *
   * @return string
   *   Expected step value.
   */
  private function getExpectedStepValue() {
    switch ($this->fieldType) {
      case 'range_integer':
        return '1';

      case 'range_float':
        return 'any';

      case 'range_decimal':
        return '0.0001';
    }
  }

}
