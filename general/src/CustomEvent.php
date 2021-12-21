<?php

namespace Drupal\general;

use Symfony\Component\EventDispatcher\Event;

/**
 * Custom Event Creation.
 */
class CustomEvent extends Event {

  const SUBMIT = 'event.submit';
  protected $referenceID;

  /**
   * Passing Node ID.
   */
  public function __construct($referenceID) {
    $this->referenceID = $referenceID;
  }

  /**
   * ReferenceID.
   */
  public function getReferenceId() {
    return $this->referenceID;
  }

  /**
   * Custom Event Function.
   */
  public function myEventDescription() {
    return "This is my first custom event description.";
  }

}
