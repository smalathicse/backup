<?php

namespace Drupal\general;

use Symfony\Component\EventDispatcher\Event;

class CustomEvent extends Event {

  const SUBMIT  = 'event.submit';
  protected $referenceID;
  
  public function __construct($referenceID) 
  {
     $this->referenceID = $referenceID;
  }
  
  public function getReferenceID() 
  {
     return $this->referenceID;
  }
  
  public function myEventDescription() {
      return "This is my first custom event description.";
  }
}