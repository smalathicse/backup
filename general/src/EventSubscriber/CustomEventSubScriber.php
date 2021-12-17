<?php

/**
 * @file
 * Contains \Drupal\general\CustomEventSubScriber.
 */
 
 namespace Drupal\general\EventSubscriber;
 
 
 use Drupal\Core\Config\ConfigCrudEvent;
 use Drupal\Core\Config\ConfigEvents;
 use Drupal\general\CustomEvent;
 use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 
 
 class CustomEventSubScriber implements EventSubscriberInterface {
 
     public static function getSubscribedEvents() {
	   $events[configEvents::SAVE][] = array('onSavingConfig', 800);
	   $events[CustomEvent::SUBMIT][] = array('customAction', 800);
	   return $events;
	 }
	 /**
	   * Subscriber Callback for the event.
	   * @param CustomEvent $event
	   */
	  public function customAction(CustomEvent $event) {
		drupal_set_message("The Custom Event has been subscribed, which has be dispatched on submit of the article with " . $event->getReferenceID());
	  }

	  /**
	   * Subscriber Callback for the event.
	   * @param ConfigCrudEvent $event
	   */
	  public function onSavingConfig(ConfigCrudEvent $event) {
		drupal_set_message("You have saved a configuration of " . $event->getConfig()->getName());
	  }
 }
 
 
 