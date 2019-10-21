<?php

namespace Drupal\simple_fb_connect;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\simple_fb_connect\SimpleFbConnectPersistentDataHandler;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Facebook\Facebook;

/**
 * Class SimpleFbConnectFbFactory.
 *
 * Creates an instance of Facebook\Facebook service with app ID and secret from
 * SimpleFbConnect module settings.
 */
class SimpleFbConnectFbFactory {
  protected $configFactory;
  protected $loggerFactory;
  protected $peristentDataHandler;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Used for accessing Drupal configuration.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Used for logging errors.
   * @param \Drupal\simple_fb_connect\SimpleFbConnectPersistentDataHandler $persistent_data_handler
   *   Used for reading data from and writing data to session.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LoggerChannelFactoryInterface $logger_factory, SimpleFbConnectPersistentDataHandler $persistent_data_handler) {
    $this->configFactory         = $config_factory;
    $this->loggerFactory         = $logger_factory;
    $this->persistentDataHandler = $persistent_data_handler;
  }

  /**
   * Returns an instance of Facebook\Facebook service.
   *
   * Reads Facebook App ID and App Secret from SimpleFbConnect module settings
   * and creates an instance of Facebook service with these as parameters.
   *
   * @return \Facebook\Facebook
   *   Facebook service instance.
   */
  public function getFbService() {
    // Check that App ID and secret have been defined in module settings.
    if ($this->validateConfig()) {
      $sdk_config = array(
        'app_id' => $this->getAppId(),
        'app_secret' => $this->getAppSecret(),
        'default_graph_version' => 'v2.6',
        'persistent_data_handler' => $this->persistentDataHandler,
      );
      return new Facebook($sdk_config);
    }

    // Return FALSE if app ID or secret is missing.
    return FALSE;
  }

  /**
   * Returns an instance of SimpleFbConnectPersistentDataHandler service.
   *
   * @return Drupal\simple_fb_connect\SimpleFbConnectPersistentDataHandler
   *   SimpleFbConnectPersistentDataHandler service instance.
   */
  public function getPersistentDataHandler() {
    return $this->persistentDataHandler;
  }

  /**
   * Checks that module is configured.
   *
   * @return bool
   *   True if module is configured
   *   False otherwise
   */
  protected function validateConfig() {
    $app_id = $this->getAppId();
    $app_secret = $this->getAppSecret();

    if (!$app_id || !$app_secret) {
      $this->loggerFactory
        ->get('simple_fb_connect')
        ->error('Define App ID and App Secret on module settings.');
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Returns app_id from module settings.
   *
   * @return string
   *   Application ID defined in module settings.
   */
  protected function getAppId() {
    $app_id = $this->configFactory
      ->get('simple_fb_connect.settings')
      ->get('app_id');
    return $app_id;
  }

  /**
   * Returns app_secret from module settings.
   *
   * @return string
   *   Application secret defined in module settings.
   */
  protected function getAppSecret() {
    $app_secret = $this->configFactory
      ->get('simple_fb_connect.settings')
      ->get('app_secret');
    return $app_secret;
  }

}
