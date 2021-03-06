<?php

namespace Drupal\tz_module;

/**
 * Provides an interface defining Statistics Storage.
 *
 * Stores the views per day, total views and timestamp of last view
 * for entities.
 */
interface NodeStatsInterface {

  /**
   * Counts an entity view.
   *
   * @param int $id
   *   The ID of the entity to count.
   * @param string $user
   *   Username of current user.
   * @param int $time
   *   Timestamp.
   *
   * @return bool
   *   TRUE if the entity view has been counted.
   */
  public function recordView($id, $user, $time);

  /**
   * Returns full statistics/
   *
   * @param int $id
   *   The ID of the entity to count.
   *
   * @return mixed
   *   Array of statistical information.
   */
  public function getStatistics($id);

}
