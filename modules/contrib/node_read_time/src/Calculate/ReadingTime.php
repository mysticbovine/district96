<?php

namespace Drupal\node_read_time\Calculate;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Calculates the reading time of a node.
 */
class ReadingTime {
  use StringTranslationTrait;


  /**
   * Number of words per minute.
   *
   * @var int
   */
  private $wordsPerMinute;

  /**
   * The reading time value.
   *
   * @var int
   */
  private $readingTime;

  /**
   * The words from all the fields.
   *
   * @var string
   */
  private $words;

  /**
   * EntityTypeManager object.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Stores the configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   EntityTypeManager object.
   * @param Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   ConfigFactory object.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactoryInterface $configFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->configFactory = $configFactory;
    $this->config = $configFactory->get('node_read_time.settings');
  }

  /**
   * Sets the number of words.
   *
   * @param int $words
   *   The number of collected words.
   */
  public function setWords($words) {
    $this->words = $words;

  }

  /**
   * Sets words per minute.
   *
   * @param int $wordsPerMinute
   *   Set the number of words per minute.
   *
   * @return \Drupal\node_read_time\Calculate\ReadingTime
   *   Returns this class object.
   */
  public function setWordsPerMinute($wordsPerMinute) {
    $this->wordsPerMinute = $wordsPerMinute;

    return $this;
  }

  /**
   * Gets the reading time value.
   *
   * @return int|null
   *   Returns the reading time value.
   */
  public function getReadingTime() {
    return $this->readingTime;
  }

  /**
   * Sets the reading time value.
   *
   * @@param int
   *   Sets the reading time value.
   */
  public function setReadingTime($readingTime) {
    $this->readingTime = $readingTime;
  }

  /**
   * Gets the fields from allowed types.
   *
   * @param object $entity
   *   The Entity that should be checked for textfields.
   *
   * @return \Drupal\node_read_time\Calculate\ReadingTime
   *   Returns this class object.
   */
  public function collectWords($entity) {

    $entity_fields = !empty($entity) ? $entity->getFieldDefinitions() : NULL;

    $allowedTypes = [
      'text',
      'text_long',
      'text_with_summary',
      'string_long',
      'entity_reference_revisions',
    ];

    if ($entity_fields) {
      // Clean the unnecessary fields.
      foreach ($entity_fields as $k => $field) {
        if (!in_array($field->getType(), $allowedTypes)) {
          unset($entity_fields[$k]);
        }
        // Remove revision fields.
        if (strpos($k, 'revision') !== FALSE) {
          unset($entity_fields[$k]);
        }
      }

      foreach ($entity_fields as $k => $field) {
        if (!empty($entity->get($k)->getValue()[0]['value'])) {
          $this->words .= $entity->get($k)->getValue()[0]['value'];
        }
        elseif ($field->getType() == 'entity_reference_revisions') {

          $fieldStorage = $entity_fields[$k]
            ->get('fieldStorage');
          if ($fieldStorage) {
            $entityType = $fieldStorage->get('settings')['target_type'];
            $list = $entity
              ->get($k)
              ->getValue();

            foreach ($list as $item) {
              // If we are in preview mode, paragraphs are still unknown.
              if (empty($item['target_id'])) {
                continue;
              }

              $referenceRevisionEntity = $this->entityTypeManager
                ->getStorage($entityType)
                ->load($item['target_id']);

              $this->collectWords($referenceRevisionEntity);
            }
          }

        }
      }
    }
    return $this;
  }

  /**
   * Calculate the reading time.
   *
   * @return $this
   */
  public function calculateReadingTime() {
    $unit = $this->config->get('reading_time.unit_of_time');
    $words_count = count(preg_split('/\s+/', (strip_tags((string) $this->words))));
    $reading_time = 0;
    if ($words_count > 1) {
      $minute = floor($words_count / $this->wordsPerMinute);
      $second = floor($words_count % $this->wordsPerMinute / ($this->wordsPerMinute / 60));
      switch ($unit) {
        case 'minute':
          $reading_time = $this->formatPlural(ceil($words_count / $this->wordsPerMinute), '1 minute', '@count minutes');
          break;

        case 'second':
          $reading_time = $this->formatPlural($minute, '1 minute', '@count minutes') . ', ' .
            $this->formatPlural($second, '1 second', '@count seconds');
          break;

        case 'below':
          if ($words_count > 0 && $words_count <= $this->wordsPerMinute) {
            $reading_time = $this->t('1 minute');
          }
          else {
            $reading_time = $this->formatPlural($minute, '1 minute', '@count minutes') . ', ' .
              $this->formatPlural($second, '1 second', '@count seconds');
          }
          break;

        default:
          $reading_time = ceil($words_count / $this->wordsPerMinute);
      }
    }

    $this->readingTime = $reading_time;

    return $this;
  }

}
