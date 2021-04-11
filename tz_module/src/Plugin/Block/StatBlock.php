<?php

namespace Drupal\tz_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\tz_module\NodeStatsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Node statistics block.
 *
 * @Block(
 *   id = "tz_module_stat_block",
 *   admin_label = @Translation("Statisics block"),
 * )
 */
class StatBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Node statistics class.
   *
   * @var \Drupal\tz_module\NodeStatsInterface
   */
  protected $nodeStats;

  /**
   * Request stack.
   *
   * @var RequestStack $requestStack;
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, NodeStatsInterface $node_stats, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStats = $node_stats;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tz_module.stats'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#markup'] = '<div class=statistics_information_block></div>';
    $build['#attached']['library'][] = 'tz_module/build_block';
    $settings = ['data' => ['nid' => $this->requestStack->getCurrentRequest()->get('node')->id()], 'url' => '/build_block'];
    $build['#attached']['drupalSettings']['statistic_block_info'] = $settings;
    return $build;
  }

  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('url'));
  }

}
