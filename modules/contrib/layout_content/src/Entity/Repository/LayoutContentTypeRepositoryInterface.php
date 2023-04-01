<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity\Repository;

use Drupal\layout_content\Entity\LayoutContentTypeInterface;

interface LayoutContentTypeRepositoryInterface {

  public function find(string $id): ?LayoutContentTypeInterface;

  public function findAll(): array;

}
