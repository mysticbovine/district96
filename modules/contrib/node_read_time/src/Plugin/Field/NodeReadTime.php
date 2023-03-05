<?php

namespace Drupal\node_read_time\Plugin\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Calculate the reading time for the entity.
 *
 * @package Drupal\node_read_time\Plugin\Field
 */
class NodeReadTime extends FieldItemList {
  use ComputedItemListTrait;

  /**
   * Computes the field value.
   */
  protected function computeValue() {
    $entity = $this->getEntity();

    $config = \Drupal::config('node_read_time.settings')
      ->get('reading_time')['container'];

    $reading_time = NULL;

    if ($config[$entity->getType()]['is_activated']) {
      // If words per minute is not set, give an average of 225.
      $words_per_minute = \Drupal::config('node_read_time.settings')->get('reading_time')['words_per_minute'] ?: 225;
      $reading_time_service = \Drupal::service('node_read_time.reading_time');
      $reading_time = $reading_time_service
        ->setWordsPerMinute($words_per_minute)
        ->collectWords($entity)
        ->calculateReadingTime()
        ->getReadingTime();

      // Clear the words variable.
      $reading_time_service->setWords(0);
    }
    $this->list[0] = $this->createItem(0, $reading_time);
  }

}
