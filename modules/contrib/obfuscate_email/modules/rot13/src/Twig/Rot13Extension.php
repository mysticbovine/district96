<?php

namespace Drupal\rot13\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * A class providing Drupal Twig extensions.
 *
 * Specifically Twig functions, filter and node visitors.
 *
 * @see \Drupal\Core\CoreServiceProvider
 */
class Rot13Extension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return array(
      new TwigFilter('rot13', 'str_rot13'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'rot13_twig_extension';
  }

}
