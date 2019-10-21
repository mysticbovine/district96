<?php

/**
 * @file
 * Contains \Drupal\better_messages\Controller\BetterMessagesController.
 */

namespace Drupal\better_messages\Controller;

use Drupal\Core\Controller\ControllerBase;

class BetterMessagesController extends ControllerBase {

    public function __construct() {
	//drupal_set_message("test", 'warning');
    }

    public function betterMessagesView($messages) {
	if (empty($messages)) {
	    return array();
	}
	$settings = $this->config('better_messages.settings')->get();

	$build['myelement'] = array(
	    '#theme' => 'better_messages_default',
	    '#message_list' => $messages
	);
	$build['myelement']['#attached']['library'][] = 'better_messages/better_messages';
	$build['myelement']['#attached']['drupalSettings']['better_messages'] = $settings;


	return $build;
    }

}
