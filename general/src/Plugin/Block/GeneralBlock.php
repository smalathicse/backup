<?php

namespace Drupal\general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\general\Services\CustomService;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Cache\Cache;

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
   * @var customservice\Drupal\general\Services\CustomService
   */
  protected $customservice;
  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Class ContainerInterface.
   * @param array $configuration
   *   The Configuration.
   * @param string $plugin_id
   *   The Plugin ID.
   * @param mixed $plugin_definition
   *   The Plugin Definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('customservice'),
      $container->get('config.factory')
    );
  }

  /**
   * @param array $configuration
   *   The Configuration.
   * @param string $plugin_id
   *   The Plugin ID.
   * @param mixed $plugin_definition
   *   The Plugin Definition.
   * @param \Drupal\general\Services\CustomService $custom_service
   *   Class CustomService.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $custom_service, ConfigFactory $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->customservice = $custom_service;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $username = $this->customservice->getUserName();
    $userroles = $this->customservice->getUserRole();
    
    $config = $this->configFactory->get('general_config.settings');
    $config_title = $config->get('config_title');

    \Drupal::service('module_handler')->invoke('general_node_title', [$username]);
    \Drupal::service('module_handler')->alter('general_node_title', $username);
    
    return [
      '#theme' => 'general_block',
      '#data' => ['name' => $username],
      '#config' => $config_title,
      '#attached' => [
        'library' => ['general/general'],
        'drupalSettings' => [
          'general' => ['role' => $userroles],
        ],
      ]
    ];
  }
  
   public function getCacheContexts() {
    // Vary caching of this block per user.
     return Cache::mergeContexts(parent::getCacheContexts(), ['user']);
   }

}
