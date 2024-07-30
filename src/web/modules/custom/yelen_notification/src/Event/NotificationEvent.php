<?php


namespace Drupal\yelen_notification\Event;


use Drupal\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
  const CREATION_CONTENT_PUBLIC = "creation_content_public";

  private string $node_id;
  private string $node_title;
  private string $node_content_type;
  private array $email_address;

  /**
   * @return string
   */
  public function getNodeId(): string
  {
    return $this->node_id;
  }

  /**
   * @param string $node_id
   */
  public function setNodeId(string $node_id): void
  {
    $this->node_id = $node_id;
  }

  /**
   * @return string
   */
  public function getNodeTitle(): string
  {
    return $this->node_title;
  }

  /**
   * @param string $node_title
   */
  public function setNodeTitle(string $node_title): void
  {
    $this->node_title = $node_title;
  }

  /**
   * @return string
   */
  public function getNodeContentType(): string
  {
    return $this->node_content_type;
  }

  /**
   * @param string $node_content_type
   */
  public function setNodeContentType(string $node_content_type): void
  {
    $this->node_content_type = $node_content_type;
  }

  /**
   * @return string
   */
  public function getEmailAddress(): string
  {
    return implode(', ',$this->email_address);
  }

  /**
   * @param array $email_address
   */
  public function setEmailAddress(array $email_address): void
  {
    $this->email_address = $email_address;
  }

}
