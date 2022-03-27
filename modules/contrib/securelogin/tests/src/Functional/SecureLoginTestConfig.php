<?php

namespace Drupal\Tests\securelogin\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Tests secure login module configuration.
 *
 * @group Secure login
 */
class SecureLoginTestConfig extends SecureLoginTestBase {

  use StringTranslationTrait;

  /**
   * Overrides WebTestBase::setUp().
   */
  protected function setUp() {
    parent::setUp();
    // We cannot login to HTTP site if Secure Login is installed.
    if (!$this->isSecure) {
      $this->config('securelogin.settings')->set('base_url', 'https://example.org')->save();
      return;
    }
    $web_user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($web_user);
    $this->drupalGet('admin/config/people/securelogin');
    $fields['base_url'] = 'https://example.org';
    $this->drupalPostForm(NULL, $fields, $this->t('Save configuration'));
  }

  /**
   * Ensure redirects use the configured base URL.
   */
  public function testSecureLoginBaseUrl() {
    // Disable redirect following.
    $this->getSession()->getDriver()->getClient()->followRedirects(FALSE);
    $maximumMetaRefreshCount = $this->maximumMetaRefreshCount;
    $this->maximumMetaRefreshCount = 0;
    $this->drupalGet($this->httpUrl('user/login'));
    $this->assertSession()->statusCodeEquals(301);
    $this->assertIdentical(0, strpos($this->getSession()->getResponseHeader('Location'), 'https://example.org/user/login'), 'Location header uses the configured secure base URL.');
    $this->getSession()->getDriver()->getClient()->followRedirects(TRUE);
    $this->maximumMetaRefreshCount = $maximumMetaRefreshCount;
  }

}
