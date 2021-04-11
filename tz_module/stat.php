<?php

/**
 * @file
 * Handles counts of node views via AJAX.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

chdir('../../..');

$autoloader = require_once 'autoload.php';

$kernel = DrupalKernel::createFromRequest(Request::createFromGlobals(), $autoloader, 'prod');
$kernel->boot();
$container = $kernel->getContainer();
$nid = filter_input('INPUT_POST', 'nid', FILTER_VALIDATE_INT);
$user = filter_input('INPUT_POST', 'user');
  if ($nid) {
    $container->get('request_stack')->push(Request::createFromGlobals());
    $container->get('tz_module.stats')->recordView($nid, $user);
  }

