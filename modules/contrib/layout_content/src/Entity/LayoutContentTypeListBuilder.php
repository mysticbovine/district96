<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class LayoutContentTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header = [];
    $header['label'] = $this->t('Layout type');
    $header['id'] = $this->t('ID');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->toLink(NULL, 'edit-form')->toString();
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function getTitle(): FormattableMarkup {
    return $this->t('Custom layout types');
  }

}
