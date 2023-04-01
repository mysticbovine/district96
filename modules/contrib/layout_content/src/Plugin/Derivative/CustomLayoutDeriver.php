<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\layout_content\Entity\Repository\LayoutContentTypeRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomLayoutDeriver extends DeriverBase implements ContainerDeriverInterface {

  protected $repository;

  public function __construct(LayoutContentTypeRepositoryInterface $repository) {
    $this->repository = $repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('layout_content_type.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives = [];
    foreach ($this->repository->findAll() as $layout) {
      $this->derivatives[$layout->id()] = clone $base_plugin_definition
        ->setLabel($layout->label())
        ->setCategory('Custom')
        ->setRegions($regions = $layout->getLayoutRegions())
        ->setDefaultRegion(reset($regions))
        ->setIconMap($layout->getIconMap());
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
