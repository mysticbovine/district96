<?php

namespace Drupal\bootstrap5;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Theme\ThemeManagerInterface;

/**
 * Bootstrap5 theme settings manager.
 */
class SettingsManager {

  use StringTranslationTrait;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * Constructs a WebformThemeManager object.
   *
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme manager.
   */
  public function __construct(ThemeManagerInterface $theme_manager) {
    $this->themeManager = $theme_manager;
  }

  /**
   * Alters theme settings form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $form_id
   *   The form id.
   *
   * @see hook_form_alter()
   */
  public function themeSettingsAlter(array &$form, FormStateInterface $form_state, $form_id) {
    // Work-around for a core bug affecting admin themes. See issue #943212.
    if (isset($form_id)) {
      return;
    }

    // Integrate with styleguide from twbstools module.
    $description = '';
    if (\Drupal::moduleHandler()
      ->moduleExists('twbstools')) {
      $styleguidePath = '/admin/appearance/styleguide';
      $description = t("Style guide demonstrates abilities of bootstrap framework. Open <a target='_blank' href='@sglink'>style guide</a> in a new window.", [
        '@sglink' => $styleguidePath,
      ]);
    }
    else {
      $styleguidePath = 'https://www.drupal.org/project/twbstools';
      $description = t("Style guide demonstrates abilities of bootstrap framework. Style guide is now part of <a target='_blank' href='@sglink'>bootstrap tools</a> module. Install and enable to the module to enhance content editor and developer workflows.", [
        '@sglink' => $styleguidePath,
      ]);
    }

    $form['styleguide'] = [
      '#type' => 'details',
      '#title' => t('Style guide'),
      '#description' => $description,
      '#open' => TRUE,
    ];

    $options_theme = [
      'none' => 'do not apply theme',
      'light' => 'light (dark text/links against a light background)',
      'dark' => 'dark (light/white text/links against a dark background)',
    ];

    $options_colour = [
      'none' => 'do not apply colour',
      'primary' => 'primary',
      'secondary' => 'secondary',
      'light' => 'light',
      'dark' => 'dark',
    ];

    // Populating options for top container.
    $options_top_container = [
      'container' => 'fixed',
      'container-fluid m-0 p-0' => 'fluid',
    ];

    // Populating extra options for top container.
    foreach (explode("\n", theme_get_setting('b5_top_container_config') ?? '') as $line) {
      $values = explode("|", trim($line) ?? '');
      if (is_array($values) && (count($values) == 2)) {
        $options_top_container += [trim($values[0]) => trim($values[1])];
      }
    }

    $form['body_details'] = [
      '#type' => 'details',
      '#title' => t('Body options'),
      '#description' => t('Combination of theme/background colour may affect background colour/text colour contrast. To fix any contrast issues, override corresponding variables in scss file: <code>[bootstrap 5 theme]/dist/boostrap/[version]/scss/_variables.scss</code>'),
      '#open' => TRUE,
    ];

    $form['body_details']['b5_top_container'] = [
      '#type' => 'select',
      '#title' => t('Website container type'),
      '#default_value' => theme_get_setting('b5_top_container'),
      '#description' => t("Type of top level container: fluid (eg edge to edge) or fixed width"),
      '#options' => $options_top_container,
    ];

    $form['body_details']['b5_top_container_config'] = [
      '#type' => 'textarea',
      '#title' => t('Website container type configuration'),
      '#default_value' => theme_get_setting('b5_top_container_config'),
      '#description' => t("Format: <classes|label> on each line, e.g. <br><pre>container|fixed<br />container-fluid m-0 p-0|fluid</pre>"),
    ];

    $form['body_details']['b5_body_schema'] = [
      '#type' => 'select',
      '#title' => t('Body theme:'),
      '#default_value' => theme_get_setting('b5_body_schema'),
      '#description' => t("Text colour theme of the body."),
      '#options' => $options_theme,
    ];

    $form['body_details']['b5_body_bg_schema'] = [
      '#type' => 'select',
      '#title' => t('Body background:'),
      '#default_value' => theme_get_setting('b5_body_bg_schema'),
      '#description' => t("Background color of the body."),
      '#options' => $options_colour,
    ];

    $form['nav_details'] = [
      '#type' => 'details',
      '#title' => t('Navbar options'),
      '#description' => t("Combination of theme/background colour may affect background colour/text colour contrast. To fix any contrast issues, override \$navbar-light-*/\$navbar-dark-* variables (refer to dist/boostrap/scss/_variables.scss)"),
      '#open' => TRUE,
    ];

    $form['nav_details']['b5_navbar_schema'] = [
      '#type' => 'select',
      '#title' => t('Navbar theme:'),
      '#default_value' => theme_get_setting('b5_navbar_schema'),
      '#description' => t("Text colour theme of the navbar."),
      '#options' => $options_theme,
    ];

    $form['nav_details']['b5_navbar_bg_schema'] = [
      '#type' => 'select',
      '#title' => t('Navbar background:'),
      '#default_value' => theme_get_setting('b5_navbar_bg_schema'),
      '#description' => t("Background color of the navbar."),
      '#options' => $options_colour,
    ];

    $form['footer_details'] = [
      '#type' => 'details',
      '#title' => t('Footer options'),
      '#description' => t("Combination of theme/background colour may affect background colour/text colour contrast. To fix any contrast issues, override corresponding variables in scss (refer to dist/boostrap/scss/_variables.scss)"),
      '#open' => TRUE,
    ];

    $form['footer_details']['b5_footer_schema'] = [
      '#type' => 'select',
      '#title' => t('Footer theme:'),
      '#default_value' => theme_get_setting('b5_footer_schema'),
      '#description' => t("Text colour theme of the footer."),
      '#options' => $options_theme,
    ];

    $form['footer_details']['b5_footer_bg_schema'] = [
      '#type' => 'select',
      '#title' => t('Footer background:'),
      '#default_value' => theme_get_setting('b5_footer_bg_schema'),
      '#description' => t("Background color of the footer."),
      '#options' => $options_colour,
    ];

    $form['text_formats'] = [
      '#type' => 'details',
      '#title' => t('Text formats and CKEditor'),
      '#description' => t("Configuration for text formats and editors."),
      '#open' => TRUE,
    ];

    /*$form['text_formats']['b5_ckeditor_enable'] = [
      '#type' => 'checkbox',
      '#title' => t('CKEditor with bootstrap support'),
      '#default_value' => theme_get_setting('b5_ckeditor_enable'),
      '#description' => t("Enable bootstrap support inside bootstrap editor. If enabled, containers, cards and other styles will be rendered inside CKEditor."),
    ];*/

    $form['subtheme'] = [
      '#type' => 'details',
      '#title' => t('Subtheme'),
      '#description' => t("Create subtheme."),
      '#open' => FALSE,
    ];

    $form['subtheme']['subtheme_folder'] = [
      '#type' => 'textfield',
      '#title' => t('Subtheme location'),
      '#default_value' => 'themes/custom',
      '#description' => t("Relative path to the webroot <em>%root</em>. No trailing slash.", [
        '%root' => DRUPAL_ROOT,
      ]),
    ];

    $form['subtheme']['subtheme_name'] = [
      '#type' => 'textfield',
      '#title' => t('Subtheme name'),
      '#default_value' => 'B5 subtheme',
      '#description' => t("If name is empty, machine name will be used."),
    ];

    $form['subtheme']['subtheme_machine_name'] = [
      '#type' => 'textfield',
      '#title' => t('Subtheme machine name'),
      '#default_value' => 'b5subtheme',
      '#description' => t("Use lowercase characters, numbers and underscores. Name must start with a letter."),
    ];

    $form['subtheme']['create'] = [
      '#type' => 'submit',
      '#name' => 'subtheme_create',
      '#value' => t('Create'),
      '#button_type' => 'danger',
      '#attributes' => [
        'class' => ['btn btn-danger'],
      ],
      '#submit' => ['bootstrap5_form_system_theme_settings_subtheme_submit'],
      '#validate' => ['bootstrap5_form_system_theme_settings_subtheme_validate'],
    ];
  }

}
