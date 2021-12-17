<?php

namespace Drupal\general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\general\Services\CustomService;

/**
 * Provides a 'General' Block.
 *
 * @Block(
 *   id = "general_block",
 *   admin_label = @Translation("General block"),
 *   category = @Translation("General Block"),
 * )
 */
class GeneralBlock extends BlockBase implements ContainerFactoryPluginInterface {
  
   /**
   * @var $customservice \Drupal\general\Services\CustomService
   */
  protected $customservice;
  
    /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('customservice')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\general\Services\CustomService $custom_service
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $custom_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->customservice = $custom_service;
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
	  
	$username= $this->customservice->getUserName();  
	$userroles = $this->customservice->getUserRole();
    return [
      '#theme' => 'general_block',
      '#data' => ['name' => $username],
	  '#attached' => [
         'library' => ['general/general'],
		 'drupalSettings' => [
		    'general' => ['role' => $userroles]
		 ],
       ],
    ];
  }

}