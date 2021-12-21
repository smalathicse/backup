<?php

namespace Drupal\general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\general\Services\CustomService;
use Drupal\core\Cache\Cache;

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
      $container->get('customservice')
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $custom_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->customservice = $custom_service;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $username = $this->customservice->getUserName();
    $userroles = $this->customservice->getUserRole();

    \Drupal::service('module_handler')->invokeAll('general_node_title', [$username]);
    \Drupal::service('module_handler')->alter('general_node_title', $username);

    return [
      '#theme' => 'general_block',
      '#data' => ['name' => $username],
      '#attached' => [
        'library' => ['general/general'],
        'drupalSettings' => [
          'general' => ['role' => $userroles],
        ],
      ],
	  '#cache' => [
        'contexts' => ['user.roles:anonymous'],
      ],
    ];
  }

}
