<?php

namespace Assegai\Forms\Enumerations;

/**
 * @enum string TextInputType The type of text input to use.
 */
enum TextInputType: string
{
  case TEXT = 'text';
  case DATE = 'date';
  case DATE_TIME_LOCAL = 'datetime-local';
  case EMAIL = 'email';
  case PASSWORD = 'password';
  case URL = 'url';
  case SEARCH = 'search';
}