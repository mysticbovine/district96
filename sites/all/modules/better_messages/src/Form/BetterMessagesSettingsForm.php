<?php

namespace Drupal\better_messages\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BetterMessagesSettingsForm extends ConfigFormBase {

    public function getFormId() {
	return 'better_messages_settings_form';
    }

    protected function getEditableConfigNames() {
	return ['better_messages.settings'];
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
	$config = $this->config('better_messages.settings');

	$settings = $config->get();

	$form['position'] = array(
	    '#type' => 'details',
	    '#title' => t('Messages positions and basic properties'),
	    '#weight' => -5,
	    '#open' => TRUE
	);

	$form['position']['pos'] = array(
	    '#type' => 'radios',
	    '#title' => t('Set position of Message'),
	    '#default_value' => $settings['position'],
	    '#description' => t('Position of message relative to screen'),
	    '#attributes' => array('class' => array('better-messages-admin-radios')),
	    '#options' => array(
		'center' => t('Center screen'),
		'tl' => t('Top left'),
		'tr' => t('Top right'),
		'bl' => t('Bottom left'),
		'br' => t('Bottom right')
	    ),
	);

	$form['position']['fixed'] = array(
	    '#type' => 'checkbox',
	    '#default_value' => $settings['fixed'],
	    '#title' => t('Keep fixed position of message as you scroll.'),
	);

	$form['position']['width'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Custom width'),
	    '#description' => t('Width in pixel. Example: 400px<br />Or percentage. Example: 100%'),
	    '#default_value' => $settings['width'],
	    '#size' => 20,
	    '#maxlength' => 20,
	    '#required' => TRUE
	);

	$form['position']['horizontal'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Left/Right spacing'),
	    '#description' => t('In active when position is set to "center".<br />In pixel. Example: 10'),
	    '#default_value' => $settings['horizontal'],
	    '#size' => 20,
	    '#maxlength' => 20,
	    '#required' => TRUE,
	);

	$form['position']['vertical'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Top/Down spacing'),
	    '#description' => t('Inactive when position is set to "center".<br />In pixel. Example: 10'),
	    '#default_value' => $settings['vertical'],
	    '#size' => 20,
	    '#maxlength' => 20,
	    '#required' => TRUE,
	);

	$form['animation'] = array(
	    '#type' => 'details',
	    '#title' => t('Messages animation settings'),
	    '#weight' => -3,
	    '#open' => TRUE
	);

	$form['animation']['popin_effect'] = array(
	    '#type' => 'select',
	    '#title' => t('Pop-in (show) message box effect'),
	    '#default_value' => $settings['popin']['effect'],
	    '#options' => array(
		'fadeIn' => t('Fade in'),
		'slideDown' => t('Slide down'),
	    ),
	);

	$form['animation']['popin_duration'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Duration of (show) effect'),
	    '#description' => t('A string representing one of the three predefined speeds ("slow", "normal", or "fast").<br />Or the number of milliseconds to run the animation (e.g. 1000).'),
	    '#default_value' => $settings['popin']['duration'],
	    '#size' => 20,
	    '#maxlength' => 20,
	);

	$form['animation']['popout_effect'] = array(
	    '#type' => 'select',
	    '#title' => t('Pop-out (close) message box effect'),
	    '#default_value' => $settings['popout']['effect'],
	    '#options' => array(
		'fadeIn' => t('Fade out'),
		'slideUp' => t('Slide Up'),
	    ),
	);

	$form['animation']['popout_duration'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Duration of (close) effect'),
	    '#description' => t('A string representing one of the three predefined speeds ("slow", "normal", or "fast").<br />Or the number of milliseconds to run the animation (e.g. 1000).'),
	    '#default_value' => $settings['popout']['duration'],
	    '#size' => 20,
	    '#maxlength' => 20,
	);

	$form['animation']['autoclose'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Number of seconds to auto close after the page has loaded'),
	    '#description' => t('0 for never. You can set it as 0.25 for quarter second'),
	    '#default_value' => $settings['autoclose'],
	    '#size' => 20,
	    '#maxlength' => 20,
	);

	$form['animation']['disable_autoclose'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Disable auto close if messages inculde an error message'),
	    '#default_value' => $settings['disable_autoclose'],
	);

	$form['animation']['show_countdown'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Show countdown timer'),
	    '#default_value' => $settings['show_countdown'],
	);

	$form['animation']['hover_autoclose'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Stop auto close timer when hover'),
	    '#default_value' => $settings['hover_autoclose'],
	);

	$form['animation']['open_delay'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Number of seconds to delay message after the page has loaded'),
	    '#description' => t('0 for never. You can set it as 0.25 for quarter second'),
	    '#default_value' => $settings['opendelay'],
	    '#size' => 20,
	    '#maxlength' => 20,
	);

	$form['jquery_ui'] = array(
	    '#type' => 'details',
	    '#title' => t('jQuery UI enhancements'),
	    '#weight' => 10,
	    '#description' => t('These settings require !jquery_ui'),
	    '#open' => TRUE
	);

	$form['jquery_ui']['draggable'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Make Better Messages draggable'),
	    '#default_value' => $settings['jquery_ui']['draggable'],
	);
	$form['jquery_ui']['resizable'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Make Better Messages resizable'),
	    '#default_value' => $settings['jquery_ui']['resizable'],
	);

	$form['extra'] = array(
	    '#type' => 'details',
	    '#title' => t('Better Messages visibility'),
	    '#description' => t('Changes in this section will apply only after cache clearing'),
	    '#weight' => 0,
	    '#open' => TRUE
	);

	$form['extra']['admin'] = array(
	    '#type' => 'checkbox',
	    '#title' => t('Use Better Messages popup for the admin user (UID 1)'),
	    '#default_value' => $settings['extra']['admin']
	);

	$options = array(t('Show on every page except the listed pages.'), t('Show on only the listed pages.'));
	$description = t("Enter one page per line as Drupal paths. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", array('%blog' => 'blog', '%blog-wildcard' => 'blog/*', '%front' => '<front>'));

	$form['extra']['visibility'] = array(
	    '#type' => 'radios',
	    '#title' => t('Show Better Messages on specific pages'),
	    '#options' => $options,
	    '#default_value' => $settings['extra']['visibility'],
	);

	$form['extra']['pages'] = array(
	    '#type' => 'textarea',
	    '#title' => t('Pages'),
	    '#default_value' => $settings['extra']['pages'],
	    '#description' => $description,
	);





	return parent::buildForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
	$this->config('better_messages.settings')
		->set('position', $form_state->getValue('pos'))
		->set('fixed', $form_state->getValue('fixed'))
		->set('width', $form_state->getValue('width'))
		->set('horizontal', $form_state->getValue('horizontal'))
		->set('popin.effect', $form_state->getValue('popin_effect'))
		->set('popin.duration', $form_state->getValue('popin_duration'))
		->set('popout.effect', $form_state->getValue('popout_effect'))
		->set('popout.duration', $form_state->getValue('popout_duration'))
		->set('autoclose', $form_state->getValue('autoclose'))
		->set('disable_autoclose', $form_state->getValue('disable_autoclose'))
		->set('show_countdown', $form_state->getValue('show_countdown'))
		->set('hover_autoclose', $form_state->getValue('hover_autoclose'))
		->set('opendelay', $form_state->getValue('open_delay'))
		->set('jquery_ui.draggable', $form_state->getValue('draggable'))
		->set('jquery_ui.resizable', $form_state->getValue('resizable'))
		->set('extra.admin', $form_state->getValue('admin'))
		->set('extra.visibility', $form_state->getValue('visibility'))
		->set('extra.pages', $form_state->getValue('pages'))
		->save();


	parent::submitForm($form, $form_state);
    }

}

