<?php

/**
 * @file
 * Contains \Drupal\stringoverrides\StringOverridesTranslation.
 */

namespace Drupal\stringoverrides;

use Drupal\Core\StringTranslation\Translator\StaticTranslation;

/**
 * Provides string overrides.
 */
class StringOverridesTranslation extends StaticTranslation {

  /**
   * Constructs a StringOverridesTranslation object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function getLanguage($langcode) {
    // This is just a dummy implementation.
    return array(
      '' => array(
        'Home' => 'Home',
        'Content' => 'Content',
        'Structure' => 'Structure',
        'Appearance' => 'Appearance',
        'Extend' => 'Extend',
        'Configuration' => 'Configuration',
        'People' => 'People',
        'Reports' => 'Reports',
        'Help' => 'Help',
		'Log out' => 'Log out',
        'My account' => 'My profile',
      ),
    );
  }

}
