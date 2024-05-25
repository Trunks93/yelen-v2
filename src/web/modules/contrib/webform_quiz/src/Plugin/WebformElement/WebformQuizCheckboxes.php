<?php

namespace Drupal\webform_quiz\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\Checkboxes;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform_quiz\QuizWebformElementBase;

/**
 * Provides a 'webform_quiz_checkboxes' element.
 *
 * @WebformElement(
 *   id = "webform_quiz_checkboxes",
 *   api = "https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!Element!Checkboxes.php/class/Checkboxes",
 *   label = @Translation("Webform Quiz Checkboxes"),
 *   description = @Translation("Provides a form element for a set of checkboxes with correct answers provided."),
 *   category = @Translation("Webform Quiz"),
 * )
 */
class WebformQuizCheckboxes extends Checkboxes {
  use QuizWebformElementBase;

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    // This addresses an issue where the webform_quiz_checkboxes element was not
    // appearing in the webform.
    $this->traitPrepare($element, $webform_submission, 'checkboxes');

  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['options']['options']['#type'] = 'webform_quiz_webform_element_options';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return $this->traitBuildConfigurationForm($form, $form_state);
  }

}
