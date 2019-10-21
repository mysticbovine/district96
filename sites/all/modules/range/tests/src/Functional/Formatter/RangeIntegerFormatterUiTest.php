<?php

namespace Drupal\Tests\range\Functional\Formatter;

/**
 * Tests the integer field formatter UI.
 *
 * @group range
 */
class RangeIntegerFormatterUiTest extends RangeFormatterUiTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->fieldType = 'range_integer';

    parent::setUp();
  }

  /**
   * Tests default formatter.
   */
  public function testIntegerFormatter() {
    $this->drupalGet('entity_test/structure/entity_test/display');

    // Enable Default formatter.
    $edit = [
      "fields[$this->fieldName][parent]" => 'content',
      "fields[$this->fieldName][region]" => 'content',
      "fields[$this->fieldName][type]" => 'range_integer',
    ];
    $this->submitForm($edit, 'Save');

    // Ensure that summary is correct.
    $this->assertSession()->pageTextContains('1234-4321');
    $this->assertSession()->pageTextContains('Equivalent values will be combined into a single value.');
    $this->assertSession()->pageTextNotContains('Display with FROM value prefix and suffix.');
    $this->assertSession()->pageTextNotContains('Display with TO value prefix and suffix.');

    // Go to formatter settings form.
    $this->submitForm([], $this->fieldName . '_settings_edit');

    // Ensure that form displays correct set of fields.
    $this->assertSession()->fieldValueEquals('Range separator', '-');
    $this->assertSession()->fieldValueEquals('Thousand marker', '');
    $this->assertSession()->optionExists('Thousand marker', '- None -');
    $this->assertSession()->optionExists('Thousand marker', 'Decimal point');
    $this->assertSession()->optionExists('Thousand marker', 'Comma');
    $this->assertSession()->optionExists('Thousand marker', 'Space');
    $this->assertSession()->optionExists('Thousand marker', 'Thin space');
    $this->assertSession()->optionExists('Thousand marker', 'Apostrophe');
    $this->assertSession()->fieldNotExists('Decimal marker');
    $this->assertSession()->fieldNotExists('Scale');
    $this->assertSession()->checkboxChecked('Combine equivalent values');
    $this->assertSession()->checkboxNotChecked('Display FROM value prefix and suffix');
    $this->assertSession()->checkboxNotChecked('Display TO value prefix and suffix');

    // Update formatter settings.
    $edit = [
      "fields[$this->fieldName][settings_edit_form][settings][range_separator]" => '|',
      "fields[$this->fieldName][settings_edit_form][settings][thousand_separator]" => ' ',
      "fields[$this->fieldName][settings_edit_form][settings][range_combine]" => FALSE,
      "fields[$this->fieldName][settings_edit_form][settings][from_prefix_suffix]" => TRUE,
      "fields[$this->fieldName][settings_edit_form][settings][to_prefix_suffix]" => FALSE,
    ];
    $this->submitForm($edit, 'Update');

    // Ensure that summary is correct.
    $this->assertSession()->pageTextContains('1 234|4 321');
    $this->assertSession()->pageTextNotContains('Equivalent values will be combined into a single value.');
    $this->assertSession()->pageTextContains('Display with FROM value prefix and suffix.');
    $this->assertSession()->pageTextNotContains('Display with TO value prefix and suffix.');

    // Go to formatter settings form.
    $this->submitForm([], $this->fieldName . '_settings_edit');

    // Ensure that form displays correct set of fields.
    $this->assertSession()->fieldValueEquals('Range separator', '|');
    $this->assertSession()->fieldValueEquals('Thousand marker', ' ');
    $this->assertSession()->checkboxNotChecked('Combine equivalent values');
    $this->assertSession()->checkboxChecked('Display FROM value prefix and suffix');
    $this->assertSession()->checkboxNotChecked('Display TO value prefix and suffix');

    // Update formatter settings.
    $edit = [
      "fields[$this->fieldName][settings_edit_form][settings][from_prefix_suffix]" => FALSE,
      "fields[$this->fieldName][settings_edit_form][settings][to_prefix_suffix]" => TRUE,
    ];
    $this->submitForm($edit, 'Update');

    // Ensure that summary is correct.
    $this->assertSession()->pageTextNotContains('Display with FROM value prefix and suffix.');
    $this->assertSession()->pageTextContains('Display with TO value prefix and suffix.');

    // Go to formatter settings form.
    $this->submitForm([], $this->fieldName . '_settings_edit');

    // Ensure that form displays correct set of fields.
    $this->assertSession()->checkboxNotChecked('Display FROM value prefix and suffix');
    $this->assertSession()->checkboxChecked('Display TO value prefix and suffix');
  }

}
