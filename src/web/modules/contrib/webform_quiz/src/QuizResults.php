<?php

namespace Drupal\webform_quiz;

use Drupal\webform\Entity\WebformSubmission;
use Drupal\Core\Render\Element;

/**
 * Quiz results class definition.
 */
class QuizResults {

  /**
   * {@inheritdoc}
   */
  protected $webformSubmission;

  /**
   * {@inheritdoc}
   */
  protected $numberOfPointsReceived;

  /**
   * {@inheritdoc}
   */
  protected $numberOfPointsAvailable;

  /**
   * {@inheritdoc}
   */
  protected $percentageCorrect;

  /**
   * QuizResults constructor.
   *
   * @param \Drupal\webform\Entity\WebformSubmission $webformSubmission
   *   WebformSubmission entity object.
   */
  public function __construct(WebformSubmission $webformSubmission) {
    $this->webformSubmission = $webformSubmission;
    $this->processResults();
  }

  /**
   * {@inheritdoc}
   */
  public static function compareAnswer($answer, $correct_answer, $strict = TRUE, $hits = [], $missed_answers = [], $missed_correct_answers = []) {
    $answer = empty($answer) ? [] : $answer;
    $answer = is_array($answer) ? $answer : [$answer];

    $correct_answer = empty($correct_answer) ? [] : $correct_answer;
    $correct_answer = is_array($correct_answer) ? $correct_answer : [$correct_answer];

    $ca_used = [];

    // Loop answers.
    foreach ($answer as $a) {
      if (!is_scalar($a)) {
        throw new \RuntimeException("Answer is of wrong type " . gettype($a));
      }

      $hit = FALSE;
      foreach ($correct_answer as $ca_idx => $ca) {
        if (!is_scalar($ca)) {
          throw new \RuntimeException("Correct answer is of wrong type " . gettype($ca));
        }
        if ($a == $ca) {
          $hit = TRUE;
          $ca_used[] = $ca_idx;
          break;
        }
      }

      if ($hit) {
        $hits[] = $a;
      }
      else {
        $missed_answers[] = $a;
      }
    }

    // Loop correct answers.
    foreach ($correct_answer as $ca_idx => $ca) {
      if (array_search($ca_idx, $ca_used) !== FALSE) {
        continue;
      }

      if (!is_scalar($ca)) {
        throw new \RuntimeException("Answer is of wrong type " . gettype($ca));
      }

      $missed_correct_answers[] = $ca;
    }

    $res = count($hits);
    if ($strict) {
      return count($missed_correct_answers) + count($missed_answers) > 0 ? 0 : $hits;
    }
    return $res < 0 ? 0 : $res;
  }

  /**
   * {@inheritdoc}
   */
  protected function processElementWithChildren($webform, $webform_submission, &$submission_data, $element_key, &$element, &$number_of_available_points, &$number_of_points_received) {
    if (isset($submission_data[$element_key]) && isset($element['#correct_answer'])) {
      $user_choice = $submission_data[$element_key];
      $element_manager = \Drupal::service('plugin.manager.webform.element');

      $c_answer = $element['#correct_answer'];
      $element_plugin = NULL;
      if (isset($element['#type'])) {
        // Load the element's handler.
        $element_plugin = $element_manager->getElementInstance($element, $webform);
        $element_plugin->setWebformSubmission($webform_submission);
        if (method_exists($element_plugin, 'prepareValueForCompare')) {
          $user_choice = $element_plugin->prepareValueForCompare($user_choice);
          $c_answer = $element_plugin->prepareValueForCompare($c_answer);
        }
      }

      $number_of_available_points++;
      if (self::compareAnswer($user_choice, $c_answer, $element_plugin->isStrictCompare()) > 0) {
        // This indicates that the user answered the question correctly.
        $number_of_points_received++;
      }
    }

    foreach (Element::children($element) as $subelement_key) {
      $this->processElementWithChildren($webform, $webform_submission, $submission_data, $subelement_key, $element[$subelement_key], $number_of_available_points, $number_of_points_received);
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function processResults() {
    $webform_submission = $this->webformSubmission;
    $webform = $webform_submission->getWebform();
    $elements = $webform->getElementsInitialized();

    $submission_data = $webform_submission->getData();

    $number_of_points_received = 0;
    $number_of_available_points = 0;

    foreach ($elements as $element_key => $element) {
      $this->processElementWithChildren($webform, $webform_submission, $submission_data, $element_key, $element, $number_of_available_points, $number_of_points_received);
    }

    $this->numberOfPointsReceived = $number_of_points_received;
    $this->numberOfPointsAvailable = $number_of_available_points;
    $this->percentageCorrect = $number_of_available_points > 0 ? ($number_of_points_received / $number_of_available_points) * 100 : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfPointsReceived() {
    return $this->numberOfPointsReceived;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfPointsAvailable() {
    return $this->numberOfPointsAvailable;
  }

  /**
   * {@inheritdoc}
   */
  public function getPercentageCorrect() {
    return $this->percentageCorrect;
  }

}
