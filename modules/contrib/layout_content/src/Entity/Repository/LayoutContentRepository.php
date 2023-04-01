<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity\Repository;

class LayoutContentRepository implements LayoutContentRepositoryInterface {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function find(int $id): ?LayoutContentInterface {
    return $this->entityTypeManager
      ->getStorage('layout_content')
      ->load($id);
  }

  public function findByRevision(int $revision_id): ?LayoutContentInterface {
    return $this->entityTypeManager
      ->getStorage('layout_content')
      ->loadRevision($revision_id);
  }

  public function findByType(string $type): array {
    return $this->entityTypeManager
      ->getStorage('layout_content')
      ->loadByProperties(['type' => $type]);
  }

  public function findAll(): array {
    return $this->entityTypeManager
      ->getStorage('layout_content')
      ->loadMultiple();
  }

}
