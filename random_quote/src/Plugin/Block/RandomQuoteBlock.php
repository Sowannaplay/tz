<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\random_quote\RandomQuotesInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides random quote block.
 *
 * @Block(
 *   id = "random_quote_block",
 *   admin_label = @Translation("Random quote block"),
 * )
 */
class RandomQuoteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\random_quote\RandomQuotesInterface
   */
  protected $randomQuote;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RandomQuotesInterface $random_quote) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->randomQuote = $random_quote;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('random_quote.quote_generator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#markup'] = $this->randomQuote->getQuote();
    return $build;
  }

  /**
   * Disables caching.
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
