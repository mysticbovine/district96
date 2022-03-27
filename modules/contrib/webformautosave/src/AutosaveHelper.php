<?php

namespace Drupal\webformautosave;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform_submission_log\WebformSubmissionLogManager;

/**
 * A helper class that houses helper functions for the webformautosave module.
 *
 * @package Drupal\webformautosave
 */
class AutosaveHelper {

  /**
   * The webform submission logger.
   *
   * @var \Drupal\webform_submission_log\WebformSubmissionLogManager
   *   The webform_submission log manager
   */
  protected $webformSubmissionLogManager;

  /**
   * AutosaveHelper constructor.
   *
   * @param \Drupal\webform_submission_log\WebformSubmissionLogManager $webform_submission_log_manager
   *   The webform_submission log manager.
   */
  public function __construct(WebformSubmissionLogManager $webform_submission_log_manager) {
    $this->webformSubmissionLogManager = $webform_submission_log_manager;
  }

  /**
   * Getter for the most recent submission url.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   A webform_submission.
   *
   * @return \Drupal\Core\Url
   *   The url for the most recent submission.
   */
  public function getSubmissionUrl(WebformSubmissionInterface $webform_submission) {
    $submission_url = $webform_submission->getTokenUrl();
    $submission_url->setAbsolute(FALSE);
    $current_params = (array) \Drupal::request()->query->all();
    // Add the current params to the submission url.
    foreach ($current_params as $key => $param) {
      $submission_url->setRouteParameter($key, $param);
    }
    return $submission_url;
  }

  /**
   * Getter for the most recent submission log.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   A webform_submission.
   *
   * @return object
   *   The most recent logged submission.
   */
  public function getCurrentSubmissionLog(WebformSubmissionInterface $webform_submission) {
    // Get the submission logs.
    $submission_log_query = $this->webformSubmissionLogManager->getQuery($webform_submission);
    $submission_log_query->orderBy('timestamp', 'DESC');
    $submission_log_query->range(0, 1);
    $submission_log = $submission_log_query->execute()->fetchObject();

    // Clean and return the record if available.
    if (!empty($submission_log)) {
      $submission_log->variables = unserialize($submission_log->variables);
      $submission_log->data = unserialize($submission_log->data);
      return $submission_log;
    }

    // Return a vanilla log with the current timestamp and UID if we get here.
    $now = new DrupalDateTime();
    $log_data = [
      'webform_id' => $webform_submission->getWebform()->id(),
      'sid' => $webform_submission->id(),
      'uid' => \Drupal::currentUser()->id(),
      'message' => 'initial log by webform_autosave',
      'timestamp' => $now->getTimestamp(),
    ];
    $this->webformSubmissionLogManager->insert($log_data);
    return (object) $log_data;
  }

  /**
   * Getter for all the fields on the current page.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   A webform_submission.
   *
   * @return array|bool
   *   All the elements on the current page or FALSE if we don't find any.
   */
  public function getCurrentFields(WebformSubmissionInterface $webform_submission) {
    $webform = $webform_submission->getWebform();
    $elements = $webform->getElementsInitializedAndFlattened();
    $is_wizzard = $webform->hasWizardPages();
    if ($is_wizzard && is_array($elements)) {
      $current_page = $webform_submission->getCurrentPage();
      return $elements[$current_page]['#webform_children'];
    }
    elseif (is_array($elements)) {
      return $elements;
    }
    return FALSE;
  }

  /**
   * Getter for the first field on the current page.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   A webform_submission.
   *
   * @return string|bool
   *   Returns the first field on the current page or FALSE if it is empty.
   */
  public function getFirstWebformField(WebformSubmissionInterface $webform_submission) {
    $elements = $this->getCurrentFields($webform_submission);
    if (!empty($elements) && is_array($elements)) {
      return key($elements);
    }
    return FALSE;
  }

}
