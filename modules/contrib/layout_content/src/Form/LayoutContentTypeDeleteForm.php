<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

class LayoutContentTypeDeleteForm extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // @todo check if layout type has associated layout entities.
    return parent::buildForm($form, $form_state);
  }

}
