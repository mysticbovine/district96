<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Form;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Entity\ContentLanguageSettings;

class LayoutContentTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Provide a label for this layout type to help identify it in the administration pages.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\layout_content\Entity\LayoutContentType::load',
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
    ];

    $form['revision'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create new revision'),
      '#default_value' => $this->entity->shouldCreateNewRevision(),
      '#description' => $this->t('Create a new revision by default for this layout type.'),
    ];

    $form['layout'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Layout'),
      '#default_value' => Yaml::encode($this->entity->getLayout()),
      '#description' => $this->t('Define the layout (rows and columns) for this custom layout type.'),
    ];

    if ($this->moduleHandler->moduleExists('language')) {
      $form['language'] = [
        '#type' => 'details',
        '#title' => $this->t('Language settings'),
        '#group' => 'additional_settings',
      ];

      $language_configuration = ContentLanguageSettings::loadByEntityTypeBundle('layout_content', $this->entity->id());
      $form['language']['language_configuration'] = [
        '#type' => 'language_configuration',
        '#entity_information' => [
          'entity_type' => 'layout_content',
          'bundle' => $this->entity->id(),
        ],
        '#default_value' => $language_configuration,
      ];

      $form['#submit'][] = 'language_configuration_element_submit';
    }

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->set('layout', Yaml::decode($form_state->getValue('layout')));
    $status = $this->entity->save();

    $edit_link = $this->entity->toLink(
      $this->t('Edit'),
      'edit-form'
    )->toString();
    $logger = $this->logger('layout_content');
    if ($status === SAVED_UPDATED) {
      $this->messenger()->addStatus(
        $this->t(
          'Custom layout type %label has been updated.',
          ['%label' => $this->entity->label()]
        )
      );
      $logger->notice(
        'Custom layout type %label has been updated.',
        ['%label' => $this->entity->label(), 'link' => $edit_link]
      );
    }
    else {
      $this->messenger()->addStatus(
        $this->t(
          'Custom layout type %label has been added.',
          ['%label' => $this->entity->label()]
        )
      );
      $logger->notice(
        'Custom layout type %label has been added.',
        ['%label' => $this->entity->label(), 'link' => $edit_link]
      );
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }

}
