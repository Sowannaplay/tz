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
   * Constructs the statistics storage.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection for the node view storage.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function recordView($id, $user, $time) {
    return (bool) $this->connection
      ->merge('node_counter')
      ->key('nid', $id)
      ->fields([
        'daycount' => 1,
        'totalcount' => 1,
        'timestamp' => $time,
        'last_user' => !empty($user) ? $user : 'Anonymous',
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

}
