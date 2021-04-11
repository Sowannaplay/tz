<?php

namespace Drupal\random_quote;

/**
 * Provides an interface defining Random Quotes generator.
 */
interface RandomQuotesInterface {

  /**
   * Gets random quote from API.
   *
   * @return string
   */
  public  function getQuote();

}
