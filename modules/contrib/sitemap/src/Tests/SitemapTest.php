<?php

namespace Drupal\sitemap\Tests;

/**
 * Tests the routes provided by the sitemap module.
 *
 * @group sitemap
 */
class SitemapTest extends SitemapTestBase {

  /**
   * Test user access and page locations.
   */
  public function testSitemap() {

    // Find the Sitemap page at /sitemap.
    $this->drupalLogin($this->userView);
    $this->drupalGet('/sitemap');
    $this->assertResponse('200');

    // Unauthorized users cannot view the sitemap.
    $this->drupalLogin($this->userNoAccess);
    $this->drupalGet('/sitemap');
    $this->assertResponse('403');

  }

  /**
   * Test user access and page locations for administrators.
   */
  public function testSitemapAdmin() {

    // Find the Sitemap settings page.
    $this->drupalLogin($this->userAdmin);
    $this->drupalGet('/admin/config/search/sitemap');
    $this->assertResponse('200');

    // Unauthorized users cannot view the sitemap.
    $this->drupalLogin($this->userView);
    $this->drupalGet('/admin/config/search/sitemap');
    $this->assertResponse('403');

  }

  // @todo Test multiple plugin types.
  // @todo Test weighting.
}
