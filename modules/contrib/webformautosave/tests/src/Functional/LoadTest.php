<?php

namespace Drupal\Tests\webformautosave\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Simple test to ensure that main page loads with module enabled.
 *
 * @group webformautosave
 */
class LoadTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'dblog',
    'rest',
    'webform',
    'webform_submission_log',
    'webformautosave',
  ];

  /**
   * A user with permission to administer site configuration.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->user = $this->drupalCreateUser(['access administration pages']);
  }

  /**
   * Tests that the home page loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalLogin($this->user);
    $this->drupalGet('admin');
    $this->assertSession()
      ->elementExists('xpath', '//h1[text() = "Administration"]');
  }

}
