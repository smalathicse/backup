<?php

namespace Drupal\general\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;

/**
 * Provides a 'CacheTags' Block.
 *
 * @Block(
 *   id = "cache_tags_block",
 *   admin_label = @Translation("Cache Tags block"),
 *   category = @Translation("Cache Tags Block"),
 * )
 */
class CacheTagsBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param array $configuration
   *   The Configuration.
   * @param string $plugin_id
   *   The Plugin ID.
   * @param mixed $plugin_definition
   *   The Plugin Definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // To get Latest article content Id.
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'article');
    $query->sort('created', 'desc');
    $query->range(0, 1);
    $entity_ids = $query->execute();
    $nodeId = current($entity_ids);

    $content = '';
    $nodetitle = '';
    $nodeUrl = '';
    if (!empty($entity_ids)) {
      $content = $this->entityTypeManager->getStorage('node')->load($nodeId);
      if ($content) {
        $nodetitle = $content->getTitle();
        $options = ['absolute' => TRUE];
        $url = Url::fromRoute('entity.node.canonical', ['node' => $nodeId], $options);
        $nodeUrl = $url->toString();
      }
    }

    return [
      '#theme' => 'cache_tags_block',
      '#data' => [$nodeUrl => $nodetitle],
      '#cache' => [
        'tags' => ['node_list:article'],
      ],
    ];
  }

}
