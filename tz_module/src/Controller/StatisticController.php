<?php

namespace Drupal\tz_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\tz_module\NodeStatsInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StatisticController
 * @package Drupal\tz_module\Controller
 */
class StatisticController extends ControllerBase {

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
  public function __construct(NodeStatsInterface $node_stats, RequestStack $request_stack) {
    $this->nodeStats = $node_stats;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tz_module.stats'),
      $container->get('request_stack')
    );
  }

  /**
   * Recieves ajax request to add the count.
   */
  public function invokeViewRecord() {
    $nid = $this->requestStack->getCurrentRequest()->get('nid');
    $user = $this->requestStack->getCurrentRequest()->get('user');
    $time = $this->requestStack->getCurrentRequest()->server->get('REQUEST_TIME');
    $this->nodeStats->recordView($nid, $user, $time);
    return new JsonResponse([$nid, $user, $time]);
  }

  /**
   * Returns content of a block to avoid caching for anonymous.
   */
  public function statisticBlockContent() {
    $nid = $this->requestStack->getCurrentRequest()->get('nid');
    $statistics_array = $this->nodeStats->getStatistics($nid);
    $total = $statistics_array->totalcount;
    $daily = $statistics_array->daycount;
    $last_user = $statistics_array->last_user;
    $time = date('d.m.Y H:i:s', $statistics_array->timestamp);
    $result = ["Total views: $total, Daily views: $daily </br> Last viewed by: $last_user at $time"];
    return new JsonResponse($result);
  }

}
