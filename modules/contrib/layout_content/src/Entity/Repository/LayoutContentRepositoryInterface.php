<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity\Repository;

interface LayoutContentRepositoryInterface {

  public function find(int $id): ?LayoutContentInterface;

  public function findByRevision(int $revision_id): ?LayoutContentInterface;

  public function findByType(string $type): array;

  public function findAll(): array;

}
