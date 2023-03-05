<?php

namespace Drupal\node_read_time\Plugin\views\field;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node_read_time\Calculate\ReadingTime;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to node read time.
 *
 * @ViewsField("node_read_time")
 */
class NodeReadTime extends FieldPluginBase {

  /**
   * The config service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configManager;

  /**
   * The config service.
   *
   * @var \Drupal\node_read_time\Calculate\ReadingTime
   */
  protected $readingTime;

  /**
   * Constructs a new Node Read Time object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\node_read_time\Calculate\ReadingTime $reading_time
   *   The node read time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, ReadingTime $reading_time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configManager = $config_factory;
    $this->readingTime = $reading_time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('node_read_time.reading_time')
    );
  }

  /**
   * Leave empty to avoid a query on this field.
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['message_not_enable'] = ['default' => ''];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    $form['message_not_enable'] = [
      '#title' => $this->t('Message to display in case node read time is not enabled'),
      '#description' => $this->t('This text will be displayed in case the node type
         does not have read time enable. Can be empty.'),
      '#type' => 'textfield',
      '#default_value' => $this->options['message_not_enable'],
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Render function for the node_read_time field.
   *
   * Displays the node read time.
   *
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $node = $this->getEntity($values);

    $config = $this->configManager->get('node_read_time.settings');
    $bundles = $config->get('reading_time')['container'];

    if ($bundles) {
      foreach ($bundles as $machine_name => $bundle) {
        if ($bundle['is_activated'] && $machine_name === $node->getType()) {
          // If words per minute is not set, give an average of 225.
          $words_per_minute = $this->configManager->get('node_read_time.settings')->get('reading_time')['words_per_minute'] ?: 225;
          $reading_time = $this->readingTime
            ->setWordsPerMinute($words_per_minute)
            ->collectWords($node)
            ->calculateReadingTime()
            ->getReadingTime();

          // Clear the words variable.
          $this->readingTime->setWords(0);

          return [
            '#theme' => 'reading_time',
            '#reading_time' => $reading_time,
          ];

        }
      }
    }
    return $this->options['message_not_enable'];
  }

}
