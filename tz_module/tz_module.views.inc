<?php

/**
 * @file
 * Provide views data for statistics.module.
 */

/**
 * Implements hook_views_data().
 */
function statistics_views_data() {
  $data['node_counter']['table']['group'] = t('Content statistics');

  $data['node_counter']['table']['join'] = [
    'node_field_data' => [
      'left_field' => 'nid',
      'field' => 'nid',
    ],
  ];

  $data['node_counter']['totalcount'] = [
    'title' => t('Total views'),
    'help' => t('The total number of times the node has been viewed.'),
    'field' => [
      'id' => 'statistics_numeric',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',

    ],
  ];

  $data['node_counter']['daycount'] = [
    'title' => t('Views today'),
    'help' => t('The total number of times the node has been viewed today.'),
    'field' => [
      'id' => 'statistics_numeric',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['node_counter']['timestamp'] = [
    'title' => t('Most recent view'),
    'help' => t('The most recent time the node has been viewed.'),
    'field' => [
      'id' => 'node_counter_timestamp',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'date',
    ],
    'argument' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['node_counter']['last_user'] = [
    'title' => t('Most recent user'),
    'help' => t('The most recent user to check the node'),
    'field' => [
      'id' => 'node_counter_last_user',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'string',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  return $data;
}
