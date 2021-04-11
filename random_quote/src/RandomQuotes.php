<?php

namespace Drupal\random_quote;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;

/**
 * Service to provide random quote.
 */
Class RandomQuotes implements RandomQuotesInterface {

  /**
   * Https client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Constructs the random quote service.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   Client interface.
   */
  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuote() {
    $request = $this->client->get('https://goquotes-api.herokuapp.com/api/v1/random?count=1');
    $body_array = Json::decode($request->getBody());
    return $body_array['quotes'][0]['text'];
  }

}
