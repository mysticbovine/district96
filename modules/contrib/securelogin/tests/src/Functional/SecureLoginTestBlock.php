<?php

namespace Drupal\Tests\securelogin\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Tests Secure login with user login block enabled.
 *
 * @group Secure login
 */
class SecureLoginTestBlock extends SecureLoginTestBase {

  use StringTranslationTrait;

  /**
   * Boolean true if Secure Login module should be installed.
   *
   * @var bool
   */
  protected $enableSecureLogin = FALSE;

  /**
   * Use a profile that disables the cache modules.
   *
   * @var string
   */
  protected $profile = 'testing_config_import';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block', 'node', 'views'];

  /**
   * A user with the 'administer blocks' permission.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser(['administer blocks', 'administer site configuration']);

    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('user_login_block');
    $this->drupalLogout();

    $this->container->get('module_installer')->install(['securelogin']);
  }

  /**
   * Tests pages with user login block.
   */
  public function testUserLoginBlock() {
    global $base_url;
    global $base_path;

    // Disable redirect following.
    $maximumMetaRefreshCount = $this->maximumMetaRefreshCount;
    $this->maximumMetaRefreshCount = 0;
    $this->getSession()->getDriver()->getClient()->followRedirects(FALSE);

    $this->drupalGet($this->httpUrl('node'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/node'), 'Location header uses the secure base URL.');

    // Fetch the same URL again as it may be cached.
    $this->drupalGet($this->httpUrl('node'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/node'), 'Location header uses the secure base URL.');

    $this->drupalGet($this->httpUrl('admin'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/admin'), 'Location header uses the secure base URL.');

    $this->drupalGet($this->httpUrl('admin/config'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/admin/config'), 'Location header uses the secure base URL.');

    $this->drupalGet($this->httpUrl('no-page-by-this-name'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/no-page-by-this-name'), 'Location header uses the secure base URL.');

    $this->drupalGet($this->httpUrl('nor-this-one'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), str_replace('http://', 'https://', $base_url) . '/index.php/nor-this-one'), 'Location header uses the secure base URL.');

    $this->assertTrue($this->config('securelogin.settings')->get('secure_forms'), 'Secure forms settings is enabled by default.');

    // Disable secure forms.
    if ($this->isSecure) {
      $this->maximumMetaRefreshCount = $maximumMetaRefreshCount;
      $this->getSession()->getDriver()->getClient()->followRedirects(TRUE);
      $this->drupalLogin($this->adminUser);
      $edit['secure_forms'] = FALSE;
      $this->drupalPostForm('admin/config/people/securelogin', $edit, $this->t('Save configuration'));
      // Reset config after modifying it.
      $this->container->get('config.factory')->reset('securelogin.settings');
      $this->assertFalse($this->config('securelogin.settings')->get('secure_forms'), 'Secure forms is disabled.');
      $this->drupalGet('user/logout');
      $this->maximumMetaRefreshCount = 0;
      $this->getSession()->getDriver()->getClient()->followRedirects(FALSE);
    }
    else {
      // Refresh schema after installing module.
      $this->container->get('config.typed')->clearCachedDefinitions();
      $this->config('securelogin.settings')->set('secure_forms', FALSE)->save();
      drupal_flush_all_caches();
    }

    $this->drupalGet($this->httpUrl('node'));
    $this->assertSession()->statusCodeEquals(200);
    $element = $this->assertSession()->elementAttributeExists('css', 'form', 'action');
    $this->assertIdentical($element->getAttribute('action'), str_replace('http://', 'https://', $base_url) . "/index.php/node?destination={$base_path}index.php/node", 'The action attribute uses the secure base URL.');

    $this->drupalGet($this->httpUrl('admin'));
    $this->assertSession()->statusCodeEquals(403);
    $element = $this->assertSession()->elementAttributeExists('css', 'form', 'action');
    $this->assertIdentical($element->getAttribute('action'), str_replace('http://', 'https://', $base_url) . "/index.php/system/403?destination={$base_path}index.php/admin", 'The action attribute uses the secure base URL.');

    $this->drupalGet($this->httpUrl('admin/config'));
    $this->assertSession()->statusCodeEquals(403);
    $element = $this->assertSession()->elementAttributeExists('css', 'form', 'action');
    $this->assertIdentical($element->getAttribute('action'), str_replace('http://', 'https://', $base_url) . "/index.php/system/403?destination={$base_path}index.php/admin/config", 'The action attribute uses the secure base URL.');

    $this->drupalGet($this->httpUrl('no-page-by-this-name'));
    $this->assertSession()->statusCodeEquals(404);
    $element = $this->assertSession()->elementAttributeExists('css', 'form', 'action');
    $this->assertIdentical($element->getAttribute('action'), str_replace('http://', 'https://', $base_url) . "/index.php/system/404?destination={$base_path}index.php/", 'The action attribute uses the secure base URL.');

    $this->drupalGet($this->httpUrl('nor-this-one'));
    $this->assertSession()->statusCodeEquals(404);
    $element = $this->assertSession()->elementAttributeExists('css', 'form', 'action');
    $this->assertIdentical($element->getAttribute('action'), str_replace('http://', 'https://', $base_url) . "/index.php/system/404?destination={$base_path}index.php/", 'The action attribute uses the secure base URL.');

    $this->getSession()->getDriver()->getClient()->followRedirects(TRUE);
    $this->maximumMetaRefreshCount = $maximumMetaRefreshCount;
  }

}
