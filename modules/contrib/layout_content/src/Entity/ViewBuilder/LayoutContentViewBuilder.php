<?php

declare(strict_types = 1);

namespace Drupal\layout_content\Entity\ViewBuilder;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Render\Element;

class LayoutContentViewBuilder extends EntityViewBuilder {

  public function buildMultiple(array $build_list): array {
    $build_list = parent::buildMultiple($build_list);

    $children = Element::children($build_list);
    foreach ($children as $key) {
      $display = EntityViewDisplay::collectRenderDisplay(
        $build_list[$key]['#layout_content'],
        $build_list[$key]['#view_mode']
      );
      $layout_regions_component = $display->getComponent('layout_regions');

      $layout_definition = $build_list[$key]['#layout_definition'];
      foreach ($layout_definition->getRegionNames() as $region_name) {
        if (!isset($build_list[$key][$region_name])) {
          continue;
        }

        if ($layout_regions_component) {
          $build_list[$key]['layout_regions'][$region_name] = $build_list[$key][$region_name];

          $region = &$build_list[$key]['layout_regions'][$region_name];
          $region['#type'] = 'container';
          $region['#attributes']['class'][] = 'layout-content-region';
          $region['#attributes']['class'][] = 'layout-content-region--'
            . Html::cleanCssIdentifier($region_name);
        }

        unset($build_list[$key][$region_name]);
      }

      if (!empty($build_list[$key]['layout_regions'])) {
        $build_list[$key]['layout_regions']['#type'] = 'container';
        $build_list[$key]['layout_regions']['#attributes']['class'][] = 'layout-content-regions';
        $build_list[$key]['layout_regions']['#weight'] = $layout_regions_component['weight'];
      }
    }

    return $build_list;
  }

}
