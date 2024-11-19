<?php


namespace Drupal\yelen_notification\Event;


use Drupal\webform\Plugin\WebformElement\DateTime;
use function DrupalCodeGenerator\InputOutput\title;

class QuizNotificationEvent
{
  const PUBLISH_QUIZ = "publish_quiz";

  private string $quiz_id;
  private string $quiz_title;
  private array $email_address;
  private string|null $beginDate;
  private string|null $endDate;
  private string|null $description;

  public function __construct($id, $title, $emails, $date = [])
  {
    $this->quiz_id = $id;
    $this->quiz_title = $title;
    $this->email_address = $emails;
    $this->beginDate = !empty($date) ? $date['value'] : null;
    $this->endDate = !empty($date) ? $date['end_value'] : null;
  }

  /**
   * @return string|Int
   */
  public function getQuizId(): string|int
  {
    return $this->quiz_id;
  }

  /**
   * @param string $quiz_id
   */
  public function setQuizId(string $quiz_id): void
  {
    $this->quiz_id = $quiz_id;
  }

  /**
   * @return string
   */
  public function getQuizTitle(): string
  {
    return $this->quiz_title;
  }

  /**
   * @param string $quiz_title
   */
  public function setQuizTitle(string $quiz_title): void
  {
    $this->quiz_title = $quiz_title;
  }

  /**
   * @return array
   */
  public function getEmailAddress(): array
  {
    return $this->email_address;
  }

  /**
   * @param array $email_address
   */
  public function setEmailAddress(array $email_address): void
  {
    $this->email_address = $email_address;
  }

  /**
   * @return mixed|string
   */
  public function getBeginDate(): mixed
  {
    if ($this->beginDate !== null) {
      return new \DateTime($this->beginDate);
    }
    return null;

  }

  /**
   * @param mixed|string $beginDate
   */
  public function setBeginDate(mixed $beginDate): void
  {
    $this->beginDate = $beginDate;
  }

  /**
   * @return mixed|string
   */
  public function getEndDate(): mixed
  {
    if ($this->endDate !== null) {
      return new \DateTime($this->endDate);
    }
    return null;
  }

  /**
   * @param mixed|string $endDate
   */
  public function setEndDate(mixed $endDate): void
  {
    $this->endDate = $endDate;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description ?? "<span> N/A </span>";
  }

  /**
   * @param string|null $description
   */
  public function setDescription(string|null $description): void
  {
    $this->description = $description;
  }


}
