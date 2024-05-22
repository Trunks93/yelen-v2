<?php

namespace Drupal\webform_quiz;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Quiz Webform Element Base trait class.
 */
trait QuizWebformElementBase {

  /**
   * {@inheritdoc}
   */
  public function isStrictCompare() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    $properties = [
      'correct_answer' => [],
      'correct_answer_description' => '',
    ] + parent::defineDefaultProperties() + parent::defineDefaultMultipleProperties();
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
          // Form display.
      'correct_answer' => [],
      'correct_answer_description' => '',
    ] + parent::getDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function traitBuildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $storage = $form_state->getStorage();
    $element_properties = $storage['element_properties'];

    // Modify the existing element description to distinguish it from the
    // correct answer description.
    $form['element_description']['description']['#title'] = $this->t('Element Description');

    // Add a WYSIWYG for the correct answer description.
    $form['element_description']['correct_answer_description'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Correct Answer Description'),
      '#description' => $this->t('A description of why the correct answer is correct.'),
      '#default_value' => $element_properties['correct_answer_description'] ?? '',
      '#weight' => 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function traitPrepare(array &$element, WebformSubmissionInterface $webform_submission = NULL, $type = NULL, $call_parent = TRUE) {
    if (!empty($type)) {
      $element['#type'] = $type;
    }
    $element['#attributes']['quiz-element'] = '';

    $correct_answer_description_wrapper = [
      '#type' => 'container',
      '#attributes' => ['id' => 'cad-wrapper-' . $element['#webform_id']],
    ];
    $element['#suffix'] = \Drupal::service('renderer')->render($correct_answer_description_wrapper);

    if (!empty($element['#correct_answer_description'])) {
      $element['#attributes']['quiz-has-cad'] = '';

      $element['#ajax'] = [
        'callback' => 'Drupal\webform_quiz\Plugin\WebformElement\WebformQuizRadios::ajaxShowCorrectAnswerDescription',
        'event' => 'show_answer',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ];
    }

    if ($call_parent) {
      parent::prepare($element, $webform_submission);
    }

  }

  /**
   * Ajax handler to show the correct description when user clicks an option.
   *
   * @param array $form
   *   Submited form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   FormState object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The AjaxResponse.
   */
  public static function ajaxShowCorrectAnswerDescription(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();

    $triggering_element = $form_state->getTriggeringElement();
    $element_key = $triggering_element['#name'];

    /** @var \Drupal\webform\WebformSubmissionForm $form_obj */
    $form_obj = $form_state->getFormObject();
    $webform = $form_obj->getWebform();
    $element = $webform->getElement($element_key);
    $count = 0;
    while (empty($element) && $count < count($triggering_element['#parents'])) {
      $element = $webform->getElement($triggering_element['#parents'][$count]);
      $count++;
    }
    $description = $element['#correct_answer_description'] ?? '';

    $answer = $form_state->getValue($element['#webform_key']);
    $answer = is_array($answer) ? $answer : [$answer];
    $c_answer = $element['#correct_answer'];
    $temp = [];
    foreach ($answer as $value) {
      if (!empty($value)) {
        $_key = (string) $value;
        $temp[$_key] = $_key;
      }
    }
    $answer = $temp;

    $element_manager = \Drupal::service('plugin.manager.webform.element');
    $element_plugin = $element_manager->getElementInstance($element, $webform);
    // $element_plugin->setWebformSubmission($webform_submission);
    if (method_exists($element_plugin, 'prepareValueForCompare')) {
      $answer = $element_plugin->prepareValueForCompare($answer);
      $c_answer = $element_plugin->prepareValueForCompare($c_answer);
    }

    $is_user_correct = FALSE;
    if (QuizResults::compareAnswer($answer, $c_answer, $element_plugin->isStrictCompare()) > 0) {
      $is_user_correct = TRUE;
    }

    $build['#type'] = 'container';
    $build['#attributes']['id'] = 'cad-wrapper-inner' . $element['#webform_id'];
    $build['description'] = [
      '#type' => 'webform_quiz_correct_answer_description',
      '#correct_answer' => $element['#correct_answer'],
      '#correct_answer_description' => $description,
      '#is_user_correct' => $is_user_correct,
      '#triggering_element' => $triggering_element,
    ];

    $ajax_response->addCommand(new HtmlCommand('#cad-wrapper-' . $element['#webform_id'], $build));

    // Allow other modules to add ajax commands.
    \Drupal::moduleHandler()->invokeAll(
      'webform_quiz_correct_answer_shown',
      [$ajax_response, $element, $form_state]
    );

    return $ajax_response;
  }

}
