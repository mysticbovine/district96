<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity;

use Drupal\Core\Entity\RevisionableEntityBundleInterface;

interface LayoutContentTypeInterface extends RevisionableEntityBundleInterface {

  public function getLayout(): array;

  public function setLayout(array $layout): self;

  public function getLayoutRegions(): array;

  public function getIconMap(): array;

}