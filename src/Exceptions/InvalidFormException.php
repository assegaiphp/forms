<?php

namespace Assegai\Forms\Exceptions;

use Assegai\Forms\Exceptions\FormException;

/**
 * Class InvalidFormException
 * @package Assegai\Forms\Exceptions
 */
class InvalidFormException extends FormException
{
  /**
   * InvalidFormException constructor.
   * @param string $message
   */
  public function __construct(string $message = 'The form is invalid.')
  {
    parent::__construct($message);
  }
}