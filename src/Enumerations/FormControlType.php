<?php

namespace Assegai\Forms\Enumerations;

/**
 * @enum string FormControlType The type of form control to use.
 */
enum FormControlType: string
{
  case TEXT = 'text';
  case LONG_TEXT = 'long-text';
  case SELECT = 'select';
  case NUMERIC = 'numeric';

  public function getElementType(): FormControlElementType
  {
    return match ($this) {
      self::LONG_TEXT => FormControlElementType::TEXTAREA,
      self::SELECT => FormControlElementType::SELECT,
      default => FormControlElementType::INPUT,
    };
  }
}