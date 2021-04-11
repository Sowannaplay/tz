<?php

namespace Drupal\tz_module;

use Drupal\Core\Database\Connection;
use Drupal\Core\State\StateInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the default database storage backend for statistics.
 */
class NodeStats implements NodeStatsInterface {

  /**
  * The database connection used.
  *
  * @var \Drupal\Core\Database\Connection
  */
  protected $connection;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs the statistics storage.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection for the node view storage.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(Connection $connection, RequestStack $request_stack) {
    $this->connection = $connection;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function recordView($id, $user) {
    return (bool) $this->connection
      ->merge('node_counter')
      ->key('nid', $id)
      ->fields([
        'daycount' => 1,
        'totalcount' => 1,
        'timestamp' => $this->getRequestTime(),
        'last_user' => $user,
      ])
      ->expression('daycount', 'daycount + 1')
      ->expression('totalcount', 'totalcount + 1')
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function getStatistics($id) {
    $views = $this->connection
      ->select('node_counter', 'nc')
      ->fields('nc', ['totalcount', 'daycount', 'timestamp', 'last_user'])
      ->condition('nid', $id, 'IN')
      ->execute()
      ->fetchAll();

    return reset($views);
  }

  /**
   * Get current request time.
   *
   * @return int
   *   Unix timestamp for current server request time.
   */
  protected function getRequestTime() {
    return $this->requestStack->getCurrentRequest()->server->get('REQUEST_TIME');
  }

}
