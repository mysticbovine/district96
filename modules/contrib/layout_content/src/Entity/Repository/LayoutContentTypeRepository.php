<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity\Repository;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\layout_content\Entity\LayoutContentTypeInterface;

class LayoutContentTypeRepository implements LayoutContentTypeRepositoryInterface {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function find(string $id): ?LayoutContentTypeInterface {
    return $this->entityTypeManager
      ->getStorage('layout_content_type')
      ->load($id);
  }

  public function findAll(): array {
    return $this->entityTypeManager
      ->getStorage('layout_content_type')
      ->loadMultiple();
  }

}
