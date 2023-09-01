<?php

namespace Assegai\Forms\Enumerations;

/**
 * @enum string HTMLInputType The type of HTML input to use.
 */
enum HTMLInputType: string
{
  case BUTTON = 'button';
  case CHECKBOX = 'checkbox';
  case COLOR = 'color';
  case DATE = 'date';
  case DATE_TIME_LOCAL = 'datetime-local';
  case EMAIL = 'email';
  case FILE = 'file';
  case HIDDEN = 'hidden';
  case IMAGE = 'image';
  case MONTH = 'month';
  case NUMBER = 'number';
  case PASSWORD = 'password';
  case RADIO = 'radio';
  case RANGE = 'range';
  case RESET = 'reset';
  case SEARCH = 'search';
  case SUBMIT = 'submit';
  case TEL = 'tel';
  case TEXT = 'text';
  case TIME = 'time';
  case URL = 'url';
  case WEEK = 'week';
}
