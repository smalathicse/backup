<?php

namespace Drupal\general\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\general\CustomEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class CustomEventSubScriber implements EventSubscriberInterface {

  /**
   *
   */
  public static function getSubscribedEvents() {
    $events[configEvents::SAVE][] = ['onSavingConfig'];
    $events[CustomEvent::SUBMIT][] = ['customAction'];
    return $events;
  }

  /**
   * Subscriber Callback for the event.
   *
   * @param \Drupal\general\CustomEvent $event
   */
  public function customAction(CustomEvent $event) {
    drupal_set_message("The Custom Event has been subscribed, which has be dispatched on submit of the article with " . $event->getReferenceId());
  }

  /**
   * Subscriber Callback for the event.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   */
  public function onSavingConfig(ConfigCrudEvent $event) {
    drupal_set_message("You have saved a configuration of " . $event->getConfig()->getName());
  }

}
