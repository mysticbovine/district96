<?php

declare(strict_types = 1);

namespace Drupal\layout_content;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\LayoutEntityHelperTrait;
use Drupal\layout_content\Plugin\Layout\CustomLayout;

final class LayoutContentEntityOperations {

  use LayoutEntityHelperTrait;

  public function preSave(EntityInterface $entity): void {
    if (!$this->isLayoutCompatibleEntity($entity)) {
      return;
    }

    if ($sections = $this->getEntitySections($entity)) {
      $duplicate = FALSE;
      if ($this->originalEntityUsesDefaultStorage($entity)) {
        // This is a new override from a default and the layouts need to be
        // duplicated.
        $duplicate = TRUE;
      }

      $new_revision = FALSE;
      if ($entity instanceof RevisionableInterface) {
        // If the parent entity will have a new revision create a new revision
        // of the block.
        $new_revision = TRUE;
      }

      foreach ($sections as $section) {
        $layout = $section->getLayout();
        if (!$layout instanceof CustomLayout) {
          continue;
        }

        $layout->save($new_revision, $duplicate);
        $section->setLayoutSettings($layout->getConfiguration());
      }
    }
  }

  public function delete(EntityInterface $entity): void {
    if (!$this->isLayoutCompatibleEntity($entity)) {
      return;
    }

    if ($sections = $this->getEntitySections($entity)) {
      foreach ($sections as $section) {
        $layout = $section->getLayout();
        if (!$layout instanceof CustomLayout) {
          continue;
        }

        // @todo figure out if we need to delete the revision? Deleting by the
        // entity returned from the layout plugin deletes all revisions of that
        // entity.
        // $layout->getEntity()->delete();
      }
    }
  }

}
